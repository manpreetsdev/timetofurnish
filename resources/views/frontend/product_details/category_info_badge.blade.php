@php
    $categoryInfoBadge = get_product_category_info_badge($detailedProduct);
@endphp
{{-- {{  dd($categoryInfoBadge['text'])}} --}}
@if ($categoryInfoBadge)
    <div class="product-category-info-badge">
        @if ($categoryInfoBadge['type'] === 'image')
            <img
                src="{{ uploaded_asset($categoryInfoBadge['image_id']) }}"
                alt="{{ translate('Product Info') }}"
                class="product-category-info-badge__image"
                style="width: {{ $categoryInfoBadge['image_width'] }};"
                loading="lazy"
            >
        @else
            <span class="product-category-info-badge__text" style="white-space: pre-line;">
                <span class="product-category-info-badge__text-icon">
                    <i class="las la-shipping-fast"></i>
                </span>
                @php
                    // Try to split text for badge display: e.g. "Fast Delivery 7-10 Working Days"
                    $badgeText = trim(strip_tags($categoryInfoBadge['text']));
                    // Try splitting by numbers pattern (first digit encountered)
                    if (preg_match('/^(.+?)(\d.*)$/s', $badgeText, $parts)) {
                        $leftText = trim($parts[1]);
                        $rightText = trim($parts[2]);
                    } else {
                        $leftText = $badgeText;
                        $rightText = '';
                    }
                @endphp
                <span class="product-category-info-badge__text-content" style="white-space:pre; margin-left:0; display: flex; align-items: center; gap: 18px;">
                    <span>{{ $leftText }}</span>
                    @if ($rightText !== '')
                        <span>{{ $rightText }}</span>
                    @endif
                </span>
     
            </span>
        @endif
    </div>
@endif
