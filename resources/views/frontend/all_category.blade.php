@extends('frontend.layouts.app')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        html { scroll-behavior: smooth; }

        .all-categories-page-wrap {
            background-color: #FAF8F5 !important;
        }

        .category-pills-wrap {
            position: sticky;
            top: 15px;
            z-index: 999;
            background-color: #FAF8F5 !important;
            padding: 15px 0;
            overflow: visible;
            box-shadow: 0 10px 30px rgba(104, 91, 78, 0.02);
            transition: top 0.2s ease;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* Nav arrows — hidden by default on desktop */
        .category-pills-nav-btn {
            display: none;
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #d8d0c3;
            color: #4e453c;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(104, 91, 78, 0.10);
            transition: all 0.2s ease;
            font-size: 16px;
        }

        .category-pills-nav-btn:hover {
            background: #4F4238;
            color: #ffffff;
            border-color: #4F4238;
        }

        .category-pills-nav-btn:disabled {
            opacity: 0.35;
            pointer-events: none;
        }

        .category-pills-viewport {
            flex: 1 1 auto;
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: smooth;
        }

        .category-pills-viewport::-webkit-scrollbar { display: none; }

        .category-pills-list {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: max-content;
            min-width: 100%;
            margin: 0;
            padding: 0 6px;
            list-style: none;
        }

        .category-pill-btn {
            flex: 0 0 auto;
            min-height: 46px;
            border-radius: 10px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            background-color: #ffffff !important;
            color: #4e453c !important;
            border: 1px solid #eae5d9 !important;
            font-family: 'Poppins', sans-serif !important;
            font-weight: 500 !important;
            font-size: 16px !important;
            text-decoration: none !important;
            transition: all 0.25s ease !important;
            box-sizing: border-box !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            opacity: 1;
        }

        .category-pill-btn a {
            display: flex;
            min-width: 100%;
            height: 100%;
            align-items: center;
            justify-content: center;
            padding: 12px 18px;
            color: inherit;
            text-decoration: none;
            border-radius: 10px;
        }

        /* ===== MOBILE: show arrows + one pill at a time ===== */
        @media (max-width: 767px) {
            .category-pills-wrap {
                gap: 8px;
                padding: 12px 0;
            }

            /* Always show arrow buttons on mobile */
            .category-pills-nav-btn {
                display: flex !important;
                flex: 0 0 40px;
                width: 40px;
                height: 40px;
            }

            .category-pills-viewport {
                scroll-snap-type: x mandatory;
                /* Flex child fills remaining space between the two buttons */
                flex: 1 1 0;
                min-width: 0;
            }

            .category-pills-list {
                justify-content: flex-start;
                gap: 0;
                padding: 0;
                /* Each pill must exactly equal the viewport width */
                width: 100%;
            }

            /* Each pill is full-width of the viewport → one visible at a time */
            .category-pill-btn {
                flex: 0 0 100%;
                width: 100%;
                min-width: 100%;
                scroll-snap-align: center;
                border-radius: 12px !important;
                min-height: 48px;
                font-size: 15px !important;
            }

            .category-pill-btn.active {
                background-color: #4F4238 !important;
                color: #ffffff !important;
                border-color: #4F4238 !important;
            }

            .category-pill-btn a {
                width: 100%;
                padding: 12px 16px;
                justify-content: center;
            }
        }

        .category-pill-btn:hover,
        .category-pill-btn.active {
            background-color: #4F4238 !important;
            color: #ffffff !important;
            border-color: #4F4238 !important;
            box-shadow: 0 4px 12px rgba(104, 91, 78, 0.15) !important;
            text-decoration: none !important;
        }

        .category-pill-btn:hover a,
        .category-pill-btn.active a {
            color: #ffffff !important;
        }

        .category-section-card {
            position: relative;
            background: #ffffff !important;
            border: 1px solid #21252933 !important;
            border-radius: 16px !important;
            padding: 40px 30px 30px 30px !important;
            margin-top: 40px !important;
            margin-bottom: 40px !important;
        }

        @media (max-width: 576px) {
            .category-section-card {
                padding: 25px 15px 15px 15px !important;
                margin-top: 20px !important;
            }
        }

        .category-section-title-wrap {
            position: absolute;
            top: -16px;
            left: 35px;
            background-color: #FAF8F5 !important;
            padding: 0 15px !important;
            z-index: 5;
        }

        @media (max-width: 576px) {
            .category-section-title-wrap {
                left: 20px;
                top: -12px;
                padding: 0 10px !important;
            }
        }

        .category-section-title {
            font-family: 'Playfair Display', serif !important;
            font-weight: 700 !important;
            font-size: 28px !important;
            line-height: 30px !important;
            color: #2e241c !important;
            margin: 0 !important;
        }

        @media (max-width: 576px) {
            .category-section-title {
                font-size: 20px !important;
                line-height: 24px !important;
            }
        }

        .category-section-title a {
            color: #2e241c !important;
            text-decoration: none !important;
            transition: color 0.2s ease !important;
        }

        .category-section-title a:hover { color: #685b4e !important; }

        .subcategory-card {
            background: transparent !important;
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            text-align: center;
            height: 100%;
            margin: 8px 0;
            display: block;
        }

        .subcategory-img-wrap {
            border-radius: 10px !important;
            overflow: hidden !important;
            background-color: #FAF7F2 !important;
            width: 100% !important;
            max-width: 257px !important;
            height: 236px !important;
            position: relative;
            margin-bottom: 12px;
            border: none !important;
            margin-left: auto !important;
            margin-right: auto !important;
        }

        @media only screen and (min-width:768px) and (max-width:1024px){
            .all-categories-page-wrap:first-child .category-section {
                    margin-top: 30px !important;
                }
        }
        @media (max-width: 576px) {
            .subcategory-img-wrap {
                height: 200px !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 100% !important;
            }
        }

        .subcategory-img {
            transition: transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) !important;
            object-fit: cover;
            width: 100%;
            height: 100%;
            border-radius: 10px !important;
        }

        .subcategory-card:hover .subcategory-img {
            transform: scale(1.06) !important;
        }

        .subcategory-name {
            font-family: 'Poppins', sans-serif !important;
            font-size: 18px !important;
            font-weight: 500 !important;
            color: #000000 !important;
            transition: color 0.2s ease !important;
            margin-top: 10px;
            display: block;
            text-align: center;
        }

        .subcategory-card:hover .subcategory-name { color: #685b4e !important; }

        .category-section-card .slick-list { margin: 0 -8px !important; }
        .category-section-card .slick-slide { padding: 0 8px !important; }
        .category-section-card .aiz-carousel {
            position: relative !important;
            overflow: visible !important;
        }
        .category-section-card .aiz-carousel .slick-track { justify-content: flex-start !important; }

        /* ===== Subcategory carousel arrows – desktop ===== */
        .all-categories-page-wrap .category-section-card .aiz-carousel .slick-prev,
        .all-categories-page-wrap .category-section-card .aiz-carousel .slick-next {
            position: absolute;
            top: 118px !important;
            transform: translateY(-50%) !important;
            width: 38px !important;
            height: 38px !important;
            border-radius: 8px !important;
            background: #ffffff !important;
            border: 1px solid #eae5d9 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
            z-index: 10;
            color: #4e453c !important;
            display: flex !important;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            font-size: 16px;
        }

        .all-categories-page-wrap .category-section-card .aiz-carousel .slick-prev:hover,
        .all-categories-page-wrap .category-section-card .aiz-carousel .slick-next:hover {
            background: #685b4e !important;
            color: #ffffff !important;
            border-color: #685b4e !important;
            box-shadow: 0 4px 12px rgba(104, 91, 78, 0.20) !important;
        }

        .all-categories-page-wrap .category-section-card .aiz-carousel .slick-prev { left: -50px !important; }
        .all-categories-page-wrap .category-section-card .aiz-carousel .slick-next { right: -50px !important; }

        /* ===== Subcategory carousel arrows – mobile ===== */
        @media (max-width: 767px) {
            /* On mobile the card has reduced padding (15px), so pull arrows
               just inside the card so they don't clip outside the page */
            .category-section-card {
                /* Extra horizontal padding to leave room for arrows */
                padding-left:30px !important;
                padding-right: 30px !important;
            }

            .all-categories-page-wrap .category-section-card .aiz-carousel .slick-prev,
            .all-categories-page-wrap .category-section-card .aiz-carousel .slick-next {
                display: flex !important;
                opacity: 1 !important;
                visibility: visible !important;
                z-index: 20 !important;
                top: 100px !important;
                width: 36px !important;
                height: 36px !important;
                background: #ffffff !important;
                border: 1px solid #d8d0c3 !important;
                box-shadow: 0 2px 8px rgba(0,0,0,0.10) !important;
            }

            /* Position arrows at the left/right edges of the card padding area */
            .all-categories-page-wrap .category-section-card .aiz-carousel .slick-prev { left: -45px !important; }
            .all-categories-page-wrap .category-section-card .aiz-carousel .slick-next { right: -45px !important; }

            /* Restore normal card padding at the top/bottom */
            .category-section-card {
                padding-top: 30px !important;
                padding-bottom: 20px !important;
            }
        }

        .category-section-card .slick-disabled {
            opacity: 0.25 !important;
            pointer-events: none;
        }
    </style>
    <!-- Breadcrumb -->
    {{-- Banner Section – use shared banner partial --}}
    @php
        $banner = [
            'title' => translate('All categories'),
            'breadcrumb_label' => translate('All categories'),
            // You can set a specific background image if needed, otherwise fallback will be used
            // 'background_image' => 'path/to/image.jpg',
        ];
    @endphp
    @include('frontend.custom-pages.partials.banner', ['banner' => $banner])

    <!-- All Categories Content -->
    <section class="mb-0 pb-3 all-categories-page-wrap">
        <div class="container pt-4">

            <!-- Category Pills/Tabs Navigation -->
            <div class="category-pills-wrap">
                <button type="button" class="category-pills-nav-btn category-pills-prev" aria-label="Previous categories">
                    <i class="las la-angle-left"></i>
                </button>
                <div class="category-pills-viewport">
                    <ul class="category-pills-list">
                        @foreach ($categories as $key => $category)
                            <li class="category-pill-btn {{ $key == 0 ? 'active' : '' }}">
                                <a href="#category-{{ $category->id }}">
                                    {{ $category->getTranslation('name') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="category-pills-nav-btn category-pills-next" aria-label="Next categories">
                    <i class="las la-angle-right"></i>
                </button>
            </div>

            <!-- Categories Sliders -->
            <div class="row">
                @foreach ($categories as $category)
                    <div class="col-md-12 category-section" id="category-{{ $category->id }}">
                        <div class="category-section-card">

                            <!-- Category Name Wrapper to Cut the Border -->
                            <div class="category-section-title-wrap">
                                <h2 class="category-section-title">
                                    <a href="{{ route('products.category', $category->slug) }}" class="text-reset text-decoration-none">
                                        {{ $category->getTranslation('name') }}
                                    </a>
                                </h2>
                            </div>

                            <!-- Subcategories Slider -->
                            @if ($category->childrenCategories->count() > 0)
                                <div class="aiz-carousel border-right-0"
                                     data-items="5"
                                     data-xl-items="5"
                                     data-lg-items="4"
                                     data-md-items="3"
                                     data-sm-items="1"
                                     data-xs-items="1"
                                     data-arrows="true"
                                     data-infinite="false">

                                    @foreach ($category->childrenCategories as $child_category)
                                        @php
                                            $img_id = $child_category->banner ?: ($child_category->cover_image ?: $child_category->icon);
                                            $img_url = $img_id ? uploaded_asset($img_id) : static_asset('assets/img/default_subcategory.jpg');
                                        @endphp
                                        <div class="carousel-box">
                                            <div class="subcategory-card">
                                                <a href="{{ route('products.category', $child_category->slug) }}" class="d-block text-reset text-decoration-none">
                                                    <div class="subcategory-img-wrap">
                                                        <img class="subcategory-img lazyload"
                                                             src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                             data-src="{{ $img_url }}"
                                                             alt="{{ $child_category->getTranslation('name') }}"
                                                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                    </div>
                                                    <span class="subcategory-name text-truncate-2">
                                                        {{ $child_category->getTranslation('name') }}
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    {{ translate('No subcategories found.') }}
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>

    <script>
        function adjustStickyPills() {
            var headerHeight = $('header').outerHeight() || 0;
            var isStickyHeader = $('header').hasClass('sticky-top') || $('header').css('position') === 'fixed' || $('header').css('position') === 'sticky';

            $('.category-pills-wrap').css('top', isStickyHeader ? (headerHeight + 'px') : '15px');
        }

        function updatePillArrowState() {
            var viewport = document.querySelector('.category-pills-viewport');
            var prevBtn = document.querySelector('.category-pills-prev');
            var nextBtn = document.querySelector('.category-pills-next');

            if (!viewport || !prevBtn || !nextBtn) {
                return;
            }

            var maxScrollLeft = Math.max(0, viewport.scrollWidth - viewport.clientWidth);
            var currentScrollLeft = viewport.scrollLeft;

            prevBtn.disabled = currentScrollLeft <= 2;
            nextBtn.disabled = maxScrollLeft <= 2 || currentScrollLeft >= maxScrollLeft - 2;
        }

        function scrollPills(direction) {
            var viewport = document.querySelector('.category-pills-viewport');
            if (!viewport) return;

            var isMobile = window.innerWidth <= 767;

            if (isMobile) {
                // On mobile: scroll exactly one pill width (full viewport)
                var pillWidth = viewport.clientWidth;
                viewport.scrollBy({
                    left: direction === 'next' ? pillWidth : -pillWidth,
                    behavior: 'smooth'
                });

                // After scrolling, update active pill
                setTimeout(function() {
                    var scrollLeft = viewport.scrollLeft;
                    var newIndex = Math.round(scrollLeft / pillWidth);
                    var pills = document.querySelectorAll('.category-pill-btn');
                    pills.forEach(function(p, i) {
                        p.classList.toggle('active', i === newIndex);
                    });
                    updatePillArrowState();
                }, 350);
            } else {
                var visibleWidth = viewport.clientWidth;
                var scrollAmount = Math.max(visibleWidth * 0.8, 320);
                viewport.scrollBy({
                    left: direction === 'next' ? scrollAmount : -scrollAmount,
                    behavior: 'smooth'
                });
            }
        }

        // ── Force slick arrows on mobile for category carousels ──────────────
        // aiz-core.js hardcodes arrows:false below 992 px which removes the
        // arrow DOM elements entirely. We re-inject them after every init /
        // breakpoint change so they always appear inside .category-section-card.
        function forceSubcategoryArrows($carousel) {
            if (!$carousel || !$carousel.length) return;
            var $slick = $carousel;

            // If slick hasn't been initialised yet, bail – the init event will fire
            if (!$slick.hasClass('slick-initialized')) return;

            // Re-enable arrows via slick's public API (won't take effect visually
            // but makes slick aware) then manually inject the buttons if absent.
            var prevArrowHTML = '<button type="button" class="slick-prev slick-arrow"><i class="las la-angle-left"></i></button>';
            var nextArrowHTML = '<button type="button" class="slick-next slick-arrow"><i class="las la-angle-right"></i></button>';

            if (!$slick.find('.slick-prev').length) {
                $slick.find('.slick-list').before(prevArrowHTML);
            }
            if (!$slick.find('.slick-next').length) {
                $slick.find('.slick-list').after(nextArrowHTML);
            }

            // Wire up click handlers (delegated so works even when re-injected)
        }

        function wireSubcategoryArrowClicks() {
            // Use delegated events so they work after dynamic injection
            $(document).off('click.subcatArrow', '.category-section-card .slick-prev');
            $(document).off('click.subcatArrow', '.category-section-card .slick-next');

            $(document).on('click.subcatArrow', '.category-section-card .slick-prev', function() {
                $(this).closest('.aiz-carousel').slick('slickPrev');
            });
            $(document).on('click.subcatArrow', '.category-section-card .slick-next', function() {
                $(this).closest('.aiz-carousel').slick('slickNext');
            });
        }

        $(function() {
            adjustStickyPills();
            $(window).on('resize scroll', adjustStickyPills);

            updatePillArrowState();

            $(window).on('resize', updatePillArrowState);

            $(document).on('click', '.category-pills-prev', function() {
                scrollPills('prev');
            });

            $(document).on('click', '.category-pills-next', function() {
                scrollPills('next');
            });

            $('.category-pills-viewport').on('scroll', updatePillArrowState);

            $(document).on('click', '.category-pill-btn a', function(e) {
                e.preventDefault();

                var targetId = $(this).attr('href');
                var $target = $(targetId);

                if (!$target.length) {
                    return;
                }

                var headerHeight = $('header').outerHeight() || 0;
                var pillsHeight = $('.category-pills-wrap').outerHeight() || 0;
                var targetOffset = $target.offset().top - (headerHeight + pillsHeight + 35);

                $('html, body').animate({ scrollTop: targetOffset }, 500);

                $('.category-pill-btn').removeClass('active');
                $(this).closest('.category-pill-btn').addClass('active');
            });

            $(window).on('scroll', function() {
                var scrollPos = $(document).scrollTop();
                var headerHeight = $('header').outerHeight() || 0;
                var pillsHeight = $('.category-pills-wrap').outerHeight() || 0;

                $('.category-section').each(function() {
                    var refElement = $(this);
                    var topBound = refElement.offset().top - (headerHeight + pillsHeight + 45);
                    var bottomBound = topBound + refElement.outerHeight();

                    if (scrollPos >= topBound && scrollPos < bottomBound) {
                        var targetId = '#' + refElement.attr('id');
                        $('.category-pill-btn').removeClass('active');
                        $('.category-pill-btn a[href="' + targetId + '"]').closest('.category-pill-btn').addClass('active');
                    }
                });
            });

            $('.category-pills-viewport').on('scroll', function() {
                updatePillArrowState();
            });

            // ── Subcategory carousel arrow fix ──────────────────────────────
            // Hook slick events BEFORE aiz-core initialises the carousels so
            // we catch init + every breakpoint change.
            $(document).on('init afterChange breakpoint', '.category-section-card .aiz-carousel', function(e, slick) {
                forceSubcategoryArrows($(this));
            });

            // Also run once shortly after page load to catch already-inited carousels
            setTimeout(function() {
                $('.category-section-card .aiz-carousel.slick-initialized').each(function() {
                    forceSubcategoryArrows($(this));
                });
                wireSubcategoryArrowClicks();
            }, 800);

            // Re-run on window resize (slick reinitialises responsive breakpoints)
            $(window).on('resize', function() {
                setTimeout(function() {
                    $('.category-section-card .aiz-carousel.slick-initialized').each(function() {
                        forceSubcategoryArrows($(this));
                    });
                }, 300);
            });

            wireSubcategoryArrowClicks();
        });
    </script>
@endsection
