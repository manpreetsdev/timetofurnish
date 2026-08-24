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
                                        @else
                                            @php $pages = get_pages_footer(!empty($w['page_ids']) ? $w['page_ids'] : '2,3,4,5,6,7,8,10,11'); @endphp
                                            @foreach ($pages as $key => $value)
                                                <li>
                                                    <a href="{{ url($value->slug) }}" class="fs-14 text-light animate-underline-white">
                                                        {{ $value->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                            @if(!empty($w['extra_lbls']))
                                                @foreach($w['extra_lbls'] as $eIdx => $eLbl)
                                                    @if(!empty(trim($eLbl)))
                                                        @php $eLnk = $w['extra_lnks'][$eIdx] ?? '#'; @endphp
                                                        <li>
                                                            <a href="{{ url($eLnk) }}" class="fs-14 text-light animate-underline-white">
                                                                {{ $eLbl }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
                                    </ul>
                                @elseif ($wType == 'my_account')
                                    <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                        {{ $w['title'] ?? 'My Account' }}
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
                                        @else
                                            @if (Auth::check())
                                                <li><a class="fs-14 text-light animate-underline-white" href="{{ route('logout') }}">{{ !empty($w['logout_text']) ? translate($w['logout_text']) : translate('Logout') }}</a></li>
                                                <li><a class="fs-14 text-light animate-underline-white" href="{{ route('purchase_history.index') }}">{{ !empty($w['order_history_text']) ? translate($w['order_history_text']) : translate('Order History') }}</a></li>
                                                <li><a class="fs-14 text-light animate-underline-white" href="{{ route('wishlists.index') }}">{{ !empty($w['wishlist_text']) ? translate($w['wishlist_text']) : translate('My Wishlist') }}</a></li>
                                            @else
                                                <li><a class="fs-14 text-light animate-underline-white" href="{{ route('user.login') }}">{{ !empty($w['login_text']) ? translate($w['login_text']) : translate('Login') }}</a></li>
                                            @endif
                                            <li><a class="fs-14 text-light animate-underline-white" href="{{ route('orders.track') }}">{{ !empty($w['track_order_text']) ? translate($w['track_order_text']) : translate('Track Order') }}</a></li>
                                            @if(!empty($w['extra_lbls']))
                                                @foreach($w['extra_lbls'] as $eIdx => $eLbl)
                                                    @if(!empty(trim($eLbl)))
                                                        @php $eLnk = $w['extra_lnks'][$eIdx] ?? '#'; @endphp
                                                        <li>
                                                            <a href="{{ url($eLnk) }}" class="fs-14 text-light animate-underline-white">
                                                                {{ $eLbl }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endif
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
                                    @php
                                        $showSellerPanel = footer_widget_flag_on($w['show_seller_panel'] ?? 'on');
                                        $showDownloadSellerApp = footer_widget_flag_on($w['show_download_app'] ?? 'on');
                                        $showBecomeSeller = footer_widget_flag_on($w['show_become_seller'] ?? 'on');
                                        $showFollowUs = footer_widget_flag_on($w['show_follow_us'] ?? 'on');
                                    @endphp
                                    <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                        {{ $w['title'] ?? 'Seller Zone' }}
                                    </h4>
                                    @if($showSellerPanel)
                                        <ul class="list-unstyled mb-3">
                                            <li>
                                                <a class="fs-14 text-light animate-underline-white" href="{{ !empty($w['seller_url']) ? $w['seller_url'] : route('seller.login') }}">
                                                    {{ !empty($w['seller_login_text']) ? translate($w['seller_login_text']) : translate('Login to Seller Panel') }}
                                                </a>
                                            </li>
                                            @if ($showDownloadSellerApp && get_setting('seller_app_link'))
                                                <li>
                                                    <a class="fs-14 text-light animate-underline-white" target="_blank" href="{{ get_setting('seller_app_link') }}">
                                                        {{ !empty($w['download_seller_app_text']) ? translate($w['download_seller_app_text']) : translate('Download Seller App') }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @endif

                                    @if($showBecomeSeller)
                                        <div class="sub-widget-title">{{ $w['subheading_2'] ?? translate('Join Our Partner Network') }}</div>
                                        <ul class="list-unstyled mb-3">
                                            <li>
                                                 <a href="{{ !empty($w['become_seller_url']) ? $w['become_seller_url'] : route('shops.create') }}" class="fs-14 text-light animate-underline-white">
                                                    {{ !empty($w['become_seller_text']) ? translate($w['become_seller_text']) : translate('Register your shop') }}
                                                </a>
                                            </li>
                                        </ul>
                                    @endif

                                    @php
                                        $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                                        $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                                        $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                                        $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                                        $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                                        $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                                        $has_social_links = $fb || $tw || $ig || $yt || $pt || $tk;
                                    @endphp
                                    @if($showFollowUs && !empty($w['subheading_3']) && $has_social_links)
                                        <div class="sub-widget-title">{{ $w['subheading_3'] }}</div>
                                        <ul class="footer-social-list mt-2">
                                            @if ($fb) <li><a href="{{ $fb }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a></li> @endif
                                            @if ($tw)
                                                <li>
                                                    <a href="{{ $tw }}" target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($ig) <li><a href="{{ $ig }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a></li> @endif
                                            @if ($yt) <li><a href="{{ $yt }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.17 1 12 1 12s0 3.83.46 5.58a2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.83 23 12 23 12s0-3.83-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg></a></li> @endif
                                            @if ($pt) <li><a href="{{ $pt }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a8 8 0 0 0-2.915 15.452c-.088-.556-.166-1.41.034-2.022.183-.559 1.183-5.011 1.183-5.011s-.303-.605-.303-1.5c0-1.406.816-2.457 1.83-2.457.86 0 1.275.645 1.275 1.418 0 .864-.55 2.158-.834 3.359-.237 1.006.505 1.826 1.498 1.826 1.796 0 3.178-1.895 3.178-4.631 0-2.42-1.74-4.11-4.225-4.11-2.887 0-4.582 2.167-4.582 4.404 0 .87.335 1.803.754 2.311.083.1.095.19.07.288-.077.319-.248 1.012-.281 1.147-.044.178-.146.216-.328.131-1.226-.574-1.993-2.37-1.993-3.818 0-3.111 2.261-5.968 6.529-5.968 3.42 0 6.079 2.43 6.079 5.674 0 3.398-2.14 6.134-5.112 6.134-1 0-1.939-.519-2.26-1.134 0 0-.495 1.884-.614 2.344-.223.856-.822 1.925-1.225 2.58A8 8 0 1 0 8 0"/></svg></a></li> @endif
                                            @if ($tk) <li><a href="{{ $tk }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z"/></svg></a></li> @endif
                                            @foreach(($w['extra_social'] ?? []) as $sItem)
                                                @if(!empty($sItem['url'] ?? ''))
                                                    <li>
                                                        <a href="{{ $sItem['url'] }}" target="_blank">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
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
                                                 {{ $w['pay_title'] ?? ($show_deliv ? translate('Pay Securely With') : ($w['title'] ?? translate('Pay Securely With'))) }}
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
                                                 {{ $w['trust_title'] ?? translate('What Trustpilot Say’s') }}
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
                                        @if ($fb) <li><a href="{{ $fb }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg></a></li> @endif
                                        @if ($tw)
                                            <li>
                                                <a href="{{ $tw }}" target="_blank">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.6.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                    </svg>
                                                </a>
                                            </li>
                                        @endif
                                        @if ($ig) <li><a href="{{ $ig }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a></li> @endif
                                        @if ($yt) <li><a href="{{ $yt }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.17 1 12 1 12s0 3.83.46 5.58a2.78 2.78 0 0 0 1.94 2C5.12 20 12 20 12 20s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.83 23 12 23 12s0-3.83-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg></a></li> @endif
                                        @if ($pt) <li><a href="{{ $pt }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8 0a8 8 0 0 0-2.915 15.452c-.088-.556-.166-1.41.034-2.022.183-.559 1.183-5.011 1.183-5.011s-.303-.605-.303-1.5c0-1.406.816-2.457 1.83-2.457.86 0 1.275.645 1.275 1.418 0 .864-.55 2.158-.834 3.359-.237 1.006.505 1.826 1.498 1.826 1.796 0 3.178-1.895 3.178-4.631 0-2.42-1.74-4.11-4.225-4.11-2.887 0-4.582 2.167-4.582 4.404 0 .87.335 1.803.754 2.311.083.1.095.19.07.288-.077.319-.248 1.012-.281 1.147-.044.178-.146.216-.328.131-1.226-.574-1.993-2.37-1.993-3.818 0-3.111 2.261-5.968 6.529-5.968 3.42 0 6.079 2.43 6.079 5.674 0 3.398-2.14 6.134-5.112 6.134-1 0-1.939-.519-2.26-1.134 0 0-.495 1.884-.614 2.344-.223.856-.822 1.925-1.225 2.58A8 8 0 1 0 8 0"/></svg></a></li> @endif
                                        @if ($tk) <li><a href="{{ $tk }}" target="_blank"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M9 0h1.98c.144.715.54 1.617 1.235 2.512C12.895 3.389 13.797 4 15 4v2c-1.753 0-3.07-.814-4-1.829V11a5 5 0 1 1-5-5v2a3 3 0 1 0 3 3V0Z"/></svg></a></li> @endif
                                        @foreach(($w['extra_social'] ?? []) as $sItem)
                                            @if(!empty($sItem['url'] ?? ''))
                                                <li>
                                                    <a href="{{ $sItem['url'] }}" target="_blank">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
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
</div>
