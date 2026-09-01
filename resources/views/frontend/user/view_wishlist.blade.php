@extends('frontend.layouts.user_panel')

@section('panel_content')

<style>
    /* =========================================
       WISHLIST MODERN CARD DESIGN
    ========================================= */
    .wishlist-modern-card {
        position: relative;
        background: #ffffff;
        border: 1px solid #f3ece4;
        border-radius: 12px;
        padding: 12px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        box-shadow: 0 4px 15px rgba(104, 91, 78, 0.04);
    }

    .wishlist-modern-card:hover {
        box-shadow: 0 12px 30px rgba(104, 91, 78, 0.08);
        border-color: #685b4e;
    }

    /* Image Wrapper */
    .wishlist-img-wrap {
        position: relative;
        width: 100%;
        height: 170px;
        overflow: hidden;
        border-radius: 10px;
        background-color: #FAF7F2;
        margin-bottom: 12px;
    }

    .wishlist-img-wrap a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .wishlist-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .wishlist-modern-card:hover .wishlist-img-wrap img {
        transform: scale(1.06);
    }

    /* Remove Button (Top Right) */
    .wishlist-remove-btn {
        position: absolute !important;
        top: 8px !important;
        right: 8px !important;
        width: 32px !important;
        height: 32px !important;
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 10 !important;
        border: none !important;
        border-radius: 50% !important;
        text-decoration: none !important;
        color: #756657 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        transition: all 0.25s ease !important;
        cursor: pointer !important;
    }

    .wishlist-remove-btn:hover {
        background: #ffffff !important;
        color: #e55353 !important;
        transform: scale(1.1) !important;
        box-shadow: 0 4px 12px rgba(229, 83, 83, 0.2) !important;
    }

    .wishlist-remove-btn i,
    .wishlist-remove-btn svg {
        font-size: 16px !important;
        transition: color 0.2s ease !important;
    }

    /* Discount Badge */
    .wishlist-discount-badge {
        position: absolute;
        top: 8px;
        left: 8px;
        background-color: #685b4e;
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        text-transform: uppercase;
        z-index: 2;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 6px rgba(104, 91, 78, 0.15);
    }

    /* Product Details Content */
    .wishlist-card-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
        text-align: center;
    }

    .wishlist-product-name {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        margin: 0 0 8px 0;
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .wishlist-product-name a {
        color: #3e3327 !important;
        text-decoration: none !important;
        transition: color 0.2s ease !important;
    }

    .wishlist-product-name a:hover {
        color: #685b4e !important;
    }

    /* Price Section */
    .wishlist-product-price {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 6px;
    }

    .wishlist-current-price {
        color: #685b4e !important;
        font-weight: 700 !important;
        font-size: 15px !important;
    }

    .wishlist-product-price del {
        color: #9c9184 !important;
        font-size: 12px !important;
        font-weight: 400 !important;
    }

    /* Add to Basket Button - ALWAYS VISIBLE */
    .wishlist-add-basket-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 8px !important;
        width: 100% !important;
        height: 38px !important;
        margin: 0 !important;
        padding: 0 12px !important;
        background: #685b4e !important;
        border: none !important;
        border-radius: 6px !important;
        color: #ffffff !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        text-align: center !important;
        text-decoration: none !important;
        opacity: 1 !important;
        visibility: visible !important;
        cursor: pointer !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 2px 6px rgba(104, 91, 78, 0.12) !important;
    }

    .wishlist-add-basket-btn:hover {
        background: #54493e !important;
        color: #ffffff !important;
        text-decoration: none !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 12px rgba(104, 91, 78, 0.25) !important;
    }

    .wishlist-add-basket-btn:focus {
        background: #54493e !important;
        color: #ffffff !important;
        outline: none !important;
    }

    /* Pagination Styling */
    .aiz-pagination .pagination {
        justify-content: center;
        margin-top: 20px;
        gap: 6px;
    }

    .aiz-pagination .page-item .page-link {
        border-radius: 8px !important;
        color: #4a3e38 !important;
        border: 1px solid #f0eae1 !important;
        background: #ffffff !important;
        padding: 8px 14px !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease !important;
    }

    .aiz-pagination .page-item.active .page-link,
    .aiz-pagination .page-item .page-link:hover {
        background: #685b4e !important;
        border-color: #685b4e !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(104, 91, 78, 0.2) !important;
    }

    @media (max-width: 767px) {
        .wishlist-img-wrap {
            height: 150px;
        }

        .wishlist-add-basket-btn {
            height: 36px !important;
            font-size: 12.5px !important;
        }
    }
</style>


<!-- =========================================
     WISHLIST TITLE
========================================= -->
<div class="aiz-titlebar mb-4">
    <div class="row align-items-center">
        <div class="col-md-6">
            <b class="fs-20 fw-700 text-dark">
                {{ translate('Wishlist') }}
            </b>
        </div>
    </div>
</div>


<!-- =========================================
     WISHLIST PRODUCTS
========================================= -->
@if (count($wishlists) > 0)

<div class="row row-cols-xxl-4 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-sm-2 row-cols-1 gutters-16 mb-4">

    @foreach($wishlists as $key => $wishlist)

    @if ($wishlist->product != null)

    <div class="col mb-4 d-flex align-items-stretch" id="wishlist_{{ $wishlist->id }}">

        <!-- =========================================
             WISHLIST MODERN CARD
        ========================================= -->
        <div class="wishlist-modern-card w-100">

            <!-- =========================================
                 PRODUCT IMAGE AREA
            ========================================= -->
            <div class="wishlist-img-wrap">
                <a href="{{ route('product', $wishlist->product->slug) }}">
                    <img
                        src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}"
                        class="lazyload"
                        title="{{ $wishlist->product->getTranslation('name') }}"
                        alt="{{ $wishlist->product->getTranslation('name') }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>

                <!-- =====================================
                     DISCOUNT / OFFER BADGE
                ====================================== -->
                @php
                    $active_offer = get_product_active_offer($wishlist->product);
                @endphp
                @if ($active_offer)
                    @php
                        $badge_txt = $active_offer->badge_text;
                        if (is_numeric($badge_txt) || (str_ends_with($badge_txt, '%') && !str_contains(strtolower($badge_txt), 'off'))) {
                            $badge_txt .= ' OFF';
                        }
                    @endphp
                    <span class="wishlist-discount-badge">
                        {{ $badge_txt }}
                    </span>
                @elseif (discount_in_percentage($wishlist->product) > 0)
                    <span class="wishlist-discount-badge">
                        -{{ discount_in_percentage($wishlist->product) }}%
                    </span>
                @endif

                <!-- =====================================
                     REMOVE FROM WISHLIST
                ====================================== -->
                <a
                    href="javascript:void(0)"
                    onclick="removeFromWishlist({{ $wishlist->id }})"
                    data-toggle="tooltip"
                    data-title="{{ translate('Remove from wishlist') }}"
                    data-placement="left"
                    class="wishlist-remove-btn">
                    <i class="las la-trash-alt"></i>
                </a>
            </div>

            <!-- =========================================
                 PRODUCT CARD DETAILS
            ========================================= -->
            <div class="wishlist-card-body">
                <div>
                    <!-- Product Name -->
                    <h5 class="wishlist-product-name">
                        <a
                            href="{{ route('product', $wishlist->product->slug) }}"
                            title="{{ $wishlist->product->getTranslation('name') }}">
                            {{ $wishlist->product->getTranslation('name') }}
                        </a>
                    </h5>

                    <!-- Price -->
                    <div class="wishlist-product-price">
                        <span class="wishlist-current-price">
                            {{ home_discounted_base_price($wishlist->product) }}
                        </span>

                        @if(home_base_price($wishlist->product) != home_discounted_base_price($wishlist->product))
                        <del>
                            {{ home_base_price($wishlist->product) }}
                        </del>
                        @endif
                    </div>
                </div>

                <!-- =========================================
                     ADD TO BASKET (ALWAYS VISIBLE)
                ========================================= -->
                <a
                    href="javascript:void(0)"
                    onclick="showAddToCartModal({{ $wishlist->product->id }})"
                    class="wishlist-add-basket-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span>{{ translate('Add to Basket') }}</span>
                </a>
            </div>

        </div>

    </div>

    @endif

    @endforeach

</div>

@else

<!-- =========================================
     EMPTY WISHLIST
========================================= -->
<div class="row">
    <div class="col">
        <div class="text-center bg-white p-4 border" style="border-radius: 12px; border-color: #f3ece4 !important;">
            <img
                class="mw-100 h-200px"
                src="{{ static_asset('assets/img/nothing.svg') }}"
                alt="Image">
            <h5 class="mb-0 h5 mt-3 text-muted">
                {{ translate("There isn't anything added yet") }}
            </h5>
        </div>
    </div>
</div>

@endif


<!-- =========================================
     PAGINATION
========================================= -->
<div class="aiz-pagination mt-4 d-flex justify-content-center">
    {{ $wishlists->links() }}
</div>

@endsection


@section('modal')

<!-- =========================================
     ADD TO CART MODAL
========================================= -->
<div
    class="modal fade"
    id="addToCart"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true">

    <div
        class="modal-dialog modal-lg modal-dialog-centered modal-dialog-zoom product-modal"
        id="modal-size"
        role="document">

        <div class="modal-content position-relative">

            <div class="c-preloader">
                <i class="fa fa-spin fa-spinner"></i>
            </div>

            <button
                type="button"
                class="close absolute-close-btn"
                data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">
                    &times;
                </span>
            </button>

            <div id="addToCart-modal-body">

            </div>

        </div>

    </div>

</div>

@endsection


@section('script')

<script type="text/javascript">
    /* =========================================
       INSTANT REMOVE FROM WISHLIST (OPTIMISTIC UI)
    ========================================== */
    function removeFromWishlist(id) {
        var $item = $('#wishlist_' + id);

        // 1. Instant UI Feedback (Fade out & collapse immediately)
        if ($item.length) {
            $item.css({
                'transition': 'all 0.25s ease-out',
                'opacity': '0',
                'transform': 'scale(0.85)'
            });

            setTimeout(function() {
                $item.slideUp(200, function() {
                    $item.remove();
                    if ($('.wishlist-modern-card').length === 0) {
                        location.reload();
                    }
                });
            }, 200);
        }

        // 2. Optimistically decrement header wishlist count badge
        var $badge = $('#wishlist .badge');
        if ($badge.length) {
            var currentCount = parseInt($badge.text().trim()) || 0;
            if (currentCount > 1) {
                $badge.text(currentCount - 1);
            } else {
                $badge.remove();
            }
        }

        // 3. Send server request in background
        $.post(
            '{{ route('wishlists.remove') }}',
            {
                _token: '{{ csrf_token() }}',
                id: id
            },
            function(data) {
                if (data) {
                    $('#wishlist').html(data);
                }
                AIZ.plugins.notify(
                    'success',
                    '{{ translate("Item has been removed from wishlist") }}'
                );
            }
        ).fail(function() {
            // Restore element if server call fails
            if ($item.length) {
                $item.show().css({'opacity': '1', 'transform': 'none'});
            }
            AIZ.plugins.notify(
                'danger',
                '{{ translate("Something went wrong, please try again") }}'
            );
        });
    }
</script>

@endsection