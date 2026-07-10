@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-pages/admin-page-builder.css') }}?v={{ time() }}">
@php
    $isEdit         = false;
    $currentLang    = env('DEFAULT_LANGUAGE');
    $titleValue     = '';
    $slugValue      = '';
    $metaImageValue = '';
@endphp
@include('backend.website_settings.pages.partials.page_builder', [
    'isEdit'            => false,
    'titleValue'        => $titleValue,
    'slugValue'         => $slugValue,
    'metaImageValue'    => $metaImageValue,
    'pageBuilderData'   => $pageBuilderData,
    'fontFamilyOptions' => $fontFamilyOptions,
])
@endsection

@section('script')
@include('backend.website_settings.pages.partials.page_builder_script')
@endsection
