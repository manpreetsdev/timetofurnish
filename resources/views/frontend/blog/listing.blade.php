@extends('frontend.layouts.app')
@php
    $banner = [
        'title' => 'Blogs',
        'breadcrumb_label' => 'Blogs',
        'background_image' => null,
        'height' => 340,
        'text_align' => 'center',
        'overlay_color' => 'rgba(54, 38, 26, 0.42)',
        'title_color' => '#ffffff',
        'subtitle_color' => '#f8f0e7',
        'title_font_family' => 'Playfair Display, serif',
        'subtitle_font_family' => 'Poppins, sans-serif',
        'subtitle' => null,
    ];
@endphp

@section('content')

<!-- Banner -->
{{--<section class="contact-banner blog-banner">
    <div class="contact-overlay">
        <div class="container">
            <div class="banner-content">
                <h1>Blogs @if(request()->has('page') && request('page') > 1)<span class="sr-only"> - {{ translate('Page') }} {{ request('page') }}</span>@endif</h1>

                <ul class="breadcrumb justify-content-center bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item active">
                        Blogs
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>--}}
@include('frontend.custom-pages.partials.banner', [
    'banner' => $banner
])
<!-- Search -->
<div class="blog-search-section">
    <div class="blog-search-content">
        <h2>Search Our Blogs</h2>
        {{-- <p>Find helpful tips, design ideas and furniture guides to create your perfect space.</p>--}}

        <form id="search-form" method="GET">
            <div class="blog-search-box">

                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search blog articles...">

                <button type="submit">
                    <i class="las la-search"></i>
                </button>

            </div>
        </form>
    </div>
</div>
{{-- --}}
<section class="pb-4 pt-0">
    <div class="container">
        <div class="row gutters-16">
            <!-- Contents -->
            <div class="col-xl-12 order-xl-1">
                <!-- Breadcrumb -->
                <div class="row gutters-16 mb-4">
                    {{-- <div class="col-5 col-xl-6">
                            <h1 class="fw-700 fs-20 fs-md-24 text-dark mb-0">{{ translate('Blogs')}} @if(request()->has('page') && request('page') > 1)<span class="sr-only"> - {{ translate('Page') }} {{ request('page') }}</span>@endif</h1>
                </div>
                <div class="col-5 col-xl-6">
                    <ul class="breadcrumb bg-transparent p-0 justify-content-end">
                        <li class="breadcrumb-item has-transition opacity-60 hov-opacity-100">
                            <a class="text-reset" href="{{ route('home') }}">
                                {{ translate('Home')}}
                            </a>
                        </li>
                        <li class="text-dark fw-600 breadcrumb-item">
                            "{{ translate('Blog') }}"
                        </li>
                    </ul>
                </div>--}}
                {{-- <div class="col d-xl-none mb-lg-3 text-right">
                            <button type="button" class="btn btn-icon p-0 active" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
                                <i class="la la-filter la-2x"></i>
                            </button>
                        </div>--}}
            </div>
            <!-- Blogs -->
            <div class="row gx-2">
                @foreach($blogs as $blog)
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                    <div class="card h-100 overflow-hidden shadow-none border rounded-0 hov-scale-img p-3">

                        <a href="{{ url('blog').'/'.$blog->slug }}" class="text-reset d-block overflow-hidden h-180px">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($blog->banner) }}"
                                alt="{{ $blog->title }}"
                                class="img-fit lazyload h-100 has-transition">
                        </a>

                        <div class=" d-flex flex-column h-90 mt-3">

                            <h2 class="fs-16 fw-700 mb-3 blogtexttitle">
                                <a href="{{ url('blog').'/'.$blog->slug }}"
                                    class="text-reset hov-text-primary">
                                    {{ $blog->title }}
                                </a>
                            </h2>

                            <p class="opacity-70 mb-4 blogtext">
                                {{ $blog->short_description }}
                            </p>

                            <small class="fs-12 fw-400 opacity-60 blogdate">
                                {{ date('M d, Y', strtotime($blog->created_at)) }}
                            </small>

                            <div class=" pt-1">
                                <a href="{{ url('blog').'/'.$blog->slug }}"
                                    class="fs-14 fw-700 blogreadmoretext d-flex align-items-center">
                                    Read Full Blog
                                    <i class="las la-arrow-right fs-24 ml-1"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <!-- Pagination -->
            <div class="aiz-pagination mt-4">
                {{ $blogs->links() }}
            </div>
        </div>

        <!-- Sidebar -->
        {{--<div class="col-xl-3">
                    <!-- Filters -->
                    <form class="mb-4" id="search-form" action="" method="GET">
                        <div class="aiz-filter-sidebar collapse-sidebar-wrap sidebar-xl sidebar-right z-1035">
                            <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle" data-target=".aiz-filter-sidebar" data-same=".filter-sidebar-thumb"></div>
                            <div class="collapse-sidebar c-scrollbar-light text-left" style="overflow-y: auto;">
                                <div class="d-flex d-xl-none justify-content-between align-items-center pl-3 border-bottom">
                                    <h3 class="h6 mb-0 fw-600">{{ translate('Filters') }}</h3>
        <button type="button" class="btn btn-sm p-2 filter-sidebar-thumb" data-toggle="class-toggle" data-target=".aiz-filter-sidebar">
            <i class="las la-times la-2x"></i>
        </button>
    </div>
    <!-- Search -->
    <div class="mb-4 mt-3 px-3 mt-xl-0 px-xl-0">
        <div class="input-group w-100">
            <input type="text" class="border border-right-0 rounded-0 fs-14 flex-grow-1" name="search" value="{{ $search }}" placeholder="{{translate('Search...')}}" autocomplete="off" style="padding: 14px;">
            <div class="input-group-append">
                <button class="btn bg-transparent hov-bg-light rounded-0 border border-left-0" type="submit" style="">
                    <i class="la la-search la-flip-horizontal fs-18 text-gray"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Categories -->
    <div class="bg-white border mb-3 mx-3 mx-xl-0">
        <div class="fs-16 fw-700 p-3">{{ translate('Categories')}}</div>
        <div class="p-3 aiz-checkbox-list">
            @foreach (get_all_blog_categories() as $category)
            <label class="aiz-checkbox mb-3">
                <input
                    type="checkbox"
                    name="selected_categories[]"
                    value="{{ $category->slug }}" @if (in_array($category->slug, $selected_categories)) checked @endif
                onchange="filter()"
                >
                <span class="aiz-square-check"></span>
                <span class="fs-14 fw-400 text-dark has-transition hov-text-primary">{{ $category->category_name }}</span>
            </label>
            @endforeach
        </div>
    </div>

    </div>
    </div>
    </form>

    <!-- recent posts -->
    <div class="p-3 border">
        <h3 class="fs-16 fw-700 text-dark mb-3">{{ translate('Recent Posts') }}</h3>
        <div class="row">
            @foreach($recent_blogs as $recent_blog)
            <div class="col-xl-12 col-lg-4 col-sm-6 mb-4 hov-scale-img">
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

    </div>--}}

    </div>
    </div>
</section>
@endsection
<style>
    .blog-banner {
        background:url('{{ static_asset("assets/img/contact-banner.jpg") }}') center center/cover no-repeat;
        height: 300px;
        position: relative;
    }

    .contact-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .35);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-content {
        text-align: center;
    }

    .banner-content h1 {
        font-family: 'Playfair Display', serif;
        font-size: 48px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 12px;
    }

    .banner-content .breadcrumb {
        justify-content: center;
    }

    .banner-content .breadcrumb-item,
    .banner-content .breadcrumb-item a {
        color: #fff;
        font-size: 18px;
        font-weight: 500;
        text-decoration: none;
    }

    .banner-content .breadcrumb-item+.breadcrumb-item::before {
        content: "»";
        color: #fff;
        padding: 0 10px;
    }

    .banner-content .breadcrumb-item.active {
        color: #fff;
    }

    @media(max-width:767px) {
        .blog-banner {
            height: 220px;
        }

        .banner-content h1 {
            font-size: 34px;
        }

        .banner-content .breadcrumb-item,
        .banner-content .breadcrumb-item a {
            font-size: 15px;
        }
    }


        {
            {
            -- search bar button --
        }
    }

    .blog-search-section {
            {
                {
                -- background: linear-gradient(135deg, #FFF8F1, #FFFDFB);
                --
            }
        }

            {
                {
                --background: #91663e14;
                --
            }
        }

        border:1px solid #F0E5DA;

            {
                {
                -- border-radius: 20px;
                --
            }
        }

        padding:21px 40px;

            {
                {
                -- margin: 50px 0;
                --
            }
        }

        position:relative;
        overflow:hidden;

            {
                {
                --box-shadow: 0 15px 35px rgba(0, 0, 0, .05);
                --
            }
        }
    }

    /* Decorative icons (optional) */
    .blog-search-section::before {
            {
                {
                --content: "🌿";
                --
            }
        }

        position:absolute;
        left:30px;
        bottom:20px;
        font-size:70px;
        opacity:.08;
    }

    .blog-search-section::after {
            {
                {
                --content: "🛋";
                --
            }
        }

        position:absolute;
        right:35px;
        bottom:20px;
        font-size:80px;
        opacity:.08;
    }

    .blog-search-content {
        max-width: 760px;
        margin: auto;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .blog-search-content h2 {
        font-size: 42px;
        font-weight: 700;
        color: #3A2A22;
        margin-bottom: 10px;
        font-family: 'Playfair Display', serif;
    }

    .blog-search-content p {
        color: #7B6A58;
        font-size: 17px;
        margin-bottom: 30px;
    }

    .blog-search-box {
        display: flex;
        max-width: 760px;
        margin: auto;
    }

    .blog-search-box input {
        flex: 1;
        height: 55px;
        border: none;
        padding: 0 25px;
        font-size: 16px;
        background: #fff;
        border-radius: 50px 0 0 50px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .08);
        outline: none;
    }

    .blog-search-box input:focus {
        box-shadow: 0 8px 25px rgba(176, 137, 104, .25);
    }

    .blog-search-box button {
        width: 80px;
        border: none;
        background: #5f4d3e;
        color: #fff;
        border-radius: 0 50px 50px 0;
        transition: .3s;
        font-size: 22px;
    }

    .blog-search-box button:hover {
        background: #8C5A30;
    }

    @media (max-width:768px) {

        .blog-search-section {
            padding: 25px 15px;
        }

        .blog-search-content h2 {
            font-size: 26px;
        }

        /* Reduce search bar width */
        .blog-search-box {
            width: 85%;
            max-width: 300px;
            margin: 0 auto;
            display: flex;
            align-items: center;
        }

        .blog-search-box input {
            flex: 1;
            width: auto;
            min-width: 0;
            height: 42px;
            padding: 0 15px;
            font-size: 14px;
        }

        .blog-search-box button {
            width: 50px;
            height: 42px;
            font-size: 16px;
        }

    }
</style>
@section('script')
<script type="text/javascript">
    function filter() {
        $('#search-form').submit();
    }
</script>
@endsection