@php
$configured_sections = json_decode(get_setting('homepage_sections_configuration', '[]'), true);
if (empty($configured_sections)) {
$configured_sections = [
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
}
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@400&display=swap');

    .modern-section-title {
        font-family: 'Playfair Display', serif !important;
        font-weight: 700 !important;
        font-size: 36px !important;
        line-height: 50px !important;
        color: #1D1712 !important;
        letter-spacing: 0% !important;
    }

    .modern-section-subtitle {
        font-family: 'Poppins', sans-serif !important;
        font-weight: 400 !important;
        font-size: 18px !important;
        line-height: 100% !important;
        color: #393939 !important;
        letter-spacing: 0% !important;
        margin-top: 6px;
    }

    .home-section-arrow:disabled,
    .home-section-arrow.disabled {
        opacity: 0.35 !important;
        cursor: not-allowed !important;
        pointer-events: none !important;
        background-color: #f0f0f0 !important;
        border-color: #ddd !important;
        color: #aaa !important;
    }
</style>

@foreach($configured_sections as $section)
@if(($section['status'] ?? 1) == 1)
@php
$secId = 'section_' . ($section['id'] ?? uniqid());
$theme = get_setting('homepage_select', 'metro');

$default_pad = (($section['type'] ?? '') == 'home_slider') ? '0' : '20';
$pad_top = $section['padding_top'] ?? $default_pad;
$pad_bottom = $section['padding_bottom'] ?? $default_pad;
$bg_color = $section['bg_color'] ?? 'transparent';
$show_border = ($section['show_border'] ?? 0) == 1;
$border_color = $section['border_color'] ?? '#21252933';
$heading_size = $section['heading_size'] ?? '36';

$type = $section['type'] ?? '';
$has_content = false;

if ($type == 'home_slider') {
$slider_images = $section['slider_images'] ?? [];
$slider_links = $section['slider_links'] ?? [];
if (empty($slider_images)) {
$slider_images = json_decode(get_setting('home_slider_images', '[]'), true) ?: [];
$slider_links = json_decode(get_setting('home_slider_links', '[]'), true) ?: [];
}
$has_content = !empty($slider_images) && is_array($slider_images);
} elseif (in_array($type, ['banner_level_1', 'banner_level_2', 'banner_level_3'])) {
$banner_images = $section['banner_images'] ?? [];
$banner_links = $section['banner_links'] ?? [];
if (empty($banner_images)) {
if ($type == 'banner_level_1') {
$banner_images = json_decode(get_setting('home_banner1_images', '[]'), true) ?: [];
$banner_links = json_decode(get_setting('home_banner1_links', '[]'), true) ?: [];
} elseif ($type == 'banner_level_2') {
$banner_images = json_decode(get_setting('home_banner2_images', '[]'), true) ?: [];
$banner_links = json_decode(get_setting('home_banner2_links', '[]'), true) ?: [];
} elseif ($type == 'banner_level_3') {
$banner_images = json_decode(get_setting('home_banner3_images', '[]'), true) ?: [];
$banner_links = json_decode(get_setting('home_banner3_links', '[]'), true) ?: [];
}
}
$has_content = !empty($banner_images) && is_array($banner_images);
} elseif ($type == 'offers') {
$has_content = \Cache::remember('homepage_offers_count', 86400, function () {
return \App\Models\Offer::homeSection()->count() > 0;
});
} elseif ($type == 'flash_deals') {
$flash_deal = get_featured_flash_deal();
$has_content = ($flash_deal != null);
} elseif ($type == 'reviews') {
$homepage_reviews_count = \Cache::remember('homepage_reviews_count', 86400, function () {
return \App\Models\HomepageReview::where('status', 1)->count();
});
$section_status = get_setting('homepage_reviews_section_status', 1);
$has_content = ($section_status == 1 && $homepage_reviews_count > 0);
} else {
// Product section
$products = collect();
if ($type == 'todays_deal') {
$products = \Cache::remember('todays_deal_products_home_8', 3600, function () {
return filter_products(\App\Models\Product::with(['thumbnail', 'stocks', 'taxes'])->where('todays_deal', '1'))->limit(8)->get();
});
$default_heading = translate("Today's Deals");
$default_subheading = translate('Unbeatable offers await, ensuring maximum savings');
$view_all_link = route('todays-deal');
} elseif ($type == 'featured_products') {
$products = \Cache::remember('featured_products_home_8', 3600, function () {
return filter_products(\App\Models\Product::with(['thumbnail', 'stocks', 'taxes'])->where('featured', 1))->latest()->limit(8)->get();
});
$default_heading = translate('Featured Products');
$default_subheading = translate('Handpicked outstanding items selected for you');
$view_all_link = route('search');
} elseif ($type == 'best_selling') {
$products = \Cache::remember('best_selling_products_home_8', 3600, function () {
return filter_products(\App\Models\Product::with(['thumbnail', 'stocks', 'taxes'])->orderBy('num_of_sale', 'desc'))->limit(8)->get();
});
$default_heading = translate('Best Selling');
$default_subheading = translate('Our top-rated products loved by customers');
$view_all_link = route('search');
} elseif ($type == 'newest_products') {
$products = \Cache::remember('newest_products_8', 3600, function () {
return filter_products(\App\Models\Product::with(['thumbnail', 'stocks', 'taxes'])->latest())->limit(8)->get();
});
$default_heading = translate('Latest Products');
$default_subheading = translate('Discover our newest arrivals added recently');
$view_all_link = route('search', ['sort_by' => 'newest']);
} elseif ($type == 'category_products' && !empty($section['category_id'])) {
$catId = $section['category_id'];
$category = \Cache::rememberForever('category_model_' . $catId, function () use ($catId) {
return \App\Models\Category::find($catId);
});
if ($category) {
$products = \Cache::remember('category_products_home_8_' . $catId, 3600, function () use ($catId) {
$category_ids = \App\Utility\CategoryUtility::children_ids($catId);
$category_ids[] = $catId;
return filter_products(\App\Models\Product::with(['thumbnail', 'stocks', 'taxes'])->whereIn('category_id', $category_ids)->latest())->limit(8)->get();
});
$default_heading = $category->getTranslation('name');
$default_subheading = translate('Explore products in ') . $category->getTranslation('name');
$view_all_link = route('products.category', $category->slug);
}
}

$heading = ($section['heading'] ?? '') ?: ($default_heading ?? '');
$subheading = ($section['subheading'] ?? '') ?: ($default_subheading ?? '');
$products = $products->take(8);
$product_count = count($products);
$has_content = $product_count > 0;
}

$wrapper_class = '';
if (in_array($type, ['home_slider', 'reviews', 'offers', 'flash_deals'])) {
$wrapper_class = '';
} elseif (in_array($type, ['banner_level_1', 'banner_level_2', 'banner_level_3'])) {
$wrapper_class = 'banner_section_con';
} else {
$wrapper_class = 'container';
}
@endphp

@if($has_content)
<style>
    #wrapper_{{ $secId }} .modern-section-title {
        font-size: {{ $heading_size }}px !important;
        line-height: 1.4 !important;
    }
</style>

<div class="homepage-section-wrapper {{ $wrapper_class }}" id="wrapper_{{ $secId }}" style="
                padding-top: {{ $pad_top }}px !important; 
                padding-bottom: {{ $pad_bottom }}px !important; 
                background-color: {{ $bg_color }} !important;
                @if($show_border)
                    margin-top:30px;
                    margin-bottom:20px;
                    padding:20px;
                    border: 1px solid {{ $border_color }} !important;
                    border-radius: 10px;    
                @endif
            ">

    <!-- Section: {{ str_replace('_', ' ', $section['type'] ?? '') }} -->
    @if(($section['type'] ?? '') == 'home_slider')
    <!-- Sliders -->
    @if($theme == 'metro')
  <div class="home-banner-area">
        <div class="p-0">
            <div class="home-slider slider-full">
                @if(!empty($slider_images) && is_array($slider_images))
                <div class="aiz-carousel home_banner_img dots-inside-bottom mobile-slider-dots mobile-img-auto-height"
     data-dots="true"
     data-autoplay="false"
     data-infinite="true">
                    @foreach ($slider_images as $key => $imgId)
                    @php
                    $slider_src = is_numeric($imgId) ? uploaded_asset($imgId) : (is_object($imgId) || is_array($imgId) ? (isset($imgId['file_name']) ? my_asset($imgId['file_name']) : '') : $imgId);
                    if (empty($slider_src)) {
                    $slider_src = my_asset($imgId);
                    }
                    @endphp
                    <div class="carousel-box">
                        <a href="{{ $slider_links[$key] ?? '#' }}">
                            <div class="d-block mw-100 img-fit overflow-hidden home_slider_img overflow-hidden">
                                <img class="img-fit m-auto has-transition"
                                    src="{{ $slider_src ?: static_asset('assets/img/placeholder.jpg') }}"
                                    alt="{{ env('APP_NAME') }} slide"
                                    @if($key==0) fetchpriority="high" @else loading="lazy" decoding="async" @endif
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="home-banner-area mb-3 mt-2">
        <div class="container">
            <div class="d-flex flex-wrap position-relative">
                <div class="position-static d-none d-xl-block">
                    @include('frontend.classic.partials.category_menu')
                </div>

                <div class="home-slider w-100">
                    @if(!empty($slider_images) && is_array($slider_images))
                    <div class="aiz-carousel dots-inside-bottom" data-autoplay="true" data-infinite="true">
                        @foreach ($slider_images as $key => $imgId)
                        @php
                        $slider_src = is_numeric($imgId) ? uploaded_asset($imgId) : (is_object($imgId) || is_array($imgId) ? (isset($imgId['file_name']) ? my_asset($imgId['file_name']) : '') : $imgId);
                        if (empty($slider_src)) {
                        $slider_src = my_asset($imgId);
                        }
                        @endphp
                        <div class="carousel-box">
                            <a href="{{ $slider_links[$key] ?? '#' }}">
                                <img class="d-block mw-100 img-fit overflow-hidden h-180px h-md-320px h-lg-460px overflow-hidden"
                                    src="{{ $slider_src ?: static_asset('assets/img/placeholder-rect.jpg') }}"
                                    alt="{{ env('APP_NAME') }} slide"
                                    @if($key==0) fetchpriority="high" @else loading="lazy" decoding="async" @endif
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @elseif(in_array(($section['type'] ?? ''), ['banner_level_1', 'banner_level_2', 'banner_level_3']))
    <!-- Banners -->
    @if(!empty($banner_images) && is_array($banner_images))
    <div class="section-padding home_Section banner_Sections_home">
        <div class="container p-0">
            @php
            $banner_count = count($banner_images);
            $data_md = $banner_count >= 2 ? 2 : 1;
            @endphp
            <div class="w-100">
                <div class="aiz-carousel gutters-5 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ $banner_count }}" data-xxl-items="{{ $banner_count }}"
                    data-xl-items="{{ $banner_count }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false" data-infinite="true">
                    @foreach ($banner_images as $key => $imgId)
                    <div class="carousel-box overflow-hidden hov-scale-img">
                        <a href="{{ $banner_links[$key] ?? '#' }}" class="d-block text-reset overflow-hidden">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($imgId) }}" alt="{{ env('APP_NAME') }} banner"
                                class="img-fluid lazyload w-100 has-transition" loading="lazy" decoding="async"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    @elseif(($section['type'] ?? '') == 'offers')
    <!-- Dynamic Offers & Hot Deals Section -->
    @include('frontend.partials.homepage_offers')

    @elseif(($section['type'] ?? '') == 'flash_deals')
    <!-- Flash Deal Section -->
    @if ($flash_deal != null)
    @php
    $flash_deal_bg = get_setting('flash_deal_bg_color', '#311042');
    $flash_deal_bg_full_width = get_setting('flash_deal_bg_full_width') == 1 ? true : false;
    $flash_deal_banner_menu_text =
    get_setting('flash_deal_banner_menu_text') == 'dark' || get_setting('flash_deal_banner_menu_text') == null
    ? 'text-dark'
    : 'text-white';
    @endphp
    <section class="home_Section my-3"
        style="background: {{ $flash_deal_bg_full_width && $flash_deal_bg != null ? $flash_deal_bg : '' }};"
        id="flash_deal">
        <div class="container">
            <!-- Top Section sm to lg -->
            <div
                class="d-flex d-lg-none flex-wrap mb-2 mb-md-3 @if ($flash_deal_bg_full_width && $flash_deal_bg != null) pt-2 pt-md-3 @endif align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                    <span class="d-inline-block {{ $flash_deal_banner_menu_text }}">{{ translate('Flash Sale') }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24" class="ml-3">
                        <path id="Path_28795" data-name="Path 28795"
                            d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                            transform="translate(-15 -5)" fill="#fcc201" />
                    </svg>
                </h3>
                <!-- Links -->
                <div>
                    <div class="text-dark d-flex align-items-center mb-0">
                        <a href="{{ route('flash-deals') }}"
                            class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">{{ translate('View All Flash Sale') }}</a>
                        <span class=" border-left border-soft-light border-width-2 pl-3">
                            <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Countdown for small device -->
            <div class="bg-white mb-4 d-md-none">
                <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
            </div>

            <div class="row no-gutters align-items-center" style="background: {{ $flash_deal_bg }};">
                <!-- Flash Deals Baner & Countdown -->
                <div class="col-xxl-4 col-lg-5 col-6 h-200px h-md-400px h-lg-475px">
                    <div class="h-100 w-100 w-xl-auto"
                        style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center;">
                        <div class="py-5 px-md-3 px-xl-5 d-none d-md-block">
                            <div class="bg-white">
                                <div class="aiz-count-down-circle"
                                    end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8 col-lg-7 col-6">
                    <div class="pl-3 pr-lg-3 pl-xl-2rem pr-xl-2rem">
                        <!-- Top Section from lg device -->
                        <div
                            class="d-none d-lg-flex flex-wrap mb-2 mb-md-3 align-items-baseline justify-content-between">
                            <!-- Title -->
                            <h3 class="fs-16 fs-md-20 fw-700 mb-2">
                                <span
                                    class="d-inline-block {{ $flash_deal_banner_menu_text }}">{{ translate('Flash Sale') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24"
                                    viewBox="0 0 16 24" class="ml-3">
                                    <path id="Path_28795" data-name="Path 28795"
                                        d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                        transform="translate(-15 -5)" fill="#fcc201" />
                                </svg>
                            </h3>
                            <!-- Links -->
                            <div>
                                <div class="text-dark d-flex align-items-center mb-0">
                                    <a href="{{ route('flash-deals') }}"
                                        class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif mr-3">
                                        {{ translate('View All Flash Sale') }}
                                    </a>
                                    <span class=" border-left border-soft-light border-width-2 pl-3">
                                        <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                            class="fs-10 fs-md-12 fw-700 has-transition {{ $flash_deal_banner_menu_text }} @if (get_setting('flash_deal_banner_menu_text') == 'light') text-white opacity-60 hov-opacity-100 animate-underline-white @else text-reset opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary @endif">{{ translate('View All Products from This Flash Sale') }}</a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Flash Deals Products -->
                        @php
                        $flash_deal_products = get_flash_deal_products($flash_deal->id);
                        @endphp
                        <div class="aiz-carousel border-top @if (count($flash_deal_products) > 8) border-right @endif arrow-inactive-none arrow-x-0"
                            data-items="5" data-xxl-items="5" data-xl-items="3.5" data-lg-items="3"
                            data-md-items="2" data-sm-items="2.5" data-xs-items="2" data-arrows="true"
                            data-dots="false">
                            @php
                            $init = 0;
                            $end = 1;
                            @endphp
                            @foreach ($flash_deal_products as $key => $flash_deal_product)
                            @if ($key >= $init && $key <= $end)
                                @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                @php
                                $product_url = route('product', $flash_deal_product->product->slug);
                                if ($flash_deal_product->product->auction_product == 1) {
                                $product_url = route('auction-product', $flash_deal_product->product->slug);
                                }
                                @endphp
                                <div class="h-100px h-md-200px h-lg-auto flash-deal-item position-relative text-center border-bottom @if ($key < count($flash_deal_products) - 1) border-right @endif has-transition hov-shadow-out z-1">
                                    <a href="{{ $product_url }}"
                                        class="d-block py-md-2 overflow-hidden hov-scale-img"
                                        title="{{ $flash_deal_product->product->getTranslation('name') }}">
                                        <!-- Image -->
                                        <img src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                            class="lazyload h-60px h-md-100px h-lg-120px mw-100 mx-auto has-transition" loading="lazy" decoding="async"
                                            alt="{{ $flash_deal_product->product->getTranslation('name') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        <!-- Price -->
                                        <div class="fs-10 fs-md-14 mt-md-2 text-center h-md-48px has-transition overflow-hidden pt-md-4 flash-deal-price">
                                            <span class="d-block text-primary fw-700">{{ home_discounted_base_price($flash_deal_product->product) }}</span>
                                            @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                            <del class="d-block fw-400 text-secondary">{{ home_base_price($flash_deal_product->product) }}</del>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @endif
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    @elseif(($section['type'] ?? '') == 'reviews')
    <!-- Customer Reviews -->
    @include('frontend.partials.homepage_reviews')

    @else
    <!-- Product Section: todays_deal / featured / best_selling / newest / category -->
    @if($product_count > 0)
    <section class="mb-0 mt-0 home-mobile-product-section home_Section" id="{{ $secId }}">
        <div class="container">
            <div class="modern-section-bordered-wrap">
                <!-- Section Header -->
                <div class="modern-section-header home-section-heading-with-arrows mb-4">
                    <div class="home-section-heading-copy">
                        @php
                        // Dynamic word splitting for heading typography highlights
                        $words = explode(' ', $heading);
                        $first_word = $words[0] ?? '';
                        $remaining_words = implode(' ', array_slice($words, 1));
                        @endphp
                        <h3 class="modern-section-title">
                            {!! $first_word !!} @if(!empty($remaining_words)) <span style="color: #C27325;">{{ $remaining_words }}</span> @endif
                        </h3>
                        @if(!empty($subheading))
                        <div class="modern-section-subtitle">
                            {{ $subheading }}
                        </div>
                        @endif
                    </div>
                    <div class="home-section-arrow-group @if ($product_count <= 4) home-arrows-desktop-disabled @endif @if ($product_count <= 2) home-arrows-mobile-disabled @endif">
                        @if(!empty($view_all_link))
                        <a href="{{ $view_all_link }}" class="modern-view-all-link">{{ translate('View All') }} &rarr;</a>
                        @endif
                        <span class="home-section-arrows-only">
                            <button type="button" class="home-section-arrow is-prev" aria-label="{{ translate('Previous') }}" onclick="homeSectionSlide('prev','{{ $secId }}')">
                                <i class="las la-angle-left"></i>
                            </button>
                            <button type="button" class="home-section-arrow is-next" aria-label="{{ translate('Next') }}" onclick="homeSectionSlide('next','{{ $secId }}')">
                                <i class="las la-angle-right"></i>
                            </button>
                        </span>
                    </div>
                </div>

                <!-- Products slider -->
                <div class="px-sm-3">
                    <div class="aiz-carousel sm-gutters-16 arrow-none home-mobile-product-carousel" data-items="4" data-xxl-items="4" data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows="true" data-dots="false" data-infinite="false" data-autoplay="false">
                        @foreach ($products as $product)
                        <div class="carousel-box px-0 position-relative">
                            @include('frontend.' . $theme . '.partials.product_box_1', ['product' => $product])
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @endif
</div>
@endif
@endif
@endforeach
		<style>
		/* Desktop: original dots position */
.mobile-slider-dots {
    position: relative;
}

/* Mobile: move dots below banner */
@media (max-width: 767px) {

    .mobile-slider-dots {
        padding-bottom: 40px !important;
        overflow: visible !important;
    }

    .mobile-slider-dots .slick-dots {
        position: absolute !important;
        bottom: 10px !important;
        left: 0 !important;
        right: 0 !important;

        width: 100% !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;

        margin: 0 !important;
        padding: 0 !important;
        z-index: 99 !important;
    }
}
		

		@media (max-width: 767px) {

 		.aiz-carousel.dots-inside-bottom .slick-dots li.slick-active button ,.aiz-carousel.dots-inside-bottom .slick-dots li button{
    background: #b2a5a5 !important;
}

}
	</style>
	<script>
document.addEventListener("DOMContentLoaded", function () {

    const section = document.getElementById("wrapper_section_sec_todays_deal");

    if (!section) return;

    function removePadding() {
        section.style.setProperty("padding-top", "0px", "important");
    }

    // Initial
    removePadding();

    // Watch for admin changing inline styles
    const observer = new MutationObserver(function () {
        removePadding();
    });

    observer.observe(section, {
        attributes: true,
        attributeFilter: ["style"]
    });

});
</script>