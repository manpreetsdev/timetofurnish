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
                'image' => (string) ($item['image'] ?? ''),
                'content' => (string) ($item['content'] ?? ''),
            ];
        })
        ->filter()
        ->values();

    $borderStyle = $section['border_style'] ?? 'solid';
    $borderWidth = !empty($section['border_width']) ? (int) $section['border_width'] : ($showBorder ? 1 : 0);
    $paddingTop = (isset($section['padding_top']) && $section['padding_top'] !== '') ? (int) $section['padding_top'] : 50;
    $paddingBottom = (isset($section['padding_bottom']) && $section['padding_bottom'] !== '') ? (int) $section['padding_bottom'] : 50;
    $paddingLeftRight = (isset($section['padding_left_right']) && $section['padding_left_right'] !== '') ? (int) $section['padding_left_right'] : 0;

    if ($highlight !== '' && str_contains($title, $highlight)) {
        $titleHtml = str_replace(e($highlight), '<span>' . e($highlight) . '</span>', e($title));
    }
@endphp

<section class="ttf-story-section ttf-story-section--toc {{ $visibilityClasses }}" style="
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
    --section-padding-left: {{ $paddingLeftRight }}px;
    --section-padding-right: {{ $paddingLeftRight }}px;
    --section-sidebar-width: {{ (int) ($section['sidebar_width'] ?? 290) }}px;
    --section-title-size: {{ !empty($section['title_font_size']) ? (int) $section['title_font_size'] . 'px' : '' }};
    --section-title-height: {{ !empty($section['title_line_height']) ? $section['title_line_height'] : '' }};
    --section-body-size: {{ !empty($section['body_font_size']) ? (int) $section['body_font_size'] . 'px' : '' }};
    --section-body-height: {{ !empty($section['body_line_height']) ? $section['body_line_height'] : '' }};
    --section-highlight-color: {{ !empty($section['highlight_color']) ? $section['highlight_color'] : 'var(--section-accent)' }};
">
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
                    @if (!empty($tocItem['image']))
                        <div class="ttf-policy-section__image mb-3">
                            <img src="{{ uploaded_asset($tocItem['image']) }}" alt="{{ $tocItem['title'] }}" class="img-fluid rounded">
                        </div>
                    @endif
                    <div class="ttf-rich-text">
                        {!! $tocItem['content'] !!}
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</section>

<script>
    (function () {
        function initTocTabs() {
            const tocSections = document.querySelectorAll('.ttf-story-section--toc');
            tocSections.forEach(function (section) {
                const tabs = section.querySelectorAll('.ttf-policy-toc a');
                const contents = section.querySelectorAll('.ttf-policy-content .ttf-policy-section');
                
                if (tabs.length === 0 || contents.length === 0) return;

                function activateTab(targetId) {
                    let found = false;
                    contents.forEach(function (content) {
                        if (content.id === targetId) {
                            content.classList.add('active');
                            found = true;
                        } else {
                            content.classList.remove('active');
                        }
                    });

                    tabs.forEach(function (tab) {
                        const href = tab.getAttribute('href').replace('#', '');
                        const li = tab.closest('li');
                        if (href === targetId) {
                            tab.classList.add('active');
                            if (li) li.classList.add('active');
                        } else {
                            tab.classList.remove('active');
                            if (li) li.classList.remove('active');
                        }
                    });
                    return found;
                }

                // Set first tab active by default
                const firstAnchor = tabs[0].getAttribute('href').replace('#', '');
                activateTab(firstAnchor);

                // Click event listeners
                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function (e) {
                        const sidebar = section.querySelector('.ttf-policy-toc');
                        if (!sidebar) return;
                        
                        const isMobile = window.getComputedStyle(sidebar).display === 'none';
                        if (!isMobile) {
                            e.preventDefault();
                            const targetId = this.getAttribute('href').replace('#', '');
                            activateTab(targetId);
                        }
                    });
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTocTabs);
        } else {
            initTocTabs();
        }
    })();
</script>

