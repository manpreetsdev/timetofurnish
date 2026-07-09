@php
    $blockTemplates = \App\Support\CustomPageTemplate::classicBlockTemplates();
    $block = array_merge($blockTemplates[$block['type'] ?? 'content'] ?? $blockTemplates['content'], $block);
@endphp

<div class="ttf-builder-section card mb-3" data-classic-block-card data-classic-block-index="{{ $blockIndex }}">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h6 class="mb-1">{{ translate('Content Block') }}</h6>
            <small class="text-muted">{{ translate('Add a manageable text and image block for simple pages.') }}</small>
        </div>
        <button type="button" class="btn btn-sm btn-soft-danger" data-remove-classic-block>
            <i class="las la-trash"></i>
            {{ translate('Remove Block') }}
        </button>
    </div>
    <div class="card-body">
        <input type="hidden" name="builder[classic_blocks][{{ $blockIndex }}][type]" value="content">

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Layout') }}</label>
                    <select class="form-control aiz-selectpicker" name="builder[classic_blocks][{{ $blockIndex }}][layout]">
                        <option value="text_only" @selected(($block['layout'] ?? '') === 'text_only')>{{ translate('Text Only') }}</option>
                        <option value="image_left" @selected(($block['layout'] ?? '') === 'image_left')>{{ translate('Image Left') }}</option>
                        <option value="image_right" @selected(($block['layout'] ?? '') === 'image_right')>{{ translate('Image Right') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Heading Font Family') }}</label>
                    <select class="form-control aiz-selectpicker" name="builder[classic_blocks][{{ $blockIndex }}][title_font_family]">
                        <option value="">{{ translate('Use global heading font') }}</option>
                        @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                            <option value="{{ $fontValue }}" @selected(($block['title_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Body Font Family') }}</label>
                    <select class="form-control aiz-selectpicker" name="builder[classic_blocks][{{ $blockIndex }}][body_font_family]">
                        <option value="">{{ translate('Use global paragraph font') }}</option>
                        @foreach ($fontFamilyOptions as $fontValue => $fontLabel)
                            <option value="{{ $fontValue }}" @selected(($block['body_font_family'] ?? '') === $fontValue)>{{ $fontLabel }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ translate('Heading') }}</label>
                    <input type="text" class="form-control" name="builder[classic_blocks][{{ $blockIndex }}][title]" value="{{ $block['title'] ?? '' }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label>{{ translate('Highlight Text') }}</label>
                    <input type="text" class="form-control" name="builder[classic_blocks][{{ $blockIndex }}][highlight_text]" value="{{ $block['highlight_text'] ?? '' }}">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Background Color') }}</label>
                    <input type="text" class="form-control" name="builder[classic_blocks][{{ $blockIndex }}][background_color]" value="{{ $block['background_color'] ?? '' }}" placeholder="#fffdf9">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Heading Color') }}</label>
                    <input type="text" class="form-control" name="builder[classic_blocks][{{ $blockIndex }}][title_color]" value="{{ $block['title_color'] ?? '' }}" placeholder="#2c2218">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Text Color') }}</label>
                    <input type="text" class="form-control" name="builder[classic_blocks][{{ $blockIndex }}][body_color]" value="{{ $block['body_color'] ?? '' }}" placeholder="#564638">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{ translate('Content') }}</label>
            <textarea
                class="aiz-text-editor form-control"
                data-min-height="220"
                data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["insert", ["link", "picture", "video"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                name="builder[classic_blocks][{{ $blockIndex }}][content]"
            >{!! $block['content'] ?? '' !!}</textarea>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="form-group">
                    <label>{{ translate('Image') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="builder[classic_blocks][{{ $blockIndex }}][image]" class="selected-files" value="{{ $block['image'] ?? '' }}">
                    </div>
                    <div class="file-preview box sm"></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Image Alt Text') }}</label>
                    <input type="text" class="form-control" name="builder[classic_blocks][{{ $blockIndex }}][image_alt]" value="{{ $block['image_alt'] ?? '' }}">
                </div>
            </div>
        </div>
    </div>
</div>
