@extends('frontend.layouts.app')

@section('content')

<section class="pt-5 mb-4 cart_tabs">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row gutters-5 sm-gutters-10">

                    <div class="col done">
                        <div class="text-center border border-bottom-6px p-2 text-success">
                            <i class="la-3x mb-2 las la-shopping-bag"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                <a href="{{ url('cart') }}">
                                    {{ translate('1. My Cart') }}
                                </a>
                            </h3>
                        </div>
                    </div>

                    <div class="col done">
                        <div class="text-center border border-bottom-6px p-2 text-success">
                            <i class="la-3x mb-2 las la-map-marker"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                <a href="{{ url('checkout') }}">
                                    {{ translate('2. Shipping info') }}
                                </a>
                            </h3>
                        </div>
                    </div>

                    <div class="col active">
                        <div class="text-center border border-bottom-6px p-2 text-primary">
                            <i class="la-3x mb-2 las la-shipping-fast cart-animate"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">
                                {{ translate('3. Delivery info') }}
                            </h3>
                        </div>
                    </div>

                    <div class="col">
                        <div class="text-center border border-bottom-6px p-2">
                            <i class="la-3x mb-2 opacity-50 las la-wallet"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">
                                {{ translate('4. Payment') }}
                            </h3>
                        </div>
                    </div>

                    <div class="col">
                        <div class="text-center border border-bottom-6px p-2">
                            <i class="la-3x mb-2 opacity-50 las la-clipboard-check"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">
                                {{ translate('5. Confirmation') }}
                            </h3>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xxl-12 col-xl-10">
                <div class="p-0 p-lg-0 bg-white delivery-maincontainer">

                    <form class="form-default" id="delivery-form" action="{{ route('checkout.store_delivery_info') }}"
                        method="POST">

                        @csrf

                        @php

                        $admin_products = [];
                        $seller_products = [];
                        $admin_product_variation = [];
                        $seller_product_variation = [];

                        foreach ($carts as $key => $cartItem) {
                        $product = get_single_product($cartItem['product_id']);

                        $variation = $cartItem['variation'];

                        // Get stock row
                        $stock = $product->stocks->where('variant', $variation)->first();

                        $variant_price = $stock ? $stock->price : 0;

                        $variation_data = [
                        'product_id' => $cartItem['product_id'],
                        'variation' => $variation,
                        'price' => $variant_price,
                        'sku' => $stock ? $stock->sku : '',
                        ];

                        if ($product->added_by == 'admin') {
                        array_push($admin_products, $cartItem['product_id']);

                        $admin_product_variation[] = $variation_data;
                        } else {
                        $product_ids = [];

                        if (isset($seller_products[$product->user_id])) {
                        $product_ids = $seller_products[$product->user_id];
                        }

                        array_push($product_ids, $cartItem['product_id']);

                        $seller_products[$product->user_id] = $product_ids;

                        $seller_product_variation[] = $variation_data;
                        }
                        }

                        $pickup_point_list = [];

                        if (get_setting('pickup_point') == 1) {
                        $pickup_point_list = get_all_pickup_points();
                        }
                        @endphp

                        {{-- SELLER PRODUCTS --}}
                        @if (!empty($seller_products))
                        @foreach ($seller_products as $seller_id => $seller_product)
                        <div class="card border-0 rounded-0 mb-4 checkout-seller-card" style="border-radius:unset !important;box-shadow:none !important; background: transparent;">
                            <div class="card-header py-3 px-0 border-bottom-0 d-flex align-items-center gap-2" style="background: transparent !important;    justify-content: flex-start !important; border:none !important;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 32px; height: 32px; background: #fdf6ed; border: 1px solid #f0e6da;">
                                    <i class="las la-store text-primary" style="color: #b57a45; font-size: 18px;"></i>
                                </div>
                                <h5 class="fs-16 fw-700 text-dark mb-0">
                                    {{ get_shop_by_user_id($seller_id)->name }}
                                </h5>
                                <span class="badge badge-inline bg-light border text-muted fs-11" style="border-color: #f0e6da !important; background-color: #faf8f5 !important; color: #8b5e34 !important;">
                                    {{ translate('Seller') }}
                                </span>
                            </div>

                            <div class="card-body p-0">
                                <div class="mb-4">
                                    @php
                                    $physical = false;
                                    $seller_subtotal = 0;
                                    @endphp

                                    @foreach ($seller_product as $key2 => $productId)
                                    @php
                                    $product = get_single_product($productId);

                                    if ($product->digital == 0) {
                                    $physical = true;
                                    }

                                    $cart = collect($carts)->firstWhere('product_id', $productId);

                                    $price = 0;
                                    $qty = 1;
                                    $hasVariation = false;
                                    $cartItem_addons = [];
                                    $cartItem_attributes = [];
                                    $hasAddons = false;

                                    if ($cart) {
                                    $cartItem_addons = !empty($cart->addons)
                                    ? json_decode($cart->addons, true)
                                    : [];
                                    $cartItem_attributes = !empty($cart->attributes)
                                    ? json_decode($cart->attributes, true)
                                    : [];

                                    $attributeNames = [];
                                    if (is_array($cartItem_attributes)) {
                                    foreach ($cartItem_attributes as $attr) {
                                    if (!empty($attr['attribute_name'])) {
                                    $attributeNames[] = strtolower(
                                    trim($attr['attribute_name']),
                                    );
                                    }
                                    }
                                    }

                                    $variation_string =
                                    $seller_product_variation[$key2]['variation'] ?? '';
                                    $variation_parts = array_map(function ($v) {
                                    return strtolower(
                                    preg_replace('/[^a-zA-Z0-9]/', '', $v),
                                    );
                                    }, explode('-', $variation_string));

                                    // Remove redundant variants that were injected into addons
                                    $cartItem_addons = array_filter($cartItem_addons, function (
                                    $addon,
                                    ) use ($attributeNames, $variation_parts) {
                                    if (
                                    in_array(
                                    strtolower(trim($addon['addon_name'] ?? '')),
                                    $attributeNames,
                                    )
                                    ) {
                                    return false;
                                    }
                                    $addon_value_clean = strtolower(
                                    preg_replace(
                                    '/[^a-zA-Z0-9]/',
                                    '',
                                    $addon['name'] ?? '',
                                    ),
                                    );
                                    if (
                                    !empty($addon_value_clean) &&
                                    in_array($addon_value_clean, $variation_parts)
                                    ) {
                                    return false;
                                    }
                                    return true;
                                    });

                                    $calculated_addon_price = 0;
                                    foreach ($cartItem_addons as $addon) {
                                    $calculated_addon_price += $addon['price'] ?? 0;
                                    }

                                    $base_price =
                                    cart_product_price($cart, $product, false, false);
                                    $price = $base_price + $calculated_addon_price;
                                    $qty = $cart['quantity'];
                                    $row_total = $price * $qty;
                                    $seller_subtotal += $row_total;

                                    $hasVariation = !empty($variation_string);
                                    $hasAddons = !empty($cartItem_addons);
                                    }
                                    @endphp

                                    {{-- DESKTOP VIEW --}}
                                    <div class="d-none d-lg-block delivery-desktop-card-wrapper">
                                        <div class="delivery-desktop-card position-relative">
                                            {{-- Edit and Delete Buttons --}}
                                            @if($cart)
                                            <div class="card-action-btns">
                                                <a href="{{ route('cart.editItem', $cart->id) }}"
                                                    class="btn-action-edit"
                                                    title="{{ translate('Edit options') }}">
                                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                                            stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                            stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                    </svg>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    onclick="removeFromCartView(event, {{ $cart->id }})"
                                                    class="btn-action-delete"
                                                    title="{{ translate('Remove') }}">
                                                    <i class="las la-trash fs-18"></i>
                                                </a>
                                            </div>
                                            @endif

                                            <div class="row align-items-center">

                                                {{-- Product Image, Name & Pricing Breakdown --}}
                                                <div class="col-lg-7 d-flex align-items-start gap-3 min-w-0">
                                                    <img src="{{ get_image($product->thumbnail) }}"
                                                        class="img-fit rounded-3 flex-shrink-0"
                                                        style="width:100px;height:100px;object-fit:cover;"
                                                        alt="{{ $product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    <div class="min-w-0 flex-grow-1" style="margin-left: 15px;">
                                                        <span class="fs-16 fw-700 text-dark d-block delivery-product-name-text">
                                                            {{ $product->getTranslation('name') }}
                                                            @if($hasVariation) <span class="text-muted fs-13">- {{ $seller_product_variation[$key2]['variation'] }}</span> @endif
                                                        </span>

                                                        {{-- Selected Attributes --}}
                                                        @if (!empty($cartItem_attributes) && is_array($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                        <div class="attribute-details mt-2">
                                                            @foreach ($cartItem_attributes as $attribute)
                                                            <span class="d-block fs-12 text-muted">
                                                                {{ $attribute['attribute_name'] ?? '' }}:
                                                                {{ $attribute['option_name'] ?? '' }}
                                                            </span>
                                                            @endforeach
                                                        </div>
                                                        @endif

                                                        {{-- Pricing & Addons breakdown --}}
                                                        <div class="price-breakdown-box p-3 rounded-3 mt-3"
                                                            style="background: #faf8f5; border: 1px solid #f0e6da; border-radius: 8px; max-width: 480px;">
                                                            {{-- Product Price --}}
                                                            <div class="d-flex justify-content-between align-items-center  fs-13">
                                                                <span class="text-secondary">{{ translate('Product Price') }}</span>
                                                                <span class="fw-600 text-dark">
                                                                    {{ single_price($base_price ?? 0) }}
                                                                    @if ($qty > 1)
                                                                    <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                                        ({{ single_price(($base_price ?? 0) * $qty) }} total)
                                                                    </small>
                                                                    @endif
                                                                </span>
                                                            </div>

                                                            {{-- Addons Price --}}
                                                            @if ($calculated_addon_price > 0)
                                                            <div class="d-flex justify-content-between align-items-center  fs-13 border-top pt-2">
                                                                <span class="text-secondary">{{ translate('Add-on Price') }}</span>
                                                                <span class="fw-600 text-dark">
                                                                    +{{ single_price($calculated_addon_price) }}
                                                                    @if ($qty > 1)
                                                                    <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                                        (+{{ single_price($calculated_addon_price * $qty) }} total)
                                                                    </small>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            @endif

                                                            {{-- Addons Details list --}}
                                                            @if ($hasAddons)
                                                            @php
                                                            $toggleId = 'addonCollapseDeliveryDesktop' . ($seller_id ?? '') . ($key2 ?? '') . ($cart->id ?? uniqid());
                                                            @endphp
                                                            <div class="border-top pt-2 mt-2">
                                                                <button type="button"
                                                                    class="addon-toggle-btn d-flex justify-content-between align-items-center w-100 text-left"
                                                                    data-toggle="collapse"
                                                                    data-target="#{{ $toggleId }}"
                                                                    aria-expanded="false"
                                                                    aria-controls="{{ $toggleId }}">
                                                                    <span class="fw-600 fs-11 text-uppercase">{{ translate('Selected Add-ons') }}</span>
                                                                    <i class="las la-angle-down addon-arrow"></i>
                                                                </button>
                                                                <div class="collapse addon-details mt-2" id="{{ $toggleId }}">
                                                                    @foreach ($cartItem_addons as $addon)
                                                                    @php
                                                                    $addonImage = $addon['image'] ?? ($addon['img'] ?? ($addon['image_url'] ?? ''));
                                                                    $addonImageSrc = $addonImage
                                                                    ? (\Illuminate\Support\Str::startsWith($addonImage, ['http://', 'https://', 'data:'])
                                                                    ? $addonImage
                                                                    : (str_starts_with(ltrim($addonImage, '/'), 'addon/') || str_starts_with(ltrim($addonImage, '/'), 'addons/')
                                                                    ? asset('public/' . ltrim($addonImage, '/'))
                                                                    : asset(ltrim($addonImage, '/'))))
                                                                    : '';
                                                                    @endphp
                                                                    <div class="d-flex justify-content-between align-items-center fs-12 text-secondary py-1 addon-row">
                                                                        <span class="addon-name-text hh">
                                                                            @if ($addonImageSrc)
                                                                            <img src="{{ $addonImageSrc }}"
                                                                                alt="{{ $addon['name'] ?? 'Addon' }}"
                                                                                style="width:24px;height:24px;object-fit:cover;border-radius:4px;border:1px solid #e5e5e5;margin-right:6px;vertical-align:middle;">
                                                                            @else
                                                                            •
                                                                            @endif
                                                                            <strong class="text-black"> {{ $addon['addon_name'] ?? '' }}</strong>
                                                                            @if (isset($addon['name']))
                                                                            | {{ $addon['name'] }}
                                                                            @endif
                                                                        </span>
                                                                        <span class="fw-600 addon-price-text">
                                                                            @if (isset($addon['price']) && floatval($addon['price']) > 0)
                                                                            +£{{ number_format($addon['price'], 2) }}
                                                                            @endif
                                                                        </span>
                                                                    </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            @endif
                                                        </div>

                                                        @if ($product->dispatch_time)
                                                        <div class="mt-2 fs-12 text-muted">
                                                            <i class="las la-clock fs-14"></i>
                                                            <span class="fw-600">{{ translate('Dispatch Time') }}:</span>
                                                            {{ $product->dispatch_time }}
                                                        </div>
                                                        @endif

                                                        @php
                                                        $productShippingCharges = getProductShippingCharges($product);
                                                        $productServices = $product->checkoutServices()->where('status', 1)->orderBy('sort_order')->get();
                                                        $selectedCartServiceIds = [];
                                                        if ($cart && !empty($cart->services)) {
                                                        $cartServices = json_decode($cart->services, true);
                                                        if (is_array($cartServices)) {
                                                        foreach ($cartServices as $cs) {
                                                        $selectedCartServiceIds[] = $cs['id'];
                                                        }
                                                        }
                                                        }
                                                        @endphp

                                                        {{-- Shipping Charges list (desktop) --}}
                                                        @if($productShippingCharges->count() > 0)
                                                        <div class="mt-3 fs-12 text-secondary d-flex flex-wrap align-items-center gap-2">
                                                            <span class="fw-700 text-uppercase fs-10 text-muted" style="letter-spacing: 0.5px; white-space: nowrap;">{{ translate('Shipping') }}:</span>
                                                            @foreach($productShippingCharges as $charge)
                                                            <span class="shipping-badge border">
                                                                <i class="las la-truck text-muted" style="font-size: 14px;"></i>
                                                                {{ $charge->name }}: <strong class="text-primary" style="color: #b57a45 !important;">{{ single_price($charge->price) }}</strong>
                                                            </span>
                                                            @endforeach
                                                        </div>
                                                        @endif

                                                        {{-- Available Services checklist (desktop) --}}
                                                        @if($productServices->count() > 0)
                                                        <div class="mt-3">
                                                            <span class="fw-700 fs-10 text-uppercase text-secondary d-block mb-2" style="letter-spacing: 0.5px;">{{ translate('Additional Services') }}</span>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @foreach($productServices as $service)
                                                                <label class="aiz-megabox mb-0">
                                                                    <input type="checkbox" name="selected_services[{{ $cart->id }}][]"
                                                                        value="{{ $service->id }}"
                                                                        class="service-checkbox"
                                                                        data-price="{{ $service->price }}"
                                                                        @if (in_array($service->id, $selectedCartServiceIds)) checked @endif>
                                                                    <span class="d-flex aiz-megabox-elem p-2 align-items-center custom-service-pill"
                                                                        @if(!empty($service->description)) title="{{ $service->description }}" @endif>
                                                                        <span class="aiz-rounded-check flex-shrink-0" style="margin-right: 6px; width: 14px; height: 14px;"></span>
                                                                        <span class="fs-12 fw-700 text-dark" style="white-space: nowrap;">
                                                                            {{ $service->name }} ({{ single_price($service->price) }})
                                                                        </span>
                                                                    </span>
                                                                </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                {{-- Quantity --}}
                                                <div class="col-lg-2 d-flex flex-column align-items-center justify-content-center text-center">
                                                    <span class="fs-12 text-secondary mb-2 text-uppercase fw-600" style="letter-spacing: 0.5px;">{{ translate('Quantity') }}</span>
                                                    @if ($product->auction_product == 0)
                                                    <div class="quantity-group" style="max-width:110px;">
                                                        <div class="d-flex flex-wrap input-group input-group-sm">
                                                            <button class="btn btn-outline-secondary border-0 px-2 rounded-left"
                                                                type="button" data-type="minus"
                                                                onclick="handleCartQuantity(this, {{ $cart->id }}, 'minus')"
                                                                @if ($qty <=1) disabled @endif>
                                                                <i class="las la-minus"></i>
                                                            </button>
                                                            <input type="number" name="quantity[{{ $cart->id }}]"
                                                                class="form-control text-center fw-bold fs-15 border-0 p-0 cart-qty-input"
                                                                value="{{ $qty }}"
                                                                min="{{ $product->min_qty ?? 1 }}"
                                                                max="{{ $product->stocks->first()->qty ?? 9999 }}"
                                                                onchange="updateQuantity({{ $cart->id }}, this)">
                                                            <button class="btn btn-outline-secondary border-0 px-2 rounded-right"
                                                                type="button" data-type="plus"
                                                                onclick="handleCartQuantity(this, {{ $cart->id }}, 'plus')"
                                                                @if ($qty>= ($product->stocks->first()->qty ?? 9999)) disabled @endif>
                                                                <i class="las la-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <span class="fw-700 fs-18 text-dark">1</span>
                                                    @endif
                                                </div>

                                                {{-- Total Amount --}}
                                                <div class="col-lg-3 d-flex flex-column align-items-end justify-content-center text-end" style="padding-right: 25px;">
                                                    <span class="fs-12 text-secondary mb-2 text-uppercase fw-600" style="letter-spacing: 0.5px;">{{ translate('Total Amount') }}</span>
                                                    <span class="fw-700 fs-20 text-primary" style="color: #b57a45 !important;">
                                                        {{ single_price($row_total ?? 0) }}
                                                    </span>
                                                </div>
                                            </div> {{-- end row --}}
                                        </div> {{-- end delivery-desktop-card --}}
                                    </div> {{-- end delivery-desktop-card-wrapper --}}

                                    {{-- MOBILE VIEW --}}
                                    <div class="d-block d-lg-none delivery-mobile-card">
                                        <div class="d-flex justify-content-between align-items-start mb-3" style="gap:5px;">
                                            <div class="d-flex align-items-start gap-3 min-w-0">
                                                <img src="{{ get_image($product->thumbnail) }}"
                                                    class="img-fit rounded-3 flex-shrink-0"
                                                    style="width:80px;height:80px;object-fit:cover;"
                                                    alt="{{ $product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                <div class="min-w-0" style="margin-left: 10px;">
                                                    <span class="fs-13 fw-700 text-dark d-block delivery-product-name-text">
                                                        {{ $product->getTranslation('name') }}
                                                        @if($hasVariation) <span class="text-muted fs-11">- {{ $seller_product_variation[$key2]['variation'] }}</span> @endif
                                                    </span>

                                                    {{-- Selected Attributes --}}
                                                    @if (!empty($cartItem_attributes) && is_array($cartItem_attributes) && count($cartItem_attributes) > 0)
                                                    <div class="attribute-details mt-1">
                                                        @foreach ($cartItem_attributes as $attribute)
                                                        <span class="d-block fs-11 text-muted">
                                                            {{ $attribute['attribute_name'] ?? '' }}:
                                                            {{ $attribute['option_name'] ?? '' }}
                                                        </span>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                            {{-- Edit and Delete buttons (mobile) --}}
                                            @if($cart)
                                            <div class="ms-2 flex-shrink-0 d-flex gap-1">
                                                <div class="card-action-btns-mobile">
                                                    <a href="{{ route('cart.editItem', $cart->id) }}"
                                                        class="btn-action-edit-mobile"
                                                        title="{{ translate('Edit options') }}">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M11 4H4C2.89543 4 2 4.89543 2 6V20C2 21.1046 2.89543 22 4 22H18C19.1046 22 20 21.1046 20 20V13M18.5 2.5C19.3284 1.67157 20.6716 1.67157 21.5 2.5C22.3284 3.32843 22.3284 4.67157 21.5 5.5L12 15L8 16L9 12L18.5 2.5Z"
                                                                stroke="#b57a45" stroke-width="1.8" stroke-linecap="round"
                                                                stroke-linejoin="round" style="transition: stroke 0.2s;" />
                                                        </svg>
                                                    </a>
                                                    <a href="javascript:void(0)"
                                                        onclick="removeFromCartView(event, {{ $cart->id }})"
                                                        class="btn-action-delete-mobile"
                                                        title="{{ translate('Remove') }}">
                                                        <i class="las la-trash fs-14"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        {{-- Pricing & Addons breakdown (mobile) --}}
                                        <div class="price-breakdown-box p-3 rounded-3 mb-3"
                                            style="background: #faf8f5; border: 1px solid #f0e6da; border-radius: 8px;">
                                            {{-- Product Price --}}
                                            <div class="d-flex justify-content-between align-items-center fs-13">
                                                <span class="text-secondary">{{ translate('Product Price') }}</span>
                                                <span class="fw-600 text-dark">
                                                    {{ single_price($base_price ?? 0) }}
                                                    @if ($qty > 1)
                                                    <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                        ({{ single_price(($base_price ?? 0) * $qty) }} total)
                                                    </small>
                                                    @endif
                                                </span>
                                            </div>

                                            {{-- Addons Price --}}
                                            @if ($calculated_addon_price > 0)
                                            <div class="d-flex justify-content-between align-items-center mb-2 fs-13 border-top pt-2">
                                                <span class="text-secondary">{{ translate('Add-on Price') }}</span>
                                                <span class="fw-600 text-dark">
                                                    +{{ single_price($calculated_addon_price) }}
                                                    @if ($qty > 1)
                                                    <small class="text-muted fs-11" style="display: block; text-align: right;">
                                                        (+{{ single_price($calculated_addon_price * $qty) }} total)
                                                    </small>
                                                    @endif
                                                </span>
                                            </div>
                                            @endif

                                            {{-- Addons Details list (mobile) --}}
                                            @if ($hasAddons)
                                            @php
                                            $toggleIdMobile = 'addonCollapseDeliveryMobile' . ($seller_id ?? '') . ($key2 ?? '') . ($cart->id ?? uniqid());
                                            @endphp
                                            <div class="border-top pt-2 mt-2">
                                                <button type="button"
                                                    class="addon-toggle-btn d-flex justify-content-between align-items-center w-100 text-left"
                                                    data-toggle="collapse"
                                                    data-target="#{{ $toggleIdMobile }}"
                                                    aria-expanded="false"
                                                    aria-controls="{{ $toggleIdMobile }}">
                                                    <span class="fw-600 fs-11 text-uppercase">{{ translate('Selected Add-ons') }}</span>
                                                    <i class="las la-angle-down addon-arrow"></i>
                                                </button>
                                                <div class="collapse addon-details mt-2" id="{{ $toggleIdMobile }}">
                                                    @foreach ($cartItem_addons as $addon)
                                                    @php
                                                    $addonImage = $addon['image'] ?? ($addon['img'] ?? ($addon['image_url'] ?? ''));
                                                    $addonImageSrc = $addonImage
                                                    ? (\Illuminate\Support\Str::startsWith($addonImage, ['http://', 'https://', 'data:'])
                                                    ? $addonImage
                                                    : (str_starts_with(ltrim($addonImage, '/'), 'addon/') || str_starts_with(ltrim($addonImage, '/'), 'addons/')
                                                    ? asset('public/' . ltrim($addonImage, '/'))
                                                    : asset(ltrim($addonImage, '/'))))
                                                    : '';
                                                    @endphp
                                                    <div class="d-flex justify-content-between align-items-center fs-12 text-secondary py-1 addon-row">
                                                        <span class="addon-name-text">
                                                            @if ($addonImageSrc)
                                                            <img src="{{ $addonImageSrc }}"
                                                                alt="{{ $addon['name'] ?? 'Addon' }}"
                                                                style="width:24px;height:24px;object-fit:cover;border-radius:4px;border:1px solid #e5e5e5;margin-right:6px;vertical-align:middle;">
                                                            @else
                                                            •
                                                            @endif
                                                            <strong class="text-black"> {{ $addon['addon_name'] ?? '' }}</strong>
                                                            @if (isset($addon['name']))
                                                            | {{ $addon['name'] }}
                                                            @endif
                                                        </span>
                                                        <span class="fw-600 addon-price-text">
                                                            @if (isset($addon['price']) && floatval($addon['price']) > 0)
                                                            +£{{ number_format($addon['price'], 2) }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>

                                        @php
                                        $productShippingCharges = getProductShippingCharges($product);
                                        $productServices = $product->checkoutServices()->where('status', 1)->orderBy('sort_order')->get();
                                        $selectedCartServiceIds = [];
                                        if ($cart && !empty($cart->services)) {
                                        $cartServices = json_decode($cart->services, true);
                                        if (is_array($cartServices)) {
                                        foreach ($cartServices as $cs) {
                                        $selectedCartServiceIds[] = $cs['id'];
                                        }
                                        }
                                        }
                                        @endphp

                                        {{-- Shipping Charges list (mobile) --}}
                                        @if($productShippingCharges->count() > 0)
                                        <div class="mt-2 fs-11 text-secondary d-flex flex-wrap align-items-center gap-2">
                                            <span class="fw-700 text-uppercase fs-9 text-muted" style="letter-spacing: 0.5px; white-space: nowrap;">{{ translate('Shipping') }}:</span>
                                            @foreach($productShippingCharges as $charge)
                                            <span class="shipping-badge shipping-badge-mobile border">
                                                <i class="las la-truck text-muted" style="font-size: 12px;"></i>
                                                {{ $charge->name }}: <strong class="text-primary" style="color: #b57a45 !important;">{{ single_price($charge->price) }}</strong>
                                            </span>
                                            @endforeach
                                        </div>
                                        @endif

                                        {{-- Available Services checklist (mobile) --}}
                                        @if($productServices->count() > 0)
                                        <div class="mt-2 mb-2">
                                            <span class="fw-700 fs-9 text-uppercase text-secondary d-block mb-1" style="letter-spacing: 0.5px;">{{ translate('Additional Services') }}</span>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($productServices as $service)
                                                <label class="aiz-megabox mb-0">
                                                    <input type="checkbox" name="selected_services[{{ $cart->id }}][]"
                                                        value="{{ $service->id }}"
                                                        class="service-checkbox"
                                                        data-price="{{ $service->price }}"
                                                        @if (in_array($service->id, $selectedCartServiceIds)) checked @endif>
                                                    <span class="d-flex aiz-megabox-elem p-2 align-items-center custom-service-pill custom-service-pill-mobile"
                                                        @if(!empty($service->description)) title="{{ $service->description }}" @endif>
                                                        <span class="aiz-rounded-check flex-shrink-0" style="margin-right: 4px; width: 12px; height: 12px;"></span>
                                                        <span class="fs-10 fw-700 text-dark" style="white-space: nowrap;">
                                                            {{ $service->name }} ({{ single_price($service->price) }})
                                                        </span>
                                                    </span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                        @if ($product->dispatch_time)
                                        <div class="mb-3 fs-12 text-muted">
                                            <i class="las la-clock fs-14"></i>
                                            <span class="fw-600">{{ translate('Dispatch Time') }}:</span>
                                            {{ $product->dispatch_time }}
                                        </div>
                                        @endif

                                        {{-- Quantity and total row (mobile) --}}
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="d-block text-secondary fs-11 mb-1">{{ translate('Quantity') }}</span>
                                                @if ($product->auction_product == 0)
                                                <div class="quantity-group" style="max-width:110px;">
                                                    <div class="d-flex flex-wrap input-group input-group-sm">
                                                        <button class="btn btn-outline-secondary border-0 px-2 rounded-left"
                                                            type="button" data-type="minus"
                                                            onclick="handleCartQuantity(this, {{ $cart->id }}, 'minus')"
                                                            @if ($qty <=1) disabled @endif>
                                                            <i class="las la-minus"></i>
                                                        </button>
                                                        <input type="number" name="quantity[{{ $cart->id }}]"
                                                            class="form-control text-center fw-bold fs-14 border-0 p-0 cart-qty-input-mobile"
                                                            value="{{ $qty }}"
                                                            min="{{ $product->min_qty ?? 1 }}"
                                                            max="{{ $product->stocks->first()->qty ?? 9999 }}"
                                                            onchange="updateQuantity({{ $cart->id }}, this)">
                                                        <button class="btn btn-outline-secondary border-0 px-2 rounded-right"
                                                            type="button" data-type="plus"
                                                            onclick="handleCartQuantity(this, {{ $cart->id }}, 'plus')"
                                                            @if ($qty>= ($product->stocks->first()->qty ?? 9999)) disabled @endif>
                                                            <i class="las la-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                @else
                                                <span class="fw-700 fs-16 text-dark">1</span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <span class="d-block text-secondary fs-11 mb-1">{{ translate('Total Amount') }}</span>
                                                <span class="fw-700 fs-16 text-primary" style="color: #b57a45 !important;">
                                                    {{ single_price($row_total ?? 0) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                @php
                                $seller_shipping = 0;
                                $seller_services_total = 0;
                                foreach ($seller_product as $productId) {
                                $shippingProduct = get_single_product($productId);
                                $shippingCart = collect($carts)->firstWhere(
                                'product_id',
                                $productId,
                                );
                                if ($shippingProduct) {
                                $seller_shipping += getProductShippingChargeTotal(
                                $shippingProduct,
                                $shippingCart->quantity ?? 1,
                                );
                                }
                                if ($shippingCart && !empty($shippingCart->services)) {
                                $cartServices = json_decode($shippingCart->services, true);
                                if (is_array($cartServices)) {
                                foreach ($cartServices as $cs) {
                                $seller_services_total += (float) ($cs['price'] ?? 0);
                                }
                                }
                                }
                                }
                                @endphp

                                {{-- Totals Section --}}
                                <div class="seller-totals-box p-4 rounded-3 mt-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-13 text-secondary">{{ translate('Subtotal') }}</span>
                                        <span class="fw-600 text-dark">{{ single_price($seller_subtotal) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fs-13 text-secondary">{{ translate('Shipping Charges') }}</span>
                                        <span class="fw-600 text-dark shipping-total-display">{{ single_price($seller_shipping) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                        <span class="fs-13 text-secondary">{{ translate('Services') }}</span>
                                        <span class="fw-600 text-dark services-total-display">{{ single_price($seller_services_total) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-700 fs-16 text-dark">{{ translate('Total') }}</span>
                                        <span class="fw-700 fs-20 grand-total-display" style="color: #b57a45;"
                                            data-base-total="{{ $seller_subtotal + $seller_shipping }}">
                                            {{ single_price($seller_subtotal + $seller_shipping + $seller_services_total) }}
                                        </span>
                                    </div>
                                </div>
                            </div> {{-- end card-body --}}
                        </div>
                        @endforeach
                        @endif

                </div>

                <div class="row g-2 mt-3">
                    <div class="col-6 col-md-6 mb-2 mb-md-0">
                        <a href="{{ url('checkout') }}"
                            class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design filled">
                            <i class="las la-arrow-left fs-17"></i>
                            {{ translate('Back') }}
                        </a>
                    </div>
                    <div class="col-6 col-md-6 text-center text-md-right">
                        <button type="submit" id="continue-to-payment-btn"
                            class="btn borderbtn fs-14 fw-700 rounded-0 w-100 w-md-auto py-3 custom_checkout_button_design unfilled">
                            {{ translate('Next') }}
                        </button>
                    </div>
                </div>
                </form>

            </div>
        </div>
    </div>
</section>

<style>
    :focus-visible {
        outline: none !important;
    }

    .delivery-maincontainer {
        background: #fdfdfc;
    }


    .delivery-desktop-card-wrapper {
        margin-bottom: 20px;
    }

    .delivery-desktop-card {
        border: 1px solid #f0e6da;
        border-radius: 12px;
        background: #fff;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 20px rgba(181, 122, 69, 0.02);
    }

    .delivery-desktop-card:hover {
        border-color: #b57a45;
        background: #fdfdfb !important;
        box-shadow: 0 8px 30px rgba(181, 122, 69, 0.06);
        transform: translateY(-2px);
    }

    .delivery-product-name-text {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: anywhere;
        line-height: 1.4;
    }

    .addon-toggle-btn {
        background: #f5eee6 !important;
        border: 1px solid #e2d2c0 !important;
        color: #8b5e34 !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        transition: all .3s ease;
    }

    .addon-toggle-btn:hover {
        background: #8b5e34 !important;
        color: #fff !important;
    }

    .addon-toggle-btn:focus {
        /* box-shadow: none !important; */
    }

    .addon-name-text {
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        overflow-wrap: anywhere;
        flex: 1 1 auto;
        min-width: 0;
    }

    .addon-price-text {
        white-space: nowrap;
        flex: 0 0 auto;
        margin-left: auto;
    }

    .addon-row {
        gap: 8px;
        flex-wrap: wrap;
    }

    .addon-details {
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box;
        background: #f9f6f3;
        border-radius: 8px;
        /* box-shadow: 0 1px 4px rgba(181, 122, 69, .08); */
    }

    .la-rotate-180 {
        transform: rotate(180deg);
        transition: transform 0.2s ease;
    }

    .min-w-0 {
        min-width: 0;
    }

    a:-webkit-any-link:focus-visible {
        outline: none !important;
    }

    #continue-to-payment-btn[disabled] {
        background: #d9cbbb !important;
        cursor: no-drop;
        outline: none !important;
    }

    #continue-to-payment-btn:focus-visible {
        outline: none !important;
    }

    @media (max-width: 991.98px) {
        .delivery-maincontainer {
            padding: 0 !important;
        }

        .cart-summary-card {
            margin-top: 30px !important;
        }
    }

    /* Premium service pills and quantity styling */
    .custom-service-pill {
        border: 1px solid #f0e6da !important;
        border-radius: 20px !important;
        background: #fff !important;
        cursor: pointer;
        padding: 8px 16px !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .custom-service-pill-mobile {
        padding: 4px 10px !important;
    }

    .custom-service-pill:hover {
        border-color: #b57a45 !important;
        background: #fdfaf6 !important;
        transform: translateY(-1px);
    }

    input:checked+.custom-service-pill {
        border-color: #b57a45 !important;
        background: #fdf6ed !important;
        box-shadow: 0 2px 6px rgba(181, 122, 69, 0.1);
    }

    input:checked+.custom-service-pill .aiz-rounded-check {
        background-color: #b57a45 !important;
        border-color: #b57a45 !important;
    }

    input:checked+.custom-service-pill .text-dark {
        color: #b57a45 !important;
    }

    .quantity-group .btn {
        border: 1px solid #e2d2c0 !important;
        background: #faf8f5;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-group .btn:hover:not([disabled]) {
        background: #b57a45;
        color: #fff !important;
        border-color: #b57a45 !important;
    }

    .quantity-group .btn:hover:not([disabled]) i {
        color: #fff !important;
    }

    .quantity-group .cart-qty-input {
        border: 1px solid #e2d2c0 !important;
        border-left: none !important;
        border-right: none !important;
        height: 32px;
        max-width: 36px;
        background: #faf8f5;
        box-shadow: none !important;
    }

    .quantity-group .cart-qty-input-mobile {
        border: 1px solid #e2d2c0 !important;
        border-left: none !important;
        border-right: none !important;
        height: 28px;
        max-width: 32px;
        background: #faf8f5;
        box-shadow: none !important;
    }

    .quantity-group .input-group {
        flex-wrap: nowrap !important;
    }

    .gap-1 {
        gap: 4px !important;
    }

    .gap-2 {
        gap: 8px !important;
    }

    .d-flex.flex-wrap {
        margin-bottom: -4px;
    }

    .d-flex.flex-wrap>* {
        margin-bottom: 4px;
    }

    /* Action buttons */
    .card-action-btns {
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 10;
        display: flex;
        gap: 8px;
    }

    .btn-action-edit {
        outline: none;
        border: 1px solid #EADDCF !important;
        background: #fdf6ed !important;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(181, 122, 69, 0.05);
    }

    .btn-action-edit:hover {
        background: #b57a45 !important;
        border-color: #b57a45 !important;
        transform: scale(1.05);
    }

    .btn-action-edit:hover svg path {
        stroke: #ffffff !important;
    }

    .btn-action-delete {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        border: 1px solid #f8d7da !important;
        background: #f8d7da !important;
        color: #721c24 !important;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action-delete:hover {
        background: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
        transform: scale(1.05);
    }

    .card-action-btns-mobile {
        display: flex;
        gap: 4px;
    }

    .btn-action-edit-mobile {
        outline: none;
        border: 1px solid #EADDCF !important;
        background: #fdf6ed !important;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action-edit-mobile:hover {
        background: #b57a45 !important;
        border-color: #b57a45 !important;
        transform: scale(1.05);
    }

    .btn-action-edit-mobile:hover svg path {
        stroke: #ffffff !important;
    }

    .btn-action-delete-mobile {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #f8d7da !important;
        background: #f8d7da !important;
        color: #721c24 !important;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-action-delete-mobile:hover {
        background: #dc3545 !important;
        border-color: #dc3545 !important;
        color: #ffffff !important;
        transform: scale(1.05);
    }

    .shipping-badge {
        background-color: #faf8f5 !important;
        border-color: #f0e6da !important;
        border: 1px solid #f0e6da;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11px;
        color: #333;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .shipping-badge-mobile {
        font-size: 10px;
        padding: 2px 8px;
    }

    .seller-totals-box {
        background: #faf8f5;
        border: 1px solid #f0e6da;
        border-radius: 12px;
        max-width: 480px;
        margin-left: auto;
    }
</style>
@endsection


@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        let $serviceCheckboxes = $('.service-checkbox');
        let $continueBtn = $('#continue-to-payment-btn');
        let $serviceError = $('#service-required-error');

        function updateTotals() {
            $('.checkout-seller-card').each(function() {
                let $sellerCard = $(this);
                let serviceTotal = 0;

                $sellerCard.find('.service-checkbox:checked:visible').each(function() {
                    serviceTotal += parseFloat($(this).data('price')) || 0;
                });

                let $grandTotal = $sellerCard.find('.grand-total-display');
                let baseTotal = parseFloat($grandTotal.data('base-total')) || 0;
                let finalTotal = baseTotal + serviceTotal;

                $sellerCard.find('.services-total-display').html('£' + serviceTotal.toFixed(2));
                $grandTotal.html('£' + finalTotal.toFixed(2));
            });

            checkServiceSelection();
        }

        // Sync same checkbox in Desktop/Mobile and handle mutual exclusivity per product
        $serviceCheckboxes.on('change', function() {
            let name = $(this).attr('name');
            let val = $(this).val();
            let isChecked = $(this).is(':checked');

            // 1. Sync Desktop and Mobile checkbox state for the same service
            $(`.service-checkbox[name="${name}"][value="${val}"]`).prop('checked', isChecked);

            // 2. Uncheck other service checkboxes for the same product
            if (isChecked) {
                $(`.service-checkbox[name="${name}"]`).not(`[value="${val}"]`).prop('checked', false);
            }

            updateTotals();
        });

        // Initial auto-selection of free services per product if none checked
        let uniqueNames = [];
        $serviceCheckboxes.each(function() {
            let name = $(this).attr('name');
            if (uniqueNames.indexOf(name) === -1) {
                uniqueNames.push(name);
            }
        });

        uniqueNames.forEach(function(name) {
            let $productCheckboxes = $(`.service-checkbox[name="${name}"]`);
            if ($productCheckboxes.filter(':checked').length === 0) {
                let $freeService = $productCheckboxes.filter(function() {
                    return parseFloat($(this).data('price')) === 0;
                });
                if ($freeService.length > 0) {
                    let val = $freeService.first().val();
                    $(`.service-checkbox[name="${name}"][value="${val}"]`).prop('checked', true);
                }
            }
        });

        // Initial calculation
        updateTotals();

        // Hide or show button depending on if any service-checkbox must be chosen
        function checkServiceSelection() {
            if ($serviceCheckboxes.length > 0) {
                if ($('.service-checkbox:checked').length > 0) {
                    $continueBtn.removeAttr('disabled').show();
                    $serviceError.addClass('d-none');
                } else {
                    $continueBtn.attr('disabled', 'disabled').show();
                }
            } else {
                $continueBtn.show();
                $serviceError.addClass('d-none');
            }
        }

        // Prevent form submission if services required and none checked
        $('#delivery-form').on('submit', function(e) {
            if ($serviceCheckboxes.length > 0 && $('.service-checkbox:checked').length === 0) {
                $serviceError.removeClass('d-none');
                $continueBtn.hide();
                e.preventDefault();
            }
        });

        // Arrow rotation for collapse
        $('.collapse').on('show.bs.collapse', function() {
            $(this).prev('.addon-toggle-btn').find('.addon-arrow').addClass('la-rotate-180');
        });
        $('.collapse').on('hide.bs.collapse', function() {
            $(this).prev('.addon-toggle-btn').find('.addon-arrow').removeClass('la-rotate-180');
        });
    });

    function display_option(key) {

    }

    function show_pickup_point(el, type) {

        var value = $(el).val();

        var target = $(el).data('target');

        if (value == 'home_delivery' || value == 'carrier') {

            if (!$(target).hasClass('d-none')) {
                $(target).addClass('d-none');
            }

            $('.carrier_id_' + type).removeClass('d-none');

        } else {

            $(target).removeClass('d-none');

            $('.carrier_id_' + type).addClass('d-none');
        }
    }

    function handleCartQuantity(btn, cartId, type) {
        let group = btn.closest('.quantity-group');
        if (!group) return;
        let inp = group.querySelector('input[type=number]');
        if (!inp) return;
        let qty = parseInt(inp.value, 10);
        let min = parseInt(inp.min, 10) || 1;
        let max = parseInt(inp.max, 10) || 1;

        if (type === 'plus' && qty < max) {
            qty += 1;
            inp.value = qty;
            updateQuantity(cartId, inp);
        }
        if (type === 'minus' && qty > min) {
            qty -= 1;
            inp.value = qty;
            updateQuantity(cartId, inp);
        }
    }

    function updateQuantity(key, element) {
        $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            },
            function(data) {
                location.reload();
            });
    }

    function removeFromCartView(e, key) {
        e.preventDefault();
        if ($('#remove-cart-modal').length === 0) {
            $('body').append(`
                    <div class="modal fade" id="remove-cart-modal" tabindex="-1" role="dialog" aria-labelledby="removeCartModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content text-left">
                          <div class="modal-header">
                            <h5 class="modal-title" id="removeCartModalLabel">{{ translate("Confirmation") }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body text-left">
                            {{ translate("Are you sure you want to remove this item from your cart?") }}
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate("Cancel") }}</button>
                            <button type="button" class="btn btn-primary" id="remove-cart-modal-confirm">{{ translate("Remove") }}</button>
                          </div>
                        </div>
                      </div>
                    </div>
                `);
        }
        $('#remove-cart-modal').data('cart-key', key);
        $('#remove-cart-modal-confirm').off('click').on('click', function() {
            var cartKey = $('#remove-cart-modal').data('cart-key');
            $('#remove-cart-modal').modal('hide');
            $.post('{{ route('cart.removeFromCart') }}', {
                    _token: AIZ.data.csrf,
                    id: cartKey
                },
                function(data) {
                    location.reload();
                });
        });
        $('#remove-cart-modal').modal('show');
    }
</script>
@endsection