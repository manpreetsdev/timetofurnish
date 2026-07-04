@php
    $title = (string) ($section['title'] ?? '');
    $highlight = (string) ($section['highlight_text'] ?? '');
    $titleHtml = e($title);

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

<section class="ttf-story-section ttf-story-section--text" style="--section-bg: {{ $section['background_color'] ?: 'transparent' }}; --section-heading: {{ $section['title_color'] ?: 'var(--ttf-heading)' }}; --section-text: {{ $section['body_color'] ?: 'var(--ttf-text)' }}; --section-accent: {{ $section['accent_color'] ?: 'var(--ttf-accent)' }}; --section-heading-font: {{ $section['title_font_family'] ?: 'var(--ttf-heading-font)' }}; --section-body-font: {{ $section['body_font_family'] ?: 'var(--ttf-text-font)' }};">
    @if ($title !== '')
        <h2>{!! $titleHtml !!}</h2>
    @endif
    <div class="ttf-rich-text">
        {!! $section['content'] ?? '' !!}
    </div>
</section>
