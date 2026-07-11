<!-- Mobile Accordion Footer (under 768px) -->
<div class="d-md-none bg-transparent ttf-mobile-footer">
    @foreach($columns as $col => $c)
        @if ($c['status'] == 'on')
            @php
                $mobileItems = [];
                $mobileSeq = 0;

                foreach ($c['widgets'] as $wIndex => $w) {
                    $wType = $w['type'] ?? 'menu_links';

                    if ($wType === 'seller_zone') {
                        if (footer_widget_flag_on($w['show_seller_panel'] ?? 'on') || (footer_widget_flag_on($w['show_download_app'] ?? 'on') && get_setting('seller_app_link'))) {
                            $mobileItems[] = [
                                'kind' => 'seller_login',
                                'display' => $w['mobile_login_display'] ?? 'toggle',
                                'order' => (int) ($w['mobile_login_order'] ?? (($wIndex + 1) * 10)),
                                'seq' => $mobileSeq++,
                                'wIndex' => $wIndex,
                                'w' => $w,
                            ];
                        }

                        if (footer_widget_flag_on($w['show_become_seller'] ?? 'on')) {
                            $mobileItems[] = [
                                'kind' => 'seller_register',
                                'display' => $w['mobile_register_display'] ?? 'section',
                                'order' => (int) ($w['mobile_register_order'] ?? (($wIndex + 1) * 10 + 1)),
                                'seq' => $mobileSeq++,
                                'wIndex' => $wIndex,
                                'w' => $w,
                            ];
                        }

                        $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                        $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                        $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                        $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                        $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                        $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                        $hasSocialLinks = $fb || $tw || $ig || $yt || $pt || $tk || !empty($w['extra_social']);

                        if (footer_widget_flag_on($w['show_follow_us'] ?? 'on') && !empty($w['subheading_3']) && $hasSocialLinks) {
                            $mobileItems[] = [
                                'kind' => 'seller_social',
                                'display' => $w['mobile_social_display'] ?? 'section',
                                'order' => (int) ($w['mobile_social_order'] ?? (($wIndex + 1) * 10 + 2)),
                                'seq' => $mobileSeq++,
                                'wIndex' => $wIndex,
                                'w' => $w,
                            ];
                        }

                        continue;
                    }

                    if ($wType === 'images_widget') {
                        if (($w['show_deliv'] ?? 'on') === 'on') {
                            $mobileItems[] = [
                                'kind' => 'images_deliv',
                                'display' => $w['deliv_mobile_display'] ?? ($w['mobile_view'] ?? 'section'),
                                'order' => (int) ($w['deliv_mobile_order'] ?? (($wIndex + 1) * 10)),
                                'seq' => $mobileSeq++,
                                'wIndex' => $wIndex,
                                'w' => $w,
                            ];
                        }

                        if (($w['show_pay'] ?? 'on') === 'on') {
                            $mobileItems[] = [
                                'kind' => 'images_pay',
                                'display' => $w['pay_mobile_display'] ?? ($w['mobile_view'] ?? 'section'),
                                'order' => (int) ($w['pay_mobile_order'] ?? (($wIndex + 1) * 10 + 1)),
                                'seq' => $mobileSeq++,
                                'wIndex' => $wIndex,
                                'w' => $w,
                            ];
                        }

                        if (($w['show_trust'] ?? 'on') === 'on') {
                            $mobileItems[] = [
                                'kind' => 'images_trust',
                                'display' => $w['trust_mobile_display'] ?? ($w['mobile_view'] ?? 'section'),
                                'order' => (int) ($w['trust_mobile_order'] ?? (($wIndex + 1) * 10 + 2)),
                                'seq' => $mobileSeq++,
                                'wIndex' => $wIndex,
                                'w' => $w,
                            ];
                        }

                        continue;
                    }

                    // Fallback for regular widgets
                    $mobileItems[] = [
                        'kind' => 'regular',
                        'display' => $w['mobile_view'] ?? 'toggle',
                        'order' => (int) ($w['mobile_order'] ?? (($wIndex + 1) * 10)),
                        'seq' => $mobileSeq++,
                        'wIndex' => $wIndex,
                        'w' => $w,
                    ];
                }

                if (!empty($c['extra_blocks'])) {
                    foreach($c['extra_blocks'] as $ebIdx => $eb) {
                        $eb_show = $eb['show_on'] ?? 'both';
                        if ($eb_show !== 'desktop') {
                            $mobileItems[] = [
                                'kind' => 'extra_block',
                                'display' => $eb['mobile_view'] ?? 'toggle',
                                'order' => (int) ($eb['mobile_order'] ?? 999),
                                'seq' => $mobileSeq++,
                                'ebIdx' => $ebIdx,
                                'eb' => $eb,
                            ];
                        }
                    }
                }

                usort($mobileItems, function($a, $b) {
                    if ($a['order'] === $b['order']) {
                        return $a['seq'] - $b['seq'];
                    }
                    return $a['order'] - $b['order'];
                });
            @endphp

            @foreach($mobileItems as $mobileItem)
                @if (($mobileItem['kind'] ?? '') === 'regular')
                    @php
                        $w = $mobileItem['w'];
                        $wIndex = $mobileItem['wIndex'];
                        $wType = $w['type'] ?? 'menu_links';
                        $widget_id = "widget-col-{$col}-{$wIndex}-mob";
                        $mobileDisplay = $mobileItem['display'] ?? 'toggle';
                        $mobileToggle = $mobileDisplay === 'toggle';
                    @endphp

                    @if($mobileDisplay !== 'hidden')
                        @if($mobileToggle)
                            <div id="{{ $widget_id }}" class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-section">
                                <div class="container">
                                    <div class="aiz-accordion-heading">
                                        <button class="aiz-accordion fs-14 text-white bg-transparent">{{ $w['title'] ?? translate('Menu') }}</button>
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
                                                    @if(!empty($w['lbls']))
                                                        @foreach ($w['lbls'] as $key => $label)
                                                            @if(!empty(trim($label)))
                                                                @php $url = isset($w['lnks'][$key]) ? $w['lnks'][$key] : '#'; @endphp
                                                                <li class="mb-2">
                                                                    <a href="{{ url($url) }}" class="fs-14 text-light animate-underline-white">
                                                                        {{ $label }}
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        @endforeach
                                                    @else
                                                        @php $pages = get_pages_footer(!empty($w['page_ids']) ? $w['page_ids'] : '2,3,4,5,6,7,8,10,11'); @endphp
                                                        @foreach ($pages as $key => $value)
                                                            <li class="mb-2">
                                                                <a href="{{ url($value->slug) }}" class="fs-14 text-light animate-underline-white">
                                                                    {{ $value->title }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                        @if(!empty($w['extra_lbls']))
                                                            @foreach($w['extra_lbls'] as $eIdx => $eLbl)
                                                                @if(!empty(trim($eLbl)))
                                                                    @php $eLnk = $w['extra_lnks'][$eIdx] ?? '#'; @endphp
                                                                    <li class="mb-2">
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
                                                    @else
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
                                                        @if(!empty($w['extra_lbls']))
                                                            @foreach($w['extra_lbls'] as $eIdx => $eLbl)
                                                                @if(!empty(trim($eLbl)))
                                                                    @php $eLnk = $w['extra_lnks'][$eIdx] ?? '#'; @endphp
                                                                    <li class="mb-2 pb-2">
                                                                        <a class="fs-14 text-light animate-underline-white" href="{{ url($eLnk) }}">{{ $eLbl }}</a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </ul>
                                            @elseif ($wType == 'text_html')
                                                <div class="fs-13 text-light" style="line-height: 1.8;">
                                                    {!! $w['html'] ?? '' !!}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="{{ $widget_id }}" class="secure-payment-box ttf-mobile-section mt-3">
                                <div class="container">
                                    <h5 class="secure-payment-title textheading">{{ $w['title'] ?? translate('Menu') }}</h5>
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
                                            @if(!empty($w['lbls']))
                                                @foreach ($w['lbls'] as $key => $label)
                                                    @if(!empty(trim($label)))
                                                        @php $url = isset($w['lnks'][$key]) ? $w['lnks'][$key] : '#'; @endphp
                                                        <li class="mb-2">
                                                            <a href="{{ url($url) }}" class="fs-14 text-light animate-underline-white">
                                                                {{ $label }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @else
                                                @php $pages = get_pages_footer(!empty($w['page_ids']) ? $w['page_ids'] : '2,3,4,5,6,7,8,10,11'); @endphp
                                                @foreach ($pages as $key => $value)
                                                    <li class="mb-2">
                                                        <a href="{{ url($value->slug) }}" class="fs-14 text-light animate-underline-white">
                                                            {{ $value->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                                @if(!empty($w['extra_lbls']))
                                                    @foreach($w['extra_lbls'] as $eIdx => $eLbl)
                                                        @if(!empty(trim($eLbl)))
                                                            @php $eLnk = $w['extra_lnks'][$eIdx] ?? '#'; @endphp
                                                            <li class="mb-2">
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
                                            @else
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
                                                @if(!empty($w['extra_lbls']))
                                                    @foreach($w['extra_lbls'] as $eIdx => $eLbl)
                                                        @if(!empty(trim($eLbl)))
                                                            @php $eLnk = $w['extra_lnks'][$eIdx] ?? '#'; @endphp
                                                            <li class="mb-2 pb-2">
                                                                <a class="fs-14 text-light animate-underline-white" href="{{ url($eLnk) }}">{{ $eLbl }}</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endif
                                        </ul>
                                    @elseif ($wType == 'text_html')
                                        <div class="fs-13 text-light" style="line-height: 1.8;">
                                            {!! $w['html'] ?? '' !!}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                @elseif (in_array(($mobileItem['kind'] ?? ''), ['seller_login', 'seller_register', 'seller_social']))
                    @php
                        $sellerKind = $mobileItem['kind'];
                        $sellerDisplay = $mobileItem['display'] ?? 'section';
                        $sellerToggle = $sellerDisplay === 'toggle';
                        $fb = !empty($w['facebook_link']) ? $w['facebook_link'] : get_setting('facebook_link');
                        $tw = !empty($w['twitter_link']) ? $w['twitter_link'] : get_setting('twitter_link');
                        $ig = !empty($w['instagram_link']) ? $w['instagram_link'] : get_setting('instagram_link');
                        $yt = !empty($w['youtube_link']) ? $w['youtube_link'] : get_setting('youtube_link');
                        $pt = !empty($w['pinterest_link']) ? $w['pinterest_link'] : get_setting('pinterest_link');
                        $tk = !empty($w['tiktok_link']) ? $w['tiktok_link'] : get_setting('foot_social_tk');
                        $sellerSectionTitle = $sellerKind === 'seller_login'
                            ? ($w['title'] ?? translate('Seller Zone'))
                            : ($sellerKind === 'seller_register'
                                ? ($w['subheading_2'] ?? translate('Become A Seller'))
                                : ($w['subheading_3'] ?? translate('Follow Us')));
                        $sellerSectionId = $widget_id . '-' . $sellerKind;
                    @endphp
                    @if($sellerDisplay !== 'hidden')
                        @if($sellerToggle)
                            <div id="{{ $sellerSectionId }}" class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-section">
                                <div class="container">
                                    <div class="aiz-accordion-heading">
                                        <button class="aiz-accordion fs-14 text-white bg-transparent">{{ $sellerSectionTitle }}</button>
                                    </div>
                                    <div class="aiz-accordion-panel bg-transparent">
                                        <div class="py-3">
                                            @if($sellerKind === 'seller_login')
                                                <ul class="list-unstyled mb-0">
                                                    @if(footer_widget_flag_on($w['show_seller_panel'] ?? 'on'))
                                                        <li class="mb-2 pb-2">
                                                            <a class="fs-14 text-light animate-underline-white" href="{{ !empty($w['seller_url']) ? $w['seller_url'] : route('seller.login') }}">
                                                                {{ !empty($w['seller_login_text']) ? translate($w['seller_login_text']) : translate('Login to Seller Panel') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if (footer_widget_flag_on($w['show_download_app'] ?? 'on') && get_setting('seller_app_link'))
                                                        <li class="mb-2">
                                                            <a class="fs-14 text-light animate-underline-white" target="_blank" href="{{ get_setting('seller_app_link') }}">
                                                                {{ !empty($w['download_seller_app_text']) ? translate($w['download_seller_app_text']) : translate('Download Seller App') }}
                                                            </a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            @elseif($sellerKind === 'seller_register')
                                                <ul class="list-unstyled mb-0">
                                                    <li class="mb-2">
                                                        <a href="{{ !empty($w['become_seller_url']) ? $w['become_seller_url'] : route('shops.create') }}" class="fs-14 text-light animate-underline-white">
                                                            {{ !empty($w['become_seller_text']) ? translate($w['become_seller_text']) : translate('Register your shop') }}
                                                        </a>
                                                    </li>
                                                </ul>
                                            @else
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
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div id="{{ $sellerSectionId }}" class="secure-payment-box ttf-mobile-section mt-3">
                                <div class="container">
                                    <h5 class="secure-payment-title textheading">{{ $sellerSectionTitle }}</h5>
                                    @if($sellerKind === 'seller_login')
                                        <ul class="list-unstyled mb-0">
                                            @if(footer_widget_flag_on($w['show_seller_panel'] ?? 'on'))
                                                <li class="mb-2 pb-2">
                                                    <a class="fs-14 text-light animate-underline-white" href="{{ !empty($w['seller_url']) ? $w['seller_url'] : route('seller.login') }}">
                                                        {{ !empty($w['seller_login_text']) ? translate($w['seller_login_text']) : translate('Login to Seller Panel') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if (footer_widget_flag_on($w['show_download_app'] ?? 'on') && get_setting('seller_app_link'))
                                                <li class="mb-2">
                                                    <a class="fs-14 text-light animate-underline-white" target="_blank" href="{{ get_setting('seller_app_link') }}">
                                                        {{ !empty($w['download_seller_app_text']) ? translate($w['download_seller_app_text']) : translate('Download Seller App') }}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    @elseif($sellerKind === 'seller_register')
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-2">
                                                <a href="{{ !empty($w['become_seller_url']) ? $w['become_seller_url'] : route('shops.create') }}" class="fs-14 text-light animate-underline-white">
                                                    {{ !empty($w['become_seller_text']) ? translate($w['become_seller_text']) : translate('Register your shop') }}
                                                </a>
                                            </li>
                                        </ul>
                                    @else
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
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endif
                @elseif (in_array(($mobileItem['kind'] ?? ''), ['images_deliv', 'images_pay', 'images_trust']))
                    @php
                        $w = $mobileItem['w'];
                        $wIndex = $mobileItem['wIndex'];
                        $kind = $mobileItem['kind'];
                        $widget_id = "widget-col-{$col}-{$wIndex}-mob";
                        $mobileDisplay = $mobileItem['display'] ?? ($w['mobile_view'] ?? 'section');
                        $mobileToggle = $mobileDisplay === 'toggle';
                        $deliv_imgs = get_footer_images_helper(!empty($w['deliv_img']) ? $w['deliv_img'] : get_setting('foot_img_deliv'), static_asset('assets/img/delivery_partners_logo.png'));
                        $pay_imgs = get_footer_images_helper(!empty($w['pay_img']) ? $w['pay_img'] : get_setting('foot_img_pay'), static_asset('assets/img/securelypayments.png'));
                        $trust_imgs = get_footer_images_helper(!empty($w['trust_img']) ? $w['trust_img'] : get_setting('foot_img_trust'), static_asset('assets/img/trustpilot.png'));
                        $trust_lnk = !empty($w['trustpilot_lnk']) ? $w['trustpilot_lnk'] : get_setting('foot_lnk_trust', '#');
                        $sectionTitle = $kind === 'images_deliv'
                            ? ($w['title'] ?? translate('Delivery Partners'))
                            : ($kind === 'images_pay'
                                ? ($w['pay_title'] ?? translate('Pay Securely With'))
                                : ($w['trust_title'] ?? translate('What Trustpilot Say’s')));
                        $sectionId = $kind === 'images_deliv' ? $widget_id : ($kind === 'images_pay' ? $widget_id.'-pay' : $widget_id.'-trust');
                    @endphp

                    @if($mobileDisplay !== 'hidden' && $mobileToggle)
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
                    @elseif($mobileDisplay !== 'hidden')
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
            @endforeach
        @endif
    @endforeach
</div>
