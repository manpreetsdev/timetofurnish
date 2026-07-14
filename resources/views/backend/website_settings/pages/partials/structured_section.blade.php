@php
    $tpl        = \App\Support\CustomPageTemplate::structuredSectionTemplates();
    $wt         = $widget['type'] ?? 'rich_text';
    $widget     = array_merge($tpl[$wt] ?? $tpl['rich_text'], $widget);
    $wt         = $widget['type'];
    $gi         = $groupIndex;
    $wi         = $widgetIndex;
    $n          = "builder[sections][$gi][widgets][$wi]";

    $isRichText  = $wt === 'rich_text';
    $isSplit     = $wt === 'split';
    $isFullWidth = $wt === 'full_width';
    $isGrid      = $wt === 'image_grid';
    $isFullImage = $wt === 'full_image';
    $isToc       = $wt === 'toc_content';
    $isHeader    = $wt === 'header_widget';
    $isImage     = $wt === 'image_widget';
    $isButton    = $wt === 'button_widget';
    $hasImage    = $isSplit || $isFullWidth || $isFullImage;

    $wLabels = [
        'rich_text'     => translate('Text Editor'),
        'split'         => translate('Two Column'),
        'full_width'    => translate('Full Width'),
        'image_grid'    => translate('Grid Cards'),
        'full_image'    => translate('Image Showcase'),
        'toc_content'   => translate('TOC + Content'),
        'header_widget' => translate('Heading'),
        'image_widget'  => translate('Single Image'),
        'button_widget' => translate('Action Button'),
    ];
    $wLabel  = $wLabels[$wt] ?? translate('Widget');
    $wTitle  = trim((string)($widget['title'] ?? ''));
    $preview = $wTitle !== '' ? $wTitle : $wLabel;
    $themeAccent = get_setting('base_color', '#d43533');

    $visOn = collect([
        ($widget['show_on_desktop']  ?? '1') === '1' ? 'Desktop'  : null,
        ($widget['show_on_ipad_pro'] ?? '1') === '1' ? 'iPad Pro' : null,
        ($widget['show_on_ipad']     ?? '1') === '1' ? 'iPad'     : null,
        ($widget['show_on_phone']    ?? '1') === '1' ? 'Phone'    : null,
    ])->filter()->values();
    $visSummary = $visOn->count() === 4 ? translate('All Devices')
        : ($visOn->isEmpty() ? translate('Hidden') : $visOn->implode(', '));
    $itemCount = count($widget['items'] ?? []);
    $pickerColor = static fn ($value, $fallback = '#ffffff') => \App\Support\CustomPageTemplate::colorPickerValue($value, $fallback);
@endphp

<div class="ttf-pb-widget" data-widget-card data-widget-index="{{ $wi }}" draggable="true">
    <input type="hidden" name="{{ $n }}[type]"         value="{{ $wt }}">
    <input type="hidden" name="{{ $n }}[column_index]" value="{{ $widget['column_index'] ?? '0' }}" data-widget-column-input>

    {{-- Widget header --}}
    <div class="ttf-pb-widget__header">
        <span class="ttf-pb-drag-handle ttf-pb-drag-handle--widget" data-widget-drag-handle><i class="las la-grip-vertical"></i></span>
        <div class="ttf-pb-widget__info">
            <div class="ttf-pb-widget__type">{{ $wLabel }}</div>
            <div class="ttf-pb-widget__title" data-widget-label>{{ $preview }}</div>
        </div>
        <div class="ttf-pb-widget__actions">
            <button type="button" class="ttf-pb-icon-btn" data-move-widget-up   title="{{ translate('Up') }}"><i class="las la-arrow-up"></i></button>
            <button type="button" class="ttf-pb-icon-btn" data-move-widget-down title="{{ translate('Down') }}"><i class="las la-arrow-down"></i></button>
            <button type="button" class="ttf-pb-icon-btn" data-copy-widget      title="{{ translate('Duplicate') }}"><i class="las la-copy"></i></button>
            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--primary" data-toggle-widget
                    data-label-open="{{ translate('Expand') }}" data-label-close="{{ translate('Collapse') }}"
                    title="{{ translate('Edit Content') }}"><i class="las la-angle-right"></i></button>
            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--primary" data-edit-widget-settings
                    title="{{ translate('Style Settings') }}"><i class="las la-sliders-h"></i></button>
            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-widget
                    title="{{ translate('Delete') }}"><i class="las la-trash"></i></button>
        </div>
    </div>

    {{-- Content editor (collapsible) --}}
    <div class="ttf-pb-widget__body d-none" data-widget-body>

        {{-- Preview strip --}}
        <div class="ttf-pb-widget-preview">
            <div class="ttf-pb-widget-preview__label">{{ $wLabel }}</div>
            <div class="ttf-pb-widget-preview__title" data-widget-preview>{{ $preview }}</div>
            @if ($itemCount > 0)
                <span class="ttf-pb-widget-preview__meta">{{ $itemCount }} {{ translate('items') }}</span>
            @endif
        </div>

        {{-- ══════════════════════════════════════════════════════════
             CONTENT SECTION
             ══════════════════════════════════════════════════════════ --}}

        {{-- Shared: title / show_title toggle (all except image, button) --}}
        @if (!$isImage && !$isButton)
        <details class="ttf-pb-accordion" open>
            <summary class="ttf-pb-accordion__summary">{{ translate('Content') }}</summary>
            <div class="ttf-pb-accordion__body">

                <label class="ttf-pb-toggle-opt" style="margin-bottom:10px">
                    <input type="hidden"   name="{{ $n }}[show_title]" value="0">
                    <input type="checkbox" name="{{ $n }}[show_title]" value="1"
                           data-toggle-field="show_title"
                           @checked(($widget['show_title']??'1')==='1')>
                    <span>{{ translate('Show heading') }}</span>
                </label>

                <div class="{{ ($widget['show_title']??'1')==='1' ? '' : 'd-none' }}" data-toggle-target="show_title">
                    <div class="form-group">
                        <label>{{ translate('Heading') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[title]"
                               value="{{ $widget['title'] ?? '' }}" data-widget-heading-input>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Highlight Text') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[highlight_text]"
                               value="{{ $widget['highlight_text'] ?? '' }}">
                    </div>
                    @if (!$isHeader)
                    <div class="form-group">
                        <label>{{ translate('Sub Heading') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[subtitle]"
                               value="{{ $widget['subtitle'] ?? '' }}" data-widget-subheading-input>
                    </div>
                    @endif
                </div>

                @if ($isHeader)
                    <div class="ttf-pb-row-2">
                        <div class="form-group">
                            <label>{{ translate('Tag') }}</label>
                            <select class="form-control" name="{{ $n }}[header_tag]">
                                @foreach(['h1','h2','h3','h4','h5','h6'] as $ht)
                                    <option value="{{ $ht }}" @selected(($widget['header_tag']??'h2')===$ht)>{{ strtoupper($ht) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>{{ translate('Align') }}</label>
                            <select class="form-control" name="{{ $n }}[text_align]">
                                <option value="left"   @selected(($widget['text_align']??'left')==='left')>{{ translate('Left') }}</option>
                                <option value="center" @selected(($widget['text_align']??'')==='center')>{{ translate('Center') }}</option>
                                <option value="right"  @selected(($widget['text_align']??'')==='right')>{{ translate('Right') }}</option>
                            </select>
                        </div>
                    </div>
                @endif

                @if (!$isHeader)
                <div class="form-group" style="margin-bottom:0">
                    <label>{{ $isToc ? translate('Intro / Body Content') : translate('Body Content') }}</label>
                    <textarea class="aiz-text-editor form-control" data-min-height="200"
                        data-buttons='[["font",["bold","underline","italic","clear"]],["para",["ul","ol","paragraph"]],["style",["style"]],["color",["color"]],["table",["table"]],["insert",["link","picture","video"]],["view",["fullscreen","codeview","undo","redo"]]]'
                        name="{{ $n }}[content]">{!! $widget['content'] ?? '' !!}</textarea>
                </div>
                @endif

            </div>
        </details>
        @endif

        {{-- Image widget content --}}
        @if ($isImage)
        <details class="ttf-pb-accordion" open>
            <summary class="ttf-pb-accordion__summary">{{ translate('Image') }}</summary>
            <div class="ttf-pb-accordion__body">
                <div class="form-group">
                    <label>{{ translate('Image') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="{{ $n }}[image]" class="selected-files" value="{{ $widget['image'] ?? '' }}">
                    </div>
                    <div class="file-preview box sm"></div>
                </div>
                <div class="ttf-pb-row-2">
                    <div class="form-group">
                        <label>{{ translate('Alt Text') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[image_alt]" value="{{ $widget['image_alt'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Link URL') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[image_link]" value="{{ $widget['image_link'] ?? '' }}" placeholder="https://...">
                    </div>
                </div>
            </div>
        </details>
        @endif

        {{-- Button widget content --}}
        @if ($isButton)
        <details class="ttf-pb-accordion" open>
            <summary class="ttf-pb-accordion__summary">{{ translate('Button') }}</summary>
            <div class="ttf-pb-accordion__body">
                <div class="ttf-pb-row-2">
                    <div class="form-group">
                        <label>{{ translate('Text') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[button_text]" value="{{ $widget['button_text'] ?? 'Click Here' }}">
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Link URL') }}</label>
                        <input type="text" class="form-control" name="{{ $n }}[button_link]" value="{{ $widget['button_link'] ?? '' }}" placeholder="https://...">
                    </div>
                </div>
            </div>
        </details>
        @endif

        {{-- Checklist items (split) --}}
        @if ($isSplit)
        <details class="ttf-pb-accordion">
            <summary class="ttf-pb-accordion__summary">{{ translate('Checklist Items') }}</summary>
            <div class="ttf-pb-accordion__body">
                <div style="display:flex;justify-content:flex-end;margin-bottom:8px">
                    <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost" data-add-item="split_items">
                        <i class="las la-plus"></i> {{ translate('Add Item') }}
                    </button>
                </div>
                <div data-item-target="split_items" data-next-index="{{ count($widget['items'] ?? []) }}">
                    @foreach (($widget['items'] ?? []) as $ii => $item)
                        <div class="ttf-pb-repeater-row" data-item-row data-item-index="{{ $ii }}">
                            <input type="text" class="form-control" name="{{ $n }}[items][{{ $ii }}][text]" value="{{ $item['text'] ?? '' }}">
                            <div class="ttf-pb-repeater-actions">
                                <button type="button" class="ttf-pb-icon-btn" data-move-item-up><i class="las la-arrow-up"></i></button>
                                <button type="button" class="ttf-pb-icon-btn" data-move-item-down><i class="las la-arrow-down"></i></button>
                                <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-item><i class="las la-times"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-item-template="split_items">
                    <div class="ttf-pb-repeater-row" data-item-row data-item-index="__ITEM_INDEX__">
                        <input type="text" class="form-control" name="{{ $n }}[items][__ITEM_INDEX__][text]" value="">
                        <div class="ttf-pb-repeater-actions">
                            <button type="button" class="ttf-pb-icon-btn" data-move-item-up><i class="las la-arrow-up"></i></button>
                            <button type="button" class="ttf-pb-icon-btn" data-move-item-down><i class="las la-arrow-down"></i></button>
                            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-item><i class="las la-times"></i></button>
                        </div>
                    </div>
                </template>
            </div>
        </details>
        @endif

        {{-- Grid cards --}}
        @if ($isGrid)
        <details class="ttf-pb-accordion">
            <summary class="ttf-pb-accordion__summary">{{ translate('Grid Cards') }}</summary>
            <div class="ttf-pb-accordion__body">
                <div style="display:flex;justify-content:flex-end;margin-bottom:8px">
                    <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost" data-add-item="grid_items">
                        <i class="las la-plus"></i> {{ translate('Add Card') }}
                    </button>
                </div>
                <div data-item-target="grid_items" data-next-index="{{ count($widget['items'] ?? []) }}">
                    @foreach (($widget['items'] ?? []) as $ii => $item)
                        <div class="ttf-pb-card-editor" data-item-row data-item-index="{{ $ii }}">
                            <div class="ttf-pb-card-editor__actions">
                                <button type="button" class="ttf-pb-icon-btn" data-move-item-up><i class="las la-arrow-up"></i></button>
                                <button type="button" class="ttf-pb-icon-btn" data-move-item-down><i class="las la-arrow-down"></i></button>
                                <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-item><i class="las la-trash"></i></button>
                            </div>
                            <div class="form-group"><label>{{ translate('Card Title') }}</label><input type="text" class="form-control" name="{{ $n }}[items][{{ $ii }}][title]" value="{{ $item['title'] ?? '' }}"></div>
                            <div class="form-group"><label>{{ translate('Card Image') }}</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="{{ $n }}[items][{{ $ii }}][image]" class="selected-files" value="{{ $item['image'] ?? '' }}">
                                </div><div class="file-preview box sm"></div>
                            </div>
                            <div class="form-group" style="margin-bottom:0"><label>{{ translate('Description') }}</label><textarea class="form-control" rows="2" name="{{ $n }}[items][{{ $ii }}][text]">{{ $item['text'] ?? '' }}</textarea></div>
                        </div>
                    @endforeach
                </div>
                <template data-item-template="grid_items">
                    <div class="ttf-pb-card-editor" data-item-row data-item-index="__ITEM_INDEX__">
                        <div class="ttf-pb-card-editor__actions">
                            <button type="button" class="ttf-pb-icon-btn" data-move-item-up><i class="las la-arrow-up"></i></button>
                            <button type="button" class="ttf-pb-icon-btn" data-move-item-down><i class="las la-arrow-down"></i></button>
                            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-item><i class="las la-trash"></i></button>
                        </div>
                        <div class="form-group"><label>{{ translate('Card Title') }}</label><input type="text" class="form-control" name="{{ $n }}[items][__ITEM_INDEX__][title]" value=""></div>
                        <div class="form-group"><label>{{ translate('Card Image') }}</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="{{ $n }}[items][__ITEM_INDEX__][image]" class="selected-files" value="">
                            </div><div class="file-preview box sm"></div>
                        </div>
                        <div class="form-group" style="margin-bottom:0"><label>{{ translate('Description') }}</label><textarea class="form-control" rows="2" name="{{ $n }}[items][__ITEM_INDEX__][text]"></textarea></div>
                    </div>
                </template>
            </div>
        </details>
        @endif

        {{-- TOC entries --}}
        @if ($isToc)
        <details class="ttf-pb-accordion">
            <summary class="ttf-pb-accordion__summary">{{ translate('TOC Sections') }}</summary>
            <div class="ttf-pb-accordion__body">
                <div style="display:flex;justify-content:flex-end;margin-bottom:8px">
                    <button type="button" class="ttf-pb-btn ttf-pb-btn--ghost" data-add-item="toc_items">
                        <i class="las la-plus"></i> {{ translate('Add Entry') }}
                    </button>
                </div>
                <div data-item-target="toc_items" data-next-index="{{ count($widget['items'] ?? []) }}">
                    @foreach (($widget['items'] ?? []) as $ii => $item)
                        <div class="ttf-pb-card-editor" data-item-row data-item-index="{{ $ii }}">
                            <div class="ttf-pb-card-editor__actions">
                                <button type="button" class="ttf-pb-icon-btn" data-move-item-up><i class="las la-arrow-up"></i></button>
                                <button type="button" class="ttf-pb-icon-btn" data-move-item-down><i class="las la-arrow-down"></i></button>
                                <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-item><i class="las la-trash"></i></button>
                            </div>
                            <div class="ttf-pb-row-2">
                                <div class="form-group"><label>{{ translate('Title') }}</label>
                                    <input type="text" class="form-control" name="{{ $n }}[items][{{ $ii }}][title]" value="{{ $item['title'] ?? '' }}" data-toc-title-input>
                                    <input type="hidden" name="{{ $n }}[items][{{ $ii }}][anchor_id]" value="{{ $item['anchor_id'] ?? '' }}" data-toc-anchor-input>
                                </div>
                                <div class="form-group"><label>{{ translate('Image') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="{{ $n }}[items][{{ $ii }}][image]" class="selected-files" value="{{ $item['image'] ?? '' }}">
                                    </div><div class="file-preview box sm"></div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-bottom:0"><label>{{ translate('Content') }}</label>
                                <textarea class="aiz-text-editor form-control" data-min-height="160"
                                    data-buttons='[["font",["bold","underline","italic","clear"]],["para",["ul","ol","paragraph"]],["style",["style"]],["color",["color"]],["table",["table"]],["insert",["link","picture","video"]],["view",["fullscreen","codeview","undo","redo"]]]'
                                    name="{{ $n }}[items][{{ $ii }}][content]">{!! $item['content'] ?? '' !!}</textarea>
                            </div>
                        </div>
                    @endforeach
                </div>
                <template data-item-template="toc_items">
                    <div class="ttf-pb-card-editor" data-item-row data-item-index="__ITEM_INDEX__">
                        <div class="ttf-pb-card-editor__actions">
                            <button type="button" class="ttf-pb-icon-btn" data-move-item-up><i class="las la-arrow-up"></i></button>
                            <button type="button" class="ttf-pb-icon-btn" data-move-item-down><i class="las la-arrow-down"></i></button>
                            <button type="button" class="ttf-pb-icon-btn ttf-pb-icon-btn--danger" data-remove-item><i class="las la-trash"></i></button>
                        </div>
                        <div class="ttf-pb-row-2">
                            <div class="form-group"><label>{{ translate('Title') }}</label>
                                <input type="text" class="form-control" name="{{ $n }}[items][__ITEM_INDEX__][title]" value="" data-toc-title-input>
                                <input type="hidden" name="{{ $n }}[items][__ITEM_INDEX__][anchor_id]" value="" data-toc-anchor-input>
                            </div>
                            <div class="form-group"><label>{{ translate('Image') }}</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="{{ $n }}[items][__ITEM_INDEX__][image]" class="selected-files" value="">
                                </div><div class="file-preview box sm"></div>
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0"><label>{{ translate('Content') }}</label>
                            <textarea class="aiz-text-editor form-control" data-min-height="160"
                                data-buttons='[["font",["bold","underline","italic","clear"]],["para",["ul","ol","paragraph"]],["style",["style"]],["color",["color"]],["table",["table"]],["insert",["link","picture","video"]],["view",["fullscreen","codeview","undo","redo"]]]'
                                name="{{ $n }}[items][__ITEM_INDEX__][content]"></textarea>
                        </div>
                    </div>
                </template>
            </div>
        </details>
        @endif

    </div>{{-- /ttf-pb-widget__body --}}

    {{-- ══════════════════════════════════════════════════════════
         SETTINGS PORTAL — moved into right panel on gear click
         ══════════════════════════════════════════════════════════ --}}
    <div class="d-none" data-widget-settings-portal><div>

        {{-- Layout & Media --}}
        <details class="ttf-pb-accordion" open>
          <summary class="ttf-pb-accordion__summary">{{ translate('Layout & Media') }}</summary>
          <div class="ttf-pb-accordion__body">
            @if ($isRichText)
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Text Align') }}</label>
                  <select class="form-control" name="{{ $n }}[text_align]">
                    <option value="left"   @selected(($widget['text_align']??'left')==='left')>Left</option>
                    <option value="center" @selected(($widget['text_align']??'')==='center')>Center</option>
                    <option value="right"  @selected(($widget['text_align']??'')==='right')>Right</option>
                  </select>
                </div>
                <div class="form-group"><label>{{ translate('Max Width %') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[max_width]" value="{{ $widget['max_width']??'100' }}" min="30" max="100">
                </div>
              </div>
            @endif
            @if ($isSplit)
              <div class="form-group"><label>{{ translate('Desktop Layout') }}</label>
                <select class="form-control" name="{{ $n }}[layout]">
                  <option value="image_left"  @selected(($widget['layout']??'image_left')==='image_left')>{{ translate('Image Left') }}</option>
                  <option value="image_right" @selected(($widget['layout']??'')==='image_right')>{{ translate('Image Right') }}</option>
                </select>
              </div>
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Tablet Order') }}</label>
                  <select class="form-control" name="{{ $n }}[tablet_stack_order]">
                    <option value="content_first" @selected(($widget['tablet_stack_order']??'content_first')==='content_first')>{{ translate('Content First') }}</option>
                    <option value="image_first"   @selected(($widget['tablet_stack_order']??'')==='image_first')>{{ translate('Image First') }}</option>
                  </select>
                </div>
                <div class="form-group"><label>{{ translate('Mobile Order') }}</label>
                  <select class="form-control" name="{{ $n }}[mobile_stack_order]">
                    <option value="content_first" @selected(($widget['mobile_stack_order']??'content_first')==='content_first')>{{ translate('Content First') }}</option>
                    <option value="image_first"   @selected(($widget['mobile_stack_order']??'')==='image_first')>{{ translate('Image First') }}</option>
                  </select>
                </div>
              </div>
            @endif
            @if ($isFullWidth)
              <div class="form-group"><label>{{ translate('Display Mode') }}</label>
                <select class="form-control" name="{{ $n }}[display_mode]">
                  <option value="content_only"  @selected(($widget['display_mode']??'content_only')==='content_only')>{{ translate('Content Only') }}</option>
                  <option value="image_only"    @selected(($widget['display_mode']??'')==='image_only')>{{ translate('Image Only') }}</option>
                  <option value="content_image" @selected(($widget['display_mode']??'')==='content_image')>{{ translate('Content + Image') }}</option>
                </select>
              </div>
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Image Position') }}</label>
                  <select class="form-control" name="{{ $n }}[image_position]">
                    <option value="bottom" @selected(($widget['image_position']??'bottom')==='bottom')>Bottom</option>
                    <option value="top"    @selected(($widget['image_position']??'')==='top')>Top</option>
                  </select>
                </div>
                <div class="form-group"><label>{{ translate('Text Align') }}</label>
                  <select class="form-control" name="{{ $n }}[text_align]">
                    <option value="left"   @selected(($widget['text_align']??'left')==='left')>Left</option>
                    <option value="center" @selected(($widget['text_align']??'')==='center')>Center</option>
                    <option value="right"  @selected(($widget['text_align']??'')==='right')>Right</option>
                  </select>
                </div>
              </div>
            @endif
            @if ($isGrid)
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Columns') }}</label>
                  <select class="form-control" name="{{ $n }}[columns]">
                    @foreach([2,3,4] as $c)<option value="{{ $c }}" @selected((string)($widget['columns']??'3')===(string)$c)>{{ $c }}</option>@endforeach
                  </select>
                </div>
                <div class="form-group"><label>{{ translate('Card Image Height') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[card_image_height]" value="{{ $widget['card_image_height']??'240' }}" min="80" max="600">
                </div>
              </div>
            @endif
            @if ($isToc)
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('TOC Title') }}</label>
                  <input type="text" class="form-control" name="{{ $n }}[toc_title]" value="{{ $widget['toc_title']??'' }}">
                </div>
                <div class="form-group"><label>{{ translate('Sidebar Width') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[sidebar_width]" value="{{ $widget['sidebar_width']??'320' }}" min="160" max="500">
                </div>
              </div>
              <label class="ttf-pb-toggle-opt">
                <input type="hidden" name="{{ $n }}[sticky_sidebar]" value="0">
                <input type="checkbox" name="{{ $n }}[sticky_sidebar]" value="1" @checked(($widget['sticky_sidebar']??'1')==='1')>
                <span>{{ translate('Sticky sidebar') }}</span>
              </label>
            @endif
            @if ($isImage)
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Alignment') }}</label>
                  <select class="form-control" name="{{ $n }}[image_align]">
                    <option value="left"   @selected(($widget['image_align']??'center')==='left')>Left</option>
                    <option value="center" @selected(($widget['image_align']??'center')==='center')>Center</option>
                    <option value="right"  @selected(($widget['image_align']??'')==='right')>Right</option>
                  </select>
                </div>
                <div class="form-group"><label>{{ translate('Width %') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[image_width]" value="{{ $widget['image_width']??'100' }}" min="10" max="100">
                </div>
              </div>
              <div class="form-group"><label>{{ translate('Height px (blank = auto)') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[image_height]" value="{{ $widget['image_height']??'' }}" placeholder="Auto">
              </div>
            @endif
            @if ($isButton)
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Alignment') }}</label>
                  <select class="form-control" name="{{ $n }}[button_align]">
                    <option value="left"   @selected(($widget['button_align']??'left')==='left')>Left</option>
                    <option value="center" @selected(($widget['button_align']??'')==='center')>Center</option>
                    <option value="right"  @selected(($widget['button_align']??'')==='right')>Right</option>
                  </select>
                </div>
                <div class="form-group"><label>{{ translate('Border Radius') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[button_border_radius]" value="{{ $widget['button_border_radius']??'6' }}" min="0" max="100">
                </div>
              </div>
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Font Size') }}</label>
                  <input type="text" class="form-control" name="{{ $n }}[button_font_size]" value="{{ $widget['button_font_size']??'16' }}" placeholder="16">
                </div>
                <div class="form-group"><label>{{ translate('Padding') }}</label>
                  <input type="text" class="form-control" name="{{ $n }}[button_padding]" value="{{ $widget['button_padding']??'12px 24px' }}" placeholder="12px 24px">
                </div>
              </div>
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('BG Color') }}</label>
                  <div class="ttf-pb-color-row">
                    <input type="color" value="{{ $pickerColor($widget['button_bg_color'] ?? null, $themeAccent) }}">
                    <input type="text" class="form-control" name="{{ $n }}[button_bg_color]" value="{{ $widget['button_bg_color']??$themeAccent }}">
                  </div>
                </div>
                <div class="form-group"><label>{{ translate('Text Color') }}</label>
                  <div class="ttf-pb-color-row">
                    <input type="color" value="{{ $pickerColor($widget['button_text_color'] ?? null, '#ffffff') }}">
                    <input type="text" class="form-control" name="{{ $n }}[button_text_color]" value="{{ $widget['button_text_color']??'#ffffff' }}">
                  </div>
                </div>
              </div>
            @endif
            @if ($hasImage)
              <div class="form-group"><label>{{ translate('Widget Image') }}</label>
                <div class="input-group" data-toggle="aizuploader" data-type="image">
                  <div class="input-group-prepend"><div class="input-group-text">{{ translate('Browse') }}</div></div>
                  <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                  <input type="hidden" name="{{ $n }}[image]" class="selected-files" value="{{ $widget['image']??'' }}">
                </div>
                <div class="file-preview box sm"></div>
              </div>
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Alt Text') }}</label>
                  <input type="text" class="form-control" name="{{ $n }}[image_alt]" value="{{ $widget['image_alt']??'' }}">
                </div>
                <div class="form-group"><label>{{ translate('Image Height px') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[image_height]" value="{{ $widget['image_height']??'520' }}" min="80" max="1200">
                </div>
              </div>
            @endif
          </div>
        </details>

        {{-- Typography --}}
        @if (!$isImage && !$isButton)
        <details class="ttf-pb-accordion">
          <summary class="ttf-pb-accordion__summary">{{ translate('Typography') }}</summary>
          <div class="ttf-pb-accordion__body">
            <div class="form-group"><label>{{ translate('Heading Font Family') }}</label>
              <select class="form-control aiz-selectpicker" data-container="body" name="{{ $n }}[title_font_family]">
                <option value="">{{ translate('Use global') }}</option>
                @foreach ($fontFamilyOptions as $fv => $fl)
                  <option value="{{ $fv }}" @selected(($widget['title_font_family']??'')===$fv)>{{ $fl }}</option>
                @endforeach
              </select>
            </div>
            <div class="ttf-pb-row-2">
              <div class="form-group"><label>{{ translate('H Size (px)') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[title_font_size]" value="{{ $widget['title_font_size']??'28' }}" min="8" max="120">
              </div>
              <div class="form-group"><label>{{ translate('H Weight') }}</label>
                <select class="form-control" name="{{ $n }}[title_font_weight]">
                  @foreach(['300','400','500','600','700','800','900'] as $fw)
                    <option value="{{ $fw }}" @selected(($widget['title_font_weight']??'700')===$fw)>{{ $fw }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group"><label>{{ translate('H Line Height') }}</label>
                <input type="text" class="form-control" name="{{ $n }}[title_line_height]" value="{{ \App\Support\CustomPageTemplate::normalizeLineHeightValue($widget['title_line_height'] ?? null, $widget['title_font_size'] ?? 28, '1.18') }}" placeholder="e.g. 34px">
              </div>
              <div class="form-group"><label>{{ translate('H Letter Spacing') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[title_letter_spacing]" value="{{ $widget['title_letter_spacing']??'0' }}" min="-5" max="20" step="0.5">
              </div>
            </div>
            <div class="ttf-pb-divider"></div>
            <div class="form-group"><label>{{ translate('Body Font Family') }}</label>
              <select class="form-control aiz-selectpicker" data-container="body" name="{{ $n }}[body_font_family]">
                <option value="">{{ translate('Use global') }}</option>
                @foreach ($fontFamilyOptions as $fv => $fl)
                  <option value="{{ $fv }}" @selected(($widget['body_font_family']??'')===$fv)>{{ $fl }}</option>
                @endforeach
              </select>
            </div>
            <div class="ttf-pb-row-2">
              <div class="form-group"><label>{{ translate('B Size (px)') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[body_font_size]" value="{{ $widget['body_font_size']??'16' }}" min="8" max="72">
              </div>
              <div class="form-group"><label>{{ translate('B Weight') }}</label>
                <select class="form-control" name="{{ $n }}[body_font_weight]">
                  @foreach(['300','400','500','600','700'] as $fw)
                    <option value="{{ $fw }}" @selected(($widget['body_font_weight']??'400')===$fw)>{{ $fw }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group"><label>{{ translate('B Line Height') }}</label>
                <input type="text" class="form-control" name="{{ $n }}[body_line_height]" value="{{ \App\Support\CustomPageTemplate::normalizeLineHeightValue($widget['body_line_height'] ?? null, $widget['body_font_size'] ?? 18, '1.72') }}" placeholder="e.g. 31px">
              </div>
              <div class="form-group"><label>{{ translate('B Letter Spacing') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[body_letter_spacing]" value="{{ $widget['body_letter_spacing']??'0' }}" min="-5" max="20" step="0.5">
              </div>
            </div>
          </div>
        </details>

        {{-- Colors --}}
        <details class="ttf-pb-accordion">
          <summary class="ttf-pb-accordion__summary">{{ translate('Colors') }}</summary>
          <div class="ttf-pb-accordion__body">
            <div class="ttf-pb-row-2">
              <div class="form-group"><label>{{ translate('Heading') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['title_color'] ?? null, '#1e293b') }}">
                  <input type="text" class="form-control" name="{{ $n }}[title_color]" value="{{ $widget['title_color']??'#1e293b' }}"></div>
              </div>
              <div class="form-group"><label>{{ translate('Sub Heading') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['subtitle_color'] ?? null, '#475569') }}">
                  <input type="text" class="form-control" name="{{ $n }}[subtitle_color]" value="{{ $widget['subtitle_color']??'#475569' }}"></div>
              </div>
              <div class="form-group"><label>{{ translate('Body Text') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['body_color'] ?? null, '#475569') }}">
                  <input type="text" class="form-control" name="{{ $n }}[body_color]" value="{{ $widget['body_color']??'#475569' }}"></div>
              </div>
              <div class="form-group"><label>{{ translate('Accent') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['accent_color'] ?? null, $themeAccent) }}">
                  <input type="text" class="form-control" name="{{ $n }}[accent_color]" value="{{ $widget['accent_color']??$themeAccent }}"></div>
              </div>
              <div class="form-group"><label>{{ translate('Highlight') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['highlight_color'] ?? null, $themeAccent) }}">
                  <input type="text" class="form-control" name="{{ $n }}[highlight_color]" value="{{ $widget['highlight_color']??$themeAccent }}"></div>
              </div>
              @if ($isSplit)
              <div class="form-group"><label>{{ translate('Check Icon') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['check_icon_color'] ?? null, $themeAccent) }}">
                  <input type="text" class="form-control" name="{{ $n }}[check_icon_color]" value="{{ $widget['check_icon_color']??$themeAccent }}"></div>
              </div>
              @endif
            </div>
          </div>
        </details>
        @endif

        {{-- Background --}}
        <details class="ttf-pb-accordion">
          <summary class="ttf-pb-accordion__summary">{{ translate('Background') }}</summary>
          <div class="ttf-pb-accordion__body">
            <label class="ttf-pb-toggle-opt" style="margin-bottom:10px">
              <input type="hidden"   name="{{ $n }}[show_background]" value="0">
              <input type="checkbox" name="{{ $n }}[show_background]" value="1"
                     data-style-toggle="background" @checked(($widget['show_background']??'0')==='1')>
              <span>{{ translate('Enable background') }}</span>
            </label>
            <div class="{{ ($widget['show_background']??'0')==='1' ? '' : 'd-none' }}" data-style-target="background">
              <div class="form-group"><label>{{ translate('Background Color') }}</label>
                <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['background_color'] ?? null, '#ffffff') }}">
                  <input type="text" class="form-control" name="{{ $n }}[background_color]" value="{{ $widget['background_color']??'#ffffff' }}"></div>
              </div>
              <div class="form-group"><label>{{ translate('Corner Radius (px)') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[border_radius]" value="{{ $widget['border_radius']??'0' }}" min="0" max="60">
              </div>
            </div>
          </div>
        </details>

        {{-- Border --}}
        <details class="ttf-pb-accordion">
          <summary class="ttf-pb-accordion__summary">{{ translate('Border') }}</summary>
          <div class="ttf-pb-accordion__body">
            <label class="ttf-pb-toggle-opt" style="margin-bottom:10px">
              <input type="hidden"   name="{{ $n }}[show_border]" value="0">
              <input type="checkbox" name="{{ $n }}[show_border]" value="1"
                     data-style-toggle="border" @checked(($widget['show_border']??'0')==='1')>
              <span>{{ translate('Enable border') }}</span>
            </label>
            <div class="{{ ($widget['show_border']??'0')==='1' ? '' : 'd-none' }}" data-style-target="border">
              <div class="ttf-pb-row-2">
                <div class="form-group"><label>{{ translate('Color') }}</label>
                  <div class="ttf-pb-color-row"><input type="color" value="{{ $pickerColor($widget['border_color'] ?? null, '#e2e8f0') }}">
                    <input type="text" class="form-control" name="{{ $n }}[border_color]" value="{{ $widget['border_color']??'#e2e8f0' }}"></div>
                </div>
                <div class="form-group"><label>{{ translate('Width (px)') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[border_width]" value="{{ $widget['border_width']??'1' }}" min="0" max="20">
                </div>
              </div>
              <div class="form-group"><label>{{ translate('Style') }}</label>
                <select class="form-control" name="{{ $n }}[border_style]">
                  <option value="solid"  @selected(($widget['border_style']??'solid')==='solid')>Solid</option>
                  <option value="dashed" @selected(($widget['border_style']??'')==='dashed')>Dashed</option>
                  <option value="dotted" @selected(($widget['border_style']??'')==='dotted')>Dotted</option>
                </select>
              </div>
            </div>
          </div>
        </details>

        {{-- Spacing --}}
        <details class="ttf-pb-accordion">
          <summary class="ttf-pb-accordion__summary">{{ translate('Spacing') }}</summary>
          <div class="ttf-pb-accordion__body">
            <label class="ttf-pb-toggle-opt" style="margin-bottom:10px">
              <input type="hidden"   name="{{ $n }}[use_padding]" value="0">
              <input type="checkbox" name="{{ $n }}[use_padding]" value="1"
                     data-style-toggle="padding" @checked(($widget['use_padding']??'0')==='1')>
              <span>{{ translate('Enable custom padding') }}</span>
            </label>
            <div class="{{ ($widget['use_padding']??'0')==='1' ? '' : 'd-none' }}" data-style-target="padding">
              <p style="font-size:10px;color:#94a3b8;margin:0 0 6px;font-weight:700;text-transform:uppercase;letter-spacing:.04em">{{ translate('Padding (px)') }}</p>
              <div class="ttf-pb-spacing-grid">
                <div class="form-group"><label>{{ translate('Top') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[padding_top]" value="{{ $widget['padding_top']??'0' }}" min="0" max="300">
                </div>
                <div class="form-group"><label>{{ translate('Right') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[padding_right]" value="{{ $widget['padding_right']??'0' }}" min="0" max="300">
                </div>
                <div class="form-group"><label>{{ translate('Bottom') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[padding_bottom]" value="{{ $widget['padding_bottom']??'0' }}" min="0" max="300">
                </div>
                <div class="form-group"><label>{{ translate('Left') }}</label>
                  <input type="number" class="form-control" name="{{ $n }}[padding_left]" value="{{ $widget['padding_left'] ?? ($widget['padding_left_right']??'0') }}" min="0" max="300">
                </div>
              </div>
            </div>
            <div class="ttf-pb-divider"></div>
            <p style="font-size:10px;color:#94a3b8;margin:0 0 6px;font-weight:700;text-transform:uppercase;letter-spacing:.04em">{{ translate('Margin (px)') }}</p>
            <div class="ttf-pb-row-2">
              <div class="form-group"><label>{{ translate('Margin Top') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[margin_top]" value="{{ $widget['margin_top']??'0' }}" min="-100" max="300">
              </div>
              <div class="form-group"><label>{{ translate('Margin Bottom') }}</label>
                <input type="number" class="form-control" name="{{ $n }}[margin_bottom]" value="{{ $widget['margin_bottom']??'0' }}" min="-100" max="300">
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
                <input type="hidden" name="{{ $n }}[show_on_desktop]" value="0">
                <input type="checkbox" name="{{ $n }}[show_on_desktop]" value="1" data-visibility-toggle="desktop" @checked(($widget['show_on_desktop']??'1')==='1')>
                <span>Desktop</span>
              </label>
              <label class="ttf-pb-toggle-opt">
                <input type="hidden" name="{{ $n }}[show_on_ipad_pro]" value="0">
                <input type="checkbox" name="{{ $n }}[show_on_ipad_pro]" value="1" data-visibility-toggle="ipad_pro" @checked(($widget['show_on_ipad_pro']??'1')==='1')>
                <span>iPad Pro</span>
              </label>
              <label class="ttf-pb-toggle-opt">
                <input type="hidden" name="{{ $n }}[show_on_ipad]" value="0">
                <input type="checkbox" name="{{ $n }}[show_on_ipad]" value="1" data-visibility-toggle="ipad" @checked(($widget['show_on_ipad']??'1')==='1')>
                <span>iPad</span>
              </label>
              <label class="ttf-pb-toggle-opt">
                <input type="hidden" name="{{ $n }}[show_on_phone]" value="0">
                <input type="checkbox" name="{{ $n }}[show_on_phone]" value="1" data-visibility-toggle="phone" @checked(($widget['show_on_phone']??'1')==='1')>
                <span>Phone</span>
              </label>
            </div>
          </div>
        </details>

    </div></div>{{-- /data-widget-settings-portal --}}
</div>{{-- /ttf-pb-widget --}}
