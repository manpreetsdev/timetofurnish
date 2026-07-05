@php
    $styles = $pageBuilderData['styles'] ?? [];
    $templateType = $pageBuilderData['template'] ?? \App\Support\CustomPageTemplate::TEMPLATE_CLASSIC;
    $classicBlocks = $pageBuilderData['classic_blocks'] ?? [];
    $policySections = $pageBuilderData['policy_sections'] ?? [];
    $policyContent = $templateType === \App\Support\CustomPageTemplate::TEMPLATE_POLICY
        ? \App\Support\CustomPageTemplate::renderPolicyContent($pageBuilderData['policy_html'] ?? '')
        : ['html' => '', 'toc' => []];
@endphp

<div
    class="ttf-custom-page"
    style="
        --ttf-page-bg: {{ $styles['page_background'] ?? '#FAF8F5' }};
        --ttf-content-bg: {{ $styles['content_background'] ?? '#ffffff' }};
        --ttf-card-bg: {{ $styles['card_background'] ?? '#fffdf9' }};
        --ttf-card-border: {{ $styles['card_border_color'] ?? '#21252933' }};
        --ttf-accent: {{ $styles['accent_color'] ?? '#C27325' }};
        --ttf-heading: {{ $styles['heading_color'] ?? '#2c2218' }};
        --ttf-subheading: {{ $styles['subheading_color'] ?? '#5b4839' }};
        --ttf-text: {{ $styles['paragraph_color'] ?? '#393939' }};
        --ttf-muted: {{ $styles['muted_color'] ?? '#8a786a' }};
        --ttf-heading-font: {{ $styles['heading_font_family'] ?? 'Playfair Display, serif' }};
        --ttf-subheading-font: {{ $styles['subheading_font_family'] ?? 'Poppins, sans-serif' }};
        --ttf-text-font: {{ $styles['paragraph_font_family'] ?? 'Poppins, sans-serif' }};
        --ttf-heading-weight: {{ $styles['heading_font_weight'] ?? '700' }};
        --ttf-body-weight: {{ $styles['body_font_weight'] ?? '400' }};
        --ttf-container-width: {{ (int) ($styles['container_width'] ?? 1440) }}px;
        --ttf-section-gap: {{ (int) ($styles['section_spacing'] ?? 54) }}px;
        --ttf-toc-bg: {{ $styles['toc_background'] ?? '#fffdf9' }};
        --ttf-toc-border: {{ $styles['toc_border_color'] ?? '#21252933' }};
        --ttf-toc-heading: {{ $styles['toc_heading_color'] ?? '#3a2a1f' }};
        --ttf-toc-text: {{ $styles['toc_text_color'] ?? '#6f5f52' }};
    "
>
    @include('frontend.custom-pages.partials.banner', [
        'banner' => $pageBuilderData['banner'] ?? [],
        'page' => $page,
    ])

    @if (!empty($pageBuilderData['sections']))
        <section class="ttf-custom-page__body">
            <div class="ttf-custom-page__container">
                @foreach (($pageBuilderData['sections'] ?? []) as $section)
                    @if (($section['type'] ?? '') === 'section_group' || !empty($section['widgets']))
                        @include('frontend.custom-pages.partials.section_group', ['group' => $section])
                    @else
                        @includeIf('frontend.custom-pages.sections.' . ($section['type'] ?? 'text'), ['section' => $section])
                    @endif
                @endforeach
            </div>
        </section>
    @elseif ($templateType === \App\Support\CustomPageTemplate::TEMPLATE_POLICY)
        <section class="ttf-custom-page__body">
            <div class="ttf-custom-page__container">
                @if (!empty($pageBuilderData['policy_intro']))
                    <div class="ttf-policy-lead">
                        {!! nl2br(e($pageBuilderData['policy_intro'])) !!}
                    </div>
                @endif

                @php
                    $policyToc = [];
                    $usedPolicyIds = [];
                    foreach ($policySections as $policyIndex => $policySection) {
                        $policyTitle = trim((string) ($policySection['title'] ?? ''));
                        if ($policyTitle === '') {
                            $policyTitle = 'Section ' . ($policyIndex + 1);
                        }

                        $basePolicyId = trim(preg_replace('/[^a-z0-9]+/i', '-', strtolower($policyTitle)), '-');
                        $basePolicyId = $basePolicyId !== '' ? $basePolicyId : 'section';
                        $policyId = $basePolicyId;
                        $policyCounter = 2;

                        while (in_array($policyId, $usedPolicyIds, true)) {
                            $policyId = $basePolicyId . '-' . $policyCounter;
                            $policyCounter++;
                        }

                        $usedPolicyIds[] = $policyId;
                        $policyToc[] = [
                            'id' => $policyId,
                            'title' => $policyTitle,
                            'section' => $policySection,
                        ];
                    }
                @endphp

                <div class="ttf-policy-layout @if (empty($policyToc) && empty($policyContent['toc'])) ttf-policy-layout--single @endif">
                    @if (!empty($policyToc) || !empty($policyContent['toc']))
                        <aside class="ttf-policy-toc">
                            <h2>{{ translate('Table of Contents') }}</h2>
                            <ul>
                                @if (!empty($policyToc))
                                    @foreach ($policyToc as $tocItem)
                                        <li>
                                            <a href="#{{ $tocItem['id'] }}">{{ $tocItem['title'] }}</a>
                                        </li>
                                    @endforeach
                                @else
                                    @foreach ($policyContent['toc'] as $tocItem)
                                        <li class="{{ $tocItem['level'] === 'h3' ? 'is-child' : '' }}">
                                            <a href="#{{ $tocItem['id'] }}">{{ $tocItem['label'] }}</a>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </aside>
                    @endif

                    <div class="ttf-policy-content">
                        @if (!empty($policyToc))
                            @foreach ($policyToc as $tocItem)
                                <section class="ttf-policy-section" id="{{ $tocItem['id'] }}">
                                    <h2>{{ $tocItem['title'] }}</h2>
                                    @if (!empty($tocItem['section']['summary']))
                                        <p class="ttf-policy-section__summary">{{ $tocItem['section']['summary'] }}</p>
                                    @endif
                                    {!! $tocItem['section']['content'] ?? '' !!}
                                    @php
                                        $policyItems = collect($tocItem['section']['items'] ?? [])->pluck('text')->filter();
                                    @endphp
                                    @if ($policyItems->isNotEmpty())
                                        <ul>
                                            @foreach ($policyItems as $policyItem)
                                                <li>{{ $policyItem }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </section>
                            @endforeach
                        @else
                            {!! $policyContent['html'] !!}
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="ttf-custom-page__body">
            <div class="ttf-custom-page__container">
                @if (!empty($classicBlocks))
                    @foreach ($classicBlocks as $block)
                        @include('frontend.custom-pages.sections.classic_block', ['block' => $block])
                    @endforeach
                @endif

                @if (!empty($pageBuilderData['classic_html']))
                    <div class="ttf-classic-content @if (!empty($classicBlocks)) ttf-classic-content--legacy @endif">
                        {!! $pageBuilderData['classic_html'] ?? '' !!}
                    </div>
                @endif
            </div>
        </section>
    @endif
</div>
