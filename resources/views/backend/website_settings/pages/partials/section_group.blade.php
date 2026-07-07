@php
    $group = array_merge(\App\Support\CustomPageTemplate::sectionGroupTemplate(), $group);
    $groupName = trim((string) ($group['name'] ?? ''));
    $widgets = $group['widgets'] ?? [];
    $visibleOn = collect([
        ($group['show_on_desktop'] ?? '1') === '1' ? translate('Desktop') : null,
        ($group['show_on_ipad_pro'] ?? '1') === '1' ? translate('iPad Pro') : null,
        ($group['show_on_ipad'] ?? '1') === '1' ? translate('iPad') : null,
        ($group['show_on_phone'] ?? '1') === '1' ? translate('Phone') : null,
    ])->filter()->values();
    $visibilitySummary = $visibleOn->count() === 4
        ? translate('All Devices')
        : ($visibleOn->isEmpty() ? translate('Hidden Everywhere') : $visibleOn->implode(', '));

    $columnsCount = max(1, (int) ($group['columns'] ?? 1));
    $columnsWidgets = [];
    for ($i = 0; $i < $columnsCount; $i++) {
        $columnsWidgets[$i] = [];
    }
    foreach ($widgets as $widgetIndex => $widget) {
        $colIdx = max(0, min($columnsCount - 1, (int) ($widget['column_index'] ?? 0)));
        $columnsWidgets[$colIdx][] = [
            'index' => $widgetIndex,
            'data' => $widget,
        ];
    }
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
                <small class="text-muted d-block">{{ translate('Keep the canvas simple. Use the settings icon to edit section columns, gap, spacing, and visibility.') }}</small>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-move-group-up title="{{ translate('Move Up') }}">
                <i class="las la-arrow-up"></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-move-group-down title="{{ translate('Move Down') }}">
                <i class="las la-arrow-down"></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-copy-group title="{{ translate('Copy Section') }}">
                <i class="las la-copy"></i>
            </button>
            <button type="button" class="btn btn-sm btn-soft-primary" data-toggle-group data-label-open="{{ translate('Expand') }}" data-label-close="{{ translate('Collapse') }}">
                {{ translate('Expand') }}
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-edit-section-settings title="{{ translate('Section Settings') }}">
                <i class="las la-cog"></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-soft-danger" data-remove-group title="{{ translate('Remove Section') }}">
                <i class="las la-trash"></i>
            </button>
        </div>
    </div>

    <div class="card-body d-none" data-group-body>
        <div class="ttf-group-canvas">
            <div class="ttf-group-canvas__header">
                <span>{{ translate('Section Canvas') }}</span>
                <small>{{ translate('Drag widgets into any column. Use arrows or drag handles to reorder.') }}</small>
            </div>

            <div class="ttf-admin-columns-grid ttf-columns-count-{{ $columnsCount }}" data-columns-grid>
                @for ($col = 0; $col < $columnsCount; $col++)
                    <div class="ttf-admin-column" data-admin-column="{{ $col }}">
                        <div class="ttf-admin-column-header">
                            <span>{{ translate('Column') }} {{ $col + 1 }}</span>
                        </div>

                        <div class="ttf-widget-list" data-widget-container data-column-index="{{ $col }}">
                            @foreach ($columnsWidgets[$col] as $item)
                                @include('backend.website_settings.pages.partials.structured_section', [
                                    'widget' => $item['data'],
                                    'groupIndex' => $groupIndex,
                                    'widgetIndex' => $item['index'],
                                    'fontFamilyOptions' => $fontFamilyOptions,
                                ])
                            @endforeach
                        </div>

                        <div class="ttf-sections-empty-state @if(!empty($columnsWidgets[$col])) d-none @endif" data-widget-empty-state>
                            <small class="text-muted">{{ translate('Empty column. Drag or add a widget here.') }}</small>
                        </div>
                    </div>
                @endfor
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

        <div class="d-none" data-section-settings-portal>
            <div class="ttf-section-settings">
                <details class="ttf-setting-group" open>
                    <summary>{{ translate('Section Basics') }}</summary>
                    <div class="ttf-setting-group__body">
                        <div class="form-group">
                            <label>{{ translate('Section Name') }}</label>
                            <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][name]" value="{{ $group['name'] ?? '' }}" data-group-name-input placeholder="{{ translate('About Intro Section') }}">
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Grid Columns') }}</label>
                                    <select class="form-control aiz-selectpicker" data-container="body" name="builder[sections][{{ $groupIndex }}][columns]" data-group-columns-select>
                                        <option value="1" @selected($columnsCount === 1)>{{ translate('1 Column') }}</option>
                                        <option value="2" @selected($columnsCount === 2)>{{ translate('2 Columns') }}</option>
                                        <option value="3" @selected($columnsCount === 3)>{{ translate('3 Columns') }}</option>
                                        <option value="4" @selected($columnsCount === 4)>{{ translate('4 Columns') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Widget Gap') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][section_gap]" value="{{ $group['section_gap'] ?? '18' }}" min="0" max="80">
                                </div>
                            </div>
                        </div>
                    </div>
                </details>

                <details class="ttf-setting-group">
                    <summary>{{ translate('Layout & Spacing') }}</summary>
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
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Border Color') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][border_color]" value="{{ $group['border_color'] ?? '#21252933' }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Border Width') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][border_width]" value="{{ $group['border_width'] ?? '' }}" min="0" max="10" placeholder="1">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Border Style') }}</label>
                                    <select class="form-control" name="builder[sections][{{ $groupIndex }}][border_style]">
                                        <option value="solid" @selected(($group['border_style'] ?? 'solid') === 'solid')>{{ translate('Solid') }}</option>
                                        <option value="dashed" @selected(($group['border_style'] ?? '') === 'dashed')>{{ translate('Dashed') }}</option>
                                        <option value="dotted" @selected(($group['border_style'] ?? '') === 'dotted')>{{ translate('Dotted') }}</option>
                                        <option value="double" @selected(($group['border_style'] ?? '') === 'double')>{{ translate('Double') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3 {{ ($group['use_padding'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="padding">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ translate('Overall Padding') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][section_padding]" value="{{ $group['section_padding'] ?? '28' }}" min="0" max="80">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Padding Top') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][padding_top]" value="{{ $group['padding_top'] ?? '' }}" min="0" max="120" placeholder="28">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Padding Bottom') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][padding_bottom]" value="{{ $group['padding_bottom'] ?? '' }}" min="0" max="120" placeholder="28">
                                </div>
                            </div>
                        </div>
                    </div>
                </details>

                <details class="ttf-setting-group">
                    <summary>{{ translate('Visibility') }}</summary>
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
        </div>
    </div>
</div>
