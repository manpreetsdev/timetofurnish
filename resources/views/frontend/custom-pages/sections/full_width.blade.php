@php
    $title = (string) ($section['title'] ?? '');
    $highlight = (string) ($section['highlight_text'] ?? '');
    $titleHtml = e($title);
    $displayMode = $section['display_mode'] ?? 'content_only';
    $imagePosition = $section['image_position'] ?? 'bottom';
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

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

<section class="ttf-story-section ttf-story-section--full-width {{ $imagePosition === 'top' ? 'is-image-top' : 'is-image-bottom' }} {{ ($section['tablet_image_position'] ?? 'bottom') === 'top' ? 'is-tablet-image-top' : 'is-tablet-image-bottom' }} {{ ($section['mobile_image_position'] ?? 'bottom') === 'top' ? 'is-mobile-image-top' : 'is-mobile-image-bottom' }} {{ $displayMode === 'image_only' ? 'is-image-only' : '' }} {{ $visibilityClasses }}" style="--section-bg: {{ $showBackground ? $backgroundColor : 'transparent' }}; --section-heading: {{ $section['title_color'] ?? 'var(--ttf-heading)' }}; --section-subheading: {{ $section['subtitle_color'] ?? 'var(--ttf-subheading)' }}; --section-text: {{ $section['body_color'] ?? 'var(--ttf-text)' }}; --section-accent: {{ $section['accent_color'] ?? 'var(--ttf-accent)' }}; --section-heading-font: {{ $section['title_font_family'] ?? 'var(--ttf-heading-font)' }}; --section-body-font: {{ $section['body_font_family'] ?? 'var(--ttf-text-font)' }}; --section-border: {{ $showBorder ? $borderColor : 'transparent' }}; --section-radius: {{ ($showBackground || $showBorder) ? (int) ($section['border_radius'] ?? 24) : 0 }}px; --section-padding: {{ $usePadding ? (int) ($section['section_padding'] ?? 32) : 0 }}px; --section-text-align: {{ $section['text_align'] ?? 'left' }}; --section-image-height: {{ (int) ($section['image_height'] ?? 520) }}px;">
    @if ($displayMode === 'content_image' && !empty($section['image']) && $imagePosition === 'top')
        <div class="ttf-story-section__full-media">
            <img class="ttf-story-section__hero-image" src="{{ uploaded_asset($section['image']) }}" alt="{{ $section['image_alt'] ?? $title }}">
        </div>
    @endif

    @if ($displayMode !== 'image_only')
        <div class="ttf-story-section__full-content">
            @if ($title !== '')
                <h2>{!! $titleHtml !!}</h2>
            @endif
            @if (!empty($section['subtitle']))
                <p class="ttf-story-section__subheading">{{ $section['subtitle'] }}</p>
            @endif
            @if (!empty($section['content']))
                <div class="ttf-rich-text">
                    {!! $section['content'] !!}
                </div>
            @endif
        </div>
    @endif

    @if ($displayMode === 'content_image' && !empty($section['image']) && $imagePosition !== 'top')
        <div class="ttf-story-section__full-media">
            <img class="ttf-story-section__hero-image" src="{{ uploaded_asset($section['image']) }}" alt="{{ $section['image_alt'] ?? $title }}">
        </div>
    @endif

    @if ($displayMode === 'image_only' && !empty($section['image']))
        <div class="ttf-story-section__full-media">
            <img class="ttf-story-section__hero-image" src="{{ uploaded_asset($section['image']) }}" alt="{{ $section['image_alt'] ?? $title }}">
        </div>
    @endif
</section>
