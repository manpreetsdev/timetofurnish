@php
    $text = $section['button_text'] ?? 'Click Here';
    $link = $section['button_link'] ?? '#';
    $align = $section['button_align'] ?? 'left';
    $bgColor = $section['button_bg_color'] ?? '#c8883a';
    $textColor = $section['button_text_color'] ?? '#ffffff';
    $fontSize = !empty($section['button_font_size']) ? (is_numeric($section['button_font_size']) ? $section['button_font_size'] . 'px' : $section['button_font_size']) : '16px';
    $borderRadius = !empty($section['button_border_radius']) ? (is_numeric($section['button_border_radius']) ? $section['button_border_radius'] . 'px' : $section['button_border_radius']) : '6px';
    $padding = $section['button_padding'] ?? '12px 24px';
    
    $showBackground = ($section['show_background'] ?? '0') === '1';
    $showBorder = ($section['show_border'] ?? '0') === '1';
    $usePadding = ($section['use_padding'] ?? '0') === '1';
    $backgroundColor = !empty($section['background_color']) ? $section['background_color'] : 'var(--ttf-card-bg)';
    $borderColor = !empty($section['border_color']) ? $section['border_color'] : 'var(--ttf-card-border)';
    $borderStyle = $section['border_style'] ?? 'solid';
    $borderWidth = !empty($section['border_width']) ? (int) $section['border_width'] : ($showBorder ? 1 : 0);
    
    $alignmentClass = $align === 'center' ? 'is-centered' : ($align === 'right' ? 'is-right' : 'is-left');
    $visibilityClasses = collect([
        ($section['show_on_desktop'] ?? '1') === '0' ? 'ttf-hide-desktop' : null,
        ($section['show_on_ipad_pro'] ?? '1') === '0' ? 'ttf-hide-ipad-pro' : null,
        ($section['show_on_ipad'] ?? '1') === '0' ? 'ttf-hide-ipad' : null,
        ($section['show_on_phone'] ?? '1') === '0' ? 'ttf-hide-phone' : null,
    ])->filter()->implode(' ');
@endphp

<div class="ttf-story-section ttf-story-section--button {{ $alignmentClass }} {{ $visibilityClasses }}" style="
    --section-bg: {{ $showBackground ? $backgroundColor : 'transparent' }};
    --section-border: {{ $showBorder ? $borderColor : 'transparent' }};
    --section-border-width: {{ $borderWidth }}px;
    --section-border-style: {{ $borderStyle }};
    --section-radius: {{ ($showBackground || $showBorder) ? (int) ($section['border_radius'] ?? 24) : 0 }}px;
    --section-padding-top: {{ (isset($section['padding_top']) && $section['padding_top'] !== '') ? (int) $section['padding_top'] : 50 }}px;
    --section-padding-bottom: {{ (isset($section['padding_bottom']) && $section['padding_bottom'] !== '') ? (int) $section['padding_bottom'] : 50 }}px;
    --section-padding-left: {{ (isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0 }}px;
    --section-padding-right: {{ (isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0 }}px;
    --btn-align: {{ $align === 'center' ? 'center' : ($align === 'right' ? 'flex-end' : 'flex-start') }};
    --btn-bg: {{ $bgColor }};
    --btn-color: {{ $textColor }};
    --btn-font-size: {{ $fontSize }};
    --btn-radius: {{ $borderRadius }};
    --btn-padding: {{ $padding }};
">
    <div class="ttf-button-wrapper">
        <a class="ttf-action-btn" href="{{ $link }}">{{ $text }}</a>
    </div>
</div>
