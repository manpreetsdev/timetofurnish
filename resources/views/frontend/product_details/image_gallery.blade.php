<style>
    div#imageGalleryCol,
    .product-gallery {
        touch-action: pan-y !important;
    }

    .product-gallery-thumb,
    .product-gallery-thumb .slick-list,
    .product-gallery-thumb .slick-track,
    .product-gallery-thumb .carousel-box {
        touch-action: manipulation !important;
        -webkit-overflow-scrolling: touch;
    }

    .product-gallery-thumb .carousel-box img {
        -webkit-user-drag: none;
        user-select: none;
        pointer-events: auto;
    }

    .image_gallery_section_shadow {
        box-shadow: 0 0 16px 5px rgba(124, 124, 124, 0.11);
        padding: 15px 10px !important;
        margin-bottom: 30px;
        margin-top: 0px;
    }

    /* Prevent vertical stacking before Slick Carousel JS initializes */
    .product-gallery:not(.slick-initialized) > .carousel-box:nth-child(n+2) {
        display: none !important;
    }
    .product-gallery-thumb:not(.slick-initialized) {
        display: flex !important;
        overflow-x: auto !important;
        gap: 8px !important;
        white-space: nowrap !important;
        padding-bottom: 4px !important;
    }
    .product-gallery-thumb:not(.slick-initialized) > .carousel-box {
        flex: 0 0 auto !important;
        width: 64px !important;
    }

    /* Base Gallery Container */
    .product-gallery {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        background-color: #f8f9fa;
    }

    .product-gallery .slick-list,
    .product-gallery .slick-track,
    .product-gallery .slick-slide {
        height: 100%;
    }

    .product-gallery .carousel-box {
        width: 100%;
        overflow: hidden;
        border-radius: 10px;
        background-color: #fcfcfc;
    }

    /* Uniform Height for every slide image */
    .product-gallery img.carousal_image_custom_height,
    .product-gallery .carousel-box img {
        width: 100% !important;
        object-fit: cover !important;
        object-position: center !important;
        display: block !important;
        border-radius: 10px;
    }

    /* Responsive Heights */
    @media (max-width: 575.98px) {
        .product-gallery,
        .product-gallery .carousel-box,
        .product-gallery .carousel-box img {
            height: 260px !important;
        }
    }

    @media (min-width: 576px) and (max-width: 767.98px) {
        .product-gallery,
        .product-gallery .carousel-box,
        .product-gallery .carousel-box img {
            height: 380px !important;
        }
    }

    @media (min-width: 768px) and (max-width: 991.98px) {
        .product-gallery,
        .product-gallery .carousel-box,
        .product-gallery .carousel-box img {
            height: 440px !important;
        }
    }

    @media (min-width: 992px) {
        .product-gallery,
        .product-gallery .carousel-box,
        .product-gallery .carousel-box img {
            height: 480px !important;
        }
    }

    /* Slick Prev / Next Arrows Styling */
    .product-gallery .slick-arrow {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        position: absolute !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        z-index: 20 !important;
        width: 38px !important;
        height: 38px !important;
        background: rgba(255, 255, 255, 0.92) !important;
        color: #333333 !important;
        border-radius: 50% !important;
        border: 1px solid rgba(0, 0, 0, 0.1) !important;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.16) !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        padding: 0 !important;
    }

    .product-gallery .slick-arrow:hover {
        background: #ffffff !important;
        color: #685b4e !important;
        box-shadow: 0 5px 14px rgba(0, 0, 0, 0.22) !important;
    }

    .product-gallery .slick-arrow i {
        font-size: 20px !important;
        line-height: 1 !important;
        font-weight: bold !important;
    }

    .product-gallery .slick-prev {
        left: 10px !important;
    }

    .product-gallery .slick-next {
        right: 10px !important;
    }

    /* Thumbnail Box Styling */
    .product-gallery-thumb {
        padding: 4px 0;
    }

    .aiz-carousel.product-gallery-thumb .slick-track {
        transition: transform 0.3s ease !important;
    }

    .product-gallery-thumb .slick-arrow,
    .category-nav-row .slick-arrow,
    .banner-category .slick-arrow {
        display: none !important;
    }

    .product-gallery-thumb .carousel-box {
        padding: 3px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .product-gallery-thumb .carousel-box img {
        width: 100% !important;
        height: 64px !important;
        object-fit: cover !important;
        border-radius: 6px !important;
        border: 2px solid #e2e8f0 !important;
        box-shadow: none !important;
        transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
    }

    .product-gallery-thumb .slick-slide.slick-current .carousel-box img,
    .product-gallery-thumb .carousel-box.active img {
        border-color: #685b4e !important;
        box-shadow: 0 0 0 2px #685b4e !important;
        opacity: 1 !important;
    }

    .product-gallery-thumb .slick-slide:not(.slick-current) .carousel-box:not(.active) img {
        border-color: #e2e8f0 !important;
        box-shadow: none !important;
    }
</style>

<div class="sticky-top z-3 row gutters-10">
    @php
        $photos = [];
        if ($detailedProduct->photos != null) {
            $photos = array_filter(explode(',', $detailedProduct->photos));
        }

        // Deduplicate image assets so the same image does not repeat multiple times
        $gallery_images = [];
        $rendered_ids = [];

        if ($detailedProduct->digital == 0 && isset($detailedProduct->stocks)) {
            foreach ($detailedProduct->stocks as $stock) {
                if (!empty($stock->image) && !in_array($stock->image, $rendered_ids)) {
                    $gallery_images[] = [
                        'image' => $stock->image,
                        'variant' => $stock->variant,
                        'title' => $stock->name ?? $detailedProduct->getTranslation('name')
                    ];
                    $rendered_ids[] = $stock->image;
                }
            }
        }

        foreach ($photos as $photo) {
            if (!empty($photo) && !in_array($photo, $rendered_ids)) {
                $gallery_images[] = [
                    'image' => $photo,
                    'variant' => null,
                    'title' => $detailedProduct->getTranslation('name')
                ];
                $rendered_ids[] = $photo;
            }
        }
    @endphp

    <!-- Gallery Images -->
    <div class="col-12 position-relative" style="position: relative;">
        @if ($detailedProduct->auction_product != 1)
        @php
        $isInWishlist =
        auth()->check() &&
        \App\Models\Wishlist::where('user_id', auth()->id())
        ->where('product_id', $detailedProduct->id)
        ->exists();
        @endphp
        <div class="wishlist-btn-wrapper" style="position: absolute; top: 15px; right: 20px; z-index: 11;">
            <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }});"
                class="wishlist-btn d-flex align-items-center justify-content-center"
                style="background: white; border: 1px solid #e6e6e6 !important; border-radius: 50%; height: 48px; width: 48px; border: none; margin: 0; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12); transition: all 0.3s ease;">
                <i class="la la-heart{{ $isInWishlist ? '' : '-o' }} wishlist-heart-icon"
                    style="font-size: 24px; color: #dc3545 !important;"></i>
                <span class="wishlist-tooltip-custom">
                    Add to wishlist
                    <span class="wishlist-tooltip-arrow"></span>
                </span>
            </a>
        </div>
        @endif

        <div class="aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
            data-nav-for='.product-gallery-thumb' data-fade='true' data-auto-height='false' data-arrows='true' data-infinite='false' data-autoplay='false'>
            @if (empty($gallery_images))
            <div class="carousel-box img-zoom rounded-0">
                <img src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                    alt="{{ $detailedProduct->getTranslation('name') }}"
                    class="img-fluid w-100 h-100 carousal_image_custom_height"
                    width="800" height="600"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </div>
            @else
            @foreach ($gallery_images as $imgItem)
            <div class="carousel-box img-zoom rounded-0" @if($imgItem['variant']) data-variation="{{ $imgItem['variant'] }}" @endif>
                <img class="img-fluid w-100 h-100 carousal_image_custom_height"
                    src="{{ uploaded_asset($imgItem['image']) }}"
                    alt="{{ $imgItem['title'] }}"
                    width="800"
                    height="600"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <!-- Thumbnail Images -->
    <div class="col-12 mt-3">
        <div class="aiz-carousel product-gallery-thumb arrow-none"
            data-items='7' data-xl-items='7' data-lg-items='6' data-md-items='5' data-sm-items='4' data-xs-items='4'
            data-focus-select='true' data-arrows='false' data-autoplay='false' data-infinite='false' data-vertical='false'
            data-auto-height='false'>

            @if (empty($gallery_images))
            <div class="carousel-box c-pointer rounded-0">
                <img class="mw-100 size-60px mx-auto border p-1"
                    src="{{ uploaded_asset($detailedProduct->thumbnail_img) }}"
                    alt="{{ $detailedProduct->getTranslation('name') }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </div>
            @else
            @foreach ($gallery_images as $imgItem)
            <div class="carousel-box c-pointer rounded-0" @if($imgItem['variant']) data-variation="{{ $imgItem['variant'] }}" @endif>
                <img class="mw-100 size-60px mx-auto border p-1"
                    src="{{ uploaded_asset($imgItem['image']) }}"
                    alt="{{ $imgItem['title'] }}"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
            </div>
            @endforeach
            @endif

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery === 'undefined') return;

        var $gallery = $('.product-gallery');
        var $thumbs  = $('.product-gallery-thumb');
        var isSyncing = false;

        function syncThumbs(targetIndex) {
            if (isSyncing || typeof targetIndex === 'undefined' || targetIndex < 0) return;
            isSyncing = true;

            // Remove active and slick-current from ALL thumbnail slides first
            $thumbs.find('.slick-slide').removeClass('slick-current');
            $thumbs.find('.carousel-box').removeClass('active');

            var $targetThumbSlide = $thumbs.find('.slick-slide[data-slick-index="' + targetIndex + '"]');
            if ($targetThumbSlide.length) {
                $targetThumbSlide.addClass('slick-current');
                $targetThumbSlide.find('.carousel-box').addClass('active');
            } else {
                var $targetBox = $thumbs.find('.carousel-box').eq(targetIndex);
                $targetBox.addClass('active');
                $targetBox.closest('.slick-slide').addClass('slick-current');
            }

            if ($thumbs.hasClass('slick-initialized')) {
                $thumbs.slick('slickGoTo', targetIndex, true);
            }

            isSyncing = false;
        }

        // Sync main gallery slide changes to thumbnail slider position & active highlight
        $gallery.on('afterChange', function(event, slick, currentSlide) {
            if (isSyncing) return;
            syncThumbs(currentSlide);
        });

        // Click or tap any thumbnail -> change main gallery slide & scroll thumbnail track
        $(document).on('click', '.product-gallery-thumb .carousel-box, .product-gallery-thumb .slick-slide', function(e) {
            if (isSyncing) return;

            var $slide = $(this).closest('.slick-slide');
            var index = $slide.data('slick-index');

            if (typeof index === 'undefined') {
                index = $(this).index();
            }

            if (typeof index !== 'undefined' && index >= 0) {
                if ($gallery.hasClass('slick-initialized')) {
                    $gallery.slick('slickGoTo', index);
                }
                syncThumbs(index);
            }
        });
    });
</script>