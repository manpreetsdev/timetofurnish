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
        $all_pages = $page;
        return view('backend.website_settings.pages.index', compact('page', 'all_pages'));
    }

    public function update_policy_pages(Request $request)
    {
        $policy_keys = [
            'return_policy_page_id',
            'support_policy_page_id',
            'seller_policy_page_id',
            'privacy_policy_page_id',
            'delivery_policy_page_id',
            'disclaimer_policy_page_id',
            'cookie_policy_page_id',
            'customer_terms_policy_page_id',
            'terms_conditions_page_id',
        ];

        foreach ($policy_keys as $id_key) {
            $page_id = $request->input($id_key);
            
            // Save page ID
            $setting_id = BusinessSetting::where('type', $id_key)->first();
            if (!$setting_id) {
                $setting_id = new BusinessSetting();
                $setting_id->type = $id_key;
            }
            $setting_id->value = $page_id;
            $setting_id->save();
        }

        \Artisan::call('cache:clear');
        flash(translate('Policy pages configuration has been updated successfully'))->success();
        return back();
    }
    public function appearance(Request $request)
    {
        return view('backend.website_settings.appearance');
    }
    public function select_homepage(Request $request)
    {
        return view('backend.website_settings.select_homepage');
    }

    public function homepage_builder(Request $request)
    {
        $categories = Category::all();
        return view('backend.website_settings.pages.homepage_builder', compact('categories'));
    }

    public function homepage_builder_update(Request $request)
    {
        $sections = [];
        if ($request->has('sections') && is_array($request->sections)) {
            foreach ($request->sections as $sec) {
                if (empty($sec['type'])) {
                    continue;
                }
                
                $secType = $sec['type'];
                $slider_images = $sec['slider_images'] ?? [];
                $slider_links = $sec['slider_links'] ?? [];
                $banner_images = $sec['banner_images'] ?? [];
                $banner_links = $sec['banner_links'] ?? [];
                
                // Sync to global business settings
                if ($secType == 'home_slider') {
                    $setting_img = BusinessSetting::where('type', 'home_slider_images')->first() ?: new BusinessSetting(['type' => 'home_slider_images']);
                    $setting_img->value = json_encode($slider_images);
                    $setting_img->save();
                    
                    $setting_lnk = BusinessSetting::where('type', 'home_slider_links')->first() ?: new BusinessSetting(['type' => 'home_slider_links']);
                    $setting_lnk->value = json_encode($slider_links);
                    $setting_lnk->save();
                } elseif ($secType == 'banner_level_1') {
                    $setting_img = BusinessSetting::where('type', 'home_banner1_images')->first() ?: new BusinessSetting(['type' => 'home_banner1_images']);
                    $setting_img->value = json_encode($banner_images);
                    $setting_img->save();
                    
                    $setting_lnk = BusinessSetting::where('type', 'home_banner1_links')->first() ?: new BusinessSetting(['type' => 'home_banner1_links']);
                    $setting_lnk->value = json_encode($banner_links);
                    $setting_lnk->save();
                } elseif ($secType == 'banner_level_2') {
                    $setting_img = BusinessSetting::where('type', 'home_banner2_images')->first() ?: new BusinessSetting(['type' => 'home_banner2_images']);
                    $setting_img->value = json_encode($banner_images);
                    $setting_img->save();
                    
                    $setting_lnk = BusinessSetting::where('type', 'home_banner2_links')->first() ?: new BusinessSetting(['type' => 'home_banner2_links']);
                    $setting_lnk->value = json_encode($banner_links);
                    $setting_lnk->save();
                } elseif ($secType == 'banner_level_3') {
                    $setting_img = BusinessSetting::where('type', 'home_banner3_images')->first() ?: new BusinessSetting(['type' => 'home_banner3_images']);
                    $setting_img->value = json_encode($banner_images);
                    $setting_img->save();
                    
                    $setting_lnk = BusinessSetting::where('type', 'home_banner3_links')->first() ?: new BusinessSetting(['type' => 'home_banner3_links']);
                    $setting_lnk->value = json_encode($banner_links);
                    $setting_lnk->save();
                }

                $sections[] = [
                    'id' => uniqid(),
                    'type' => $secType,
                    'heading' => $sec['heading'] ?? '',
                    'subheading' => $sec['subheading'] ?? '',
                    'category_id' => $sec['category_id'] ?? null,
                    'banner_image' => $sec['banner_image'] ?? '',
                    'banner_link' => $sec['banner_link'] ?? '',
                    'banner_height' => $sec['banner_height'] ?? '350px',
                    'slider_images' => $slider_images,
                    'slider_links' => $slider_links,
                    'banner_images' => $banner_images,
                    'banner_links' => $banner_links,
                    'status' => isset($sec['status']) ? 1 : 0,
                    // Styling configs
                    'heading_size' => $sec['heading_size'] ?? '36',
                    'show_border' => isset($sec['show_border']) ? 1 : 0,
                    'border_color' => $sec['border_color'] ?? '#e5e7eb',
                    'padding_top' => $sec['padding_top'] ?? '30',
                    'padding_bottom' => $sec['padding_bottom'] ?? '30',
                    'bg_color' => $sec['bg_color'] ?? '#ffffff',
                ];
            }
        }

        $setting = BusinessSetting::where('type', 'homepage_sections_configuration')->first();
        if (!$setting) {
            $setting = new BusinessSetting();
            $setting->type = 'homepage_sections_configuration';
        }
        $setting->value = json_encode($sections);
        $setting->save();

        \Artisan::call('cache:clear');
        flash(translate('Homepage sections configuration has been updated successfully'))->success();
        return back();
    }
}
