@php
    $title = (string) ($section['title'] ?? '');
    $highlight = (string) ($section['highlight_text'] ?? '');
    $titleHtml = e($title);

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

<section class="ttf-story-section ttf-story-section--feature {{ ($section['layout'] ?? 'image_right') === 'image_left' ? 'is-reversed' : '' }}" style="--section-bg: {{ $section['background_color'] ?: 'var(--ttf-card-bg)' }}; --section-heading: {{ $section['title_color'] ?: 'var(--ttf-heading)' }}; --section-text: {{ $section['body_color'] ?: 'var(--ttf-text)' }}; --section-accent: {{ $section['accent_color'] ?: 'var(--ttf-accent)' }}; --section-heading-font: {{ $section['title_font_family'] ?: 'var(--ttf-heading-font)' }}; --section-body-font: {{ $section['body_font_family'] ?: 'var(--ttf-text-font)' }};">
    <div class="ttf-story-section__content">
        @if ($title !== '')
            <h2>{!! $titleHtml !!}</h2>
        @endif
        @if (!empty($section['intro']))
            <div class="ttf-story-section__intro">{!! nl2br(e($section['intro'])) !!}</div>
        @endif
        @if (!empty($section['items']))
            <ul class="ttf-feature-list">
                @foreach ($section['items'] as $item)
                    @if (!empty($item['text']))
                        <li>{{ $item['text'] }}</li>
                    @endif
                @endforeach
            </ul>
        @endif
    </div>
    @if (!empty($section['image']))
        <div class="ttf-story-section__media">
            <img src="{{ uploaded_asset($section['image']) }}" alt="{{ $section['image_alt'] ?? $title }}">
        </div>
    @endif
</section>
