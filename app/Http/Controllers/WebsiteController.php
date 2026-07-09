<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:header_setup'])->only('header');
        $this->middleware(['permission:footer_setup'])->only('footer', 'exportFooter', 'importFooter');
        $this->middleware(['permission:view_all_website_pages'])->only('pages');
        $this->middleware(['permission:website_appearance'])->only('appearance');
        $this->middleware(['permission:select_homepage'])->only('select_homepage');
    }

    public function header(Request $request)
    {
        return view('backend.website_settings.header');
    }
    public function footer(Request $request)
    {
        $lang = $request->lang;
        return view('backend.website_settings.footer', compact('lang'));
    }

    public function exportFooter()
    {
        $types = ['frontend_copyright_text', 'footer_disclaimer_text', 'footer_title', 'footer_description', 'footer_builder_schema_version'];
        $settings = BusinessSetting::where(function($query) use ($types) {
            $query->whereIn('type', $types)
                  ->orWhere('type', 'like', 'foot_%');
        })->get(['type', 'value', 'lang'])->toArray();

        $filename = "footer_settings_" . date('Y-m-d_H-i-s') . ".json";
        return response()->json($settings, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function importFooter(Request $request)
    {
        if ($request->hasFile('footer_file')) {
            $file = $request->file('footer_file');
            $data = json_decode(file_get_contents($file->getRealPath()), true);
            if (is_array($data)) {
                foreach ($data as $item) {
                    if (isset($item['type']) && isset($item['value'])) {
                        $type = $item['type'];
                        $value = $item['value'];
                        $lang = $item['lang'] ?? null;
                        
                        $setting = BusinessSetting::where('type', $type);
                        if ($lang) {
                            $setting = $setting->where('lang', $lang);
                        } else {
                            $setting = $setting->whereNull('lang');
                        }
                        $setting = $setting->first();

                        if (!$setting) {
                            $setting = new BusinessSetting();
                            $setting->type = $type;
                            $setting->lang = $lang;
                        }
                        $setting->value = $value;
                        $setting->save();
                    }
                }
                \Artisan::call('cache:clear');
                flash(translate('Footer settings imported successfully'))->success();
            } else {
                flash(translate('Invalid file format'))->error();
            }
        } else {
            flash(translate('Please upload a file'))->error();
        }
        return back();
    }
    public function pages(Request $request)
    {
        $page = Page::where('type', '!=', 'home_page')->get();
        return view('backend.website_settings.pages.index', compact('page'));
    }
    public function appearance(Request $request)
    {
        return view('backend.website_settings.appearance');
    }
    public function select_homepage(Request $request)
    {
        return view('backend.website_settings.select_homepage');
    }
}
