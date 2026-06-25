@php
    // Populate realistic custom fields to match theme design
    $products = [
        'Luxury Bed Set', 'Corner Sofa Suite', 'Dining Table Set',
        'Modern Armchair', 'Velvet Dressing Table', 'Oak Coffee Table',
        'Leather Lounge Chair', 'Wooden Wardrobe', 'Minimalist Bookshelf'
    ];
    $categories = [
        'Bedroom Collection', 'Living Room', 'Dining Collection',
        'Office Space', 'Bedroom Decor', 'Living Room Set',
        'Lounge Chairs', 'Storage Solutions', 'Study Room'
    ];

    $product_index = $review->id % count($products);
    $purchased_product = $products[$product_index];
    $category_tag = $review->category_tag ?: $categories[$product_index];
    $helpful_votes = ($review->id * 7 + 3) % 20 + 4; // realistic counts (e.g. 4 to 23)
    $review_text_plain = trim(strip_tags((string) $review->review_text));
    $needs_read_more = \Illuminate\Support\Str::length($review_text_plain) > 30;
    $review_text_id = 'review-text-' . $review->id;
@endphp

@if ($review->type == 'image')
    <!-- Full Image Review Card -->
    <div class="full-image-review-box">
        @if ($review->image != null)
            <img class="img-fluid lazyload"
                 src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                 data-src="{{ uploaded_asset($review->image) }}"
                 alt="{{ translate('Review Image') }}"
                 onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
        @endif
    </div>
@else
    <!-- Custom Glassmorphism Testimonial Card (Light Theme to Match Base Theme Color) -->
    <div class="review-card {{ $review->id % 3 == 0 ? 'featured-border' : '' }}">
        <div class="review-quote-icon">
            <i class="las la-quote-right"></i>
        </div>

        <div>
            <!-- User Profile Header -->
            <div class="d-flex align-items-center justify-content-between mb-3 ">
                <div class="d-flex align-items-center">
                    <img class="reviewer-avatar lazyload mr-3"
                         src="{{ static_asset('assets/img/avatar-place.png') }}"
                         data-src="{{ $review->image != null ? uploaded_asset($review->image) : static_asset('assets/img/avatar-place.png') }}"
                         alt="{{ $review->name }}"
                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';"
                         style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(104, 91, 78, 0.15); margin-right: 12px; transition: all 0.3s ease;">
                    <div>
                        <h5 class="reviewer-name mb-0 font-weight-700" style="font-size: 15px; line-height: 1.2; margin-bottom: 2px; color: #39322a;">{{ $review->name }}</h5>
                     <!-- Stars Rating -->
            <div class="review-rating">
                <span class="d-none d-md-inline-block">
                    @for ($i = 0; $i < 5; $i++)
                        @if ($i < $review->rating)
                            <i class="las la-star"></i>
                        @else
                            <i class="lar la-star text-muted" style="opacity: 0.4;"></i>
                        @endif
                    @endfor
                </span>
                <span class="d-inline-flex d-md-none align-items-center">
                    <span class="rating-point-val mr-1 font-weight-700" style="color: #c5a059;">{{ floatval($review->rating) }}</span>
                    <i class="las la-star text-warning" style="color: #c5a059 !important; font-size: 13px;"></i>
                    <!-- <span class="rating-count-text ml-1" style="color: #8c7e70; font-size: 10px; font-weight: 500;">
                        {{ $review->rating == 1 ? translate('review') : translate('reviews') }}
                    </span> -->
                </span>
            </div>
                    </div>
                </div>
                <span class="review-relative-date fs-11" style="color: #8c7e70;">
                    @if ($review->review_date != null)
                        {{ $review->review_date->diffForHumans() }}
                    @else
                        {{ translate('Recently') }}
                    @endif
                </span>
            </div>



            <!-- Purchased Item Details -->
            <div class="purchased-text">
                {{ translate('Purchased') }}: {{ translate($purchased_product) }}
            </div>

            <!-- Review Text Content -->
            <div class="review-content">
                <span id="{{ $review_text_id }}" class="review-content-text">"{{ $review->review_text }}"</span>
                @if ($needs_read_more)
                    <button type="button"
                            class="review-read-more-btn"
                            data-target="{{ $review_text_id }}"
                            aria-expanded="false">
                        {{ translate('Read More') }}
                    </button>
                @endif
            </div>
        </div>

        <!-- Footer Separator & Controls -->
        <div class="review-card-divider d-flex align-items-center justify-content-between">
            <span class="category-badge">
                {{ translate($category_tag) }}
            </span>
            <span class="helpful-votes">
                <i class="lar la-thumbs-up" style="margin-right: 4px; font-size: 14px; color: #685b4e;"></i>
                {{ translate('Helpful') }} ({{ $helpful_votes }})
            </span>
        </div>
    </div>
@endif
