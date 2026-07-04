@php
    $widgetTemplates = \App\Support\CustomPageTemplate::structuredSectionTemplates();
    $widgetType = $widget['type'] ?? 'rich_text';
    $widget = array_merge($widgetTemplates[$widgetType] ?? $widgetTemplates['rich_text'], $widget);
    $widgetType = $widget['type'] ?? 'rich_text';
    $widgetLabels = [
        'rich_text' => translate('Text Editor'),
        'split' => translate('Two Column'),
        'full_width' => translate('Full Width'),
        'image_grid' => translate('Grid Cards'),
        'full_image' => translate('Image Showcase'),
        'toc_content' => translate('TOC + Content'),
        'header_widget' => translate('Heading'),
        'image_widget' => translate('Single Image'),
        'button_widget' => translate('Action Button'),
    ];
    $widgetLabel = $widgetLabels[$widgetType] ?? translate('Widget');
    $widgetTitle = trim((string) ($widget['title'] ?? ''));
    $widgetSubtitle = trim((string) ($widget['subtitle'] ?? ''));
    $widgetPreview = $widgetTitle !== '' ? $widgetTitle : ($widgetSubtitle !== '' ? $widgetSubtitle : $widgetLabel);
    $isRichText = $widgetType === 'rich_text';
    $isSplit = $widgetType === 'split';
    $isFullWidth = $widgetType === 'full_width';
    $isGrid = $widgetType === 'image_grid';
    $isFullImage = $widgetType === 'full_image';
    $isToc = $widgetType === 'toc_content';
    $isHeader = $widgetType === 'header_widget';
    $isImage = $widgetType === 'image_widget';
    $isButton = $widgetType === 'button_widget';

    $visibleOn = collect([
        ($widget['show_on_desktop'] ?? '1') === '1' ? translate('Desktop') : null,
        ($widget['show_on_ipad_pro'] ?? '1') === '1' ? translate('iPad Pro') : null,
        ($widget['show_on_ipad'] ?? '1') === '1' ? translate('iPad') : null,
        ($widget['show_on_phone'] ?? '1') === '1' ? translate('Phone') : null,
    ])->filter()->values();
    $visibilitySummary = $visibleOn->count() === 4
        ? translate('All Devices')
        : ($visibleOn->isEmpty() ? translate('Hidden Everywhere') : $visibleOn->implode(', '));
@endphp

<div class="ttf-widget-editor card mb-3" data-widget-card data-widget-index="{{ $widgetIndex }}" draggable="false">
    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][type]" value="{{ $widgetType }}">
    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][column_index]" value="{{ $widget['column_index'] ?? '0' }}" data-widget-column-input>

    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="ttf-section-headline">
            <div class="ttf-section-meta">
                <span class="ttf-section-chip">{{ $widgetLabel }}</span>
                <span class="ttf-section-chip ttf-section-chip--muted" data-widget-visibility-summary>{{ $visibilitySummary }}</span>
            </div>
            <h6 class="mb-1" data-widget-label>{{ $widgetLabel }}</h6>
            <small class="text-muted d-block" data-widget-preview>{{ $widgetPreview }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="ttf-drag-handle ttf-drag-handle--widget" data-widget-drag-handle aria-label="{{ translate('Drag to move widget') }}">
                <i class="las la-grip-vertical"></i>
            </button>
            <button type="button" class="btn btn-sm btn-soft-primary" data-toggle-widget data-label-open="{{ translate('Expand') }}" data-label-close="{{ translate('Collapse') }}">
                {{ translate('Expand') }}
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-soft-primary" data-edit-widget-settings title="{{ translate('Widget Settings') }}">
                <i class="las la-cog"></i>
            </button>
            <button type="button" class="btn btn-icon btn-sm btn-soft-danger" data-remove-widget title="{{ translate('Remove Widget') }}">
                <i class="las la-trash"></i>
            </button>
        </div>
    </div>

    <div class="card-body d-none" data-widget-body>
        <div class="ttf-widget-editor__body">
            @if (!$isImage && !$isButton)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header">
                        <h6 class="mb-1">{{ translate('Content') }}</h6>
                        <small class="text-muted">
                            @if ($isRichText)
                                {{ translate('Simple content widget for long text or general information.') }}
                            @elseif ($isSplit)
                                {{ translate('Two column widget with image and text plus optional checklist.') }}
                            @elseif ($isFullWidth)
                                {{ translate('Wide section widget with optional hero image placement.') }}
                            @elseif ($isGrid)
                                {{ translate('Repeatable grid card widget for values, services or highlights.') }}
                            @elseif ($isFullImage)
                                {{ translate('Large image showcase widget with optional heading and content.') }}
                            @elseif ($isHeader)
                                {{ translate('Standalone heading block with custom alignment and size settings.') }}
                            @else
                                {{ translate('Sidebar table of contents with linked content sections.') }}
                            @endif
                        </small>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Heading') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][title]" value="{{ $widget['title'] ?? '' }}" data-widget-heading-input>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Highlight Text') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][highlight_text]" value="{{ $widget['highlight_text'] ?? '' }}">
                                </div>
                            </div>
                            @if (!$isHeader)
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ ($isGrid || $isToc) ? translate('Sub Heading / Eyebrow') : translate('Sub Heading') }}</label>
                                        <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][subtitle]" value="{{ $widget['subtitle'] ?? '' }}" data-widget-subheading-input>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            @if (!$isHeader && !$isImage && !$isButton)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header">
                        <h6 class="mb-1">{{ translate('Body Content') }}</h6>
                        <small class="text-muted">{{ $isToc ? translate('Intro content shown above the linked TOC sections.') : translate('Use the editor for formatted content, paragraphs and links.') }}</small>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="form-group mb-0">
                            <textarea
                                class="aiz-text-editor form-control"
                                data-min-height="220"
                                data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                                name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][content]"
                            >{!! $widget['content'] ?? '' !!}</textarea>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isHeader)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header">
                        <h6 class="mb-1">{{ translate('Heading Options') }}</h6>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group mb-lg-0">
                                    <label>{{ translate('Header Tag') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][header_tag]">
                                        <option value="h1" @selected(($widget['header_tag'] ?? 'h2') === 'h1')>H1</option>
                                        <option value="h2" @selected(($widget['header_tag'] ?? 'h2') === 'h2')>H2</option>
                                        <option value="h3" @selected(($widget['header_tag'] ?? 'h2') === 'h3')>H3</option>
                                        <option value="h4" @selected(($widget['header_tag'] ?? 'h2') === 'h4')>H4</option>
                                        <option value="h5" @selected(($widget['header_tag'] ?? 'h2') === 'h5')>H5</option>
                                        <option value="h6" @selected(($widget['header_tag'] ?? 'h2') === 'h6')>H6</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Text Align') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][text_align]">
                                        <option value="left" @selected(($widget['text_align'] ?? 'left') === 'left')>{{ translate('Left') }}</option>
                                        <option value="center" @selected(($widget['text_align'] ?? '') === 'center')>{{ translate('Center') }}</option>
                                        <option value="right" @selected(($widget['text_align'] ?? '') === 'right')>{{ translate('Right') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isImage)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header">
                        <h6 class="mb-1">{{ translate('Image Settings') }}</h6>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Image Link URL') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_link]" value="{{ $widget['image_link'] ?? '' }}" placeholder="https://...">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Image Alignment') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_align]">
                                        <option value="left" @selected(($widget['image_align'] ?? 'center') === 'left')>{{ translate('Left') }}</option>
                                        <option value="center" @selected(($widget['image_align'] ?? 'center') === 'center')>{{ translate('Center') }}</option>
                                        <option value="right" @selected(($widget['image_align'] ?? 'center') === 'right')>{{ translate('Right') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Image Width (%)') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_width]" value="{{ $widget['image_width'] ?? '100' }}" min="10" max="100">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Image Height (px)') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_height]" value="{{ $widget['image_height'] ?? '' }}" placeholder="{{ translate('Auto') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-lg-0">
                                    <label>{{ translate('Image Alt Text') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_alt]" value="{{ $widget['image_alt'] ?? '' }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Widget Image') }}</label>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div></div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image]" class="selected-files" value="{{ $widget['image'] ?? '' }}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isButton)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header">
                        <h6 class="mb-1">{{ translate('Button Settings') }}</h6>
                    </div>
                    <div class="ttf-section-block__body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Button Text') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_text]" value="{{ $widget['button_text'] ?? 'Click Here' }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Button Link URL') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_link]" value="{{ $widget['button_link'] ?? '' }}" placeholder="https://...">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Button Alignment') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_align]">
                                        <option value="left" @selected(($widget['button_align'] ?? 'left') === 'left')>{{ translate('Left') }}</option>
                                        <option value="center" @selected(($widget['button_align'] ?? '') === 'center')>{{ translate('Center') }}</option>
                                        <option value="right" @selected(($widget['button_align'] ?? '') === 'right')>{{ translate('Right') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Button Padding') }}</label>
                                    <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_padding]" value="{{ $widget['button_padding'] ?? '12px 24px' }}" placeholder="12px 24px">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Background Color') }}</label>
                                    <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_bg_color]" value="{{ $widget['button_bg_color'] ?? '#c8883a' }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>{{ translate('Text Color') }}</label>
                                    <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_text_color]" value="{{ $widget['button_text_color'] ?? '#ffffff' }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-lg-0">
                                    <label>{{ translate('Font Size (px)') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_font_size]" value="{{ $widget['button_font_size'] ?? '16' }}" min="10" max="72">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-0">
                                    <label>{{ translate('Border Radius (px)') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][button_border_radius]" value="{{ $widget['button_border_radius'] ?? '6' }}" min="0" max="50">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($isSplit)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header ttf-section-block__header--inline">
                        <div>
                            <h6 class="mb-1">{{ translate('Checklist Items') }}</h6>
                            <small class="text-muted">{{ translate('Repeatable bullet points under the main content.') }}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-soft-primary" data-add-item="split_items"><i class="las la-plus"></i> {{ translate('Add Item') }}</button>
                    </div>
                    <div class="ttf-section-block__body">
                        <div data-item-target="split_items" data-next-index="{{ count($widget['items'] ?? []) }}">
                            @foreach (($widget['items'] ?? []) as $itemIndex => $item)
                                <div class="row gutters-5 align-items-center mb-2" data-item-row>
                                    <div class="col"><input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][text]" value="{{ $item['text'] ?? '' }}"></div>
                                    <div class="col-auto"><button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger" data-remove-item><i class="las la-times"></i></button></div>
                                </div>
                            @endforeach
                        </div>
                        <template data-item-template="split_items">
                            <div class="row gutters-5 align-items-center mb-2" data-item-row>
                                <div class="col"><input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][text]" value=""></div>
                                <div class="col-auto"><button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger" data-remove-item><i class="las la-times"></i></button></div>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

            @if ($isGrid)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header ttf-section-block__header--inline">
                        <div>
                            <h6 class="mb-1">{{ translate('Grid Cards') }}</h6>
                            <small class="text-muted">{{ translate('Each card can have image, title and text.') }}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-soft-primary" data-add-item="grid_items"><i class="las la-plus"></i> {{ translate('Add Card') }}</button>
                    </div>
                    <div class="ttf-section-block__body">
                        <div data-item-target="grid_items" data-next-index="{{ count($widget['items'] ?? []) }}">
                            @foreach (($widget['items'] ?? []) as $itemIndex => $item)
                                <div class="ttf-grid-card-editor" data-item-row>
                                    <div class="row">
                                        <div class="col-lg-6"><div class="form-group"><label>{{ translate('Card Title') }}</label><input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][title]" value="{{ $item['title'] ?? '' }}"></div></div>
                                        <div class="col-lg-6"><div class="form-group"><label>{{ translate('Card Image') }}</label><div class="input-group" data-toggle="aizuploader" data-type="image"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div></div><div class="form-control file-amount">{{ translate('Choose File') }}</div><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][image]" class="selected-files" value="{{ $item['image'] ?? '' }}"></div><div class="file-preview box sm"></div></div></div>
                                        <div class="col-12"><div class="form-group mb-0"><label>{{ translate('Card Description') }}</label><textarea class="form-control" rows="3" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][text]">{{ $item['text'] ?? '' }}</textarea></div></div>
                                    </div>
                                    <div class="ttf-grid-card-editor__actions"><button type="button" class="btn btn-sm btn-soft-danger" data-remove-item><i class="las la-trash"></i> {{ translate('Remove Card') }}</button></div>
                                </div>
                            @endforeach
                        </div>
                        <template data-item-template="grid_items">
                            <div class="ttf-grid-card-editor" data-item-row>
                                <div class="row">
                                    <div class="col-lg-6"><div class="form-group"><label>{{ translate('Card Title') }}</label><input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][title]" value=""></div></div>
                                    <div class="col-lg-6"><div class="form-group"><label>{{ translate('Card Image') }}</label><div class="input-group" data-toggle="aizuploader" data-type="image"><div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div></div><div class="form-control file-amount">{{ translate('Choose File') }}</div><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][image]" class="selected-files" value=""></div><div class="file-preview box sm"></div></div></div>
                                    <div class="col-12"><div class="form-group mb-0"><label>{{ translate('Card Description') }}</label><textarea class="form-control" rows="3" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][text]"></textarea></div></div>
                                </div>
                                <div class="ttf-grid-card-editor__actions"><button type="button" class="btn btn-sm btn-soft-danger" data-remove-item><i class="las la-trash"></i> {{ translate('Remove Card') }}</button></div>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

            @if ($isToc)
                <div class="ttf-section-block">
                    <div class="ttf-section-block__header ttf-section-block__header--inline">
                        <div>
                            <h6 class="mb-1">{{ translate('Linked TOC Sections') }}</h6>
                            <small class="text-muted">{{ translate('Each entry links to the matching table of contents sidebar panel.') }}</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-soft-primary" data-add-item="toc_items"><i class="las la-plus"></i> {{ translate('Add Entry') }}</button>
                    </div>
                    <div class="ttf-section-block__body">
                        <div data-item-target="toc_items" data-next-index="{{ count($widget['items'] ?? []) }}">
                            @foreach (($widget['items'] ?? []) as $itemIndex => $item)
                                <div class="ttf-toc-editor-card" data-item-row>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ translate('Section Title') }}</label>
                                                <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][title]" value="{{ $item['title'] ?? '' }}" data-toc-title-input>
                                                <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][anchor_id]" value="{{ $item['anchor_id'] ?? '' }}" data-toc-anchor-input>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>{{ translate('Section Image') }}</label>
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][image]" class="selected-files" value="{{ $item['image'] ?? '' }}">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label>{{ translate('Section Content') }}</label>
                                                <textarea class="aiz-text-editor form-control" data-min-height="180" data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]' name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][{{ $itemIndex }}][content]">{!! $item['content'] ?? '' !!}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ttf-grid-card-editor__actions"><button type="button" class="btn btn-sm btn-soft-danger" data-remove-item><i class="las la-trash"></i> {{ translate('Remove Entry') }}</button></div>
                                </div>
                            @endforeach
                        </div>
                        <template data-item-template="toc_items">
                            <div class="ttf-toc-editor-card" data-item-row>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ translate('Section Title') }}</label>
                                            <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][title]" value="" data-toc-title-input>
                                            <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][anchor_id]" value="" data-toc-anchor-input>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label>{{ translate('Section Image') }}</label>
                                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][image]" class="selected-files" value="">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label>{{ translate('Section Content') }}</label>
                                            <textarea class="aiz-text-editor form-control" data-min-height="180" data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["table", ["table"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]' name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][items][__ITEM_INDEX__][content]"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="ttf-grid-card-editor__actions"><button type="button" class="btn btn-sm btn-soft-danger" data-remove-item><i class="las la-trash"></i> {{ translate('Remove Entry') }}</button></div>
                            </div>
                        </template>
                    </div>
                </div>
            @endif

            <div class="ttf-section-settings ttf-section-settings--widget">
                <details class="ttf-setting-group" open>
                    <summary>{{ translate('Layout & Media') }}</summary>
                    <div class="ttf-setting-group__body">
                        @if ($isSplit)
                            <div class="form-group">
                                <label>{{ translate('Desktop Layout') }}</label>
                                <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][layout]">
                                    <option value="image_left" @selected(($widget['layout'] ?? '') === 'image_left')>{{ translate('Image Left / Content Right') }}</option>
                                    <option value="image_right" @selected(($widget['layout'] ?? '') === 'image_right')>{{ translate('Content Left / Image Right') }}</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ translate('iPad Order') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][tablet_stack_order]">
                                            <option value="content_first" @selected(($widget['tablet_stack_order'] ?? '') === 'content_first')>{{ translate('Heading First') }}</option>
                                            <option value="image_first" @selected(($widget['tablet_stack_order'] ?? '') === 'image_first')>{{ translate('Image First') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ translate('Phone Order') }}</label>
                                        <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][mobile_stack_order]">
                                            <option value="content_first" @selected(($widget['mobile_stack_order'] ?? '') === 'content_first')>{{ translate('Heading First') }}</option>
                                            <option value="image_first" @selected(($widget['mobile_stack_order'] ?? '') === 'image_first')>{{ translate('Image First') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($isFullWidth)
                            <div class="form-group">
                                <label>{{ translate('Display Mode') }}</label>
                                <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][display_mode]">
                                    <option value="content_only" @selected(($widget['display_mode'] ?? '') === 'content_only')>{{ translate('Content Only') }}</option>
                                    <option value="image_only" @selected(($widget['display_mode'] ?? '') === 'image_only')>{{ translate('Image Only') }}</option>
                                    <option value="content_image" @selected(($widget['display_mode'] ?? '') === 'content_image')>{{ translate('Content + Image') }}</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-4"><div class="form-group"><label>{{ translate('Desktop') }}</label><select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_position]"><option value="top" @selected(($widget['image_position'] ?? '') === 'top')>{{ translate('Image Top') }}</option><option value="bottom" @selected(($widget['image_position'] ?? '') === 'bottom')>{{ translate('Image Bottom') }}</option></select></div></div>
                                <div class="col-4"><div class="form-group"><label>{{ translate('iPad') }}</label><select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][tablet_image_position]"><option value="top" @selected(($widget['tablet_image_position'] ?? '') === 'top')>{{ translate('Image Top') }}</option><option value="bottom" @selected(($widget['tablet_image_position'] ?? '') === 'bottom')>{{ translate('Image Bottom') }}</option></select></div></div>
                                <div class="col-4"><div class="form-group"><label>{{ translate('Phone') }}</label><select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][mobile_image_position]"><option value="top" @selected(($widget['mobile_image_position'] ?? '') === 'top')>{{ translate('Image Top') }}</option><option value="bottom" @selected(($widget['mobile_image_position'] ?? '') === 'bottom')>{{ translate('Image Bottom') }}</option></select></div></div>
                            </div>
                        @endif

                        @if ($isRichText)
                            <div class="row">
                                <div class="col-6"><div class="form-group"><label>{{ translate('Text Align') }}</label><select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][text_align]"><option value="left" @selected(($widget['text_align'] ?? '') === 'left')>{{ translate('Left') }}</option><option value="center" @selected(($widget['text_align'] ?? '') === 'center')>{{ translate('Center') }}</option><option value="right" @selected(($widget['text_align'] ?? '') === 'right')>{{ translate('Right') }}</option></select></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Content Width (%)') }}</label><input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][max_width]" value="{{ $widget['max_width'] ?? '100' }}" min="40" max="100"></div></div>
                            </div>
                        @endif

                        @if ($isGrid)
                            <div class="row">
                                <div class="col-6"><div class="form-group"><label>{{ translate('Columns') }}</label><select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][columns]">@foreach ([2,3,4] as $columnCount)<option value="{{ $columnCount }}" @selected((string) ($widget['columns'] ?? '3') === (string) $columnCount)>{{ $columnCount }}</option>@endforeach</select></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Card Image Height') }}</label><input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][card_image_height]" value="{{ $widget['card_image_height'] ?? '240' }}" min="120" max="520"></div></div>
                            </div>
                        @endif

                        @if ($isToc)
                            <div class="row">
                                <div class="col-6"><div class="form-group"><label>{{ translate('Sidebar Title') }}</label><input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][toc_title]" value="{{ $widget['toc_title'] ?? '' }}"></div></div>
                                <div class="col-6"><div class="form-group"><label>{{ translate('Sidebar Width') }}</label><input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][sidebar_width]" value="{{ $widget['sidebar_width'] ?? '290' }}" min="180" max="420"></div></div>
                                <div class="col-12"><label class="ttf-toggle-option"><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][sticky_sidebar]" value="0"><input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][sticky_sidebar]" value="1" @checked(($widget['sticky_sidebar'] ?? '1') === '1')><span>{{ translate('Sticky sidebar') }}</span></label></div>
                            </div>
                        @endif

                        @if ($isSplit || $isFullWidth || $isFullImage)
                            <div class="form-group">
                                <label>{{ translate('Image Height (px)') }}</label>
                                <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_height]" value="{{ $widget['image_height'] ?? '520' }}" min="160" max="900">
                            </div>
                        @endif

                        @if ($isImage || $isButton || $isHeader)
                            <div class="ttf-sidebar-note py-2 my-1">
                                {{ translate('Layout settings managed above.') }}
                            </div>
                        @endif

                        @if (!$isGrid && !$isRichText && !$isToc && !$isImage && !$isButton && !$isHeader)
                            <div class="form-group">
                                <label>{{ translate('Image Alt Text') }}</label>
                                <input type="text" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image_alt]" value="{{ $widget['image_alt'] ?? '' }}">
                            </div>
                            <div class="form-group mb-0">
                                <label>{{ translate('Widget Image') }}</label>
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend"><div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div></div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][image]" class="selected-files" value="{{ $widget['image'] ?? '' }}">
                                </div>
                                <div class="file-preview box sm"></div>
                            </div>
                        @endif
                    </div>
                </details>

                <details class="ttf-setting-group">
                    <summary>{{ translate('Typography & Colors') }}</summary>
                    <div class="ttf-setting-group__body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ translate('Heading Font Family') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][title_font_family]">
                                        <option value="">{{ translate('Use global heading font') }}</option>
                                        @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                            <option value="{{ $fontValue }}" @selected(($widget['title_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Heading Font Size (px)') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][title_font_size]" value="{{ $widget['title_font_size'] ?? '' }}" min="10" max="100" placeholder="{{ translate('Auto') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Heading Line Height') }}</label>
                                    <input type="number" step="0.1" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][title_line_height]" value="{{ $widget['title_line_height'] ?? '' }}" min="0.8" max="2.5" placeholder="{{ translate('Auto') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>{{ translate('Body Font Family') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][body_font_family]">
                                        <option value="">{{ translate('Use global paragraph font') }}</option>
                                        @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                                            <option value="{{ $fontValue }}" @selected(($widget['body_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Body Font Size (px)') }}</label>
                                    <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][body_font_size]" value="{{ $widget['body_font_size'] ?? '' }}" min="10" max="100" placeholder="{{ translate('Auto') }}">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>{{ translate('Body Line Height') }}</label>
                                    <input type="number" step="0.1" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][body_line_height]" value="{{ $widget['body_line_height'] ?? '' }}" min="0.8" max="2.5" placeholder="{{ translate('Auto') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-6"><div class="form-group"><label>{{ translate('Heading Color') }}</label><input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][title_color]" value="{{ $widget['title_color'] ?? '#2c2218' }}"></div></div>
                            <div class="col-6"><div class="form-group"><label>{{ translate('Accent Color') }}</label><input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][accent_color]" value="{{ $widget['accent_color'] ?? '#c8883a' }}"></div></div>
                            <div class="col-6"><div class="form-group"><label>{{ translate('Sub Heading Color') }}</label><input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][subtitle_color]" value="{{ $widget['subtitle_color'] ?? '#5b4839' }}"></div></div>
                            <div class="col-6"><div class="form-group"><label>{{ translate('Content Color') }}</label><input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][body_color]" value="{{ $widget['body_color'] ?? '#564638' }}"></div></div>
                            <div class="col-6"><div class="form-group mb-0"><label>{{ translate('Highlight Color') }}</label><input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][highlight_color]" value="{{ $widget['highlight_color'] ?? '#c8883a' }}"></div></div>
                            @if ($isSplit)
                                <div class="col-6"><div class="form-group mb-0"><label>{{ translate('Check Icon') }}</label><input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][check_icon_color]" value="{{ $widget['check_icon_color'] ?? '#c8883a' }}"></div></div>
                            @endif
                        </div>
                    </div>
                </details>
            </div>

            <div class="d-none" data-widget-settings-portal>
                <div class="ttf-section-settings ttf-section-settings--widget">
                    <details class="ttf-setting-group" open>
                        <summary>{{ translate('Layout & Media') }}</summary>
                        <div class="ttf-setting-group__body">
                            @if ($isSplit)
                                <div class="form-group">
                                    <label>{{ translate('Desktop Layout') }}</label>
                                    <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][layout]">
                                        <option value="image_left" @selected(($widget['layout'] ?? '') === 'image_left')>{{ translate('Image Left / Content Right') }}</option>
                                        <option value="image_right" @selected(($widget['layout'] ?? '') === 'image_right')>{{ translate('Content Left / Image Right') }}</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('iPad Order') }}</label>
                                            <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][tablet_stack_order]">
                                                <option value="content_first" @selected(($widget['tablet_stack_order'] ?? '') === 'content_first')>{{ translate('Heading First') }}</option>
                                                <option value="image_first" @selected(($widget['tablet_stack_order'] ?? '') === 'image_first')>{{ translate('Image First') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Highlight') }}</label>
                                            <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][accent_highlight]" value="{{ $widget['accent_highlight'] ?? '#c8883a' }}">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($isFullWidth)
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Desktop Order') }}</label>
                                            <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][order]">
                                                <option value="content_first" @selected(($widget['order'] ?? '') === 'content_first')>{{ translate('Content Left / Image Right') }}</option>
                                                <option value="image_first" @selected(($widget['order'] ?? '') === 'image_first')>{{ translate('Image Left / Content Right') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Highlight') }}</label>
                                            <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][accent_highlight]" value="{{ $widget['accent_highlight'] ?? '#c8883a' }}">
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($isGrid)
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Grid Columns') }}</label>
                                            <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][grid_columns]">
                                                <option value="4" @selected(($widget['grid_columns'] ?? '3') === '4')>{{ translate('4 Columns') }}</option>
                                                <option value="3" @selected(($widget['grid_columns'] ?? '3') === '3')>{{ translate('3 Columns') }}</option>
                                                <option value="2" @selected(($widget['grid_columns'] ?? '3') === '2')>{{ translate('2 Columns') }}</option>
                                                <option value="1" @selected(($widget['grid_columns'] ?? '3') === '1')>{{ translate('1 Column') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Card Border') }}</label>
                                            <select class="form-control aiz-selectpicker" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_border]">
                                                <option value="1" @selected(($widget['show_border'] ?? '1') === '1')>{{ translate('Show Border') }}</option>
                                                <option value="0" @selected(($widget['show_border'] ?? '') === '0')>{{ translate('No Border') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($isFullImage)
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Text Position') }}</label>
                                            <select class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][text_position]">
                                                <option value="left" @selected(($widget['text_position'] ?? 'left') === 'left')>{{ translate('Left') }}</option>
                                                <option value="right" @selected(($widget['text_position'] ?? '') === 'right')>{{ translate('Right') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>{{ translate('Text Alignment') }}</label>
                                            <select class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][text_align]">
                                                <option value="left" @selected(($widget['text_align'] ?? 'left') === 'left')>{{ translate('Left') }}</option>
                                                <option value="center" @selected(($widget['text_align'] ?? '') === 'center')>{{ translate('Center') }}</option>
                                                <option value="right" @selected(($widget['text_align'] ?? '') === 'right')>{{ translate('Right') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($isHeader || $isButton || $isImage)
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group mb-0">
                                            <label>{{ translate('Highlight / Color') }}</label>
                                            <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][accent_highlight]" value="{{ $widget['accent_highlight'] ?? '#c8883a' }}">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </details>

                    <details class="ttf-setting-group">
                        <summary>{{ translate('Typography') }}</summary>
                        <div class="ttf-setting-group__body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ translate('Heading Font Size') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][font_size]" value="{{ $widget['font_size'] ?? '' }}" min="12" max="72" placeholder="32">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ translate('Subheading Size') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][subheading_size]" value="{{ $widget['subheading_size'] ?? '' }}" min="12" max="48" placeholder="18">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Line Height (H)') }}</label>
                                        <input type="number" step="0.1" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][line_height]" value="{{ $widget['line_height'] ?? '' }}" min="1" max="2.5" placeholder="1.3">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Paragraph Size') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][paragraph_size]" value="{{ $widget['paragraph_size'] ?? '' }}" min="10" max="24" placeholder="15">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </details>

                    <details class="ttf-setting-group">
                        <summary>{{ translate('Frame & Spacing') }}</summary>
                        <div class="ttf-setting-group__body">
                            <div class="ttf-toggle-list">
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_background]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_background]" value="1" data-style-toggle="background" @checked(($widget['show_background'] ?? '0') === '1')>
                                    <span>{{ translate('Use background color') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_border]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_border]" value="1" data-style-toggle="border" @checked(($widget['show_border'] ?? '0') === '1')>
                                    <span>{{ translate('Show card border') }}</span>
                                </label>
                                <label class="ttf-toggle-option">
                                    <input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][use_padding]" value="0">
                                    <input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][use_padding]" value="1" data-style-toggle="padding" @checked(($widget['use_padding'] ?? '0') === '1')>
                                    <span>{{ translate('Use inner paddings') }}</span>
                                </label>
                            </div>

                            <div class="row mt-3 {{ ($widget['show_background'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="background">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Background Color') }}</label>
                                        <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][background_color]" value="{{ $widget['background_color'] ?? '#fffdf9' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 {{ (($widget['show_background'] ?? '0') === '1' || ($widget['show_border'] ?? '0') === '1') ? '' : 'd-none' }}" data-style-target="radius">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Corner Radius') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][border_radius]" value="{{ $widget['border_radius'] ?? '20' }}" min="0" max="60">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 {{ ($widget['show_border'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="border">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ translate('Border Color') }}</label>
                                        <input type="color" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][border_color]" value="{{ $widget['border_color'] ?? '#e3d6ca' }}">
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>{{ translate('Border Width') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][border_width]" value="{{ $widget['border_width'] ?? '' }}" min="0" max="10" placeholder="1">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Border Style') }}</label>
                                        <select class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][border_style]">
                                            <option value="solid" @selected(($widget['border_style'] ?? 'solid') === 'solid')>{{ translate('Solid') }}</option>
                                            <option value="dashed" @selected(($widget['border_style'] ?? '') === 'dashed')>{{ translate('Dashed') }}</option>
                                            <option value="dotted" @selected(($widget['border_style'] ?? '') === 'dotted')>{{ translate('Dotted') }}</option>
                                            <option value="double" @selected(($widget['border_style'] ?? '') === 'double')>{{ translate('Double') }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3 {{ ($widget['use_padding'] ?? '0') === '1' ? '' : 'd-none' }}" data-style-target="padding">
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Top') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][padding_top]" value="{{ $widget['padding_top'] ?? '' }}" min="0" max="120" placeholder="32">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('Bottom') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][padding_bottom]" value="{{ $widget['padding_bottom'] ?? '' }}" min="0" max="120" placeholder="32">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group mb-0">
                                        <label>{{ translate('L/R') }}</label>
                                        <input type="number" class="form-control" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][padding_left_right]" value="{{ $widget['padding_left_right'] ?? '' }}" min="0" max="120" placeholder="32">
                                    </div>
                                </div>
                            </div>

                            <div class="ttf-setting-divider"></div>

                            <div class="ttf-toggle-list">
                                <label class="ttf-toggle-option"><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_desktop]" value="0"><input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_desktop]" value="1" data-visibility-toggle="desktop" @checked(($widget['show_on_desktop'] ?? '1') === '1')><span>{{ translate('Desktop') }}</span></label>
                                <label class="ttf-toggle-option"><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_ipad_pro]" value="0"><input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_ipad_pro]" value="1" data-visibility-toggle="ipad_pro" @checked(($widget['show_on_ipad_pro'] ?? '1') === '1')><span>{{ translate('iPad Pro') }}</span></label>
                                <label class="ttf-toggle-option"><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_ipad]" value="0"><input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_ipad]" value="1" data-visibility-toggle="ipad" @checked(($widget['show_on_ipad'] ?? '1') === '1')><span>{{ translate('iPad') }}</span></label>
                                <label class="ttf-toggle-option"><input type="hidden" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_phone]" value="0"><input type="checkbox" name="builder[sections][{{ $groupIndex }}][widgets][{{ $widgetIndex }}][show_on_phone]" value="1" data-visibility-toggle="phone" @checked(($widget['show_on_phone'] ?? '1') === '1')><span>{{ translate('Phone') }}</span></label>
                            </div>
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </div>
</div>
