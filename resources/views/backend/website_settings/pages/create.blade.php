@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-pages/admin-page-builder.css') }}">

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Add New Page') }}</h1>
        </div>
    </div>
</div>

<div class="card">
    @include('backend.website_settings.pages.partials.form', [
        'pageBuilderData' => $pageBuilderData,
        'fontFamilyOptions' => $fontFamilyOptions,
    ])
</div>
@endsection

@section('script')
    @include('backend.website_settings.pages.partials.page_builder_script')
@endsection
