<?php

namespace App\Support;

use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Cache;

class FooterDefaults
{
    public const MAX_COLUMNS = 8;
    public const DEFAULT_ACTIVE_COLUMNS = 5;
    public const SCHEMA_VERSION = 'screenshot_2026_07_delivery';

    public static function columns($lang = null)
    {
        if (!self::hasCurrentSchema()) {
            return self::defaultColumns($lang);
        }

        $columns = [];

        for ($col = 1; $col <= self::MAX_COLUMNS; $col++) {
            $default = self::defaultColumn($col, $lang);

            $columns[$col] = [
                'status' => get_setting('foot_col_'.$col.'_status', $default['status']),
                'width' => get_setting('foot_col_'.$col.'_width', $default['width']),
                'widgets' => self::widgets($col, $lang),
            ];
        }

        return $columns;
    }

    public static function defaultColumns($lang = null)
    {
        $columns = [];

        for ($col = 1; $col <= self::MAX_COLUMNS; $col++) {
            $columns[$col] = self::defaultColumn($col, $lang);
        }

        return $columns;
    }

    public static function defaultColumn($col, $lang = null)
    {
        $defaults = [
            1 => [
                'status' => 'on',
                'width' => '20%',
                'widgets' => [[
                    'type' => 'menu_links',
                    'title' => 'Quick Links',
                    'lbls' => ['Home', 'About Us', 'Categories', 'Blogs', 'Contact Us', 'Careers', 'Meet Our Team', 'Join Our Delivery Partner'],
                    'lnks' => ['', 'about-us', 'categories', 'blog', 'contact-us', 'career', 'meet-the-team', 'become_delivery_partner'],
                ]],
            ],
            2 => [
                'status' => 'on',
                'width' => '20%',
                'widgets' => [[
                    'type' => 'menu_links',
                    'title' => 'Important Links',
                    'lbls' => ['Return Policy', 'Support Policy', 'Seller Terms & Conditions', 'Privacy Policy', 'Delivery', 'Disclaimer', 'Cookie Policy', 'Customer Terms & Conditions'],
                    'lnks' => ['return-policy', 'support-policy', 'seller-terms-conditions', 'privacy-policy', 'delivery', 'disclaimer', 'cookie-policy', 'customer-terms-conditions'],
                ]],
            ],
            3 => [
                'status' => 'on',
                'width' => '20%',
                'widgets' => [[
                    'type' => 'my_account',
                    'title' => 'My Account',
                ]],
            ],
            4 => [
                'status' => 'on',
                'width' => '20%',
                'widgets' => [[
                    'type' => 'seller_zone',
                    'title' => 'Seller Zone',
                    'seller_url' => '',
                    'become_seller_url' => '',
                    'subheading_2' => 'Join Our Partner Network',
                    'subheading_3' => 'Follow Us',
                ]],
            ],
            5 => [
                'status' => 'on',
                'width' => '20%',
                'widgets' => [
                    [
                        'type' => 'images_widget',
                        'title' => 'Delivery Partners',
                        'show_deliv' => 'on',
                        'show_pay' => 'on',
                        'show_trust' => 'on',
                        'deliv_img' => get_setting('foot_img_deliv'),
                        'pay_img' => get_setting('foot_img_pay'),
                        'trust_img' => get_setting('foot_img_trust'),
                        'trustpilot_lnk' => get_setting('foot_lnk_trust', '#'),
                    ],
                ],
            ],
        ];

        return $defaults[$col] ?? [
            'status' => 'off',
            'width' => '20%',
            'widgets' => [],
        ];
    }

    public static function widgets($col, $lang = null)
    {
        if (!self::hasCurrentSchema()) {
            return self::defaultColumn($col, $lang)['widgets'];
        }

        $setting = self::setting('foot_col_'.$col.'_widgets', $lang);

        if ($setting['exists']) {
            $widgets = json_decode($setting['value'], true);
            return is_array($widgets) ? array_values($widgets) : [];
        }

        return self::defaultColumn($col, $lang)['widgets'];
    }

    protected static function setting($type, $lang = null)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::all();
        });

        $setting = null;

        if ($lang) {
            $setting = $settings->where('type', $type)->where('lang', $lang)->first();
        }

        if (!$setting) {
            $setting = $settings->where('type', $type)->first();
        }

        return [
            'exists' => (bool) $setting,
            'value' => $setting ? $setting->value : null,
        ];
    }

    protected static function hasCurrentSchema()
    {
        $setting = self::setting('footer_builder_schema_version');

        return $setting['value'] === self::SCHEMA_VERSION;
    }
}
