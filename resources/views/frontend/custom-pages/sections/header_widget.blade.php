@php
    $title = (string) ($section['title'] ?? '');
    $highlight = (string) ($section['highlight_text'] ?? '');
    $titleHtml = e($title);
    $showTitle = ($section['show_title'] ?? '1') === '1';
    $tag = $section['header_tag'] ?? 'h2';
    if (!in_array($tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'])) {
        $tag = 'h2';
    }
    
    $showBackground = ($section['show_background'] ?? '0') === '1';
    $showBorder = ($section['show_border'] ?? '0') === '1';
    $usePadding = ($section['use_padding'] ?? '0') === '1';
    $backgroundColor = !empty($section['background_color']) ? $section['background_color'] : 'var(--ttf-card-bg)';
    $borderColor = !empty($section['border_color']) ? $section['border_color'] : 'var(--ttf-card-border)';
    $borderStyle = $section['border_style'] ?? 'solid';
    $borderWidth = !empty($section['border_width']) ? (int) $section['border_width'] : ($showBorder ? 1 : 0);
    $textAlign = $section['text_align'] ?? 'left';
    $alignmentClass = $textAlign === 'center' ? 'is-centered' : ($textAlign === 'right' ? 'is-right' : 'is-left');
    $visibilityClasses = collect([
        ($section['show_on_desktop'] ?? '1') === '0' ? 'ttf-hide-desktop' : null,
        ($section['show_on_ipad_pro'] ?? '1') === '0' ? 'ttf-hide-ipad-pro' : null,
        ($section['show_on_ipad'] ?? '1') === '0' ? 'ttf-hide-ipad' : null,
        ($section['show_on_phone'] ?? '1') === '0' ? 'ttf-hide-phone' : null,
    ])->filter()->implode(' ');

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

@if ($showTitle && $title !== '')
    <div class="ttf-story-section ttf-story-section--header {{ $alignmentClass }} {{ $visibilityClasses }}" style="
        --section-bg: {{ $showBackground ? $backgroundColor : 'transparent' }};
        --section-heading: {{ $section['title_color'] ?? 'var(--ttf-heading)' }};
        --section-accent: {{ $section['accent_color'] ?? 'var(--ttf-accent)' }};
        --section-heading-font: {{ $section['title_font_family'] ?? 'var(--ttf-heading-font)' }};
        --section-border: {{ $showBorder ? $borderColor : 'transparent' }};
        --section-border-width: {{ $borderWidth }}px;
        --section-border-style: {{ $borderStyle }};
        --section-radius: {{ ($showBackground || $showBorder) ? (int) ($section['border_radius'] ?? 24) : 0 }}px;
        --section-padding-top: {{ (isset($section['padding_top']) && $section['padding_top'] !== '') ? (int) $section['padding_top'] : 50 }}px;
        --section-padding-bottom: {{ (isset($section['padding_bottom']) && $section['padding_bottom'] !== '') ? (int) $section['padding_bottom'] : 50 }}px;
        --section-padding-left: {{ (isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0 }}px;
        --section-padding-right: {{ (isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0 }}px;
        --section-text-align: {{ $textAlign }};
        --section-title-size: {{ !empty($section['title_font_size']) ? (int) $section['title_font_size'] . 'px' : '' }};
        --section-title-height: {{ !empty($section['title_line_height']) ? (is_numeric($section['title_line_height']) ? $section['title_line_height'] . 'px' : $section['title_line_height']) : '' }};
        --section-highlight-color: {{ !empty($section['highlight_color']) ? $section['highlight_color'] : 'var(--section-accent)' }};
    ">
        <{{ $tag }} class="ttf-header-element">{!! $titleHtml !!}</{{ $tag }}>
    </div>
@endif
