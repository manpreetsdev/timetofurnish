@php
    $bannerTitle = $banner['title'] ?? 'Default Title';
    $breadcrumbLabel = $banner['breadcrumb_label'] ?? $bannerTitle;
    $backgroundImage = !empty($banner['background_image']) ? uploaded_asset($banner['background_image']) : static_asset('assets/img/banner.png');
    $bannerAlignment = $banner['text_align'] ?? 'center';
    $bannerJustify = $bannerAlignment === 'left' ? 'flex-start' : ($bannerAlignment === 'right' ? 'flex-end' : 'center');
@endphp

<section
    class="ttf-page-banner"
    style="
        --ttf-banner-height: {{ (int) ($banner['height'] ?? 340) }}px;
        --ttf-banner-align: {{ $bannerAlignment }};
        --ttf-banner-justify: {{ $bannerJustify }};
        --ttf-banner-overlay: {{ $banner['overlay_color'] ?? 'rgba(54, 38, 26, 0.42)' }};
        --ttf-banner-title-color: {{ $banner['title_color'] ?? '#ffffff' }};
        --ttf-banner-subtitle-color: {{ $banner['subtitle_color'] ?? '#f8f0e7' }};
        --ttf-banner-title-font: {{ $banner['title_font_family'] ?? 'Playfair Display, serif' }};
        --ttf-banner-subtitle-font: {{ $banner['subtitle_font_family'] ?? 'Poppins, sans-serif' }};
        --ttf-banner-image: url('{{ $backgroundImage }}');
    "
>
    <div class="ttf-page-banner__overlay"></div>
    <div class="ttf-page-banner__content">
        <div class="ttf-page-banner__crumbs">
             <h1>{{ $bannerTitle }}</h1>
        <div class="sub_breadcrumb">
             <a href="{{ route('home') }}">{{ translate('Home') }}</a>
            <span>&raquo;</span>
            <span>{{ $breadcrumbLabel }}</span>
        </div>
        </div>

        @if (!empty($banner['subtitle']))
            <p>{{ $banner['subtitle'] }}</p>
        @endif
    </div>
</section>
