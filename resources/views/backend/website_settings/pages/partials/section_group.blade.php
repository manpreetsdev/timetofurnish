@php
    $group = array_merge(\App\Support\CustomPageTemplate::sectionGroupTemplate(), $group);
    $groupName = trim((string) ($group['name'] ?? ''));
    $widgets = $group['widgets'] ?? [];
    $widgetLibrary = [
        'rich_text' => [
            'label' => translate('Text Editor'),
            'icon' => 'las la-align-left',
            'description' => translate('Rich text, policies and long copy'),
        ],
        'split' => [
            'label' => translate('Two Column'),
            'icon' => 'las la-columns',
            'description' => translate('Image + content with checklist'),
        ],
        'full_width' => [
            'label' => translate('Full Width'),
            'icon' => 'las la-window-maximize',
            'description' => translate('Wide content block with image order control'),
        ],
        'image_grid' => [
            'label' => translate('Grid Cards'),
            'icon' => 'las la-th-large',
            'description' => translate('Repeatable cards with image and content'),
        ],
        'full_image' => [
            'label' => translate('Image Showcase'),
            'icon' => 'las la-image',
            'description' => translate('Large visual section with text support'),
        ],
        'toc_content' => [
            'label' => translate('TOC + Content'),
            'icon' => 'las la-list-alt',
            'description' => translate('Privacy policy sidebar with linked content'),
        ],
    ];
    $visibleOn = collect([
        ($group['show_on_desktop'] ?? '1') === '1' ? translate('Desktop') : null,
        ($group['show_on_ipad_pro'] ?? '1') === '1' ? translate('iPad Pro') : null,
        ($group['show_on_ipad'] ?? '1') === '1' ? translate('iPad') : null,
        ($group['show_on_phone'] ?? '1') === '1' ? translate('Phone') : null,
    ])->filter()->values();
    $visibilitySummary = $visibleOn->count() === 4
        ? translate('All Devices')
        : ($visibleOn->isEmpty() ? translate('Hidden Everywhere') : $visibleOn->implode(', '));
@endphp

<div class="ttf-group-card card mb-4" data-group-card data-group-index="{{ $groupIndex }}" draggable="false">
    <input type="hidden" name="builder[sections][{{ $groupIndex }}][type]" value="section_group">

    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-start gap-3">
            <button type="button" class="ttf-drag-handle" data-group-drag-handle aria-label="{{ translate('Drag to reorder section') }}">
                <i class="las la-grip-vertical"></i>
            </button>
            <div class="ttf-section-headline">
                <div class="ttf-section-meta">
                    <span class="ttf-section-chip">{{ translate('Section') }}</span>
                    <span class="ttf-section-chip ttf-section-chip--muted" data-group-visibility-summary>{{ $visibilitySummary }}</span>
                    <span class="ttf-section-chip ttf-section-chip--muted" data-group-widget-count>{{ count($widgets) }} {{ translate('Widgets') }}</span>
                </div>
                <h6 class="mb-1" data-group-label>{{ $groupName !== '' ? $groupName : translate('Untitled Section') }}</h6>
                <small class="text-muted d-block">{{ translate('Widgets inside this section share the same container and visibility settings.') }}</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-soft-primary" data-toggle-group data-label-open="{{ translate('Edit Section') }}" data-label-close="{{ translate('Hide Section') }}">
                {{ translate('Edit Section') }}
            </button>
            <button type="button" class="btn btn-sm btn-soft-danger" data-remove-group>
                <i class="las la-trash"></i>
                {{ translate('Remove') }}
            </button>
        </div>
    </div>

    <div class="card-body d-none" data-group-body>
        <div class="ttf-group-editor">
            <div class="ttf-group-editor__main">
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header">
                        <h6 class="mb-1">{{ translate('Section Info') }}</h6>
                        <small class="text-muted">{{ translate('Use sections as clear layout containers. Add one or more widgets inside each section.') }}</small>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Section Name') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][name]" value="{{ $group['name'] ?? '' }}" data-group-name-input placeholder="{{ translate('About Intro Section') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Widget Gap') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][section_gap]" value="{{ $group['section_gap'] ?? '18' }}" min="0" max="80">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ttf-section-block">
                    <div class="ttf-section-block__header ttf-section-block__header--inline">
                        <div>
                            <h6 class="mb-1">{{ translate('Section Widgets') }}</h6>
                            <small class="text-muted">{{ translate('Add widgets inside this section. You can mix text, images, policy TOC and grid widgets freely.') }}</small>
                        </div>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="ttf-widget-library ttf-widget-library--compact">
                            @foreach ($widgetLibrary as $widgetKey => $widgetMeta)
                                <button type="button" class="ttf-widget-card" data-add-widget="{{ $widgetKey }}">
                                    <span class="ttf-widget-card__icon"><i class="{{ $widgetMeta['icon'] }}"></i></span>
                                    <span class="ttf-widget-card__title">{{ $widgetMeta['label'] }}</span>
                                    <span class="ttf-widget-card__text">{{ $widgetMeta['description'] }}</span>
                                </button>
                            @endforeach
                        </div>

                        <div class="ttf-widget-list" data-widget-container data-next-widget-index="{{ count($widgets) }}">
                            @foreach ($widgets as $widgetIndex => $widget)
                                @include('backend.website_settings.pages.partials.structured_section', [
                                    'widget' => $widget,
                                    'groupIndex' => $groupIndex,
                                    'widgetIndex' => $widgetIndex,
                                    'fontFamilyOptions' => $fontFamilyOptions,
                                ])
                            @endforeach
                        </div>

                        <div class="ttf-sections-empty-state @if(!empty($widgets)) d-none @endif" data-widget-empty-state>
                            <h6 class="mb-1">{{ translate('No Widgets In This Section') }}</h6>
                            <p class="mb-0 text-muted">{{ translate('Choose a widget above to start building this section.') }}</p>
                        </div>

                        @foreach (array_keys(\App\Support\CustomPageTemplate::structuredSectionTemplates()) as $widgetKey)
                            <template data-widget-template="{{ $widgetKey }}">
                                @include('backend.website_settings.pages.partials.structured_section', [
                                    'widget' => \App\Support\CustomPageTemplate::structuredSectionTemplates()[$widgetKey],
                                    'groupIndex' => $groupIndex,
                                    'widgetIndex' => '__WIDGET_INDEX__',
                                    'fontFamilyOptions' => $fontFamilyOptions,
                                ])
                            </template>
                        @endforeach
                    </div>
                </div>
            </div>

            <aside class="ttf-group-editor__aside">
                <div class="ttf-section-settings">
                    <details class="ttf-setting-group" open>
                        <summary>{{ translate('Section Frame') }}</summary>
                        <div class="ttf-setting-group__body">
                            <div class="ttf-toggle-list">
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][show_background]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][show_background]" value="1" data-style-toggle="background" @checked(($group['show_background'] ?? '0') === '1')>
                                    <span>{{ translate('Use section background') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][show_border]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][show_border]" value="1" data-style-toggle="border" @checked(($group['show_border'] ?? '0') === '1')>
                                    <span>{{ translate('Show section border') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][use_padding]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][use_padding]" value="1" data-style-toggle="padding" @checked(($group['use_padding'] ?? '0') === '1')>
                                    <span>{{ translate('Use section inner spacing') }}</span>
                                </label>
                            </div>

                            <div class="row mt-3 {{ ($group['show_background'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="background">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Background Color') }}</label>
                                        <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][background_color]" value="{{ $group['background_color'] ?? '#fffdf9' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 {{ (($group['show_background'] ?? '0') === '1' || ($group['show_border'] ?? '0') === '1') ? '' : 'd-none' }}" data-style-target="radius">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Corner Radius') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][border_radius]" value="{{ $group['border_radius'] ?? '24' }}" min="0" max="60">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 {{ ($group['show_border'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="border">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Border Color') }}</label>
                                        <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][border_color]" value="{{ $group['border_color'] ?? '#e3d6ca' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 {{ ($group['use_padding'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="padding">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Section Padding') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][section_padding]" value="{{ $group['section_padding'] ?? '28' }}" min="0" max="80">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>

                    <details class="ttf-setting-group">
                        <summary>{{ translate('Section Visibility') }}</summary>
                        <div class="ttf-setting-group__body">
                            <div class="ttf-toggle-list">
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][show_on_desktop]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][show_on_desktop]" value="1" data-visibility-toggle="desktop" @checked(($group['show_on_desktop'] ?? '1') === '1')>
                                    <span>{{ translate('Desktop') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][show_on_ipad_pro]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][show_on_ipad_pro]" value="1" data-visibility-toggle="ipad_pro" @checked(($group['show_on_ipad_pro'] ?? '1') === '1')>
                                    <span>{{ translate('iPad Pro') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][show_on_ipad]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][show_on_ipad]" value="1" data-visibility-toggle="ipad" @checked(($group['show_on_ipad'] ?? '1') === '1')>
                                    <span>{{ translate('iPad') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][show_on_phone]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][show_on_phone]" value="1" data-visibility-toggle="phone" @checked(($group['show_on_phone'] ?? '1') === '1')>
                                    <span>{{ translate('Phone') }}</span>
                                </label>
                            </div>
                        </div>
                    </details>
                </div>
            </aside>
        </div>
    </div>
</div>
