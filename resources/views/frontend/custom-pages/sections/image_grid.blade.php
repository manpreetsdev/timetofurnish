@php
    $title = (string) ($section['title'] ?? '');
    $highlight = (string) ($section['highlight_text'] ?? '');
    $titleHtml = e($title);
    $showTitle = ($section['show_title'] ?? '1') === '1';
    $showBackground = ($section['show_background'] ?? '0') === '1';
    $showBorder = ($section['show_border'] ?? '0') === '1';
    $usePadding = ($section['use_padding'] ?? '0') === '1';
    $backgroundColor = !empty($section['background_color']) ? $section['background_color'] : 'var(--ttf-card-bg)';
    $borderColor = !empty($section['border_color']) ? $section['border_color'] : 'var(--ttf-card-border)';
    $visibilityClasses = collect([
        ($section['show_on_desktop'] ?? '1') === '0' ? 'ttf-hide-desktop' : null,
        ($section['show_on_ipad_pro'] ?? '1') === '0' ? 'ttf-hide-ipad-pro' : null,
        ($section['show_on_ipad'] ?? '1') === '0' ? 'ttf-hide-ipad' : null,
        ($section['show_on_phone'] ?? '1') === '0' ? 'ttf-hide-phone' : null,
    ])->filter()->implode(' ');

    $borderStyle = $section['border_style'] ?? 'solid';
    $borderWidth = !empty($section['border_width']) ? (int) $section['border_width'] : ($showBorder ? 1 : 0);
    $paddingTop = (isset($section['padding_top']) && $section['padding_top'] !== '') ? (int) $section['padding_top'] : 50;
    $paddingBottom = (isset($section['padding_bottom']) && $section['padding_bottom'] !== '') ? (int) $section['padding_bottom'] : 50;
    $paddingLeft = isset($section['padding_left']) && $section['padding_left'] !== '' ? (int) $section['padding_left'] : ((isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0);
    $paddingRight = isset($section['padding_right']) && $section['padding_right'] !== '' ? (int) $section['padding_right'] : ((isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0);
    $marginTop = isset($section['margin_top']) && $section['margin_top'] !== '' ? (int) $section['margin_top'] : 0;
    $marginBottom = isset($section['margin_bottom']) && $section['margin_bottom'] !== '' ? (int) $section['margin_bottom'] : null;
    $titleLineHeight = \App\Support\CustomPageTemplate::normalizeLineHeightValue($section['title_line_height'] ?? null, $section['title_font_size'] ?? 28, '1.18');
    $bodyLineHeight = \App\Support\CustomPageTemplate::normalizeLineHeightValue($section['body_line_height'] ?? null, $section['body_font_size'] ?? 18, '1.72');

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

<section class="ttf-story-section ttf-story-section--grid {{ $visibilityClasses }}" style="
    --section-bg: {{ $showBackground ? $backgroundColor : 'transparent' }};
    --section-heading: {{ $section['title_color'] ?? 'var(--ttf-heading)' }};
    --section-subheading: {{ $section['subtitle_color'] ?? 'var(--ttf-subheading)' }};
    --section-text: {{ $section['body_color'] ?? 'var(--ttf-text)' }};
    --section-accent: {{ $section['accent_color'] ?? 'var(--ttf-accent)' }};
    --section-heading-font: {{ $section['title_font_family'] ?? 'var(--ttf-heading-font)' }};
    --section-body-font: {{ $section['body_font_family'] ?? 'var(--ttf-text-font)' }};
    --section-border: {{ $showBorder ? $borderColor : 'transparent' }};
    --section-border-width: {{ $borderWidth }}px;
    --section-border-style: {{ $borderStyle }};
    --section-radius: {{ ($showBackground || $showBorder) ? (int) ($section['border_radius'] ?? 24) : 0 }}px;
    --section-padding-top: {{ $paddingTop }}px;
    --section-padding-bottom: {{ $paddingBottom }}px;
    --section-padding-left: {{ $paddingLeft }}px;
    --section-padding-right: {{ $paddingRight }}px;
    --section-margin-top: {{ $marginTop }}px;
    --section-margin-bottom: {{ $marginBottom !== null ? $marginBottom . 'px' : 'var(--ttf-section-gap)' }};
    --ttf-grid-columns: {{ max(2, min(4, (int) ($section['columns'] ?? 3))) }};
    --ttf-grid-image-height: {{ (int) ($section['card_image_height'] ?? 240) }}px;
    --section-title-size: {{ !empty($section['title_font_size']) ? (is_numeric($section['title_font_size']) ? $section['title_font_size'] . 'px' : $section['title_font_size']) : '' }};
    --section-heading-weight: {{ $section['title_font_weight'] ?? 'var(--ttf-heading-weight)' }};
    --section-title-height: {{ $titleLineHeight }};
    --section-title-spacing: {{ isset($section['title_letter_spacing']) && $section['title_letter_spacing'] !== '' ? $section['title_letter_spacing'] . 'px' : '0px' }};
    --section-body-size: {{ !empty($section['body_font_size']) ? (is_numeric($section['body_font_size']) ? $section['body_font_size'] . 'px' : $section['body_font_size']) : '' }};
    --section-body-weight: {{ $section['body_font_weight'] ?? 'var(--ttf-body-weight)' }};
    --section-body-height: {{ $bodyLineHeight }};
    --section-body-spacing: {{ isset($section['body_letter_spacing']) && $section['body_letter_spacing'] !== '' ? $section['body_letter_spacing'] . 'px' : '0px' }};
    --section-highlight-color: {{ !empty($section['highlight_color']) ? $section['highlight_color'] : 'var(--section-accent)' }};
">
    @if (!empty($section['subtitle']))
        <p class="ttf-story-section__eyebrow">{{ $section['subtitle'] }}</p>
    @endif
    @if ($showTitle && $title !== '')
        <h2>{!! $titleHtml !!}</h2>
    @endif
    @if (!empty($section['content']))
        <div class="ttf-rich-text ttf-grid-intro">
            {!! $section['content'] !!}
        </div>
    @endif
    <div class="ttf-image-grid">
        @foreach (($section['items'] ?? []) as $item)
            @php
                $cardTitle = trim((string) ($item['title'] ?? ''));
                $cardText = trim((string) ($item['text'] ?? ''));
            @endphp
            @if ($cardTitle !== '' || $cardText !== '' || !empty($item['image']))
                <article class="ttf-image-grid__item">
                    @if (!empty($item['image']))
                        <img src="{{ uploaded_asset($item['image']) }}" alt="{{ $cardTitle !== '' ? $cardTitle : $title }}">
                    @endif
                    @if ($cardTitle !== '')
                        <h3>{{ $cardTitle }}</h3>
                    @endif
                    @if ($cardText !== '')
                        <p>{!! nl2br(e($cardText)) !!}</p>
                    @endif
                </article>
            @endif
        @endforeach
    </div>
</section>
