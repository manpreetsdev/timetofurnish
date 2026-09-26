@php
$home_offers = \App\Models\Offer::homeSection()->with('products')->get();
@endphp
@if ($home_offers->count() > 0)
<style>
    /* Habitat-Style Light Clean Offer Banner */
    .habitat-offer-wrapper {
        margin-top: 32px !important;
        margin-bottom: 40px !important;
    }

    .habitat-offer-card {
        background-color: #f4f3f0 !important;
        border-radius: 20px !important;
        overflow: hidden !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
        border: 1px solid #eae6df !important;
    }

    .habitat-left-content {
        padding: 40px 36px !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .habitat-eyebrow-pill {
        background-color: #5f4d3e !important;
        color: #ffffff !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 5px 14px !important;
        border-radius: 4px !important;
        display: inline-flex;
        align-items: center;
        width: fit-content;
    }

    .habitat-badge-pill {
        background-color: #ffffff !important;
        color: #5f4d3e !important;
        border: 1px solid #dcd4c8 !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 5px 12px !important;
        border-radius: 4px !important;
        display: inline-flex;
        align-items: center;
    }

    .habitat-offer-title {
        color: #1d1712 !important;
        font-family: 'Playfair Display', Georgia, serif !important;
        font-size: clamp(26px, 2.8vw, 40px) !important;
        font-weight: 700 !important;
        line-height: 1.2 !important;
        letter-spacing: -0.4px;
        margin-top: 14px;
        margin-bottom: 12px !important;
    }

    .habitat-offer-desc {
        color: #4a4642 !important;
        font-size: 15px !important;
        line-height: 1.6 !important;
        margin-bottom: 24px !important;
        max-width: 460px;
    }

    /* Habitat Countdown Timer */
    .habitat-timer-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 28px !important;
    }

    .habitat-timer-wrap .timer-card {
        background: #ffffff !important;
        border: 1px solid #e2dcd3 !important;
        border-radius: 8px !important;
        width: 54px;
        height: 56px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .habitat-timer-wrap .timer-num {
        font-size: 19px;
        font-weight: 800;
        color: #5f4d3e;
        line-height: 1;
    }

    .habitat-timer-wrap .timer-txt {
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase;
        color: #8a7e72;
        letter-spacing: 0.6px;
        margin-top: 3px;
    }

    .habitat-timer-wrap .timer-divider {
        font-size: 19px;
        font-weight: 700;
        color: #aaa197;
        line-height: 56px;
    }

    /* Habitat Style Clean CTA Button */
    .habitat-cta-btn {
        background-color: #5f4d3e !important;
        border: 2px solid #5f4d3e !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 14px !important;
        padding: 12px 30px !important;
        border-radius: 6px !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.25s ease !important;
        text-decoration: none !important;
        width: fit-content;
    }

    .habitat-cta-btn:hover {
        background-color: #4a3c30 !important;
        border-color: #4a3c30 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(95, 77, 62, 0.25) !important;
    }

    /* Right Column Padded Frame */
    .habitat-right-column-wrap {
        padding: 24px !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .habitat-spotlight-card {
        background: #ffffff !important;
        border-radius: 16px !important;
        border: 1px solid #eae4da !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
        width: 100%;
        position: relative;
    }

    /* Multi-Image Grid Layout Inside Card */
    .habitat-grid-container {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: #ffffff;
    }

    .habitat-main-tile {
        flex: 1 1 70%;
        position: relative;
        height: 310px;
        border-radius: 12px;
        overflow: hidden;
        background: #faf8f5;
        border: 1px solid #eee8df;
    }

    .habitat-main-tile.full-width-tile {
        flex: 1 1 100%;
    }

    .habitat-spotlight-img {
        width: 100%;
        height: 100%;
        object-fit: cover !important;
        display: block;
        transition: transform 0.35s ease, opacity 0.2s ease;
    }

    .habitat-main-tile:hover .habitat-spotlight-img {
        transform: scale(1.03);
    }

    .habitat-gallery-side-grid {
        flex: 0 0 28%;
        display: flex;
        flex-direction: column;
        gap: 10px;
        justify-content: space-between;
    }

    .habitat-gallery-thumb-tile {
        height: 93px;
        border-radius: 10px;
        overflow: hidden;
        background: #faf8f5;
        border: 2px solid #e8e2d8;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }

    .habitat-gallery-thumb-tile:hover,
    .habitat-gallery-thumb-tile.active {
        border-color: #5f4d3e !important;
        box-shadow: 0 4px 12px rgba(95, 77, 62, 0.2);
        transform: translateY(-2px);
    }

    .habitat-gallery-thumb-tile img {
        width: 100%;
        height: 100%;
        object-fit: cover !important;
    }

    /* +N More Overlay on Last Gallery Thumbnail */
    .habitat-more-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(45, 36, 28, 0.78) !important;
        backdrop-filter: blur(2px) !important;
        -webkit-backdrop-filter: blur(2px) !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        justify-content: center !important;
        color: #ffffff !important;
        border-radius: 8px !important;
        transition: all 0.25s ease !important;
        z-index: 5 !important;
        text-decoration: none !important;
    }

    .habitat-gallery-thumb-tile:hover .habitat-more-overlay {
        background: rgba(95, 77, 62, 0.92) !important;
    }

    .habitat-more-overlay .more-num {
        font-size: 17px !important;
        font-weight: 800 !important;
        line-height: 1 !important;
        color: #ffffff !important;
    }

    .habitat-more-overlay .more-txt {
        font-size: 9px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.6px !important;
        margin-top: 3px !important;
        color: #f3ece4 !important;
    }

    /* Image Overlays Inside Main Tile */
    .habitat-img-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        z-index: 10;
        background-color: #5f4d3e !important;
        color: #ffffff !important;
        font-size: 11px;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 4px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 4px 12px rgba(95, 77, 62, 0.25);
    }

    .habitat-img-wishlist {
        position: absolute;
        top: 14px;
        right: 14px;
        z-index: 10;
        background: #ffffff;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee8df;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        color: #5f4d3e;
        transition: all 0.2s ease;
        padding: 0;
        cursor: pointer;
    }

    .habitat-img-wishlist:hover {
        transform: scale(1.1);
        background: #5f4d3e;
        color: #ffffff;
    }

    /* Bottom Product Details & Action Bar */
    .habitat-spotlight-info-bar {
        background: #ffffff;
        border-top: 1px solid #f0eae1;
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 10;
    }

    .habitat-product-info {
        max-width: 65%;
    }

    .habitat-product-name {
        font-size: 15px;
        font-weight: 700;
        color: #1d1712;
        margin-bottom: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        text-decoration: none !important;
    }

    .habitat-product-name:hover {
        color: #5f4d3e;
    }

    .habitat-price-current {
        font-size: 16px;
        font-weight: 800;
        color: #5f4d3e;
    }

    .habitat-price-old {
        font-size: 12px;
        text-decoration: line-through;
        color: #9e958c;
        margin-left: 6px;
    }

    .habitat-add-btn {
        background-color: #5f4d3e !important;
        color: #ffffff !important;
        font-size: 12px !important;
        font-weight: 800 !important;
        padding: 9px 20px !important;
        border-radius: 30px !important;
        border: none !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease !important;
        text-decoration: none !important;
        box-shadow: 0 4px 12px rgba(95, 77, 62, 0.2);
    }

    .habitat-add-btn:hover {
        background-color: #4a3c30 !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(95, 77, 62, 0.3);
    }

    /* Base Slider Controls Styling */
    .habitat-products-slider {
        position: relative;
    }

    .habitat-products-slider .slick-prev,
    .habitat-products-slider .slick-next {
        position: absolute !important;
        top: 45% !important;
        transform: translateY(-50%) !important;
        z-index: 30 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background: #ffffff !important;
        border: 1px solid #e0d8ce !important;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14) !important;
        color: #5f4d3e !important;
        width: 36px !important;
        height: 36px !important;
        transition: all 0.25s ease !important;
        border-radius: 50% !important;
    }

    .habitat-products-slider .slick-prev:hover,
    .habitat-products-slider .slick-next:hover {
        background: #5f4d3e !important;
        color: #ffffff !important;
        border-color: #5f4d3e !important;
    }

    .habitat-products-slider .slick-prev {
        left: 10px !important;
    }

    .habitat-products-slider .slick-next {
        right: 10px !important;
    }

    /* Desktop vs Mobile Responsive Controls: Desktop = Arrows ONLY, Mobile = Dots ONLY */
    @media (min-width: 768px) {
        .habitat-products-slider .slick-dots {
            display: none !important;
        }

        .habitat-products-slider .slick-prev,
        .habitat-products-slider .slick-next {
            display: flex !important;
        }
    }

    @media (max-width: 767.98px) {

        .habitat-products-slider .slick-prev,
        .habitat-products-slider .slick-next {
            display: none !important;
        }

        .habitat-products-slider .slick-dots {
            display: flex !important;
            position: relative !important;
            bottom: auto !important;
            margin:2px 0 14px 0 !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 0 !important;
            z-index: 25 !important;
            list-style: none !important;
        }

        .habitat-products-slider .slick-dots li {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1 !important;
        }

        .habitat-products-slider .slick-dots li button {
            width: 8px !important;
            height: 8px !important;
            padding: 0 !important;
            border-radius: 50% !important;
            background: #d4c8bc !important;
            border: none !important;
            font-size: 0 !important;
            transition: all 0.3s ease !important;
        }

        .habitat-products-slider .slick-dots li.slick-active button {
            width: 8px !important;
            border-radius: 10px !important;
            background: #5f4d3e !important;
        }
    }

    @media (max-width: 991.98px) {
        .habitat-left-content {
            padding: 30px 24px !important;
        }

        .habitat-right-column-wrap {
            padding: 16px !important;
        }

        .habitat-main-tile {
            height: 260px;
        }

        .habitat-gallery-thumb-tile {
            height: 77px;
        }
    }

    @media (max-width: 575.98px) {
        .habitat-left-content {
            padding: 24px 18px !important;
        }

        .habitat-right-column-wrap {
            padding: 10px !important;
        }

        .habitat-spotlight-card {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            margin-bottom: 24px !important;
        }

        .habitat-cta-btn {
            width: 100% !important;
        }

        .habitat-grid-container {
            flex-direction: column;
            padding: 10px;
            gap: 8px;
        }

        .habitat-main-tile {
            flex: 0 0 auto !important;
            height: 175px !important;
        }

        .habitat-main-tile.full-width-tile {
            height: 240px !important;
        }

        .habitat-gallery-side-grid {
            flex: 0 0 auto !important;
            flex-direction: row;
            gap: 6px;
        }

        .habitat-gallery-thumb-tile {
            flex: 1 1 33.33%;
            height: 60px !important;
        }

        .habitat-spotlight-info-bar {
            padding: 10px 12px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-top: auto;
        }

        .habitat-product-info {
            max-width: 100%;
        }

        .habitat-add-btn {
            width: 100%;
            justify-content: center;
        }

        /* Clean Overlay Dots Floating Inside Card Bottom on Mobile */
        .habitat-products-slider .slick-dots {
            display: flex !important;
          
        }
    }
</style>

<section class="habitat-offer-wrapper">
    <div class="container">
        <div class="aiz-carousel" data-items="1" data-arrows="false" data-dots="false" data-autoplay="false" data-infinite="true">
            @foreach ($home_offers as $offer)
            <div class="carousel-box">
                <div class="habitat-offer-card w-100">
                    <div class="row no-gutters align-items-center">

                        <!-- Left Column: Offer Details & Countdown (Habitat Style) -->
                        <div class="col-lg-5 col-md-6 col-12 habitat-left-content">
                            <div class="d-flex flex-wrap align-items-center mb-2" style="gap: 8px;">
                                <span class="habitat-eyebrow-pill">
                                    {{ translate($offer->name) }}
                                </span>
                                @if ($offer->badge_text)
                                @php
                                $badge_txt = $offer->badge_text;
                                if (is_numeric($badge_txt) || (str_ends_with($badge_txt, '%') && !str_contains(strtolower($badge_txt), 'off'))) {
                                $badge_txt .= ' OFF';
                                }
                                @endphp
                                <span class="habitat-badge-pill">
                                    {{ $badge_txt }}
                                </span>
                                @endif
                            </div>

                            <h2 class="habitat-offer-title">
                                {{ translate($offer->name) }}
                            </h2>

                            @if ($offer->custom_text)
                            <p class="habitat-offer-desc">
                                {{ $offer->custom_text }}
                            </p>
                            @endif

                            @if ($offer->ends_at)
                            <div class="habitat-timer-wrap" data-end-date="{{ $offer->ends_at->format('Y/m/d H:i:s') }}">
                                <div class="timer-card">
                                    <span class="days timer-num">00</span>
                                    <span class="timer-txt">{{ translate('Days') }}</span>
                                </div>
                                <span class="timer-divider">:</span>
                                <div class="timer-card">
                                    <span class="hours timer-num">00</span>
                                    <span class="timer-txt">{{ translate('Hours') }}</span>
                                </div>
                                <span class="timer-divider">:</span>
                                <div class="timer-card">
                                    <span class="minutes timer-num">00</span>
                                    <span class="timer-txt">{{ translate('Mins') }}</span>
                                </div>
                                <span class="timer-divider">:</span>
                                <div class="timer-card">
                                    <span class="seconds timer-num">00</span>
                                    <span class="timer-txt">{{ translate('Secs') }}</span>
                                </div>
                            </div>
                            @endif

                            <div>
                                @if ($offer->products->count() > 0)
                                <a href="{{ route('product', $offer->products->first()->slug) }}" class="habitat-cta-btn offer-shop-deal-btn">
                                    <span>{{ translate('Shop Deal') }}</span>
                                    <i class="las la-arrow-right fs-16"></i>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Right Column: Spotlight Padded Card with Multi-Image Grid -->
                        <div class="col-lg-7 col-md-6 col-12 habitat-right-column-wrap">
                            <div class="habitat-spotlight-card">
                                @if ($offer->products->count() > 0)
                                @php
                                $has_multiple = $offer->products->count() > 1;
                                @endphp
                                <div class="aiz-carousel habitat-products-slider"
                                    data-items="1"
                                    data-arrows="{{ $has_multiple ? 'true' : 'false' }}"
                                    data-dots="{{ $has_multiple ? 'true' : 'false' }}"
                                    data-autoplay="false"
                                    data-infinite="true">
                                    @foreach ($offer->products as $product)
                                    @php
                                    $product_url = route('product', $product->slug);
                                    $active_offer = get_product_active_offer($product);
                                    $badge_txt = !empty($offer->badge_text) ? $offer->badge_text : ($active_offer->badge_text ?? translate('SPECIAL OFFER'));
                                    if (is_numeric($badge_txt) || (str_ends_with($badge_txt, '%') && !str_contains(strtolower($badge_txt), 'off'))) {
                                    $badge_txt .= ' OFF';
                                    }
                                    $old_offer_price = home_offer_old_price($product);
                                    $disc_price = home_discounted_base_price($product);

                                    // Collect all images (thumbnail + gallery photos)
                                    $all_images = [];
                                    if (!empty($product->photos)) {
                                    $raw_photos = array_filter(explode(',', $product->photos));
                                    foreach ($raw_photos as $rp) {
                                    if (!empty($rp) && !in_array($rp, $all_images)) {
                                    $all_images[] = $rp;
                                    }
                                    }
                                    }
                                    if (!empty($product->thumbnail) && !in_array($product->thumbnail, $all_images)) {
                                    array_unshift($all_images, $product->thumbnail);
                                    }
                                    if (empty($all_images) && !empty($product->thumbnail_img)) {
                                    $all_images[] = $product->thumbnail_img;
                                    }
                                    $has_gallery_grid = count($all_images) > 1;
                                    @endphp
                                    <div class="carousel-box position-relative" data-product-url="{{ $product_url }}">

                                        <!-- Image Grid Container (Main Photo + Gallery Grid) -->
                                        <div class="habitat-grid-container">
                                            <!-- Main Spotlight Image Tile -->
                                            <div class="habitat-main-tile {{ !$has_gallery_grid ? 'full-width-tile' : '' }}">
                                                <a href="{{ $product_url }}" class="d-block w-100 h-100">
                                                    <img src="{{ get_image($all_images[0] ?? $product->thumbnail) }}"
                                                        id="habitat-main-img-{{ $product->id }}"
                                                        alt="{{ $product->getTranslation('name') }}"
                                                        class="habitat-spotlight-img"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>

                                                <!-- Top Left Offer Badge -->
                                                <span class="habitat-img-badge">
                                                    {{ translate($badge_txt) }}
                                                </span>

                                                <!-- Top Right Wishlist Button -->
                                                <button type="button" class="habitat-img-wishlist" onclick="addToWishList({{ $product->id }})" title="{{ translate('Add to wishlist') }}" aria-label="{{ translate('Add to wishlist') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Gallery Side Grid (Only if product has multiple images) -->
                                            @if ($has_gallery_grid)
                                            @php
                                            $total_imgs = count($all_images);
                                            $side_imgs = array_slice($all_images, 1, 3);
                                            $more_count = $total_imgs > 4 ? ($total_imgs - 4) : 0;
                                            @endphp
                                            <div class="habitat-gallery-side-grid">
                                                @foreach ($side_imgs as $g_index => $g_img)
                                                @php
                                                $g_url = get_image($g_img);
                                                $is_last_thumb = ($g_index === 2 && $more_count > 0);
                                                @endphp
                                                <div class="habitat-gallery-thumb-tile {{ $g_index === 0 ? 'active' : '' }}"
                                                    @if ($is_last_thumb)
                                                    onclick="window.location.href='{{ $product_url }}'"
                                                    @else
                                                    onclick="switchHabitatOfferImg(this, '{{ $g_url }}', '{{ $product->id }}')"
                                                    @endif>
                                                    <img src="{{ $g_url }}"
                                                        alt="Gallery photo {{ $g_index + 2 }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                                                    @if ($is_last_thumb)
                                                    <div class="habitat-more-overlay" title="{{ translate('View all images') }}">
                                                        <span class="more-num">+{{ $more_count }}</span>
                                                        <span class="more-txt">{{ translate('View All') }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Bottom Product Info & Add to Basket Bar -->
                                        <div class="habitat-spotlight-info-bar">
                                            <div class="habitat-product-info">
                                                <a href="{{ $product_url }}" class="habitat-product-name" title="{{ $product->getTranslation('name') }}">
                                                    {{ $product->getTranslation('name') }}
                                                </a>
                                                <div class="d-flex align-items-center">
                                                    <span class="habitat-price-current">{{ $disc_price }}</span>
                                                    @if ($old_offer_price)
                                                    <span class="habitat-price-old">{{ $old_offer_price }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <a href="{{ $product_url }}" class="habitat-add-btn">
                                                <span>{{ translate('Add to Basket') }}</span>
                                                <i class="las la-shopping-cart fs-14"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script type="text/javascript">
    // Global image switcher function for multi-image grid
    function switchHabitatOfferImg(thumbElem, imgUrl, productId) {
        if (typeof $ === 'undefined') return;
        var $mainImg = $('#habitat-main-img-' + productId);
        if ($mainImg.length) {
            $mainImg.css('opacity', '0.4');
            setTimeout(function() {
                $mainImg.attr('src', imgUrl);
                $mainImg.css('opacity', '1');
            }, 120);
        }
        $(thumbElem).siblings().removeClass('active');
        $(thumbElem).addClass('active');
    }

    document.addEventListener("DOMContentLoaded", function() {
        // Countdown timer logic
        const timers = document.querySelectorAll('.habitat-timer-wrap');
        timers.forEach(function(timer) {
            const endDateStr = timer.getAttribute('data-end-date');
            if (!endDateStr) return;
            const endDate = new Date(endDateStr).getTime();

            const daysVal = timer.querySelector('.days');
            const hoursVal = timer.querySelector('.hours');
            const minsVal = timer.querySelector('.minutes');
            const secsVal = timer.querySelector('.seconds');

            function updateTimer() {
                const now = new Date().getTime();
                const difference = endDate - now;

                if (difference <= 0) {
                    if (daysVal) daysVal.innerText = '00';
                    if (hoursVal) hoursVal.innerText = '00';
                    if (minsVal) minsVal.innerText = '00';
                    if (secsVal) secsVal.innerText = '00';
                    return;
                }

                const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                if (daysVal) daysVal.innerText = String(days).padStart(2, '0');
                if (hoursVal) hoursVal.innerText = String(hours).padStart(2, '0');
                if (minsVal) minsVal.innerText = String(minutes).padStart(2, '0');
                if (secsVal) secsVal.innerText = String(seconds).padStart(2, '0');
            }

            updateTimer();
            setInterval(updateTimer, 1000);
        });

        // Dynamic "Shop Deal" Button URL Update on Slide Change
        if (typeof $ !== 'undefined') {
            function bindOfferSlideEvents() {
                $('.habitat-products-slider').each(function() {
                    const $slider = $(this);
                    $slider.off('afterChange.offerDealLink').on('afterChange.offerDealLink', function(event, slick, currentSlide) {
                        const $slides = $(slick.$slides);
                        if ($slides.length > 0) {
                            const $active = $($slides[currentSlide]);
                            const pUrl = $active.find('[data-product-url]').data('product-url') || $active.data('product-url');
                            if (pUrl) {
                                $slider.closest('.habitat-offer-card').find('.offer-shop-deal-btn').attr('href', pUrl);
                            }
                        }
                    });
                });
            }

            bindOfferSlideEvents();
            setTimeout(bindOfferSlideEvents, 500);
            setTimeout(bindOfferSlideEvents, 1500);
        }
    });
</script>
@endif