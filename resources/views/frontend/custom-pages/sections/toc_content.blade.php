@php
    if (!function_exists('formatDim')) {
        function formatDim($val) {
            $val = trim((string) $val);
            if ($val === '') {
                return '';
            }
            return is_numeric($val) ? $val . 'px' : $val;
        }
    }

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

<style>
  /* Sticky header for the section title */
  .ttf-story-section--toc .sticky-header {
    position: sticky;
    top: 0;
    z-index: 10;
    background: var(--section-bg, #fff);
    padding-top: 1rem;
    padding-bottom: 1rem;
  }
  /* Sticky TOC sidebar, positioned below the sticky header */
  .ttf-story-section--toc .ttf-policy-toc.is-sticky {
    position: sticky;
    top: var(--header-sticky-height, 170px); /* adjust if header height changes */
    align-self: flex-start; /* ensure it stays at top of its column */
    z-index: 9;
  }
</style>

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
    --section-padding-left: {{ $paddingLeft }}px;
    --section-padding-right: {{ $paddingRight }}px;
    --section-margin-top: {{ $marginTop }}px;
    --section-margin-bottom: {{ $marginBottom !== null ? $marginBottom . 'px' : 'var(--ttf-section-gap)' }};
    --section-sidebar-width: {{ (int) ($section['sidebar_width'] ?? 386) }}px;
    --section-title-size: {{ !empty($section['title_font_size']) ? (is_numeric($section['title_font_size']) ? $section['title_font_size'] . 'px' : $section['title_font_size']) : '' }};
    --section-heading-weight: {{ $section['title_font_weight'] ?? 'var(--ttf-heading-weight)' }};
    --section-title-height: {{ $titleLineHeight }};
    --section-title-spacing: {{ isset($section['title_letter_spacing']) && $section['title_letter_spacing'] !== '' ? $section['title_letter_spacing'] . 'px' : '0px' }};
    --section-body-size: {{ !empty($section['body_font_size']) ? (is_numeric($section['body_font_size']) ? $section['body_font_size'] . 'px' : $section['body_font_size']) : '' }};
    --section-body-weight: {{ $section['body_font_weight'] ?? 'var(--ttf-body-weight)' }};
    --section-body-height: {{ $bodyLineHeight }};
    --section-body-spacing: {{ isset($section['body_letter_spacing']) && $section['body_letter_spacing'] !== '' ? $section['body_letter_spacing'] . 'px' : '0px' }};
    --section-highlight-color: {{ !empty($section['highlight_color']) ? $section['highlight_color'] : 'var(--section-accent)' }};
    
    --toc-bg: {{ $section['toc_bg_color'] ?? '#FAF8F5' }};
    --toc-border-color: {{ $section['toc_border_color'] ?? '#FAF8F5' }};
    --toc-border-width: {{ formatDim($section['toc_border_width'] ?? '1') }};
    --toc-border-style: {{ $section['toc_border_style'] ?? 'solid' }};
    --toc-border-radius: {{ formatDim($section['toc_border_radius'] ?? '16') }};
    --toc-padding: {{ formatDim($section['toc_padding'] ?? '22') }};
    --toc-margin: {{ formatDim($section['toc_margin'] ?? '0') }};
    
    --toc-title-font: {{ $section['toc_title_font'] ?? 'Playfair Display, serif' }};
    --toc-title-size: {{ formatDim($section['toc_title_size'] ?? '26') }};
    --toc-title-weight: {{ $section['toc_title_weight'] ?? '700' }};
    --toc-title-height: {{ formatDim($section['toc_title_height'] ?? '24') }};
    --toc-title-color: {{ $section['toc_title_color'] ?? '#3a2a1f' }};
    
    --toc-active-font: {{ $section['toc_active_font'] ?? 'Poppins, sans-serif' }};
    --toc-active-size: {{ formatDim($section['toc_active_size'] ?? '18') }};
    --toc-active-weight: {{ $section['toc_active_weight'] ?? '600' }};
    --toc-active-height: {{ formatDim($section['toc_active_height'] ?? '24') }};
    --toc-active-color: {{ $section['toc_active_color'] ?? '#393939' }};
    --toc-active-indicator: {{ $section['toc_active_indicator_color'] ?? '#3b2d22' }};
    
    --toc-inactive-font: {{ $section['toc_inactive_font'] ?? 'Poppins, sans-serif' }};
    --toc-inactive-size: {{ formatDim($section['toc_inactive_size'] ?? '18') }};
    --toc-inactive-weight: {{ $section['toc_inactive_weight'] ?? '500' }};
    --toc-inactive-height: {{ formatDim($section['toc_inactive_height'] ?? '24') }};
    --toc-inactive-color: {{ $section['toc_inactive_color'] ?? '#909090' }};
    
    --toc-content-heading-font: {{ $section['content_heading_font'] ?? 'Playfair Display, serif' }};
    --toc-content-heading-weight: {{ $section['content_heading_weight'] ?? '700' }};
    --toc-content-heading-size-desktop: {{ formatDim($section['content_heading_size_desktop'] ?? '40') }};
    --toc-content-heading-size-mobile: {{ formatDim($section['content_heading_size_mobile'] ?? '32') }};
    --toc-content-heading-height: {{ formatDim($section['content_heading_height'] ?? '20') }};
    --toc-content-heading-color: {{ $section['content_heading_color'] ?? '#2c2218' }};
    
    --toc-content-body-font: {{ $section['content_body_font'] ?? 'Poppins, sans-serif' }};
    --toc-content-body-weight: {{ $section['content_body_weight'] ?? '400' }};
    --toc-content-body-size-desktop: {{ formatDim($section['content_body_size_desktop'] ?? '18') }};
    --toc-content-body-size-mobile: {{ formatDim($section['content_body_size_mobile'] ?? '16') }};
    --toc-content-body-height-desktop: {{ formatDim($section['content_body_height_desktop'] ?? '31') }};
    --toc-content-body-height-mobile: {{ formatDim($section['content_body_height_mobile'] ?? '28') }};
    --toc-content-body-color: {{ $section['content_body_color'] ?? '#393939' }};
">
    @if (!empty($section['subtitle']))
        <p class="ttf-story-section__eyebrow">{{ $section['subtitle'] }}</p>
    @endif
    @if ($showTitle && $title !== '')
        <h2 class="sticky-header">{!! $titleHtml !!}</h2>
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
            <div id="remote-privacy-content" class="ttf-rich-text"></div>
        </div>
    </div>
</section>

<script>
    (function () {
        function initTocSections() {
            const tocSections = document.querySelectorAll('.ttf-story-section--toc');
            tocSections.forEach(function (section) {
                const tabs = section.querySelectorAll('.ttf-policy-toc a');
                const contents = section.querySelectorAll('.ttf-policy-content .ttf-policy-section, #remote-privacy-content h2');
                if (tabs.length === 0 || contents.length === 0) {
                    return;
                }

                function setActive(targetId) {
                    const tocSidebar = section.querySelector('.ttf-policy-toc');
                    const isMobile = tocSidebar ? window.getComputedStyle(tocSidebar).display === 'none' : true;

                    tabs.forEach(function (tab) {
                        const li = tab.closest('li');
                        const isActive = tab.getAttribute('href') === '#' + targetId;
                        tab.classList.toggle('active', isActive);
                        if (li) {
                            li.classList.toggle('active', isActive);
                        }
                    });

                    // No need to hide/show sections; all content is displayed by default.
                    // Keep this loop for potential future enhancements but currently does nothing.
                    // contents.forEach(function (content) {
                    //     // No display toggling needed.
                    // });
                }

                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function (e) {
                        const targetId = this.getAttribute('href').replace('#', '');
                        setActive(targetId);
                    });
                });

                if ('IntersectionObserver' in window) {
                    const observer = new IntersectionObserver(function (entries) {
                        // Determine visible entry based on highest intersection ratio
                        const visibleEntry = entries
                            .filter(function (entry) {
                                return entry.isIntersecting;
                            })
                            .sort(function (a, b) {
                                return b.intersectionRatio - a.intersectionRatio;
                            })[0];
                        if (visibleEntry) {
                            setActive(visibleEntry.target.id);
                        }
                    }, {
                        rootMargin: '-20% 0px -55% 0px',
                        threshold: [0.2, 0.4, 0.65]
                    });
                    contents.forEach(function (content) {
                        observer.observe(content);
                    });
                }

                // Initial activation
                const firstId = contents[0].id;
                setActive(firstId);

                // Resize handler
                window.addEventListener('resize', function () {
                    const activeTab = section.querySelector('.ttf-policy-toc li.active a') || tabs[0];
                    if (activeTab) {
                        const targetId = activeTab.getAttribute('href').replace('#', '');
                        setActive(targetId);
                    }
                });
            });
        }

        function fetchPrivacyContent() {
            fetch('https://www.workday.com/en-us/privacy.html')
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const main = doc.querySelector('main') || doc.body;
                    const contentDiv = document.getElementById('remote-privacy-content');
                    if (contentDiv) {
                        contentDiv.innerHTML = main.innerHTML;
                        // Build TOC from h2 headings
                        const tocList = document.querySelector('.ttf-policy-toc ul');
                        if (tocList) {
                            tocList.innerHTML = '';
                            const headings = contentDiv.querySelectorAll('h2');
                            headings.forEach((h, idx) => {
                                const id = 'privacy-section-' + idx;
                                h.id = id;
                                const li = document.createElement('li');
                                const a = document.createElement('a');
                                a.href = '#' + id;
                                a.textContent = h.textContent.trim();
                                li.appendChild(a);
                                tocList.appendChild(li);
                            });
                        }
                        // Re-initialize observers for new sections
                        initTocSections();
                    }
                })
                .catch(err => console.error('Failed to load privacy content', err));
        }

        function init() {
            initTocSections();
            fetchPrivacyContent();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
</script>
