<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upload;
use Response;
use Auth;
use Storage;
use Image;
use enshrined\svgSanitize\Sanitizer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AizUploadController extends Controller
{


    public function index(Request $request)
    {

        $all_uploads = (auth()->user()->user_type == 'seller') ? Upload::where('user_id', auth()->user()->id) : Upload::query();
        $search = null;
        $sort_by = null;

        if ($request->search != null) {
            $search = $request->search;
            $all_uploads->where('file_original_name', 'like', '%' . $request->search . '%');
        }

        $sort_by = $request->sort;
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

        $all_uploads = $all_uploads->paginate(60)->appends(request()->query());


        return (auth()->user()->user_type == 'seller')
            ? view('seller.uploads.index', compact('all_uploads', 'search', 'sort_by'))
            : view('backend.uploaded_files.index', compact('all_uploads', 'search', 'sort_by'));
    }

    public function create()
    {
        return (auth()->user()->user_type == 'seller')
            ? view('seller.uploads.create')
            : view('backend.uploaded_files.create');
    }


    public function show_uploader(Request $request)
    {
        return view('uploader.aiz-uploader');
    }
    public function upload(Request $request)
    {
        $files = $this->extractAizFiles($request);

        if (count($files) === 0) {
            return response()->json([
                'result' => false,
                'error' => translate('Upload file is missing'),
                'message' => translate('Upload file is missing'),
            ], 400);
        }

        if (count($files) === 1) {
            $response = $this->storeUploadedAizFile($files[0]);

            if (!$response['result']) {
                return response()->json([
                    'result' => false,
                    'error' => $response['message'],
                    'message' => $response['message'],
                ], $response['status']);
            }

            return response()->json($response['upload']);
        }

        $uploads = [];
        $errors = [];

        foreach ($files as $file) {
            $response = $this->storeUploadedAizFile($file);

            if ($response['result']) {
                $uploads[] = $response['upload'];
            } else {
                $errors[] = [
                    'file' => $file ? $file->getClientOriginalName() : null,
                    'message' => $response['message'],
                ];
            }
        }

        if (count($errors) > 0) {
            return response()->json([
                'result' => false,
                'message' => translate('One or more files could not be uploaded'),
                'uploads' => $uploads,
                'errors' => $errors,
            ], 422);
        }

        return response()->json([
            'result' => true,
            'message' => translate('Files uploaded successfully'),
            'uploads' => $uploads,
        ]);
    }

    private function extractAizFiles(Request $request): array
    {
        $files = $request->allFiles();
        $flatFiles = [];

        $flatten = function ($value) use (&$flatten, &$flatFiles) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    $flatten($item);
                }
                return;
            }

            if ($value) {
                $flatFiles[] = $value;
            }
        };

        foreach ($files as $file) {
            $flatten($file);
        }

        if (!empty($flatFiles)) {
            return $flatFiles;
        }

        if ($request->hasFile('aiz_file')) {
            $file = $request->file('aiz_file');
            return is_array($file) ? array_values(array_filter($file)) : [$file];
        }

        return [];
    }

    private function storeUploadedAizFile($file): array
    {
        $type = get_configured_upload_types();
        $extension = strtolower($file->getClientOriginalExtension());
        $originalName = $file->getClientOriginalName();

        if (!isset($type[$extension])) {
            return [
                'result' => false,
                'status' => 400,
                'message' => translate('The file extension ') . $extension . translate(' is not allowed.'),
            ];
        }

        $error_message = null;
        if (!validate_uploaded_file($file, $error_message)) {
            return [
                'result' => false,
                'status' => 400,
                'message' => $error_message,
            ];
        }

        if (
            env('DEMO_MODE') == 'On' &&
            isset($type[$extension]) &&
            $type[$extension] == 'archive'
        ) {
            return [
                'result' => true,
                'upload' => (object) [],
            ];
        }

        $upload = new Upload;
        $upload->file_original_name = null;
        $arr = explode('.', $originalName);
        for ($i = 0; $i < count($arr) - 1; $i++) {
            if ($i == 0) {
                $upload->file_original_name .= $arr[$i];
            } else {
                $upload->file_original_name .= "." . $arr[$i];
            }
        }

        $uploadDir = public_path('uploads/all');
        if (!is_dir($uploadDir) && !@mkdir($uploadDir, 0755, true) && !is_dir($uploadDir)) {
            return [
                'result' => false,
                'status' => 500,
                'message' => translate('Unable to create the upload directory.'),
            ];
        }

        $storedName = Str::random(40) . '.' . $extension;
        $path = 'uploads/all/' . $storedName;
        $fullPath = $uploadDir . '/' . $storedName;

        try {
            $file->move($uploadDir, $storedName);
            $size = filesize($fullPath) ?: $file->getSize();
        } catch (\Throwable $e) {
            Log::error('AIZ upload move failed', [
                'file' => $originalName,
                'error' => $e->getMessage(),
            ]);

            return [
                'result' => false,
                'status' => 500,
                'message' => translate('Unable to store the file on the server.') . ' ' . $e->getMessage(),
            ];
        }

        try {
            if ($extension === 'svg') {
                $sanitizer = new Sanitizer();
                $dirtySVG = file_get_contents($fullPath);
                $cleanSVG = $sanitizer->sanitize($dirtySVG);

                if ($cleanSVG === false || $cleanSVG === null) {
                    $this->removeLocalUploadFile($path);

                    return [
                        'result' => false,
                        'status' => 422,
                        'message' => translate('The SVG file is invalid or unsafe.'),
                    ];
                }

                file_put_contents($fullPath, $cleanSVG);
            } elseif ($type[$extension] == 'image') {
                try {
                    $img = Image::make($fullPath);
                } catch (\Throwable $e) {
                    $this->removeLocalUploadFile($path);

                    return [
                        'result' => false,
                        'status' => 422,
                        'message' => translate('The selected file is not a valid image.'),
                    ];
                }

                if (get_setting('disable_image_optimization') != 1) {
                    $img = $img->encode();
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
                    $img->save($fullPath);
                    clearstatcache();
                    $size = $img->filesize();
                }
            }
        } catch (\Throwable $e) {
            $this->removeLocalUploadFile($path);
            Log::error('AIZ upload processing failed', [
                'file' => $originalName,
                'error' => $e->getMessage(),
            ]);

            return [
                'result' => false,
                'status' => 422,
                'message' => translate('The file could not be processed.'),
            ];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_mime = finfo_file($finfo, $fullPath);

        if (env('FILESYSTEM_DRIVER') != 'local') {
            try {
                Storage::disk(env('FILESYSTEM_DRIVER'))->put(
                    $path,
                    file_get_contents($fullPath),
                    [
                        'visibility' => 'public',
                        'ContentType' => $extension == 'svg' ? 'image/svg+xml' : $file_mime
                    ]
                );

                if ($arr[0] != 'updates') {
                    unlink($fullPath);
                }
            } catch (\Throwable $e) {
                $this->removeLocalUploadFile($path);
                Log::error('AIZ upload remote storage failed', [
                    'file' => $originalName,
                    'error' => $e->getMessage(),
                ]);

                return [
                    'result' => false,
                    'status' => 500,
                    'message' => translate('Unable to store the file in remote storage.'),
                ];
            }
        }

        $upload->extension = $extension;
        $upload->file_name = $path;
        $upload->user_id = Auth::user()->id;
        $upload->type = $type[$upload->extension];
        $upload->file_size = $size;
        $upload->save();

        return [
            'result' => true,
            'upload' => $upload,
        ];
    }

    private function removeLocalUploadFile(string $path): void
    {
        $fullPath = public_path($path);

        if (file_exists($fullPath)) {
            @unlink($fullPath);
        }
    }

    public function get_uploaded_files(Request $request)
    {
        $uploads = Upload::where('user_id', Auth::user()->id);
        if ($request->search != null) {
            $uploads->where('file_original_name', 'like', '%' . $request->search . '%');
        }
        if ($request->sort != null) {
            switch ($request->sort) {
                case 'newest':
                    $uploads->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $uploads->orderBy('created_at', 'asc');
                    break;
                case 'smallest':
                    $uploads->orderBy('file_size', 'asc');
                    break;
                case 'largest':
                    $uploads->orderBy('file_size', 'desc');
                    break;
                default:
                    $uploads->orderBy('created_at', 'desc');
                    break;
            }
        }
        return $uploads->paginate(60)->appends(request()->query());
    }

    public function destroy($id)
    {
        $upload = Upload::findOrFail($id);

        if (auth()->user()->user_type == 'seller' && $upload->user_id != auth()->user()->id) {
            flash(translate("You don't have permission for deleting this!"))->error();
            return back();
        }
        try {
            if (env('FILESYSTEM_DRIVER') != 'local') {
                Storage::disk(env('FILESYSTEM_DRIVER'))->delete($upload->file_name);
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
        return back();
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

    public function delete_selected_files(Request $request)
    {
        $ids = collect($request->input('ids', []))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        if ($ids->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => translate('No files selected'),
            ], 422);
        }

        $uploads = Upload::whereIn('id', $ids)
            ->when(auth()->user()->user_type == 'seller', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->get();

        foreach ($uploads as $upload) {
            try {
                if (env('FILESYSTEM_DRIVER') != 'local') {
                    Storage::disk(env('FILESYSTEM_DRIVER'))->delete($upload->file_name);
                    if (file_exists(public_path($upload->file_name))) {
                        unlink(public_path($upload->file_name));
                    }
                } elseif (file_exists(public_path($upload->file_name))) {
                    unlink(public_path($upload->file_name));
                }
            } catch (\Exception $e) {
                // Keep deleting the database record even if the file is already missing.
            }

            $upload->delete();
        }

        return response()->json([
            'success' => true,
            'deleted' => $uploads->count(),
        ]);
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
                if (env('FILESYSTEM_DRIVER') != 'local') {
                    Storage::disk(env('FILESYSTEM_DRIVER'))->delete($upload->file_name);
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
