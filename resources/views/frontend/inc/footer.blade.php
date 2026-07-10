
<!-- footer Description -->
</div>
@if (get_setting('footer_title') != null || get_setting('footer_description') != null)
    <section class="bg-light border-top border-bottom mt-auto">
        <!--<h1>j</h1>-->
        <div class="container py-4">
            <h1 class="fs-18 fw-700 text-gray-dark mb-3">{{ get_setting('footer_title', null, $system_language->code) }}
            </h1>
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

<!-- CSS Variables injection block -->
<style>
    :root {
        --foot-bg-color: {{ $foot_bg_color }};
        --foot-border-color: {{ $foot_border_color }};
        --foot-head-color: {{ $foot_head_color }};
        --foot-text-color: {{ $foot_text_color }};
        --foot-hover-color: {{ $foot_hover_color }};
        --foot-pad-top: {{ $foot_pad_top }};
        --foot-pad-bot: {{ $foot_pad_bot }};
        --foot-pad-left: {{ $foot_pad_left }};
        --foot-pad-right: {{ $foot_pad_right }};
        --foot-mob-pad-top: {{ $foot_mob_pad_top }};
        --foot-mob-pad-bot: {{ $foot_mob_pad_bot }};
        --foot-mob-pad-left: {{ $foot_mob_pad_left }};
        --foot-mob-pad-right: {{ $foot_mob_pad_right }};
        --foot-copy-bg: {{ $foot_copy_bg }};
        --foot-copy-text: {{ $foot_copy_text }};
        --foot-news-bg: {{ $foot_news_bg }};
        --foot-news-border: {{ $foot_news_border }};
        --foot-news-btn_bg: {{ $foot_news_btn_bg }};
        --foot-social-radius: {{ $foot_social_radius }};
        --foot-news-btn-tx: {{ $foot_news_btn_tx }};
        --foot-news-border-top: {{ $news_border_top }};
        --foot-news-border-bottom: {{ $news_border_bottom }};
        --foot-news-border-left: {{ $news_border_left }};
        --foot-news-border-right: {{ $news_border_right }};
        --foot-news-pad-top: {{ $foot_news_pad_top }};
        --foot-news-pad-bot: {{ $foot_news_pad_bot }};
        --foot-news-pad-left: {{ $foot_news_pad_left }};
        --foot-news-pad-right: {{ $foot_news_pad_right }};
        --foot-news-mob-pad-top: {{ $foot_news_mob_pad_top }};
        --foot-news-mob-pad-bot: {{ $foot_news_mob_pad_bot }};
        --foot-news-mob-pad-left: {{ $foot_news_mob_pad_left }};
        --foot-news-mob-pad-right: {{ $foot_news_mob_pad_right }};
        --foot-bar-pad-top: {{ $foot_bar_pad_top }};
        --foot-bar-pad-bot: {{ $foot_bar_pad_bot }};
        --foot-bar-pad-left: {{ $foot_bar_pad_left }};
        --foot-bar-pad-right: {{ $foot_bar_pad_right }};
        --foot-bar-mob-pad-top: {{ $foot_bar_mob_pad_top }};
        --foot-bar-mob-pad-bot: {{ $foot_bar_mob_pad_bot }};
        --foot-bar-mob-pad-left: {{ $foot_bar_mob_pad_left }};
        --foot-bar-mob-pad-right: {{ $foot_bar_mob_pad_right }};
        --foot-head-font-size: {{ get_setting('foot_head_font_size', '16px') }};
        --foot-body-font-size: {{ get_setting('foot_body_font_size', '13px') }};
        --foot-mob-head-font-size: {{ $foot_mob_head_font_size }};
        --foot-mob-body-font-size: {{ $foot_mob_body_font_size }};
        --foot-body-line-height: {{ get_setting('foot_body_line_height', '1.8') }};
        --foot-col-spacing: {{ get_setting('foot_col_spacing', '20px') }};
        --foot-head-margin-bottom: {{ get_setting('foot_head_margin_bottom', '18px') }};
        @if (!empty($foot_bg_img) && $foot_bg_img != 'none')
            --foot-bg-img: url("{{ uploaded_asset($foot_bg_img) }}");
        @else
            --foot-bg-img: none;
        @endif
        @if (!empty($foot_mob_bg_img) && $foot_mob_bg_img != 'none')
            --foot-mob-bg-img: url("{{ uploaded_asset($foot_mob_bg_img) }}");
        @else
            --foot-mob-bg-img: none;
        @endif
        @if (!empty($foot_bg_pattern_left))
            --foot-bg-pattern-left: url("{{ uploaded_asset($foot_bg_pattern_left) }}");
        @else
            --foot-bg-pattern-left: none;
        @endif
        @if (!empty($foot_bg_pattern_right))
            --foot-bg-pattern-right: url("{{ uploaded_asset($foot_bg_pattern_right) }}");
        @else
            --foot-bg-pattern-right: none;
        @endif
        @if (!empty($foot_news_highlight_img))
            --foot-news-highlight-img: url("{{ uploaded_asset($foot_news_highlight_img) }}");
        @endif
    }
</style>
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-footer.css') }}">

<footer class="footer-widget ttf-footer-links-section">
    @if ($foot_news_show == 'on')
        <section class="footer-widget iuytrey footer-newsletter-section">
            <div class="container">
                <div class="align-items-center footer-newsletter-row">
                    <div class="col-lg-7 col-md-9 mx-auto text-center newsletter-column">
                        <h5 class="fs-14 fw-700 mb-3 textheading">
                            {!! str_ireplace('newsletter', '<span class="text-highlight">newsletter</span>', $foot_news_title) !!}
                        </h5>
                        <div class="mb-3">
                            <form method="POST" action="{{ route('subscribers.store') }}">
                                @csrf
                                <div class="position-relative newsletter-form-wrap">
                                    <input type="email" class="form-control w-100 email_input_footer"
                                        placeholder="{{ translate('Your Email') }}" name="email" required
                                        style="padding: 12px 160px 12px 24px;">
                                    <button type="submit"
                                        class="btn footer_submit_btn borderbtn position-absolute d-flex align-items-center justify-content-center"
                                        style="right: 4px; top: 4px; bottom: 4px; min-width: 130px; border:none;">
                                        <span class="d-sm-block d-lg-block">{{ $foot_news_btn }}</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Desktop & iPad Footer Widgets (min-width: 768px) -->
    <div class="container d-none d-md-block">
        <div class="row gutters-20">
            @foreach($columns as $col => $c)
                @if ($c['status'] == 'on')
                    @php
                        $custom_width = $c['width'];
                        $is_bootstrap = str_starts_with($custom_width, 'col-') || str_starts_with($custom_width, 'ttf-');
                    @endphp
                    <div class="ttf-footer-col {{ $is_bootstrap ? $custom_width : '' }}"
                         style="@if(!$is_bootstrap) width: {{ $custom_width }} !important; flex: 0 0 {{ $custom_width }} !important; max-width: {{ $custom_width }} !important; @endif">
                        <div class="ttf-footer-card">
                            @foreach($c['widgets'] as $wIndex => $w)
                                @php
                                    $wType = $w['type'] ?? 'menu_links';
                                    $widget_id = "widget-col-{$col}-{$wIndex}";

                                    // Custom Styling Variables for each specific widget
                                    $style_text_align = $w['style_text_align'] ?? '';
                                    $style_font_size = $w['style_font_size'] ?? '';
                                    $style_text_color = $w['style_text_color'] ?? '';
                                    $style_head_color = $w['style_head_color'] ?? '';
                                    $style_hover_color = $w['style_hover_color'] ?? '';
                                    $style_line_height = $w['style_line_height'] ?? '';
                                    $style_margin_bottom = $w['style_margin_bottom'] ?? '';
                                    $style_head_weight = $w['style_head_weight'] ?? '';
                                    $style_text_weight = $w['style_text_weight'] ?? '';

                                    // Social Icons specific overrides
                                    $style_social_radius = $w['style_social_radius'] ?? '';
                                    $style_social_bg = $w['style_social_bg'] ?? '';
                                    $style_social_color = $w['style_social_color'] ?? '';
                                    $style_social_hover_bg = $w['style_social_hover_bg'] ?? '';
                                    $style_social_hover_color = $w['style_social_hover_color'] ?? '';
                                    $style_social_width = $w['style_social_width'] ?? '36px';
                                @endphp

                                <style>
                                    #{{ $widget_id }}, #{{ $widget_id }}-mob {
                                        @if(!empty($style_text_align)) text-align: {{ $style_text_align }} !important; @endif
                                    }
                                    #{{ $widget_id }} h4, #{{ $widget_id }}-mob h4,
                                    #{{ $widget_id }} .sub-widget-title, #{{ $widget_id }}-mob .sub-widget-title,
                                    #{{ $widget_id }} .secure-payment-title, #{{ $widget_id }}-mob .secure-payment-title,
                                    #{{ $widget_id }}-mob .aiz-accordion {
                                        @if(!empty($style_head_color)) color: {{ $style_head_color }} !important; @endif
                                        @if(!empty($style_margin_bottom)) margin-bottom: {{ $style_margin_bottom }} !important; @endif
                                        @if(!empty($style_head_weight)) font-weight: {{ $style_head_weight }} !important; @endif
                                    }
                                    #{{ $widget_id }} a, #{{ $widget_id }}-mob a,
                                    #{{ $widget_id }} p, #{{ $widget_id }}-mob p,
                                    #{{ $widget_id }} span, #{{ $widget_id }}-mob span,
                                    #{{ $widget_id }} li, #{{ $widget_id }}-mob li {
                                        @if(!empty($style_font_size)) font-size: {{ $style_font_size }} !important; @endif
                                        @if(!empty($style_text_color)) color: {{ $style_text_color }} !important; @endif
                                        @if(!empty($style_line_height)) line-height: {{ $style_line_height }} !important; @endif
                                        @if(!empty($style_text_weight)) font-weight: {{ $style_text_weight }} !important; @endif
                                    }
                                    #{{ $widget_id }} a:hover, #{{ $widget_id }}-mob a:hover {
                                        @if(!empty($style_hover_color)) color: {{ $style_hover_color }} !important; @endif
                                    }
                                    #{{ $widget_id }} .footer-social-list li a, #{{ $widget_id }}-mob .footer-social-list li a {
                                        @if(!empty($style_social_width)) width: {{ $style_social_width }} !important; height: {{ $style_social_width }} !important; @endif
                                        @if(!empty($style_social_radius)) border-radius: {{ $style_social_radius }} !important; @endif
                                        @if(!empty($style_social_bg)) background-color: {{ $style_social_bg }} !important; @endif
                                    }
                                    #{{ $widget_id }} .footer-social-list li a i, #{{ $widget_id }}-mob .footer-social-list li a i,
                                    #{{ $widget_id }} .footer-social-list li a svg, #{{ $widget_id }}-mob .footer-social-list li a svg {
                                        @if(!empty($style_social_color)) color: {{ $style_social_color }} !important; @endif
                                    }
                                    #{{ $widget_id }} .footer-social-list li a:hover, #{{ $widget_id }}-mob .footer-social-list li a:hover {
                                        @if(!empty($style_social_hover_bg)) background-color: {{ $style_social_hover_bg }} !important; @endif
                                    }
                                    #{{ $widget_id }} .footer-social-list li a:hover i, #{{ $widget_id }}-mob .footer-social-list li a:hover i,
                                    #{{ $widget_id }} .footer-social-list li a:hover svg, #{{ $widget_id }}-mob .footer-social-list li a:hover svg {
                                        @if(!empty($style_social_hover_color)) color: {{ $style_social_hover_color }} !important; @endif
                                    }
                                </style>

                                <div id="{{ $widget_id }}" class="mb-4">
                                    @if ($wType == 'menu_links')
                                        <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                            {{ $w['title'] ?? 'Menu' }}
                                        </h4>
                                        <ul class="list-unstyled">
                                            @if(!empty($w['lbls']))
                                                @foreach ($w['lbls'] as $key => $label)
                                                    @if(!empty(trim($label)))
                                                        @php $url = isset($w['lnks'][$key]) ? $w['lnks'][$key] : '#'; @endphp
                                                        <li>
                                                            <a href="{{ url($url) }}" class="fs-14 text-light animate-underline-white">
                                                                {{ $label }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    @elseif ($wType == 'important_links')
                                        <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                            {{ $w['title'] ?? 'Important Links' }}
                                        </h4>
                                        <ul class="list-unstyled">
                                            @php $pages = get_pages_footer(!empty($w['page_ids']) ? $w['page_ids'] : '2,3,4,5,6,7,8,10,11'); @endphp
                                            @foreach ($pages as $key => $value)
                                                <li>
                                                    <a href="{{ url($value->slug) }}" class="fs-14 text-light animate-underline-white">
                                                        {{ $value->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @elseif ($wType == 'my_account')
                                        <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                            {{ $w['title'] ?? 'My Account' }}
                                        </h4>
                                        <ul class="list-unstyled">
                                            @if (Auth::check())
                                                <li><a class="fs-14 text-light animate-underline-white" href="{{ route('logout') }}">{{ !empty($w['logout_text']) ? translate($w['logout_text']) : translate('Logout') }}</a></li>
                                            @else
                                                <li><a class="fs-14 text-light animate-underline-white" href="{{ route('user.login') }}">{{ !empty($w['login_text']) ? translate($w['login_text']) : translate('Login') }}</a></li>
                                            @endif
                                            <li><a class="fs-14 text-light animate-underline-white" href="{{ route('purchase_history.index') }}">{{ !empty($w['order_history_text']) ? translate($w['order_history_text']) : translate('Order History') }}</a></li>
                                            <li><a class="fs-14 text-light animate-underline-white" href="{{ route('wishlists.index') }}">{{ !empty($w['wishlist_text']) ? translate($w['wishlist_text']) : translate('My Wishlist') }}</a></li>
                                            <li><a class="fs-14 text-light animate-underline-white" href="{{ route('orders.track') }}">{{ !empty($w['track_order_text']) ? translate($w['track_order_text']) : translate('Track Order') }}</a></li>
                                        </ul>
                                    @elseif ($wType == 'text_html')
                                        @if(!empty($w['title']))
                                            <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                                {{ $w['title'] }}
                                            </h4>
                                        @endif
                                        <div class="fs-13 text-light" style="line-height: 1.8;">
                                            {!! $w['html'] ?? '' !!}
                                        </div>
                                    @elseif ($wType == 'seller_zone')
                                        <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                            {{ $w['title'] ?? 'Seller Zone' }}
                                        </h4>
                                        <ul class="list-unstyled mb-3">
                                            <li>
                                                <a class="fs-14 text-light animate-underline-white" href="{{ !empty($w['seller_url']) ? $w['seller_url'] : route('seller.login') }}">
                                                    {{ !empty($w['seller_login_text']) ? translate($w['seller_login_text']) : translate('Login to Seller Panel') }}
                                                </a>
                                            </li>
                                            @if (get_setting('seller_app_link'))
                                                <li>
                                                    <a class="fs-14 text-light animate-underline-white" target="_blank" href="{{ get_setting('seller_app_link') }}">
                                                        {{ !empty($w['download_seller_app_text']) ? translate($w['download_seller_app_text']) : translate('Download Seller App') }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>

                                        <div class="sub-widget-title">{{ $w['subheading_2'] ?? translate('Join Our Partner Network') }}</div>
                                        <ul class="list-unstyled mb-3">
                                            <li>
                                                 <a href="{{ !empty($w['become_seller_url']) ? $w['become_seller_url'] : route('shops.create') }}" class="fs-14 text-light animate-underline-white">
                                                    {{ !empty($w['become_seller_text']) ? translate($w['become_seller_text']) : translate('Register your shop') }}
                                                </a>
                                            </li>
                                        </ul>

                                        @php
                                            $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                                            $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                                            $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                                            $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                                            $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                                            $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                                            $has_social_links = $fb || $tw || $ig || $yt || $pt || $tk;
                                        @endphp
                                        @if(!empty($w['subheading_3']) && $has_social_links)
                                            <div class="sub-widget-title">{{ $w['subheading_3'] }}</div>
                                            <ul class="footer-social-list mt-2">
                                                @if ($fb) <li><a href="{{ $fb }}" target="_blank"><i class="lab la-facebook-f"></i></a></li> @endif
                                                @if ($tw)
                                                    <li>
                                                        <a href="{{ $tw }}" target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                            </svg>
                                                        </a>
                                                    </li>
                                                @endif
                                                @if ($ig) <li><a href="{{ $ig }}" target="_blank"><i class="lab la-instagram"></i></a></li> @endif
                                                @if ($yt) <li><a href="{{ $yt }}" target="_blank"><i class="lab la-youtube"></i></a></li> @endif
                                                @if ($pt) <li><a href="{{ $pt }}" target="_blank"><i class="lab la-pinterest"></i></a></li> @endif
                                                @if ($tk) <li><a href="{{ $tk }}" target="_blank"><i class="lab la-tiktok"></i></a></li> @endif
                                                @foreach(($w['extra_social'] ?? []) as $sItem)
                                                    @if(!empty($sItem['url'] ?? ''))
                                                        <li>
                                                            <a href="{{ $sItem['url'] }}" target="_blank">
                                                                <i class="{{ $sItem['icon'] ?? 'lab la-link' }}"></i>
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    @elseif ($wType == 'images_widget')
                                         @php
                                             $show_deliv = ($w['show_deliv'] ?? 'on') == 'on';
                                             $show_pay = ($w['show_pay'] ?? 'on') == 'on';
                                             $show_trust = ($w['show_trust'] ?? 'on') == 'on';

                                             $deliv_imgs = get_footer_images_helper(!empty($w['deliv_img']) ? $w['deliv_img'] : get_setting('foot_img_deliv'), static_asset('assets/img/delivery_partners_logo.png'));
                                             $pay_imgs = get_footer_images_helper(!empty($w['pay_img']) ? $w['pay_img'] : get_setting('foot_img_pay'), static_asset('assets/img/securelypayments.png'));
                                             $trust_imgs = get_footer_images_helper(!empty($w['trust_img']) ? $w['trust_img'] : get_setting('foot_img_trust'), static_asset('assets/img/trustpilot.png'));
                                             $trust_lnk = !empty($w['trustpilot_lnk']) ? $w['trustpilot_lnk'] : get_setting('foot_lnk_trust', '#');
                                         @endphp
                                         @if($show_deliv)
                                             <div class="secure-payment-box mb-3">
                                                 <h5 class="secure-payment-title textheading">
                                                     {{ $w['title'] ?? translate('Delivery Partners') }}
                                                 </h5>
                                                 <div class="logo-images-row">
                                                     @foreach($deliv_imgs as $img)
                                                         <div class="logo-image-item">
                                                             <img src="{{ $img }}" alt="Delivery Partner">
                                                         </div>
                                                     @endforeach
                                                 </div>
                                             </div>
                                         @endif

                                         @if($show_pay)
                                             <div class="secure-payment-box mb-3">
                                                 <h5 class="secure-payment-title textheading">
                                                     {{ $show_deliv ? translate('Pay Securely With') : ($w['title'] ?? translate('Pay Securely With')) }}
                                                 </h5>
                                                 <div class="logo-images-row">
                                                     @foreach($pay_imgs as $img)
                                                         <div class="logo-image-item">
                                                             <img src="{{ $img }}" alt="Pay Securely With">
                                                         </div>
                                                     @endforeach
                                                 </div>
                                             </div>
                                         @endif

                                         @if($show_trust)
                                             <div class="secure-payment-box">
                                                 <h5 class="secure-payment-title textheading">
                                                     {{ translate('What Trustpilot Say’s') }}
                                                 </h5>
                                                 @if(!empty($trust_lnk))
                                                     <a href="{{ $trust_lnk }}" target="_blank" class="logo-images-row">
                                                         @foreach($trust_imgs as $img)
                                                             <div class="logo-image-item">
                                                                 <img src="{{ $img }}" alt="Trustpilot Reviews" >
                                                             </div>
                                                         @endforeach
                                                     </a>
                                                 @else
                                                     <div class="logo-images-row">
                                                         @foreach($trust_imgs as $img)
                                                             <div class="logo-image-item">
                                                                 <img src="{{ $img }}" alt="Trustpilot Reviews" >
                                                             </div>
                                                         @endforeach
                                                     </div>
                                                 @endif

                                             </div>
                                         @endif
                                    @elseif ($wType == 'social_icons')
                                        <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                            {{ $w['title'] ?? 'Follow Us' }}
                                        </h4>
                                        @php
                                            $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                                            $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                                            $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                                            $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                                            $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                                            $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                                        @endphp
                                        <ul class="footer-social-list mt-3">
                                            @if ($fb) <li><a href="{{ $fb }}" target="_blank"><i class="lab la-facebook-f"></i></a></li> @endif
                                            @if ($tw)
                                                <li>
                                                    <a href="{{ $tw }}" target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($ig) <li><a href="{{ $ig }}" target="_blank"><i class="lab la-instagram"></i></a></li> @endif
                                            @if ($yt) <li><a href="{{ $yt }}" target="_blank"><i class="lab la-youtube"></i></a></li> @endif
                                            @if ($pt) <li><a href="{{ $pt }}" target="_blank"><i class="lab la-pinterest"></i></a></li> @endif
                                            @if ($tk) <li><a href="{{ $tk }}" target="_blank"><i class="lab la-tiktok"></i></a></li> @endif
                                            @foreach(($w['extra_social'] ?? []) as $sItem)
                                                @if(!empty($sItem['url'] ?? ''))
                                                    <li>
                                                        <a href="{{ $sItem['url'] }}" target="_blank">
                                                            <i class="{{ $sItem['icon'] ?? 'lab la-link' }}"></i>
                                                        </a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach

                            {{-- ── Extra Link Blocks (desktop: min-width 768px) ── --}}
                            @if (!empty($c['extra_blocks']))
                                @foreach($c['extra_blocks'] as $eb)
                                    @php $eb_show = $eb['show_on'] ?? 'both'; @endphp
                                    @if ($eb_show !== 'mobile')
                                        <div class="mb-4">
                                            @if (!empty($eb['title']))
                                                <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                                    {{ $eb['title'] }}
                                                </h4>
                                            @endif
                                            @if (!empty($eb['lbls']))
                                                <ul class="list-unstyled">
                                                    @foreach($eb['lbls'] as $ebIdx => $ebLbl)
                                                        @if (!empty(trim($ebLbl)))
                                                            @php $ebUrl = $eb['lnks'][$ebIdx] ?? '#'; @endphp
                                                            <li>
                                                                <a href="{{ url($ebUrl) }}" class="fs-14 text-light animate-underline-white">
                                                                    {{ $ebLbl }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>    <!-- Mobile Accordion Footer (under 768px) -->
    <div class="d-md-none bg-transparent ttf-mobile-footer">
        @foreach($columns as $col => $c)
            @if ($c['status'] == 'on')
                @php
                    $mobileItems = [];
                    $mobileSeq = 0;

                    foreach ($c['widgets'] as $wIndex => $w) {
                        $wType = $w['type'] ?? 'menu_links';

                        if ($wType === 'images_widget') {
                            if (($w['show_deliv'] ?? 'on') === 'on') {
                                $mobileItems[] = [
                                    'kind' => 'images_deliv',
                                    'order' => (int) ($w['deliv_mobile_order'] ?? (($wIndex + 1) * 10)),
                                    'seq' => $mobileSeq++,
                                    'wIndex' => $wIndex,
                                    'w' => $w,
                                ];
                            }

                            if (($w['show_pay'] ?? 'on') === 'on') {
                                $mobileItems[] = [
                                    'kind' => 'images_pay',
                                    'order' => (int) ($w['pay_mobile_order'] ?? (($wIndex + 1) * 10 + 1)),
                                    'seq' => $mobileSeq++,
                                    'wIndex' => $wIndex,
                                    'w' => $w,
                                ];
                            }

                            if (($w['show_trust'] ?? 'on') === 'on') {
                                $mobileItems[] = [
                                    'kind' => 'images_trust',
                                    'order' => (int) ($w['trust_mobile_order'] ?? (($wIndex + 1) * 10 + 2)),
                                    'seq' => $mobileSeq++,
                                    'wIndex' => $wIndex,
                                    'w' => $w,
                                ];
                            }

                            continue;
                        }

                        $defaultOrder = ($wIndex + 1) * 10;
                        $mobileItems[] = [
                            'kind' => 'widget',
                            'order' => (int) ($w['mobile_order'] ?? $defaultOrder),
                            'seq' => $mobileSeq++,
                            'wIndex' => $wIndex,
                            'w' => $w,
                        ];
                    }

                    foreach (($c['extra_blocks'] ?? []) as $ebIdx => $eb) {
                        $ebShow = $eb['show_on'] ?? 'both';
                        if ($ebShow === 'desktop') {
                            continue;
                        }

                        $defaultOrder = (count($c['widgets']) + $ebIdx + 1) * 10;
                        $mobileItems[] = [
                            'kind' => 'extra',
                            'order' => (int) ($eb['mobile_order'] ?? $defaultOrder),
                            'seq' => $mobileSeq++,
                            'ebIdx' => $ebIdx,
                            'eb' => $eb,
                        ];
                    }

                    usort($mobileItems, function ($a, $b) {
                        $cmp = $a['order'] <=> $b['order'];
                        return $cmp !== 0 ? $cmp : ($a['seq'] <=> $b['seq']);
                    });
                @endphp

                @foreach($mobileItems as $mobileItem)
                    @if(($mobileItem['kind'] ?? 'widget') === 'widget')
                        @php
                            $w = $mobileItem['w'];
                            $wIndex = $mobileItem['wIndex'];
                        @endphp
                    @php
                        $wType = $w['type'] ?? 'menu_links';
                        $widget_id = "widget-col-{$col}-{$wIndex}-mob";
                    @endphp

                    @if (in_array($wType, ['menu_links', 'important_links', 'my_account', 'seller_zone', 'text_html']))
                        <div id="{{ $widget_id }}" class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-section">
                            <div class="container">
                                <div class="aiz-accordion-heading">
                                    <button class="aiz-accordion fs-14 text-white bg-transparent">
                                        {{ $w['title'] ?? 'Section' }}
                                    </button>
                                </div>
                                <div class="aiz-accordion-panel bg-transparent">
                                    <div class="py-3">
                                        @if ($wType == 'menu_links')
                                            <ul class="list-unstyled">
                                                @if(!empty($w['lbls']))
                                                    @foreach ($w['lbls'] as $key => $label)
                                                        @if(!empty(trim($label)))
                                                            @php $url = isset($w['lnks'][$key]) ? $w['lnks'][$key] : '#'; @endphp
                                                            <li class="mb-2 pb-2">
                                                                <a href="{{ url($url) }}" class="fs-14 text-light animate-underline-white">
                                                                    {{ $label }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </ul>
                                        @elseif ($wType == 'important_links')
                                            <ul class="list-unstyled">
                                                @php $pages = get_pages_footer(!empty($w['page_ids']) ? $w['page_ids'] : '2,3,4,5,6,7,8,10,11'); @endphp
                                                @foreach ($pages as $key => $value)
                                                    <li class="mb-2">
                                                        <a href="{{ url($value->slug) }}" class="fs-14 text-light animate-underline-white">
                                                            {{ $value->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @elseif ($wType == 'my_account')
                                            <ul class="list-unstyled">
                                                @auth
                                                    <li class="mb-2 pb-2">
                                                        <a class="fs-14 text-light animate-underline-white" href="{{ route('logout') }}">{{ !empty($w['logout_text']) ? translate($w['logout_text']) : translate('Logout') }}</a>
                                                    </li>
                                                @else
                                                    <li class="mb-2 pb-2">
                                                        <a class="fs-14 text-light animate-underline-white" href="{{ route('user.login') }}">{{ !empty($w['login_text']) ? translate($w['login_text']) : translate('Login') }}</a>
                                                    </li>
                                                @endauth
                                                <li class="mb-2 pb-2">
                                                    <a class="fs-14 text-light animate-underline-white" href="{{ route('purchase_history.index') }}">{{ !empty($w['order_history_text']) ? translate($w['order_history_text']) : translate('Order History') }}</a>
                                                </li>
                                                <li class="mb-2 pb-2">
                                                    <a class="fs-14 text-light animate-underline-white" href="{{ route('wishlists.index') }}">{{ !empty($w['wishlist_text']) ? translate($w['wishlist_text']) : translate('My Wishlist') }}</a>
                                                </li>
                                                <li class="mb-2 pb-2">
                                                    <a class="fs-14 text-light animate-underline-white" href="{{ route('orders.track') }}">{{ !empty($w['track_order_text']) ? translate($w['track_order_text']) : translate('Track Order') }}</a>
                                                </li>
                                            </ul>
                                        @elseif ($wType == 'text_html')
                                            <div class="fs-13 text-light" style="line-height: 1.8;">
                                                {!! $w['html'] ?? '' !!}
                                            </div>
                                        @elseif ($wType == 'seller_zone')
                                            <ul class="list-unstyled mb-3">
                                                <li class="mb-2 pb-2">
                                                    <a class="fs-14 text-light animate-underline-white" href="{{ !empty($w['seller_url']) ? $w['seller_url'] : route('seller.login') }}">
                                                        {{ !empty($w['seller_login_text']) ? translate($w['seller_login_text']) : translate('Login to Seller Panel') }}
                                                    </a>
                                                </li>
                                                <li class="mb-2">
                                                    <a href="{{ !empty($w['become_seller_url']) ? $w['become_seller_url'] : route('shops.create') }}" class="fs-14 text-light animate-underline-white">
                                                        {{ !empty($w['become_seller_text']) ? translate($w['become_seller_text']) : translate('Register your shop') }}
                                                    </a>
                                                </li>
                                            </ul>

                                            @php
                                                $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                                                $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                                                $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                                                $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                                                $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                                                $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                                                $has_social_links = $fb || $tw || $ig || $yt || $pt || $tk || !empty($w['extra_social']);
                                            @endphp
                                            @if(!empty($w['subheading_3']) && $has_social_links)
                                                <h5 class="secure-payment-title textheading">{{ $w['subheading_3'] }}</h5>
                                                <ul class="footer-social-list mt-2">
                                                    @if ($fb) <li><a href="{{ $fb }}" target="_blank"><i class="lab la-facebook-f"></i></a></li> @endif
                                                    @if ($tw)
                                                        <li>
                                                            <a href="{{ $tw }}" target="_blank">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                                    <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if ($ig) <li><a href="{{ $ig }}" target="_blank"><i class="lab la-instagram"></i></a></li> @endif
                                                    @if ($yt) <li><a href="{{ $yt }}" target="_blank"><i class="lab la-youtube"></i></a></li> @endif
                                                    @if ($pt) <li><a href="{{ $pt }}" target="_blank"><i class="lab la-pinterest"></i></a></li> @endif
                                                    @if ($tk) <li><a href="{{ $tk }}" target="_blank"><i class="lab la-tiktok"></i></a></li> @endif
                                                    @foreach(($w['extra_social'] ?? []) as $sItem)
                                                        @if(!empty($sItem['url'] ?? ''))
                                                            <li><a href="{{ $sItem['url'] }}" target="_blank"><i class="{{ $sItem['icon'] ?? 'lab la-link' }}"></i></a></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($wType == 'images_widget')
                        @php
                            $show_deliv = ($w['show_deliv'] ?? 'on') == 'on';
                            $show_pay = ($w['show_pay'] ?? 'on') == 'on';
                            $show_trust = ($w['show_trust'] ?? 'on') == 'on';

                            $deliv_imgs = get_footer_images_helper(!empty($w['deliv_img']) ? $w['deliv_img'] : get_setting('foot_img_deliv'), static_asset('assets/img/delivery_partners_logo.png'));
                            $pay_imgs = get_footer_images_helper(!empty($w['pay_img']) ? $w['pay_img'] : get_setting('foot_img_pay'), static_asset('assets/img/securelypayments.png'));
                            $trust_imgs = get_footer_images_helper(!empty($w['trust_img']) ? $w['trust_img'] : get_setting('foot_img_trust'), static_asset('assets/img/trustpilot.png'));
                            $trust_lnk = !empty($w['trustpilot_lnk']) ? $w['trustpilot_lnk'] : get_setting('foot_lnk_trust', '#');
                        @endphp
                        @if($show_deliv)
                            <div id="{{ $widget_id }}" class="secure-payment-box ttf-mobile-section mt-3">
                                <div class="container">
                                    <h5 class="secure-payment-title textheading">
                                        {{ $w['title'] ?? translate('Delivery Partners') }}
                                    </h5>
                                    <div class="logo-images-row">
                                        @foreach($deliv_imgs as $img)
                                            <div class="logo-image-item">
                                                <img src="{{ $img }}" alt="Delivery Partner">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($show_pay)
                            <div id="{{ $widget_id }}-pay" class="secure-payment-box ttf-mobile-section mt-3">
                                <div class="container">
                                    <h5 class="secure-payment-title textheading">
                                        {{ $show_deliv ? translate('Pay Securely With') : ($w['title'] ?? translate('Pay Securely With')) }}
                                    </h5>
                                    <div class="logo-images-row">
                                        @foreach($pay_imgs as $img)
                                            <div class="logo-image-item">
                                                <img src="{{ $img }}" alt="Pay Securely With">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($show_trust)
                            <div id="{{ $widget_id }}-trust" class="secure-payment-box ttf-mobile-section mt-3">
                                <div class="container">
                                    <h5 class="secure-payment-title textheading">
                                        {{ translate('What Trustpilot Say’s') }}
                                    </h5>
                                    @if(!empty($trust_lnk))
                                        <a href="{{ $trust_lnk }}" target="_blank" class="logo-images-row">
                                            @foreach($trust_imgs as $img)
                                                <div class="logo-image-item">
                                                    <img src="{{ $img }}" alt="Trustpilot Reviews">
                                                </div>
                                            @endforeach
                                        </a>
                                    @else
                                        <div class="logo-images-row">
                                            @foreach($trust_imgs as $img)
                                                <div class="logo-image-item">
                                                    <img src="{{ $img }}" alt="Trustpilot Reviews">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @elseif ($wType == 'social_icons')
                        @php
                            $mobileSocialToggle = ($w['mobile_view'] ?? 'section') === 'toggle';
                            $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                            $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                            $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                            $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                            $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                            $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                        @endphp
                        @if($mobileSocialToggle)
                            <div id="{{ $widget_id }}" class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-section">
                                <div class="container">
                                    <div class="aiz-accordion-heading">
                                        <button class="aiz-accordion fs-14 text-white bg-transparent">{{ $w['title'] ?? translate('Follow Us') }}</button>
                                    </div>
                                    <div class="aiz-accordion-panel bg-transparent">
                                        <div class="py-3">
                                            <ul class="footer-social-list mt-2">
                                                @if ($fb) <li><a href="{{ $fb }}" target="_blank"><i class="lab la-facebook-f"></i></a></li> @endif
                                                @if ($tw)
                                                    <li><a href="{{ $tw }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/></svg></a></li>
                                                @endif
                                                @if ($ig) <li><a href="{{ $ig }}" target="_blank"><i class="lab la-instagram"></i></a></li> @endif
                                                @if ($yt) <li><a href="{{ $yt }}" target="_blank"><i class="lab la-youtube"></i></a></li> @endif
                                                @if ($pt) <li><a href="{{ $pt }}" target="_blank"><i class="lab la-pinterest"></i></a></li> @endif
                                                @if ($tk) <li><a href="{{ $tk }}" target="_blank"><i class="lab la-tiktok"></i></a></li> @endif
                                                @foreach(($w['extra_social'] ?? []) as $sItem)
                                                    @if(!empty($sItem['url'] ?? ''))
                                                        <li><a href="{{ $sItem['url'] }}" target="_blank"><i class="{{ $sItem['icon'] ?? 'lab la-link' }}"></i></a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="{{ $widget_id }}" class="secure-payment-box ttf-mobile-section mt-3">
                                <div class="container">
                                    <h5 class="secure-payment-title textheading">{{ $w['title'] ?? translate('Follow Us') }}</h5>
                                    <ul class="footer-social-list mt-2">
                                        @if ($fb) <li><a href="{{ $fb }}" target="_blank"><i class="lab la-facebook-f"></i></a></li> @endif
                                        @if ($tw)
                                            <li><a href="{{ $tw }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/></svg></a></li>
                                        @endif
                                        @if ($ig) <li><a href="{{ $ig }}" target="_blank"><i class="lab la-instagram"></i></a></li> @endif
                                        @if ($yt) <li><a href="{{ $yt }}" target="_blank"><i class="lab la-youtube"></i></a></li> @endif
                                        @if ($pt) <li><a href="{{ $pt }}" target="_blank"><i class="lab la-pinterest"></i></a></li> @endif
                                        @if ($tk) <li><a href="{{ $tk }}" target="_blank"><i class="lab la-tiktok"></i></a></li> @endif
                                        @foreach(($w['extra_social'] ?? []) as $sItem)
                                            @if(!empty($sItem['url'] ?? ''))
                                                <li><a href="{{ $sItem['url'] }}" target="_blank"><i class="{{ $sItem['icon'] ?? 'lab la-link' }}"></i></a></li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    @endif
                    @else
                        @if(in_array(($mobileItem['kind'] ?? ''), ['images_deliv', 'images_pay', 'images_trust']))
                            @php
                                $w = $mobileItem['w'];
                                $wIndex = $mobileItem['wIndex'];
                                $kind = $mobileItem['kind'];
                                $widget_id = "widget-col-{$col}-{$wIndex}-mob";
                                $mobileToggle = ($w['mobile_view'] ?? 'section') === 'toggle';
                                $deliv_imgs = get_footer_images_helper(!empty($w['deliv_img']) ? $w['deliv_img'] : get_setting('foot_img_deliv'), static_asset('assets/img/delivery_partners_logo.png'));
                                $pay_imgs = get_footer_images_helper(!empty($w['pay_img']) ? $w['pay_img'] : get_setting('foot_img_pay'), static_asset('assets/img/securelypayments.png'));
                                $trust_imgs = get_footer_images_helper(!empty($w['trust_img']) ? $w['trust_img'] : get_setting('foot_img_trust'), static_asset('assets/img/trustpilot.png'));
                                $trust_lnk = !empty($w['trustpilot_lnk']) ? $w['trustpilot_lnk'] : get_setting('foot_lnk_trust', '#');
                                $sectionTitle = $kind === 'images_deliv'
                                    ? ($w['title'] ?? translate('Delivery Partners'))
                                    : ($kind === 'images_pay' ? translate('Pay Securely With') : translate('What Trustpilot Say’s'));
                                $sectionId = $kind === 'images_deliv' ? $widget_id : ($kind === 'images_pay' ? $widget_id.'-pay' : $widget_id.'-trust');
                            @endphp

                            @if($mobileToggle)
                                <div id="{{ $sectionId }}" class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-section">
                                    <div class="container">
                                        <div class="aiz-accordion-heading">
                                            <button class="aiz-accordion fs-14 text-white bg-transparent">{{ $sectionTitle }}</button>
                                        </div>
                                        <div class="aiz-accordion-panel bg-transparent">
                                            <div class="py-3">
                                                @if($kind === 'images_deliv')
                                                    <div class="logo-images-row">
                                                        @foreach($deliv_imgs as $img)
                                                            <div class="logo-image-item"><img src="{{ $img }}" alt="Delivery Partner"></div>
                                                        @endforeach
                                                    </div>
                                                @elseif($kind === 'images_pay')
                                                    <div class="logo-images-row">
                                                        @foreach($pay_imgs as $img)
                                                            <div class="logo-image-item"><img src="{{ $img }}" alt="Pay Securely With"></div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    @if(!empty($trust_lnk))
                                                        <a href="{{ $trust_lnk }}" target="_blank" class="logo-images-row">
                                                            @foreach($trust_imgs as $img)
                                                                <div class="logo-image-item"><img src="{{ $img }}" alt="Trustpilot Reviews"></div>
                                                            @endforeach
                                                        </a>
                                                    @else
                                                        <div class="logo-images-row">
                                                            @foreach($trust_imgs as $img)
                                                                <div class="logo-image-item"><img src="{{ $img }}" alt="Trustpilot Reviews"></div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div id="{{ $sectionId }}" class="secure-payment-box ttf-mobile-section mt-3">
                                    <div class="container">
                                        <h5 class="secure-payment-title textheading">{{ $sectionTitle }}</h5>
                                        @if($kind === 'images_deliv')
                                            <div class="logo-images-row">
                                                @foreach($deliv_imgs as $img)
                                                    <div class="logo-image-item"><img src="{{ $img }}" alt="Delivery Partner"></div>
                                                @endforeach
                                            </div>
                                        @elseif($kind === 'images_pay')
                                            <div class="logo-images-row">
                                                @foreach($pay_imgs as $img)
                                                    <div class="logo-image-item"><img src="{{ $img }}" alt="Pay Securely With"></div>
                                                @endforeach
                                            </div>
                                        @else
                                            @if(!empty($trust_lnk))
                                                <a href="{{ $trust_lnk }}" target="_blank" class="logo-images-row">
                                                    @foreach($trust_imgs as $img)
                                                        <div class="logo-image-item"><img src="{{ $img }}" alt="Trustpilot Reviews"></div>
                                                    @endforeach
                                                </a>
                                            @else
                                                <div class="logo-images-row">
                                                    @foreach($trust_imgs as $img)
                                                        <div class="logo-image-item"><img src="{{ $img }}" alt="Trustpilot Reviews"></div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            @php
                                $eb = $mobileItem['eb'];
                                $ebIdx = $mobileItem['ebIdx'];
                                $eb_widget_id = "extra-block-col-{$col}-{$ebIdx}-mob";
                                $mobileExtraToggle = ($eb['mobile_view'] ?? 'toggle') === 'toggle';
                            @endphp
                            @if($mobileExtraToggle)
                                <div id="{{ $eb_widget_id }}" class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-section">
                                    <div class="container">
                                        <div class="aiz-accordion-heading">
                                            <button class="aiz-accordion fs-14 text-white bg-transparent">
                                                {{ $eb['title'] ?? translate('More Links') }}
                                            </button>
                                        </div>
                                        <div class="aiz-accordion-panel bg-transparent">
                                            <div class="py-3">
                                                <ul class="list-unstyled">
                                                    @if (!empty($eb['lbls']))
                                                        @foreach($eb['lbls'] as $eLIdx => $eLbl)
                                                            @if (!empty(trim($eLbl)))
                                                                @php $eLnk = $eb['lnks'][$eLIdx] ?? '#'; @endphp
                                                                <li class="mb-2 pb-2">
                                                                    <a href="{{ url($eLnk) }}" class="fs-14 text-light animate-underline-white">
                                                                        {{ $eLbl }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div id="{{ $eb_widget_id }}" class="secure-payment-box ttf-mobile-section mt-3">
                                    <div class="container">
                                        @if(!empty($eb['title']))
                                            <h5 class="secure-payment-title textheading">{{ $eb['title'] }}</h5>
                                        @endif
                                        <ul class="list-unstyled">
                                            @if (!empty($eb['lbls']))
                                                @foreach($eb['lbls'] as $eLIdx => $eLbl)
                                                    @if (!empty(trim($eLbl)))
                                                        @php $eLnk = $eb['lnks'][$eLIdx] ?? '#'; @endphp
                                                        <li class="mb-2 pb-2">
                                                            <a href="{{ url($eLnk) }}" class="fs-14 text-light animate-underline-white">
                                                                {{ $eLbl }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
</footer>

<div class="ttf-footer-bottom-bar">
    <div class="container px-xs-0">
        <div class="row align-items-center">
            <div class="col-lg-6 order-1 order-lg-0 mt-2 mb-sm-50">
                <div class="text-justify fs-14" current-verison="{{ get_setting('current_version') }}">
                    <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                        {!! $frontend_copyright_text !!}
                    </p>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-0 mt-2 mb-sm-50">
                <div class="text-right fs-14 " current-verison="{{ get_setting('current_version') }}">
                    <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                        <span class="footer-text-short">{{ Str::limit(strip_tags($footer_disclaimer_text), 90) }}</span>
                        <span class="footer-text-full d-none">{!! $footer_disclaimer_text !!}</span>
                        <a href="javascript:void(0);" class="footer-read-more-btn ml-1">Read More</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile bottom nav -->
<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom border-top border-sm-bottom border-sm-left border-sm-right mx-auto mb-sm-2"
    style="background-color: #fff !important;">
    <div class="row align-items-center gutters-5 h-100">
        <!-- Home -->
        <div class="col">
            <a href="{{ route('home') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['home'], 'svg-active') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                        <path id="Path_2916" data-name="Path 2916"
                            d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                            transform="translate(-3495.144 602)" fill="#b5b5bf" />
                    </g>
                </svg>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['home'], 'text-primary') }}">{{ translate('Home') }}</span>
            </a>
        </div>

        <!-- Categories -->
        <div class="col">
            <a href="{{ route('categories.all') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['categories.all'], 'svg-active') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_25497" data-name="Group 25497" transform="translate(3373.432 -602)">
                        <path id="Path_2917" data-name="Path 2917"
                            d="M126.713,0h-5V5a2,2,0,0,0,2,2h3a2,2,0,0,0,2-2V2a2,2,0,0,0-2-2m1,5a1,1,0,0,1-1,1h-3a1,1,0,0,1-1-1V1h4a1,1,0,0,1,1,1Z"
                            transform="translate(-3495.144 602)" fill="#91919c" />
                        <path id="Path_2918" data-name="Path 2918"
                            d="M144.713,18h-3a2,2,0,0,0-2,2v3a2,2,0,0,0,2,2h5V20a2,2,0,0,0-2-2m1,6h-4a1,1,0,0,1-1-1V20a1,1,0,0,1,1-1h3a1,1,0,0,1,1,1Z"
                            transform="translate(-3504.144 593)" fill="#91919c" />
                        <path id="Path_2919" data-name="Path 2919"
                            d="M143.213,0a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5"
                            transform="translate(-3504.144 602)" fill="#91919c" />
                        <path id="Path_2920" data-name="Path 2920"
                            d="M125.213,18a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5"
                            transform="translate(-3495.144 593)" fill="#91919c" />
                    </g>
                </svg>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['categories.all'], 'text-primary') }}">{{ translate('Categories') }}</span>
            </a>
        </div>
        <!-- Cart -->
        @php
            $count = count(get_user_cart());
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}"
                class="text-secondary d-block text-center pb-2 pt-3 px-3 {{ areActiveRoutes(['cart'], 'svg-active') }}">
                <span class="d-inline-block position-relative px-2">
                    <svg id="Group_25499" data-name="Group 25499" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="16.001" height="16"
                        viewBox="0 0 16.001 16">
                        <defs>
                            <clipPath id="clip-pathw">
                                <rect id="Rectangle_1383" data-name="Rectangle 1383" width="16" height="16"
                                    fill="#91919c" />
                            </clipPath>
                        </defs>
                        <g id="Group_8095" data-name="Group 8095" transform="translate(0 0)"
                            clip-path="url(#clip-pathw)">
                            <path id="Path_2926" data-name="Path 2926"
                                d="M8,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                transform="translate(-3 -11.999)" fill="#91919c" />
                            <path id="Path_2927" data-name="Path 2927"
                                d="M24,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                transform="translate(-10.999 -11.999)" fill="#91919c" />
                            <path id="Path_2928" data-name="Path 2928"
                                d="M15.923,3.975A1.5,1.5,0,0,0,14.5,2h-9a.5.5,0,1,0,0,1h9a.507.507,0,0,1,.129.017.5.5,0,0,1,.355.612l-1.581,6a.5.5,0,0,1-.483.372H5.456a.5.5,0,0,1-.489-.392L3.1,1.176A1.5,1.5,0,0,0,1.632,0H.5a.5.5,0,1,0,0,1H1.544a.5.5,0,0,1,.489.392L3.9,9.826A1.5,1.5,0,0,0,5.368,11h7.551a1.5,1.5,0,0,0,1.423-1.026Z"
                                transform="translate(0 -0.001)" fill="#91919c" />
                        </g>
                    </svg>
                    @if ($count > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['cart'], 'text-primary') }}">
                    {{ translate('Cart') }}
                    (<span class="cart-count">{{ $count }}</span>)
                </span>
            </a>
        </div>

        <!-- Notifications -->
        <div class="col">
            <a href="{{ route('all-notifications') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['all-notifications'], 'svg-active') }}">
                <span class="d-inline-block position-relative px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                        <path id="ecf3cc267cd87627e58c1954dc6fbcc2"
                            d="M5.488,14.056a.617.617,0,0,0-.8-.016.6.6,0,0,0-.082.855A2.847,2.847,0,0,0,6.835,16h0l.174-.007a2.846,2.846,0,0,0,2.048-1.1h0l.053-.073a.6.6,0,0,0-.134-.782.616.616,0,0,0-.862.081,1.647,1.647,0,0,1-.334.331,1.591,1.591,0,0,1-2.222-.331H5.55ZM6.828,0C4.372,0,1.618,1.732,1.306,4.512h0v1.45A3,3,0,0,1,.6,7.37a.535.535,0,0,0-.057.077A3.248,3.248,0,0,0,0,9.088H0l.021.148a3.312,3.312,0,0,0,.752,2.2,3.909,3.909,0,0,0,2.5,1.232,32.525,32.525,0,0,0,7.1,0,3.865,3.865,0,0,0,2.456-1.232A3.264,3.264,0,0,0,13.6,9.249h0v-.1a3.361,3.361,0,0,0-.582-1.682h0L12.96,7.4a3.067,3.067,0,0,1-.71-1.408h0V4.54l-.039-.081a.612.612,0,0,0-1.132.208h0v1.45a.363.363,0,0,0,0,.077,4.21,4.21,0,0,0,.979,1.957,2.022,2.022,0,0,1,.312,1h0v.155a2.059,2.059,0,0,1-.468,1.373,2.656,2.656,0,0,1-1.661.788,32.024,32.024,0,0,1-6.87,0,2.663,2.663,0,0,1-1.7-.824,2.037,2.037,0,0,1-.447-1.33h0V9.151a2.1,2.1,0,0,1,.305-1.007A4.212,4.212,0,0,0,2.569,6.187a.363.363,0,0,0,0-.077h0V4.653a4.157,4.157,0,0,1,4.2-3.442,4.608,4.608,0,0,1,2.257.584h0l.084.042A.615.615,0,0,0,9.649,1.8.6.6,0,0,0,9.624.739,5.8,5.8,0,0,0,6.828,0Z"
                            transform="translate(-3499.144 602)" fill="#91919b" />
                    </svg>
                    @if (Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['all-notifications'], 'text-primary') }}">{{ translate('Notifications') }}</span>
            </a>
        </div>

        <!-- Account -->
        <div class="col">
            @if (Auth::check())
                @if (isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if ($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @elseif(isSeller())
                    <a href="{{ route('dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if ($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @else
                    <a href="javascript:void(0)"
                        class="text-secondary d-block text-center pb-2 pt-3 mobile-side-nav-thumb"
                        data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <span class="d-block mx-auto">
                            @if ($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="text-secondary d-block text-center pb-2 pt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
                            <path id="Path_2924" data-name="Path 2924"
                                d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3"
                                transform="translate(-3499.144 602)" fill="#b5b5bf" />
                            <path id="Path_2925" data-name="Path 2925"
                                d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4"
                                transform="translate(-3495.144 592)" fill="#b5b5bf" />
                        </g>
                    </svg>
                    <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                </a>
            @endif
        </div>

    </div>
</div>

@if (Auth::check() && !isAdmin())
    <!-- User Side nav -->
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static"
            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif

<script>
    function toggleFlags(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('flagDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Outside click close
    document.addEventListener('click', function() {
        const dropdown = document.getElementById('flagDropdown');
        if (dropdown) dropdown.style.display = 'none';
    });

    // Footer read more/less toggle
    document.addEventListener('DOMContentLoaded', function() {
        const readMoreBtn = document.querySelector('.footer-read-more-btn');
        const shortText = document.querySelector('.footer-text-short');
        const fullText = document.querySelector('.footer-text-full');

        if (readMoreBtn && shortText && fullText) {
            readMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (fullText.classList.contains('d-none')) {
                    fullText.classList.remove('d-none');
                    shortText.classList.add('d-none');
                    readMoreBtn.textContent = 'Read Less';
                } else {
                    fullText.classList.add('d-none');
                    shortText.classList.remove('d-none');
                    readMoreBtn.textContent = 'Read More';
                }
            });
        }
		/* ==========================
           Mobile Footer Accordion
           Only one open at a time
        ========================== */
        const mobileFooter = document.querySelector('.ttf-mobile-footer');

        if (mobileFooter) {
            mobileFooter.addEventListener('click', function (e) {
                const currentButton = e.target.closest('.aiz-accordion');

                if (!currentButton || !mobileFooter.contains(currentButton)) {
                    return;
                }

                const currentWrap = currentButton.closest('.aiz-accordion-wrap');

                setTimeout(function () {
                    mobileFooter.querySelectorAll('.aiz-accordion-wrap').forEach(function (wrap) {
                        if (wrap !== currentWrap) {
                            const btn = wrap.querySelector('.aiz-accordion');
                            const panel = wrap.querySelector('.aiz-accordion-panel');

                            const heading = wrap.querySelector('.aiz-accordion-heading');
                            if (heading) {
                                heading.classList.remove('active');
                            }
                            if (btn) {
                                btn.classList.remove('active');
                            }

                            if (panel) {
                                panel.classList.remove('active', 'show');
                                panel.style.maxHeight = null;
                                panel.style.display = '';
                            }
                        }
                    });
                }, 50);
            });
        }
    });
</script>
