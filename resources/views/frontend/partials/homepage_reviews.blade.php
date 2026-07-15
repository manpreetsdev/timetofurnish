@php
    $homepage_reviews = \App\Models\HomepageReview::where('status', 1)->get();
    $section_status = get_setting('homepage_reviews_section_status', 1);
    $desktop_slider = get_setting('homepage_reviews_desktop_slider', 1);
@endphp

@if ($section_status == 1 && count($homepage_reviews) > 0)
    <section class="homepage-reviews-section home_Section py-5 py-md-6 position-relative" style="background-image: url('{{ static_asset('assets/img/reviews_bg_luxury.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; overflow: hidden; border-top: 1px solid #f2ebe1; border-bottom: 1px solid #f2ebe1;">
        <!-- Warm Beige overlay to match base theme color (#685b4e) instead of dark grey -->
        <div class="position-absolute" style="top:0; left:0; right:0; bottom:0; background: linear-gradient(180deg, rgba(251, 249, 246, 0.86) 0%, rgba(243, 236, 228, 0.84) 100%); z-index: 1;"></div>

        <div class="container position-relative home_review_container" style="z-index: 2;">
            <!-- Section Header -->
            <div class="row justify-content-center mb-4 mb-md-1 text-center homepage-reviews-header">
                <div class="col-lg-6">
                    <span class="d-inline-block text-uppercase fw-600 tracking-wider fs-11 mb-2" style="letter-spacing: 2px; color: #a18a68 !important;">{{ translate('Testimonials') }}</span>
                    <h2 class="fs-24 fs-md-36 fw-700 mb-3" style="color: #39322a;">{{ translate('What Our Customers Says') }}</h2>
                    <p class="opacity-70 fs-14" style="margin-bottom: 20px; color: #5d5247;">{{ translate('Real experiences shared by our happy customers.') }}</p>
                    <div class="mx-auto" style="width: 50px; height: 3px; background-color: #685b4e; border-radius: 2px;"></div>
                </div>
            </div>

            <!-- Custom Glassmorphism Testimonial Style (Warm Light Theme) -->
            <style>
                .aiz-carousel .slick-prev{
                    left:-60px;
                }
                
                .aiz-carousel .slick-next{
                    right:-60px;
                }
                .homepage-reviews-section {
                    min-height: 430px;
                }
                .review-card {
                    background: rgba(255, 255, 255, 0.72);
                    backdrop-filter: blur(16px);
                    -webkit-backdrop-filter: blur(16px);
                    border: 1px solid rgba(104, 91, 78, 0.12);
                    border-radius: 20px;
                    padding: 1.5rem 1.4rem;
                    min-height: 190px;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    position: relative;
                    box-shadow: 0 15px 35px rgba(104, 91, 78, 0.04);
                    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
                }
                .review-card:hover {
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
                    line-height: 1.45;
                    color: #4a433c;
                    margin-bottom: 0.4rem;
                    font-style: normal;
                }
                .review-content-text {
                    display: block;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }
                .review-content-text.is-expanded {
                    white-space: normal;
                    overflow: visible;
                }
                .review-read-more-btn {
                    background: transparent;
                    border: 0;
                    padding: 0;
                    margin-top: 2px;
                    color: #685b4e;
                    font-size: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    line-height: 1.2;
                    text-decoration: underline;
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
                @media (max-width: 991.98px) {
                    .homepage-reviews-section {
                        min-height: auto;
                        padding-top: 1rem !important;
                        padding-bottom: 1rem !important;
                    }
                    .homepage-reviews-header {
                        margin-bottom: 0.75rem !important;
                    }
                    .homepage-reviews-header span {
                        font-size: 10px !important;
                        margin-bottom: 0.2rem !important;
                    }
                    .homepage-reviews-header h2 {
                        font-size: 24px !important;
                        margin-bottom: 0.35rem !important;
                        line-height: 1.15;
                        font-family: 'Playfair Display', serif !important;
                        font-weight: 500 !important;
                    }
                    .homepage-reviews-header p {
                        font-size: 14px !important;
                        margin-bottom: 0.45rem !important;
                        line-height: 1.35;
                    }
                    .home_review_container{
                        padding-right:10px  !important;
                        padding-left:10px  !important;
                    }
                    .review-card {
                        min-height: 165px;
                        padding: 0.95rem 0.85rem;
                        border-radius: 14px;
                    }
                    .review-quote-icon {
                        font-size: 3.1rem;
                        bottom: 56px;
                        right: 14px;
                    }
                    .reviewer-avatar {
                        width: 36px !important;
                        height: 36px !important;
                    }
                    .reviewer-name {
                        font-size: 12px !important;
                    }
                    .review-relative-date {
                        font-size: 9px;
                    }
                    .review-rating {
                        font-size: 0.82rem;
                        line-height: 1;
                    }
                    .purchased-text {
                        font-size: 9.5px;
                        margin-bottom: 0.3rem;
                        letter-spacing: 0.3px;
                    }
                    .review-content {
                        font-size: 12px;
                        margin-bottom: 0.18rem;
                        line-height: 1.3;
                    }
                    .review-read-more-btn {
                        font-size: 10.5px;
                    }
                    .review-card-divider {
                        padding-top: 0.65rem;
                    }
                    .category-badge {
                        font-size: 8.5px;
                        padding: 4px 10px;
                    }
                    .helpful-votes {
                        font-size: 9px;
                    }
                    .homepage-reviews-slider .slick-slide {
                        padding: 7px 2px;
                    }
                    .homepage-reviews-slider .slick-track{
                        padding-top:5px;
                    }
                    .homepage-reviews-slider .slick-list {
                        margin: 0 -7px;
                    }
                    .homepage-reviews-slider .slick-dots {
                        bottom: -18px;
                    }
                }

                @media (max-width: 575.98px) {
                    .homepage-reviews-section {
                        padding-top: 0.75rem !important;
                        padding-bottom: 0.75rem !important;
                    }
                    .homepage-reviews-header h2 {
                        font-size: 21px !important;
                    }
                    .homepage-reviews-header p {
                        font-size: 12px !important;
                    }
                    .review-card-divider {
        flex-wrap: nowrap !important;
        flex-direction: column;
        gap: 5px !important;
    }
                }

                @media (max-width: 767.98px) {
                    
                    .homepage-reviews-header {
                        margin-right: 0 !important;
                        padding-left: 15px;
                        padding-right: 15px;
                    }
                    
                }
				@media (max-width: 767.98px) {
    .purchased-text {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        line-height: 16px !important;
    }
}
            </style>

            @if ($desktop_slider == 1)
                <!-- Slider Mode for Desktop & Mobile -->
                <div class="aiz-carousel homepage-reviews-slider arrow-inactive-none"
                     data-items="3"
                     data-xxl-items="3"
                     data-xl-items="3"
                     data-lg-items="2"
                     data-md-items="2"
                     data-sm-items="2"
                     data-xs-items="2"
                     data-arrows="true"
                     data-dots="true"
                     data-autoplay="true"
                     data-infinite="false">
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
                         data-sm-items="1.5"
                         data-xs-items="1.5"
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

    <script>
        document.addEventListener('click', function (event) {
            var toggleButton = event.target.closest('.review-read-more-btn');
            if (!toggleButton) {
                return;
            }

            var targetId = toggleButton.getAttribute('data-target');
            var targetText = document.getElementById(targetId);
            if (!targetText) {
                return;
            }

            var isExpanded = targetText.classList.toggle('is-expanded');
            toggleButton.textContent = isExpanded ? '{{ translate('Read Less') }}' : '{{ translate('Read More') }}';
            toggleButton.setAttribute('aria-expanded', isExpanded ? 'true' : 'false');
        });
    </script>
@endif
