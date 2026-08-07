@extends('frontend.layouts.user_panel')

@section('panel_content')
<div class="dashboard-banner">

    <div class="banner-content">

        <h2>
            Welcome Back,
            {{ Auth::user()->name }}
        </h2>

        <p>
            Manage your furniture orders, wishlist and account.
        </p>

        <div class="banner-buttons">

            <a href="{{ route('home') }}" class="btn-shop">
                Continue Shopping
            </a>

            <a href="{{ route('purchase_history.index') }}" class="btn-order">
                View Orders
            </a>

        </div>

    </div>

    {{-- <div class="banner-image">

        <img src="{{ asset('assets/images/contact-banner.jpg') }}">

</div>--}}

</div>

<div class="row mt-4">

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon red">
                <i class="las la-shopping-cart"></i>
            </div>
            <div class="stat-content">
                <h6>Products in Cart</h6>
                @php
                $cart = get_user_cart();
                @endphp
                <h2>{{ count($cart) > 0 ? sprintf("%02d", count($cart)) : 0 }}</h2>
                <a href="{{ route('cart') }}">View Cart →</a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon blue">
                <i class="lar la-heart"></i>
            </div>
            <div class="stat-content">
                <h6>Products in Wishlist</h6>
                <h2>{{ count(Auth::user()->wishlists) > 0 ? sprintf("%02d", count(Auth::user()->wishlists)) : 0 }}</h2>
                <a href="{{ route('wishlists.index') }}">View Wishlist →</a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon green">
                <i class="las la-box-open"></i>
            </div>
            <div class="stat-content">
                <h6>Total Products Ordered</h6>
                @php
                $total = get_user_total_ordered_products();
                @endphp
                <h2>{{ $total > 0 ? sprintf("%02d", $total) : 0 }}</h2>
                <a href="{{ route('purchase_history.index') }}">View Orders →</a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon orange">
                <i class="las la-map-marker-alt"></i>
            </div>
            <div class="stat-content">
                <h6>Saved Addresses</h6>
                <h2>{{ Auth::user()->addresses ? Auth::user()->addresses->count() : 0 }}</h2>
                <a href="javascript:void(0)" onclick="add_new_address()">Manage Addresses →</a>
            </div>
        </div>
    </div>

</div>

<div class="row mt-2">

    <div class="col-lg-8 mb-4">
        <div class="dashboard-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">Recent Orders</h4>
                <a href="{{ route('purchase_history.index') }}" class="view-all-link">View All</a>
            </div>

            @php
            $orders = Auth::user()->orders()->latest()->take(3)->get();
            @endphp

            @forelse($orders as $order)
            <div class="order-row">
                <div class="order-info">
                    <div class="order-thumb">
                        <i class="las la-box"></i>
                    </div>
                    <div>
                        <h5>Order #{{ $order->code }}</h5>
                        <p>{{ date('d M Y', strtotime($order->created_at)) }}</p>
                    </div>
                </div>

                <div class="order-meta">
                    <span class="order-status">{{ ucfirst($order->delivery_status) }}</span>
                    <strong>{{ single_price($order->grand_total) }}</strong>
                </div>
            </div>
            @empty
            <div class="empty-box">
                <img src="{{ static_asset('assets/img/nothing.svg') }}" alt="No orders">
                <h5>No orders found</h5>
            </div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="dashboard-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="section-title mb-0">Default Shipping Address</h4>
                <a href="javascript:void(0)" class="view-all-link" onclick="add_new_address()">Edit</a>
            </div>

            @php
            $address = null;
            if (Auth::user()->addresses != null) {
            $address = Auth::user()->addresses->where('set_default', 1)->first();
            }
            @endphp

            @if($address != null)
            <div class="address-box">
                <p><strong>{{ $address->address }}</strong></p>
                <p>{{ $address->post_code }} - {{ $address->city_id }}</p>
                <p>{{ $address->country->name }}</p>
                <p>{{ $address->phone }}</p>
            </div>
            @else
            <div class="address-empty">
                <i class="las la-map-marker-alt"></i>
                <h5>No default address added</h5>
                <p>Add your shipping address for faster checkout.</p>
            </div>
            @endif

            <button class="btn-address" onclick="add_new_address()">
                <i class="la la-plus"></i> Add New Address
            </button>
        </div>
    </div>

</div>
<div class="row gutters-16 mt-2">

    <!-- count summary -->
    {{-- <div class="col-xl-4 col-md-6 mb-4">
            <div class="px-4 bg-white border h-100">
                <!-- Cart summary -->
                <div class="d-flex align-items-center py-4 border-bottom">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
                        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -427)">
                        <path id="Path_32314" data-name="Path 32314" d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z" transform="translate(1367 427)" fill="#d43533"/>
                        <g id="Group_24770" data-name="Group 24770" transform="translate(1382.999 443)">
                            <path id="Path_25692" data-name="Path 25692" d="M294.507,424.89a2,2,0,1,0,2,2A2,2,0,0,0,294.507,424.89Zm0,3a1,1,0,1,1,1-1A1,1,0,0,1,294.507,427.89Z" transform="translate(-289.508 -412.89)" fill="#fff"/>
                            <path id="Path_25693" data-name="Path 25693" d="M302.507,424.89a2,2,0,1,0,2,2A2,2,0,0,0,302.507,424.89Zm0,3a1,1,0,1,1,1-1A1,1,0,0,1,302.507,427.89Z" transform="translate(-289.508 -412.89)" fill="#fff"/>
                            <g id="LWPOLYLINE">
                            <path id="Path_25694" data-name="Path 25694" d="M305.43,416.864a1.5,1.5,0,0,0-1.423-1.974h-9a.5.5,0,0,0,0,1h9a.467.467,0,0,1,.129.017.5.5,0,0,1,.354.611l-1.581,6a.5.5,0,0,1-.483.372h-7.462a.5.5,0,0,1-.489-.392l-1.871-8.433a1.5,1.5,0,0,0-1.465-1.175h-1.131a.5.5,0,1,0,0,1h1.043a.5.5,0,0,1,.489.391l1.871,8.434a1.5,1.5,0,0,0,1.465,1.175h7.55a1.5,1.5,0,0,0,1.423-1.026Z" transform="translate(-289.508 -412.89)" fill="#fff"/>
                            </g>
                        </g>
                        </g>
                    </svg>
                    <div class="ml-3 d-flex flex-column justify-content-between">
                        @php
                            $cart = get_user_cart();
                        @endphp
                        <span class="fs-20 fw-700 mb-1">{{ count($cart) > 0 ? sprintf("%02d", count($cart)) : 0 }}</span>
    <span class="fs-14 fw-400 text-secondary">{{ translate('Products in Cart') }}</span>
</div>
</div>

<!-- Wishlist summary -->
<div class="d-flex align-items-center py-4 border-bottom">
    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -499)">
            <path id="Path_32309" data-name="Path 32309" d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z" transform="translate(1367 499)" fill="#3490f3" />
            <g id="Group_24772" data-name="Group 24772" transform="translate(1383 515)">
                <g id="Wooden" transform="translate(0 1)">
                    <path id="Path_25676" data-name="Path 25676" d="M290.82,413.6a4.5,4.5,0,0,0-6.364,0l-.318.318-.318-.318a4.5,4.5,0,1,0-6.364,6.364l6.046,6.054a.9.9,0,0,0,1.272,0l6.046-6.054A4.5,4.5,0,0,0,290.82,413.6Zm-.707,5.657-5.975,5.984-5.975-5.984a3.5,3.5,0,1,1,4.95-4.95l.389.389a.9.9,0,0,0,1.272,0l.389-.389a3.5,3.5,0,1,1,4.95,4.95Z" transform="translate(-276.138 -412.286)" fill="#fff" />
                </g>
                <rect id="Rectangle_1603" data-name="Rectangle 1603" width="16" height="16" transform="translate(0)" fill="none" />
            </g>
        </g>
    </svg>
    <div class="ml-3 d-flex flex-column justify-content-between">
        <span class="fs-20 fw-700 mb-1">{{ count(Auth::user()->wishlists) > 0 ? sprintf("%02d", count(Auth::user()->wishlists)) : 0 }}</span>
        <span class="fs-14 fw-400 text-secondary">{{ translate('Products in Wishlist') }}</span>
    </div>
</div>

<!-- Order summary -->
<div class="d-flex align-items-center py-4">
    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48">
        <g id="Group_25000" data-name="Group 25000" transform="translate(-1367 -576)">
            <path id="Path_32315" data-name="Path 32315" d="M24,0A24,24,0,1,1,0,24,24,24,0,0,1,24,0Z" transform="translate(1367 576)" fill="#85b567" />
            <path id="_2e746ddacacf202af82cf4480bae6173" data-name="2e746ddacacf202af82cf4480bae6173" d="M11.483,3h-.009a.308.308,0,0,0-.1.026L4.26,6.068A.308.308,0,0,0,4,6.376V15.6a.308.308,0,0,0,.026.127v0l.009.017a.308.308,0,0,0,.157.147l7.116,3.042a.338.338,0,0,0,.382,0L18.8,15.9a.308.308,0,0,0,.189-.243q0-.008,0-.017s0-.01,0-.015,0-.01,0-.015,0,0,0,0V6.376a.308.308,0,0,0-.255-.306L11.632,3.031l-.007,0a.308.308,0,0,0-.05-.017l-.009,0-.022,0h-.062Zm.014.643L13,4.287,6.614,7.02,6.6,7.029,5.088,6.383,11.5,3.643Zm2.29.979,1.829.782L9.108,8.188a.414.414,0,0,0-.186.349v3.291l-.667-1a.308.308,0,0,0-.393-.1l-.786.392V7.493l6.712-2.87ZM16.4,5.738l1.509.645L11.5,9.124,9.99,8.48l6.39-2.733.018-.009ZM4.615,6.85l1.846.789v3.975a.308.308,0,0,0,.445.275l.987-.494,1.064,1.595v0a.308.308,0,0,0,.155.14h0l.027.009a.308.308,0,0,0,.057.012h.036l.036,0,.025,0,.018,0,.015,0a.308.308,0,0,0,.05-.022h0a.308.308,0,0,0,.156-.309V8.955l1.654.707v8.56L4.615,15.411Zm13.765,0v8.56L11.8,18.223V9.662Z" transform="translate(1379.5 588.5)" fill="#fff" stroke="#fff" stroke-width="0.25" fill-rule="evenodd" />
        </g>
    </svg>
    <div class="ml-3 d-flex flex-column justify-content-between">
        @php
        $total = get_user_total_ordered_products();
        @endphp
        <span class="fs-20 fw-700 mb-1">{{ $total > 0 ? sprintf("%02d", $total) : 0 }}</span>
        <span class="fs-14 fw-400 text-secondary">{{ translate('Total Products Ordered') }}</span>
    </div>
</div>

</div>
</div>--}}

<!-- Purchased Package -->
@if (get_setting('classified_product'))
<div class="col-xl-4 col-md-6 mb-4">
    <div class="p-4 border h-100">
        <h6 class="fw-700 mb-3 text-dark">{{ translate('Purchased Package') }}</h6>
        @php
        $customer_package = get_single_customer_package(Auth::user()->customer_package_id);
        @endphp
        @if($customer_package != null)
        <img src="{{ uploaded_asset($customer_package->logo) }}" class="img-fluid mb-4 h-70px"
            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
        <p class="fs-14 fw-700 mb-3 text-primary">{{ translate('Current Package') }}: {{ $customer_package->getTranslation('name') }}</p>
        <p class="mb-2 d-flex justify-content-between">
            <span class="text-secondary">{{ translate('Product Upload') }}</span>
            <span class="fw-700">{{ $customer_package->product_upload }} {{ translate('Times')}}</span>
        </p>
        <p class="mb-3 d-flex justify-content-between">
            <span class="text-secondary">{{ translate('Product Upload Remains') }}</span>
            <span class="fw-700">{{ Auth::user()->remaining_uploads }} {{ translate('Times')}}</span>
        </p>
        @else
        <span class="fs-14 fw-700 mb-4 text-primary">{{translate('Package Not Found')}}</span>
        @endif
        <a href="{{ route('customer_packages_list_show') }}" class="btn btn-primary btn-block fs-14 fw-500" style="border-radius: 25px;">{{ translate('Upgrade Package') }}</a>
    </div>
</div>
@endif

<!-- Default Shipping Address -->
<!--<div class="col-xl-4 col-md-6 mb-4">
            <div class="p-4 border h-100">
                <h6 class="fw-700 mb-3 text-dark">{{ translate('Default Shipping Address') }}</h6>
                @if(Auth::user()->addresses != null)
                    @php
                        $address = Auth::user()->addresses->where('set_default', 1)->first();
                    @endphp
                    @if($address != null)
                        <ul class="list-unstyled mb-5">
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->address }},</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->post_code }} - {{ $address->city_id }},</span></li>
                            {{--<li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->state->name }},</span></li>--}}
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->country->name }}.</span></li>
                            <li class="fs-14 fw-400 text-derk pb-1"><span>{{ $address->phone }}</span></li>
                        </ul>
                    @endif
                @endif
                <button class="btn btn-dark btn-block fs-14 fw-500" onclick="add_new_address()" style="border-radius: 25px;">
                    <i class="la la-plus fs-18 fw-700 mr-2"></i>
                    {{ translate('Add New Address') }}
                </button>
            </div>
        </div>-->>

</div>

<div class="row align-items-center mb-2 mt-1">
    <div class="col-6">
        <h3 class=" mb-0 fs-14 fs-md-16 fw-700 text-dark">{{ translate('My Wishlist')}}</h3>
    </div>
    <div class="col-6 text-right">
        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary" href="{{ route('wishlists.index') }}">{{ translate('View All') }}</a>
    </div>
</div>
@php
$wishlists = get_user_wishlist();
@endphp
@if (count($wishlists) > 0)
<div class="row row-cols-xxl-5 row-cols-xl-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-2 gutters-16 border-top border-left mx-1 mx-md-0 mb-4">
    @foreach($wishlists->take(5) as $key => $wishlist)
    @if ($wishlist->product != null)
    <div class="aiz-card-box col py-3 text-center border-right border-bottom has-transition hov-shadow-out z-1" id="wishlist_{{ $wishlist->id }}">
        <div class="position-relative h-140px h-md-200px img-fit overflow-hidden mb-3">
            <!-- Image -->
            <a href="{{ route('product', $wishlist->product->slug) }}" class="d-block h-100">
                <img src="{{ uploaded_asset($wishlist->product->thumbnail_img) }}" class="lazyload mx-auto img-fit"
                    title="{{ $wishlist->product->getTranslation('name') }}">
            </a>
            <!-- Remove from wishlisht -->
            <div class="absolute-top-right aiz-p-hov-icon">
                <a href="javascript:void(0)" onclick="removeFromWishlist({{ $wishlist->id }})" data-toggle="tooltip" data-title="{{ translate('Remove from wishlist') }}" data-placement="left">
                    <i class="la la-trash"></i>
                </a>
            </div>
            <!-- add to cart -->
            <a class="cart-btn absolute-bottom-left w-100 h-35px aiz-p-hov-icon text-white fs-13 fw-700 d-flex justify-content-center align-items-center"
                href="javascript:void(0)" onclick="showAddToCartModal({{ $wishlist->product->id }})">{{ translate('Add to Basket') }}</a>
        </div>
        <!-- Product Name -->
        <h5 class="fs-14 mb-0 lh-1-5 fw-400 text-truncate-2 mb-3">
            <a href="{{ route('product', $wishlist->product->slug) }}" class="text-reset hov-text-primary"
                title="{{ $wishlist->product->getTranslation('name') }}">{{ $wishlist->product->getTranslation('name') }}</a>
        </h5>
        <!-- Price -->
        <div class="fs-14">
            <span class="fw-600 text-primary">{{ home_discounted_base_price($wishlist->product) }}</span>
            @if(home_base_price($wishlist->product) != home_discounted_base_price($wishlist->product))
            <del class="opacity-60 ml-1">{{ home_base_price($wishlist->product) }}</del>
            @endif
        </div>
    </div>
    @endif
    @endforeach
</div>
@else
<div class="row">
    <div class="col">
        <div class="text-center bg-white p-4 border">
            <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
            <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet")}}</h5>
        </div>
    </div>
</div>
@endif
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
    .dashboard-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
        padding: 34px 40px;
        border-radius: 26px;
        background: linear-gradient(135deg, #FFF8F1, #F6EBDD);
        box-shadow: 0 12px 32px rgba(0, 0, 0, .06);
        margin-bottom: 26px;
        overflow: hidden;
    }

    .banner-content {
        flex: 1;
        max-width: 45%;
    }

    .banner-content h2 {
        font-size: 36px;
        font-weight: 700;
        color: #4B2F1F;
        margin-bottom: 12px;
    }

    .banner-content p {
        font-size: 17px;
        line-height: 1.7;
        color: #6F6258;
        margin-bottom: 24px;
    }

    .banner-buttons {
        display: flex;
        gap: 14px;
        flex-wrap: wrap;
    }

    .btn-shop,
    .btn-order,
    .btn-address {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 13px 24px;
        border-radius: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: .3s ease;
        border: 1px solid transparent;
    }

    .btn-shop {
        background: #A56B3D;
        color: #fff;
    }

    .btn-shop:hover {
        background: #8E5730;
        color: #fff;
    }

    .btn-order {
        background: #fff;
        color: #A56B3D;
        border-color: #CFAE8F;
    }

    .btn-order:hover {
        background: #FBF3EB;
        color: #A56B3D;
    }

    .banner-image {
        flex: 1;
        text-align: right;
    }

    .banner-image img {
        width: 100%;
        max-width: 560px;
        height: auto;
        display: block;
        margin-left: auto;
        border-radius: 22px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 22px;
        background: #fff;
        border: 1px solid #EFE7DE;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
        min-height: 126px;
        transition: .3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 14px 30px rgba(0, 0, 0, .08);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 24px;
        color: #fff;
    }

    .stat-icon.red {
        background: #D9413A;
    }

    .stat-icon.blue {
        background: #3C8DF0;
    }

    .stat-icon.green {
        background: #88B861;
    }

    .stat-icon.orange {
        background: #F39B54;
    }

    .stat-content h6 {
        font-size: 14px;
        color: #6B625B;
        margin-bottom: 4px;
        font-weight: 500;
    }

    .stat-content h2 {
        font-size: 30px;
        font-weight: 700;
        color: #1E1B18;
        margin-bottom: 8px;
    }

    .stat-content a,
    .view-all-link {
        font-size: 13px;
        font-weight: 600;
        color: #A56B3D;
        text-decoration: none;
    }

    .dashboard-card {
        background: #fff;
        border: 1px solid #EFE7DE;
        border-radius: 20px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .05);
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #1E1B18;
    }

    .order-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        padding: 16px 0;
        border-bottom: 1px solid #F1E8DF;
    }

    .order-row:last-child {
        border-bottom: none;
    }

    .order-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .order-thumb {
        width: 58px;
        height: 58px;
        border-radius: 12px;
        background: #F8F0E7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #A56B3D;
        font-size: 26px;
    }

    .order-info h5 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
        color: #2C241E;
    }

    .order-info p {
        font-size: 13px;
        color: #7B6E64;
        margin: 3px 0 0;
    }

    .order-meta {
        text-align: right;
        min-width: 110px;
    }

    .order-status {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 999px;
        background: #EAF6E1;
        color: #5E8E35;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .address-box {
        background: #FFF9F4;
        border: 1px dashed #D8BEA5;
        border-radius: 18px;
        padding: 18px;
        color: #4B2F1F;
        line-height: 1.8;
    }

    .address-empty {
        text-align: center;
        padding: 30px 15px;
        border: 1px dashed #D8BEA5;
        border-radius: 18px;
        background: #FFF9F4;
    }

    .address-empty i {
        font-size: 42px;
        color: #A56B3D;
        margin-bottom: 10px;
        display: block;
    }

    .address-empty h5 {
        font-size: 18px;
        font-weight: 700;
        color: #4B2F1F;
        margin-bottom: 8px;
    }

    .address-empty p {
        color: #7C6F65;
        margin: 0;
    }

    .btn-address {
        width: 100%;
        margin-top: 16px;
        background: #A56B3D;
        color: #fff;
        border: none;
        padding: 14px 20px;
        border-radius: 14px;
        font-weight: 600;
        transition: .3s;
    }

    .btn-address:hover {
        background: #8E5730;
    }

    .empty-box {
        text-align: center;
        padding: 30px 10px;
    }

    .empty-box img {
        max-width: 180px;
        margin-bottom: 12px;
    }

    .empty-box h5 {
        font-size: 16px;
        font-weight: 600;
        color: #4B2F1F;
    }

    @media (max-width: 991px) {
        .dashboard-banner {
            flex-direction: column;
            padding: 26px;
        }

        .banner-content {
            max-width: 100%;
        }

        .banner-image {
            width: 100%;
            text-align: center;
        }

        .banner-image img {
            margin: 0 auto;
        }

        .order-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-meta {
            text-align: left;
        }
    }
</style>