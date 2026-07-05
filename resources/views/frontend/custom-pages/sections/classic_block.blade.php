@php
    $title = (string) ($block['title'] ?? '');
    $highlight = (string) ($block['highlight_text'] ?? '');
    $titleHtml = e($title);

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }

    $layout = $block['layout'] ?? 'text_only';
@endphp

<section class="ttf-story-section ttf-story-section--classic {{ $layout === 'image_right' ? 'is-reversed' : '' }} {{ $layout !== 'text_only' ? 'is-split' : '' }}" style="--section-bg: {{ $block['background_color'] ?: 'transparent' }}; --section-heading: {{ $block['title_color'] ?: 'var(--ttf-heading)' }}; --section-text: {{ $block['body_color'] ?: 'var(--ttf-text)' }}; --section-accent: {{ $block['accent_color'] ?: 'var(--ttf-accent)' }}; --section-heading-font: {{ $block['title_font_family'] ?: 'var(--ttf-heading-font)' }}; --section-body-font: {{ $block['body_font_family'] ?: 'var(--ttf-text-font)' }};">
    @if ($layout !== 'text_only' && !empty($block['image']))
        <div class="ttf-story-section__media">
            <img src="{{ uploaded_asset($block['image']) }}" alt="{{ $block['image_alt'] ?? $title }}">
        </div>
    @endif
    <div class="ttf-story-section__content">
        @if ($title !== '')
            <h2>{!! $titleHtml !!}</h2>
        @endif
        <div class="ttf-rich-text">
            {!! $block['content'] ?? '' !!}
        </div>
    </div>
</section>
