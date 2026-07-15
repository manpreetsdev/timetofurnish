@extends('frontend.layouts.app')

@if (isset($category_id))
    @php
        $meta_title = $category->meta_title;
        $meta_description = $category->meta_description;
    @endphp
@elseif (isset($brand_id))
    @php
        $meta_title = get_single_brand($brand_id)->meta_title;
        $meta_description = get_single_brand($brand_id)->meta_description;
    @endphp
@else
    @php
        $meta_title = get_setting('meta_title');
        $meta_description = get_setting('meta_description');
    @endphp
@endif

@section('meta_title'){{ $meta_title }}@stop
@section('meta_description'){{ $meta_description }}@stop

@section('meta')
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $meta_title }}">
    <meta itemprop="description" content="{{ $meta_description }}">

    <!-- Twitter Card data -->
    <meta name="twitter:title" content="{{ $meta_title }}">
    <meta name="twitter:description" content="{{ $meta_description }}">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $meta_title }}" />
    <meta property="og:description" content="{{ $meta_description }}" />
@endsection

@section('content')

    <section class="mb-4 pt-4 product-listing-page">
        <div class="container sm-px-0 pt-2">
            <form class="" id="search-form" action="" method="GET">
                <div class="row">

                    <!-- Sidebar Filters -->
                    <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl z-1035 col-xl-3" id="filter-sidebar">
                        <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
                            data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                        <div class="collapse-sidebar c-scrollbar-light text-left">
                            <div class="d-flex justify-content-between align-items-center px-0 py-3 border-bottom">
                                <h5 class="mb-0 fw-700 text-dark">{{ translate('Filters') }}</h5>
                                <button type="button" class="btn btn-sm p-2 text-dark opacity-60 hov-opacity-100 close-filter-btn">
                                    <i class="las la-times la-2x"></i>
                                </button>
                            </div>

                            <div class="py-4">
                                <!-- Categories -->
                                <div class="mb-4 custom-filter-box custom-categories-box">
                                    <div class="fs-16 fw-700 pb-3 mb-3">
                                        <a href="#collapse_1"
                                            class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between text-decoration-none"
                                            data-toggle="collapse">
                                            {{ translate('Categories') }}
                                        </a>
                                    </div>
                                    <div class="collapse show" id="collapse_1">
                                        <ul class="p-0 mb-0 list-unstyled" style="padding:0 !important;">
                                            @if (!isset($category_id))
                                                @foreach ($categories as $category)
                                                    <li class="mb-2">
                                                        <a class="filter-pill-item"
                                                            href="{{ route('products.category', $category->slug) }}">
                                                            <span class="category-name">{{ $category->getTranslation('name') }}</span>
                                                            <span class="category-count-badge">{{ count($category->products) }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="mb-2">
                                                    <a class="filter-pill-item back-pill"
                                                        href="{{ route('search') }}">
                                                        <span><i class="las la-angle-left mr-1"></i> {{ translate('All Categories') }}</span>
                                                    </a>
                                                </li>

                                                @if ($category->parent_id != 0)
                                                    <li class="mb-2">
                                                        <a class="filter-pill-item back-pill"
                                                            href="{{ route('products.category', get_single_category($category->parent_id)->slug) }}">
                                                            <span><i class="las la-angle-left mr-1"></i> {{ get_single_category($category->parent_id)->getTranslation('name') }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="mb-2">
                                                    <a class="filter-pill-item active-pill"
                                                        href="{{ route('products.category', $category->slug) }}">
                                                        <span class="category-name">{{ $category->getTranslation('name') }}</span>
                                                        <span class="category-count-badge">{{ count($category->products) }}</span>
                                                    </a>
                                                </li>
                                                @foreach ($category->childrenCategories as $key => $immediate_children_category)
                                                    <li class="pl-4 mb-2">
                                                        <a class="filter-pill-item"
                                                            href="{{ route('products.category', $immediate_children_category->slug) }}">
                                                            <span class="category-name">{{ $immediate_children_category->getTranslation('name') }}</span>
                                                            <span class="category-count-badge">{{ count($immediate_children_category->products) }}</span>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <!-- Price range -->
                                <div class="mb-4 custom-filter-box custom-price-box">
                                    <div class="fs-16 fw-700 pb-3 mb-3">
                                        <a href="#collapse_price"
                                            class="dropdown-toggle filter-section text-dark d-flex align-items-center justify-content-between text-decoration-none"
                                            data-toggle="collapse">
                                            {{ translate('Price range') }}
                                        </a>
                                    </div>
                                    <div class="collapse show" id="collapse_price">
                                        <div class="pt-2 px-1 pb-3">
                                            @php
                                                $product_count = get_products_count();
                                            @endphp
                                            <div class="aiz-range-slider">
                                                <div id="input-slider-range"
                                                    data-range-value-min="@if ($product_count < 1) 0 @else {{ get_product_min_unit_price() }} @endif"
                                                    data-range-value-max="@if ($product_count < 1) 0 @else {{ get_product_max_unit_price() }} @endif">
                                                </div>

                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <span class="range-slider-value value-low fs-14 fw-600 opacity-70"
                                                            @if (isset($min_price)) data-range-value-low="{{ $min_price }}"
                                                            @elseif(product_collection_min_listing_price($products) > 0)
                                                                data-range-value-low="{{ product_collection_min_listing_price($products) }}"
                                                            @else
                                                                data-range-value-low="0" @endif
                                                            id="input-slider-range-value-low"></span>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <span class="range-slider-value value-high fs-14 fw-600 opacity-70"
                                                            @if (isset($max_price)) data-range-value-high="{{ $max_price }}"
                                                            @elseif(product_collection_max_listing_price($products) > 0)
                                                                data-range-value-high="{{ product_collection_max_listing_price($products) }}"
                                                            @else
                                                                data-range-value-high="0" @endif
                                                            id="input-slider-range-value-high"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Hidden Items -->
                                    <input type="hidden" name="min_price" value="">
                                    <input type="hidden" name="max_price" value="">
                                </div>

                                <!-- Color -->
                                @if (get_setting('color_filter_activation'))
                                    <div class="mb-4 custom-filter-box custom-color-box">
                                        <div class="fs-16 fw-700 pb-3 mb-3">
                                            <a href="#collapse_color"
                                                class="dropdown-toggle text-dark filter-section collapsed d-flex align-items-center justify-content-between text-decoration-none"
                                                data-toggle="collapse">
                                                {{ translate('Filter by color') }}
                                            </a>
                                        </div>
                                        @php
                                            $show = '';
                                            foreach ($colors as $key => $color) {
                                                if (isset($selected_color) && $selected_color == $color->code) {
                                                    $show = 'show';
                                                }
                                            }
                                        @endphp
                                        <div class="collapse {{ $show }}" id="collapse_color">
                                            <div class="pt-2 aiz-radio-inline">
                                                @foreach ($colors as $key => $color)
                                                    <label class="aiz-megabox pl-0 mr-2" data-toggle="tooltip"
                                                        data-title="{{ $color->name }}">
                                                        <input type="radio" name="color" value="{{ $color->code }}"
                                                            onchange="filter()"
                                                            @if (isset($selected_color) && $selected_color == $color->code) checked @endif>
                                                        <span
                                                            class="aiz-megabox-elem rounded d-flex align-items-center justify-content-center p-1 mb-2">
                                                            <span class="size-30px d-inline-block rounded-circle"
                                                                style="background: {{ $color->code }}; box-shadow: inset 0 0 0 1px rgba(0,0,0,0.15);"></span>
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contents -->
                    <div class="col-xl-12 col-12" id="products-column">

                        <!-- Breadcrumb -->
                        <ul class="breadcrumb bg-transparent py-0 px-1">
                            <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                                <a class="text-reset" href="{{ route('home') }}">{{ translate('Home') }}</a>
                            </li>
                            @if (!isset($category_id))
                                <li class="breadcrumb-item fw-700  text-dark">
                                    "{{ translate('All Categories') }}"
                                </li>
                            @else
                                <li class="breadcrumb-item opacity-50 hov-opacity-100">
                                    <a class="text-reset"
                                        href="{{ route('search') }}">{{ translate('All Categories') }}</a>
                                </li>
                            @endif
                            @if (isset($category_id))
                                <li class="text-dark fw-600 breadcrumb-item">
                                    "{{ $category->getTranslation('name') }}"
                                </li>
                            @endif
                        </ul>

                        <!-- Top Filters -->
                        <div class="text-left mb-4">
                            <div class="row gutters-10 flex-wrap align-items-center justify-content-between">
                                <div class="col-md-6 col-12 mb-3 mb-md-0">
                                    <h1 class="fs-20 fs-md-24 fw-700 text-dark mb-0">
                                        @if (isset($category_id))
                                            {{ $category->getTranslation('name') }}
                                        @elseif(isset($query))
                                            {{ translate('Search result for ') }}"{{ $query }}"
                                        @else
                                            {{ translate('All Products') }}
                                        @endif
                                    </h1>
                                    <input type="hidden" name="keyword" value="{{ $query }}">
                                </div>
                                <div class="col-md-6 col-12 d-flex justify-content-between justify-content-md-end align-items-center">
                                    <button type="button" class="btn btn-filter mr-3">
                                        <i class="las la-sliders-h"></i>
                                        <span>{{ translate('Filters') }}</span>
                                    </button>
                                    <div class="w-md-200px w-170px">
                                        <select class="form-control form-control-sm aiz-selectpicker rounded-0" name="sort_by"
                                            onchange="filter()">
                                            <option value="">{{ translate('Sort by') }}</option>
                                            <option value="newest"
                                                @isset($sort_by) @if ($sort_by == 'newest') selected @endif @endisset>
                                                {{ translate('Newest') }}</option>
                                            <option value="oldest"
                                                @isset($sort_by) @if ($sort_by == 'oldest') selected @endif @endisset>
                                                {{ translate('Oldest') }}</option>
                                            <option value="price-asc"
                                                @isset($sort_by) @if ($sort_by == 'price-asc') selected @endif @endisset>
                                                {{ translate('Price low to high') }}</option>
                                            <option value="price-desc"
                                                @isset($sort_by) @if ($sort_by == 'price-desc') selected @endif @endisset>
                                                {{ translate('Price high to low') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products -->
                        <div class="px-3 product-listing-grid-wrap">
                            <div
                                class="row gutters-16 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-4 row-cols-md-3 row-cols-2 product-listing-grid">
                                @foreach ($products as $key => $product)
                                    <div class="col mb-4 pl-0 pr-1 d-flex align-items-stretch">
                                        @include(
                                            'frontend.' .
                                                get_setting('homepage_select') .
                                                '.partials.product_box_1',
                                            ['product' => $product]
                                        )
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="aiz-pagination mt-4">
                            {{ $products->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
    <script type="text/javascript">
        function filter() {
            $('#search-form').submit();
        }

        function rangefilter(arg) {
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
