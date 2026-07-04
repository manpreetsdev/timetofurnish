@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-pages/admin-page-builder.css') }}">

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Edit Page Information') }}</h1>
        </div>
    </div>
</div>

<div class="card">
    <ul class="nav nav-tabs nav-fill border-light">
        @foreach (get_all_active_language() as $key => $language)
            <li class="nav-item">
                <a class="nav-link text-reset @if ($language->code == $lang) active @else bg-soft-dark border-light border-left-0 @endif py-3" href="{{ route('custom-pages.edit', ['id'=>$page->slug, 'lang'=> $language->code] ) }}">
                    <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                    <span>{{ $language->name }}</span>
                </a>
            </li>
        @endforeach
    </ul>

    @include('backend.website_settings.pages.partials.form', [
        'page' => $page,
        'lang' => $lang,
        'pageBuilderData' => $pageBuilderData,
        'fontFamilyOptions' => $fontFamilyOptions,
    ])
</div>
@endsection

@section('script')
    @include('backend.website_settings.pages.partials.page_builder_script')
@endsection
