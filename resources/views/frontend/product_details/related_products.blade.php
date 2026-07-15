@php
    $relatedProducts = get_related_products($detailedProduct);
    $relatedProductCount = count($relatedProducts);
@endphp

@if ($relatedProductCount > 0)
    <div class="mb-4 mt-4 home-mobile-product-section" id="section_related_products_detail">
        <div class="modern-section-bordered-wrap bg-white border p-3 p-sm-4">
            <!-- Section Header -->
            <div class="modern-section-header home-section-heading-with-arrows mb-4 d-flex justify-content-between align-items-center">
                <div class="home-section-heading-copy">
                    <h3 class="modern-section-title fs-16 fw-700 mb-0">{{ translate('Related') }}
                       <span style="color: #C27325;"> {{ translate(' Products') }}</span>
                    </h3>
                    <div class="modern-section-subtitle fs-12 text-secondary mt-1">
                        {{ translate('Similar items you might be interested in') }}
                    </div>
                </div>
                <div class="home-section-arrow-group @if ($relatedProductCount <= 4) home-arrows-desktop-disabled @endif @if ($relatedProductCount <= 2) home-arrows-mobile-disabled @endif">
                    <span class="home-section-arrows-only">
                        <button type="button" class="home-section-arrow is-prev"
                            aria-label="{{ translate('Previous') }}"
                            onclick="homeSectionSlide('prev','section_related_products_detail')">
                            <i class="las la-angle-left"></i>
                        </button>
                        <button type="button" class="home-section-arrow is-next"
                            aria-label="{{ translate('Next') }}"
                            onclick="homeSectionSlide('next','section_related_products_detail')">
                            <i class="las la-angle-right"></i>
                        </button>
                    </span>
                </div>
            </div>

            <!-- Products Section -->
            <div class="px-sm-3">
                <div class="aiz-carousel sm-gutters-16 arrow-none home-mobile-product-carousel" data-items="4"
                    data-xxl-items="4" data-xl-items="4" data-lg-items="4" data-md-items="3" data-sm-items="2"
                    data-xs-items="2" data-arrows="true" data-dots="false" data-infinite="false"
                    data-autoplay="false">
                    @foreach ($relatedProducts as $key => $related_product)
                        <div class="carousel-box px-0 position-relative">
                            @include(
                                'frontend.' . get_setting('homepage_select') . '.partials.product_box_1',
                                ['product' => $related_product]
                            )
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif