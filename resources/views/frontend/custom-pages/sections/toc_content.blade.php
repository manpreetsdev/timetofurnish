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
    --section-sidebar-width: {{ (int) ($section['sidebar_width'] ?? 386) }}px;
    --section-title-size: {{ !empty($section['title_font_size']) ? (is_numeric($section['title_font_size']) ? $section['title_font_size'] . 'px' : $section['title_font_size']) : '' }};
    --section-title-height: {{ !empty($section['title_line_height']) ? (is_numeric($section['title_line_height']) ? $section['title_line_height'] . 'px' : $section['title_line_height']) : '' }};
    --section-body-size: {{ !empty($section['body_font_size']) ? (is_numeric($section['body_font_size']) ? $section['body_font_size'] . 'px' : $section['body_font_size']) : '' }};
    --section-body-height: {{ !empty($section['body_line_height']) ? (is_numeric($section['body_line_height']) ? $section['body_line_height'] . 'px' : $section['body_line_height']) : '' }};
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
        function initTocSections() {
            const tocSections = document.querySelectorAll('.ttf-story-section--toc');
            tocSections.forEach(function (section) {
                const tabs = section.querySelectorAll('.ttf-policy-toc a');
                const contents = section.querySelectorAll('.ttf-policy-content .ttf-policy-section');
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

                    contents.forEach(function (content) {
                        if (isMobile) {
                            content.style.display = 'block';
                            content.style.animation = 'none';
                        } else {
                            const isActiveContent = content.getAttribute('id') === targetId;
                            if (isActiveContent) {
                                content.style.display = 'block';
                                content.style.animation = 'ttfFadeIn 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards';
                            } else {
                                content.style.display = 'none';
                            }
                        }
                    });
                }

                tabs.forEach(function (tab) {
                    tab.addEventListener('click', function (e) {
                        const tocSidebar = section.querySelector('.ttf-policy-toc');
                        const isMobile = tocSidebar ? window.getComputedStyle(tocSidebar).display === 'none' : true;

                        if (!isMobile) {
                            e.preventDefault();
                            const targetId = this.getAttribute('href').replace('#', '');
                            setActive(targetId);
                        }
                    });
                });

                if ('IntersectionObserver' in window) {
                    const observer = new IntersectionObserver(function (entries) {
                        const tocSidebar = section.querySelector('.ttf-policy-toc');
                        const isMobile = tocSidebar ? window.getComputedStyle(tocSidebar).display === 'none' : true;
                        
                        if (isMobile) {
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

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTocSections);
        } else {
            initTocSections();
        }
    })();
</script>
