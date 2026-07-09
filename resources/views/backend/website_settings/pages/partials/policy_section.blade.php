@php
    $section = array_merge(\App\Support\CustomPageTemplate::policySectionTemplate(), $policySection);
@endphp

<div class="ttf-builder-section card mb-3" data-policy-section-card data-policy-section-index="{{ $policyIndex }}">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
        <div>
            <h6 class="mb-1">{{ translate('Policy Section') }}</h6>
            <small class="text-muted">{{ translate('Each section becomes part of the page content and table of contents.') }}</small>
        </div>
        <button type="button" class="btn btn-sm btn-soft-danger" data-remove-policy-section>
            <i class="las la-trash"></i>
            {{ translate('Remove Section') }}
        </button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="form-group">
                    <label>{{ translate('Section Heading') }}</label>
                    <input type="text" class="form-control" name="builder[policy_sections][{{ $policyIndex }}][title]" value="{{ $section['title'] ?? '' }}" placeholder="{{ translate('Who We Are') }}">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label>{{ translate('Short Summary') }}</label>
                    <input type="text" class="form-control" name="builder[policy_sections][{{ $policyIndex }}][summary]" value="{{ $section['summary'] ?? '' }}" placeholder="{{ translate('Optional small intro line') }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>{{ translate('Section Content') }}</label>
            <textarea
                class="aiz-text-editor form-control"
                data-min-height="220"
                data-buttons='[["font", ["bold", "underline", "italic", "clear"]],["para", ["ul", "ol", "paragraph"]],["style", ["style"]],["color", ["color"]],["insert", ["link"]],["view", ["fullscreen", "codeview", "undo", "redo"]]]'
                name="builder[policy_sections][{{ $policyIndex }}][content]"
            >{!! $section['content'] ?? '' !!}</textarea>
        </div>

        <div class="form-group mb-2">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <label class="mb-0">{{ translate('Bullet Points') }}</label>
                <button type="button" class="btn btn-sm btn-soft-primary" data-add-policy-item>
                    <i class="las la-plus"></i>
                    {{ translate('Add Point') }}
                </button>
            </div>
        </div>
        <div data-policy-item-target data-next-index="{{ count($section['items'] ?? []) }}">
            @foreach (($section['items'] ?? []) as $itemIndex => $item)
                <div class="row gutters-5 align-items-center mb-2" data-policy-item-row>
                    <div class="col">
                        <input type="text" class="form-control" name="builder[policy_sections][{{ $policyIndex }}][items][{{ $itemIndex }}][text]" value="{{ $item['text'] ?? '' }}" placeholder="{{ translate('Policy point') }}">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger" data-remove-policy-item>
                            <i class="las la-times"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        <template data-policy-item-template>
            <div class="row gutters-5 align-items-center mb-2" data-policy-item-row>
                <div class="col">
                    <input type="text" class="form-control" name="builder[policy_sections][{{ $policyIndex }}][items][__ITEM_INDEX__][text]" value="" placeholder="{{ translate('Policy point') }}">
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-icon btn-circle btn-sm btn-soft-danger" data-remove-policy-item>
                        <i class="las la-times"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
