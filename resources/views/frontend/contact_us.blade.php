@extends('frontend.layouts.app')

@php
$seoTitle = seo_title($page->meta_title ?? null, translate('Contact Us') . ' | ' . get_setting('website_name'));
$seoDescription = seo_description($page->meta_description ?? null, translate('Get in touch with us for support, inquiries, and feedback.'));
@endphp

@section('meta_title'){{ $seoTitle }}@stop
@section('meta_description'){{ $seoDescription }}@stop
@section('canonical_url'){{ route('contact_us') }}@stop

{{--
@section('meta_title'){{ $page->meta_title }}@stop

@section('meta_description'){{ $page->meta_description }}@stop

@section('meta_keywords'){{ $page->tags }}@stop

@section('meta')

<!-- Schema.org markup for Google+ -->
<meta itemprop="name" content="{{ $page->meta_title }}">
<meta itemprop="description" content="{{ $page->meta_description }}">
<meta itemprop="image" content="{{ uploaded_asset($page->meta_image) }}">

<!-- Twitter Card data -->
<meta name="twitter:card" content="website">
<meta name="twitter:site" content="@publisher_handle">
<meta name="twitter:title" content="{{ $page->meta_title }}">
<meta name="twitter:description" content="{{ $page->meta_description }}">
<meta name="twitter:creator" content="@author_handle">
<meta name="twitter:image" content="{{ uploaded_asset($page->meta_image) }}">

<!-- Open Graph data -->
<meta property="og:title" content="{{ $page->meta_title }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ URL($page->slug) }}" />
<meta property="og:image" content="{{ uploaded_asset($page->meta_image) }}" />
<meta property="og:description" content="{{ $page->meta_description }}" />
<meta property="og:site_name" content="{{ env('APP_NAME') }}" />
@endsection--}}

@section('content')
<section class="contact-banner">
    <div class="contact-overlay">
        <div class="container">
            <div class="banner-content">

                <h1>Contact Us</h1>

                <ul class="breadcrumb justify-content-center bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}">Home</a>
                    </li>

                    <li class="breadcrumb-item active">
                        Contact Us
                    </li>
                </ul>

            </div>
        </div>
    </div>
</section>

<section class="contact-section py-5">
    <div class="container">
        <div class="row g-5 align-items-start contactdetail">

            <!-- Left Side -->
            <div class="col-lg-5">

                <h2 class="contact-heading">
                    We'd <span>Love</span> to Hear From You
                </h2>

                <p class="contact-text">
                    Have any questions about our furniture, custom orders, or shipping?
                    Reach out to us and we'll get back to you as soon as possible.
                </p>

                <!-- Office -->
                <div class="info-card">
                    <div class="icon-box">
                        <img src="{{ static_asset('assets/img/LocationT.png') }}" alt="Location">
                    </div>

                    <div>
                        <h5>Our Office</h5>

                        <p>
                            20 Wenlock Road London,
                            England, N1 7GU
                        </p>

                        <small>
                            <strong class="reg">Registered VAT NO:</strong>
                            519774256
                        </small>
                    </div>
                </div>

                <!-- Whatsapp -->
                <div class="info-card">
                    <div class="icon-box">
                        <img src="{{ static_asset('assets/img/whatsapT.png') }}" alt="whatsapp">
                    </div>

                    <div>
                        <h5>WhatsApp Chat</h5>

                        <a href="https://wa.me/447751510365">
                            +44 7751 510365
                        </a>
                    </div>
                </div>

                <!-- Email -->
                <div class="info-card">
                    <div class="icon-box">
                        <img src="{{ static_asset('assets/img/EmailT.png') }}" alt="Email">
                    </div>

                    <div>
                        <h5>Email Support</h5>

                        <a href="mailto:askus@timetofurnish.com">
                            askus@timetofurnish.com
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Side -->
            <div class="col-lg-7">

                <div class="contact-form-box">

                    <h3>
                        Send us a <span>message</span>
                    </h3>

                    <p class="mb-4">
                        Tell us how we can help, and we'll respond soon.
                    </p>

                    <form action="{{ route('contact_us.submit') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>First Name*</label>

                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Last Name*</label>

                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email Address*</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Phone Number*</label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="form-control"
                                    required>
                            </div>

                            <div class="col-12 mb-4">
                                <label>Your Message*</label>

                                <textarea
                                    rows="5"
                                    name="message"
                                    class="form-control"
                                    required></textarea>
                            </div>

                            <div class="col-12">

                                <button class="contact-btn w-100">
                                    Subscribe
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

<style>
    .contact-section {
        background: #FAF8F5;
        padding: 70px 0;
    }

    .contact-section .container {
        max-width: 1180px;
    }

    .contact-heading {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        line-height: 42px;
        font-weight: 700;
        color: #221a16;

        max-width: 360px;
    }

    .contact-heading span {
        color: #c78c43;
    }

    .contact-text {
        max-width: 420px;
        font-size: 15px;
        line-height: 21px;
        color: #666;
        margin-bottom: 28px;
    }

    .info-card {
        display: flex;
        align-items: center;
        gap: 16px;
        border: 1px solid #e6ddd4;
        border-radius: 8px;

        padding: 18px 20px;
        margin-bottom: 28px;
        min-height: 82px;
    }

    .icon-box {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        background: #f5ebdf;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9d7754;
        font-size: 18px;
        flex-shrink: 0;
    }

    .info-card h5 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .info-card p,
    .info-card a {
        font-size: 14px;
        color: #666;
        margin: 0;
        text-decoration: none;
    }

    .info-card small {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        text-decoration: underline;

    }

    .contact-form-box {

        border: 1px solid #e6ddd4;
        border-radius: 8px;
        padding: 28px;
    }

    .contact-form-box h3 {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .contact-form-box h3 span {
        color: #c78c43;
    }

    .contact-form-box p {
        font-size: 15px;
        color: #666;
        margin-bottom: 22px;
    }

    .contact-form-box label {
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .contact-form-box .form-control {
        height: 42px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: none;
        font-size: 14px;
        background: transparent;
    }

    .contact-form-box textarea.form-control {
        height: 90px !important;
    }

    .contact-btn {
        width: 100%;
        height: 46px;
        border: none;
        border-radius: 5px;
        background: #756657;
        color: #fff;
        font-size: 15px;
        font-weight: 500;
    }

    .contact-btn:hover {
        background: #67584c;
    }

    .icon-box {
        width: 40px;
        height: 40px;
        background: #F7ECDD;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-box i {
        font-size: 18px;
        color: #9A7855;
    }

    .reg {
        font-weight: 600;
    }

    .contact-banner {
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

    .contactdetail {
        margin: 0 122px;
    }

    .contactdetail>.col-lg-5 {
        padding-right: 45px;
    }

    .contactdetail>.col-lg-7 {
        padding-left: 16px;
    }

    @media (max-width: 767px) {
        .contactdetail {
            margin: 0;
        }

        .contactdetail>.col-lg-5 {
            padding: 0px;
        }

        .info-card {
            padding: 7px 7px;
        }

        .contactdetail>.col-lg-7 {
            padding: 0px;
        }

        .contact-form-box {
            padding: 20px 15px;

        }

        .contact-form-box h3 {
            font-size: 20px;
        }

        .contact-heading {
            font-size: 22px;
        }
    }
</style>
@endsection