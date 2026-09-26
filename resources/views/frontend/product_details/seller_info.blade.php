@if ($detailedProduct->added_by == 'seller' && optional($detailedProduct->user)->shop != null)
    <div class="seller-info-card border p-3 p-sm-4 rounded-lg" style="background: #FAF6F3; border-radius: 12px;">
        <div class="d-flex flex-column align-items-center">

            <!-- Seller Info: Logo, Name, Address -->
            <div class="d-flex flex-column align-items-center w-100 mb-3">
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="avatar avatar-md mb-2 overflow-hidden border shadow-sm" style="border-radius: 50%; width: 75px; height: 75px;">
                    <img class="lazyload seller_img img-fit" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ uploaded_asset($detailedProduct->user->shop->logo) }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </a>

                <div class="text-center">
                    <div class="fw-700 fs-16 text-dark d-flex align-items-center justify-content-center gap-1 flex-wrap">
                        <span>{{ $detailedProduct->user->name }}</span>
                        <span class="badge badge-inline badge-info fs-10 px-2 py-1 ml-1" style="border-radius: 10px;">
                            <i class="las la-check-circle"></i> {{ translate('Verified Seller') }}
                        </span>
                    </div>
                    @if($detailedProduct->user->shop->address)
                        <div class="opacity-70 fs-12 mt-1">
                            <i class="las la-map-marker-alt"></i> {{ $detailedProduct->user->shop->address }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rating -->
            <div class="mb-3 text-center">
                <div class="rating rating-mr-1">
                    {{ renderStarRating($detailedProduct->user->shop->rating) }}
                </div>
                <div class="opacity-60 fs-12 mt-1">
                    ({{ $detailedProduct->user->shop->num_of_reviews }} {{ translate('customer reviews') }})
                </div>
            </div>

            <!-- Visit Store Button -->
            <div class="w-100 text-center">
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="btn fs-14 fw-700 w-100 visit-store-btn"
                    style="background: #685c4e; color: #fff; border:1px solid #685c4e; border-radius: 8px; padding: 10px 16px;">
                    <i class="las la-store mr-1"></i> {{ translate('Visit Seller Store') }}
                </a>
            </div>

        </div>
    </div>
@else
    {{-- In-house / Official Store --}}
    <div class="seller-info-card border p-3 p-sm-4 rounded-lg" style="background: #FAF6F3; border-radius: 12px;">
        <div class="d-flex flex-column align-items-center">

            <div class="d-flex flex-column align-items-center w-100 mb-3">
                <div class="avatar avatar-md mb-2 overflow-hidden border bg-white p-2 d-flex align-items-center justify-content-center shadow-sm" style="border-radius: 50%; width: 75px; height: 75px;">
                    @php
                        $siteLogo = get_setting('header_logo') ? uploaded_asset(get_setting('header_logo')) : static_asset('assets/img/logo.png');
                    @endphp
                    <img class="img-fit" src="{{ $siteLogo }}" alt="{{ get_setting('website_name', 'Time To Furnish') }}" style="max-height: 48px; object-fit: contain;">
                </div>

                <div class="text-center">
                    <div class="fw-700 fs-16 text-dark d-flex align-items-center justify-content-center gap-1 flex-wrap">
                        <span>{{ get_setting('website_name', 'Time To Furnish') }}</span>
                        <span class="badge badge-inline badge-success fs-10 px-2 py-1 ml-1" style="border-radius: 10px; background-color: #28a745; color: #fff;">
                            <i class="las la-check-circle"></i> {{ translate('Official Store') }}
                        </span>
                    </div>
                    <div class="opacity-70 fs-12 mt-1">
                        {{ translate('Sold & Fulfilled directly by Time To Furnish') }}
                    </div>
                </div>
            </div>

            <!-- Rating / Quality Guarantee -->
            <div class="mb-3 text-center">
                <div class="rating rating-mr-1">
                    {{ renderStarRating(5) }}
                </div>
                <div class="opacity-60 fs-12 mt-1">
                    {{ translate('Verified Quality & Express UK Delivery') }}
                </div>
            </div>

            <!-- Visit Store Button -->
            <div class="w-100 text-center">
                <a href="{{ route('search') }}"
                    class="btn fs-14 fw-700 w-100 visit-store-btn"
                    style="background: #685c4e; color: #fff; border:1px solid #685c4e; border-radius: 8px; padding: 10px 16px;">
                    <i class="las la-shopping-bag mr-1"></i> {{ translate('Explore Official Store') }}
                </a>
            </div>

        </div>
    </div>
@endif
<style>
    .visit-store-btn {
        display: inline-block;
        transition: all 0.2s ease;
    }
    .visit-store-btn:hover {
        background: #54493d !important;
        border-color: #54493d !important;
        color: #fff !important;
    }
</style>

