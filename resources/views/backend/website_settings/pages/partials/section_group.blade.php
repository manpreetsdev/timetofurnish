@php
    $group       = array_merge(\App\Support\CustomPageTemplate::sectionGroupTemplate(), $group);
    $groupName   = trim((string) ($group['name'] ?? ''));
    $widgets     = $group['widgets'] ?? [];
    $colCount    = max(1, (int) ($group['columns'] ?? 1));
    $gi          = $groupIndex;

    $colWidgets  = array_fill(0, $colCount, []);
    foreach ($widgets as $wi => $w) {
        $ci = max(0, min($colCount - 1, (int) ($w['column_index'] ?? 0)));
        $colWidgets[$ci][] = ['index' => $wi, 'data' => $w];
    }

    $visibleOn = collect([
        ($group['show_on_desktop']  ?? '1') === '1' ? 'Desktop'  : null,
        ($group['show_on_ipad_pro'] ?? '1') === '1' ? 'iPad Pro' : null,
        ($group['show_on_ipad']     ?? '1') === '1' ? 'iPad'     : null,
        ($group['show_on_phone']    ?? '1') === '1' ? 'Phone'    : null,
    ])->filter()->values();
    $visSummary = $visibleOn->count() === 4 ? translate('All Devices')
        : ($visibleOn->isEmpty() ? translate('Hidden') : $visibleOn->implode(', '));
    $pickerColor = static fn ($value, $fallback = '#ffffff') => \App\Support\CustomPageTemplate::colorPickerValue($value, $fallback);
@endphp

<div class="ttf-pb-section" data-group-card data-group-index="{{ $gi }}" draggable="false">
    <input type="hidden" name="builder[sections][{{ $gi }}][type]" value="section_group">

    {{-- Section header --}}
    <div class="ttf-pb-section__header" data-toggle-group>
        <button type="button" class="ttf-pb-icon-btn" data-toggle-group data-toggle-group-icon title="{{ translate('Expand / Collapse') }}">
            <i class="las la-angle-right"></i>
        </button>
        <span class="ttf-pb-drag-handle" data-group-drag-handle><i class="las la-grip-vertical"></i></span>
        <div class="ttf-pb-section__info">
            <div class="ttf-pb-section__name" data-group-label>
                {{ $groupName !== '' ? $groupName : translate('Untitled Section') }}
            </div>
            <div class="ttf-pb-section__meta">
                <span class="ttf-pb-badge">{{ translate('Section') }}</span>
                <span class="ttf-pb-badge ttf-pb-badge--muted" data-group-visibility-summary>{{ $visSummary }}</span>
                <span class="ttf-pb-badge ttf-pb-badge--muted" data-group-widget-count>{{ count($widgets) }} {{ translate('Widgets') }}</span>
            </div>
        </div>
        <div class="ttf-pb-section__actions">
            <button type="button" class="ttf-pb-icon-btn" data-move-group-up   title="{{ translate('Up') }}"><i class="las la-arrow-up"></i></button>
            <button type="button" class="ttf-pb-icon-btn" data-move-group-down title="{{ translate('Down') }}"><i class="las la-arrow-down"></i></button>
            <button type="button" class="ttf-pb-icon-btn" data-copy-group      title="{{ translate('Duplicate') }}"><i class="las la-copy"></i></button>
            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--primary" data-edit-section-settings title="{{ translate('Settings') }}"><i class="las la-sliders-h"></i></button>
            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-group title="{{ translate('Delete') }}"><i class="las la-trash"></i></button>
        </div>
    </div>

    {{-- Section body (columns + widget drop zones) --}}
    <div class="ttf-pb-section__body" data-group-body>
        <div class="ttf-pb-columns ttf-pb-columns--{{ $colCount }}" data-columns-grid>
            @for ($col = 0; $col < $colCount; $col++)
                <div class="ttf-pb-column" data-admin-column="{{ $col }}">
                    <div class="ttf-pb-column__label">{{ translate('Column') }} {{ $col + 1 }}</div>
                    <div class="ttf-pb-column__drop" data-widget-container data-column-index="{{ $col }}">
                        @foreach ($colWidgets[$col] as $item)
                            @include('backend.website_settings.pages.partials.structured_section', [
                                'widget'            => $item['data'],
                                'groupIndex'        => $gi,
                                'widgetIndex'       => $item['index'],
                                'fontFamilyOptions' => $fontFamilyOptions,
                            ])
                        @endforeach
                    </div>
                    @if (empty($colWidgets[$col]))
                        <div class="ttf-pb-column__empty" data-widget-empty-state>
                            {{ translate('Empty column. Drag or add a widget here.') }}
                        </div>
                    @else
                        <div class="ttf-pb-column__empty d-none" data-widget-empty-state></div>
                    @endif
                </div>
            @endfor
        </div>

        {{-- Widget templates (one per type) --}}
        @foreach (array_keys(\App\Support\CustomPageTemplate::structuredSectionTemplates()) as $wKey)
            <template data-widget-template="{{ $wKey }}">
                @include('backend.website_settings.pages.partials.structured_section', [
                    'widget'            => \App\Support\CustomPageTemplate::structuredSectionTemplates()[$wKey],
                    'groupIndex'        => $gi,
                    'widgetIndex'       => '__WIDGET_INDEX__',
                    'fontFamilyOptions' => $fontFamilyOptions,
                ])
            </template>
        @endforeach
    </div>

    {{-- Settings portal (hidden; moved into right panel on gear click) --}}
    <div class="d-none" data-section-settings-portal>
        <div class="ttf-pb-section-settings-content">

            {{-- Basics --}}
            <details class="ttf-pb-accordion" open>
                <summary class="ttf-pb-accordion__summary">{{ translate('Section Basics') }}</summary>
                <div class="ttf-pb-accordion__body">
                    <div class="form-group">
                        <label>{{ translate('Section Name') }}</label>
                        <input type="text" class="form-control"
                               name="builder[sections][{{ $gi }}][name]"
                               value="{{ $group['name'] ?? '' }}"
                               data-group-name-input
                               placeholder="{{ translate('e.g. Hero Section') }}">
                    </div>
                    <div class="ttf-pb-row-2">
                        <div class="form-group">
                            <label>{{ translate('Columns') }}</label>
                            <select class="form-control" name="builder[sections][{{ $gi }}][columns]"
                                    data-group-columns-select>
                                <option value="1" @selected($colCount===1)>1</option>
                                <option value="2" @selected($colCount===2)>2</option>
                                <option value="3" @selected($colCount===3)>3</option>
                                <option value="4" @selected($colCount===4)>4</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Widget Gap (px)') }}</label>
                            <input type="number" class="form-control"
                                   name="builder[sections][{{ $gi }}][section_gap]"
                                   value="{{ $group['section_gap'] ?? '18' }}" min="0" max="80">
                        </div>
                    </div>
                </div>
            </details>

            {{-- Background --}}
            <details class="ttf-pb-accordion">
                <summary class="ttf-pb-accordion__summary">{{ translate('Background') }}</summary>
                <div class="ttf-pb-accordion__body">
                    <div class="ttf-pb-toggle-list" style="margin-bottom:10px">
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][show_background]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][show_background]"
                                   value="1" data-style-toggle="background"
                                   @checked(($group['show_background']??'0')==='1')>
                            <span>{{ translate('Enable background color') }}</span>
                        </label>
                    </div>
                    <div class="{{ ($group['show_background']??'0')==='1' ? '' : 'd-none' }}" data-style-target="background">
                        <div class="form-group">
                            <label>{{ translate('Background Color') }}</label>
                            <div class="ttf-pb-color-row">
                                <input type="color" value="{{ $pickerColor($group['background_color'] ?? null, '#ffffff') }}"
                                       data-color-sync="builder[sections][{{ $gi }}][background_color]">
                                <input type="text" class="form-control"
                                       name="builder[sections][{{ $gi }}][background_color]"
                                       value="{{ $group['background_color'] ?? '#ffffff' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Corner Radius (px)') }}</label>
                            <input type="number" class="form-control"
                                   name="builder[sections][{{ $gi }}][border_radius]"
                                   value="{{ $group['border_radius'] ?? '0' }}" min="0" max="60">
                        </div>
                    </div>
                </div>
            </details>

            {{-- Border --}}
            <details class="ttf-pb-accordion">
                <summary class="ttf-pb-accordion__summary">{{ translate('Border') }}</summary>
                <div class="ttf-pb-accordion__body">
                    <div class="ttf-pb-toggle-list" style="margin-bottom:10px">
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][show_border]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][show_border]"
                                   value="1" data-style-toggle="border"
                                   @checked(($group['show_border']??'0')==='1')>
                            <span>{{ translate('Enable border') }}</span>
                        </label>
                    </div>
                    <div class="{{ ($group['show_border']??'0')==='1' ? '' : 'd-none' }}" data-style-target="border">
                        <div class="ttf-pb-row-2">
                            <div class="form-group">
                                <label>{{ translate('Color') }}</label>
                                <div class="ttf-pb-color-row">
                                    <input type="color" value="{{ $pickerColor($group['border_color'] ?? null, '#e2e8f0') }}">
                                    <input type="text" class="form-control"
                                           name="builder[sections][{{ $gi }}][border_color]"
                                           value="{{ $group['border_color'] ?? '#e2e8f0' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Width (px)') }}</label>
                                <input type="number" class="form-control"
                                       name="builder[sections][{{ $gi }}][border_width]"
                                       value="{{ $group['border_width'] ?? '1' }}" min="0" max="20">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Style') }}</label>
                            <select class="form-control" name="builder[sections][{{ $gi }}][border_style]">
                                <option value="solid"  @selected(($group['border_style']??'solid')==='solid')>Solid</option>
                                <option value="dashed" @selected(($group['border_style']??'')==='dashed')>Dashed</option>
                                <option value="dotted" @selected(($group['border_style']??'')==='dotted')>Dotted</option>
                            </select>
                        </div>
                    </div>
                </div>
            </details>

            {{-- Spacing --}}
            <details class="ttf-pb-accordion">
                <summary class="ttf-pb-accordion__summary">{{ translate('Spacing (Padding)') }}</summary>
                <div class="ttf-pb-accordion__body">
                    <div class="ttf-pb-toggle-list" style="margin-bottom:10px">
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][use_padding]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][use_padding]"
                                   value="1" data-style-toggle="padding"
                                   @checked(($group['use_padding']??'0')==='1')>
                            <span>{{ translate('Enable custom padding') }}</span>
                        </label>
                    </div>
                    <div class="{{ ($group['use_padding']??'0')==='1' ? '' : 'd-none' }}" data-style-target="padding">
                        <div class="ttf-pb-spacing-grid">
                            <div class="form-group">
                                <label>{{ translate('Top') }}</label>
                                <input type="number" class="form-control"
                                       name="builder[sections][{{ $gi }}][padding_top]"
                                       value="{{ $group['padding_top'] ?? '50' }}" min="0" max="300">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Right') }}</label>
                                <input type="number" class="form-control"
                                       name="builder[sections][{{ $gi }}][padding_right]"
                                       value="{{ $group['padding_right'] ?? ($group['padding_left_right'] ?? '0') }}" min="0" max="300">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Bottom') }}</label>
                                <input type="number" class="form-control"
                                       name="builder[sections][{{ $gi }}][padding_bottom]"
                                       value="{{ $group['padding_bottom'] ?? '50' }}" min="0" max="300">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Left') }}</label>
                                <input type="number" class="form-control"
                                       name="builder[sections][{{ $gi }}][padding_left]"
                                       value="{{ $group['padding_left'] ?? ($group['padding_left_right'] ?? '0') }}" min="0" max="300">
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            {{-- Visibility --}}
            <details class="ttf-pb-accordion">
                <summary class="ttf-pb-accordion__summary">{{ translate('Visibility') }}</summary>
                <div class="ttf-pb-accordion__body">
                    <div class="ttf-pb-toggle-list">
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][show_on_desktop]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][show_on_desktop]"
                                   value="1" data-visibility-toggle="desktop"
                                   @checked(($group['show_on_desktop']??'1')==='1')>
                            <span>{{ translate('Desktop') }}</span>
                        </label>
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][show_on_ipad_pro]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][show_on_ipad_pro]"
                                   value="1" data-visibility-toggle="ipad_pro"
                                   @checked(($group['show_on_ipad_pro']??'1')==='1')>
                            <span>{{ translate('iPad Pro') }}</span>
                        </label>
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][show_on_ipad]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][show_on_ipad]"
                                   value="1" data-visibility-toggle="ipad"
                                   @checked(($group['show_on_ipad']??'1')==='1')>
                            <span>{{ translate('iPad') }}</span>
                        </label>
                        <label class="ttf-pb-toggle-opt">
                            <input type="hidden" name="builder[sections][{{ $gi }}][show_on_phone]" value="0">
                            <input type="checkbox" name="builder[sections][{{ $gi }}][show_on_phone]"
                                   value="1" data-visibility-toggle="phone"
                                   @checked(($group['show_on_phone']??'1')==='1')>
                            <span>{{ translate('Phone') }}</span>
                        </label>
                    </div>
                </div>
            </details>

        </div>{{-- /ttf-pb-section-settings-content --}}
    </div>{{-- /data-section-settings-portal --}}
</div>{{-- /ttf-pb-section --}}
