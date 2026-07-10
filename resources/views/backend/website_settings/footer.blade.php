@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-footer.css') }}">
<style>
    /* Premium Visual Editor Layout Styles */
    .ttf-editor-layout {
        display: flex;
        gap: 24px;
        margin-top: 15px;
    }
    .ttf-preview-pane {
        flex: 1;
        min-width: 0;
        background: #f1f2f7;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e4e5eb;
    }
    .ttf-preview-title {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .ttf-preview-wrapper {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        border: 2px dashed #b5b5bf;
        position: relative;
    }
    
    /* Config Panel styling */
    .ttf-config-pane {
        width: 440px;
        flex-shrink: 0;
    }
    .ttf-config-card {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #e4e5eb;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 20px;
    }
    
    /* Interactive Preview Hotspots */
    .ttf-hotspot {
        position: relative;
        cursor: pointer;
        transition: all 0.25s ease;
        border: 2px solid transparent !important;
    }
    .ttf-hotspot:hover {
        border-color: #3390f3 !important;
        background: rgba(51, 144, 243, 0.03) !important;
    }
    .ttf-hotspot.active {
        border-color: #3390f3 !important;
        box-shadow: 0 0 0 4px rgba(51, 144, 243, 0.15);
        background: rgba(51, 144, 243, 0.05) !important;
    }
    .ttf-edit-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #3390f3;
        color: #fff;
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        z-index: 10;
        opacity: 0;
        transform: translateY(-5px);
        transition: all 0.25s ease;
        pointer-events: none;
        box-shadow: 0 4px 10px rgba(51, 144, 243, 0.3);
    }
    .ttf-hotspot:hover .ttf-edit-badge {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Settings panel tabs */
    .config-tabs {
        border-bottom: 1px solid #e4e5eb;
        background: #f8f9fa;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        overflow: hidden;
    }
    .config-tab-btn {
        border: none;
        background: none;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
    }
    .config-tab-btn:hover {
        color: #3390f3;
        background: rgba(51, 144, 243, 0.03);
    }
    .config-tab-btn.active {
        color: #3390f3;
        border-bottom-color: #3390f3;
        background: #ffffff;
    }
    
    .tab-content-pane {
        display: none;
        padding: 20px;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .tab-content-pane.active {
        display: block;
    }
    
    /* Widget card builder styling */
    .widget-card {
        border: 1px solid #e4e5eb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .widget-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .menu-link-row {
        background: #f8f9fa;
        border: 1px solid #e4e5eb;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    .menu-link-row .btn-remove-row {
        position: absolute;
        top: -6px;
        right: -6px;
        padding: 2px;
        font-size: 10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Website Footer Builder') }}</h1>
        </div>
    </div>
</div>

<!-- Language Selector Tabs -->
<ul class="nav nav-tabs nav-fill border-light bg-white rounded shadow-sm mb-3">
    @foreach (get_all_active_language() as $key => $language)
        <li class="nav-item">
            <a class="nav-link text-reset @if ($language->code == $lang) active font-weight-bold text-primary @else bg-soft-dark border-light @endif py-3" href="{{ route('website.footer', ['lang'=> $language->code] ) }}">
                <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                <span>{{ $language->name }}</span>
            </a>
        </li>
    @endforeach
</ul>

<!-- Footer Backup & Restore Section -->
<div class="card shadow-sm mb-3">
    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
        <h6 class="mb-0 font-weight-bold text-dark"><i class="las la-sync-alt"></i> {{ translate('Footer Backup & Restore (JSON)') }}</h6>
    </div>
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6 border-right">
                <p class="text-muted fs-12 mb-2">{{ translate('Export your current footer configurations (all widgets, links, text, menus, styling, colors) to a JSON file for backup.') }}</p>
                <a href="{{ route('website.footer.export') }}" class="btn btn-xs btn-primary">
                    <i class="las la-download"></i> {{ translate('Export Footer Settings') }}
                </a>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <p class="text-muted fs-12 mb-2">{{ translate('Restore/import footer configurations from a previously exported JSON file.') }}</p>
                <form action="{{ route('website.footer.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                    @csrf
                    <div class="custom-file custom-file-sm mr-2 flex-grow-1" style="height: auto;">
                        <input type="file" name="footer_file" class="custom-file-input" id="footerFile" accept=".json" required onchange="$(this).next('.custom-file-label').html(this.files[0].name)">
                        <label class="custom-file-label" for="footerFile" style="padding: 0.25rem 0.5rem; height: auto; font-size: 11px;">{{ translate('Choose file') }}</label>
                    </div>
                    <button type="submit" class="btn btn-xs btn-success flex-shrink-0">
                        <i class="las la-upload"></i> {{ translate('Import Settings') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data" onsubmit="refreshColumnIndices()">
    @csrf
    
    <input type="hidden" name="tab" value="footer-builder">
    <input type="hidden" name="lang_edit" value="{{ $lang }}">

    <div class="ttf-editor-layout">
        
        <!-- Live Preview Area -->
        <div class="ttf-preview-pane">
            <div class="ttf-preview-title">
                <i class="las la-eye"></i>
                <span>{{ translate('Live Preview (Click any section to edit)') }}</span>
            </div>
            
            <div class="ttf-preview-wrapper">
                
                @php
                    // Pre-fill colors and values
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
                    
                    // Newsletter
                    $foot_news_show = get_setting('foot_news_show', 'on');
                    $foot_news_title = get_setting('foot_news_title', 'Subscribe to our newsletter for regular updates about Offers, Coupons & more', $lang);
                    $foot_news_btn = get_setting('foot_news_btn', 'Subscribe', $lang);
                    $foot_news_highlight_img = get_setting('foot_news_highlight_img');
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
                    
                    // Bottom Copyright Bar
                    $foot_copy_bg = get_setting('foot_copy_bg', '#5f4d3e');
                    $foot_copy_text = get_setting('foot_copy_text', '#ffffff');
                    $frontend_copyright_text = get_setting('frontend_copyright_text', 'Copyright &copy; 2026 Time to Furnish. All Right Reserved.', $lang);
                    $footer_disclaimer_text = get_setting('footer_disclaimer_text', 'We operate as an independent third-party marketplace.', $lang);
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

                    $columns = \App\Support\FooterDefaults::columns($lang);
                @endphp
                
                <!-- Simulated Frontend Footer Container with CSS Variables mapping -->
                <div class="footer-widget ttf-footer-links-section ttf-hotspot" id="hotspot-general" onclick="activateSection('tab-general', this)" style="--foot-bg-color: {{ $foot_bg_color }}; --foot-head-color: {{ $foot_head_color }}; --foot-text-color: {{ $foot_text_color }}; --foot-hover-color: {{ $foot_hover_color }}; --foot-pad-top: {{ $foot_pad_top }}; --foot-pad-bot: {{ $foot_pad_bot }}; --foot-border-color: {{ $foot_border_color }}; --foot-copy-bg: {{ $foot_copy_bg }}; --foot-copy-text: {{ $foot_copy_text }}; --foot-news-bg: {{ $foot_news_bg }}; --foot-news-border: {{ $foot_news_border }}; --foot-news-btn_bg: {{ $foot_news_btn_bg }}; --foot-social-radius: {{ $foot_social_radius }};
                --foot-news-btn-tx: {{ $foot_news_btn_tx }}; --foot-head-font-size: {{ get_setting('foot_head_font_size', '16px') }}; --foot-body-font-size: {{ get_setting('foot_body_font_size', '13px') }}; --foot-body-line-height: {{ get_setting('foot_body_line_height', '1.8') }}; --foot-col-spacing: {{ get_setting('foot_col_spacing', '20px') }}; --foot-head-margin-bottom: {{ get_setting('foot_head_margin_bottom', '18px') }}; @if(!empty($foot_bg_pattern_left)) --foot-bg-pattern-left: url('{{ uploaded_asset($foot_bg_pattern_left) }}'); @else --foot-bg-pattern-left: none; @endif @if(!empty($foot_bg_pattern_right)) --foot-bg-pattern-right: url('{{ uploaded_asset($foot_bg_pattern_right) }}'); @else --foot-bg-pattern-right: none; @endif @if(!empty($foot_news_highlight_img)) --foot-news-highlight-img: url('{{ uploaded_asset($foot_news_highlight_img) }}'); @endif">
                    <span class="ttf-edit-badge"><i class="las la-cog"></i> {{ translate('General Styles') }}</span>
                    
                    <!-- Sim Newsletter Section -->
                    <div id="preview-newsletter-section" class="footer-widget iuytrey footer-newsletter-section ttf-hotspot @if($foot_news_show == 'off') d-none @endif" onclick="activateSection('tab-newsletter', this); event.stopPropagation();">
                        <span class="ttf-edit-badge"><i class="las la-envelope"></i> {{ translate('Newsletter Settings') }}</span>
                        <div class="container py-2">
                            <div class="col-12 text-center">
                                <h5 class="mb-3 textheading" id="preview-news-title">
                                    {!! str_ireplace('newsletter', '<span class="text-highlight">newsletter</span>', $foot_news_title) !!}
                                </h5>
                                <div class="mx-auto" style="max-width: 480px; display: flex; gap: 6px;">
                                    <input type="text" class="form-control email_input_footer" placeholder="{{ translate('Your Email') }}" disabled style="height: 38px;">
                                    <button type="button" class="btn footer_submit_btn" id="preview-news-btn" style="height: 38px; min-width: 100px;">{{ $foot_news_btn }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sim Columns Grid -->
                    <div class="container mt-4">
                        <div class="row gutters-20">
                            @for($col = 1; $col <= 8; $col++)
                                @php
                                    $col_status = $columns[$col]['status'];
                                    $col_width = $columns[$col]['width'];
                                    $is_bootstrap = str_starts_with($col_width, 'col-') || str_starts_with($col_width, 'ttf-');
                                    $widgets = $columns[$col]['widgets'];
                                @endphp
                                
                                <div id="preview-col-{{ $col }}" class="ttf-hotspot @if($col_status == 'off') d-none @endif {{ $is_bootstrap ? $col_width : '' }}" style="@if(!$is_bootstrap) width: {{ $col_width }} !important; flex: 0 0 {{ $col_width }} !important; max-width: {{ $col_width }} !important; @endif" onclick="activateSection('tab-col-{{ $col }}', this); event.stopPropagation();">
                                    <span class="ttf-edit-badge"><i class="las la-edit"></i> {{ translate('Column') }} {{ $col }}</span>
                                    <div class="ttf-footer-card">
                                        @foreach($widgets as $w)
                                            @php $wType = $w['type'] ?? 'menu_links'; @endphp
                                            @if($wType == 'menu_links')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Menu' }}</h4>
                                                <ul>
                                                    @php $wLbls = $w['lbls'] ?? []; @endphp
                                                    @foreach($wLbls as $lbl)
                                                        <li><a href="#" onclick="return false;">{{ $lbl }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @elseif($wType == 'important_links')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Important Links' }}</h4>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Return Policy</a></li>
                                                    <li><a href="#" onclick="return false;">Privacy Policy</a></li>
                                                </ul>
                                            @elseif($wType == 'my_account')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'My Account' }}</h4>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Login</a></li>
                                                    <li><a href="#" onclick="return false;">Order History</a></li>
                                                </ul>
                                            @elseif($wType == 'text_html')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Text Widget' }}</h4>
                                                <div style="font-size: 13px; line-height: 1.8;">{!! $w['html'] ?? '' !!}</div>
                                            @elseif($wType == 'seller_zone')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Seller Zone' }}</h4>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Login to Seller Panel</a></li>
                                                </ul>
                                                <div class="sub-widget-title">{{ $w['subheading_2'] ?? translate('Join Our Partner Network') }}</div>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Register your shop</a></li>
                                                </ul>
                                                @if(!empty($w['subheading_3']))
                                                    <div class="sub-widget-title">{{ $w['subheading_3'] }}</div>
                                                    <ul class="footer-social-list">
                                                        <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                                                        <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                                                        <li><a href="#" onclick="return false;"><i class="lab la-twitter"></i></a></li>
                                                    </ul>
                                                @endif
                                            @elseif($wType == 'social_icons')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Follow Us' }}</h4>
                                                <ul class="footer-social-list">
                                                    <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                                                    <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                                                </ul>
                                            @elseif($wType == 'images_widget')
                                                @php
                                                    $show_deliv = ($w['show_deliv'] ?? 'on') == 'on';
                                                    $show_pay = ($w['show_pay'] ?? 'on') == 'on';
                                                    $show_trust = ($w['show_trust'] ?? 'on') == 'on';
                                                @endphp
                                                @if($show_deliv)
                                                    <div class="secure-payment-box mb-3">
                                                        <h5 class="secure-payment-title textheading">{{ $w['title'] ?? translate('Delivery Partners') }}</h5>
                                                        @php
                                                            $deliv_img = !empty($w['deliv_img']) ? uploaded_asset($w['deliv_img']) : (get_setting('foot_img_deliv') ? uploaded_asset(get_setting('foot_img_deliv')) : static_asset('assets/img/delivery_partners_logo.png'));
                                                        @endphp
                                                        <img src="{{ $deliv_img }}" alt="" class="secure-payment-img">
                                                    </div>
                                                @endif
                                                @if($show_pay)
                                                    <div class="secure-payment-box mb-3">
                                                        <h5 class="secure-payment-title textheading">{{ $show_deliv ? translate('Pay Securely With') : ($w['title'] ?? translate('Pay Securely With')) }}</h5>
                                                        @php
                                                            $pay_img = !empty($w['pay_img']) ? uploaded_asset($w['pay_img']) : (get_setting('foot_img_pay') ? uploaded_asset(get_setting('foot_img_pay')) : static_asset('assets/img/securelypayments.png'));
                                                        @endphp
                                                        <img src="{{ $pay_img }}" alt="" class="secure-payment-img">
                                                    </div>
                                                @endif
                                                @if($show_trust)
                                                    <div class="secure-payment-box">
                                                        <h5 class="secure-payment-title textheading">{{ translate('What Trustpilot Say’s') }}</h5>
                                                        @php
                                                            $trust_img = !empty($w['trust_img']) ? uploaded_asset($w['trust_img']) : (get_setting('foot_img_trust') ? uploaded_asset(get_setting('foot_img_trust')) : static_asset('assets/img/trustpilot.png'));
                                                        @endphp
                                                        <img src="{{ $trust_img }}" alt="" class="secure-payment-img trustpilot-img">
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                    
                </div>
                
                <!-- Bottom Copyright Disclaimer -->
                <div class="ttf-footer-bottom-bar ttf-hotspot" onclick="activateSection('tab-bottom-bar', this); event.stopPropagation();">
                    <span class="ttf-edit-badge"><i class="las la-copyright"></i> {{ translate('Bottom Bar') }}</span>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 text-left">
                                <div class="sim-copyright" id="preview-copyright">{!! $frontend_copyright_text !!}</div>
                            </div>
                            <div class="col-lg-6 text-right">
                                <div class="sim-disclaimer" id="preview-disclaimer">{!! $footer_disclaimer_text !!}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Config panel on the right -->
        <div class="ttf-config-pane">
            <div class="ttf-config-card">
                
                <div class="config-tabs d-flex">
                    <button type="button" class="config-tab-btn active" onclick="showTab('tab-general', this)">
                        {{ translate('Styles') }}
                    </button>
                    <button type="button" class="config-tab-btn" onclick="showTab('tab-newsletter', this)">
                        {{ translate('Newsletter') }}
                    </button>
                    <button type="button" class="config-tab-btn" onclick="showTab('tab-columns', this)">
                        {{ translate('Columns') }}
                    </button>
                    <button type="button" class="config-tab-btn" onclick="showTab('tab-bottom-bar', this)">
                        {{ translate('Bottom Bar') }}
                    </button>
                </div>
                
                <!-- Tab Pane: General Styles -->
                <div id="tab-general" class="tab-content-pane active">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('General Styling & Dimensions') }}</h6>
                    
                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_bg_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_bg_color" id="color-input-foot_bg_color" value="{{ $foot_bg_color }}" oninput="updateLiveStyle('foot_bg_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_bg_color, '#') && strlen($foot_bg_color) == 7 ? $foot_bg_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_bg_color').value = this.value; updateLiveStyle('foot_bg_color', this.value)">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_bg_img">
                            <input type="hidden" name="foot_bg_img" class="selected-files" value="{{ get_setting('foot_bg_img') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Mobile Background Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_mob_bg_img">
                            <input type="hidden" name="foot_mob_bg_img" class="selected-files" value="{{ get_setting('foot_mob_bg_img') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <!-- Figma Pattern Images Left and Right -->
                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Left Pattern Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_bg_pattern_left">
                            <input type="hidden" name="foot_bg_pattern_left" class="selected-files" value="{{ get_setting('foot_bg_pattern_left') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Right Pattern Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_bg_pattern_right">
                            <input type="hidden" name="foot_bg_pattern_right" class="selected-files" value="{{ get_setting('foot_bg_pattern_right') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_top">
                                <input type="text" class="form-control" name="foot_pad_top" value="{{ $foot_pad_top }}" placeholder="45px" oninput="updateLiveStyle('foot_pad_top', this.value)">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_bot">
                                <input type="text" class="form-control" name="foot_pad_bot" value="{{ $foot_pad_bot }}" placeholder="45px" oninput="updateLiveStyle('foot_pad_bot', this.value)">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_left">
                                <input type="text" class="form-control" name="foot_pad_left" value="{{ $foot_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_right">
                                <input type="text" class="form-control" name="foot_pad_right" value="{{ $foot_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_top">
                                <input type="text" class="form-control" name="foot_mob_pad_top" value="{{ $foot_mob_pad_top }}" placeholder="12px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_bot">
                                <input type="text" class="form-control" name="foot_mob_pad_bot" value="{{ $foot_mob_pad_bot }}" placeholder="12px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_left">
                                <input type="text" class="form-control" name="foot_mob_pad_left" value="{{ $foot_mob_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_right">
                                <input type="text" class="form-control" name="foot_mob_pad_right" value="{{ $foot_mob_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Typography & Spacing -->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Header Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_head_font_size">
                                <input type="text" class="form-control" name="foot_head_font_size" value="{{ get_setting('foot_head_font_size', '16px') }}" placeholder="16px" oninput="updateLiveStyle('foot_head_font_size', this.value)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Body Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_body_font_size">
                                <input type="text" class="form-control" name="foot_body_font_size" value="{{ get_setting('foot_body_font_size', '13px') }}" placeholder="13px" oninput="updateLiveStyle('foot_body_font_size', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Header Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_head_font_size">
                                <input type="text" class="form-control" name="foot_mob_head_font_size" value="{{ $foot_mob_head_font_size }}" placeholder="14px">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Body Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_body_font_size">
                                <input type="text" class="form-control" name="foot_mob_body_font_size" value="{{ $foot_mob_body_font_size }}" placeholder="13px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Column Gap') }}</label>
                                <input type="hidden" name="types[]" value="foot_col_spacing">
                                <input type="text" class="form-control" name="foot_col_spacing" value="{{ get_setting('foot_col_spacing', '20px') }}" placeholder="20px" oninput="updateLiveStyle('foot_col_spacing', this.value)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Body Line Height') }}</label>
                                <input type="hidden" name="types[]" value="foot_body_line_height">
                                <input type="text" class="form-control" name="foot_body_line_height" value="{{ get_setting('foot_body_line_height', '1.8') }}" placeholder="1.8" oninput="updateLiveStyle('foot_body_line_height', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Heading Bottom Margin') }}</label>
                        <input type="hidden" name="types[]" value="foot_head_margin_bottom">
                        <input type="text" class="form-control" name="foot_head_margin_bottom" value="{{ get_setting('foot_head_margin_bottom', '18px') }}" placeholder="18px" oninput="updateLiveStyle('foot_head_margin_bottom', this.value)">
                    </div>
                    
                    <!-- Colors Config -->
                    <div class="form-group">
                        <label class="form-label">{{ translate('Border / Divider Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_border_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_border_color" id="color-input-foot_border_color" value="{{ $foot_border_color }}" oninput="updateLiveStyle('foot_border_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_border_color, '#') && strlen($foot_border_color) == 7 ? $foot_border_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_border_color').value = this.value; updateLiveStyle('foot_border_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Heading Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_head_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_head_color" id="color-input-foot_head_color" value="{{ $foot_head_color }}" oninput="updateLiveStyle('foot_head_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_head_color, '#') && strlen($foot_head_color) == 7 ? $foot_head_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_head_color').value = this.value; updateLiveStyle('foot_head_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Body Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_text_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_text_color" id="color-input-foot_text_color" value="{{ $foot_text_color }}" oninput="updateLiveStyle('foot_text_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_text_color, '#') && strlen($foot_text_color) == 7 ? $foot_text_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_text_color').value = this.value; updateLiveStyle('foot_text_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Hover / Highlight Underline Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_hover_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_hover_color" id="color-input-foot_hover_color" value="{{ $foot_hover_color }}" oninput="updateLiveStyle('foot_hover_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_hover_color, '#') && strlen($foot_hover_color) == 7 ? $foot_hover_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_hover_color').value = this.value; updateLiveStyle('foot_hover_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Social Icons Radius') }}</label>
                        <input type="hidden" name="types[]" value="foot_social_radius">
                        <input type="text" class="form-control" name="foot_social_radius" value="{{ $foot_social_radius }}" placeholder="4px" oninput="updateLiveStyle('foot_social_radius', this.value)">
                    </div>
                </div>
                
                <!-- Tab Pane: Newsletter Settings -->
                <div id="tab-newsletter" class="tab-content-pane">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('Newsletter Widget Settings') }}</h6>
                    
                    <div class="form-group row align-items-center">
                        <label class="col-8 form-label font-weight-medium mb-0">{{ translate('Show Newsletter Section?') }}</label>
                        <div class="col-4 text-right">
                            <input type="hidden" name="types[]" value="foot_news_show">
                            <input type="hidden" name="foot_news_show" id="foot_news_show_val" value="{{ $foot_news_show }}">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="toggleNewsletter(this)" @if($foot_news_show == 'on') checked @endif>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Title') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="foot_news_title">
                        <input type="text" class="form-control" name="foot_news_title" value="{{ $foot_news_title }}" oninput="updateNewsletterTitle(this.value)">
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Button Text') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="foot_news_btn">
                        <input type="text" class="form-control" name="foot_news_btn" value="{{ $foot_news_btn }}" oninput="updateLiveText('preview-news-btn', this.value)">
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Newsletter Highlight Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_news_highlight_img">
                            <input type="hidden" name="foot_news_highlight_img" class="selected-files" value="{{ get_setting('foot_news_highlight_img') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Input Box Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_bg">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_bg" id="color-input-foot_news_bg" value="{{ $foot_news_bg }}" oninput="updateLiveStyle('foot_news_bg', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_bg, '#') && strlen($foot_news_bg) == 7 ? $foot_news_bg : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_bg').value = this.value; updateLiveStyle('foot_news_bg', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Input Box Border Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_border" id="color-input-foot_news_border" value="{{ $foot_news_border }}" oninput="updateLiveStyle('foot_news_border', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_border, '#') && strlen($foot_news_border) == 7 ? $foot_news_border : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_border').value = this.value; updateLiveStyle('foot_news_border', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Button Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_btn_bg">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_btn_bg" id="color-input-foot_news_btn_bg" value="{{ $foot_news_btn_bg }}" oninput="updateLiveStyle('foot_news_btn_bg', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_btn_bg, '#') && strlen($foot_news_btn_bg) == 7 ? $foot_news_btn_bg : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_btn_bg').value = this.value; updateLiveStyle('foot_news_btn_bg', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Button Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_btn_tx">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_btn_tx" id="color-input-foot_news_btn_tx" value="{{ $foot_news_btn_tx }}" oninput="updateLiveStyle('foot_news_btn_tx', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_btn_tx, '#') && strlen($foot_news_btn_tx) == 7 ? $foot_news_btn_tx : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_btn_tx').value = this.value; updateLiveStyle('foot_news_btn_tx', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Section Border Position') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border_pos">
                        <select class="form-control" name="foot_news_border_pos">
                            <option value="none" @if($foot_news_border_pos == 'none') selected @endif>{{ translate('None') }}</option>
                            <option value="top" @if($foot_news_border_pos == 'top') selected @endif>{{ translate('Top Only') }}</option>
                            <option value="bottom" @if($foot_news_border_pos == 'bottom') selected @endif>{{ translate('Bottom Only') }}</option>
                            <option value="top-bottom" @if($foot_news_border_pos == 'top-bottom') selected @endif>{{ translate('Top & Bottom') }}</option>
                            <option value="all" @if($foot_news_border_pos == 'all') selected @endif>{{ translate('All Sides') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Section Border Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_border_color" id="color-input-foot_news_border_color" value="{{ $foot_news_border_color }}">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_border_color, '#') && strlen($foot_news_border_color) == 7 ? $foot_news_border_color : '#685b4e' }}" oninput="document.getElementById('color-input-foot_news_border_color').value = this.value">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Section Border Width') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border_width">
                        <input type="text" class="form-control" name="foot_news_border_width" value="{{ $foot_news_border_width }}" placeholder="e.g. 1.5px">
                    </div>

                    <h6 class="fw-700 text-dark mb-2 mt-3 border-bottom pb-1 fs-12">{{ translate('Newsletter Padding Settings') }}</h6>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_top">
                                <input type="text" class="form-control" name="foot_news_pad_top" value="{{ $foot_news_pad_top }}" placeholder="24px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_bot">
                                <input type="text" class="form-control" name="foot_news_pad_bot" value="{{ $foot_news_pad_bot }}" placeholder="24px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_left">
                                <input type="text" class="form-control" name="foot_news_pad_left" value="{{ $foot_news_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_right">
                                <input type="text" class="form-control" name="foot_news_pad_right" value="{{ $foot_news_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_top">
                                <input type="text" class="form-control" name="foot_news_mob_pad_top" value="{{ $foot_news_mob_pad_top }}" placeholder="8px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_bot">
                                <input type="text" class="form-control" name="foot_news_mob_pad_bot" value="{{ $foot_news_mob_pad_bot }}" placeholder="8px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_left">
                                <input type="text" class="form-control" name="foot_news_mob_pad_left" value="{{ $foot_news_mob_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_right">
                                <input type="text" class="form-control" name="foot_news_mob_pad_right" value="{{ $foot_news_mob_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab Pane: Footer Columns (1 to 8) -->
                <div id="tab-columns" class="tab-content-pane">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('Configure Grid Columns') }}</h6>

                    <div class="form-group border rounded p-3 bg-soft-light">
                        <label class="form-label font-weight-bold mb-2">{{ translate('Grid Presets') }}</label>
                        <div class="btn-group btn-group-sm d-flex" role="group">
                            @for($preset = 1; $preset <= 5; $preset++)
                                <button type="button" class="btn btn-soft-primary" onclick="setFooterGridColumns({{ $preset }})">{{ $preset }}</button>
                            @endfor
                        </div>
                        <small class="form-text text-muted">{{ translate('Use a preset for equal columns, then adjust each column width manually below.') }}</small>
                    </div>
                    
                    <div class="accordion" id="columns-accordion">
                        @for($col = 1; $col <= 8; $col++)
                            @php
                                $col_status = $columns[$col]['status'];
                                $col_width = $columns[$col]['width'];
                                $widgets = $columns[$col]['widgets'];
                            @endphp
                            
                            <div class="card shadow-none border mb-3 @if($col_status == 'off') d-none @endif" id="card-col-settings-{{ $col }}">
                                <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
                                    <div class="c-pointer flex-grow-1" data-toggle="collapse" data-target="#collapse-col-{{ $col }}">
                                        <span class="font-weight-bold text-dark fs-12 card-col-title-text">{{ translate('Column') }} {{ $col }} Widgets</span>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-link py-0 text-dark btn-move-col-up" onclick="moveColumnUp(this); event.stopPropagation();" title="{{ translate('Move Left/Up') }}"><i class="las la-arrow-up"></i></button>
                                        <button type="button" class="btn btn-xs btn-link py-0 text-dark btn-move-col-down" onclick="moveColumnDown(this); event.stopPropagation();" title="{{ translate('Move Right/Down') }}"><i class="las la-arrow-down"></i></button>
                                        <button type="button" class="btn btn-xs btn-link py-0 text-info btn-copy-col" onclick="copyColumn({{ $col }}, this); event.stopPropagation();" title="{{ translate('Copy Column') }}"><i class="las la-copy"></i></button>
                                        <button type="button" class="btn btn-xs btn-link py-0 text-danger btn-delete-col" onclick="deleteColumn({{ $col }}, this); event.stopPropagation();" title="{{ translate('Delete Column') }}"><i class="las la-trash"></i></button>
                                    </div>
                                </div>
                                
                                <div id="collapse-col-{{ $col }}" class="collapse @if($col == 4) show @endif" data-parent="#columns-accordion">
                                    <div class="card-body p-3">
                                        
                                        <!-- Column Status -->
                                        <div class="form-group row align-items-center mb-3">
                                            <label class="col-8 form-label font-weight-medium mb-0">{{ translate('Show Column?') }}</label>
                                            <div class="col-4 text-right">
                                                <input type="hidden" name="types[]" value="foot_col_{{ $col }}_status">
                                                <input type="hidden" name="foot_col_{{ $col }}_status" id="foot_col_{{ $col }}_status_val" value="{{ $col_status }}">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input type="checkbox" onchange="toggleColumn({{ $col }}, this)" @if($col_status == 'on') checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Custom Column Width in % or px (or Bootstrap class) -->
                                        <div class="form-group border-bottom pb-3">
                                            <label class="form-label font-weight-bold">{{ translate('Column Width') }}</label>
                                            <input type="hidden" name="types[]" value="foot_col_{{ $col }}_width">
                                            <input type="text" class="form-control" name="foot_col_{{ $col }}_width" value="{{ $col_width }}" placeholder="e.g. 20%, 18%, 250px, col-lg-3" oninput="updateColumnWidth({{ $col }}, this.value)">
                                            <small class="form-text text-muted">{{ translate('Enter percentage (e.g. 20%), pixels (e.g. 250px), or bootstrap class (e.g. col-lg-3)') }}</small>
                                        </div>

                                        <!-- Add widget selector -->
                                        <div class="form-group mt-2">
                                            <label class="form-label font-weight-bold text-primary">{{ translate('Add Content Widget') }}</label>
                                            <div class="d-flex gap-2">
                                                <select class="form-control form-control-sm mr-2" id="add-widget-select-{{ $col }}">
                                                    <option value="menu_links">{{ translate('Custom Menu Links') }}</option>
                                                    <option value="important_links">{{ translate('Important Links (Auto Pages)') }}</option>
                                                    <option value="my_account">{{ translate('My Account Links') }}</option>
                                                    <option value="text_html">{{ translate('Custom Text / HTML') }}</option>
                                                    <option value="seller_zone">{{ translate('Seller Zone Composite') }}</option>
                                                    <option value="images_widget">{{ translate('Delivery & Secure Payment Logos') }}</option>
                                                    <option value="social_icons">{{ translate('Social Follow Icons') }}</option>
                                                </select>
                                                <button type="button" class="btn btn-sm btn-primary" onclick="addWidget({{ $col }})">{{ translate('Add') }}</button>
                                            </div>
                                        </div>

                                        <!-- Repeater List Container for Drag and Drop Widgets -->
                                        <div class="widgets-list mt-3" id="widgets-list-{{ $col }}" data-col="{{ $col }}">
                                            <input type="hidden" name="types[][{{ $lang }}]" value="foot_col_{{ $col }}_widgets">
                                            <input type="hidden" name="types[][{{ $lang }}]" value="foot_col_{{ $col }}_extra_blocks">
                                            
                                            @foreach($widgets as $wIndex => $w)
                                                @php
                                                    $wType = $w['type'] ?? 'menu_links';
                                                    $wTitle = $w['title'] ?? '';
                                                @endphp
                                                
                                                @if ($wType == 'menu_links')
                                                    <div class="widget-card card mb-3 border" data-type="menu_links" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                                                            <span class="font-weight-bold text-primary"><i class="las la-list"></i> {{ translate('Menu Links') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="menu_links">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Menu Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Menu Title" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="menu-links-container">
                                                                @php
                                                                    $wLbls = $w['lbls'] ?? [];
                                                                    $wLnks = $w['lnks'] ?? [];
                                                                @endphp
                                                                @foreach($wLbls as $lIdx => $lbl)
                                                                    @php $lnk = $wLnks[$lIdx] ?? ''; @endphp
                                                                    <div class="menu-link-row">
                                                                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, {{ $col }})"><i class="las la-times"></i></button>
                                                                        <div class="form-group mb-1">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lbls][]" value="{{ $lbl }}" placeholder="Link Label" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                        <div class="form-group mb-0">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lnks][]" value="{{ $lnk }}" placeholder="Link URL" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, {{ $col }}, {{ $wIndex }})">
                                                                <i class="las la-plus"></i> {{ translate('Add New Link') }}
                                                            </button>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'menu_links'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'important_links')
                                                    <div class="widget-card card mb-3 border" data-type="important_links" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                                                            <span class="font-weight-bold text-primary"><i class="las la-link"></i> {{ translate('Important Links (Auto Pages)') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="important_links">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Important Links" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Page IDs (Comma-separated)') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][page_ids]" value="{{ $w['page_ids'] ?? '2,3,4,5,6,7,8,10,11' }}" placeholder="e.g. 2,3,4,5" oninput="updateColumnPreview({{ $col }})">
                                                                <small class="form-text text-muted">{{ translate('Specify Page IDs to display automatically.') }}</small>
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'important_links'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'my_account')
                                                    <div class="widget-card card mb-3 border" data-type="my_account" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                                                            <span class="font-weight-bold text-primary"><i class="las la-user"></i> {{ translate('My Account Links') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="my_account">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="My Account" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Login Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][login_text]" value="{{ $w['login_text'] ?? 'Login' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Logout Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][logout_text]" value="{{ $w['logout_text'] ?? 'Logout' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Order History Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][order_history_text]" value="{{ $w['order_history_text'] ?? 'Order History' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Wishlist Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][wishlist_text]" value="{{ $w['wishlist_text'] ?? 'My Wishlist' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Track Order Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][track_order_text]" value="{{ $w['track_order_text'] ?? 'Track Order' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'my_account'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'text_html')
                                                    <div class="widget-card card mb-3 border" data-type="text_html" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-success">
                                                            <span class="font-weight-bold text-success"><i class="las la-code"></i> {{ translate('Custom Text / HTML') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="text_html">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Widget Title" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group mb-0">
                                                                <label class="form-label">{{ translate('HTML Content') }}</label>
                                                                <textarea class="form-control" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][html]" rows="5" placeholder="HTML or Text content" oninput="updateColumnPreview({{ $col }})">{{ $w['html'] ?? '' }}</textarea>
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'text_html'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'seller_zone')
                                                    <div class="widget-card card mb-3 border" data-type="seller_zone" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-warning">
                                                            <span class="font-weight-bold text-warning"><i class="las la-store"></i> {{ translate('Seller Zone Composite') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="seller_zone">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Seller Zone" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Seller Login URL') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][seller_url]" value="{{ $w['seller_url'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Seller Login Link Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][seller_login_text]" value="{{ $w['seller_login_text'] ?? 'Login to Seller Panel' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Become Seller URL') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][become_seller_url]" value="{{ $w['become_seller_url'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Register Shop Link Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][become_seller_text]" value="{{ $w['become_seller_text'] ?? 'Register your shop' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Download App Link Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][download_seller_app_text]" value="{{ $w['download_seller_app_text'] ?? 'Download Seller App' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Join Network Header') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][subheading_2]" value="{{ $w['subheading_2'] ?? 'Join Our Partner Network' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group mb-3 border-bottom pb-3">
                                                                <label class="form-label font-weight-bold">{{ translate('Follow Us Header') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][subheading_3]" value="{{ $w['subheading_3'] ?? 'Follow Us' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Seller social links -->
                                                            <h6 class="fs-10 font-weight-bold text-dark mb-2">{{ translate('Seller Social Link Overrides') }}</h6>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Facebook Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][facebook_link]" value="{{ $w['facebook_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Twitter (X) Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][twitter_link]" value="{{ $w['twitter_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Instagram Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][instagram_link]" value="{{ $w['instagram_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Youtube Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][youtube_link]" value="{{ $w['youtube_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Pinterest Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pinterest_link]" value="{{ $w['pinterest_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('TikTok Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][tiktok_link]" value="{{ $w['tiktok_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'seller_zone'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'images_widget')
                                                    <div class="widget-card card mb-3 border" data-type="images_widget" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-info">
                                                            <span class="font-weight-bold text-info"><i class="las la-images"></i> {{ translate('Delivery & Payment Logos') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="images_widget">
                                                            
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Delivery Partners" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label fs-10">{{ translate('Delivery') }}</label>
                                                                        <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_deliv]" onchange="updateColumnPreview({{ $col }})">
                                                                            <option value="on" @if(($w['show_deliv'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                            <option value="off" @if(($w['show_deliv'] ?? 'on') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label fs-10">{{ translate('Payment') }}</label>
                                                                        <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_pay]" onchange="updateColumnPreview({{ $col }})">
                                                                            <option value="on" @if(($w['show_pay'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                            <option value="off" @if(($w['show_pay'] ?? 'on') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group">
                                                                        <label class="form-label fs-10">{{ translate('Trustpilot') }}</label>
                                                                        <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_trust]" onchange="updateColumnPreview({{ $col }})">
                                                                            <option value="on" @if(($w['show_trust'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                            <option value="off" @if(($w['show_trust'] ?? 'on') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Delivery Image -->
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Delivery Image(s)') }}</label>
                                                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                                    </div>
                                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                    <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][deliv_img]" class="selected-files" value="{{ $w['deliv_img'] ?? '' }}">
                                                                </div>
                                                                <div class="file-preview box sm"></div>
                                                            </div>

                                                            <!-- Payment Image -->
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Pay Securely Image(s)') }}</label>
                                                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                                    </div>
                                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                    <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pay_img]" class="selected-files" value="{{ $w['pay_img'] ?? '' }}">
                                                                </div>
                                                                <div class="file-preview box sm"></div>
                                                            </div>

                                                            <!-- Trustpilot Image -->
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Trustpilot Image(s)') }}</label>
                                                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                                    </div>
                                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                    <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trust_img]" class="selected-files" value="{{ $w['trust_img'] ?? '' }}">
                                                                </div>
                                                                <div class="file-preview box sm"></div>
                                                            </div>

                                                            <div class="form-group mb-2">
                                                                <label class="form-label">{{ translate('Trustpilot Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trustpilot_lnk]" value="{{ $w['trustpilot_lnk'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'images_widget'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'social_icons')
                                                    <div class="widget-card card mb-3 border" data-type="social_icons" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-purple">
                                                            <span class="font-weight-bold text-purple"><i class="las la-share-alt"></i> {{ translate('Social Follow Icons') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="social_icons">
                                                            <div class="form-group mb-3 border-bottom pb-3">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Follow Us" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Social URL fields -->
                                                            <h6 class="fs-10 font-weight-bold text-dark mb-2">{{ translate('Social Link Connections') }}</h6>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Facebook Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][facebook_link]" value="{{ $w['facebook_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Twitter (X) Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][twitter_link]" value="{{ $w['twitter_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Instagram Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][instagram_link]" value="{{ $w['instagram_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Youtube Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][youtube_link]" value="{{ $w['youtube_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Pinterest Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pinterest_link]" value="{{ $w['pinterest_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('TikTok Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][tiktok_link]" value="{{ $w['tiktok_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'social_icons'])
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- ═══ EXTRA LINK BLOCKS — per column repeater ═══ -->
                                        @php $extra_blocks = $columns[$col]['extra_blocks'] ?? []; @endphp
                                        <div class="border-top mt-3 pt-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="font-weight-bold text-dark fs-12">
                                                    <i class="las la-layer-group text-secondary"></i>
                                                    {{ translate('Extra Link Blocks') }}
                                                </span>
                                                <button type="button" class="btn btn-xs btn-soft-secondary" onclick="addExtraBlock({{ $col }})">
                                                    <i class="las la-plus"></i> {{ translate('Add Block') }}
                                                </button>
                                            </div>
                                            <small class="form-text text-muted d-block mb-2">{{ translate('Add extra custom link sections below all widgets in this column. Each block has a heading and unlimited links. Choose if it shows on desktop, mobile, or both.') }}</small>

                                            <div class="extra-blocks-list" id="extra-blocks-list-{{ $col }}" data-col="{{ $col }}">
                                                @foreach($extra_blocks as $bIdx => $block)
                                                    <div class="extra-block-card card mb-2 border border-secondary">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center" style="background:#f5f3f0;">
                                                            <span class="text-secondary font-weight-bold fs-11"><i class="las la-grip-vertical mr-1"></i>{{ translate('Link Block') }} #{{ $bIdx + 1 }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeExtraBlock(this, {{ $col }})"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-2">
                                                            <div class="row">
                                                                <div class="col-8">
                                                                    <div class="form-group mb-2">
                                                                        <label class="form-label fs-10 mb-1">{{ translate('Block Heading') }}</label>
                                                                        <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][title]" value="{{ $block['title'] ?? '' }}" placeholder="{{ translate('e.g. Before a Seller') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-4">
                                                                    <div class="form-group mb-2">
                                                                        <label class="form-label fs-10 mb-1">{{ translate('Show On') }}</label>
                                                                        <select class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][show_on]">
                                                                            <option value="both" @if(($block['show_on'] ?? 'both') == 'both') selected @endif>{{ translate('Both') }}</option>
                                                                            <option value="desktop" @if(($block['show_on'] ?? '') == 'desktop') selected @endif>{{ translate('Desktop only') }}</option>
                                                                            <option value="mobile" @if(($block['show_on'] ?? '') == 'mobile') selected @endif>{{ translate('Mobile only') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="extra-block-links-container mb-1">
                                                                @foreach($block['lbls'] ?? [] as $lIdx => $lbl)
                                                                    @php $lnk = $block['lnks'][$lIdx] ?? ''; @endphp
                                                                    <div class="extra-link-row d-flex align-items-start gap-1 mb-1">
                                                                        <button type="button" class="btn btn-xs btn-danger flex-shrink-0 mt-1" onclick="removeExtraLinkRow(this, {{ $col }})"><i class="las la-times"></i></button>
                                                                        <div class="flex-grow-1">
                                                                            <input type="text" class="form-control form-control-sm mb-1" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][lbls][]" value="{{ $lbl }}" placeholder="{{ translate('Link Label') }}">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][lnks][]" value="{{ $lnk }}" placeholder="{{ translate('Link URL') }}">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraLinkRow(this, {{ $col }})">
                                                                <i class="las la-plus"></i> {{ translate('Add Link') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- ═══ END EXTRA LINK BLOCKS ═══ -->

                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    
                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="addNewColumn()"><i class="las la-plus"></i> {{ translate('Add New Column') }}</button>
                    </div>
                </div>
                
                <!-- Tab Pane: Bottom Bar Settings -->
                <div id="tab-bottom-bar" class="tab-content-pane">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('Copyright & Disclaimer Panel') }}</h6>
                    
                    <div class="form-group">
                        <label class="form-label">{{ translate('Bottom Bar Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_copy_bg">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_copy_bg" id="color-input-foot_copy_bg" value="{{ $foot_copy_bg }}" oninput="updateLiveStyle('foot_copy_bg', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_copy_bg, '#') && strlen($foot_copy_bg) == 7 ? $foot_copy_bg : '#ffffff' }}" oninput="document.getElementById('color-input-foot_copy_bg').value = this.value; updateLiveStyle('foot_copy_bg', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Bottom Bar Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_copy_text">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_copy_text" id="color-input-foot_copy_text" value="{{ $foot_copy_text }}" oninput="updateLiveStyle('foot_copy_text', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_copy_text, '#') && strlen($foot_copy_text) == 7 ? $foot_copy_text : '#ffffff' }}" oninput="document.getElementById('color-input-foot_copy_text').value = this.value; updateLiveStyle('foot_copy_text', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Copyright Text') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
                        <textarea class="form-control aiz-text-editor" name="frontend_copyright_text" rows="4" data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]'>{{ $frontend_copyright_text }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Disclaimer Text') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="footer_disclaimer_text">
                        <textarea class="form-control aiz-text-editor" name="footer_disclaimer_text" rows="5" data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]'>{{ $footer_disclaimer_text }}</textarea>
                    </div>

                    <h6 class="fw-700 text-dark mb-2 mt-3 border-bottom pb-1 fs-12">{{ translate('Bottom Bar Padding Settings') }}</h6>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_top">
                                <input type="text" class="form-control" name="foot_bar_pad_top" value="{{ $foot_bar_pad_top }}" placeholder="10px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_bot">
                                <input type="text" class="form-control" name="foot_bar_pad_bot" value="{{ $foot_bar_pad_bot }}" placeholder="10px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_left">
                                <input type="text" class="form-control" name="foot_bar_pad_left" value="{{ $foot_bar_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_right">
                                <input type="text" class="form-control" name="foot_bar_pad_right" value="{{ $foot_bar_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_top">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_top" value="{{ $foot_bar_mob_pad_top }}" placeholder="10px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_bot">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_bot" value="{{ $foot_bar_mob_pad_bot }}" placeholder="12px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_left">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_left" value="{{ $foot_bar_mob_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_right">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_right" value="{{ $foot_bar_mob_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Button footer -->
                <div class="p-3 bg-light border-top text-right" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2">{{ translate('Save Footer Settings') }}</button>
                </div>
                
            </div>
        </div>
        
    </div>
</form>

@section('script')
<script>
    const footerBuilderLang = @json($lang);

    function columnCards() {
        let container = document.getElementById('columns-accordion');
        if (!container) return [];
        return Array.from(container.children).filter(function(child) {
            return child.classList.contains('card');
        });
    }

    function widgetsTypeInputHtml(col) {
        return '<input type="hidden" name="types[][' + footerBuilderLang + ']" value="foot_col_' + col + '_widgets">';
    }

    function ensureWidgetsTypeInput(container, col) {
        if (!container) return;
        let input = container.querySelector('input[name^="types"]');
        if (!input) {
            container.insertAdjacentHTML('afterbegin', widgetsTypeInputHtml(col));
            return;
        }
        input.value = 'foot_col_' + col + '_widgets';
    }

    function resetWidgetsList(container, col) {
        if (!container) return;
        container.innerHTML = widgetsTypeInputHtml(col);
    }

    // Tab Panel Switcher
    function showTab(tabId, btn) {
        document.querySelectorAll('.config-tab-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');
        
        document.querySelectorAll('.tab-content-pane').forEach(function(p) {
            p.classList.remove('active');
        });
        document.getElementById(tabId).classList.add('active');
    }
    
    // When clicking a simulated hotspot, switch to the right tab/group
    function activateSection(tabId, el) {
        document.querySelectorAll('.ttf-hotspot').forEach(function(h) {
            h.classList.remove('active');
        });
        el.classList.add('active');
        
        let tabType = tabId.split('-')[1]; // e.g. general, newsletter, col, bottom
        let btn = null;
        if(tabType === 'col') {
            btn = document.querySelector('.config-tab-btn[onclick*="tab-columns"]');
            showTab('tab-columns', btn);
            
            let colNum = tabId.split('-')[2];
            $('.collapse').collapse('hide');
            $('#collapse-col-' + colNum).collapse('show');
            
            let targetCard = document.getElementById('card-col-settings-' + colNum);
            if(targetCard) {
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        } else {
            btn = document.querySelector('.config-tab-btn[onclick*="' + tabId + '"]');
            if(btn) {
                showTab(tabId, btn);
            }
        }
    }
    
    // Live update functions using CSS Variables mapping
    function updateLiveStyle(key, val) {
        let root = document.getElementById('hotspot-general');
        if (!root) return;
        
        if (key === 'foot_bg_color') {
            root.style.setProperty('--foot-bg-color', val);
        } else if (key === 'foot_border_color') {
            root.style.setProperty('--foot-border-color', val);
        } else if (key === 'foot_text_color') {
            root.style.setProperty('--foot-text-color', val);
        } else if (key === 'foot_head_color') {
            root.style.setProperty('--foot-head-color', val);
        } else if (key === 'foot_hover_color') {
            root.style.setProperty('--foot-hover-color', val);
        } else if (key === 'foot_pad_top') {
            root.style.setProperty('--foot-pad-top', val);
        } else if (key === 'foot_pad_bot') {
            root.style.setProperty('--foot-pad-bot', val);
        } else if (key === 'foot_copy_bg') {
            root.style.setProperty('--foot-copy-bg', val);
        } else if (key === 'foot_copy_text') {
            root.style.setProperty('--foot-copy-text', val);
        } else if (key === 'foot_news_bg') {
            root.style.setProperty('--foot-news-bg', val);
        } else if (key === 'foot_news_border') {
            root.style.setProperty('--foot-news-border', val);
        } else if (key === 'foot_news_btn_bg') {
            root.style.setProperty('--foot-news-btn_bg', val);
        } else if (key === 'foot_news_btn_tx') {
            root.style.setProperty('--foot-news-btn-tx', val);
        } else if (key === 'foot_head_font_size') {
            root.style.setProperty('--foot-head-font-size', val);
        } else if (key === 'foot_body_font_size') {
            root.style.setProperty('--foot-body-font-size', val);
        } else if (key === 'foot_col_spacing') {
            root.style.setProperty('--foot-col-spacing', val);
        } else if (key === 'foot_body_line_height') {
            root.style.setProperty('--foot-body-line-height', val);
        } else if (key === 'foot_head_margin_bottom') {
            root.style.setProperty('--foot-head-margin-bottom', val);
        } else if (key === 'foot_social_radius') {
            root.style.setProperty('--foot-social-radius', val);
        }
    }
    
    // Live update column width preview
    function updateColumnWidth(colNum, val) {
        let el = document.getElementById('preview-col-' + colNum);
        if (!el) return;
        
        let isBootstrap = val.startsWith('col-') || val.startsWith('ttf-');
        if (isBootstrap) {
            el.style.width = '';
            el.style.flex = '';
            el.style.maxWidth = '';
            el.className = 'ttf-hotspot ' + val;
        } else {
            el.style.setProperty('width', val, 'important');
            el.style.setProperty('flex', '0 0 ' + val, 'important');
            el.style.setProperty('max-width', val, 'important');
        }
    }
    
    function updateLiveText(targetId, val) {
        let el = document.getElementById(targetId);
        if (el) {
            el.innerText = val;
        }
    }
    
    function updateNewsletterTitle(val) {
        let el = document.getElementById('preview-news-title');
        if (el) {
            let updatedText = val.replace(/newsletter/gi, '<span class="text-highlight">newsletter</span>');
            el.innerHTML = updatedText;
        }
    }
    
    // Toggle show hide live previews
    function toggleNewsletter(checkbox) {
        let valEl = document.getElementById('foot_news_show_val');
        let previewEl = document.getElementById('preview-newsletter-section');
        if (checkbox.checked) {
            valEl.value = 'on';
            if(previewEl) previewEl.classList.remove('d-none');
        } else {
            valEl.value = 'off';
            if(previewEl) previewEl.classList.add('d-none');
        }
    }
    
    function toggleColumn(colNum, checkbox) {
        let valEl = document.getElementById('foot_col_' + colNum + '_status_val');
        let previewEl = document.getElementById('preview-col-' + colNum);
        if (checkbox.checked) {
            valEl.value = 'on';
            if(previewEl) previewEl.classList.remove('d-none');
        } else {
            valEl.value = 'off';
            if(previewEl) previewEl.classList.add('d-none');
        }
    }

    // Dynamic widget layout generator template
    function getWidgetTemplate(col, index, type, data = {}) {
        let title = data.title || '';
        let html = '';
        
        // Style variables
        let style_text_align = data.style_text_align || '';
        let style_font_size = data.style_font_size || '';
        let style_line_height = data.style_line_height || '';
        let style_margin_bottom = data.style_margin_bottom || '';
        let style_head_weight = data.style_head_weight || '';
        let style_text_weight = data.style_text_weight || '';
        let style_head_color = data.style_head_color || '';
        let style_text_color = data.style_text_color || '';
        let style_hover_color = data.style_hover_color || '';
        
        let style_social_radius = data.style_social_radius || '';
        let style_social_bg = data.style_social_bg || '';
        let style_social_color = data.style_social_color || '';
        let style_social_hover_bg = data.style_social_hover_bg || '';
        let style_social_hover_color = data.style_social_hover_color || '';
        let style_social_width = data.style_social_width || '36px';
        
        let stylesCollapseHtml = `
            <div class="border-top pt-2 mt-2">
                <a href="javascript:void(0);" class="btn btn-xs btn-soft-secondary btn-block mb-2" onclick="$(this).next('.widget-custom-styles-panel').slideToggle();">
                    <i class="las la-cog"></i> Widget Styles & Design Options
                </a>
                <div class="widget-custom-styles-panel" style="display:none; background:#fafafa; border:1px solid #eee; border-radius:6px; padding:10px;">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Text Align</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_text_align]" onchange="updateColumnPreview(${col})">
                                    <option value="" ${style_text_align === '' ? 'selected' : ''}>Default</option>
                                    <option value="left" ${style_text_align === 'left' ? 'selected' : ''}>Left</option>
                                    <option value="center" ${style_text_align === 'center' ? 'selected' : ''}>Center</option>
                                    <option value="right" ${style_text_align === 'right' ? 'selected' : ''}>Right</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Font Size Override</label>
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_font_size]" value="${style_font_size}" placeholder="e.g. 13px" oninput="updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Line Height</label>
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_line_height]" value="${style_line_height}" placeholder="e.g. 1.8" oninput="updateColumnPreview(${col})">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Bottom Margin</label>
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_margin_bottom]" value="${style_margin_bottom}" placeholder="e.g. 15px" oninput="updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Heading Weight</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_head_weight]" onchange="updateColumnPreview(${col})">
                                    <option value="" ${style_head_weight === '' ? 'selected' : ''}>Default</option>
                                    <option value="300" ${style_head_weight === '300' ? 'selected' : ''}>300 (Light)</option>
                                    <option value="400" ${style_head_weight === '400' ? 'selected' : ''}>400 (Normal)</option>
                                    <option value="500" ${style_head_weight === '500' ? 'selected' : ''}>500 (Medium)</option>
                                    <option value="600" ${style_head_weight === '600' ? 'selected' : ''}>600 (Semi Bold)</option>
                                    <option value="700" ${style_head_weight === '700' ? 'selected' : ''}>700 (Bold)</option>
                                    <option value="800" ${style_head_weight === '800' ? 'selected' : ''}>800 (Extra Bold)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Text Weight</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_text_weight]" onchange="updateColumnPreview(${col})">
                                    <option value="" ${style_text_weight === '' ? 'selected' : ''}>Default</option>
                                    <option value="300" ${style_text_weight === '300' ? 'selected' : ''}>300 (Light)</option>
                                    <option value="400" ${style_text_weight === '400' ? 'selected' : ''}>400 (Normal)</option>
                                    <option value="500" ${style_text_weight === '500' ? 'selected' : ''}>500 (Medium)</option>
                                    <option value="600" ${style_text_weight === '600' ? 'selected' : ''}>600 (Semi Bold)</option>
                                    <option value="700" ${style_text_weight === '700' ? 'selected' : ''}>700 (Bold)</option>
                                    <option value="800" ${style_text_weight === '800' ? 'selected' : ''}>800 (Extra Bold)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label class="form-label fs-10">Heading Color Override</label>
                        <div class="input-group input-group-xs">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_head_color]" id="col-style-${col}-${index}-head" value="${style_head_color}" oninput="updateColumnPreview(${col})">
                            <div class="input-group-append">
                                <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_head_color || '#000000'}" oninput="document.getElementById('col-style-${col}-${index}-head').value = this.value; updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label fs-10">Text Color Override</label>
                        <div class="input-group input-group-xs">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_text_color]" id="col-style-${col}-${index}-text" value="${style_text_color}" oninput="updateColumnPreview(${col})">
                            <div class="input-group-append">
                                <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_text_color || '#39322a'}" oninput="document.getElementById('col-style-${col}-${index}-text').value = this.value; updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label fs-10">Hover Color Override</label>
                        <div class="input-group input-group-xs">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_hover_color]" id="col-style-${col}-${index}-hover" value="${style_hover_color}" oninput="updateColumnPreview(${col})">
                            <div class="input-group-append">
                                <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_hover_color || '#876a4b'}" oninput="document.getElementById('col-style-${col}-${index}-hover').value = this.value; updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>
                    
                    ${(type === 'social_icons' || type === 'seller_zone') ? `
                        <h6 class="fs-10 font-weight-bold text-dark mt-3 border-bottom pb-1">Social Follow Styling</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="form-label fs-10">Icon Width/Size</label>
                                    <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_width]" value="${style_social_width}" placeholder="36px" oninput="updateColumnPreview(${col})">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="form-label fs-10">Border Radius</label>
                                    <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_radius]" value="${style_social_radius}" placeholder="e.g. 50% or 4px" oninput="updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Background</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_bg]" id="col-style-${col}-${index}-sbg" value="${style_social_bg}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_bg || '#685b4e'}" oninput="document.getElementById('col-style-${col}-${index}-sbg').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Color</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_color]" id="col-style-${col}-${index}-scolor" value="${style_social_color}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_color || '#ffffff'}" oninput="document.getElementById('col-style-${col}-${index}-scolor').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Hover Background</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_hover_bg]" id="col-style-${col}-${index}-shbg" value="${style_social_hover_bg}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_hover_bg || '#876a4b'}" oninput="document.getElementById('col-style-${col}-${index}-shbg').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Hover Color</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_hover_color]" id="col-style-${col}-${index}-shcolor" value="${style_social_hover_color}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_hover_color || '#ffffff'}" oninput="document.getElementById('col-style-${col}-${index}-shcolor').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>`;
        
        if (type === 'menu_links') {
            let lbls = data.lbls || ['Link Label'];
            let lnks = data.lnks || ['#'];
            let linksHtml = '';
            for (let i = 0; i < lbls.length; i++) {
                linksHtml += `
                    <div class="menu-link-row">
                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, ${col})"><i class="las la-times"></i></button>
                        <div class="form-group mb-1">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lbls][]" value="${lbls[i]}" placeholder="Link Label" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lnks][]" value="${lnks[i]}" placeholder="Link URL" oninput="updateColumnPreview(${col})">
                        </div>
                    </div>`;
            }
            
            html = `
                <div class="widget-card card mb-3 border" data-type="menu_links" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                        <span class="font-weight-bold text-primary"><i class="las la-list"></i> Menu Links</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="menu_links">
                        <div class="form-group">
                            <label class="form-label">Menu Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Quick Links'}" placeholder="Menu Title" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="menu-links-container">
                            ${linksHtml}
                        </div>
                        <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, ${col}, ${index})">
                            <i class="las la-plus"></i> Add New Link
                        </button>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        } 
        else if (type === 'text_html') {
            let innerText = data.html || '';
            html = `
                <div class="widget-card card mb-3 border" data-type="text_html" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-success">
                        <span class="font-weight-bold text-success"><i class="las la-code"></i> Custom Text / HTML</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="text_html">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Title'}" placeholder="Widget Title" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">HTML / Text Content</label>
                            <textarea class="form-control" name="foot_col_${col}_widgets[${index}][html]" rows="5" placeholder="HTML content" oninput="updateColumnPreview(${col})">${innerText}</textarea>
                        </div>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'important_links') {
            let page_ids = data.page_ids || '2,3,4,5,6,7,8,10,11';
            html = `
                <div class="widget-card card mb-3 border" data-type="important_links" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                        <span class="font-weight-bold text-primary"><i class="las la-link"></i> Important Links (Auto Pages)</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="important_links">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Important Links'}" placeholder="Important Links" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Page IDs (Comma-separated)</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][page_ids]" value="${page_ids}" placeholder="e.g. 2,3,4,5" oninput="updateColumnPreview(${col})">
                            <small class="form-text text-muted">Specify Page IDs to display automatically.</small>
                        </div>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'my_account') {
            let login_text = data.login_text || 'Login';
            let logout_text = data.logout_text || 'Logout';
            let order_history_text = data.order_history_text || 'Order History';
            let wishlist_text = data.wishlist_text || 'My Wishlist';
            let track_order_text = data.track_order_text || 'Track Order';
            html = `
                <div class="widget-card card mb-3 border" data-type="my_account" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                        <span class="font-weight-bold text-primary"><i class="las la-user"></i> My Account Links</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="my_account">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'My Account'}" placeholder="My Account" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Login Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][login_text]" value="${login_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Logout Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][logout_text]" value="${logout_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Order History Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][order_history_text]" value="${order_history_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Wishlist Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][wishlist_text]" value="${wishlist_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Track Order Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][track_order_text]" value="${track_order_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'seller_zone') {
            let seller_url = data.seller_url || '';
            let become_seller_url = data.become_seller_url || '';
            let subheading_2 = data.subheading_2 || 'Join Our Partner Network';
            let subheading_3 = data.subheading_3 || '';
            let seller_login_text = data.seller_login_text || 'Login to Seller Panel';
            let become_seller_text = data.become_seller_text || 'Register your shop';
            let download_seller_app_text = data.download_seller_app_text || 'Download Seller App';
            
            html = `
                <div class="widget-card card mb-3 border" data-type="seller_zone" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-warning">
                        <span class="font-weight-bold text-warning"><i class="las la-store"></i> Seller Zone Composite</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="seller_zone">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Seller Zone'}" placeholder="Seller Zone" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seller Login URL</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][seller_url]" value="${seller_url}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seller Login Link Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][seller_login_text]" value="${seller_login_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Become Seller URL</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][become_seller_url]" value="${become_seller_url}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Register Shop Link Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][become_seller_text]" value="${become_seller_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Download App Link Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][download_seller_app_text]" value="${download_seller_app_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Join Network Header</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][subheading_2]" value="${subheading_2}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-3 border-bottom pb-3">
                            <label class="form-label">Follow Us Header</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][subheading_3]" value="${subheading_3}" oninput="updateColumnPreview(${col})">
                        </div>

                        <!-- Seller Social Links -->
                        <h6 class="fs-10 font-weight-bold text-dark mb-2">Seller Social Link Overrides</h6>
                        <div class="form-group">
                            <label class="form-label fs-10">Facebook Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][facebook_link]" value="${data.facebook_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Twitter (X) Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][twitter_link]" value="${data.twitter_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Instagram Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][instagram_link]" value="${data.instagram_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Youtube Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][youtube_link]" value="${data.youtube_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Pinterest Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pinterest_link]" value="${data.pinterest_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fs-10">TikTok Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][tiktok_link]" value="${data.tiktok_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>

                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'images_widget') {
            let trustpilot_lnk = data.trustpilot_lnk || '#';
            let deliv_img_val = data.deliv_img || '';
            let pay_img_val = data.pay_img || '';
            let trust_img_val = data.trust_img || '';
            let show_deliv = data.show_deliv || 'on';
            let show_pay = data.show_pay || 'on';
            let show_trust = data.show_trust || 'on';
            
            html = `
                <div class="widget-card card mb-3 border" data-type="images_widget" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-info">
                        <span class="font-weight-bold text-info"><i class="las la-images"></i> Delivery & Payment Logos</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="images_widget">
                        
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Delivery Partners'}" placeholder="Delivery Partners" oninput="updateColumnPreview(${col})">
                        </div>

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-label fs-10">Delivery</label>
                                    <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_deliv]" onchange="updateColumnPreview(${col})">
                                        <option value="on" ${show_deliv === 'on' ? 'selected' : ''}>Show</option>
                                        <option value="off" ${show_deliv === 'off' ? 'selected' : ''}>Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-label fs-10">Payment</label>
                                    <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_pay]" onchange="updateColumnPreview(${col})">
                                        <option value="on" ${show_pay === 'on' ? 'selected' : ''}>Show</option>
                                        <option value="off" ${show_pay === 'off' ? 'selected' : ''}>Hide</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="form-label fs-10">Trustpilot</label>
                                    <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_trust]" onchange="updateColumnPreview(${col})">
                                        <option value="on" ${show_trust === 'on' ? 'selected' : ''}>Show</option>
                                        <option value="off" ${show_trust === 'off' ? 'selected' : ''}>Hide</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Delivery Image -->
                        <div class="form-group">
                            <label class="form-label">Delivery Image</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="foot_col_${col}_widgets[${index}][deliv_img]" class="selected-files" value="${deliv_img_val}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>

                        <!-- Payment Image -->
                        <div class="form-group">
                            <label class="form-label">Pay Securely Image</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="foot_col_${col}_widgets[${index}][pay_img]" class="selected-files" value="${pay_img_val}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>

                        <!-- Trustpilot Image -->
                        <div class="form-group">
                            <label class="form-label">Trustpilot Image</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="foot_col_${col}_widgets[${index}][trust_img]" class="selected-files" value="${trust_img_val}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label">Trustpilot Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][trustpilot_lnk]" value="${trustpilot_lnk}" oninput="updateColumnPreview(${col})">
                        </div>

                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'social_icons') {
            html = `
                <div class="widget-card card mb-3 border" data-type="social_icons" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-purple">
                        <span class="font-weight-bold text-purple"><i class="las la-share-alt"></i> Social Follow Icons</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="social_icons">
                        <div class="form-group mb-3 border-bottom pb-3">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Follow Us'}" placeholder="Follow Us" oninput="updateColumnPreview(${col})">
                        </div>

                        <!-- Social URL fields -->
                        <h6 class="fs-10 font-weight-bold text-dark mb-2">Social Link Connections</h6>
                        <div class="form-group">
                            <label class="form-label fs-10">Facebook Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][facebook_link]" value="${data.facebook_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Twitter (X) Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][twitter_link]" value="${data.twitter_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Instagram Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][instagram_link]" value="${data.instagram_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Youtube Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][youtube_link]" value="${data.youtube_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Pinterest Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pinterest_link]" value="${data.pinterest_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fs-10">TikTok Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][tiktok_link]" value="${data.tiktok_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>

                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        
        return html;
    }

    // Add Widget to repeater
    function addWidget(col) {
        let select = document.getElementById('add-widget-select-' + col);
        let type = select.value;
        if (!type) return;
        
        let container = document.getElementById('widgets-list-' + col);
        if (!container) return;
        
        let index = container.querySelectorAll('.widget-card').length;
        let template = getWidgetTemplate(col, index, type, {});
        
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = template;
        let newCard = tempDiv.firstElementChild;
        container.appendChild(newCard);
        
        addDragHandlers(newCard);
        bindWidgetInputListeners(newCard);
        refreshWidgetIndices(col);
        
        AIZ.uploader.previewGenerate();
    }

    // Duplicate widget
    function copyWidget(btn) {
        let card = btn.closest('.widget-card');
        let clone = card.cloneNode(true);
        card.parentNode.insertBefore(clone, card.nextSibling);
        
        addDragHandlers(clone);
        bindWidgetInputListeners(clone);
        
        let col = card.closest('.widgets-list').getAttribute('data-col');
        refreshWidgetIndices(col);
        
        AIZ.uploader.previewGenerate();
    }

    // Delete Widget
    function removeWidget(btn) {
        let card = btn.closest('.widget-card');
        let container = card.closest('.widgets-list');
        let col = container.getAttribute('data-col');
        card.remove();
        refreshWidgetIndices(col);
    }

    // Reorder Widget Up
    function moveWidgetUp(btn) {
        let card = btn.closest('.widget-card');
        let prev = card.previousElementSibling;
        if (prev && prev.classList.contains('widget-card')) {
            card.parentNode.insertBefore(card, prev);
            let col = card.closest('.widgets-list').getAttribute('data-col');
            refreshWidgetIndices(col);
        }
    }

    // Reorder Widget Down
    function moveWidgetDown(btn) {
        let card = btn.closest('.widget-card');
        let next = card.nextElementSibling;
        if (next && next.classList.contains('widget-card')) {
            card.parentNode.insertBefore(next, card);
            let col = card.closest('.widgets-list').getAttribute('data-col');
            refreshWidgetIndices(col);
        }
    }

    // Add row to menu links widget list
    function addMenuRowToWidget(btn, col, wIndex) {
        let container = btn.previousElementSibling;
        let tempDiv = document.createElement('div');
        tempDiv.className = 'menu-link-row';
        tempDiv.innerHTML = `
            <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, ${col})"><i class="las la-times"></i></button>
            <div class="form-group mb-1">
                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${wIndex}][lbls][]" placeholder="Link Label" oninput="updateColumnPreview(${col})">
            </div>
            <div class="form-group mb-0">
                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${wIndex}][lnks][]" placeholder="Link URL" oninput="updateColumnPreview(${col})">
            </div>`;
        container.appendChild(tempDiv);
    }

    function removeMenuRow(btn, col) {
        let container = btn.closest('.menu-links-container');
        btn.closest('.menu-link-row').remove();
        updateColumnPreview(col);
    }

    // Refresh repeater input names with correct order index
    function refreshWidgetIndices(col) {
        let container = document.getElementById('widgets-list-' + col);
        if (!container) return;
        
        let cards = container.querySelectorAll('.widget-card');
        cards.forEach(function(card, index) {
            card.querySelectorAll('input, select, textarea').forEach(function(input) {
                let name = input.getAttribute('name');
                if (name) {
                    let newName = name.replace(/foot_col_\d+_widgets\[\d+\]/, 'foot_col_' + col + '_widgets[' + index + ']');
                    input.setAttribute('name', newName);
                }
            });
            
            card.querySelectorAll('.menu-link-row').forEach(function(row) {
                row.querySelectorAll('input').forEach(function(lnkInput) {
                    let lnkName = lnkInput.getAttribute('name');
                    if (lnkName) {
                        let newLnkName = lnkName.replace(/foot_col_\d+_widgets\[\d+\]/, 'foot_col_' + col + '_widgets[' + index + ']');
                        lnkInput.setAttribute('name', newLnkName);
                    }
                });
            });
        });
        
        updateColumnPreview(col);
    }

    // Live preview generator updates HTML in real-time
    function updateColumnPreview(col) {
        let previewCol = document.getElementById('preview-col-' + col);
        if (!previewCol) return;
        
        let cardContainer = previewCol.querySelector('.ttf-footer-card');
        if (!cardContainer) return;
        
        let widgetsList = document.getElementById('widgets-list-' + col);
        if (!widgetsList) return;
        
        let cards = widgetsList.querySelectorAll('.widget-card');
        let html = '';
        
        cards.forEach(function(card) {
            let type = card.getAttribute('data-type');
            let titleInput = card.querySelector('input[name*="[title]"]');
            let title = titleInput ? titleInput.value : '';
            
            if (type === 'menu_links') {
                html += `<h4>${title || 'Menu'}</h4><ul>`;
                let rows = card.querySelectorAll('.menu-link-row');
                rows.forEach(function(row) {
                    let lblInput = row.querySelector('input[name*="[lbls]"]');
                    let lbl = lblInput ? lblInput.value : '';
                    if (lbl) {
                        html += `<li><a href="#" onclick="return false;">${lbl}</a></li>`;
                    }
                });
                html += `</ul>`;
            }
            else if (type === 'text_html') {
                let textVal = card.querySelector('textarea[name*="[html]"]').value || '';
                html += `<h4>${title || 'Text Widget'}</h4><div style="font-size:13px; line-height:1.8;">${textVal}</div>`;
            }
            else if (type === 'seller_zone') {
                html += `
                    <h4>${title || 'Seller Zone'}</h4>
                    <ul><li><a href="#" onclick="return false;">Login to Seller Panel</a></li></ul>`;
                let sub2Input = card.querySelector('input[name*="[subheading_2]"]');
                let sub2 = sub2Input ? sub2Input.value : 'Join Our Partner Network';
                html += `<div class="sub-widget-title">${sub2}</div>
                    <ul><li><a href="#" onclick="return false;">Register your shop</a></li></ul>`;
                let sub3Input = card.querySelector('input[name*="[subheading_3]"]');
                let sub3 = sub3Input ? sub3Input.value : '';
                if (sub3) {
                    html += `<div class="sub-widget-title">${sub3}</div>
                        <ul class="footer-social-list">
                            <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                            <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                            <li><a href="#" onclick="return false;"><i class="lab la-twitter"></i></a></li>
                        </ul>`;
                }
            }
            else if (type === 'images_widget') {
                let showDelivInput = card.querySelector('input[name*="[show_deliv]"]');
                let showPayInput = card.querySelector('input[name*="[show_pay]"]');
                let showTrustInput = card.querySelector('input[name*="[show_trust]"]');
                let showDeliv = (showDelivInput ? showDelivInput.value : 'on') === 'on';
                let showPay = (showPayInput ? showPayInput.value : 'on') === 'on';
                let showTrust = (showTrustInput ? showTrustInput.value : 'on') === 'on';

                if (showDeliv) {
                    html += `
                        <div class="secure-payment-box mb-3">
                            <h5 class="secure-payment-title textheading">${title || 'Delivery Partners'}</h5>
                            <div style="background:#fff; border-radius:4px; height: 35px; width: 140px; border: 1px solid #e4e5eb;"></div>
                        </div>`;
                }
                if (showPay) {
                    html += `
                        <div class="secure-payment-box mb-3">
                            <h5 class="secure-payment-title textheading">${showDeliv ? 'Pay Securely With' : (title || 'Pay Securely With')}</h5>
                            <div style="background:#fff; border-radius:4px; height: 35px; width: 140px; border: 1px solid #e4e5eb;"></div>
                        </div>`;
                }
                if (showTrust) {
                    html += `
                        <div class="secure-payment-box">
                            <h5 class="secure-payment-title textheading">What Trustpilot Say’s</h5>
                            <div style="background:#fff; border-radius:4px; height: 40px; width: 140px; border: 1px solid #e4e5eb;"></div>
                        </div>`;
                }
            }
            else if (type === 'social_icons') {
                html += `
                    <h4>${title || 'Follow Us'}</h4>
                    <ul class="footer-social-list">
                        <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                        <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                    </ul>`;
            }
        });
        
        cardContainer.innerHTML = html;
    }

    // HTML5 Drag and Drop Sorting script
    let dragSrcEl = null;

    function handleDragStart(e) {
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
        this.style.opacity = '0.4';
    }

    // Drag-over handler
    function handleDragOver(e) {
        if (e.preventDefault) e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    // Drop handler
    function handleDrop(e) {
        if (e.stopPropagation) e.stopPropagation();
        
        if (dragSrcEl !== this) {
            let rect = this.getBoundingClientRect();
            let next = (e.clientY - rect.top) > (rect.height / 2);
            if (next) {
                this.parentNode.insertBefore(dragSrcEl, this.nextSibling);
            } else {
                this.parentNode.insertBefore(dragSrcEl, this);
            }
            let col = this.closest('.widgets-list').getAttribute('data-col');
            refreshWidgetIndices(col);
        }
        return false;
    }

    // Drag-end handler
    function handleDragEnd(e) {
        this.style.opacity = '1.0';
    }

    function addDragHandlers(card) {
        card.setAttribute('draggable', 'true');
        card.addEventListener('dragstart', handleDragStart, false);
        card.addEventListener('dragover', handleDragOver, false);
        card.addEventListener('drop', handleDrop, false);
        card.addEventListener('dragend', handleDragEnd, false);
    }

    function bindWidgetInputListeners(card) {
        let col = card.closest('.widgets-list').getAttribute('data-col');
        card.querySelectorAll('input, textarea, select').forEach(function(input) {
            input.addEventListener('input', function() {
                updateColumnPreview(col);
            });
            input.addEventListener('change', function() {
                updateColumnPreview(col);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.widget-card').forEach(function(card) {
            addDragHandlers(card);
            bindWidgetInputListeners(card);
        });
    });

    function refreshColumnIndices() {
        let container = document.getElementById('columns-accordion');
        if (!container) return;
        
        let cards = columnCards();
        cards.forEach(function(card, index) {
            let colNum = index + 1;
            
            // Update Card ID
            card.setAttribute('id', 'card-col-settings-' + colNum);
            
            // Update title text
            let titleText = card.querySelector('.card-col-title-text');
            if (titleText) {
                titleText.innerText = 'Column ' + colNum + ' Widgets';
            }
            
            // Update collapse target
            let headerDiv = card.querySelector('.c-pointer');
            if (headerDiv) {
                headerDiv.setAttribute('data-target', '#collapse-col-' + colNum);
            }
            
            let collapseDiv = card.querySelector('.collapse');
            if (collapseDiv) {
                collapseDiv.setAttribute('id', 'collapse-col-' + colNum);
            }
            
            // Update status switcher checkbox onChange parameter
            let checkbox = card.querySelector('input[type="checkbox"][onchange*="toggleColumn"]');
            if (checkbox) {
                checkbox.setAttribute('onchange', 'toggleColumn(' + colNum + ', this)');
            }
            
            let statusVal = card.querySelector('input[id*="_status_val"]');
            if (statusVal) {
                statusVal.setAttribute('id', 'foot_col_' + colNum + '_status_val');
            }
            
            // Update width input oninput parameter
            let widthInput = card.querySelector('input[name*="_width"]');
            if (widthInput) {
                widthInput.setAttribute('oninput', 'updateColumnWidth(' + colNum + ', this.value)');
            }
            
            // Update widgets list container ID and data-col attribute
            let widgetsList = card.querySelector('.widgets-list');
            if (widgetsList) {
                widgetsList.setAttribute('id', 'widgets-list-' + colNum);
                widgetsList.setAttribute('data-col', colNum);
                ensureWidgetsTypeInput(widgetsList, colNum);
            }
            
            // Update add-widget-select select ID
            let addSelect = card.querySelector('select[id*="add-widget-select-"]');
            if (addSelect) {
                addSelect.setAttribute('id', 'add-widget-select-' + colNum);
            }
            
            // Update add button onclick
            let addButton = card.querySelector('button[onclick*="addWidget"]');
            if (addButton) {
                addButton.setAttribute('onclick', 'addWidget(' + colNum + ')');
            }
            
            // Update copy button onclick
            let copyButton = card.querySelector('.btn-copy-col');
            if (copyButton) {
                copyButton.setAttribute('onclick', 'copyColumn(' + colNum + ', this); event.stopPropagation();');
            }
            
            // Update inputs and names inside the card
            card.querySelectorAll('input, select, textarea').forEach(function(input) {
                let name = input.getAttribute('name');
                if (name) {
                    let newName = name.replace(/foot_col_\d+/, 'foot_col_' + colNum);
                    input.setAttribute('name', newName);
                }

                if (name && name.startsWith('types') && input.value.match(/^foot_col_\d+_widgets$/)) {
                    input.value = 'foot_col_' + colNum + '_widgets';
                }
                
                let idAttr = input.getAttribute('id');
                if (idAttr) {
                    let newId = idAttr.replace(/col-style-\d+/, 'col-style-' + colNum);
                    input.setAttribute('id', newId);
                }
                
                let oninputAttr = input.getAttribute('oninput');
                if (oninputAttr && oninputAttr.includes('col-style-')) {
                    let newOninput = oninputAttr.replace(/col-style-\d+-\d+/g, function(match) {
                        let parts = match.split('-');
                        return 'col-style-' + colNum + '-' + parts[3];
                    });
                    input.setAttribute('oninput', newOninput);
                }
            });
            
            // Also update sub-rows for menus
            card.querySelectorAll('.menu-link-row').forEach(function(row) {
                let remBtn = row.querySelector('.btn-remove-row');
                if (remBtn) {
                    remBtn.setAttribute('onclick', 'removeMenuRow(this, ' + colNum + ')');
                }
            });
            
            let addNewLinkBtn = card.querySelector('button[onclick*="addMenuRowToWidget"]');
            if (addNewLinkBtn) {
                let onclickVal = addNewLinkBtn.getAttribute('onclick');
                if (onclickVal) {
                    let newOnclick = onclickVal.replace(/addMenuRowToWidget\(this,\s*\d+/, 'addMenuRowToWidget(this, ' + colNum);
                    addNewLinkBtn.setAttribute('onclick', newOnclick);
                }
            }

            // Refresh extra blocks col references
            refreshExtraBlocksForColumn(colNum);
        });
        
        // Trigger preview updates
        for (let c = 1; c <= cards.length; c++) {
            updateColumnPreview(c);
            let widthInput = document.querySelector('input[name="foot_col_' + c + '_width"]');
            if (widthInput) {
                updateColumnWidth(c, widthInput.value);
            }
        }
    }

    function moveColumnUp(btn) {
        let card = btn.closest('.card');
        let prev = card.previousElementSibling;
        if (prev && prev.classList.contains('card')) {
            card.parentNode.insertBefore(card, prev);
            refreshColumnIndices();
        }
    }

    function moveColumnDown(btn) {
        let card = btn.closest('.card');
        let next = card.nextElementSibling;
        if (next && next.classList.contains('card') && !next.classList.contains('d-none')) {
            card.parentNode.insertBefore(next, card);
            refreshColumnIndices();
        }
    }

    function addNewColumn() {
        let container = document.getElementById('columns-accordion');
        let hiddenCard = columnCards().find(function(card) {
            return card.classList.contains('d-none');
        });
        if (!hiddenCard) {
            alert('Maximum of 8 columns allowed.');
            return;
        }

        let nextCol = columnCards().indexOf(hiddenCard) + 1;
        let widgetsList = hiddenCard.querySelector('.widgets-list');
        resetWidgetsList(widgetsList, nextCol);

        let widthInput = hiddenCard.querySelector('input[name*="_width"]');
        if (widthInput) widthInput.value = '20%';
        
        // Mark status as on
        let checkbox = hiddenCard.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true;
        let statusVal = hiddenCard.querySelector('input[id*="_status_val"]');
        if (statusVal) statusVal.value = 'on';
        
        // Remove d-none
        hiddenCard.classList.remove('d-none');
        
        // Refresh indices
        refreshColumnIndices();
        
        // Expand the newly added column card
        let collapseDiv = hiddenCard.querySelector('.collapse');
        if (collapseDiv) {
            $(collapseDiv).collapse('show');
        }
    }

    function setFooterGridColumns(count) {
        let cards = columnCards();
        let width = (100 / count).toFixed(4).replace(/\.?0+$/, '') + '%';

        cards.forEach(function(card, index) {
            let colNum = index + 1;
            let isActive = index < count;
            let checkbox = card.querySelector('input[type="checkbox"][onchange*="toggleColumn"]');
            let statusVal = card.querySelector('input[id*="_status_val"]');
            let widthInput = card.querySelector('input[name*="_width"]');

            card.classList.toggle('d-none', !isActive);
            if (checkbox) checkbox.checked = isActive;
            if (statusVal) statusVal.value = isActive ? 'on' : 'off';
            if (widthInput) widthInput.value = width;
            updateColumnWidth(colNum, width);
        });

        refreshColumnIndices();
    }

    function deleteColumn(colNum, btn) {
        let container = document.getElementById('columns-accordion');
        let card = btn.closest('.card');
        
        // Count how many active columns are left (not having d-none class)
        let activeCards = columnCards().filter(function(columnCard) {
            return !columnCard.classList.contains('d-none');
        });
        if (activeCards.length <= 1) {
            alert('At least one column must be present in the footer.');
            return;
        }
        
        if (!confirm('Are you sure you want to delete this column and all its widgets?')) {
            return;
        }
        
        // Mark as status off
        let checkbox = card.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
        let statusVal = card.querySelector('input[id*="_status_val"]');
        if (statusVal) statusVal.value = 'off';
        
        // Clear widgets
        let widgetsList = card.querySelector('.widgets-list');
        resetWidgetsList(widgetsList, colNum);

        // Clear extra blocks
        let extraBlocksList = card.querySelector('.extra-blocks-list');
        if (extraBlocksList) extraBlocksList.innerHTML = '';
        
        // Hide and move to the end of the accordion container
        card.classList.add('d-none');
        container.appendChild(card);
        
        // Refresh indices
        refreshColumnIndices();
    }

    function copyColumn(colNum, btn) {
        let container = document.getElementById('columns-accordion');
        let inactiveCard = columnCards().find(function(card) {
            return card.classList.contains('d-none');
        });
        if (!inactiveCard) {
            alert('Maximum of 8 columns allowed.');
            return;
        }
        
        let card = btn.closest('.card');
        
        // Copy widgets contents
        let srcWidgetsList = card.querySelector('.widgets-list');
        let destWidgetsList = inactiveCard.querySelector('.widgets-list');
        if (srcWidgetsList && destWidgetsList) {
            destWidgetsList.innerHTML = srcWidgetsList.innerHTML;
        }

        // Copy extra blocks contents
        let srcExtraList = card.querySelector('.extra-blocks-list');
        let destExtraList = inactiveCard.querySelector('.extra-blocks-list');
        if (srcExtraList && destExtraList) {
            destExtraList.innerHTML = srcExtraList.innerHTML;
        }
        
        // Copy column width
        let srcWidth = card.querySelector('input[name*="_width"]');
        let destWidth = inactiveCard.querySelector('input[name*="_width"]');
        if (srcWidth && destWidth) {
            destWidth.value = srcWidth.value;
        }
        
        // Set status on
        let checkbox = inactiveCard.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true;
        let statusVal = inactiveCard.querySelector('input[id*="_status_val"]');
        if (statusVal) statusVal.value = 'on';
        
        // Move inactiveCard next to card, and remove d-none
        card.parentNode.insertBefore(inactiveCard, card.nextSibling);
        inactiveCard.classList.remove('d-none');
        
        // Refresh indices
        refreshColumnIndices();
        
        // Re-register drag/drop/input handlers inside the activated card
        inactiveCard.querySelectorAll('.widget-card').forEach(function(wCard) {
            addDragHandlers(wCard);
            bindWidgetInputListeners(wCard);
        });
        
        AIZ.uploader.previewGenerate();
    }

    function translate(text) {
        return text;
    }

    /* ═══════════════════════════════════════════════
       EXTRA LINK BLOCKS — per-column repeater JS
    ═══════════════════════════════════════════════ */

    /**
     * Build the HTML for one extra block card (used when adding a new block dynamically).
     */
    function getExtraBlockTemplate(col, index) {
        return `
            <div class="extra-block-card card mb-2 border border-secondary">
                <div class="card-header py-2 d-flex justify-content-between align-items-center" style="background:#f5f3f0;">
                    <span class="text-secondary font-weight-bold fs-11"><i class="las la-grip-vertical mr-1"></i>Link Block #${index + 1}</span>
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockUp(this)"><i class="las la-arrow-up"></i></button>
                        <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockDown(this)"><i class="las la-arrow-down"></i></button>
                        <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeExtraBlock(this, ${col})"><i class="las la-trash"></i></button>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10 mb-1">Block Heading</label>
                                <input type="text" class="form-control form-control-sm"
                                    name="foot_col_${col}_extra_blocks[${index}][title]"
                                    placeholder="e.g. Before a Seller">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10 mb-1">Show On</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_extra_blocks[${index}][show_on]">
                                    <option value="both" selected>Both</option>
                                    <option value="desktop">Desktop only</option>
                                    <option value="mobile">Mobile only</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="extra-block-links-container mb-1">
                        <div class="extra-link-row d-flex align-items-start gap-1 mb-1">
                            <button type="button" class="btn btn-xs btn-danger flex-shrink-0 mt-1" onclick="removeExtraLinkRow(this, ${col})"><i class="las la-times"></i></button>
                            <div class="flex-grow-1">
                                <input type="text" class="form-control form-control-sm mb-1"
                                    name="foot_col_${col}_extra_blocks[${index}][lbls][]"
                                    placeholder="Link Label">
                                <input type="text" class="form-control form-control-sm"
                                    name="foot_col_${col}_extra_blocks[${index}][lnks][]"
                                    placeholder="Link URL">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraLinkRow(this, ${col})">
                        <i class="las la-plus"></i> Add Link
                    </button>
                </div>
            </div>`;
    }

    /** Add a new extra block card to a column */
    function addExtraBlock(col) {
        let container = document.getElementById('extra-blocks-list-' + col);
        if (!container) return;
        let index = container.querySelectorAll('.extra-block-card').length;
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = getExtraBlockTemplate(col, index);
        container.appendChild(tempDiv.firstElementChild);
        refreshExtraBlockIndices(col);
    }

    /** Remove an extra block card */
    function removeExtraBlock(btn, col) {
        btn.closest('.extra-block-card').remove();
        refreshExtraBlockIndices(col);
    }

    /** Move an extra block card up */
    function moveExtraBlockUp(btn) {
        let card = btn.closest('.extra-block-card');
        let prev = card.previousElementSibling;
        if (prev && prev.classList.contains('extra-block-card')) {
            card.parentNode.insertBefore(card, prev);
            let col = card.closest('.extra-blocks-list').getAttribute('data-col');
            refreshExtraBlockIndices(col);
        }
    }

    /** Move an extra block card down */
    function moveExtraBlockDown(btn) {
        let card = btn.closest('.extra-block-card');
        let next = card.nextElementSibling;
        if (next && next.classList.contains('extra-block-card')) {
            card.parentNode.insertBefore(next, card);
            let col = card.closest('.extra-blocks-list').getAttribute('data-col');
            refreshExtraBlockIndices(col);
        }
    }

    /** Add a link row inside an extra block */
    function addExtraLinkRow(btn, col) {
        let container = btn.previousElementSibling; // .extra-block-links-container
        let card = btn.closest('.extra-block-card');
        // Figure out block index from the card's title input name
        let titleInput = card.querySelector('input[name*="_extra_blocks"]');
        let blockIdx = 0;
        if (titleInput) {
            let m = titleInput.getAttribute('name').match(/extra_blocks\[(\d+)\]/);
            if (m) blockIdx = parseInt(m[1]);
        }
        let tempDiv = document.createElement('div');
        tempDiv.className = 'extra-link-row d-flex align-items-start gap-1 mb-1';
        tempDiv.innerHTML = `
            <button type="button" class="btn btn-xs btn-danger flex-shrink-0 mt-1" onclick="removeExtraLinkRow(this, ${col})"><i class="las la-times"></i></button>
            <div class="flex-grow-1">
                <input type="text" class="form-control form-control-sm mb-1"
                    name="foot_col_${col}_extra_blocks[${blockIdx}][lbls][]"
                    placeholder="Link Label">
                <input type="text" class="form-control form-control-sm"
                    name="foot_col_${col}_extra_blocks[${blockIdx}][lnks][]"
                    placeholder="Link URL">
            </div>`;
        container.appendChild(tempDiv);
    }

    /** Remove a link row inside an extra block */
    function removeExtraLinkRow(btn, col) {
        btn.closest('.extra-link-row').remove();
    }

    /** Re-index all extra block input names after add/remove/reorder */
    function refreshExtraBlockIndices(col) {
        let container = document.getElementById('extra-blocks-list-' + col);
        if (!container) return;
        let cards = container.querySelectorAll('.extra-block-card');
        cards.forEach(function(card, bIdx) {
            // Update header label
            let label = card.querySelector('.card-header span');
            if (label) label.innerHTML = '<i class="las la-grip-vertical mr-1"></i>Link Block #' + (bIdx + 1);

            // Update all input/select names
            card.querySelectorAll('input, select').forEach(function(input) {
                let name = input.getAttribute('name');
                if (name) {
                    let newName = name.replace(
                        /foot_col_\d+_extra_blocks\[\d+\]/,
                        'foot_col_' + col + '_extra_blocks[' + bIdx + ']'
                    );
                    input.setAttribute('name', newName);
                }
            });

            // Update removeExtraBlock onclick col param
            let removeBtn = card.querySelector('button[onclick*="removeExtraBlock"]');
            if (removeBtn) removeBtn.setAttribute('onclick', 'removeExtraBlock(this, ' + col + ')');

            // Update addExtraLinkRow onclick col param
            let addLinkBtn = card.querySelector('button[onclick*="addExtraLinkRow"]');
            if (addLinkBtn) addLinkBtn.setAttribute('onclick', 'addExtraLinkRow(this, ' + col + ')');

            // Update removeExtraLinkRow onclick col param
            card.querySelectorAll('button[onclick*="removeExtraLinkRow"]').forEach(function(btn) {
                btn.setAttribute('onclick', 'removeExtraLinkRow(this, ' + col + ')');
            });
        });
    }

    /** Called by refreshColumnIndices to re-attach extra-blocks-list col number */
    function refreshExtraBlocksForColumn(colNum) {
        let container = document.getElementById('extra-blocks-list-' + colNum);
        if (!container) return;
        container.setAttribute('data-col', colNum);

        // Update all names in the container
        container.querySelectorAll('input, select').forEach(function(input) {
            let name = input.getAttribute('name');
            if (name) {
                let newName = name.replace(/foot_col_\d+_extra_blocks/, 'foot_col_' + colNum + '_extra_blocks');
                input.setAttribute('name', newName);
            }
        });

        // Update button onclick params
        container.querySelectorAll('button[onclick*="ExtraBlock"], button[onclick*="ExtraLinkRow"]').forEach(function(btn) {
            let onclick = btn.getAttribute('onclick');
            if (onclick) {
                let newOnclick = onclick.replace(/,\s*\d+\)/, ', ' + colNum + ')');
                btn.setAttribute('onclick', newOnclick);
            }
        });

        // Update add-block button (it's outside the list, in the parent section)
        let addBlockBtn = container.closest('.border-top').querySelector('button[onclick*="addExtraBlock"]');
        if (addBlockBtn) addBlockBtn.setAttribute('onclick', 'addExtraBlock(' + colNum + ')');

        // Also inject the extra_blocks types hidden input
        let widgetsList = document.getElementById('widgets-list-' + colNum);
        if (widgetsList) {
            let existing = widgetsList.querySelector('input[value*="_extra_blocks"]');
            if (!existing) {
                let inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'types[][' + (footerBuilderLang || '') + ']';
                inp.value = 'foot_col_' + colNum + '_extra_blocks';
                widgetsList.insertAdjacentElement('afterbegin', inp);
            } else {
                existing.value = 'foot_col_' + colNum + '_extra_blocks';
            }
        }
    }
</script>
@endsection

@endsection
