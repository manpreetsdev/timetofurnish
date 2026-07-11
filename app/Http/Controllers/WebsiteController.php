<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Category;
use App\Models\BusinessSetting;
use App\Support\ProductCategoryInfo;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:header_setup'])->only('header');
        $this->middleware(['permission:footer_setup'])->only('footer', 'exportFooter', 'importFooter', 'productCategoryInfo', 'updateProductCategoryInfo');
        $this->middleware(['permission:view_all_website_pages'])->only('pages');
        $this->middleware(['permission:website_appearance'])->only('appearance');
        $this->middleware(['permission:select_homepage'])->only('select_homepage');
    }

    public function header(Request $request)
    {
        return view('backend.website_settings.header');
    }

    public function productCategoryInfo(Request $request)
    {
        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->orderBy('order_level', 'desc')
            ->get();
        $badges = ProductCategoryInfo::badges();

        return view('backend.website_settings.product_category_info', compact('categories', 'badges'));
    }

    public function updateProductCategoryInfo(Request $request)
    {
        $inputBadges = $request->input('badges', []);
        $badges = ProductCategoryInfo::sanitizeBadges(is_array($inputBadges) ? $inputBadges : []);

        if (empty($badges) && !empty($inputBadges)) {
            flash(translate('Please add text or image content and select at least one category for each rule.'))->warning();
            return back()->withInput();
        }

        $setting = BusinessSetting::where('type', ProductCategoryInfo::SETTING_KEY)->first();
        if (!$setting) {
            $setting = new BusinessSetting();
            $setting->type = ProductCategoryInfo::SETTING_KEY;
        }

        $setting->value = json_encode(array_values($badges));
        $setting->save();

        \Artisan::call('cache:clear');

        flash(translate('Product category info settings updated successfully'))->success();

        return redirect()->route('website.product-category-info');
    }
    public function footer(Request $request)
    {
        $lang = $request->lang;
        return view('backend.website_settings.footer', compact('lang'));
    }

    public function exportFooter()
    {
        $types = ['frontend_copyright_text', 'footer_disclaimer_text', 'footer_title', 'footer_description', 'footer_builder_schema_version'];
        $settings = BusinessSetting::where(function ($query) use ($types) {
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
        $request->validate([
            'footer_file' => 'required|file|max:10240',
        ]);

        if (!$request->hasFile('footer_file')) {
            flash(translate('Please upload a file'))->error();
            return back();
        }

        $file = $request->file('footer_file');
        $raw = file_get_contents($file->getRealPath());

        // Strip UTF-8 BOM if present.
        $raw = preg_replace('/^\xEF\xBB\xBF/', '', (string) $raw);
        $decoded = json_decode($raw, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            flash(translate('Invalid JSON file'))->error();
            return back();
        }

        if (isset($decoded['settings']) && is_array($decoded['settings'])) {
            $data = $decoded['settings'];
        } elseif (is_array($decoded)) {
            $data = array_values($decoded) === $decoded ? $decoded : [$decoded];
        } else {
            flash(translate('Invalid file format'))->error();
            return back();
        }

        $imported = 0;

        foreach ($data as $item) {
            if (!is_array($item)) {
                continue;
            }

            $type = isset($item['type']) ? trim((string) $item['type']) : '';
            if ($type === '' || !array_key_exists('value', $item)) {
                continue;
            }

            $value = $item['value'];
            if (is_array($value) || is_object($value)) {
                $value = json_encode($value);
            }

            $lang = $item['lang'] ?? null;
            if (is_string($lang)) {
                $lang = trim($lang);
                if ($lang === '') {
                    $lang = null;
                }
            }

            $query = BusinessSetting::where('type', $type);
            if ($lang === null) {
                $query->where(function ($q) {
                    $q->whereNull('lang')->orWhere('lang', '');
                });
            } else {
                $query->where('lang', $lang);
            }

            $setting = $query->first();
            if (!$setting) {
                $setting = new BusinessSetting();
                $setting->type = $type;
                $setting->lang = $lang;
            }

            $setting->value = $value;
            $setting->save();
            $imported++;
        }

        if ($imported > 0) {
            \Artisan::call('cache:clear');
            flash(translate('Footer settings imported successfully'))->success();
        } else {
            flash(translate('No valid footer settings found in file'))->warning();
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
