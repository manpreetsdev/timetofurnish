@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Shopping Cart') }} | {{ get_setting('website_name') }}@stop
@section('meta_description'){{ translate('Review the furniture and home products currently saved in your shopping cart before checkout.') }}@stop
@section('meta_robots', 'noindex, follow')

@section('content')
    <h1 class="sr-only">{{ translate('Shopping Cart') }}</h1>
    <!-- Steps -->
    <section class="pt-5 mb-4 cart_tabs">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-shopping-bag cart-animate"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block"><a href="{{url('cart')}}">{{ translate('1. My Cart') }}</a></h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-map-marker"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-shipping-fast"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-wallet"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-clipboard-check"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Details -->
    <section class="mb-4" id="cart-summary">
        @include('frontend.'.get_setting('homepage_select').'.partials.cart_details', ['carts' => $carts])
    </section>

    {{-- Reservation expired: items the shopper had reserved for 1 hour but did
         not buy. Kept here for reference only — they no longer hold stock and
         cannot be checked out. --}}
    @if(isset($expired_carts) && count($expired_carts) > 0)
    <section class="mb-5" id="recently-in-cart">
        <div class="container">
            <div class="mx-auto col-xl-10">
                <div class="p-3 p-md-4" style="background:#faf8f5;border:1px solid #e4dcd2;border-radius:14px;">
                    <h2 class="mb-1 fs-18 fw-700 text-dark">{{ translate('Recently in cart') }}</h2>
                    <p class="mb-3 fs-13 text-secondary">
                        {{ translate('Your 1-hour reservation on these items has expired, so they were released for other shoppers. They can be re-added if still in stock.') }}
                    </p>

                    @foreach($expired_carts as $expired)
                        @php $expired_product = $expired->product; @endphp
                        @if($expired_product)
                        <div class="py-3 d-flex align-items-center border-top" style="gap:14px;">
                            <a href="{{ route('product', $expired_product->slug) }}" class="flex-shrink-0">
                                <img src="{{ get_image($expired_product->thumbnail) }}"
                                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                     alt="{{ $expired_product->getTranslation('name') }}"
                                     style="width:64px;height:64px;object-fit:cover;border-radius:10px;border:1px solid #e4dcd2;">
                            </a>
                            <div class="flex-grow-1 min-w-0">
                                <a href="{{ route('product', $expired_product->slug) }}" class="d-block fs-14 fw-600 text-dark text-truncate">
                                    {{ $expired_product->getTranslation('name') }}
                                    @if($expired->variation) <span class="text-secondary fw-400">({{ $expired->variation }})</span>@endif
                                </a>
                                <div class="fs-12 mt-1" style="color:#b5462f;">
                                    <i class="las la-clock"></i> {{ translate('Reservation expired') }} &middot; {{ translate('Quantity') }}: 0
                                </div>
                            </div>
                            <div class="flex-shrink-0 d-flex align-items-center" style="gap:8px;">
                                <a href="{{ route('product', $expired_product->slug) }}" class="btn btn-sm btn-outline-primary">{{ translate('View Product') }}</a>
                                <button type="button" class="btn btn-sm btn-light" onclick="removeFromCart({{ $expired->id }})" aria-label="{{ translate('Remove') }}">
                                    <i class="las la-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

@endsection

@section('script')
    <script type="text/javascript">
        function removeFromCartView(e, key) {
            e.preventDefault();

            // Create modal if it does not exist
            if ($('#remove-cart-modal').length === 0) {
                $('body').append(`
                    <div class="modal fade" id="remove-cart-modal" tabindex="-1" role="dialog" aria-labelledby="removeCartModalLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="removeCartModalLabel">{{ translate("Confirmation") }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
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

            // Set the key to a data attribute
            $('#remove-cart-modal').data('cart-key', key);

            // Prevent duplicate handlers
            $('#remove-cart-modal-confirm').off('click').on('click', function(){
                var cartKey = $('#remove-cart-modal').data('cart-key');
                $('#remove-cart-modal').modal('hide');
                removeFromCart(cartKey);
            });

            $('#remove-cart-modal').modal('show');
        }

        function updateQuantity(key, element) {
            $.post('{{ route('cart.updateQuantity') }}', {
                _token: AIZ.data.csrf,
                id: key,
                quantity: element.value
            }, function(data) {
                updateNavCart(data.nav_cart_view, data.cart_count);
                $('#cart-summary').html(data.cart_view);
            });
        }

    </script>
@endsection
