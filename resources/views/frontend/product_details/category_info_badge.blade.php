@php
    $position = $position ?? 'top';
    $categoryInfoBadge = get_product_category_info_badge($detailedProduct, $position);
@endphp

@if ($categoryInfoBadge)
    @if ($position === 'bottom' || $categoryInfoBadge['type'] === 'image_text')
        <div class="product-category-info-badge-bottom my-3">
            <div class="d-flex align-items-center flex-wrap" style="gap: 14px;">
                @if (!empty($categoryInfoBadge['image_id']))
                    <div class="product-category-info-badge__image-wrap">
                        <img
                            src="{{ uploaded_asset($categoryInfoBadge['image_id']) }}"
                            alt="{{ translate('Info') }}"
                            style="max-width: 100%; width: {{ $categoryInfoBadge['image_width'] ?? 'auto' }}; max-height: 120px; object-fit: contain;"
                            loading="lazy"
                        >
                    </div>
                @endif
                @if (!empty($categoryInfoBadge['text']))
                    <div class="product-category-info-badge__text-wrap flex-grow-1 fs-13" style="line-height: 1.4; color: #1a2744;">
                        {!! pci_badge_text_html($categoryInfoBadge['text']) !!}
                    </div>
                @endif
            </div>
        </div>
    @else
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
                        $badgeText = trim(strip_tags($categoryInfoBadge['text']));
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
@endif
