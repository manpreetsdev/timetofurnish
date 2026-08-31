@extends('frontend.layouts.user_panel')

@section('panel_content')

<style>
    /* =========================================
       WISHLIST CARD
    ========================================= */

    .wishlist-custom-card {
        position: relative;
        background: #fff;

        padding: 14px;
        text-align: center;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .wishlist-custom-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }


    /* =========================================
       PRODUCT IMAGE AREA
    ========================================= */

    .wishlist-image-box {
        position: relative;
        width: 100%;
        height: 140px;
        overflow: hidden;
        margin-bottom: 10px;
        border-radius: 0;
    }

    .wishlist-image-box a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .wishlist-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }


    /* =========================================
       REMOVE WISHLIST ICON
    ========================================= */

    .wishlist-remove-btn {
        position: absolute !important;
        top: 6px !important;
        right: 6px !important;

        width: 36px !important;
        height: 36px !important;

        background: #fff !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        z-index: 20 !important;

        border: none !important;
        border-radius: 0 !important;

        text-decoration: none !important;
    }

    .wishlist-remove-btn i {
        font-size: 14px !important;
        color: #756657 !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    .wishlist-remove-btn:hover i {
        color: #000 !important;
    }


    /* =========================================
       ADD TO BASKET BUTTON
       NEW CUSTOM CLASS
    ========================================= */

    .wishlist-add-basket-btn {
        position: relative !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        width: 100% !important;
        height: 35px !important;

        margin: 0 0 12px 0 !important;
        padding: 0 10px !important;

        background: #756657 !important;

        border: none !important;
        border-radius: 0 !important;

        color: #fff !important;

        font-size: 13px !important;
        font-weight: 700 !important;

        line-height: 35px !important;
        text-align: center !important;
        text-decoration: none !important;

        opacity: 1 !important;
        visibility: visible !important;

        z-index: 10 !important;

        cursor: pointer !important;

        transition: background 0.3s ease !important;
    }

    .wishlist-add-basket-btn:hover {
        background: #67584c !important;
        color: #fff !important;
        text-decoration: none !important;
    }

    .wishlist-add-basket-btn:focus {
        background: #67584c !important;
        color: #fff !important;
        outline: none !important;
        box-shadow: none !important;
    }


    /* =========================================
       PRODUCT NAME
    ========================================= */

    .wishlist-product-name {
        min-height: 44px;

        margin: 0 0 10px 0;

        font-size: 14px;
        line-height: 1.5;

        font-weight: 400;

        text-align: center;
    }

    .wishlist-product-name a {
        color: #00133a !important;
        text-decoration: none !important;
    }

    .wishlist-product-name a:hover {
        color: #756657 !important;
    }


    /* =========================================
       PRODUCT PRICE
    ========================================= */

    .wishlist-product-price {
        font-size: 14px;
        line-height: 22px;
        text-align: center;
    }

    .wishlist-product-price .wishlist-current-price {
        color: #111 !important;
        font-weight: 700 !important;
    }

    .wishlist-product-price del {
        color: #777 !important;
        opacity: 0.6;
    }


    /* =========================================
       MOBILE
    ========================================= */

    @media (max-width: 767px) {

        .wishlist-custom-card {
            padding: 10px;
            border-radius: 12px;
        }

        .wishlist-image-box {
            height: 140px;
        }

        .wishlist-add-basket-btn {
            height: 35px !important;
            line-height: 35px !important;
            font-size: 13px !important;
            margin-bottom: 10px !important;
        }

        .wishlist-product-name {
            font-size: 14px;
            min-height: auto;
        }

    }


    /* =========================================
       VERY SMALL SCREEN
    ========================================= */

    @media (max-width: 480px) {

        .wishlist-custom-card {
            padding: 8px;
        }

        .wishlist-image-box {
            height: 130px;
        }

        .wishlist-add-basket-btn {
            height: 34px !important;
            line-height: 34px !important;
            font-size: 12px !important;
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

<div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 gutters-16 border-top border-left mx-1 mx-md-0 mb-4">

    @foreach($wishlists as $key => $wishlist)

    @if ($wishlist->product != null)

    <div
        class="col py-3 border-right border-bottom"
        id="wishlist_{{ $wishlist->id }}">

        <!-- =========================================
                         CUSTOM WISHLIST CARD
                    ========================================== -->

        <div class="wishlist-custom-card">


            <!-- =========================================
                             PRODUCT IMAGE
                        ========================================== -->

            <div class="wishlist-image-box">

                <a
                    href="{{ route('product', $wishlist->product->slug) }}">

                    <img
                        src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}"
                        class="lazyload"
                        title="{{ $wishlist->product->getTranslation('name') }}"
                        alt="{{ $wishlist->product->getTranslation('name') }}">

                </a>


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

                    <i class="la la-trash"></i>

                </a>

            </div>


            <!-- =========================================
                             ADD TO BASKET
                             IMPORTANT: OUTSIDE IMAGE WRAPPER
                        ========================================== -->

            <a
                href="javascript:void(0)"
                onclick="showAddToCartModal({{ $wishlist->product->id }})"
                class="wishlist-add-basket-btn">
                {{ translate('Add to Basket') }}
            </a>


            <!-- =========================================
                             PRODUCT NAME
                        ========================================== -->

            <h5 class="wishlist-product-name">

                <a
                    href="{{ route('product', $wishlist->product->slug) }}"
                    title="{{ $wishlist->product->getTranslation('name') }}">

                    {{ $wishlist->product->getTranslation('name') }}

                </a>

            </h5>


            <!-- =========================================
                             PRICE
                        ========================================== -->

            <div class="wishlist-product-price">

                <span class="wishlist-current-price">

                    {{ home_discounted_base_price($wishlist->product) }}

                </span>


                @if(
                home_base_price($wishlist->product)
                !=
                home_discounted_base_price($wishlist->product)
                )

                <del class="ml-1">

                    {{ home_base_price($wishlist->product) }}

                </del>

                @endif

            </div>


        </div>

    </div>

    @endif

    @endforeach

</div>


@else

<!-- =========================================
         EMPTY WISHLIST
    ========================================== -->

<div class="row">

    <div class="col">

        <div class="text-center bg-white p-4 border">

            <img
                class="mw-100 h-200px"
                src="{{ static_asset('assets/img/nothing.svg') }}"
                alt="Image">

            <h5 class="mb-0 h5 mt-3">

                {{ translate("There isn't anything added yet") }}

            </h5>

        </div>

    </div>

</div>

@endif


<!-- =========================================
     PAGINATION
========================================= -->

<div class="aiz-pagination">

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
       REMOVE FROM WISHLIST
    ========================================== */

    function removeFromWishlist(id) {

        $.post(
            '{{ route('
            wishlists.remove ') }}', {
                _token: '{{ csrf_token() }}',
                id: id
            },

            function(data) {

                $('#wishlist').html(data);

                $('#wishlist_' + id).hide();

                AIZ.plugins.notify(
                    'success',
                    '{{ translate("Item has been renoved from wishlist") }}'
                );

            }
        );

    }
</script>

@endsection