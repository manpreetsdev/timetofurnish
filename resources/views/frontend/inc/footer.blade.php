<!-- footer Description -->
</div>
@if (get_setting('footer_title') != null || get_setting('footer_description') != null)
    <section class="bg-light border-top border-bottom mt-auto">
        <div class="container py-4">
            <h4 class="fs-18 fw-700 text-gray-dark mb-3">{{ get_setting('footer_title', null, $system_language->code) }}
            </h4>
            <p class="fs-13 text-gray-dark text-justify mb-0">
                {!! nl2br(get_setting('footer_description', null, $system_language->code)) !!}
            </p>
        </div>
    </section>
@endif

@php
    if (!function_exists('get_footer_images_helper')) {
        function get_footer_images_helper($img_val, $default_asset = null) {
            if (empty($img_val)) {
                return $default_asset ? [$default_asset] : [];
            }
            $ids = explode(',', $img_val);
            $urls = [];
            foreach ($ids as $id) {
                $id = trim($id);
                if (!empty($id)) {
                    $asset = uploaded_asset($id);
                    if ($asset) {
                        $urls[] = $asset;
                    }
                }
            }
            return count($urls) > 0 ? $urls : ($default_asset ? [$default_asset] : []);
        }
    }

    if (!function_exists('footer_widget_flag_on')) {
        function footer_widget_flag_on($value, $default = 'on') {
            return ($value ?? $default) === 'on';
        }
    }

    // Read styling settings
    $foot_bg_color = get_setting('foot_bg_color', '#fdfbf9');
    $foot_border_color = get_setting('foot_border_color', 'rgba(104, 91, 78, 0.2)');
    $foot_head_color = get_setting('foot_head_color', '#000000');
    $foot_text_color = get_setting('foot_text_color', '#39322a');
    $foot_hover_color = get_setting('foot_hover_color', '#876a4b');
    $foot_pad_top = get_setting('foot_pad_top', '45px');
    $foot_pad_bot = get_setting('foot_pad_bot', '45px');
    $foot_pad_left = get_setting('foot_pad_left', '0px');
    $foot_pad_right = get_setting('foot_pad_right', '0px');
    $foot_mob_pad_top = get_setting('foot_mob_pad_top', '12px');
    $foot_mob_pad_bot = get_setting('foot_mob_pad_bot', '12px');
    $foot_mob_pad_left = get_setting('foot_mob_pad_left', '0px');
    $foot_mob_pad_right = get_setting('foot_mob_pad_right', '0px');
    $foot_bg_img = get_setting('foot_bg_img');
    $foot_mob_bg_img = get_setting('foot_mob_bg_img');
    $foot_bg_pattern_left = get_setting('foot_bg_pattern_left');
    $foot_bg_pattern_right = get_setting('foot_bg_pattern_right');
    $foot_social_radius = get_setting('foot_social_radius', '4px');
    $foot_news_highlight_img = get_setting('foot_news_highlight_img');

    // Newsletter settings
    $foot_news_show = get_setting('foot_news_show', 'on');
    $foot_news_title = get_setting('foot_news_title', 'Subscribe to our newsletter for regular updates about Offers, Coupons & more', App::getLocale());
    $foot_news_btn = get_setting('foot_news_btn', 'Subscribe', App::getLocale());
    $foot_news_bg = get_setting('foot_news_bg', '#ffffff');
    $foot_news_border = get_setting('foot_news_border', '#eadfd3');
    $foot_news_btn_bg = get_setting('foot_news_btn_bg', '#685b4e');
    $foot_news_btn_tx = get_setting('foot_news_btn_tx', '#ffffff');
    $foot_news_border_pos = get_setting('foot_news_border_pos', 'top-bottom');
    $foot_news_border_color = get_setting('foot_news_border_color', 'rgba(104, 91, 78, 0.2)');
    $foot_news_border_width = get_setting('foot_news_border_width', '1.5px');
    $foot_news_pad_top = get_setting('foot_news_pad_top', '24px');
    $foot_news_pad_bot = get_setting('foot_news_pad_bot', '24px');
    $foot_news_pad_left = get_setting('foot_news_pad_left', '0px');
    $foot_news_pad_right = get_setting('foot_news_pad_right', '0px');
    $foot_news_mob_pad_top = get_setting('foot_news_mob_pad_top', '8px');
    $foot_news_mob_pad_bot = get_setting('foot_news_mob_pad_bot', '8px');
    $foot_news_mob_pad_left = get_setting('foot_news_mob_pad_left', '0px');
    $foot_news_mob_pad_right = get_setting('foot_news_mob_pad_right', '0px');

    $news_border_top = 'none';
    $news_border_bottom = 'none';
    $news_border_left = 'none';
    $news_border_right = 'none';

    if ($foot_news_border_pos == 'top-bottom') {
        $news_border_top = "{$foot_news_border_width} solid {$foot_news_border_color}";
        $news_border_bottom = "{$foot_news_border_width} solid {$foot_news_border_color}";
    } elseif ($foot_news_border_pos == 'top') {
        $news_border_top = "{$foot_news_border_width} solid {$foot_news_border_color}";
    } elseif ($foot_news_border_pos == 'bottom') {
        $news_border_bottom = "{$foot_news_border_width} solid {$foot_news_border_color}";
    } elseif ($foot_news_border_pos == 'all') {
        $news_border_top = "{$foot_news_border_width} solid {$foot_news_border_color}";
        $news_border_bottom = "{$foot_news_border_width} solid {$foot_news_border_color}";
        $news_border_left = "{$foot_news_border_width} solid {$foot_news_border_color}";
        $news_border_right = "{$foot_news_border_width} solid {$foot_news_border_color}";
    }

    // Copyright & Disclaimer settings
    $foot_copy_bg = get_setting('foot_copy_bg', '#5f4d3e');
    $foot_copy_text = get_setting('foot_copy_text', '#ffffff');
    $frontend_copyright_text = get_setting('frontend_copyright_text', 'Copyright &copy; 2026 Time to Furnish. All Right Reserved.', App::getLocale());
    $footer_disclaimer_text = get_setting('footer_disclaimer_text', 'We operate as an independent third-party marketplace and are not liable for the accuracy, originality, or legality of any images or content uploaded by sellers. All such materials are the sole responsibility of the seller, including any content copied or reproduced from external platforms. Please read our <a href="/seller-terms-conditions" target="_blank" rel="noopener"><b>Terms and Conditions</b></a>.', App::getLocale());
    $footer_disclaimer_plain = trim(preg_replace('/\s+/', ' ', strip_tags($footer_disclaimer_text)));
    $footer_disclaimer_needs_toggle = \Illuminate\Support\Str::length($footer_disclaimer_plain) > 55;
    $foot_bar_pad_top = get_setting('foot_bar_pad_top', '10px');
    $foot_bar_pad_bot = get_setting('foot_bar_pad_bot', '10px');
    $foot_bar_pad_left = get_setting('foot_bar_pad_left', '0px');
    $foot_bar_pad_right = get_setting('foot_bar_pad_right', '0px');
    $foot_bar_mob_pad_top = get_setting('foot_bar_mob_pad_top', '10px');
    $foot_bar_mob_pad_bot = get_setting('foot_bar_mob_pad_bot', '12px');
    $foot_bar_mob_pad_left = get_setting('foot_bar_mob_pad_left', '0px');
    $foot_bar_mob_pad_right = get_setting('foot_bar_mob_pad_right', '0px');

    // Mobile Font Sizes
    $foot_mob_head_font_size = get_setting('foot_mob_head_font_size', '14px');
    $foot_mob_body_font_size = get_setting('foot_mob_body_font_size', '13px');

    $columns = \App\Support\FooterDefaults::columns(App::getLocale());
@endphp

@include('frontend.inc.footer.styles')

<footer class="footer-widget ttf-footer-links-section">
    @include('frontend.inc.footer.newsletter')
    @include('frontend.inc.footer.desktop_widgets')
    @include('frontend.inc.footer.mobile_widgets')
</footer>

@include('frontend.inc.footer.bottom_bar')
@include('frontend.inc.footer.mobile_nav')
