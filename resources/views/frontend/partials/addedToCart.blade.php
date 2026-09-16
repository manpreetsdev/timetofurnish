{{-- Shared cart confirmation component. Classic and Metro include this file. --}}
@php
    // Cart add-ons can arrive as an Eloquent-cast array or as legacy JSON.
    // Normalise both shapes so every theme shows the selected add-on images.
    $selectedAddons = $cart->addons ?? [];
    if (is_string($selectedAddons)) {
        $selectedAddons = json_decode($selectedAddons, true) ?: [];
    }
    if (!is_array($selectedAddons)) {
        $selectedAddons = [];
    }
@endphp
<div class="modal-body c-scrollbar-light added-to-cart-modal">
    <div class="added-to-cart-scroll">
    <div class="added-to-cart-success text-center">
        <div class="added-to-cart-icon" aria-hidden="true"><i class="las la-check"></i></div>
        <p class="mb-1 fs-12 text-secondary text-uppercase fw-700">
            {{ $cart_was_updated ? translate('Basket updated') : translate('Added to basket') }}
        </p>
        <h3 class="fw-700 added-to-cart-title mb-0">
            {{ $cart_was_updated ? translate('Your basket has been updated') : translate('Item added to your basket') }}
        </h3>
    </div>

    <div class="added-to-cart-summary media">
        <img src="{{ get_image($product->thumbnail) }}"
             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
             alt="{{ $product->getTranslation('name') }}" class="mr-3 rounded">
        <div class="media-body min-w-0">
            <h4 class="fs-15 fw-700 text-truncate-2 mb-2">{{ $product->getTranslation('name') }}</h4>
            <div class="d-flex justify-content-between align-items-center fs-13">
                <span class="text-secondary">{{ translate('Pieces in basket') }}</span>
                <strong class="added-to-cart-quantity">{{ $cart->quantity }} {{ $cart->quantity == 1 ? translate('piece') : translate('pieces') }}</strong>
            </div>
            <div class="d-flex justify-content-between align-items-center fs-13 mt-1">
                <span class="text-secondary">{{ translate('Item total') }}</span>
                <strong class="text-primary">{{ single_price((cart_product_price($cart, $product, false) + (float) ($cart->addon_price ?? 0)) * $cart->quantity) }}</strong>
            </div>
        </div>
    </div>

    @if(!empty($selectedAddons))
        <div class="added-to-cart-addons">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="fs-13 fw-700 mb-0">{{ translate('Selected add-ons') }}</h4>
                <span class="added-to-cart-addon-count">{{ count($selectedAddons) }} {{ count($selectedAddons) == 1 ? translate('option') : translate('options') }}</span>
            </div>
            <div class="added-to-cart-addon-grid">
                @foreach($selectedAddons as $addon)
                    @php $addonImage = get_addon_image_src($addon['image'] ?? ''); @endphp
                    <div class="added-to-cart-addon-chip">
                        @if($addonImage)
                            <img src="{{ $addonImage }}" alt="{{ $addon['name'] ?? '' }}"
                                 onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                        @else
                            <span class="added-to-cart-addon-fallback"><i class="las la-puzzle-piece"></i></span>
                        @endif
                        <div class="min-w-0 flex-grow-1">
                            <span class="d-block fs-11 text-secondary text-truncate">{{ $addon['addon_name'] ?? translate('Add-on') }}</span>
                            <strong class="d-block fs-12 text-truncate">{{ $addon['name'] ?? '' }}</strong>
                        </div>
                        @php $addonPrice = (float) ($addon['price'] ?? 0); @endphp
                        <span class="added-to-cart-addon-price">
                            +{{ single_price($addonPrice) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <p class="added-to-cart-note mb-3">
        {{ translate('Select different options or quantity and choose Add to Basket again to update this item.') }}
    </p>
    </div>

    <div class="added-to-cart-footer">
    <div class="row gutters-5">
        <div class="col-6"><button type="button" class="btn btn-outline-primary btn-block rounded-0" data-dismiss="modal">{{ translate('Continue shopping') }}</button></div>
        <div class="col-6"><a href="{{ route('cart') }}" class="btn btn-primary btn-block rounded-0">{{ translate('View basket') }}</a></div>
    </div>
    </div>
</div>
