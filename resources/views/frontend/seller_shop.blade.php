@extends('frontend.layouts.app')

@php
    $seoTitle = seo_title($shop->meta_title, $shop->name . ' | ' . get_setting('website_name'));
    $seoDescription = seo_description($shop->meta_description, translate('Shop products and deals'));
@endphp

@section('meta_title'){{ $seoTitle }}@stop

@section('meta_description'){{ $seoDescription }}@stop

@section('canonical_url'){{ route('shop.visit', $shop->slug) }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $shop->meta_title }}">
    <meta itemprop="description" content="{{ $shop->meta_description }}">
    <meta itemprop="image" content="{{ uploaded_asset($shop->logo) }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="website">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $shop->meta_title }}">
    <meta name="twitter:description" content="{{ $shop->meta_description }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ uploaded_asset($shop->meta_img) }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $shop->meta_title }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ route('shop.visit', $shop->slug) }}" />
    <meta property="og:image" content="{{ uploaded_asset($shop->logo) }}" />
    <meta property="og:description" content="{{ $shop->meta_description }}" />
    <meta property="og:site_name" content="{{ $shop->name }}" />
@endsection

@section('content')
    <section class="mt-3 mb-3 bg-white">
        <div class="container">
            <!-- Top Menu -->
            <div class="d-flex flex-wrap justify-content-center justify-content-md-start">
                <a class="fw-700 fs-11 fs-md-13 mr-3 mr-sm-4 mr-md-5 text-dark opacity-60 hov-opacity-100 @if(!isset($type)) opacity-100 @endif"
                        href="{{ route('shop.visit', $shop->slug) }}">{{ translate('Store Home')}}</a>
                <a class="fw-700 fs-11 fs-md-13 mr-3 mr-sm-4 mr-md-5 text-dark opacity-60 hov-opacity-100 @if(isset($type) && $type == 'top-selling') opacity-100 @endif"
                        href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'top-selling']) }}">{{ translate('Top Selling')}}</a>
                <a class="fw-700 fs-11 fs-md-13 text-dark opacity-60 hov-opacity-100 @if(isset($type) && $type == 'all-products') opacity-100 @endif"
                        href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'all-products']) }}">{{ translate('All Products')}}</a>
            </div>
        </div>
    </section>

    @php
        $followed_sellers = [];
        if (Auth::check()) {
            $followed_sellers = get_followed_sellers();
        }
    @endphp

    <section class="@if (!isset($type) || $type == 'top-selling' || $type == 'cupons') mb-3 @endif border-top border-bottom" style="background: #fcfcfd;">
        <div class="container">
            <!-- Seller Info -->
            <div class="py-4">
                <div class="row justify-content-md-between align-items-center">
                    <div class="col-lg-5 col-md-6">
                        <div class="d-flex align-items-center">
                            <!-- Shop Logo -->
                            <a href="{{ route('shop.visit', $shop->slug) }}" class="overflow-hidden size-64px rounded-content" style="border: 1px solid #e5e5e5;
                                box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                <img class="lazyload h-64px mx-auto seller_img"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ uploaded_asset($shop->logo) }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>
                            <div class="ml-3">
                                <!-- Shop Name & Verification Status -->
                                <h1 class="fs-18 fs-md-22 fw-700 m-0 p-0 text-dark" style="display: inline-block;">
                                    <a href="{{ route('shop.visit', $shop->slug) }}" class="text-dark">
                                        {{ \App\Models\User::where('id', $shop->user_id)->value('name') ?? $shop->name }}
                                    </a>
                                </h1>
                                @if ($shop->verification_status == 1)
                                    <span class="ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                            <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                                <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="#3490f3"/>
                                            </g>
                                        </svg>
                                    </span>
                                @else
                                    <span class="ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="17.5" height="17.5" viewBox="0 0 17.5 17.5">
                                            <g id="Group_25616" data-name="Group 25616" transform="translate(-537.249 -1042.75)">
                                                <path id="Union_5" data-name="Union 5" d="M0,8.75A8.75,8.75,0,1,1,8.75,17.5,8.75,8.75,0,0,1,0,8.75Zm.876,0A7.875,7.875,0,1,0,8.75.875,7.883,7.883,0,0,0,.876,8.75Zm.875,0a7,7,0,1,1,7,7A7.008,7.008,0,0,1,1.751,8.751Zm3.73-.907a.789.789,0,0,0,0,1.115l2.23,2.23a.788.788,0,0,0,1.115,0l3.717-3.717a.789.789,0,0,0,0-1.115.788.788,0,0,0-1.115,0l-3.16,3.16L6.6,7.844a.788.788,0,0,0-1.115,0Z" transform="translate(537.249 1042.75)" fill="red"/>
                                            </g>
                                        </svg>
                                    </span>
                                @endif
                                </a>
                                <!-- Rating -->
                                <div class="rating rating-mr-1 text-dark">
                                    {{ renderStarRating($shop->rating) }}
                                    <span class="opacity-60 fs-12">({{ $shop->num_of_reviews }}
                                        {{ translate('Reviews') }})</span>
                                </div>
                                <!-- Address -->
                                <div class="location fs-12 opacity-70 text-dark mt-1">{{ $shop->address }}, {{($shop->city)? $shop->city->name: '' }}-{{($shop->postal_code)? $shop->postal_code: '' }}, {{($shop->state)? $shop->state->name: '' }}, {{($shop->country)? $shop->country->name: '' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col pl-5 pl-md-0 ml-5 ml-md-0">
                        <div class="d-lg-flex align-items-center justify-content-lg-end">
                            <div class="d-md-flex justify-content-md-end align-items-md-baseline">
                                <!-- Member Since -->
                                <div class="pr-md-3 mt-2 mt-md-0 border-md-right">
                                    <div class="fs-10 fw-400 text-secondary">{{ translate('Member Since') }}</div>
                                    <div class="mt-1 fs-16 fw-700 text-secondary">{{ date('d M Y',strtotime($shop->created_at)) }}</div>
                                </div>
                            </div>
                            <!-- follow -->
                            <div class="d-flex justify-content-md-end pl-lg-3 pt-3 pt-lg-0">
                                @if(in_array($shop->id, $followed_sellers))
                                    <a href="{{ route("followed_seller.remove", ['id'=>$shop->id]) }}" data-toggle="tooltip" data-title="{{ translate('Unfollow Seller') }}" data-placement="top"
                                        class="btn btn-success d-flex align-items-center justify-content-center fs-12 w-190px follow-btn followed"
                                        style="height: 40px; border-radius: 30px !important; justify-content: center;">
                                        <i class="las la-check fs-16 mr-2"></i>
                                        <span class="fw-700">{{ translate('Followed') }}</span> &nbsp; ({{ count($shop->followers) }})
                                    </a>
                                @else
                                    <a href="{{ Auth::check() ? route("followed_seller.store", ['id'=>$shop->id]) : route('user.login') }}"
                                        class="btn borderbtn d-flex align-items-center justify-content-center fs-12 w-190px "
                                        style="height: 40px; border-radius: 30px !important; justify-content: center;">
                                        <i class="las la-plus fs-16 mr-2"></i>
                                        <span class="fw-700">{{ translate('Follow Seller') }}</span> &nbsp; ({{ count($shop->followers) }})
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .aiz-carousel .slick-list {
            padding-top: 6px !important;
            padding-bottom: 14px !important;
            margin-top: -6px !important;
            margin-bottom: -14px !important;
        }

        .aiz-carousel .slick-slide,
        .aiz-carousel .slick-slide:focus,
        .aiz-carousel .slick-slide *:focus,
        .carousel-box:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>

    <section class="mb-4 pt-4 product-listing-page">
        <div class="container sm-px-0 pt-2">
            @if (!isset($type))
                @php
                    $feature_products = \App\Models\Product::where('user_id', $shop->user->id)->where('published', 1)->where('approved', 1)->where('seller_featured', 1)->with(['thumbnail', 'stocks', 'taxes'])->latest()->get();
                @endphp
                @if (count($feature_products) > 0)
                    <!-- Featured Products -->
                    <div class="mb-4" id="section_featured">
                        <!-- Top Section -->
                        <div class="d-flex mb-3 align-items-baseline justify-content-between">
                            <!-- Title -->
                            @php
                                $heading_featured = translate('Featured Products');
                                $words_featured = explode(' ', $heading_featured);
                                $first_word_featured = $words_featured[0] ?? '';
                                $remaining_words_featured = implode(' ', array_slice($words_featured, 1));
                            @endphp
                            <h3 class="modern-section-title mb-0">
                                {!! $first_word_featured !!} @if(!empty($remaining_words_featured)) <span style="color: #C27325;">{{ $remaining_words_featured }}</span> @endif
                            </h3>
                            <!-- Links -->
                            <div class="d-flex">
                                <a type="button" class="arrow-prev slide-arrow text-secondary mr-2" onclick="clickToSlide('slick-prev','section_featured')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                                <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_featured')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                            </div>
                        </div>
                        <!-- Products Section -->
                        <div class="px-sm-3 product-listing-grid-wrap">
                            <div class="aiz-carousel sm-gutters-16 arrow-none home-mobile-product-carousel" data-items="4" data-xxl-items="4" data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2" data-xs-items="2" data-arrows="true" data-dots="false" data-infinite="false" data-autoplay="false">
                                @foreach ($feature_products as $key => $product)
                                <div class="carousel-box position-relative">
                                    @include('frontend.' . (get_setting('homepage_select') ?: 'metro') . '.partials.product_box_1', ['product' => $product])
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if (!empty($shop->sliders))
                <!-- Banner Slider -->
                <div class="mt-3 mb-4">
                    <div class="aiz-carousel mobile-img-auto-height" data-arrows="true" data-dots="false" data-autoplay="true">
                        @foreach (explode(',',$shop->sliders) as $key => $slide)
                            <div class="carousel-box w-100 h-140px h-md-300px h-xl-450px">
                                <img class="d-block lazyload h-100 img-fit" src="{{ static_asset('assets/img/placeholder-rect.jpg') }}" data-src="{{ uploaded_asset($slide) }}" alt="{{ $key }} offer">
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Coupons -->
                @php
                    $coupons = get_coupons($shop->user->id);
                @endphp
                @if (count($coupons)>0)
                    <div class="mb-4" id="section_coupons">
                        <!-- Top Section -->
                        <div class="d-flex mb-3 align-items-baseline justify-content-between">
                            <!-- Title -->
                            <h3 class="modern-section-title mb-0">
                                {{ translate('Coupons') }}
                            </h3>
                            <!-- Links -->
                            <div class="d-flex align-items-center">
                                <a class="text-blue fs-12 fw-700 hov-text-primary mr-3" href="{{ route('shop.visit.type', ['slug'=>$shop->slug, 'type'=>'cupons']) }}">{{ translate('View All') }} &rarr;</a>
                                <a type="button" class="arrow-prev slide-arrow text-secondary mr-2" onclick="clickToSlide('slick-prev','section_coupons')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                                <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_coupons')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                            </div>
                        </div>
                        <!-- Coupons Section -->
                        <div class="px-sm-3 product-listing-grid-wrap">
                            <div class="aiz-carousel sm-gutters-16 arrow-none home-mobile-product-carousel" data-items="3" data-lg-items="2" data-sm-items="1" data-arrows="true" data-dots="false" data-infinite="false">
                                @foreach ($coupons->take(10) as $key => $coupon)
                                    <div class="carousel-box position-relative">
                                        @include('frontend.'.get_setting('homepage_select').'.partials.coupon_box',['coupon' => $coupon])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if ($shop->banner_full_width_1)
                    <!-- Banner full width 1 -->
                    @foreach (explode(',',$shop->banner_full_width_1) as $key => $banner)
                        <div class="mb-3 mt-3 w-100">
                            <img class="d-block lazyload h-100 img-fit"
                                src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($banner) }}" alt="{{ env('APP_NAME') }} offer">
                        </div>
                    @endforeach
                @endif

                @if($shop->banners_half_width)
                    <!-- Banner half width -->
                    <div class="row gutters-16 mb-3 mt-3">
                        @foreach (explode(',',$shop->banners_half_width) as $key => $banner)
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="w-100">
                                <img class="d-block lazyload h-100 img-fit"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($banner) }}" alt="{{ env('APP_NAME') }} offer">
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

            @endif

            <div class="mb-4" id="section_types">
                <!-- Top Section -->
                <div class="d-flex mb-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    @php
                        $heading_type = '';
                        if (!isset($type)){
                            $heading_type = translate('New Arrival Products');
                        } elseif ($type == 'top-selling'){
                            $heading_type = translate('Top Selling');
                        } elseif ($type == 'cupons'){
                            $heading_type = translate('All Coupons');
                        }
                        $words_type = explode(' ', $heading_type);
                        $first_word_type = $words_type[0] ?? '';
                        $remaining_words_type = implode(' ', array_slice($words_type, 1));
                    @endphp
                    <h3 class="modern-section-title mb-0">
                        {!! $first_word_type !!} @if(!empty($remaining_words_type)) <span style="color: #C27325;">{{ $remaining_words_type }}</span> @endif
                    </h3>
                </div>

                @php
                    if (!isset($type)){
                        $products = get_seller_products($shop->user->id);
                    }
                    elseif ($type == 'top-selling'){
                        $products = get_shop_best_selling_products($shop->user->id);
                    }
                    elseif ($type == 'cupons'){
                        $coupons = get_coupons($shop->user->id , 24);
                    }
                @endphp

                @if (!isset($type))
                    <!-- New Arrival Products Section -->
                    @if(count($products) > 0)
                    <div class="px-3 product-listing-grid-wrap">
                        <div class="row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 product-listing-grid">
                            @foreach ($products as $key => $product)
                                <div class="col mb-4 pl-0 pr-1 d-flex align-items-stretch">
                                    @include('frontend.' . (get_setting('homepage_select') ?: 'metro') . '.partials.product_box_1', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="aiz-pagination mt-4 mb-4">
                        {{ $products->links() }}
                    </div>
                    @endif

                    @if ($shop->banner_full_width_2)
                        <!-- Banner full width 2 -->
                        @foreach (explode(',',$shop->banner_full_width_2) as $key => $banner)
                            <div class="mt-3 mb-3 w-100">
                                <img class="d-block lazyload h-100 img-fit"
                                    src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($banner) }}" alt="{{ env('APP_NAME') }} offer">
                            </div>
                        @endforeach
                    @endif

                @elseif ($type == 'cupons')
                    <!-- All Coupons Section -->
                    <div class="row gutters-16 row-cols-xl-3 row-cols-md-2 row-cols-1">
                        @foreach ($coupons as $key => $coupon)
                            <div class="col mb-4">
                                @include('frontend.'.get_setting('homepage_select').'.partials.coupon_box',['coupon' => $coupon])
                            </div>
                        @endforeach
                    </div>
                    <div class="aiz-pagination mt-4 mb-4">
                        {{ $coupons->links() }}
                    </div>

                @elseif ($type == 'all-products')
                    <!-- All Products Section -->
                    <form class="" id="search-form" action="" method="GET">
                        <div class="row gutters-16 justify-content-center">
                            <!-- Sidebar -->
                            <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl z-1035 col-xl-3" id="filter-sidebar">
                                <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                                <div class="collapse-sidebar c-scrollbar-light text-left">
                                    <div class="d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                                        <h5 class="mb-0 fw-700 text-dark">{{ translate('Filters') }}</h5>
                                        <button type="button" class="btn btn-sm p-2 text-dark opacity-60 hov-opacity-100 close-filter-btn">
                                            <i class="las la-times la-2x"></i>
                                        </button>
                                    </div>

                                    <div class="py-1">
                                        <!-- Categories -->
                                        <div class="mb-4 custom-filter-box custom-categories-box">
                                            <div class="fs-16 fw-700 pb-3 mb-3">
                                                <a href="#collapse_1" class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between text-decoration-none" data-toggle="collapse">
                                                    {{ translate('Categories')}}
                                                </a>
                                            </div>
                                            <div class="collapse show" id="collapse_1">
                                                <ul class="p-0 mb-0 list-unstyled" style="padding:0 !important;">
                                                    @foreach (get_categories_by_products($shop->user->id) as $category)
                                                    <li class="mb-2">
                                                        <label class="aiz-checkbox mb-0 w-100">
                                                            <input
                                                                type="checkbox"
                                                                name="selected_categories[]"
                                                                value="{{ $category->id }}" @if (in_array($category->id, $selected_categories)) checked @endif
                                                                onchange="filter()"
                                                            >
                                                            <span class="aiz-square-check"></span>
                                                            <span class="fs-14 fw-400 text-dark">{{ $category->getTranslation('name') }}</span>
                                                        </label>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Price range -->
                                        <div class="mb-4 custom-filter-box custom-price-box">
                                            <div class="fs-16 fw-700 pb-3 mb-3">
                                                <a href="#collapse_price" class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between text-decoration-none" data-toggle="collapse">
                                                    {{ translate('Price range')}}
                                                </a>
                                            </div>
                                            <div class="collapse show" id="collapse_price">
                                                <div class="pt-2 px-1 pb-3" style="padding: 0px 8px !important;">
                                                    <div class="aiz-range-slider">
                                                        <div
                                                            id="input-slider-range"
                                                            data-range-value-min="@if(get_products_count($shop->user->id) < 1) 0 @else {{ get_product_min_unit_price($shop->user->id) }} @endif"
                                                            data-range-value-max="@if(get_products_count($shop->user->id) < 1) 0 @else {{ get_product_max_unit_price($shop->user->id) }} @endif"
                                                        ></div>

                                                        <div class="row mt-3">
                                                            <div class="col-6">
                                                                <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                                    @if ($min_price != null)
                                                                        data-range-value-low="{{ $min_price }}"
                                                                    @elseif(product_collection_min_listing_price($products) > 0)
                                                                        data-range-value-low="{{ product_collection_min_listing_price($products) }}"
                                                                    @else
                                                                        data-range-value-low="0"
                                                                    @endif
                                                                    id="input-slider-range-value-low"
                                                                ></span>
                                                            </div>
                                                            <div class="col-6 text-right">
                                                                <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                                    @if ($max_price != null)
                                                                        data-range-value-high="{{ $max_price }}"
                                                                    @elseif(product_collection_max_listing_price($products) > 0)
                                                                        data-range-value-high="{{ product_collection_max_listing_price($products) }}"
                                                                    @else
                                                                        data-range-value-high="0"
                                                                    @endif
                                                                    id="input-slider-range-value-high"
                                                                ></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Hidden Items -->
                                            <input type="hidden" name="min_price" value="">
                                            <input type="hidden" name="max_price" value="">
                                        </div>

                                        <!-- Ratings -->
                                        <div class="mb-4 custom-filter-box custom-ratings-box">
                                            <div class="fs-16 fw-700 pb-3 mb-3">
                                                <a href="#collapse_2" class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between text-decoration-none" data-toggle="collapse">
                                                    {{ translate('Ratings')}}
                                                </a>
                                            </div>
                                            <div class="collapse show" id="collapse_2">
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="5" @if ($rating==5) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="rating rating-mr-1">{{ renderStarRating(5) }}</span>
                                                </label>
                                                <br>
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="4" @if ($rating==4) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="rating rating-mr-1">{{ renderStarRating(4) }}</span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('And Up')}}</span>
                                                </label>
                                                <br>
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="3" @if ($rating==3) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="rating rating-mr-1">{{ renderStarRating(3) }}</span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('And Up')}}</span>
                                                </label>
                                                <br>
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="2" @if ($rating==2) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="rating rating-mr-1">{{ renderStarRating(2) }}</span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('And Up')}}</span>
                                                </label>
                                                <br>
                                                <label class="aiz-checkbox mb-3">
                                                    <input
                                                        type="radio"
                                                        name="rating"
                                                        value="1" @if ($rating==1) checked @endif
                                                        onchange="filter()"
                                                    >
                                                    <span class="aiz-square-check"></span>
                                                    <span class="rating rating-mr-1">{{ renderStarRating(1) }}</span>
                                                    <span class="fs-14 fw-400 text-dark">{{ translate('And Up')}}</span>
                                                </label>
                                                <br>
                                            </div>
                                        </div>

                                        <!-- Brands -->
                                        <div class="mb-4 custom-filter-box custom-brands-box">
                                            <div class="fs-16 fw-700 pb-3 mb-3">
                                                <a href="#collapse_3" class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between text-decoration-none" data-toggle="collapse">
                                                    {{ translate('Brands')}}
                                                </a>
                                            </div>
                                            <div class="collapse show" id="collapse_3">
                                                <div class="row gutters-10">
                                                    @foreach (get_brands_by_products($shop->user->id) as $key => $brand)
                                                        <div class="col-6">
                                                            <label class="aiz-megabox d-block mb-3">
                                                                <input value="{{ $brand->slug }}" type="radio" onchange="filter()"
                                                                    name="brand" @isset($brand_id) @if ($brand_id == $brand->id) checked @endif @endisset>
                                                                <span class="d-block aiz-megabox-elem rounded-0 p-3 border-transparent hov-border-primary">
                                                                    <img src="{{ uploaded_asset($brand->logo) }}"
                                                                        class="img-fit mb-2" alt="{{ $brand->getTranslation('name') }}">
                                                                    <span class="d-block text-center">
                                                                        <span
                                                                            class="d-block fw-400 fs-14">{{ $brand->getTranslation('name') }}</span>
                                                                    </span>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- Contents -->
                            <div class="col-xl-12 col-12" id="products-column">
                                <!-- Top Filters -->
                                <div class="text-left mb-4">
                                    <div class="row gutters-10 flex-wrap align-items-center justify-content-between">
                                        <div class="col-md-6 col-12 mb-3 mb-md-0">
                                            @php
                                                $heading_all = translate('All Products');
                                                $words_all = explode(' ', $heading_all);
                                                $first_word_all = $words_all[0] ?? '';
                                                $remaining_words_all = implode(' ', array_slice($words_all, 1));
                                            @endphp
                                            <h1 class="modern-section-title mb-0">
                                                {!! $first_word_all !!} @if(!empty($remaining_words_all)) <span style="color: #C27325;">{{ $remaining_words_all }}</span> @endif
                                            </h1>
                                        </div>

                                        @if (count($products) > 0)
                                        <div class="col-md-6 col-12 d-flex justify-content-between justify-content-md-end align-items-center">
                                            <button type="button" class="btn btn-filter mr-3">
                                                <i class="las la-sliders-h"></i>
                                                <span>{{ translate('Filters') }}</span>
                                            </button>
                                            <div class="w-md-200px w-170px sort-by-wrapper">
                                                <select class="form-control custom-sort-select" name="sort_by" onchange="filter()">
                                                    <option value="">{{ translate('Sort by')}}</option>
                                                    <option value="newest" @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>{{ translate('Newest')}}</option>
                                                    <option value="oldest" @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>{{ translate('Oldest')}}</option>
                                                    <option value="price-asc" @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>{{ translate('Price low to high')}}</option>
                                                    <option value="price-desc" @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>{{ translate('Price high to low')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Products -->
                                @if(count($products) > 0)
                                <div class="px-3 product-listing-grid-wrap">
                                    <div class="row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 product-listing-grid">
                                        @foreach ($products as $key => $product)
                                            <div class="col mb-4 pl-0 pr-1 d-flex align-items-stretch">
                                                @include('frontend.' . (get_setting('homepage_select') ?: 'metro') . '.partials.product_box_1', ['product' => $product])
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="aiz-pagination mt-4">
                                    {{ $products->appends(request()->input())->links() }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </form>
                @else
                    <!-- Top Selling Products Section -->
                    @if(count($products) > 0)
                    <div class="px-3 product-listing-grid-wrap">
                        <div class="row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 product-listing-grid">
                            @foreach ($products as $key => $product)
                                <div class="col mb-4 pl-0 pr-1 d-flex align-items-stretch">
                                    @include('frontend.' . (get_setting('homepage_select') ?: 'metro') . '.partials.product_box_1', ['product' => $product])
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="aiz-pagination mt-4 mb-4">
                        {{ $products->links() }}
                    </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter(){
            $('#search-form').submit();
        }

        function rangefilter(arg){
            $('input[name=min_price]').val(arg[0]);
            $('input[name=max_price]').val(arg[1]);
            filter();
        }

        function toggleFilters() {
            if (window.innerWidth >= 1200) {
                var sidebar = document.getElementById('filter-sidebar');
                var productsCol = document.getElementById('products-column');
                if (sidebar && productsCol) {
                    if (!sidebar.classList.contains('show-desktop')) {
                        sidebar.classList.add('show-desktop');
                        productsCol.classList.remove('col-xl-12');
                        productsCol.classList.add('col-xl-9');
                        localStorage.setItem('filters_open', 'true');
                    } else {
                        sidebar.classList.remove('show-desktop');
                        productsCol.classList.remove('col-xl-9');
                        productsCol.classList.add('col-xl-12');
                        localStorage.setItem('filters_open', 'false');
                    }
                }
            } else {
                var sidebar = document.querySelector('.aiz-filter-sidebar');
                if (sidebar) {
                    sidebar.classList.toggle('active');
                }
            }
        }

        // Apply saved state on page load and bind handlers
        $(document).ready(function() {
            if (window.innerWidth >= 1200) {
                var filtersOpen = localStorage.getItem('filters_open');
                var sidebar = document.getElementById('filter-sidebar');
                var productsCol = document.getElementById('products-column');
                if (sidebar && productsCol) {
                    if (filtersOpen === 'true') {
                        sidebar.classList.add('show-desktop');
                        productsCol.classList.remove('col-xl-12');
                        productsCol.classList.add('col-xl-9');
                    } else {
                        sidebar.classList.remove('show-desktop');
                        productsCol.classList.remove('col-xl-9');
                        productsCol.classList.add('col-xl-12');
                    }
                }
            }

            // Bind Filters toggle buttons using Event Delegation
            $(document).on('click', '.btn-filter, .close-filter-btn', function(e) {
                e.preventDefault();
                toggleFilters();
            });
        });
    </script>
@endsection
