@php
    $homepage_reviews = \App\Models\HomepageReview::where('status', 1)->get();
    $section_status = get_setting('homepage_reviews_section_status', 1);
    $desktop_slider = get_setting('homepage_reviews_desktop_slider', 1);
@endphp

@if ($section_status == 1 && count($homepage_reviews) > 0)
    <section class="py-6 py-md-7 position-relative" style="background-image: url('{{ static_asset('assets/img/reviews_bg_luxury.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; min-height: 600px; overflow: hidden; border-top: 1px solid #f2ebe1; border-bottom: 1px solid #f2ebe1;">
        <!-- Warm Beige overlay to match base theme color (#685b4e) instead of dark grey -->
        <div class="position-absolute" style="top:0; left:0; right:0; bottom:0; background: linear-gradient(180deg, rgba(251, 249, 246, 0.86) 0%, rgba(243, 236, 228, 0.84) 100%); z-index: 1;"></div>
        
        <div class="container position-relative" style="z-index: 2;">
            <!-- Section Header -->
            <div class="row justify-content-center mb-5 text-center">
                <div class="col-lg-6">
                    <span class="d-inline-block text-uppercase fw-600 tracking-wider fs-11 mb-2" style="letter-spacing: 2px; color: #a18a68 !important;">{{ translate('Testimonials') }}</span>
                    <h2 class="fs-24 fs-md-36 fw-700 mb-3" style="color: #39322a;">{{ translate('What Our Clients Say') }}</h2>
                    <p class="opacity-70 fs-14" style="margin-bottom: 20px; color: #5d5247;">{{ translate('Real experiences shared by our happy customers.') }}</p>
                    <div class="mx-auto" style="width: 50px; height: 3px; background-color: #685b4e; border-radius: 2px;"></div>
                </div>
            </div>

            <!-- Custom Glassmorphism Testimonial Style (Warm Light Theme) -->
            <style>
                .review-card {
                    background: rgba(255, 255, 255, 0.72);
                    backdrop-filter: blur(16px);
                    -webkit-backdrop-filter: blur(16px);
                    border: 1px solid rgba(104, 91, 78, 0.12);
                    border-radius: 20px;
                    padding: 2.25rem 2rem;
                    min-height: 320px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    position: relative;
                    box-shadow: 0 15px 35px rgba(104, 91, 78, 0.04);
                    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                }
                .review-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 25px 50px rgba(104, 91, 78, 0.12);
                    border-color: rgba(104, 91, 78, 0.8);
                    background: rgba(255, 255, 255, 0.92);
                }
                .review-card.featured-border {
                    border-color: rgba(104, 91, 78, 0.25);
                }
                .review-quote-icon {
                    position: absolute;
                    bottom: 80px;
                    right: 25px;
                    font-size: 5rem;
                    color: rgba(104, 91, 78, 0.05);
                    line-height: 1;
                    pointer-events: none;
                }
                .review-rating {
                    color: #c5a059;
                    font-size: 1rem;
                    margin-bottom: 1rem;
                }
                .purchased-text {
                    font-size: 0.85rem;
                    font-weight: 600;
                    color: #685b4e;
                    margin-bottom: 0.75rem;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                .review-content {
                    font-size: 0.925rem;
                    line-height: 1.6;
                    color: #4a433c;
                    flex-grow: 1;
                    margin-bottom: 1.5rem;
                    font-style: normal;
                }
                .reviewer-avatar {
                    border: 2px solid rgba(104, 91, 78, 0.15);
                }
                .review-card:hover .reviewer-avatar {
                    border-color: #685b4e;
                    transform: scale(1.05);
                }
                .reviewer-name {
                    font-weight: 700;
                    color: #39322a;
                    font-size: 15px;
                }
                .review-relative-date {
                    font-size: 11px;
                    color: #8c7e70;
                }
                .review-card-divider {
                    border-top: 1px solid rgba(104, 91, 78, 0.1);
                    margin-top: auto;
                    padding-top: 1.25rem;
                }
                .category-badge {
                    background: rgba(104, 91, 78, 0.07);
                    color: #685b4e;
                    border: 1px solid rgba(104, 91, 78, 0.15);
                    border-radius: 20px;
                    font-size: 10.5px;
                    padding: 5px 14px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }
                .review-card:hover .category-badge {
                    background: #685b4e;
                    color: #ffffff;
                    border-color: #685b4e;
                }
                .helpful-votes {
                    color: #7c7165;
                    font-size: 11.5px;
                    font-weight: 500;
                    display: flex;
                    align-items: center;
                }
                .full-image-review-box {
                    border-radius: 20px;
                    overflow: hidden;
                    border: 1px solid rgba(104, 91, 78, 0.12);
                    box-shadow: 0 15px 35px rgba(104, 91, 78, 0.04);
                    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                    position: relative;
                    background: rgba(255, 255, 255, 0.72);
                }
                .full-image-review-box:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 25px 50px rgba(104, 91, 78, 0.12);
                    border-color: rgba(104, 91, 78, 0.8);
                }
                .full-image-review-box img {
                    width: 100%;
                    height: auto;
                    display: block;
                    transition: transform 0.4s ease;
                }
                .full-image-review-box:hover img {
                    transform: scale(1.03);
                }
                .homepage-reviews-slider .slick-slide {
                    padding: 15px 15px;
                }
                .homepage-reviews-slider .slick-list {
                    margin: 0 -15px;
                }
                /* Navigation arrows matching the theme */
                .homepage-reviews-slider .slick-arrow {
                    background: rgba(255, 255, 255, 0.95);
                    border: 1px solid rgba(104, 91, 78, 0.2);
                    color: #685b4e;
                    box-shadow: 0 4px 15px rgba(104, 91, 78, 0.08);
                    transition: all 0.3s ease;
                    border-radius: 50%;
                    width: 44px;
                    height: 44px;
                    display: flex !important;
                    align-items: center;
                    justify-content: center;
                }
                .homepage-reviews-slider .slick-arrow:hover {
                    background: #685b4e;
                    color: #ffffff;
                    border-color: #685b4e;
                }
                /* Custom slider dot styling */
                .homepage-reviews-slider .slick-dots li button:before {
                    color: #685b4e;
                    font-size: 8px;
                    opacity: 0.25;
                }
                .homepage-reviews-slider .slick-dots li.slick-active button:before {
                    color: #685b4e;
                    opacity: 0.9;
                    font-size: 10px;
                }
            </style>

            @if ($desktop_slider == 1)
                <!-- Slider Mode for Desktop & Mobile -->
                <div class="aiz-carousel homepage-reviews-slider arrow-inactive-none"
                     data-items="3"
                     data-xxl-items="3"
                     data-xl-items="3"
                     data-lg-items="2"
                     data-md-items="1.5"
                     data-sm-items="1"
                     data-xs-items="1"
                     data-arrows="true"
                     data-dots="true"
                     data-autoplay="true"
                     data-infinite="true">
                    @foreach ($homepage_reviews as $review)
                        <div class="carousel-box">
                            @include('frontend.partials.homepage_review_card', ['review' => $review])
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Grid on Desktop, Slider on Mobile -->
                
                <!-- Desktop Grid View (Visible on Large Screens) -->
                <div class="d-none d-lg-block">
                    <div class="row row-cols-1 row-cols-lg-3 gutters-16 justify-content-center">
                        @foreach ($homepage_reviews as $review)
                            <div class="col mb-4">
                                @include('frontend.partials.homepage_review_card', ['review' => $review])
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile & Tablet Slider View (Visible on Medium/Small Screens) -->
                <div class="d-block d-lg-none">
                    <div class="aiz-carousel homepage-reviews-slider arrow-inactive-none"
                         data-items="2"
                         data-md-items="1.5"
                         data-sm-items="1"
                         data-xs-items="1"
                         data-arrows="true"
                         data-dots="true"
                         data-autoplay="true"
                         data-infinite="true">
                        @foreach ($homepage_reviews as $review)
                            <div class="carousel-box">
                                @include('frontend.partials.homepage_review_card', ['review' => $review])
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endif
