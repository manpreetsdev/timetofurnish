@extends('frontend.layouts.app')

@php
    $seoTitle = seo_title($blog->meta_title, $blog->title . ' | ' . get_setting('website_name'));
    $seoDescription = seo_description($blog->meta_description, $blog->short_description);
@endphp

@section('meta_title'){{ $seoTitle }}@stop

@section('meta_description'){{ $seoDescription }}@stop

@section('canonical_url'){{ url('blog/' . $blog->slug) }}@stop

@section('meta_keywords'){{ $blog->meta_keywords }}@stop

@section('meta')
<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $seoTitle }}">
<meta itemprop="description" content="{{ $seoDescription }}">
<meta itemprop="image" content="{{ uploaded_asset($blog->meta_img) }}">

<!-- Twitter Card data -->
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image" content="{{ uploaded_asset($blog->meta_img) }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $seoTitle }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ route('blog.details', $blog->slug) }}" />
<meta property="og:image" content="{{ uploaded_asset($blog->meta_img) }}" />
<meta property="og:description" content="{{ $seoDescription }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection

@section('content')
<!-- Blog Header -->
<section class="blog-header">
    <div class="container">

        <!-- Breadcrumb -->
        <nav class="blog-breadcrumb">
            {{-- <a href="{{ url('/') }}">Home</a>
            <span>/</span>--}}

            {{-- <a href="{{ route('blog') }}">Blogs</a>
            <span>/</span>--}}

            <span class="active">{{ $blog->title }}</span>
        </nav>

        <h1 class="sr-only">{{ $blog->title }}</h1>

        <!-- Blog Title -->


        <!-- Date & Category -->


    </div>
</section>
<section class="py-4">
    <div class="container ethe">
        <div class="row gutters-16 justify-content-center">

            <!-- Blog Details -->
            <div class="col-xxl-7 col-lg-8">
                <div class="mb-0">
                    <!-- Title -->
                    {{-- <h2 class="fs-20 fs-md-24 fw-700 mb-3">
                        <a href="{{ url("blog").'/'. $blog->slug }}" class="text-reset hov-text-primary" title="{{ $blog->title }}">
                    {{ $blog->title }}
                    </a>
                    </h2>--}}
                    <div class="row">
                        <div class="col-4">
                            <!-- Date -->
                            {{-- <div>
                                <small class="fs-12 fw-400 opacity-60">{{ date('M d, Y',strtotime($blog->created_at)) }}</small>
                        </div>--}}
                        <!-- Caregory -->
                        @if($blog->category != null)
                        {{-- <div>
                                    <small class="fs-12 fw-400 text-blue">{{ $blog->category->category_name }}</small>
                    </div>--}}
                    @endif
                </div>
                <!-- Share -->
                <div class="col-12 text-right d-block d-lg-none">
                    <div class="aiz-share"></div>
                </div>
                {{--<div class=" col-8 text-right aiz-share">
    <a class="facebook"><i class="fab fa-facebook-f"></i></a>
    <a class="twitter"><i class="fab fa-twitter"></i></a>
    <a class="linkedin"><i class="fab fa-linkedin-in"></i></a>
    <a class="whatsapp"><i class="fab fa-whatsapp"></i></a>
</div>--}}
            </div>
            <!-- Image -->
            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                data-src="{{ uploaded_asset($blog->banner) }}"
                alt="{{ $blog->title }}"
                class="img-fluid lazyload w-100 mt-3 mb-4">
            <!-- Description -->
            <div class="mb-4 detailcontent overflow-hidden">
                {!! $blog->description !!}
            </div>
            <!-- Facebook Comment -->
            @if (get_setting('facebook_comment') == 1)
            <div class="mb-4">
                <div class="fb-comments" data-href="{{ route("blog",$blog->slug) }}" data-width="" data-numposts="5"></div>
            </div>
            @endif
        </div>
    </div>


    <!-- recent posts -->
    <div class="col-xxl-3 col-lg-4">

        <div class="p-3 border recent-post">
            <div class="blog-share mb-4 d-none d-lg-block">
                <div class="aiz-share">
                    <a class="facebook"><i class="fab fa-facebook-f"></i></a>
                    <a class="twitter"><i class="fab fa-twitter"></i></a>
                    <a class="linkedin"><i class="fab fa-linkedin-in"></i></a>
                    <a class="whatsapp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            <h3 class="fs-16 fw-700 text-dark mb-3">{{ translate('Recent Posts') }}</h3>
            <div class="row">
                @foreach($recent_blogs as $recent_blog)
                <div class="col-lg-12 col-sm-6 mb-4 hov-scale-img">
                    <div class="d-flex">
                        <div class="">
                            <a href="{{ url("blog").'/'. $recent_blog->slug }}" class="text-reset d-block overflow-hidden size-80px size-xl-90px mr-2">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($recent_blog->banner) }}"
                                    alt="{{ $recent_blog->title }}"
                                    class="img-fit lazyload h-100 has-transition">
                            </a>
                        </div>
                        <div class="">
                            <h2 class="fs-14 fw-700 mb-2 mb-xl-3 h-35px text-truncate-2">
                                <a href="{{ url("blog").'/'. $recent_blog->slug }}" class="text-reset hov-text-primary" title="{{ $recent_blog->title }}">
                                    {{ $recent_blog->title }}
                                </a>
                            </h2>
                            <div>
                                <small class="fs-12 fw-400 opacity-60">{{ date('M d, Y',strtotime($recent_blog->created_at)) }}</small>
                            </div>
                            @if($recent_blog->category != null)
                            <div>
                                <small class="fs-12 fw-400 text-blue">{{ $recent_blog->category->category_name }}</small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    </div>
    </div>
</section>
<style>
    .blog-header {
        padding: 28px;
        background: #6e5540;
        font-size: 20px;
    }

    .blog-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;

        font-size: 15px;
        justify-content: center;
    }

    .blog-breadcrumb a {
        color: #8A6A55;
        text-decoration: none;
        transition: .3s;
    }

    .blog-breadcrumb a:hover {
        color: #6B4423;
    }

    .blog-breadcrumb span {
        color: #B7A18F;
    }

    .blog-breadcrumb .active {
        color: #ffffff;
        font-weight: 500;
        font-size: 33px;
        font-family: 'Playfair Display';
    }

    .blog-title {
        font-size: 48px;
        font-weight: 700;
        line-height: 1.25;
        color: #2E2A39;
        margin-bottom: 18px;
        max-width: 1000px;
    }

    .blog-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #8E8E8E;
        font-size: 15px;
    }

    .blog-meta .separator {
        color: #C5C5C5;
    }

    @media(max-width:768px) {

        .blog-header {
            padding: 17px 0;
        }

        .blog-title {
            font-size: 32px;
        }

        .blog-breadcrumb {
            font-size: 13px;
        }

        .blog-breadcrumb .active {
            font-size: 17px;
        }

        .blog-meta {
            font-size: 13px;
        }

    }

    .detailcontent p {
        font-size: 15px;

        color: #555;
        margin-bottom: 20px;
        text-align: justify;
    }

    .aiz-share a {
        width: 42px;
        height: 42px;
        display: flex;
        align-items: center;
        justify-content: center;

        border: 1px solid #D9CFC5;
        border-radius: 50%;

        background: #fff !important;
        color: #7B4B2A !important;

        transition: .3s;

    }

    .aiz-share a:hover {
        background: #7B4B2A !important;
        color: #fff !important;
        border-color: #7B4B2A;
    }

    .aiz-share i {
        font-size: 18px;
    }

    .recent-post {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
</style>

@endsection


@section('script')
@if (get_setting('facebook_comment') == 1)
<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v9.0&appId={{ env('FACEBOOK_APP_ID') }}&autoLogAppEvents=1" nonce="ji6tXwgZ"></script>
@endif
@endsection
