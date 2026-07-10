<?php

namespace App\Support;

class CustomPageTemplate
{
    public const TEMPLATE_CLASSIC = 'classic';
    public const TEMPLATE_POLICY = 'policy';
    public const TEMPLATE_STORY = 'structured_story';

    public static function defaultPayload(?string $title = null): array
    {
        return self::normalize([
            'page_builder' => true,
            'template' => self::TEMPLATE_STORY,
            'banner' => [
                'title' => $title ?? '',
                'breadcrumb_label' => $title ?? '',
            ],
            'styles' => self::defaultStyles(),
            'classic_html' => '',
            'classic_blocks' => [],
            'policy_intro' => '',
            'policy_html' => '',
            'policy_sections' => [],
            'sections' => [],
        ], $title);
    }

    public static function fromContent(?string $content, ?string $title = null): array
    {
        $payload = self::decodePayload($content);

        if ($payload === null) {
            return self::legacyPayload($content, $title);
        }

        return self::normalize($payload, $title);
    }

    public static function encode(array $payload, ?string $title = null): string
    {
        return json_encode(
            self::normalize($payload, $title),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }

    public static function fontFamilyOptions(): array
    {
        return [
            'Playfair Display, serif' => 'Playfair Display',
            'Poppins, sans-serif' => 'Poppins',
        ];
    }

    public static function classicBlockTemplates(): array
    {
        return [
            'content' => [
                'type' => 'content',
                'layout' => 'text_only',
                'title' => '',
                'highlight_text' => '',
                'content' => '',
                'image' => '',
                'image_alt' => '',
                'background_color' => '',
                'title_font_family' => '',
                'title_color' => '',
                'body_font_family' => '',
                'body_color' => '',
                'accent_color' => '',
            ],
        ];
    }

    public static function policySectionTemplate(): array
    {
        return [
            'title' => '',
            'summary' => '',
            'content' => '',
            'items' => [
                ['text' => ''],
            ],
        ];
    }

    public static function structuredSectionTemplates(): array
    {
        $themeAccent = self::defaultAccentColor();

        $shared = [
            'title' => '',
            'show_title' => '1',
            'subtitle' => '',
            'highlight_text' => '',
            'content' => '',
            'background_color' => '',
            'title_font_family' => '',
            'title_color' => '',
            'title_font_size' => '28',
            'title_font_weight' => '700',
            'title_line_height' => '1.18',
            'title_letter_spacing' => '0',
            'body_font_family' => '',
            'body_color' => '',
            'body_font_size' => '18',
            'body_font_weight' => '400',
            'body_line_height' => '1.72',
            'body_letter_spacing' => '0',
            'subtitle_color' => '',
            'accent_color' => '',
            'highlight_color' => '',
            'show_background' => '0',
            'show_border' => '0',
            'use_padding' => '0',
            'border_color' => '',
            'border_radius' => '24',
            'border_width' => '',
            'border_style' => 'none',
            'section_padding' => '50',
            'padding_top' => '50',
            'padding_bottom' => '50',
            'padding_left' => '0',
            'padding_right' => '0',
            'padding_left_right' => '0',
            'margin_top' => '0',
            'margin_bottom' => '0',
            'column_index' => '0',
            'show_on_desktop' => '1',
            'show_on_ipad_pro' => '1',
            'show_on_ipad' => '1',
            'show_on_phone' => '1',
        ];

        return [
            'split' => array_merge($shared, [
                'type' => 'split',
                'layout' => 'image_left',
                'tablet_stack_order' => 'content_first',
                'mobile_stack_order' => 'content_first',
                'image' => '',
                'image_alt' => '',
                'title_font_family' => 'Playfair Display, serif',
                'title_font_size' => '36',
                'title_line_height' => '1.39',
                'body_font_family' => 'Poppins, sans-serif',
                'body_font_size' => '18',
                'body_line_height' => '1.78',
                'body_font_weight' => '700',
                'highlight_color' => $themeAccent,
                'check_icon_color' => $themeAccent,
                'accent_color' => $themeAccent,
                'image_height' => '553',
                'border_radius' => '10',
                'items' => [
                    ['text' => ''],
                ],
            ]),
            'full_width' => array_merge($shared, [
                'type' => 'full_width',
                'display_mode' => 'content_only',
                'image_position' => 'bottom',
                'tablet_image_position' => 'bottom',
                'mobile_image_position' => 'bottom',
                'text_align' => 'left',
                'image' => '',
                'image_alt' => '',
                'title_font_family' => 'Playfair Display, serif',
                'title_font_size' => '36',
                'title_line_height' => '1.39',
                'body_font_family' => 'Poppins, sans-serif',
                'body_font_size' => '18',
                'body_line_height' => '1.83',
                'body_font_weight' => '400',
                'highlight_color' => $themeAccent,
                'accent_color' => $themeAccent,
                'image_height' => '553',
                'border_radius' => '10',
            ]),
            'image_grid' => array_merge($shared, [
                'type' => 'image_grid',
                'columns' => '3',
                'card_image_height' => '240',
                'items' => [
                    [
                        'title' => '',
                        'text' => '',
                        'image' => '',
                    ],
                ],
            ]),
            'full_image' => array_merge($shared, [
                'type' => 'full_image',
                'image' => '',
                'image_alt' => '',
                'image_height' => '520',
            ]),
            'rich_text' => array_merge($shared, [
                'type' => 'rich_text',
                'text_align' => 'left',
                'max_width' => '100',
            ]),
            'toc_content' => array_merge($shared, [
                'type' => 'toc_content',
                'toc_title' => 'Table of Contents',
                'sidebar_width' => '386',
                'sticky_sidebar' => '1',
                'toc_bg_color' => '#FAF8F5',
                'toc_border_color' => '#FAF8F5',
                'toc_border_width' => '1',
                'toc_border_style' => 'solid',
                'toc_border_radius' => '16',
                'toc_padding' => '22',
                'toc_margin' => '0',
                'toc_title_font' => 'Playfair Display, serif',
                'toc_title_size' => '26',
                'toc_title_weight' => '700',
                'toc_title_height' => '24',
                'toc_title_color' => '#3a2a1f',
                'toc_active_font' => 'Poppins, sans-serif',
                'toc_active_size' => '18',
                'toc_active_weight' => '600',
                'toc_active_height' => '24',
                'toc_active_color' => '#393939',
                'toc_active_indicator_color' => '#3b2d22',
                'toc_inactive_font' => 'Poppins, sans-serif',
                'toc_inactive_size' => '18',
                'toc_inactive_weight' => '500',
                'toc_inactive_height' => '24',
                'toc_inactive_color' => '#909090',
                'content_heading_font' => 'Playfair Display, serif',
                'content_heading_weight' => '700',
                'content_heading_size_desktop' => '40',
                'content_heading_size_mobile' => '32',
                'content_heading_height' => '20',
                'content_heading_color' => '#2c2218',
                'content_body_font' => 'Poppins, sans-serif',
                'content_body_weight' => '400',
                'content_body_size_desktop' => '18',
                'content_body_size_mobile' => '16',
                'content_body_height_desktop' => '31',
                'content_body_height_mobile' => '28',
                'content_body_color' => '#393939',
                'items' => [
                    [
                        'title' => '',
                        'anchor_id' => '',
                        'image' => '',
                        'summary' => '',
                        'content' => '',
                    ],
                ],
            ]),
            'header_widget' => array_merge($shared, [
                'type' => 'header_widget',
                'header_tag' => 'h2',
                'text_align' => 'left',
            ]),
            'image_widget' => array_merge($shared, [
                'type' => 'image_widget',
                'image' => '',
                'image_alt' => '',
                'image_height' => '',
                'image_width' => '100',
                'image_align' => 'center',
                'image_link' => '',
            ]),
            'button_widget' => array_merge($shared, [
                'type' => 'button_widget',
                'button_text' => 'Click Here',
                'button_link' => '',
                'button_align' => 'left',
                'button_bg_color' => $themeAccent,
                'button_text_color' => '#ffffff',
                'button_font_size' => '16',
                'button_border_radius' => '6',
                'button_padding' => '12px 24px',
            ]),
        ];
    }

    public static function sectionGroupTemplate(): array
    {
        return [
            'type' => 'section_group',
            'name' => '',
            'columns' => '1',
            'show_background' => '0',
            'show_border' => '0',
            'use_padding' => '0',
            'background_color' => '',
            'border_color' => '',
            'border_radius' => '24',
            'section_padding' => '50',
            'padding_top' => '50',
            'padding_bottom' => '50',
            'padding_left' => '0',
            'padding_right' => '0',
            'section_gap' => '18',
            'show_on_desktop' => '1',
            'show_on_ipad_pro' => '1',
            'show_on_ipad' => '1',
            'show_on_phone' => '1',
            'widgets' => [],
        ];
    }

    public static function renderPolicyContent(?string $html): array
    {
        $html = trim((string) $html);

        if ($html === '') {
            return [
                'html' => '',
                'toc' => [],
            ];
        }

        libxml_use_internal_errors(true);

        $document = new \DOMDocument('1.0', 'UTF-8');
        $document->loadHTML(
            '<?xml encoding="utf-8" ?><div class="policy-root">' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        $xpath = new \DOMXPath($document);
        $headings = $xpath->query('//h2 | //h3');
        $toc = [];
        $usedIds = [];

        foreach ($headings as $heading) {
            $text = trim($heading->textContent);

            if ($text === '') {
                continue;
            }

            $baseId = preg_replace('/[^a-z0-9]+/i', '-', strtolower($text));
            $baseId = trim((string) $baseId, '-');
            $baseId = $baseId !== '' ? $baseId : 'section';
            $anchorId = $baseId;
            $counter = 2;

            while (in_array($anchorId, $usedIds, true)) {
                $anchorId = $baseId . '-' . $counter;
                $counter++;
            }

            $usedIds[] = $anchorId;
            $heading->setAttribute('id', $anchorId);

            $toc[] = [
                'id' => $anchorId,
                'label' => $text,
                'level' => $heading->nodeName,
            ];
        }

        $root = $document->getElementsByTagName('div')->item(0);
        $renderedHtml = '';

        if ($root !== null) {
            foreach ($root->childNodes as $childNode) {
                $renderedHtml .= $document->saveHTML($childNode);
            }
        }

        libxml_clear_errors();

        return [
            'html' => $renderedHtml,
            'toc' => $toc,
        ];
    }

    protected static function decodePayload(?string $content): ?array
    {
        if (!is_string($content)) {
            return null;
        }

        $trimmed = trim($content);

        if ($trimmed === '' || ($trimmed[0] ?? '') !== '{') {
            return null;
        }

        $decoded = json_decode($trimmed, true);

        if (!is_array($decoded) || !($decoded['page_builder'] ?? false)) {
            return null;
        }

        return $decoded;
    }

    protected static function legacyPayload(?string $content, ?string $title = null): array
    {
        return self::normalize([
            'page_builder' => true,
            'template' => self::TEMPLATE_STORY,
            'banner' => [
                'title' => $title ?? '',
                'breadcrumb_label' => $title ?? '',
            ],
            'styles' => self::defaultStyles(),
            'classic_html' => (string) $content,
            'classic_blocks' => [],
            'policy_intro' => '',
            'policy_html' => '',
            'policy_sections' => [],
            'sections' => [],
        ], $title);
    }

    protected static function normalize(array $payload, ?string $title = null): array
    {
        $defaultPayload = self::defaultPayloadSkeleton($title);
        $template = $payload['template'] ?? self::TEMPLATE_STORY;

        if (!in_array($template, [self::TEMPLATE_CLASSIC, self::TEMPLATE_POLICY, self::TEMPLATE_STORY], true)) {
            $template = self::TEMPLATE_STORY;
        }

        $banner = array_merge($defaultPayload['banner'], (array) ($payload['banner'] ?? []));
        $banner['title'] = $banner['title'] !== '' ? $banner['title'] : (string) $title;
        $banner['breadcrumb_label'] = $banner['breadcrumb_label'] !== '' ? $banner['breadcrumb_label'] : $banner['title'];

        $styles = array_merge($defaultPayload['styles'], (array) ($payload['styles'] ?? []));
        $normalizedClassicBlocks = [];
        foreach ((array) ($payload['classic_blocks'] ?? []) as $block) {
            if (!is_array($block)) {
                continue;
            }

            $normalizedClassicBlocks[] = self::normalizeClassicBlock($block);
        }

        $normalizedPolicySections = [];
        foreach ((array) ($payload['policy_sections'] ?? []) as $section) {
            if (!is_array($section)) {
                continue;
            }

            $normalizedPolicySections[] = self::normalizePolicySection($section);
        }

        $normalizedSections = [];
        foreach ((array) ($payload['sections'] ?? []) as $section) {
            if (!is_array($section)) {
                continue;
            }

            if (($section['type'] ?? null) === 'section_group' || array_key_exists('widgets', $section)) {
                $normalizedSections[] = self::normalizeGroup($section);
                continue;
            }

            $normalizedSections[] = self::normalizeGroup([
                'name' => (string) ($section['title'] ?? ''),
                'widgets' => [$section],
            ]);
        }

        if ($normalizedSections === []) {
            $normalizedSections = self::buildGroupsFromLegacyPayload([
                'classic_html' => (string) ($payload['classic_html'] ?? ''),
                'classic_blocks' => $normalizedClassicBlocks,
                'policy_intro' => (string) ($payload['policy_intro'] ?? ''),
                'policy_html' => (string) ($payload['policy_html'] ?? ''),
                'policy_sections' => $normalizedPolicySections,
            ]);
        }

        return [
            'page_builder' => true,
            'template' => $normalizedSections !== [] ? self::TEMPLATE_STORY : $template,
            'banner' => $banner,
            'styles' => $styles,
            'classic_html' => (string) ($payload['classic_html'] ?? ''),
            'classic_blocks' => $normalizedClassicBlocks,
            'policy_intro' => (string) ($payload['policy_intro'] ?? ''),
            'policy_html' => (string) ($payload['policy_html'] ?? ''),
            'policy_sections' => $normalizedPolicySections,
            'sections' => $normalizedSections,
        ];
    }

    protected static function defaultPayloadSkeleton(?string $title = null): array
    {
        return [
            'banner' => [
                'title' => $title ?? '',
                'breadcrumb_label' => $title ?? '',
                'subtitle' => '',
                'background_image' => '',
                'overlay_color' => 'rgba(54, 38, 26, 0.42)',
                'height' => '340',
                'text_align' => 'center',
                'title_font_family' => 'Playfair Display, serif',
                'subtitle_font_family' => 'Poppins, sans-serif',
                'title_color' => '#ffffff',
                'subtitle_color' => '#f8f0e7',
            ],
            'styles' => self::defaultStyles(),
        ];
    }

    protected static function defaultStyles(): array
    {
        $themeAccent = self::defaultAccentColor();

        return [
            'page_background' => '#FAF8F5',
            'content_background' => '#ffffff',
            'card_background' => '#fffdf9',
            'card_border_color' => 'rgba(33, 37, 41, 0.2)',
            'accent_color' => $themeAccent,
            'heading_color' => '#2c2218',
            'subheading_color' => '#5b4839',
            'paragraph_color' => '#393939',
            'muted_color' => '#8a786a',
            'heading_font_family' => 'Playfair Display, serif',
            'subheading_font_family' => 'Poppins, sans-serif',
            'paragraph_font_family' => 'Poppins, sans-serif',
            'heading_font_weight' => '700',
            'body_font_weight' => '400',
            'container_width' => '1440',
            'section_spacing' => '54',
            'toc_background' => '#fffdf9',
            'toc_border_color' => 'rgba(33, 37, 41, 0.2)',
            'toc_heading_color' => '#3a2a1f',
            'toc_text_color' => '#6f5f52',
        ];
    }

    public static function colorPickerValue(?string $value, string $fallback = '#ffffff'): string
    {
        $normalizedFallback = self::normalizeHexColor($fallback) ?? '#ffffff';
        $value = trim((string) $value);

        if ($value === '') {
            return $normalizedFallback;
        }

        $hexColor = self::normalizeHexColor($value);
        if ($hexColor !== null) {
            return $hexColor;
        }

        if (preg_match('/rgba?\(([^)]+)\)/i', $value, $matches)) {
            $parts = array_map('trim', explode(',', $matches[1]));
            if (count($parts) >= 3) {
                $rgb = array_slice($parts, 0, 3);
                $channels = [];
                foreach ($rgb as $channel) {
                    if (!is_numeric($channel)) {
                        return $normalizedFallback;
                    }
                    $channels[] = max(0, min(255, (int) round((float) $channel)));
                }

                return sprintf('#%02x%02x%02x', $channels[0], $channels[1], $channels[2]);
            }
        }

        return $normalizedFallback;
    }

    public static function normalizeLineHeightValue($value, $fontSize, string $fallback = '1.4'): string
    {
        $rawValue = trim((string) $value);
        $fontSizeValue = (float) $fontSize;
        $fallbackValue = max(0.8, min(4, (float) $fallback));

        if ($rawValue === '') {
            return self::formatDecimalValue($fallbackValue);
        }

        if (!preg_match('/-?\d+(\.\d+)?/', $rawValue, $matches)) {
            return self::formatDecimalValue($fallbackValue);
        }

        $numericValue = (float) $matches[0];
        if ($numericValue <= 0) {
            return self::formatDecimalValue($fallbackValue);
        }

        if ($numericValue > 6 && $fontSizeValue > 0) {
            $numericValue = $numericValue / $fontSizeValue;
        }

        $numericValue = max(0.8, min(4, $numericValue));

        return self::formatDecimalValue($numericValue);
    }

    protected static function defaultAccentColor(): string
    {
        return function_exists('get_setting')
            ? get_setting('base_color', '#d43533')
            : '#d43533';
    }

    protected static function normalizeSection(array $section): array
    {
        $type = $section['type'] ?? 'split';
        $templates = self::structuredSectionTemplates();
        $legacyTypeMap = [
            'text' => 'full_width',
            'feature_list' => 'split',
            'content' => 'rich_text',
        ];

        if (isset($legacyTypeMap[$type])) {
            $type = $legacyTypeMap[$type];
        }

        $defaults = $templates[$type] ?? $templates['split'];

        $normalized = array_merge($defaults, $section);
        $normalized['type'] = $defaults['type'];
        $normalized['title_font_size'] = trim((string) ($normalized['title_font_size'] ?? '')) !== ''
            ? (string) $normalized['title_font_size']
            : (string) ($defaults['title_font_size'] ?? '28');
        $normalized['body_font_size'] = trim((string) ($normalized['body_font_size'] ?? '')) !== ''
            ? (string) $normalized['body_font_size']
            : (string) ($defaults['body_font_size'] ?? '18');
        $normalized['title_line_height'] = self::normalizeLineHeightValue(
            $normalized['title_line_height'] ?? ($defaults['title_line_height'] ?? '1.18'),
            $normalized['title_font_size'],
            (string) ($defaults['title_line_height'] ?? '1.18')
        );
        $normalized['body_line_height'] = self::normalizeLineHeightValue(
            $normalized['body_line_height'] ?? ($defaults['body_line_height'] ?? '1.72'),
            $normalized['body_font_size'],
            (string) ($defaults['body_line_height'] ?? '1.72')
        );
        $backgroundColor = trim((string) ($normalized['background_color'] ?? ''));
        $borderColor = trim((string) ($normalized['border_color'] ?? ''));
        $paddingValue = (int) ($normalized['section_padding'] ?? 0);

        $normalized['show_background'] = array_key_exists('show_background', $section)
            ? (!empty($section['show_background']) ? '1' : '0')
            : ($backgroundColor !== '' ? '1' : '0');
        $normalized['show_border'] = array_key_exists('show_border', $section)
            ? (!empty($section['show_border']) ? '1' : '0')
            : ($borderColor !== '' ? '1' : '0');
        $normalized['use_padding'] = array_key_exists('use_padding', $section)
            ? (!empty($section['use_padding']) ? '1' : '0')
            : (($paddingValue > 0 && ($backgroundColor !== '' || $borderColor !== '')) ? '1' : '0');
        $normalized['show_on_desktop'] = !array_key_exists('show_on_desktop', $section) || !empty($section['show_on_desktop']) ? '1' : '0';
        $normalized['show_on_ipad_pro'] = !array_key_exists('show_on_ipad_pro', $section) || !empty($section['show_on_ipad_pro']) ? '1' : '0';
        $normalized['show_on_ipad'] = !array_key_exists('show_on_ipad', $section) || !empty($section['show_on_ipad']) ? '1' : '0';
        $normalized['show_on_phone'] = !array_key_exists('show_on_phone', $section) || !empty($section['show_on_phone']) ? '1' : '0';
        $normalized['tablet_stack_order'] = in_array(($section['tablet_stack_order'] ?? $normalized['tablet_stack_order'] ?? 'content_first'), ['content_first', 'image_first'], true)
            ? ($section['tablet_stack_order'] ?? $normalized['tablet_stack_order'] ?? 'content_first')
            : 'content_first';
        $normalized['mobile_stack_order'] = in_array(($section['mobile_stack_order'] ?? $normalized['mobile_stack_order'] ?? 'content_first'), ['content_first', 'image_first'], true)
            ? ($section['mobile_stack_order'] ?? $normalized['mobile_stack_order'] ?? 'content_first')
            : 'content_first';
        $normalized['tablet_image_position'] = in_array(($section['tablet_image_position'] ?? $normalized['tablet_image_position'] ?? 'bottom'), ['top', 'bottom'], true)
            ? ($section['tablet_image_position'] ?? $normalized['tablet_image_position'] ?? 'bottom')
            : 'bottom';
        $normalized['mobile_image_position'] = in_array(($section['mobile_image_position'] ?? $normalized['mobile_image_position'] ?? 'bottom'), ['top', 'bottom'], true)
            ? ($section['mobile_image_position'] ?? $normalized['mobile_image_position'] ?? 'bottom')
            : 'bottom';

        if (($section['type'] ?? null) === 'feature_list') {
            $intro = trim((string) ($section['intro'] ?? ''));
            $items = collect((array) ($section['items'] ?? []))
                ->map(function ($item) {
                    return trim((string) ($item['text'] ?? ''));
                })
                ->filter()
                ->map(function ($text) {
                    return '<li>' . e($text) . '</li>';
                })
                ->implode('');

            $legacyContent = [];
            if ($intro !== '') {
                $legacyContent[] = '<p>' . e($intro) . '</p>';
            }
            if ($items !== '') {
                $legacyContent[] = '<ul>' . $items . '</ul>';
            }

            if (trim((string) ($section['content'] ?? '')) === '') {
                $normalized['content'] = implode('', $legacyContent);
            }
        }

        if ($type === 'split') {
            $normalized['items'] = [];

            foreach ((array) ($section['items'] ?? []) as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $normalized['items'][] = ['text' => (string) ($item['text'] ?? '')];
            }

            if ($normalized['items'] === []) {
                $normalized['items'][] = ['text' => ''];
            }
        }

        if ($type === 'image_grid') {
            $normalized['items'] = [];

            foreach ((array) ($section['items'] ?? []) as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $normalized['items'][] = [
                    'title' => (string) ($item['title'] ?? ''),
                    'text' => (string) ($item['text'] ?? ''),
                    'image' => (string) ($item['image'] ?? ''),
                ];
            }

            if ($normalized['items'] === []) {
                $normalized['items'][] = [
                    'title' => '',
                    'text' => '',
                    'image' => '',
                ];
            }
        }

        if ($type === 'toc_content') {
            $normalized['sticky_sidebar'] = !array_key_exists('sticky_sidebar', $section) || !empty($section['sticky_sidebar']) ? '1' : '0';
            $normalized['items'] = [];

            foreach ((array) ($section['items'] ?? []) as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $title = (string) ($item['title'] ?? '');
                $normalized['items'][] = [
                    'title' => $title,
                    'anchor_id' => (string) ($item['anchor_id'] ?? self::makeAnchorId($title)),
                    'image' => (string) ($item['image'] ?? ''),
                    'summary' => (string) ($item['summary'] ?? ''),
                    'content' => (string) ($item['content'] ?? ''),
                ];
            }

            if ($normalized['items'] === []) {
                $normalized['items'][] = [
                    'title' => '',
                    'anchor_id' => '',
                    'image' => '',
                    'summary' => '',
                    'content' => '',
                ];
            }
        }

        if (($section['type'] ?? null) === 'text') {
            $normalized['display_mode'] = 'content_only';
        }

        return $normalized;
    }

    protected static function normalizeGroup(array $group): array
    {
        $defaults = self::sectionGroupTemplate();
        $normalized = array_merge($defaults, $group);
        $backgroundColor = trim((string) ($normalized['background_color'] ?? ''));
        $borderColor = trim((string) ($normalized['border_color'] ?? ''));
        $paddingValue = (int) ($normalized['section_padding'] ?? 0);

        $normalized['show_background'] = array_key_exists('show_background', $group)
            ? (!empty($group['show_background']) ? '1' : '0')
            : ($backgroundColor !== '' ? '1' : '0');
        $normalized['show_border'] = array_key_exists('show_border', $group)
            ? (!empty($group['show_border']) ? '1' : '0')
            : ($borderColor !== '' ? '1' : '0');
        $normalized['use_padding'] = array_key_exists('use_padding', $group)
            ? (!empty($group['use_padding']) ? '1' : '0')
            : (($paddingValue > 0 && ($backgroundColor !== '' || $borderColor !== '')) ? '1' : '0');
        $normalized['show_on_desktop'] = !array_key_exists('show_on_desktop', $group) || !empty($group['show_on_desktop']) ? '1' : '0';
        $normalized['show_on_ipad_pro'] = !array_key_exists('show_on_ipad_pro', $group) || !empty($group['show_on_ipad_pro']) ? '1' : '0';
        $normalized['show_on_ipad'] = !array_key_exists('show_on_ipad', $group) || !empty($group['show_on_ipad']) ? '1' : '0';
        $normalized['show_on_phone'] = !array_key_exists('show_on_phone', $group) || !empty($group['show_on_phone']) ? '1' : '0';
        $normalized['widgets'] = [];

        foreach ((array) ($group['widgets'] ?? []) as $widget) {
            if (!is_array($widget)) {
                continue;
            }

            $normalized['widgets'][] = self::normalizeSection($widget);
        }

        if ($normalized['widgets'] === []) {
            $normalized['widgets'][] = self::normalizeSection([
                'type' => 'rich_text',
                'title' => '',
                'content' => '',
            ]);
        }

        return $normalized;
    }

    protected static function normalizeHexColor(string $value): ?string
    {
        $value = trim(strtolower($value));

        if (preg_match('/^#([0-9a-f]{3})$/', $value, $matches)) {
            return '#' . $matches[1][0] . $matches[1][0]
                . $matches[1][1] . $matches[1][1]
                . $matches[1][2] . $matches[1][2];
        }

        if (preg_match('/^#([0-9a-f]{6})$/', $value)) {
            return $value;
        }

        if (preg_match('/^#([0-9a-f]{8})$/', $value)) {
            return '#' . substr($value, 1, 6);
        }

        return null;
    }

    protected static function formatDecimalValue(float $value): string
    {
        $formatted = number_format($value, 2, '.', '');

        return rtrim(rtrim($formatted, '0'), '.');
    }

    protected static function buildGroupsFromLegacyPayload(array $payload): array
    {
        $groups = [];

        foreach ((array) ($payload['classic_blocks'] ?? []) as $block) {
            $groups[] = self::normalizeGroup([
                'name' => (string) ($block['title'] ?? 'Section'),
                'widgets' => [self::legacyClassicBlockToSection($block)],
            ]);
        }

        $classicHtml = trim((string) ($payload['classic_html'] ?? ''));
        if ($classicHtml !== '') {
            $groups[] = self::normalizeGroup([
                'name' => 'Text Section',
                'widgets' => [[
                    'type' => 'rich_text',
                    'title' => '',
                    'content' => $classicHtml,
                    'show_background' => '0',
                    'show_border' => '0',
                    'use_padding' => '0',
                ]],
            ]);
        }

        $policySections = (array) ($payload['policy_sections'] ?? []);
        $policyHtml = trim((string) ($payload['policy_html'] ?? ''));
        $policyIntro = trim((string) ($payload['policy_intro'] ?? ''));

        if ($policySections !== []) {
            $groups[] = self::normalizeGroup([
                'name' => 'Policy Section',
                'widgets' => [[
                    'type' => 'toc_content',
                    'content' => $policyIntro !== '' ? '<p>' . e($policyIntro) . '</p>' : '',
                    'items' => array_map(function ($item) {
                        return [
                            'title' => (string) ($item['title'] ?? ''),
                            'anchor_id' => self::makeAnchorId((string) ($item['title'] ?? '')),
                            'summary' => (string) ($item['summary'] ?? ''),
                            'content' => (string) ($item['content'] ?? ''),
                        ];
                    }, $policySections),
                ]],
            ]);
        } elseif ($policyHtml !== '' || $policyIntro !== '') {
            $content = [];

            if ($policyIntro !== '') {
                $content[] = '<p>' . e($policyIntro) . '</p>';
            }

            if ($policyHtml !== '') {
                $content[] = $policyHtml;
            }

            $groups[] = self::normalizeGroup([
                'name' => 'Policy Text',
                'widgets' => [[
                    'type' => 'rich_text',
                    'content' => implode('', $content),
                ]],
            ]);
        }

        return $groups;
    }

    protected static function legacyClassicBlockToSection(array $block): array
    {
        $layout = $block['layout'] ?? 'text_only';
        $hasImage = !empty($block['image']);
        $base = [
            'title' => (string) ($block['title'] ?? ''),
            'subtitle' => '',
            'highlight_text' => (string) ($block['highlight_text'] ?? ''),
            'content' => (string) ($block['content'] ?? ''),
            'background_color' => (string) ($block['background_color'] ?? ''),
            'title_font_family' => (string) ($block['title_font_family'] ?? ''),
            'title_color' => (string) ($block['title_color'] ?? ''),
            'body_font_family' => (string) ($block['body_font_family'] ?? ''),
            'body_color' => (string) ($block['body_color'] ?? ''),
            'accent_color' => (string) ($block['accent_color'] ?? ''),
            'show_background' => !empty($block['background_color']) ? '1' : '0',
        ];

        if ($hasImage && in_array($layout, ['image_left', 'image_right'], true)) {
            return array_merge($base, [
                'type' => 'split',
                'layout' => $layout,
                'image' => (string) ($block['image'] ?? ''),
                'image_alt' => (string) ($block['image_alt'] ?? ''),
            ]);
        }

        return array_merge($base, [
            'type' => 'rich_text',
        ]);
    }

    protected static function makeAnchorId(string $value): string
    {
        $baseId = preg_replace('/[^a-z0-9]+/i', '-', strtolower($value));
        $baseId = trim((string) $baseId, '-');

        return $baseId !== '' ? $baseId : 'section';
    }

    protected static function normalizeClassicBlock(array $block): array
    {
        $templates = self::classicBlockTemplates();
        $defaults = $templates[$block['type'] ?? 'content'] ?? $templates['content'];

        return array_merge($defaults, $block);
    }

    protected static function normalizePolicySection(array $section): array
    {
        $defaults = self::policySectionTemplate();
        $normalized = array_merge($defaults, $section);
        $normalized['items'] = [];

        foreach ((array) ($section['items'] ?? []) as $item) {
            if (!is_array($item)) {
                continue;
            }

            $normalized['items'][] = ['text' => (string) ($item['text'] ?? '')];
        }

        if ($normalized['items'] === []) {
            $normalized['items'][] = ['text' => ''];
        }

        return $normalized;
    }
}
