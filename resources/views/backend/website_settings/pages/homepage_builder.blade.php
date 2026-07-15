@extends('backend.layouts.app')

@section('content')
<style>
    /* Styling for visual section previews */
    .section-card .card-header {
        cursor: pointer;
        user-select: none;
        transition: background-color 0.2s ease;
    }
    .section-card .card-header:hover {
        background-color: #f3f4f6 !important;
    }
    .section-preview-box {
        border: 2px dashed #d1d5db;
        background-color: #f9fafb;
        border-radius: 6px;
        padding: 12px;
        min-height: 120px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .preview-slider-mockup {
        width: 100%;
        height: 80px;
        background: linear-gradient(90deg, #deb887 0%, #c59259 100%);
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 10px;
        color: white;
        font-weight: bold;
        font-size: 11px;
    }
    .preview-banner-1-mockup {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 6px;
    }
    .preview-banner-2-mockup {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }
    .preview-banner-3-mockup {
        width: 100%;
        height: 70px;
        background-color: #e5e7eb;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #6b7280;
    }
    .preview-banner-item {
        height: 60px;
        background-color: #e5e7eb;
        border: 1px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        color: #6b7280;
    }
    .preview-products-mockup {
        width: 100%;
    }
    .preview-products-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        margin-top: 6px;
    }
    .preview-product-card {
        height: 50px;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
    }
    .preview-reviews-mockup {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        padding: 8px;
        border-radius: 6px;
        font-size: 9px;
        color: #4b5563;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .preview-offers-mockup {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
    }
    .preview-offers-item {
        height: 50px;
        background-color: #ffe4e6;
        border: 1px dashed #f43f5e;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        color: #e11d48;
        font-weight: bold;
    }
    .preview-flashdeals-mockup {
        width: 100%;
        height: 75px;
        background: linear-gradient(135deg, #1e1b4b 0%, #311042 100%);
        border-radius: 6px;
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 6px;
    }
    .preview-flash-title {
        font-size: 10px;
        font-weight: bold;
        color: #facc15;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    .preview-flash-timer {
        display: flex;
        gap: 4px;
    }
    .preview-timer-box {
        background: rgba(255,255,255,0.2);
        padding: 2px 4px;
        border-radius: 2px;
        font-size: 8px;
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3 pb-2 border-bottom border-gray">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Homepage Dynamic Builder') }}</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <form action="{{ route('website.homepage-builder.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card rounded-0 shadow-sm border-0">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fs-16 fw-600">{{ translate('Manage Homepage Layout & Sections') }}</h5>
                    <button type="button" class="btn btn-primary btn-sm rounded-2 d-flex align-items-center" onclick="addSection()">
                        <i class="las la-plus mr-1"></i> {{ translate('Add Section') }}
                    </button>
                </div>
                
                <div class="card-body">
                    <div id="sections-container">
                        @php
                            $sections_json = get_setting('homepage_sections_configuration');
                            if (empty($sections_json) || $sections_json == '[]') {
                                $sections = [
                                    ['id' => 'sec_slider', 'type' => 'home_slider', 'status' => 1, 'heading' => 'Home Slider', 'show_border' => 0],
                                    ['id' => 'sec_offers', 'type' => 'offers', 'status' => 1, 'heading' => 'Dynamic Offers', 'show_border' => 0],
                                    ['id' => 'sec_flash', 'type' => 'flash_deals', 'status' => 1, 'heading' => 'Flash Deals', 'show_border' => 0],
                                    ['id' => 'sec_todays_deal', 'type' => 'todays_deal', 'heading' => "Today's Deals", 'subheading' => 'Unbeatable offers await, ensuring maximum savings', 'status' => 1, 'show_border' => 0],
                                    ['id' => 'sec_banner1', 'type' => 'banner_level_1', 'status' => 1, 'heading' => 'Banner Level 1', 'show_border' => 1],
                                    ['id' => 'sec_featured', 'type' => 'featured_products', 'heading' => 'Featured Products', 'subheading' => 'Handpicked outstanding items selected for you', 'status' => 1, 'show_border' => 0],
                                    ['id' => 'sec_banner2', 'type' => 'banner_level_2', 'status' => 1, 'heading' => 'Banner Level 2', 'show_border' => 1],
                                    ['id' => 'sec_best_selling', 'type' => 'best_selling', 'heading' => 'Best Selling', 'subheading' => 'Our top-rated products loved by customers', 'status' => 1, 'show_border' => 0],
                                    ['id' => 'sec_newest', 'type' => 'newest_products', 'heading' => 'Latest Products', 'subheading' => 'Discover our newest arrivals added recently', 'status' => 1, 'show_border' => 0],
                                    ['id' => 'sec_banner3', 'type' => 'banner_level_3', 'status' => 1, 'heading' => 'Banner Level 3', 'show_border' => 1],
                                    ['id' => 'sec_reviews', 'type' => 'reviews', 'status' => 1, 'heading' => 'Customer Reviews', 'show_border' => 0],
                                ];
                            } else {
                                $sections = json_decode($sections_json, true);
                            }
                        @endphp
                        
                        @forelse($sections as $index => $section)
                            <div class="card section-card mb-4 border rounded-2 shadow-none" data-index="{{ $index }}">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
                                    <div class="d-flex align-items-center w-70">
                                        <span class="badge badge-secondary mr-2 fs-12">#<span class="section-index">{{ $index + 1 }}</span></span>
                                        <strong class="section-title-preview text-dark fs-14">{{ $section['heading'] ?: translate('New Section') }}</strong>
                                        <span class="badge badge-info ml-2 text-capitalize section-type-badge">{{ str_replace('_', ' ', $section['type'] ?? '') }}</span>
                                        <i class="las la-angle-down ml-2 text-secondary collapse-icon"></i>
                                    </div>
                                    <div class="d-flex align-items-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-icon" onclick="moveUp(this)" title="{{ translate('Move Up') }}">
                                            <i class="las la-angle-up"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary btn-icon" onclick="moveDown(this)" title="{{ translate('Move Down') }}">
                                            <i class="las la-angle-down"></i>
                                        </button>
                                        <button type="button" class="btn btn-xs btn-soft-danger btn-icon" onclick="deleteSection(this)" title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- card-body is collapsed by default -->
                                <div class="card-body p-3" style="display: none;">
                                    <div class="row align-items-start">
                                        <!-- Left Column: Visual Preview -->
                                        <div class="col-md-4">
                                            <div class="section-preview-box">
                                                <!-- Home Slider Preview -->
                                                <div class="preview-slider-mockup type-preview" style="@if(($section['type'] ?? '') != 'home_slider') display:none; @endif">
                                                    <i class="las la-angle-left fs-14"></i>
                                                    <span class="fs-10">{{ translate('Home Slider Banner') }}</span>
                                                    <i class="las la-angle-right fs-14"></i>
                                                </div>

                                                <!-- Banner 1 Preview -->
                                                <div class="preview-banner-1-mockup type-preview" style="@if(($section['type'] ?? '') != 'banner_level_1') display:none; @endif">
                                                    <div class="preview-banner-item">Banner 1</div>
                                                    <div class="preview-banner-item">Banner 2</div>
                                                    <div class="preview-banner-item">Banner 3</div>
                                                </div>

                                                <!-- Banner 2 Preview -->
                                                <div class="preview-banner-2-mockup type-preview" style="@if(($section['type'] ?? '') != 'banner_level_2') display:none; @endif">
                                                    <div class="preview-banner-item">Banner 1</div>
                                                    <div class="preview-banner-item">Banner 2</div>
                                                </div>

                                                <!-- Banner 3 Preview -->
                                                <div class="preview-banner-3-mockup type-preview" style="@if(($section['type'] ?? '') != 'banner_level_3') display:none; @endif">
                                                    <span>{{ translate('Full Width Promo Banner') }}</span>
                                                </div>

                                                <!-- Reviews Preview -->
                                                <div class="preview-reviews-mockup type-preview" style="@if(($section['type'] ?? '') != 'reviews') display:none; @endif">
                                                    <div class="rating rating-mr-1 mb-1 text-warning" style="font-size: 8px;">
                                                        <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>
                                                    </div>
                                                    <strong>"Excellent quality furniture!"</strong>
                                                </div>

                                                <!-- Offers Preview -->
                                                <div class="preview-offers-mockup type-preview" style="@if(($section['type'] ?? '') != 'offers') display:none; @endif">
                                                    <div class="preview-offers-item">{{ translate('Hot Offer 1') }}</div>
                                                    <div class="preview-offers-item">{{ translate('Hot Offer 2') }}</div>
                                                </div>

                                                <!-- Flash Deals Preview -->
                                                <div class="preview-flashdeals-mockup type-preview" style="@if(($section['type'] ?? '') != 'flash_deals') display:none; @endif">
                                                    <div class="preview-flash-title"><i class="las la-bolt"></i> {{ translate('Flash Sale') }}</div>
                                                    <div class="preview-flash-timer">
                                                        <span class="preview-timer-box">12h</span>
                                                        <span class="preview-timer-box">34m</span>
                                                        <span class="preview-timer-box">56s</span>
                                                    </div>
                                                </div>

                                                <!-- Products Preview (Deals, featured, best selling, categories) -->
                                                <div class="preview-products-mockup type-preview" style="@if(in_array(($section['type'] ?? ''), ['home_slider', 'banner_level_1', 'banner_level_2', 'banner_level_3', 'reviews', 'offers', 'flash_deals'])) display:none; @endif">
                                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                                        <span class="fs-10 fw-700 text-dark">{{ translate('Section Header') }}</span>
                                                        <span class="text-primary" style="font-size: 8px;">{{ translate('View All') }} &rarr;</span>
                                                    </div>
                                                    <div class="preview-products-grid">
                                                        <div class="preview-product-card"></div>
                                                        <div class="preview-product-card"></div>
                                                        <div class="preview-product-card"></div>
                                                        <div class="preview-product-card"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Column: Settings fields -->
                                        <div class="col-md-8 border-left">
                                            <div class="form-group">
                                                <label class="fs-12 fw-600">{{ translate('Section Type') }}</label>
                                                <select name="sections[{{ $index }}][type]" class="form-control form-control-sm section-type-select" onchange="toggleSectionType(this)">
                                                    <option value="home_slider" @if(($section['type'] ?? '') == 'home_slider') selected @endif>{{ translate('Home Slider') }}</option>
                                                    <option value="banner_level_1" @if(($section['type'] ?? '') == 'banner_level_1') selected @endif>{{ translate('Banner Level 1') }}</option>
                                                    <option value="banner_level_2" @if(($section['type'] ?? '') == 'banner_level_2') selected @endif>{{ translate('Banner Level 2') }}</option>
                                                    <option value="banner_level_3" @if(($section['type'] ?? '') == 'banner_level_3') selected @endif>{{ translate('Banner Level 3') }}</option>
                                                    <option value="offers" @if(($section['type'] ?? '') == 'offers') selected @endif>{{ translate('Dynamic Offers Section') }}</option>
                                                    <option value="flash_deals" @if(($section['type'] ?? '') == 'flash_deals') selected @endif>{{ translate('Flash Deals Section') }}</option>
                                                    <option value="todays_deal" @if(($section['type'] ?? '') == 'todays_deal') selected @endif>{{ translate('Today\'s Deals Products') }}</option>
                                                    <option value="featured_products" @if(($section['type'] ?? '') == 'featured_products') selected @endif>{{ translate('Featured Products') }}</option>
                                                    <option value="best_selling" @if(($section['type'] ?? '') == 'best_selling') selected @endif>{{ translate('Best Selling Products') }}</option>
                                                    <option value="newest_products" @if(($section['type'] ?? '') == 'newest_products') selected @endif>{{ translate('Newest Products') }}</option>
                                                    <option value="category_products" @if(($section['type'] ?? '') == 'category_products') selected @endif>{{ translate('Category-Specific Products') }}</option>
                                                    <option value="reviews" @if(($section['type'] ?? '') == 'reviews') selected @endif>{{ translate('Customer Reviews Section') }}</option>
                                                </select>
                                            </div>

                                            <!-- Header fields (For non-slider/banner sections) -->
                                            <div class="heading-fields" style="@if(in_array(($section['type'] ?? ''), ['home_slider', 'banner_level_1', 'banner_level_2', 'banner_level_3'])) display:none; @endif">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="fs-12 fw-600">{{ translate('Heading / Title') }}</label>
                                                            <input type="text" name="sections[{{ $index }}][heading]" class="form-control form-control-sm section-heading-input" value="{{ $section['heading'] ?? '' }}" onkeyup="updateTitlePreview(this)">
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label class="fs-12 fw-600">{{ translate('Subheading / Subtitle') }}</label>
                                                            <input type="text" name="sections[{{ $index }}][subheading]" class="form-control form-control-sm" value="{{ $section['subheading'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Category selector (Only for category products type) -->
                                            <div class="category-fields" style="@if(($section['type'] ?? '') != 'category_products') display:none; @endif">
                                                <div class="form-group">
                                                    <label class="fs-12 fw-600">{{ translate('Select Category') }}</label>
                                                    <select name="sections[{{ $index }}][category_id]" class="form-control form-control-sm aiz-selectpicker" data-live-search="true">
                                                        <option value="">{{ translate('Choose Category') }}</option>
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}" @if(($section['category_id'] ?? 0) == $category->id) selected @endif>{{ $category->getTranslation('name') }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Dynamic items lists (For Slider and Banner Level 1/2/3) -->
                                            <div class="slides-fields" style="@if(!in_array(($section['type'] ?? ''), ['home_slider', 'banner_level_1', 'banner_level_2', 'banner_level_3'])) display:none; @endif">
                                                <label class="fs-12 fw-600 d-block border-bottom pb-2">{{ translate('Banners / Slides List') }}</label>
                                                <div class="slides-target" data-index="{{ $index }}">
                                                    @php
                                                        $images = $section['slider_images'] ?? $section['banner_images'] ?? [];
                                                        $links = $section['slider_links'] ?? $section['banner_links'] ?? [];
                                                        
                                                        // Fallback for form inputs: load global values
                                                        if (empty($images)) {
                                                            $type = $section['type'] ?? '';
                                                            if ($type == 'home_slider') {
                                                                $images = json_decode(get_setting('home_slider_images', '[]'), true) ?: [];
                                                                $links = json_decode(get_setting('home_slider_links', '[]'), true) ?: [];
                                                            } elseif ($type == 'banner_level_1') {
                                                                $images = json_decode(get_setting('home_banner1_images', '[]'), true) ?: [];
                                                                $links = json_decode(get_setting('home_banner1_links', '[]'), true) ?: [];
                                                            } elseif ($type == 'banner_level_2') {
                                                                $images = json_decode(get_setting('home_banner2_images', '[]'), true) ?: [];
                                                                $links = json_decode(get_setting('home_banner2_links', '[]'), true) ?: [];
                                                            } elseif ($type == 'banner_level_3') {
                                                                $images = json_decode(get_setting('home_banner3_images', '[]'), true) ?: [];
                                                                $links = json_decode(get_setting('home_banner3_links', '[]'), true) ?: [];
                                                            }
                                                        }
                                                        
                                                        // Resolve variable names for input elements
                                                        $input_img_name = (($section['type'] ?? '') == 'home_slider') ? 'slider_images' : 'banner_images';
                                                        $input_lnk_name = (($section['type'] ?? '') == 'home_slider') ? 'slider_links' : 'banner_links';
                                                    @endphp
                                                    @foreach($images as $key => $image_id)
                                                        <div class="row align-items-center mb-3 remove-parent border p-2 bg-soft-light rounded-1">
                                                            <div class="col-md-5">
                                                                <div class="form-group mb-0">
                                                                    <div class="input-group input-group-sm" data-toggle="aizuploader" data-type="image">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div>
                                                                        </div>
                                                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                        <!-- Save image ids as array inside the parent section -->
                                                                        <input type="hidden" name="sections[{{ $index }}][{{ $input_img_name }}][]" class="selected-files slide-img-input" value="{{ $image_id }}">
                                                                    </div>
                                                                    <div class="file-preview box sm"></div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-0">
                                                                    <input type="text" name="sections[{{ $index }}][{{ $input_lnk_name }}][]" class="form-control form-control-sm slide-link-input" placeholder="http://" value="{{ $links[$key] ?? '' }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1 text-right">
                                                                <button type="button" class="btn btn-icon btn-sm btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                                                                    <i class="las la-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <button type="button" class="btn btn-xs btn-outline-success rounded-2" onclick="addSlideEntry(this)">
                                                    <i class="las la-plus"></i> {{ translate('Add Slide/Banner') }}
                                                </button>
                                            </div>

                                            <!-- Spacing & Styling Fields -->
                                            <div class="border-top pt-3 mt-3">
                                                <h6 class="fs-12 fw-700 text-primary mb-3">{{ translate('Section Styling & Spacing') }}</h6>
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="fs-11 fw-600">{{ translate('Heading Font Size (px)') }}</label>
                                                            <input type="number" name="sections[{{ $index }}][heading_size]" class="form-control form-control-sm" value="{{ $section['heading_size'] ?? '36' }}" min="12" max="100">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="fs-11 fw-600">{{ translate('Top Padding (px)') }}</label>
                                                            <input type="number" name="sections[{{ $index }}][padding_top]" class="form-control form-control-sm" value="{{ $section['padding_top'] ?? '20' }}" min="0" max="150">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="fs-11 fw-600">{{ translate('Bottom Padding (px)') }}</label>
                                                            <input type="number" name="sections[{{ $index }}][padding_bottom]" class="form-control form-control-sm" value="{{ $section['padding_bottom'] ?? '20' }}" min="0" max="150">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="fs-11 fw-600">{{ translate('Background Color') }}</label>
                                                            <input type="color" name="sections[{{ $index }}][bg_color]" class="form-control form-control-sm" value="{{ $section['bg_color'] ?? '#ffffff' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label class="fs-11 fw-600 d-block">{{ translate('Show Section Border') }}</label>
                                                            <label class="aiz-switch aiz-switch-success mb-0">
                                                                <input type="checkbox" name="sections[{{ $index }}][show_border]" value="1" @if(($section['show_border'] ?? 0) == 1) checked @endif>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-0">
                                                            <label class="fs-11 fw-600">{{ translate('Border Color') }}</label>
                                                            <input type="color" name="sections[{{ $index }}][border_color]" class="form-control form-control-sm" value="{{ $section['border_color'] ?? '#e5e7eb' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0 mt-3 border-top pt-2">
                                                <label class="fs-12 fw-600">{{ translate('Section Status') }}</label>
                                                <div class="d-flex align-items-center">
                                                    <label class="aiz-switch aiz-switch-success mb-0">
                                                        <input type="checkbox" name="sections[{{ $index }}][status]" value="1" @if(($section['status'] ?? 1) == 1) checked @endif>
                                                        <span class="slider round"></span>
                                                    </label>
                                                    <span class="ml-2 fs-13 text-secondary">{{ translate('Show on Storefront') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-secondary empty-state">
                                <i class="las la-4x la-cubes mb-3 text-muted"></i>
                                <h5>{{ translate('No Sections Configured') }}</h5>
                                <p class="text-muted fs-12">{{ translate('Click the "Add Section" button to build dynamic layouts.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Bottom Bar -->
                    <div class="row bg-light p-3 mt-4 border-top">
                        <div class="col-md-8 d-none d-md-block">
                            <div class="d-flex align-items-center">
                                <div class="text-secondary mr-3"><i class="las la-3x la-sliders-h"></i></div>
                                <div>
                                    <h4 class="fs-15 text-dark fw-700 mb-1">{{ translate('Save Homepage Layout') }}</h4>
                                    <small class="fs-12 text-secondary">{{ translate('Configure elements as per your requirements and save to apply layout changes.') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-primary">{{ translate('Save Layout') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Template Card for dynamically added sections -->
<template id="section-template">
    <div class="card section-card mb-4 border rounded-2 shadow-none is-expanded" data-index="__INDEX__">
        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2 px-3">
            <div class="d-flex align-items-center w-70">
                <span class="badge badge-secondary mr-2 fs-12">#<span class="section-index">__NUMBER__</span></span>
                <strong class="section-title-preview text-dark fs-14">{{ translate('New Section') }}</strong>
                <span class="badge badge-info ml-2 text-capitalize section-type-badge">{{ translate('Home Slider') }}</span>
                <i class="las la-angle-up ml-2 text-secondary collapse-icon"></i>
            </div>
            <div class="d-flex align-items-center" style="gap: 6px;">
                <button type="button" class="btn btn-xs btn-outline-secondary btn-icon" onclick="moveUp(this)" title="{{ translate('Move Up') }}">
                    <i class="las la-angle-up"></i>
                </button>
                <button type="button" class="btn btn-xs btn-outline-secondary btn-icon" onclick="moveDown(this)" title="{{ translate('Move Down') }}">
                    <i class="las la-angle-down"></i>
                </button>
                <button type="button" class="btn btn-xs btn-soft-danger btn-icon" onclick="deleteSection(this)" title="{{ translate('Delete') }}">
                    <i class="las la-trash"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row align-items-start">
                <!-- Left Column: Visual Preview -->
                <div class="col-md-4">
                    <div class="section-preview-box">
                        <!-- Slider Preview -->
                        <div class="preview-slider-mockup type-preview">
                            <i class="las la-angle-left fs-14"></i>
                            <span class="fs-10">{{ translate('Home Slider Banner') }}</span>
                            <i class="las la-angle-right fs-14"></i>
                        </div>

                        <!-- Banner 1 Preview -->
                        <div class="preview-banner-1-mockup type-preview" style="display:none;">
                            <div class="preview-banner-item">Banner 1</div>
                            <div class="preview-banner-item">Banner 2</div>
                            <div class="preview-banner-item">Banner 3</div>
                        </div>

                        <!-- Banner 2 Preview -->
                        <div class="preview-banner-2-mockup type-preview" style="display:none;">
                            <div class="preview-banner-item">Banner 1</div>
                            <div class="preview-banner-item">Banner 2</div>
                        </div>

                        <!-- Banner 3 Preview -->
                        <div class="preview-banner-3-mockup type-preview" style="display:none;">
                            <span>{{ translate('Full Width Promo Banner') }}</span>
                        </div>

                        <!-- Reviews Preview -->
                        <div class="preview-reviews-mockup type-preview" style="display:none;">
                            <div class="rating rating-mr-1 mb-1 text-warning" style="font-size: 8px;">
                                <i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i><i class="las la-star"></i>
                            </div>
                            <strong>"Excellent quality furniture!"</strong>
                        </div>

                        <!-- Offers Preview -->
                        <div class="preview-offers-mockup type-preview" style="display:none;">
                            <div class="preview-offers-item">{{ translate('Hot Offer 1') }}</div>
                            <div class="preview-offers-item">{{ translate('Hot Offer 2') }}</div>
                        </div>

                        <!-- Flash Deals Preview -->
                        <div class="preview-flashdeals-mockup type-preview" style="display:none;">
                            <div class="preview-flash-title"><i class="las la-bolt"></i> {{ translate('Flash Sale') }}</div>
                            <div class="preview-flash-timer">
                                <span class="preview-timer-box">12h</span>
                                <span class="preview-timer-box">34m</span>
                                <span class="preview-timer-box">56s</span>
                            </div>
                        </div>

                        <!-- Products Preview -->
                        <div class="preview-products-mockup type-preview" style="display:none;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fs-10 fw-700 text-dark">{{ translate('Section Header') }}</span>
                                <span class="text-primary" style="font-size: 8px;">{{ translate('View All') }} &rarr;</span>
                            </div>
                            <div class="preview-products-grid">
                                <div class="preview-product-card"></div>
                                <div class="preview-product-card"></div>
                                <div class="preview-product-card"></div>
                                <div class="preview-product-card"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Settings fields -->
                <div class="col-md-8 border-left">
                    <div class="form-group">
                        <label class="fs-12 fw-600">{{ translate('Section Type') }}</label>
                        <select name="sections[__INDEX__][type]" class="form-control form-control-sm section-type-select" onchange="toggleSectionType(this)">
                            <option value="home_slider" selected>{{ translate('Home Slider') }}</option>
                            <option value="banner_level_1">{{ translate('Banner Level 1') }}</option>
                            <option value="banner_level_2">{{ translate('Banner Level 2') }}</option>
                            <option value="banner_level_3">{{ translate('Banner Level 3') }}</option>
                            <option value="offers">{{ translate('Dynamic Offers Section') }}</option>
                            <option value="flash_deals">{{ translate('Flash Deals Section') }}</option>
                            <option value="todays_deal">{{ translate('Today\'s Deals Products') }}</option>
                            <option value="featured_products">{{ translate('Featured Products') }}</option>
                            <option value="best_selling">{{ translate('Best Selling Products') }}</option>
                            <option value="newest_products">{{ translate('Newest Products') }}</option>
                            <option value="category_products">{{ translate('Category-Specific Products') }}</option>
                            <option value="reviews">{{ translate('Customer Reviews Section') }}</option>
                        </select>
                    </div>

                    <!-- Header fields -->
                    <div class="heading-fields" style="display:none;">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="fs-12 fw-600">{{ translate('Heading / Title') }}</label>
                                    <input type="text" name="sections[__INDEX__][heading]" class="form-control form-control-sm section-heading-input" value="" onkeyup="updateTitlePreview(this)">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="fs-12 fw-600">{{ translate('Subheading / Subtitle') }}</label>
                                    <input type="text" name="sections[__INDEX__][subheading]" class="form-control form-control-sm" value="">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Category Selector -->
                    <div class="category-fields" style="display:none;">
                        <div class="form-group">
                            <label class="fs-12 fw-600">{{ translate('Select Category') }}</label>
                            <select name="sections[__INDEX__][category_id]" class="form-control form-control-sm">
                                <option value="">{{ translate('Choose Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Slide items list -->
                    <div class="slides-fields">
                        <label class="fs-12 fw-600 d-block border-bottom pb-2">{{ translate('Banners / Slides List') }}</label>
                        <div class="slides-target" data-index="__INDEX__">
                            <!-- Dynamically added slide entries -->
                        </div>
                        <button type="button" class="btn btn-xs btn-outline-success rounded-2" onclick="addSlideEntry(this)">
                            <i class="las la-plus"></i> {{ translate('Add Slide/Banner') }}
                        </button>
                    </div>

                    <!-- Spacing & Styling Fields -->
                    <div class="border-top pt-3 mt-3">
                        <h6 class="fs-12 fw-700 text-primary mb-3">{{ translate('Section Styling & Spacing') }}</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="fs-11 fw-600">{{ translate('Heading Font Size (px)') }}</label>
                                    <input type="number" name="sections[__INDEX__][heading_size]" class="form-control form-control-sm" value="36" min="12" max="100">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="fs-11 fw-600">{{ translate('Top Padding (px)') }}</label>
                                    <input type="number" name="sections[__INDEX__][padding_top]" class="form-control form-control-sm" value="20" min="0" max="150">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="fs-11 fw-600">{{ translate('Bottom Padding (px)') }}</label>
                                    <input type="number" name="sections[__INDEX__][padding_bottom]" class="form-control form-control-sm" value="20" min="0" max="150">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="fs-11 fw-600">{{ translate('Background Color') }}</label>
                                    <input type="color" name="sections[__INDEX__][bg_color]" class="form-control form-control-sm" value="#ffffff">
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="fs-11 fw-600 d-block">{{ translate('Show Section Border') }}</label>
                                    <label class="aiz-switch aiz-switch-success mb-0">
                                        <input type="checkbox" name="sections[__INDEX__][show_border]" value="1">
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="fs-11 fw-600">{{ translate('Border Color') }}</label>
                                    <input type="color" name="sections[__INDEX__][border_color]" class="form-control form-control-sm" value="#e5e7eb">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 mt-3 border-top pt-2">
                        <label class="fs-12 fw-600">{{ translate('Section Status') }}</label>
                        <div class="d-flex align-items-center">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="sections[__INDEX__][status]" value="1" checked>
                                <span class="slider round"></span>
                            </label>
                            <span class="ml-2 fs-13 text-secondary">{{ translate('Show on Storefront') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<!-- Template for slider entries inside a section card -->
<template id="slide-entry-template">
    <div class="row align-items-center mb-3 remove-parent border p-2 bg-soft-light rounded-1">
        <div class="col-md-5">
            <div class="form-group mb-0">
                <div class="input-group input-group-sm" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                        <div class="input-group-text bg-soft-secondary">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="sections[__INDEX__][slider_images][]" class="selected-files slide-img-input" value="">
                </div>
                <div class="file-preview box sm"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-0">
                <input type="text" name="sections[__INDEX__][slider_links][]" class="form-control form-control-sm slide-link-input" placeholder="http://" value="">
            </div>
        </div>
        <div class="col-md-1 text-right">
            <button type="button" class="btn btn-icon btn-sm btn-circle btn-soft-danger" data-toggle="remove-parent" data-parent=".remove-parent">
                <i class="las la-times"></i>
            </button>
        </div>
    </div>
</template>

@endsection

@section('script')
<script>
    // Toggle Collapse card body
    $(document).on('click', '.section-card .card-header', function(e) {
        if ($(e.target).closest('button, a, input, label').length) {
            return; // Ignore clicks on action buttons
        }
        
        var card = $(this).closest('.section-card');
        var body = card.find('.card-body');
        var icon = card.find('.collapse-icon');
        
        body.slideToggle(200);
        card.toggleClass('is-expanded');
        
        if (card.hasClass('is-expanded')) {
            icon.removeClass('la-angle-down').addClass('la-angle-up');
        } else {
            icon.removeClass('la-angle-up').addClass('la-angle-down');
        }
    });

    function addSection() {
        var container = $('#sections-container');
        container.find('.empty-state').remove();

        var index = container.find('.section-card').length;
        var number = index + 1;
        
        var template = $('#section-template').html();
        var html = template.replace(/__INDEX__/g, index).replace(/__NUMBER__/g, number);
        
        container.append(html);
        reindexSections();
    }

    function addSlideEntry(btn) {
        var sectionCard = $(btn).closest('.section-card');
        var index = sectionCard.attr('data-index');
        var target = sectionCard.find('.slides-target');
        
        var template = $('#slide-entry-template').html();
        var html = template.replace(/__INDEX__/g, index);
        
        target.append(html);
        reindexSections();
    }

    function deleteSection(btn) {
        if(confirm("{{ translate('Are you sure you want to delete this section?') }}")) {
            $(btn).closest('.section-card').remove();
            reindexSections();
            
            if ($('#sections-container .section-card').length === 0) {
                $('#sections-container').append(`
                    <div class="text-center py-5 text-secondary empty-state">
                        <i class="las la-4x la-cubes mb-3 text-muted"></i>
                        <h5>{{ translate('No Sections Configured') }}</h5>
                        <p class="text-muted fs-12">{{ translate('Click the "Add Section" button to build dynamic layouts.') }}</p>
                    </div>
                `);
            }
        }
    }

    function moveUp(btn) {
        var card = $(btn).closest('.section-card');
        var prev = card.prev('.section-card');
        if (prev.length > 0) {
            card.insertBefore(prev);
            reindexSections();
        }
    }

    function moveDown(btn) {
        var card = $(btn).closest('.section-card');
        var next = card.next('.section-card');
        if (next.length > 0) {
            card.insertAfter(next);
            reindexSections();
        }
    }

    function reindexSections() {
        $('#sections-container .section-card').each(function(index) {
            var number = index + 1;
            $(this).attr('data-index', index);
            $(this).find('.section-index').text(number);
            
            // Re-index all base inputs
            $(this).find('input, select, textarea').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/sections\[\d+\]/, 'sections[' + index + ']');
                    $(this).attr('name', newName);
                }
            });
            
            // Re-index dynamic slide entries inside this card
            $(this).find('.slides-target .row').each(function() {
                var imgInput = $(this).find('.slide-img-input');
                var linkInput = $(this).find('.slide-link-input');
                
                var type = $(this).closest('.section-card').find('.section-type-select').val();
                var imgName = 'sections[' + index + '][slider_images][]';
                var linkName = 'sections[' + index + '][slider_links][]';
                
                if (type.startsWith('banner_level')) {
                    imgName = 'sections[' + index + '][banner_images][]';
                    linkName = 'sections[' + index + '][banner_links][]';
                }
                
                imgInput.attr('name', imgName);
                linkInput.attr('name', linkName);
            });
        });
    }

    function updateTitlePreview(input) {
        var card = $(input).closest('.section-card');
        var text = $(input).val();
        if(text.trim() === '') {
            text = "{{ translate('New Section') }}";
        }
        card.find('.section-title-preview').text(text);
    }

    function toggleSectionType(select) {
        var card = $(select).closest('.section-card');
        var type = $(select).val();
        
        var badgeText = $(select).find('option:selected').text();
        card.find('.section-type-badge').text(badgeText);
        
        // Hide all fields first
        card.find('.heading-fields').hide();
        card.find('.category-fields').hide();
        card.find('.slides-fields').hide();
        
        // Hide all previews first
        card.find('.section-preview-box .type-preview').hide();
        
        // Show correct fields & preview mockup
        if (type === 'home_slider') {
            card.find('.slides-fields').show();
            card.find('.section-preview-box .preview-slider-mockup').show();
        } else if (type === 'banner_level_1') {
            card.find('.slides-fields').show();
            card.find('.section-preview-box .preview-banner-1-mockup').show();
        } else if (type === 'banner_level_2') {
            card.find('.slides-fields').show();
            card.find('.section-preview-box .preview-banner-2-mockup').show();
        } else if (type === 'banner_level_3') {
            card.find('.slides-fields').show();
            card.find('.section-preview-box .preview-banner-3-mockup').show();
        } else if (type === 'reviews') {
            card.find('.heading-fields').show();
            card.find('.section-preview-box .preview-reviews-mockup').show();
        } else if (type === 'offers') {
            card.find('.heading-fields').show();
            card.find('.section-preview-box .preview-offers-mockup').show();
        } else if (type === 'flash_deals') {
            card.find('.heading-fields').show();
            card.find('.section-preview-box .preview-flashdeals-mockup').show();
        } else {
            card.find('.heading-fields').show();
            card.find('.section-preview-box .preview-products-mockup').show();
            if (type === 'category_products') {
                card.find('.category-fields').show();
            }
        }
        
        // Trigger index rename on inputs
        reindexSections();
    }
</script>
@endsection
