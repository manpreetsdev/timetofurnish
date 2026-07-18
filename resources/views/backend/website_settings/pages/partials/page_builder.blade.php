@php
    $banner        = $pageBuilderData['banner'] ?? [];
    $styles        = $pageBuilderData['styles'] ?? [];
    $sectionGroups = $pageBuilderData['sections'] ?? [];
    $isEdit        = $isEdit ?? false;
    $titleValue    = $titleValue ?? '';
    $slugValue     = $slugValue ?? '';
    $metaImageValue = $metaImageValue ?? '';
    $currentLang   = $currentLang ?? env('DEFAULT_LANGUAGE');

    $widgetLibrary = [
        'header_widget' => ['label' => translate('Heading'),       'icon' => 'las la-heading',        'desc' => translate('Title block')],
        'rich_text'     => ['label' => translate('Text Editor'),   'icon' => 'las la-align-left',     'desc' => translate('Rich text copy')],
        'image_widget'  => ['label' => translate('Image'),         'icon' => 'las la-image',           'desc' => translate('Responsive media')],
        'button_widget' => ['label' => translate('Button'),        'icon' => 'las la-link',            'desc' => translate('Call to action')],
        'split'         => ['label' => translate('Two Column'),    'icon' => 'las la-columns',         'desc' => translate('Image + text')],
        'full_width'    => ['label' => translate('Full Width'),    'icon' => 'las la-window-maximize', 'desc' => translate('Hero block')],
        'image_grid'    => ['label' => translate('Grid Cards'),    'icon' => 'las la-th-large',        'desc' => translate('Repeatable cards')],
        'full_image'    => ['label' => translate('Image Showcase'),'icon' => 'las la-image',           'desc' => translate('Large visual')],
        'toc_content'   => ['label' => translate('TOC + Content'), 'icon' => 'las la-list-alt',        'desc' => translate('Linked sidebar')],
    ];

    $pageTitle = $titleValue ?: ($isEdit && isset($page) ? $page->getTranslation('title', $currentLang) : translate('New Page'));
    $pageSlugDisplay = $slugValue ?: translate('new-page');
    $formAction = $isEdit && isset($page) ? route('custom-pages.update', $page->id) : route('custom-pages.store');
    $themeAccent = get_setting('base_color', '#d43533');
    $pickerColor = static fn ($value, $fallback = '#ffffff') => \App\Support\CustomPageTemplate::colorPickerValue($value, $fallback);
@endphp

{{-- ===================================================================
     FULL-SCREEN PAGE BUILDER — 3-COLUMN ELEMENTOR LAYOUT
     =================================================================== --}}
<div class="ttf-pb-root" id="ttf-page-builder"
     style="--pb-accent: {{ $themeAccent }}; --pb-accent-hover: {{ $themeAccent }}; --pb-soft: {{ hex2rgba($themeAccent, 0.12) }};">
<form class="ttf-pb-form" id="ttf-pb-form"
      action="{{ $formAction }}" method="POST" enctype="multipart/form-data" novalidate>
@csrf
@if ($isEdit)
    <input type="hidden" name="_method" value="PATCH">
    <input type="hidden" name="lang" value="{{ $currentLang }}">
@endif
<input type="hidden" name="template_type" value="{{ \App\Support\CustomPageTemplate::TEMPLATE_STORY }}">

{{-- ── HEADER BAR ─────────────────────────────────────────────────── --}}
<header class="ttf-pb-header">
    <div class="ttf-pb-header__left">
        <span class="ttf-pb-header__logo">TF</span>
        <nav class="ttf-pb-header__breadcrumb">
            <a href="{{ route('website.pages') }}" style="color:inherit;text-decoration:none;">{{ translate('Pages') }}</a>
            <i class="las la-angle-right"></i>
            <span id="ttf-pb-breadcrumb-title">{{ $pageTitle }}</span>
        </nav>
    </div>

    <div class="ttf-pb-header__center">
        <button type="button" class="ttf-pb-undo-btn" id="ttf-pb-undo" title="{{ translate('Undo') }}"><i class="las la-undo"></i></button>
        <button type="button" class="ttf-pb-redo-btn" id="ttf-pb-redo" title="{{ translate('Redo') }}"><i class="las la-redo"></i></button>

        <div class="ttf-pb-device-btns">
            <button type="button" class="ttf-pb-device-btn is-active" data-device="desktop" title="{{ translate('Desktop') }}"><i class="las la-desktop"></i></button>
            <button type="button" class="ttf-pb-device-btn" data-device="tablet" title="{{ translate('Tablet') }}"><i class="las la-tablet"></i></button>
            <button type="button" class="ttf-pb-device-btn" data-device="mobile" title="{{ translate('Mobile') }}"><i class="las la-mobile"></i></button>
        </div>

        <span class="ttf-pb-autosave" id="ttf-pb-save-status">
            <i class="las la-check-circle"></i> {{ translate('All changes saved') }}
        </span>
    </div>

    <div class="ttf-pb-header__right">
        @if ($isEdit && isset($page))
            <a href="{{ route('custom-pages.show_custom_page', $page->slug) }}" target="_blank"
               class="ttf-pb-btn ttf-pb-btn--ghost">
                <i class="las la-eye"></i> {{ translate('Preview') }}
            </a>
        @endif
        <button type="button" class="ttf-pb-btn ttf-pb-btn--icon" id="ttf-pb-left-toggle"
                title="{{ translate('Toggle Elements Panel') }}">
            <i class="las la-bars"></i>
        </button>
        <button type="button" class="ttf-pb-btn ttf-pb-btn--primary" id="ttf-pb-save-btn">
            {{ $isEdit ? translate('Update Page') : translate('Save Page') }}
        </button>
        <button type="button" class="ttf-pb-btn ttf-pb-btn--icon" id="ttf-pb-settings-toggle"
                title="{{ translate('Page Settings') }}">
            <i class="las la-cog"></i>
        </button>
    </div>
</header>

{{-- ── 3-COLUMN BODY ───────────────────────────────────────────────── --}}
<div class="ttf-pb-body">

    {{-- ── LEFT: ELEMENTS PANEL ───────────────────────────────────── --}}
    <aside class="ttf-pb-left" id="ttf-pb-left">
        <div class="ttf-pb-panel-header">
            <h4>{{ translate('Elements') }}</h4>
            <div class="ttf-pb-tabs">
                <button type="button" class="ttf-pb-tab is-active" data-tab="widgets">{{ translate('Widgets') }}</button>
                <button type="button" class="ttf-pb-tab" data-tab="navigator">{{ translate('Navigator') }}</button>
            </div>
        </div>

        <div class="ttf-pb-search">
            <div class="ttf-pb-search-wrap">
                <i class="las la-search"></i>
                <input type="text" id="ttf-pb-widget-search" placeholder="{{ translate('Search...') }}">
            </div>
        </div>

        <div class="ttf-pb-tab-content">
            {{-- Widgets tab --}}
            <div class="ttf-pb-tab-pane is-active" id="ttf-pb-tab-widgets">
                <p style="font-size:11px;color:#94a3b8;margin:0 0 10px;">{{ translate('Drag a widget into any column') }}</p>
                <div class="ttf-pb-widget-grid">
                    @foreach ($widgetLibrary as $wKey => $wMeta)
                        <div class="ttf-pb-widget-tile" draggable="true" data-sidebar-widget="{{ $wKey }}">
                            <span class="ttf-pb-widget-tile__icon"><i class="{{ $wMeta['icon'] }}"></i></span>
                            <span class="ttf-pb-widget-tile__name">{{ $wMeta['label'] }}</span>
                            <span class="ttf-pb-widget-tile__desc">{{ $wMeta['desc'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- Navigator tab --}}
            <div class="ttf-pb-tab-pane" id="ttf-pb-tab-navigator">
                <div class="ttf-pb-navigator" id="ttf-pb-navigator-tree">
                    <div class="ttf-pb-nav-empty">{{ translate('No sections yet') }}</div>
                </div>
            </div>
        </div>
    </aside>

    {{-- ── CENTER: CANVAS ──────────────────────────────────────────── --}}
    <main class="ttf-pb-canvas" id="ttf-pb-canvas">
        <div class="ttf-pb-canvas-toolbar">
            <div class="ttf-pb-canvas-toolbar__breadcrumb">
                {{ translate('Pages') }} <i class="las la-angle-right" style="margin:0 4px;font-size:10px;"></i>
                <span id="ttf-pb-canvas-title">{{ $pageTitle }}</span>
            </div>
            <div class="ttf-pb-canvas-toolbar__actions">
                <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost" id="ttf-pb-clear-canvas">
                    <i class="las la-broom"></i> {{ translate('Clear Canvas') }}
                </button>
                @if ($isEdit && isset($page))
                    <a href="{{ route('custom-pages.export', $page->id) }}" class="ttf-pb-btn ttf-pb-btn--ghost">
                        <i class="las la-download"></i> {{ translate('Export JSON') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="ttf-pb-canvas-body">
            {{-- Page info strip --}}
            <div class="ttf-pb-page-info">
                <div class="ttf-pb-page-info__fields">
                    <div>
                        <div class="ttf-pb-page-info__title" id="ttf-pb-title-display">{{ $pageTitle }}</div>
                        <div class="ttf-pb-page-info__slug">/{{ $pageSlugDisplay }}</div>
                    </div>
                </div>
                <div class="ttf-pb-page-info__actions">
                    <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost"
                            id="ttf-pb-open-page-settings" title="{{ translate('Page Settings') }}">
                        <i class="las la-cog"></i> {{ translate('Page Settings') }}
                    </button>
                </div>
            </div>

            {{-- Section list --}}
            <div id="ttf-section-groups" data-next-group-index="{{ count($sectionGroups) }}">
                @foreach ($sectionGroups as $groupIndex => $group)
                    @include('backend.website_settings.pages.partials.section_group', [
                        'group'            => $group,
                        'groupIndex'       => $groupIndex,
                        'fontFamilyOptions'=> $fontFamilyOptions,
                    ])
                @endforeach
            </div>

            {{-- Empty state --}}
            <div class="ttf-pb-canvas-empty @if(!empty($sectionGroups)) d-none @endif"
                 data-group-empty-state>
                <h5>{{ translate('Start building your page') }}</h5>
                <p>{{ translate('Drag widgets here or add a section with columns.') }}</p>
                <button type="button" class="ttf-pb-btn" data-add-group
                        style="margin:0 auto;">
                    <i class="las la-plus"></i> {{ translate('Add Section') }}
                </button>
            </div>

            {{-- Add section bar --}}
            <div class="ttf-pb-add-section-bar">
                <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost" data-add-group>
                    <i class="las la-plus"></i> {{ translate('+ Add New Section') }}
                </button>
            </div>

            {{-- Group template --}}
            <template data-group-template>
                @include('backend.website_settings.pages.partials.section_group', [
                    'group'            => \App\Support\CustomPageTemplate::sectionGroupTemplate(),
                    'groupIndex'       => '__GROUP_INDEX__',
                    'fontFamilyOptions'=> $fontFamilyOptions,
                ])
            </template>
        </div>
    </main>

    {{-- ── RIGHT: SETTINGS PANEL ───────────────────────────────────── --}}
    <aside class="ttf-pb-right" id="ttf-pb-right">
        <div class="ttf-pb-right__header">
            <h5 id="ttf-pb-right-title">{{ translate('Settings') }}</h5>
        </div>

        {{-- Context bar (shown when editing section/widget) --}}
        <div class="ttf-pb-right__context d-none" id="ttf-pb-right-context">
            <span class="ttf-pb-right__context-label" id="ttf-pb-right-context-label"></span>
            <button type="button" class="ttf-pb-right__context-back" id="ttf-pb-right-back">
                <i class="las la-arrow-left"></i> {{ translate('Back') }}
            </button>
        </div>

        <div class="ttf-pb-right__body" id="ttf-pb-right-body">
            {{-- Portal target (section/widget settings moved here on edit) --}}
            <div id="ttf-pb-portal-target" class="d-none"></div>

            {{-- Default: Page-level settings --}}
            <div id="ttf-pb-page-settings">
                {{-- Publish --}}
                <div class="ttf-pb-publish-block">
                    <button type="submit" class="ttf-pb-btn ttf-pb-btn--primary">
                        {{ $isEdit ? translate('Update Page') : translate('Save Page') }}
                    </button>
                </div>

                {{-- Page title/slug accordion --}}
                <details class="ttf-pb-accordion" open>
                    <summary class="ttf-pb-accordion__summary">{{ translate('Page Info') }}</summary>
                    <div class="ttf-pb-accordion__body">
                        <div class="form-group">
                            <label>{{ translate('Title') }} <span class="text-danger">*</span></label>
                            <input id="ttf-page-title" type="text" class="form-control"
                                   name="title" value="{{ $titleValue }}"
                                   placeholder="{{ translate('Page Title') }}" required>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Slug') }} <span class="text-danger">*</span></label>
                            
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="font-size:11px;white-space:nowrap;">{{ route('home') }}/</span>
                                    </div>
                                    <input type="text" class="form-control" name="slug"
                                           value="{{ $slugValue }}" data-page-slug-input
                                           placeholder="{{ translate('page-slug') }}" required>
                                </div>
                                <div style="margin-top:6px;">
                                    <label class="ttf-pb-toggle-opt">
                                        <input type="checkbox" value="1" data-page-slug-autofill
                                               @checked(!$isEdit || $slugValue === '')>
                                        <span>{{ translate('Auto fill from title') }}</span>
                                    </label>
                                </div>
                            
                                
                            
                        </div>
                        @if ($isEdit && isset($page))
                            <div class="form-group" style="margin-bottom:0">
                                <label>{{ translate('Language') }}</label>
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    @foreach (get_all_active_language() as $language)
                                        <a href="{{ route('custom-pages.edit', ['id'=>$page->slug,'lang'=>$language->code]) }}"
                                           class="ttf-pb-btn {{ $language->code == $currentLang ? 'ttf-pb-btn--primary' : 'ttf-pb-btn--ghost' }}"
                                           style="padding:3px 10px;font-size:11px;">
                                            {{ $language->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </details>

                {{-- Banner --}}
                <details class="ttf-pb-accordion">
                    <summary class="ttf-pb-accordion__summary">{{ translate('Banner') }}</summary>
                    <div class="ttf-pb-accordion__body">
                        <div class="form-group">
                            <label>{{ translate('Banner Title') }}</label>
                            <input type="text" class="form-control" name="builder[banner][title]"
                                   value="{{ $banner['title'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Breadcrumb Label') }}</label>
                            <input type="text" class="form-control" name="builder[banner][breadcrumb_label]"
                                   value="{{ $banner['breadcrumb_label'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Subtitle') }}</label>
                            <input type="text" class="form-control" name="builder[banner][subtitle]"
                                   value="{{ $banner['subtitle'] ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Background Image') }}</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="builder[banner][background_image]"
                                       class="selected-files" value="{{ $banner['background_image'] ?? '' }}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Height (px)') }}</label>
                                <input type="number" class="form-control" name="builder[banner][height]"
                                       value="{{ $banner['height'] ?? '340' }}" min="120" max="800">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Text Align') }}</label>
                                <select class="form-control" name="builder[banner][text_align]">
                                    <option value="left"   @selected(($banner['text_align']??'center')==='left')>{{ translate('Left') }}</option>
                                    <option value="center" @selected(($banner['text_align']??'center')==='center')>{{ translate('Center') }}</option>
                                    <option value="right"  @selected(($banner['text_align']??'')==='right')>{{ translate('Right') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Overlay Color') }}</label>
                            <input type="text" class="form-control" name="builder[banner][overlay_color]"
                                   value="{{ $banner['overlay_color'] ?? 'rgba(0,0,0,0.4)' }}"
                                   placeholder="rgba(0,0,0,0.4)">
                        </div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Title Color') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($banner['title_color'] ?? null, '#ffffff') }}">
                                    <input type="text" class="form-control" name="builder[banner][title_color]"
                                           value="{{ $banner['title_color'] ?? '#ffffff' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Subtitle Color') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($banner['subtitle_color'] ?? null, '#f0f0f0') }}">
                                    <input type="text" class="form-control" name="builder[banner][subtitle_color]"
                                           value="{{ $banner['subtitle_color'] ?? '#f0f0f0' }}">
                                </div>
                            </div>
                        </div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Title Font') }}</label>
                                <select class="form-control aiz-selectpicker" data-container="body"
                                        name="builder[banner][title_font_family]">
                                    @foreach ($fontFamilyOptions as $fv => $fl)
                                        <option value="{{ $fv }}" @selected(($banner['title_font_family']??'')===$fv)>{{ $fl }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Subtitle Font') }}</label>
                                <select class="form-control aiz-selectpicker" data-container="body"
                                        name="builder[banner][subtitle_font_family]">
                                    @foreach ($fontFamilyOptions as $fv => $fl)
                                        <option value="{{ $fv }}" @selected(($banner['subtitle_font_family']??'')===$fv)>{{ $fl }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </details>

                {{-- Typography & Colors --}}
                <details class="ttf-pb-accordion">
                    <summary class="ttf-pb-accordion__summary">{{ translate('Typography & Colors') }}</summary>
                    <div class="ttf-pb-accordion__body">
                        <div class="form-group">
                            <label>{{ translate('Heading Font') }}</label>
                            <select class="form-control aiz-selectpicker" data-container="body" name="builder[styles][heading_font_family]">
                                @foreach ($fontFamilyOptions as $fv => $fl)
                                    <option value="{{ $fv }}" @selected(($styles['heading_font_family']??'')===$fv)>{{ $fl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Subheading Font') }}</label>
                            <select class="form-control aiz-selectpicker" data-container="body" name="builder[styles][subheading_font_family]">
                                @foreach ($fontFamilyOptions as $fv => $fl)
                                    <option value="{{ $fv }}" @selected(($styles['subheading_font_family']??'')===$fv)>{{ $fl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Paragraph Font') }}</label>
                            <select class="form-control aiz-selectpicker" data-container="body" name="builder[styles][paragraph_font_family]">
                                @foreach ($fontFamilyOptions as $fv => $fl)
                                    <option value="{{ $fv }}" @selected(($styles['paragraph_font_family']??'')===$fv)>{{ $fl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Heading Weight') }}</label>
                                <select class="form-control" name="builder[styles][heading_font_weight]">
                                    @foreach(['300','400','500','600','700','800','900'] as $w)
                                        <option value="{{ $w }}" @selected(($styles['heading_font_weight']??'700')===$w)>{{ $w }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Body Weight') }}</label>
                                <select class="form-control" name="builder[styles][body_font_weight]">
                                    @foreach(['300','400','500','600','700'] as $w)
                                        <option value="{{ $w }}" @selected(($styles['body_font_weight']??'400')===$w)>{{ $w }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Container Width') }}</label>
                                <input type="number" class="form-control" name="builder[styles][container_width]"
                                       value="{{ $styles['container_width'] ?? '1440' }}" min="960" max="1920">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Section Spacing') }}</label>
                                <input type="number" class="form-control" name="builder[styles][section_spacing]"
                                       value="{{ $styles['section_spacing'] ?? '54' }}" min="0" max="200">
                            </div>
                        </div>
                        <div class="ttf-pb-divider"></div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Page BG') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['page_background'] ?? null, '#faf8f5') }}">
                                    <input type="text" class="form-control" name="builder[styles][page_background]" value="{{ $styles['page_background'] ?? '#FAF8F5' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Content BG') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['content_background'] ?? null, '#ffffff') }}">
                                    <input type="text" class="form-control" name="builder[styles][content_background]" value="{{ $styles['content_background'] ?? '#ffffff' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Accent') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['accent_color'] ?? null, $themeAccent) }}">
                                    <input type="text" class="form-control" name="builder[styles][accent_color]" value="{{ $styles['accent_color'] ?? $themeAccent }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Heading Color') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['heading_color'] ?? null, '#2c2218') }}">
                                    <input type="text" class="form-control" name="builder[styles][heading_color]" value="{{ $styles['heading_color'] ?? '#2c2218' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Sub Heading') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['subheading_color'] ?? null, '#5b4839') }}">
                                    <input type="text" class="form-control" name="builder[styles][subheading_color]" value="{{ $styles['subheading_color'] ?? '#5b4839' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Body Text') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['paragraph_color'] ?? null, '#393939') }}">
                                    <input type="text" class="form-control" name="builder[styles][paragraph_color]" value="{{ $styles['paragraph_color'] ?? '#393939' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Muted') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['muted_color'] ?? null, '#8a786a') }}">
                                    <input type="text" class="form-control" name="builder[styles][muted_color]" value="{{ $styles['muted_color'] ?? '#8a786a' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Card BG') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($styles['card_background'] ?? null, '#fffdf9') }}">
                                    <input type="text" class="form-control" name="builder[styles][card_background]" value="{{ $styles['card_background'] ?? '#fffdf9' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </details>

                {{-- SEO --}}
                <details class="ttf-pb-accordion">
                    <summary class="ttf-pb-accordion__summary">{{ translate('SEO') }}</summary>
                    <div class="ttf-pb-accordion__body">
                        <div class="form-group">
                            <label>{{ translate('Meta Title') }}</label>
                            <input type="text" class="form-control" name="meta_title"
                                   value="{{ $isEdit && isset($page) ? $page->meta_title : '' }}">
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Meta Description') }}</label>
                            <textarea class="form-control" name="meta_description"
                                      rows="3">{!! $isEdit && isset($page) ? $page->meta_description : '' !!}</textarea>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Keywords') }}</label>
                            <textarea class="form-control" name="keywords"
                                      rows="2">{!! $isEdit && isset($page) ? $page->keywords : '' !!}</textarea>
                            <small style="color:#94a3b8;font-size:10px;">{{ translate('Separate with comma') }}</small>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label>{{ translate('Meta Image') }}</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="meta_image" class="selected-files" value="{{ $metaImageValue }}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>
                </details>
            </div>{{-- /ttf-pb-page-settings --}}
        </div>{{-- /ttf-pb-right__body --}}
    </aside>{{-- /ttf-pb-right --}}

</div>{{-- /ttf-pb-body --}}
</form>

{{-- ── COLUMN CHOOSER MODAL ────────────────────────────────────── --}}
<div class="ttf-pb-modal-overlay is-hidden" id="ttf-pb-col-modal">
    <div class="ttf-pb-modal">
        <div class="ttf-pb-modal__header">
            <span class="ttf-pb-modal__title">{{ translate('Choose Section Structure') }}</span>
            <button type="button" class="ttf-pb-modal__close" id="ttf-pb-col-modal-close">
                <i class="las la-times"></i>
            </button>
        </div>
        <div class="ttf-pb-modal__body">
            <p>{{ translate('Select the number of columns. You can change the widths later.') }}</p>
            <div class="ttf-pb-col-options">
                <div class="ttf-pb-col-option is-selected" data-cols="1">
                    <div class="ttf-pb-col-option__vis"><span style="flex:1"></span></div>
                    <div class="ttf-pb-col-option__label">{{ translate('1 Column') }}</div>
                </div>
                <div class="ttf-pb-col-option" data-cols="2">
                    <div class="ttf-pb-col-option__vis"><span style="flex:1"></span><span style="flex:1"></span></div>
                    <div class="ttf-pb-col-option__label">{{ translate('2 Columns') }}</div>
                </div>
                <div class="ttf-pb-col-option" data-cols="3">
                    <div class="ttf-pb-col-option__vis"><span style="flex:1"></span><span style="flex:1"></span><span style="flex:1"></span></div>
                    <div class="ttf-pb-col-option__label">{{ translate('3 Columns') }}</div>
                </div>
            </div>
            <div style="text-align:right;">
                <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost" id="ttf-pb-col-modal-cancel">{{ translate('Cancel') }}</button>
                <button type="button" class="ttf-pb-btn ttf-pb-btn--primary" id="ttf-pb-col-modal-confirm">{{ translate('Done') }}</button>
            </div>
        </div>
    </div>
</div>

</div>{{-- /ttf-pb-root --}}
