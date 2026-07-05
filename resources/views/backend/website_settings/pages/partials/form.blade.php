@php
    $isEdit = isset($page);
    $currentLang = $lang ?? env('DEFAULT_LANGUAGE');
    $titleValue = $isEdit ? $page->getTranslation('title', $currentLang) : '';
    $slugValue = $isEdit ? $page->slug : '';
@endphp

<form class="p-0 ttf-admin-page-form" action="{{ $isEdit ? route('custom-pages.update', $page->id) : route('custom-pages.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="lang" value="{{ $currentLang }}">
    @endif

    <div class="ttf-builder-card ttf-builder-card--plain ttf-page-basics-card {{ $isEdit ? 'mb-4' : 'mb-4 mt-0' }}">
        <div class="card-header">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div>
                    <span class="ttf-step-label">{{ translate('Step 1') }}</span>
                    <h6 class="fw-600 mb-1">{{ translate('Page Basics') }}</h6>
                    <p class="mb-0 text-muted">{{ translate('Keep the page title and URL clean. These fields are shared across every custom page.') }}</p>
                </div>
                @if ($isEdit)
                    <span class="ttf-inline-note">
                        <i class="las la-language mr-1"></i>
                        {{ translate('Translatable title') }}
                    </span>
                @endif
            </div>
        </div>
        <div class="card-body">
            <div class="row ttf-page-basics-grid">
                <div class="col-lg-6">
                    <div class="form-group mb-lg-0">
                        <label for="ttf-page-title">
                            {{ translate('Title') }}
                            <span class="text-danger">*</span>
                        </label>
                        <input id="ttf-page-title" type="text" class="form-control" placeholder="{{ translate('Title') }}" name="title" value="{{ $titleValue }}" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group mb-0">
                        <label for="ttf-page-slug">
                            {{ translate('Slug') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group ttf-slug-group">
                            @if (!$isEdit || $page->type == 'custom_page')
                                <div class="input-group-prepend">
                                    <span class="input-group-text ttf-slug-prefix">{{ route('home') }}/</span>
                                </div>
                                <input
                                    id="ttf-page-slug"
                                    type="text"
                                    class="form-control"
                                    placeholder="{{ translate('page-slug') }}"
                                    name="slug"
                                    value="{{ $slugValue }}"
                                    data-page-slug-input
                                    required
                                >
                            @else
                                <input id="ttf-page-slug" class="form-control" value="{{ route('home') }}/{{ $page->slug }}" disabled>
                            @endif
                        </div>
                        @if (!$isEdit || $page->type == 'custom_page')
                            <div class="ttf-slug-options">
                                <label class="ttf-toggle-option mb-0">
                                    <input type="checkbox" value="1" data-page-slug-autofill @checked(!$isEdit || $slugValue === '')>
                                    <span>{{ translate('Auto fill from page title') }}</span>
                                </label>
                                <small class="form-text text-muted mb-0">{{ translate('Use characters, numbers and hyphens only. You can still edit the slug manually.') }}</small>
                            </div>
                        @else
                            <small class="form-text text-muted">{{ translate('Use characters, numbers and hyphens only') }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.website_settings.pages.partials.page_builder', [
        'pageBuilderData' => $pageBuilderData,
        'fontFamilyOptions' => $fontFamilyOptions,
    ])
</form>
