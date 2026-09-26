@extends('frontend.layouts.app')

@php
    $productName = ucfirst($detailedProduct->getTranslation('name'));
    $categoryName = $detailedProduct->category ? $detailedProduct->category->getTranslation('name') : translate('Furniture');
    $brandName = $detailedProduct->brand ? $detailedProduct->brand->name : get_setting('website_name');
    $priceText = single_price(home_discounted_base_price($detailedProduct, false));

    // Dynamic unique SEO title fallback
    $titleFallback = $productName;
    if ($detailedProduct->category) {
        $titleFallback .= ' - ' . $categoryName;
    }
    $titleFallback .= ' | ' . get_setting('website_name');

    $seoTitle = seo_title($detailedProduct->meta_title, $titleFallback);

    // Dynamic unique SEO description fallback (avoids duplicate site meta descriptions)
    $descFallback = "Shop " . $productName . " in " . $categoryName . " from " . $brandName . " at " . get_setting('website_name') . ". High-quality furniture, competitive price (" . $priceText . "), and fast UK delivery.";

    $seoDescription = seo_description(
        $detailedProduct->meta_description ?: $detailedProduct->getTranslation('description'),
        $descFallback
    );
@endphp

@section('meta_title'){{ $seoTitle }}@stop

@section('meta_description'){{ $seoDescription }}@stop

@section('canonical_url'){{ route('product', $detailedProduct->slug) }}@stop

@section('meta_keywords'){{ product_meta_keywords($detailedProduct) }}@stop

@section('meta')
    @php
        $availability = 'out of stock';
        $qty = 0;
        $merchantImageId = $detailedProduct->thumbnail_img;
        if (empty($merchantImageId) && !empty($detailedProduct->photos)) {
            $merchantImageId = collect(explode(',', $detailedProduct->photos))->filter()->first();
        }
        if (empty($merchantImageId)) {
            $merchantImageId = optional($detailedProduct->stocks->firstWhere('image', '!=', null))->image;
        }
        if (empty($merchantImageId)) {
            $merchantImageId = $detailedProduct->meta_img;
        }
        $merchantImage = uploaded_asset($merchantImageId);
        $merchantPrice = number_format((float) home_discounted_base_price($detailedProduct, false), 2, '.', '');
        $merchantCurrency = get_system_default_currency()->code;
        if ($detailedProduct->variant_product) {
            foreach ($detailedProduct->stocks as $key => $stock) {
                $qty += $stock->qty;
            }
        } else {
            $qty = optional($detailedProduct->stocks->first())->qty;
        }
        if ($qty > 0) {
            $availability = 'in stock';
        }

        // Collect all unique gallery image URLs for schema
        $allProductImages = [];
        if (!empty($merchantImage)) {
            $allProductImages[] = $merchantImage;
        }
        if (!empty($detailedProduct->photos)) {
            foreach (explode(',', $detailedProduct->photos) as $photoId) {
                if (!empty(trim($photoId))) {
                    $url = uploaded_asset(trim($photoId));
                    if ($url && !in_array($url, $allProductImages)) {
                        $allProductImages[] = $url;
                    }
                }
            }
        }

        // Ratings / Reviews data for rich schema
        $reviewCount = $detailedProduct->reviews ? $detailedProduct->reviews->count() : 0;
        $ratingVal = $detailedProduct->rating > 0 ? (float) $detailedProduct->rating : 0;

        $schemaPayload = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $seoTitle,
            'description' => $seoDescription,
            'image' => $allProductImages,
            'sku' => $detailedProduct->slug,
            'mpn' => $detailedProduct->slug,
            'category' => $categoryName,
            'brand' => [
                '@type' => 'Brand',
                'name' => $brandName,
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('product', $detailedProduct->slug),
                'priceCurrency' => $merchantCurrency,
                'price' => $merchantPrice,
                'availability' => $availability === 'in stock' ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'itemCondition' => 'https://schema.org/NewCondition',
                'seller' => [
                    '@type' => 'Organization',
                    'name' => get_setting('website_name'),
                ],
            ],
        ];

        if ($reviewCount > 0 && $ratingVal > 0) {
            $schemaPayload['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => number_format($ratingVal, 1, '.', ''),
                'reviewCount' => (string) $reviewCount,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_filter([
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => translate('Home'),
                    'item' => route('home'),
                ],
                $detailedProduct->category ? [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $categoryName,
                    'item' => route('products.category', $detailedProduct->category->slug),
                ] : null,
                [
                    '@type' => 'ListItem',
                    'position' => $detailedProduct->category ? 3 : 2,
                    'name' => $productName,
                    'item' => route('product', $detailedProduct->slug),
                ],
            ])),
        ];
    @endphp
    <!-- Schema.org markup for Google+ -->
    <meta itemprop="name" content="{{ $seoTitle }}">
    <meta itemprop="description" content="{{ $seoDescription }}">
    <meta itemprop="image" content="{{ $merchantImage }}">

    <!-- Twitter Card data -->
    <meta name="twitter:card" content="product">
    <meta name="twitter:site" content="@publisher_handle">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    <meta name="twitter:creator" content="@author_handle">
    <meta name="twitter:image" content="{{ $merchantImage }}">
    <meta name="twitter:data1" content="{{ single_price($merchantPrice) }}">
    <meta name="twitter:label1" content="Price">

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $seoTitle }}" />
    <meta property="og:type" content="og:product" />
    <meta property="og:url" content="{{ route('product', $detailedProduct->slug) }}" />
    <meta property="og:image" content="{{ $merchantImage }}" />
    <meta property="og:description" content="{{ $seoDescription }}" />
    <meta property="og:site_name" content="{{ get_setting('meta_title') }}" />
    <meta property="og:price:amount" content="{{ $merchantPrice }}" />
    <meta property="product:brand" content="{{ $brandName }}">
    <meta property="product:availability" content="{{ $availability }}">
    <meta property="product:condition" content="new">
    <meta property="product:price:amount" content="{{ $merchantPrice }}">
    <meta property="product:retailer_item_id" content="{{ $detailedProduct->slug }}">
    <meta property="product:price:currency" content="{{ $merchantCurrency }}" />
    <meta property="fb:app_id" content="{{ env('FACEBOOK_PIXEL_ID') }}">
    <script type="application/ld+json">
        {!! json_encode($schemaPayload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
@endsection

@section('content')
<style>
    /* ── Responsive Breadcrumbs Row ── */
    .responsive-breadcrumb-row {
        gap: 0.5rem;
        position: relative;
        align-items: center !important;
    }
    
    .responsive-breadcrumb-nav {
        min-width: 0;
        flex: 1 1 auto;
    }

    .responsive-breadcrumb-row .breadcrumb {
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
        margin-bottom: 0;
        padding-bottom: 0;
        background: transparent;
        line-height: 1.2;
    }

    .responsive-breadcrumb-row .breadcrumb-item {
        font-size: 13px;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }
    .responsive-breadcrumb-row .breadcrumb-item a {
        color: #4a4a4a;
        text-decoration: none;
        transition: color 0.15s ease;
    }
    .responsive-breadcrumb-row .breadcrumb-item a:hover {
        color: #685c4e;
    }
    .responsive-breadcrumb-row .breadcrumb-item.active {
        color: #1b1b28;
        font-weight: 600;
    }

    /* Seller Info Button Trigger */
    #viewSellerInfoBtn {
        background: #685c4e;
        color: #ffffff !important;
        border: 1px solid #685c4e;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        box-shadow: 0 3px 8px rgba(104, 92, 78, 0.25);
        transition: all .2s ease-in-out;
        padding: 7px 14px !important;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        flex-shrink: 0;
        margin-left: 8px;
    }
    #viewSellerInfoBtn:hover {
        background: #54493d;
        border-color: #54493d;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(104, 92, 78, 0.35);
    }
    #viewSellerInfoBtn i {
        font-size: 16px;
    }

    /* Mobile specific tweaks */
    @media (max-width: 767.98px) {
        .responsive-breadcrumb-row .breadcrumb-item {
            font-size: 13px;
        }
        #viewSellerInfoBtn {
            padding: 6px 12px !important;
            font-size: 12.5px;
        }
    }

    /* ── Seller Info Drawer (Mobile) & Popover (Desktop) ── */
    @media (max-width: 767.98px) {
        .seller-info-popover {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            top: auto !important;
            width: 100% !important;
            z-index: 1085 !important;
            transform: translateY(100%);
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
            display: block !important;
            visibility: hidden;
            pointer-events: none;
        }
        .seller-info-popover.active {
            transform: translateY(0) !important;
            visibility: visible !important;
            pointer-events: auto !important;
        }
        .seller-info-popover-content {
            background: #ffffff;
            border-radius: 20px 20px 0 0 !important;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.2) !important;
            padding: 1rem 1.25rem 1.5rem 1.25rem !important;
            max-width: 100% !important;
            min-width: 100% !important;
            max-height: 85vh;
            overflow-y: auto;
        }
        .drawer-drag-handle {
            width: 42px;
            height: 4.5px;
            background: #e0e0e0;
            border-radius: 3px;
            margin: 0 auto 12px auto;
        }
    }

    @media (min-width: 768px) {
        .seller-info-popover {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            z-index: 1085;
            display: none;
        }
        .seller-info-popover.active {
            display: block !important;
        }
        .seller-info-popover-content {
            background: #ffffff;
            width: 370px;
            border-radius: 14px;
            box-shadow: 0 14px 34px rgba(0,0,0,0.15), 0 2px 8px rgba(0,0,0,0.06);
            padding: 1.25rem;
            border: 1px solid #eaeaea;
        }
        .drawer-drag-handle {
            display: none;
        }
    }

    .seller-info-popover-backdrop {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: 1080;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        transition: opacity 0.25s ease;
        opacity: 0;
    }
    .seller-info-popover-backdrop.active {
        display: block;
        opacity: 1;
    }
    body.seller-info-popover-open {
        overflow: hidden !important;
    }
</style>

   <section class="pt-0 mb-4">
    <div class="container">
        <div class="py-0 bg-white">

            <div class="row">
                <div class="pt-4 pb-4 col-12 d-flex flex-wrap justify-content-between flex-column image_gallery_section_shadow">
                    <div class="d-flex flex-row align-items-center justify-content-between w-100 responsive-breadcrumb-row">
                        <!-- Clean Breadcrumbs Navigation -->
                        <nav aria-label="breadcrumb" class="responsive-breadcrumb-nav">
                            @php
                                $breadcrumbCategory = $detailedProduct->main_category;
                                $productFullName    = $detailedProduct->getTranslation('name');
                            @endphp
                            <ol class="breadcrumb bg-white pl-0 p-0 m-0 align-items-center">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('home') }}">
                                        <i class="las la-home"></i> {{ translate('Home') }}
                                    </a>
                                </li>
                                @if($breadcrumbCategory)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('products.category', $breadcrumbCategory->slug) }}" title="{{ $breadcrumbCategory->getTranslation('name') }}">
                                        {{ $breadcrumbCategory->getTranslation('name') }}
                                    </a>
                                </li>
                                @endif
                                <li class="breadcrumb-item active d-none d-md-inline-block" aria-current="page">
                                    <span>{{ $productFullName }}</span>
                                </li>
                            </ol>
                        </nav>

                        <!-- Clear Seller Info Drawer Trigger Button -->
                        <div class="position-relative ml-2" id="viewSellerInfoMenuWrapper">
                            <button type="button"
                                class="btn"
                                id="viewSellerInfoBtn"
                                title="{{ translate('View seller information') }}">
                                <i class="las la-store"></i>
                                <span>{{ translate('Seller Info') }}</span>
                                <i class="las la-angle-right d-none d-sm-inline-block ml-1" style="font-size: 11px;"></i>
                            </button>

                            <!-- Popover / Bottom Sheet Drawer -->
                            <div id="sellerInfoPopover" class="seller-info-popover">
                                <div class="seller-info-popover-content">
                                    <div class="drawer-drag-handle"></div>
                                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                                        <h5 class="mb-0 fw-700 fs-16 text-dark" id="sellerInfoPopoverLabel">
                                            <i class="las la-store mr-1 text-primary"></i> {{ translate('Seller Information') }}
                                        </h5>
                                        <button type="button" class="btn btn-icon btn-sm text-secondary p-0" id="sellerInfoPopoverClose" aria-label="Close" style="background: none; border: none;">
                                            <i class="las la-times fs-22"></i>
                                        </button>
                                    </div>
                                    <div>
                                        @include('frontend.product_details.seller_info')
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="sellerInfoPopoverBackdrop" class="seller-info-popover-backdrop"></div>
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const btn = document.getElementById('viewSellerInfoBtn');
                    const popover = document.getElementById('sellerInfoPopover');
                    const popoverClose = document.getElementById('sellerInfoPopoverClose');
                    const backdrop = document.getElementById('sellerInfoPopoverBackdrop');

                    function openPopover() {
                        if (!popover || !backdrop) return;
                        popover.classList.add('active');
                        backdrop.classList.add('active');
                        document.body.classList.add('seller-info-popover-open');
                    }

                    function closePopover() {
                        if (!popover || !backdrop) return;
                        popover.classList.remove('active');
                        backdrop.classList.remove('active');
                        document.body.classList.remove('seller-info-popover-open');
                    }

                    if (btn && popover && backdrop) {
                        btn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            if (popover.classList.contains('active')) {
                                closePopover();
                            } else {
                                openPopover();
                            }
                        });

                        if (popoverClose) {
                            popoverClose.addEventListener('click', function(e) {
                                e.stopPropagation();
                                closePopover();
                            });
                        }

                        backdrop.addEventListener('click', closePopover);

                        document.addEventListener('keydown', function(e) {
                            if (e.key === "Escape" && popover.classList.contains('active')) {
                                closePopover();
                            }
                        });

                        document.addEventListener('mousedown', function(e) {
                            if (popover.classList.contains('active') && window.innerWidth >= 768) {
                                if (!popover.contains(e.target) && !btn.contains(e.target)) {
                                    closePopover();
                                }
                            }
                        });
                    }
                });
            </script>


            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="row product-main-row gutters-10 flex-column flex-lg-row" style="margin-bottom: 50px;">
                        <!-- Product Image Gallery -->
                        <div
                            class="mb-4 col-xl-6 col-lg-6 product-gallery-col sticky-gallery"
                            id="imageGalleryCol">
                            <div>
                                @include('frontend.product_details.image_gallery')
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div
                            class="col-xl-6 col-lg-6 product-details-col scroll-details"
                            id="productDetailsCol">
                            <div>
                                @include('frontend.product_details.details')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                /* Premium Sticky Gallery & Natural Scrolling Details Layout */
                @media (min-width: 992px) {
                    .product-main-row {
                        min-height: unset !important;
                        max-height: unset !important;
                        display: flex !important;
                        flex-direction: row !important;
                        align-items: flex-start !important;
                    }
                    .product-gallery-col.sticky-gallery {
                        position: -webkit-sticky !important;
                        position: sticky !important;
                        top: 130px !important; /* Elegant offset for header navigation */
                        z-index: 10;
                        align-self: flex-start !important;
                        height: auto !important;
                    }
                    .product-details-col.scroll-details {
                        height: auto !important;
                        overflow-y: visible !important;
                        overflow-x: visible !important;
                    }
                }

                @media (max-width: 991.98px) {
                    .product-main-row {
                        flex-direction: column !important;
                        min-height: unset !important;
                        max-height: unset !important;
                    }
                    .product-gallery-col,
                    .product-details-col {
                        width: 100% !important;
                        max-width: 100% !important;
                        flex: unset !important;
                        height: auto !important;
                        min-height: unset !important;
                        max-height: unset !important;
                        overflow: visible !important;
                        position: static !important;
                    }
                }

                @media only screen and (max-width: 1500px) {
                    .aiz-carousel .slick-arrow {
                        top: 50% !important;
                        background: transparent !important;
                    }
                }
            </style>

            <div class="row">
                <div class="col-md-12">
                    @include('frontend.product_details.description')
                </div>
            </div>

        </div>
    </div>
</section>

    <section class="mb-4">
        <div class="container">
            @if (isset($detailedProduct) &&
                    property_exists($detailedProduct, 'auction_product') &&
                    $detailedProduct->auction_product)
                <!-- Reviews & Ratings -->
                {{-- @include('frontend.product_details.review_section') --}}

                <!-- Description, Video, Downloads -->
                {{-- @include('frontend.product_details.description') --}}

                <!-- Product Query -->
                @include('frontend.product_details.product_queries')
            @else
                <div class="row gutters-16">
                    <!-- Left side -->
                    {{-- <div class="col-lg-3">
                        <!-- Seller Info -->
                        @include('frontend.product_details.seller_info')

                        <!-- Top Selling Products -->
                       <div class="d-none d-lg-block">
                            @include('frontend.product_details.top_selling_products')
                       </div>
                    </div> --}}

                    <!-- Right side -->
                    <div class="col-lg-12">

                        <!-- Reviews & Ratings -->
                        {{-- @include('frontend.product_details.review_section') --}}

                        <!-- Description, Video, Downloads -->
                       {{-- @include('frontend.product_details.description') --}}

                        <!-- Related products -->
                        @include('frontend.product_details.related_products')

                        <!-- Product Query -->
                        @if (get_setting('product_query_activation') == 1)
                            @include('frontend.product_details.product_queries')
                        @endif
                        <!-- Top Selling Products -->
                        <div class="d-lg-none">
                             @include('frontend.product_details.top_selling_products')
                        </div>

                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@section('modal')
    <!-- Image Modal -->
    <div class="modal fade" id="image_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="p-4">
                    <div class="size-300px size-lg-450px">
                        <img class="img-fit h-100 lazyload"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            data-src=""
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat Modal -->
    <div class="modal fade" id="chat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-zoom product-modal" id="modal-size" role="document">
            <div class="modal-content position-relative">
                <div class="modal-header">
                    <h5 class="modal-title fw-600 h5">{{ translate('Any query about this product') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="" action="{{ route('conversations.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                    <div class="px-3 pt-3 modal-body gry-bg">
                        <div class="form-group">
                            <input type="text" class="mb-3 form-control rounded-0" name="title"
                                value="{{ $detailedProduct->name }}" placeholder="{{ translate('Product Name') }}"
                                required>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control rounded-0" rows="8" name="message" required
                                placeholder="{{ translate('Your Question') }}">{{ route('product', $detailedProduct->slug) }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary fw-600 rounded-0"
                            data-dismiss="modal">{{ translate('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary fw-600 rounded-0 w-100px">{{ translate('Send') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bid Modal -->
    @if ($detailedProduct->auction_product == 1)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid+1 : $detailedProduct->starting_bid;
        @endphp
        <div class="modal fade" id="bid_for_detail_product" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{ translate('Bid For Product') }} <small>({{ translate('Min Bid Amount: ') . $min_bid_amount }})</small> </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" action="{{ route('auction_product_bids.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $detailedProduct->id }}">
                            <div class="form-group">
                                <label class="form-label">
                                    {{ translate('Place Bid Price') }}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="amount" min="{{ $min_bid_amount }}" placeholder="{{ translate('Enter Amount') }}" required>
                                </div>
                            </div>
                            <div class="text-right form-group">
                                <button type="submit" class="mr-1 btn btn-sm btn-primary transition-3d-hover">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Product Review Modal -->
    <div class="modal fade" id="product-review-modal">
        <div class="modal-dialog">
            <div class="modal-content" id="product-review-modal-content">

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            getVariantPrice();
        });

        function CopyToClipboard(e) {
            var url = $(e).data('url');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(url).select();
            try {
                document.execCommand("copy");
                AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
            } catch (err) {
                AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
            }
            $temp.remove();
            // if (document.selection) {
            //     var range = document.body.createTextRange();
            //     range.moveToElementText(document.getElementById(containerid));
            //     range.select().createTextRange();
            //     document.execCommand("Copy");

            // } else if (window.getSelection) {
            //     var range = document.createRange();
            //     document.getElementById(containerid).style.display = "block";
            //     range.selectNode(document.getElementById(containerid));
            //     window.getSelection().addRange(range);
            //     document.execCommand("Copy");
            //     document.getElementById(containerid).style.display = "none";

            // }
            // AIZ.plugins.notify('success', 'Copied');
        }

        function show_chat_modal() {
            @if (Auth::check())
                $('#chat_modal').modal('show');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        // Pagination using ajax
        $(window).on('hashchange', function() {
            if(window.history.pushState) {
                window.history.pushState('', '/', window.location.pathname);
            } else {
                window.location.hash = '';
            }
        });

        $(document).ready(function() {
            $(document).on('click', '.product-queries-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'query', 'queries-area');
                e.preventDefault();
            });
        });

        $(document).ready(function() {
            $(document).on('click', '.product-reviews-pagination .pagination a', function(e) {
                getPaginateData($(this).attr('href').split('page=')[1], 'review', 'reviews-area');
                e.preventDefault();
            });
        });

        function getPaginateData(page, type, section) {
            $.ajax({
                url: '?page=' + page,
                dataType: 'json',
                data: {type: type},
            }).done(function(data) {
                $('.'+section).html(data);
                location.hash = page;
            }).fail(function() {
                alert('Something went worng! Data could not be loaded.');
            });
        }
        // Pagination end

        function showImage(photo) {
            $('#image_modal img').attr('src', photo);
            $('#image_modal img').attr('data-src', photo);
            $('#image_modal').modal('show');
        }

        function bid_modal(){
            @if (isCustomer() || isSeller())
                $('#bid_for_detail_product').modal('show');
          	@elseif (isAdmin())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers & Sellers can Bid.') }}');
            @else
                $('#login_modal').modal('show');
            @endif
        }

        function product_review(product_id) {
            @if (isCustomer())
                @if ($review_status == 1)
                    $.post('{{ route('product_review_modal') }}', {
                        _token: '{{ @csrf_token() }}',
                        product_id: product_id
                    }, function(data) {
                        $('#product-review-modal-content').html(data);
                        $('#product-review-modal').modal('show', {
                            backdrop: 'static'
                        });
                        AIZ.extra.inputRating();
                    });
                @else
                    AIZ.plugins.notify('warning', '{{ translate('Sorry, You need to buy this product to give review.') }}');
                @endif
            @elseif (Auth::check() && !isCustomer())
                AIZ.plugins.notify('warning', '{{ translate('Sorry, Only customers can give review.') }}');
            @else
                $('#login_modal').modal('show'); @endif
                                                                                                                        }
                                                                                                                    </script>
@endsection
