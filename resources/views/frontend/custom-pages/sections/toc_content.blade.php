@php
    $title = (string) ($section['title'] ?? '');
    $highlight = (string) ($section['highlight_text'] ?? '');
    $titleHtml = e($title);
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
    $tocItems = collect($section['items'] ?? [])
        ->map(function ($item) {
            $title = trim((string) ($item['title'] ?? ''));
            $anchor = trim((string) ($item['anchor_id'] ?? ''));

            if ($title === '') {
                return null;
            }

            return [
                'title' => $title,
                'anchor_id' => $anchor !== '' ? $anchor : \Illuminate\Support\Str::slug($title),
                'summary' => (string) ($item['summary'] ?? ''),
                'content' => (string) ($item['content'] ?? ''),
            ];
        })
        ->filter()
        ->values();

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

<section class="ttf-story-section ttf-story-section--toc {{ $visibilityClasses }}" style="--section-bg: {{ $showBackground ? $backgroundColor : 'transparent' }}; --section-heading: {{ $section['title_color'] ?? 'var(--ttf-heading)' }}; --section-subheading: {{ $section['subtitle_color'] ?? 'var(--ttf-subheading)' }}; --section-text: {{ $section['body_color'] ?? 'var(--ttf-text)' }}; --section-accent: {{ $section['accent_color'] ?? 'var(--ttf-accent)' }}; --section-heading-font: {{ $section['title_font_family'] ?? 'var(--ttf-heading-font)' }}; --section-body-font: {{ $section['body_font_family'] ?? 'var(--ttf-text-font)' }}; --section-border: {{ $showBorder ? $borderColor : 'transparent' }}; --section-radius: {{ ($showBackground || $showBorder) ? (int) ($section['border_radius'] ?? 24) : 0 }}px; --section-padding: {{ $usePadding ? (int) ($section['section_padding'] ?? 32) : 0 }}px; --section-sidebar-width: {{ (int) ($section['sidebar_width'] ?? 290) }}px;">
    @if (!empty($section['subtitle']))
        <p class="ttf-story-section__eyebrow">{{ $section['subtitle'] }}</p>
    @endif
    @if ($title !== '')
        <h2>{!! $titleHtml !!}</h2>
    @endif
    @if (!empty($section['content']))
        <div class="ttf-rich-text ttf-toc-intro">
            {!! $section['content'] !!}
        </div>
    @endif

    <div class="ttf-policy-layout ttf-policy-layout--widget">
        @if ($tocItems->isNotEmpty())
            <aside class="ttf-policy-toc {{ ($section['sticky_sidebar'] ?? '1') === '1' ? 'is-sticky' : 'is-static' }}">
                <h2>{{ $section['toc_title'] ?? translate('Table of Contents') }}</h2>
                <ul>
                    @foreach ($tocItems as $tocItem)
                        <li>
                            <a href="#{{ $tocItem['anchor_id'] }}">{{ $tocItem['title'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </aside>
        @endif

        <div class="ttf-policy-content">
            @foreach ($tocItems as $tocItem)
                <section class="ttf-policy-section" id="{{ $tocItem['anchor_id'] }}">
                    <h2>{{ $tocItem['title'] }}</h2>
                    @if ($tocItem['summary'] !== '')
                        <p class="ttf-policy-section__summary">{{ $tocItem['summary'] }}</p>
                    @endif
                    <div class="ttf-rich-text">
                        {!! $tocItem['content'] !!}
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</section>
