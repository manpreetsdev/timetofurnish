<?php

namespace App\Http\Controllers\Api\V2\Seller;

use App\Http\Resources\V2\UploadedFileCollection;
use Illuminate\Http\Request;
use App\Models\Upload;
use Response;
use Auth;
use Storage;
use Image;

class SellerFileUploadController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->user_type == 'seller') {
            $all_uploads = Upload::where('user_id', auth()->user()->id);

            if ($request->search != null) {
                $all_uploads->where('file_original_name', 'like', '%' . $request->search . '%');
            }
            if ($request->type != null) {
                $all_uploads->where('type', $request->type);
            }

            switch ($request->sort) {
                case 'newest':
                    $all_uploads->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $all_uploads->orderBy('created_at', 'asc');
                    break;
                case 'smallest':
                    $all_uploads->orderBy('file_size', 'asc');
                    break;
                case 'largest':
                    $all_uploads->orderBy('file_size', 'desc');
                    break;
                default:
                    $all_uploads->orderBy('created_at', 'desc');
                    break;
            }


            $all_uploads = $all_uploads->paginate(30)->appends(request()->query());

            return new UploadedFileCollection($all_uploads);
        }

        return response()->json([
            "result" => false,
            "data" => []
        ]);
    }

    public function upload(Request $request)
    {
        if (auth()->user()->user_type == 'seller') {
            if (!$request->hasFile('aiz_file')) {
                return $this->failed(translate('Upload file is missing'));
            }

            $file = $request->file('aiz_file');
            $error_message = null;
            if (!validate_uploaded_file($file, $error_message)) {
                return $this->failed($error_message);
            }

            $type = get_configured_upload_types();
            $upload = new Upload;
            $extension = strtolower($file->getClientOriginalExtension());

            if (
                env('DEMO_MODE') == 'On' &&
                isset($type[$extension]) &&
                $type[$extension] == 'archive'
            ) {
                return $this->failed(translate('File has been inserted successfully'));
            }

            if (isset($type[$extension])) {
                $upload->file_original_name = null;
                $arr = explode('.', $file->getClientOriginalName());
                for ($i = 0; $i < count($arr) - 1; $i++) {
                    if ($i == 0) {
                        $upload->file_original_name .= $arr[$i];
                    } else {
                        $upload->file_original_name .= "." . $arr[$i];
                    }
                }

                $path = $file->store('uploads/all', 'local');
                $size = $file->getSize();

                // Return MIME type ala mimetype extension
                $finfo = finfo_open(FILEINFO_MIME_TYPE);

                // Get the MIME type of the file
                $file_mime = finfo_file($finfo, base_path('public/') . $path);

                if ($type[$extension] == 'image' && get_setting('disable_image_optimization') != 1) {
                    try {
                        $img = Image::make($file->getRealPath())->encode();
                        $height = $img->height();
                        $width = $img->width();
                        if ($width > $height && $width > 1500) {
                            $img->resize(1500, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        } elseif ($height > 1500) {
                            $img->resize(null, 800, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        }
                        $img->save(base_path('public/') . $path);
                        clearstatcache();
                        $size = $img->filesize();
                    } catch (\Exception $e) {
                        //dd($e);
                    }
                }

                if (env('FILESYSTEM_DRIVER') == 's3') {
                    Storage::disk('s3')->put(
                        $path,
                        file_get_contents(base_path('public/') . $path),
                        [
                            'visibility' => 'public',
                            'ContentType' =>  $extension == 'svg' ? 'image/svg+xml' : $file_mime
                        ]
                    );
                    if ($arr[0] != 'updates') {
                        unlink(base_path('public/') . $path);
                    }
                }

                $upload->extension = $extension;
                $upload->file_name = $path;
                $upload->user_id = Auth::user()->id;
                $upload->type = $type[$upload->extension];
                $upload->file_size = $size;
                $upload->save();
            }
            return $this->success(translate('File has been inserted successfully'));
        }
        return $this->failed(translate("You can't upload the file"));
    }

    public function destroy($id)
    {
        $upload = Upload::findOrFail($id);

        if (auth()->user()->user_type == 'seller' && $upload->user_id != auth()->user()->id) {
            return $this->failed(translate("You don't have permission for deleting this!"));
        }
        try {
            if (env('FILESYSTEM_DRIVER') == 's3') {
                Storage::disk('s3')->delete($upload->file_name);
                if (file_exists(public_path() . '/' . $upload->file_name)) {
                    unlink(public_path() . '/' . $upload->file_name);
                }
            } else {
                unlink(public_path() . '/' . $upload->file_name);
            }
            $upload->delete();
           return $this->success(translate('File deleted successfully'));
        } catch (\Exception $e) {
            $upload->delete();
            return $this->failed(translate('File deleted Failed'));
        }
        return $this->success(translate('File deleted successfully'));
    }

    public function bulk_uploaded_files_delete(Request $request)
    {
        if ($request->id) {
            foreach ($request->id as $file_id) {
                $this->destroy($file_id);
            }
            return 1;
        } else {
            return 0;
        }
    }

    public function get_preview_files(Request $request)
    {
        $ids = explode(',', $request->ids);
        $files = Upload::whereIn('id', $ids)->get();
        $new_file_array = [];
        foreach ($files as $file) {
            $file['file_name'] = my_asset($file->file_name);
            if ($file->external_link) {
                $file['file_name'] = $file->external_link;
            }
            $new_file_array[] = $file;
        }
        // dd($new_file_array);
        return $new_file_array;
        // return $files;
    }

    public function all_file()
    {
        $uploads = Upload::all();
        foreach ($uploads as $upload) {
            try {
                if (env('FILESYSTEM_DRIVER') == 's3') {
                    Storage::disk('s3')->delete($upload->file_name);
                    if (file_exists(public_path() . '/' . $upload->file_name)) {
                        unlink(public_path() . '/' . $upload->file_name);
                    }
                } else {
                    unlink(public_path() . '/' . $upload->file_name);
                }
                $upload->delete();
                flash(translate('File deleted successfully'))->success();
            } catch (\Exception $e) {
                $upload->delete();
                flash(translate('File deleted successfully'))->success();
            }
        }

        Upload::query()->truncate();

        return back();
    }

    //Download project attachment
    public function attachment_download($id)
    {
        $project_attachment = Upload::find($id);
        try {
            $file_path = public_path($project_attachment->file_name);
            return Response::download($file_path);
        } catch (\Exception $e) {
            flash(translate('File does not exist!'))->error();
            return back();
        }
    }
    //Download project attachment
    public function file_info(Request $request)
    {
        $file = Upload::findOrFail($request['id']);

        return (auth()->user()->user_type == 'seller')
            ? view('seller.uploads.info', compact('file'))
            : view('backend.uploaded_files.info', compact('file'));
    }
}
