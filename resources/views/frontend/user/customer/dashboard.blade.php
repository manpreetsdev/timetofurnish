@extends('frontend.layouts.user_panel')

@section('panel_content')

@php
$cart = get_user_cart();
$wishlistCount = count(Auth::user()->wishlists);
$totalOrdered = get_user_total_ordered_products();
$addressCount = Auth::user()->addresses ? Auth::user()->addresses->count() : 0;

$orders = Auth::user()->orders()->latest()->take(4)->get();

$address = null;
if (Auth::user()->addresses != null) {
$address = Auth::user()->addresses->where('set_default', 1)->first();
}

$wishlists = get_user_wishlist();
@endphp


<!-- =========================
     DASHBOARD HEADER
========================= -->
<div class="customer-dashboard-header">

    <div class="dashboard-header-content">

        <span class="dashboard-small-title">
            MY ACCOUNT
        </span>

        <h1>
            Welcome back, {{ Auth::user()->name }}!
        </h1>

        <p>
            Manage your furniture orders, wishlist and account from one place.
        </p>

        <div class="dashboard-header-actions">

            <a href="{{ route('home') }}" class="dashboard-primary-btn">
                <i class="las la-shopping-bag"></i>
                Continue Shopping
            </a>

            <a rel="nofollow" href="{{ route('purchase_history.index') }}" class="dashboard-secondary-btn">
                <i class="las la-box"></i>
                View Orders
            </a>

        </div>

    </div>

    {{-- <div class="dashboard-header-icon">
        <i class="las la-couch"></i>
    </div>--}}

</div>


<!-- =========================
     STATISTICS
========================= -->
<div class="dashboard-stats">

    <!-- Cart -->
    <div class="dashboard-stat-card">

        <div class="stat-icon-wrap stat-cart">
            <i class="las la-shopping-cart"></i>
        </div>

        <div class="stat-info">

            <span class="stat-label">
                Products in Cart
            </span>

            <h3>
                {{ count($cart) > 0 ? sprintf("%02d", count($cart)) : '00' }}
            </h3>

            <a href="{{ route('cart') }}">
                View Cart
                <i class="las la-arrow-right"></i>
            </a>

        </div>

    </div>


    <!-- Wishlist -->
    <div class="dashboard-stat-card">

        <div class="stat-icon-wrap stat-wishlist">
            <i class="lar la-heart"></i>
        </div>

        <div class="stat-info">

            <span class="stat-label">
                Products in Wishlist
            </span>

            <h3>
                {{ $wishlistCount > 0 ? sprintf("%02d", $wishlistCount) : '00' }}
            </h3>

            <a rel="nofollow" href="{{ route('wishlists.index') }}">
                View Wishlist
                <i class="las la-arrow-right"></i>
            </a>

        </div>

    </div>


    <!-- Orders -->
    <div class="dashboard-stat-card">

        <div class="stat-icon-wrap stat-orders">
            <i class="las la-box-open"></i>
        </div>

        <div class="stat-info">

            <span class="stat-label">
                Products Ordered
            </span>

            <h3>
                {{ $totalOrdered > 0 ? sprintf("%02d", $totalOrdered) : '00' }}
            </h3>

            <a rel="nofollow" href="{{ route('purchase_history.index') }}">
                View Orders
                <i class="las la-arrow-right"></i>
            </a>

        </div>

    </div>


    <!-- Addresses -->
    <div class="dashboard-stat-card">

        <div class="stat-icon-wrap stat-address">
            <i class="las la-map-marker-alt"></i>
        </div>

        <div class="stat-info">

            <span class="stat-label">
                Saved Addresses
            </span>

            <h3>
                {{ sprintf("%02d", $addressCount) }}
            </h3>

            <a href="javascript:void(0)" onclick="add_new_address()">
                Manage Addresses
                <i class="las la-arrow-right"></i>
            </a>

        </div>

    </div>

</div>


<!-- =========================
     MAIN DASHBOARD AREA
========================= -->
<div class="row dashboard-main-row">

    <!-- =====================
         RECENT ORDERS
    ====================== -->
    <div class="col-xl-8 mb-4">

        <div class="dashboard-main-card">

            <div class="dashboard-card-header">

                <div>
                    <span class="card-small-title">
                        ORDER ACTIVITY
                    </span>

                    <h3>
                        Recent Orders
                    </h3>
                </div>

                <a rel="nofollow" href="{{ route('purchase_history.index') }}"
                    class="dashboard-view-all">
                    View All
                    <i class="las la-arrow-right"></i>
                </a>

            </div>


            <div class="recent-orders-list">

                @forelse($orders as $order)

                <div class="recent-order-item">

                    <div class="recent-order-left">

                        <div class="order-icon">
                            <i class="las la-box"></i>
                        </div>

                        <div class="order-details">

                            <h5>
                                Order #{{ $order->code }}
                            </h5>

                            <p>
                                <i class="las la-calendar"></i>
                                {{ date('d M Y', strtotime($order->created_at)) }}
                            </p>

                        </div>

                    </div>


                    <div class="recent-order-right">

                        <span class="order-status">
                            {{ ucfirst($order->delivery_status) }}
                        </span>

                        <strong>
                            {{ single_price($order->grand_total) }}
                        </strong>

                    </div>

                </div>

                @empty

                <div class="dashboard-empty-state">

                    <img src="{{ static_asset('assets/img/nothing.svg') }}"
                        alt="No orders">

                    <h5>
                        No orders found
                    </h5>

                    <p>
                        Your recent orders will appear here.
                    </p>

                    <a href="{{ route('home') }}"
                        class="empty-state-btn">
                        Start Shopping
                    </a>

                </div>

                @endforelse

            </div>

        </div>

    </div>


    <!-- =====================
         ACCOUNT OVERVIEW
    ====================== -->
    <div class="col-xl-4 mb-4">

        <div class="dashboard-main-card account-card">

            <div class="dashboard-card-header">

                <div>
                    <span class="card-small-title">
                        ACCOUNT
                    </span>

                    <h3>
                        Quick Overview
                    </h3>
                </div>

                <div class="account-card-icon">
                    <i class="las la-user"></i>
                </div>

            </div>


            <!-- User -->
            <div class="account-user-box">

                <div class="account-avatar">
                    <i class="las la-user"></i>
                </div>

                <div>

                    <h5>
                        {{ Auth::user()->name }}
                    </h5>

                    <p>
                        {{ Auth::user()->email }}
                    </p>

                </div>

            </div>


            <!-- Account Links -->
            <div class="account-links">

                <a rel="nofollow" href="{{ route('purchase_history.index') }}">

                    <span>
                        <i class="las la-shopping-bag"></i>
                        Purchase History
                    </span>

                    <i class="las la-angle-right"></i>

                </a>


                <a rel="nofollow" href="{{ route('wishlists.index') }}">

                    <span>
                        <i class="lar la-heart"></i>
                        My Wishlist
                    </span>

                    <span class="account-count">
                        {{ $wishlistCount }}
                    </span>

                </a>


                <a href="javascript:void(0)"
                    onclick="add_new_address()">

                    <span>
                        <i class="las la-map-marker-alt"></i>
                        Shipping Address
                    </span>

                    <i class="las la-angle-right"></i>

                </a>

            </div>


            <!-- Address -->
            <div class="dashboard-address-section">

                <div class="address-section-header">

                    <h5>
                        Default Shipping Address
                    </h5>

                    <a href="javascript:void(0)"
                        onclick="add_new_address()">
                        Edit
                    </a>

                </div>


                @if($address != null)

                <div class="modern-address-box">

                    <div class="address-location-icon">
                        <i class="las la-map-marker-alt"></i>
                    </div>

                    <div class="address-content">

                        <strong>
                            {{ $address->address }}
                        </strong>

                        <p>
                            {{ $address->post_code }} -
                            {{ $address->city_id }}
                        </p>

                        <p>
                            {{ $address->country->name }}
                        </p>

                        <p>
                            {{ $address->phone }}
                        </p>

                    </div>

                </div>

                @else

                <div class="modern-empty-address">

                    <i class="las la-map-marker-alt"></i>

                    <h5>
                        No default address
                    </h5>

                    <p>
                        Add your shipping address for faster checkout.
                    </p>

                </div>

                @endif


                <button type="button"
                    class="dashboard-address-btn"
                    onclick="add_new_address()">

                    <i class="las la-plus"></i>
                    Add New Address

                </button>

            </div>

        </div>

    </div>

</div>


<!-- =========================
     WISHLIST
========================= -->
<div class="dashboard-main-card wishlist-dashboard-card">

    <div class="dashboard-card-header">

        <div>
            <span class="card-small-title">
                YOUR FAVORITES
            </span>

            <h3>
                My Wishlist
            </h3>
        </div>

        <a rel="nofollow" href="{{ route('wishlists.index') }}"
            class="dashboard-view-all">
            View All
            <i class="las la-arrow-right"></i>
        </a>

    </div>


    @if(count($wishlists) > 0)

    <div class="dashboard-wishlist-grid">

        @foreach($wishlists->take(4) as $wishlist)

        @if($wishlist->product != null)

        <div class="dashboard-product-card">

            <div class="dashboard-product-image">

                <a href="{{ route('product', $wishlist->product->slug) }}">

                    <img src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}"
                        alt="{{ $wishlist->product->getTranslation('name') }}">

                </a>


                <button type="button"
                    class="wishlist-remove-btn"
                    onclick="removeFromWishlist({{ $wishlist->id }})">

                    <i class="las la-trash"></i>

                </button>

            </div>


            <div class="dashboard-product-content">

                <h5>

                    <a href="{{ route('product', $wishlist->product->slug) }}">

                        {{ $wishlist->product->getTranslation('name') }}

                    </a>

                </h5>


                <div class="dashboard-product-bottom">

                    <span>
                        {{ home_discounted_base_price($wishlist->product) }}
                    </span>

                    @if(home_base_price($wishlist->product) != home_discounted_base_price($wishlist->product))

                    <del>
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

    <div class="dashboard-wishlist-empty">

        <i class="lar la-heart"></i>

        <h5>
            Your wishlist is empty
        </h5>

        <p>
            Save your favourite furniture products here.
        </p>

        <a href="{{ route('home') }}">
            Explore Products
        </a>

    </div>

    @endif

</div>

@endsection

@section('modal')
<!-- Wallet Recharge Modal -->
@include('frontend.'.get_setting('homepage_select').'.partials.wallet_modal')
<script type="text/javascript">
    function show_wallet_modal() {
        $('#wallet_modal').modal('show');
    }
</script>

<!-- Address modal Modal -->
@include('frontend.'.get_setting('homepage_select').'.partials.address_modal')
@endsection

@section('script')
@if (get_setting('google_map') == 1)
@include('frontend.'.get_setting('homepage_select').'.partials.google_map')
@endif
@endsection
<style>
    /* =========================================
   DASHBOARD VARIABLES
========================================= */

    .customer-dashboard-header,
    .dashboard-stat-card,
    .dashboard-main-card {
        font-family: inherit;
    }


    /* =========================================
   DASHBOARD HEADER
========================================= */

    .customer-dashboard-header {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 210px;
        padding: 38px 42px;
        margin-bottom: 24px;
        overflow: hidden;
        background: #685b4e;
        border: 1px solid #eee1d4;
        border-radius: 20px;
    }

    .dashboard-header-content {
        position: relative;
        z-index: 2;
    }

    .dashboard-small-title {
        display: inline-block;
        margin-bottom: 8px;

        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;

        color: #fff;
    }

    .dashboard-header-content h1 {
        margin: 0 0 10px;

        font-size: 32px;
        line-height: 1.2;
        font-weight: 700;

        color: #fff;
    }

    .dashboard-header-content p {
        margin: 0 0 22px;

        font-size: 14px;
        color: #fff;
    }

    .dashboard-header-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dashboard-primary-btn,
    .dashboard-secondary-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        padding: 11px 18px;

        border-radius: 9px;

        font-size: 13px;
        font-weight: 600;

        text-decoration: none !important;

        transition: all .25s ease;
    }

    .dashboard-primary-btn {
        background: #a96839;
        color: #fff !important;
    }

    .dashboard-primary-btn:hover {
        background: #8f542d;
        transform: translateY(-2px);
    }

    .dashboard-secondary-btn {
        background: #fff;
        color: #a96839 !important;
        border: 1px solid #d9b99b;
    }

    .dashboard-secondary-btn:hover {
        background: #fff8f2;
        transform: translateY(-2px);
    }

    .dashboard-header-icon {
        position: absolute;

        right: 55px;
        bottom: -25px;

        width: 150px;
        height: 150px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: rgba(255, 255, 255, .45);

        color: #b77a4b;

        font-size: 80px;

        opacity: .65;
    }


    /* =========================================
   STAT CARDS
========================================= */

    .dashboard-stats {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 20px;

        margin-bottom: 24px;
    }

    .dashboard-stat-card {
        display: flex;
        align-items: center;

        min-height: 145px;

        padding: 22px;

        background: #fff;

        border: 1px solid #eee8e2;

        border-radius: 15px;

        box-shadow: 0 4px 16px rgba(45, 33, 26, .045);

        transition: all .25s ease;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-3px);

        box-shadow:
            0 10px 25px rgba(45, 33, 26, .08);
    }

    .stat-icon-wrap {
        width: 55px;
        height: 55px;

        min-width: 55px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 15px;

        border-radius: 12px;

        font-size: 24px;
    }

    .stat-cart {
        color: #d94c43;
        background: #fff0ef;
    }

    .stat-wishlist {
        color: #3d8ce8;
        background: #edf5ff;
    }

    .stat-orders {
        color: #71a953;
        background: #eff8e9;
    }

    .stat-address {
        color: #e9924d;
        background: #fff3e8;
    }

    .stat-info {
        min-width: 0;
    }

    .stat-label {
        display: block;

        margin-bottom: 3px;

        font-size: 12px;
        font-weight: 500;

        color: #7b7068;
    }

    .stat-info h3 {
        margin: 0 0 5px;

        font-size: 27px;
        line-height: 1;

        font-weight: 700;

        color: #211b17;
    }

    .stat-info a {
        display: inline-flex;
        align-items: center;
        gap: 3px;

        font-size: 11px;
        font-weight: 600;

        color: #a96839 !important;

        text-decoration: none !important;
    }

    .stat-info a i {
        font-size: 13px;
    }


    /* =========================================
   MAIN CARDS
========================================= */

    .dashboard-main-row {
        margin-left: -10px;
        margin-right: -10px;
    }

    .dashboard-main-row>[class*="col-"] {
        padding-left: 10px;
        padding-right: 10px;
    }

    .dashboard-main-card {
        height: 100%;

        padding: 24px;

        background: #fff;

        border: 1px solid #eee8e2;

        border-radius: 15px;

        box-shadow:
            0 4px 16px rgba(45, 33, 26, .045);
    }

    .dashboard-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding-bottom: 17px;

        margin-bottom: 3px;

        border-bottom: 1px solid #eee8e2;
    }

    .card-small-title {
        display: block;

        margin-bottom: 4px;

        font-size: 10px;
        font-weight: 700;
        letter-spacing: 1.3px;

        color: #a96839;
    }

    .dashboard-card-header h3 {
        margin: 0;

        font-size: 20px;
        font-weight: 700;

        color: #201b17;
    }

    .dashboard-view-all {
        display: inline-flex;
        align-items: center;
        gap: 4px;

        font-size: 12px;
        font-weight: 600;

        color: #a96839 !important;

        text-decoration: none !important;
    }

    .dashboard-view-all:hover {
        color: #814b28 !important;
    }


    /* =========================================
   RECENT ORDERS
========================================= */

    .recent-order-item {
        display: flex;

        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 17px 0;

        border-bottom: 1px solid #f0ebe7;
    }

    .recent-order-item:last-child {
        border-bottom: none;
    }

    .recent-order-left {
        display: flex;
        align-items: center;

        min-width: 0;
    }

    .order-icon {
        width: 47px;
        height: 47px;

        min-width: 47px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 13px;

        border-radius: 11px;

        background: #faf1e9;

        color: #a96839;

        font-size: 21px;
    }

    .order-details {
        min-width: 0;
    }

    .order-details h5 {
        margin: 0 0 5px;

        font-size: 14px;
        font-weight: 700;

        color: #27211d;

        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .order-details p {
        display: flex;
        align-items: center;
        gap: 4px;

        margin: 0;

        font-size: 11px;

        color: #8a7e75;
    }

    .order-details p i {
        font-size: 13px;
    }

    .recent-order-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;

        gap: 6px;

        flex-shrink: 0;
    }

    .order-status {
        display: inline-flex;
        align-items: center;

        padding: 4px 10px;

        border-radius: 30px;

        background: #edf8e8;

        color: #639444;

        font-size: 10px;
        font-weight: 600;

        text-transform: capitalize;
    }

    .recent-order-right strong {
        font-size: 13px;
        font-weight: 700;

        color: #29221d;
    }


    /* =========================================
   EMPTY ORDERS
========================================= */

    .dashboard-empty-state {
        padding: 35px 15px;

        text-align: center;
    }

    .dashboard-empty-state img {
        width: 110px;

        margin-bottom: 12px;
    }

    .dashboard-empty-state h5 {
        margin: 0 0 5px;

        font-size: 15px;
        font-weight: 700;

        color: #302721;
    }

    .dashboard-empty-state p {
        margin-bottom: 15px;

        font-size: 12px;

        color: #82776f;
    }

    .empty-state-btn {
        display: inline-flex;

        padding: 9px 17px;

        border-radius: 8px;

        background: #a96839;

        color: #fff !important;

        font-size: 12px;
        font-weight: 600;

        text-decoration: none !important;
    }


    /* =========================================
   ACCOUNT CARD
========================================= */

    .account-card {
        padding-bottom: 20px;
    }

    .account-card-icon {
        width: 38px;
        height: 38px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 10px;

        background: #f7ede5;

        color: #a96839;

        font-size: 18px;
    }

    .account-user-box {
        display: flex;
        align-items: center;

        padding: 18px 0;

        border-bottom: 1px solid #eee8e2;
    }

    .account-avatar {
        width: 48px;
        height: 48px;

        min-width: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 12px;

        border-radius: 50%;

        background: #f3eee9;

        color: #a96839;

        font-size: 23px;
    }

    .account-user-box h5 {
        margin: 0 0 3px;

        font-size: 14px;
        font-weight: 700;

        color: #29221e;
    }

    .account-user-box p {
        margin: 0;

        max-width: 190px;

        font-size: 11px;

        color: #81766f;

        overflow: hidden;
        text-overflow: ellipsis;
    }


    /* =========================================
   ACCOUNT LINKS
========================================= */

    .account-links {
        padding: 7px 0;

        border-bottom: 1px solid #eee8e2;
    }

    .account-links a {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 10px 0;

        color: #4c423b !important;

        font-size: 12px;
        font-weight: 500;

        text-decoration: none !important;
    }

    .account-links a:hover {
        color: #a96839 !important;
    }

    .account-links a span:first-child {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .account-links a i {
        color: #a96839;

        font-size: 16px;
    }

    .account-links a>i {
        font-size: 13px;
        color: #aaa;
    }

    .account-count {
        width: 22px;
        height: 22px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #f7ede5;

        color: #a96839;

        font-size: 10px;
        font-weight: 700;
    }


    /* =========================================
   ADDRESS
========================================= */

    .dashboard-address-section {
        padding-top: 18px;
    }

    .address-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        margin-bottom: 12px;
    }

    .address-section-header h5 {
        margin: 0;

        font-size: 13px;
        font-weight: 700;

        color: #2b241f;
    }

    .address-section-header a {
        font-size: 11px;
        font-weight: 600;

        color: #a96839 !important;

        text-decoration: none !important;
    }

    .modern-address-box {
        display: flex;

        padding: 14px;

        background: #fffaf6;

        border: 1px dashed #d8bda5;

        border-radius: 11px;
    }

    .address-location-icon {
        width: 34px;
        height: 34px;

        min-width: 34px;

        display: flex;
        align-items: center;
        justify-content: center;

        margin-right: 10px;

        border-radius: 8px;

        background: #f5e8dd;

        color: #a96839;

        font-size: 17px;
    }

    .address-content {
        min-width: 0;
    }

    .address-content strong {
        display: block;

        margin-bottom: 4px;

        font-size: 11px;
        line-height: 1.5;

        color: #43362e;
    }

    .address-content p {
        margin: 2px 0;

        font-size: 10px;

        color: #796d64;
    }

    .modern-empty-address {
        padding: 22px 15px;

        text-align: center;

        background: #fffaf6;

        border: 1px dashed #d8bda5;

        border-radius: 11px;
    }

    .modern-empty-address>i {
        display: block;

        margin-bottom: 5px;

        color: #a96839;

        font-size: 30px;
    }

    .modern-empty-address h5 {
        margin: 0 0 4px;

        font-size: 13px;
        font-weight: 700;

        color: #46382f;
    }

    .modern-empty-address p {
        margin: 0;

        font-size: 10px;
        line-height: 1.5;

        color: #81766d;
    }

    .dashboard-address-btn {
        width: 100%;

        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;

        margin-top: 10px;
        padding: 10px;

        border: none;
        border-radius: 8px;

        background: #a96839;

        color: #fff;

        font-size: 11px;
        font-weight: 600;

        cursor: pointer;

        transition: all .25s ease;
    }

    .dashboard-address-btn:hover {
        background: #8f542d;
    }


    /* =========================================
   WISHLIST
========================================= */

    .wishlist-dashboard-card {
        margin-top: 0;
    }

    .dashboard-wishlist-grid {
        display: grid;

        grid-template-columns:
            repeat(4, minmax(0, 1fr));

        gap: 18px;
    }

    .dashboard-product-card {
        overflow: hidden;

        background: #fff;

        border: 1px solid #eee8e2;

        border-radius: 12px;

        transition: all .25s ease;
    }

    .dashboard-product-card:hover {
        transform: translateY(-3px);

        box-shadow:
            0 10px 25px rgba(45, 33, 26, .08);
    }

    .dashboard-product-image {
        position: relative;

        height: 190px;

        padding: 12px;

        background: #faf8f6;

        overflow: hidden;
    }

    .dashboard-product-image a {
        width: 100%;
        height: 100%;

        display: block;
    }

    .dashboard-product-image img {
        width: 100%;
        height: 100%;

        object-fit: contain;

        transition: transform .3s ease;
    }

    .dashboard-product-card:hover img {
        transform: scale(1.04);
    }

    .wishlist-remove-btn {
        position: absolute;

        top: 10px;
        right: 10px;

        width: 30px;
        height: 30px;

        display: flex;
        align-items: center;
        justify-content: center;

        border: none;
        border-radius: 50%;

        background: #fff;

        color: #a96839;

        box-shadow: 0 3px 10px rgba(0, 0, 0, .08);

        cursor: pointer;

        transition: all .2s ease;
    }

    .wishlist-remove-btn:hover {
        background: #a96839;
        color: #fff;
    }

    .dashboard-product-content {
        padding: 14px;
    }

    .dashboard-product-content h5 {
        height: 40px;

        margin: 0 0 10px;

        font-size: 13px;
        line-height: 1.5;
        font-weight: 600;

        overflow: hidden;
    }

    .dashboard-product-content h5 a {
        color: #332a24 !important;

        text-decoration: none !important;
    }

    .dashboard-product-content h5 a:hover {
        color: #a96839 !important;
    }

    .dashboard-product-bottom {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .dashboard-product-bottom span {
        color: #a96839;

        font-size: 13px;
        font-weight: 700;
    }

    .dashboard-product-bottom del {
        color: #999;

        font-size: 11px;
    }


    /* =========================================
   EMPTY WISHLIST
========================================= */

    .dashboard-wishlist-empty {
        padding: 40px 15px;

        text-align: center;
    }

    .dashboard-wishlist-empty>i {
        display: block;

        margin-bottom: 10px;

        color: #c7b5a6;

        font-size: 50px;
    }

    .dashboard-wishlist-empty h5 {
        margin-bottom: 5px;

        font-size: 16px;
        font-weight: 700;

        color: #302721;
    }

    .dashboard-wishlist-empty p {
        margin-bottom: 15px;

        font-size: 12px;

        color: #81766f;
    }

    .dashboard-wishlist-empty a {
        display: inline-block;

        padding: 9px 18px;

        border-radius: 8px;

        background: #a96839;

        color: #fff !important;

        font-size: 12px;
        font-weight: 600;

        text-decoration: none !important;
    }


    /* =========================================
   TABLET
========================================= */

    @media (max-width: 1199px) {

        .dashboard-stats {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));
        }

        .dashboard-wishlist-grid {
            grid-template-columns:
                repeat(3, minmax(0, 1fr));
        }

    }


    /* =========================================
   MOBILE
========================================= */

    @media (max-width: 767px) {

        .customer-dashboard-header {
            min-height: auto;

            padding: 26px 22px;

            border-radius: 15px;
        }

        .dashboard-header-content h1 {
            font-size: 25px;
        }

        .dashboard-header-content p {
            font-size: 12px;

            line-height: 1.6;
        }

        .dashboard-header-icon {
            display: none;
        }

        .dashboard-stats {
            grid-template-columns:
                1fr;

            gap: 12px;
        }

        .dashboard-stat-card {
            min-height: 110px;

            padding: 18px;
        }

        .stat-icon-wrap {
            width: 48px;
            height: 48px;

            min-width: 48px;

            font-size: 21px;
        }

        .stat-info h3 {
            font-size: 24px;
        }

        .dashboard-main-card {
            padding: 18px;
        }

        .dashboard-card-header h3 {
            font-size: 17px;
        }

        .recent-order-item {
            align-items: flex-start;
        }

        .recent-order-right {
            align-items: flex-end;
        }

        .dashboard-wishlist-grid {
            grid-template-columns:
                repeat(2, minmax(0, 1fr));

            gap: 10px;
        }

        .dashboard-product-image {
            height: 150px;
        }

    }


    /* =========================================
   SMALL MOBILE
========================================= */

    @media (max-width: 480px) {

        .dashboard-header-actions {
            flex-direction: column;
        }

        .dashboard-primary-btn,
        .dashboard-secondary-btn {
            width: 100%;
        }

        .recent-order-item {
            flex-direction: column;
        }

        .recent-order-right {
            width: 100%;

            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-wishlist-grid {
            grid-template-columns: 1fr;
        }

        .dashboard-product-image {
            height: 200px;
        }

    }
</style>