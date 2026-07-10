@php
    $image = $section['image'] ?? '';
    $alt = $section['image_alt'] ?? '';
    $link = $section['image_link'] ?? '';
    $align = $section['image_align'] ?? 'center';
    $width = !empty($section['image_width']) ? $section['image_width'] : '100';
    $height = !empty($section['image_height']) ? (int) $section['image_height'] . 'px' : 'auto';
    $borderRadius = !empty($section['border_radius']) ? (int) $section['border_radius'] . 'px' : '0px';
    
    $showBackground = ($section['show_background'] ?? '0') === '1';
    $showBorder = ($section['show_border'] ?? '0') === '1';
    $usePadding = ($section['use_padding'] ?? '0') === '1';
    $backgroundColor = !empty($section['background_color']) ? $section['background_color'] : 'var(--ttf-card-bg)';
    $borderColor = !empty($section['border_color']) ? $section['border_color'] : 'var(--ttf-card-border)';
    $borderStyle = $section['border_style'] ?? 'solid';
    $borderWidth = !empty($section['border_width']) ? (int) $section['border_width'] : ($showBorder ? 1 : 0);
    $paddingLeft = isset($section['padding_left']) && $section['padding_left'] !== '' ? (int) $section['padding_left'] : ((isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0);
    $paddingRight = isset($section['padding_right']) && $section['padding_right'] !== '' ? (int) $section['padding_right'] : ((isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0);
    $marginTop = isset($section['margin_top']) && $section['margin_top'] !== '' ? (int) $section['margin_top'] : 0;
    $marginBottom = isset($section['margin_bottom']) && $section['margin_bottom'] !== '' ? (int) $section['margin_bottom'] : null;
    
    $alignmentClass = $align === 'center' ? 'is-centered' : ($align === 'right' ? 'is-right' : 'is-left');
    $visibilityClasses = collect([
        ($section['show_on_desktop'] ?? '1') === '0' ? 'ttf-hide-desktop' : null,
        ($section['show_on_ipad_pro'] ?? '1') === '0' ? 'ttf-hide-ipad-pro' : null,
        ($section['show_on_ipad'] ?? '1') === '0' ? 'ttf-hide-ipad' : null,
        ($section['show_on_phone'] ?? '1') === '0' ? 'ttf-hide-phone' : null,
    ])->filter()->implode(' ');
@endphp

@if ($image !== '')
    <div class="ttf-story-section ttf-story-section--image {{ $alignmentClass }} {{ $visibilityClasses }}" style="
        --section-bg: {{ $showBackground ? $backgroundColor : 'transparent' }};
        --section-border: {{ $showBorder ? $borderColor : 'transparent' }};
        --section-border-width: {{ $borderWidth }}px;
        --section-border-style: {{ $borderStyle }};
        --section-radius: {{ ($showBackground || $showBorder) ? (int) ($section['border_radius'] ?? 24) : 0 }}px;
        --section-padding-top: {{ (isset($section['padding_top']) && $section['padding_top'] !== '') ? (int) $section['padding_top'] : 50 }}px;
        --section-padding-bottom: {{ (isset($section['padding_bottom']) && $section['padding_bottom'] !== '') ? (int) $section['padding_bottom'] : 50 }}px;
        --section-padding-left: {{ $paddingLeft }}px;
        --section-padding-right: {{ $paddingRight }}px;
        --section-margin-top: {{ $marginTop }}px;
        --section-margin-bottom: {{ $marginBottom !== null ? $marginBottom . 'px' : 'var(--ttf-section-gap)' }};
        --image-align: {{ $align === 'center' ? 'center' : ($align === 'right' ? 'flex-end' : 'flex-start') }};
        --image-width: {{ $width }}%;
        --image-height: {{ $height }};
        --image-radius: {{ $borderRadius }};
    ">
        <div class="ttf-image-wrapper">
            @if ($link !== '')
                <a href="{{ $link }}">
            @endif
            <img class="ttf-single-image" src="{{ uploaded_asset($image) }}" alt="{{ $alt }}" />
            @if ($link !== '')
                </a>
            @endif
        </div>
    </div>
@endif
