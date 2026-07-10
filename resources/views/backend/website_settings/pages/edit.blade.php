@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-pages/admin-page-builder.css') }}?v={{ time() }}">
@php
    $isEdit       = true;
    $currentLang  = $lang ?? env('DEFAULT_LANGUAGE');
    $titleValue   = $page->getTranslation('title', $currentLang);
    $slugValue    = $page->slug;
    $metaImageValue = $page->meta_image ?? '';
@endphp
@include('backend.website_settings.pages.partials.page_builder', [
    'page'              => $page,
    'lang'              => $currentLang,
    'isEdit'            => true,
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
