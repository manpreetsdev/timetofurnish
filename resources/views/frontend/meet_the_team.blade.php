@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Meet the Team') }} - {{ get_setting('website_name') }}@stop
@section('meta_description'){{ translate('Meet the team behind the brand and learn more about their experience and expertise.') }}@stop

@php
    $banner = [
        'title' => 'Meet Our Team',
        'breadcrumb_label' => 'Meet Our Team',
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
<?php
$bannerImage = get_setting('team_members_banner_image');
$bannerBg = $bannerImage
    ? (is_numeric($bannerImage) ? uploaded_asset($bannerImage) : asset($bannerImage))
    : asset('assets/img/team/team-banner.png');

$bannerTitle = get_setting('team_members_banner_title', translate('Meet Our Team'));
$bannerSubtitle = get_setting('team_members_banner_subtitle', translate('Discover the people who keep Time To Furnish moving.'));
$bannerDescription = get_setting('team_members_banner_desc', '');

$introSubtitle = get_setting('team_members_intro_subtitle', translate('Welcome from the Managing Director'));

$introBody = get_setting('team_members_intro_body', "My name is Mrs. H. Kaur, and it is my pleasure to welcome you to a company built on generations of craftsmanship, dedication, and trust.

Our story began in the early 1980s when my father established a furniture business alongside a sawmill in North India. For more than two decades, he devoted himself to the furniture and timber industry, earning a reputation for exceptional quality, honest workmanship, and outstanding customer service. Growing up surrounded by skilled craftsmen, premium timber, and furniture manufacturing gave me not only invaluable industry knowledge but also a lifelong passion for creating beautiful furniture.

Inspired by my father's legacy, I envisioned building a business that would connect trusted furniture manufacturers directly with customers. As technology transformed the retail industry, we recognised the opportunity to create a modern marketplace that removes unnecessary barriers between manufacturers and buyers.

That vision became Time To Furnish.

Today, Time To Furnish is a dedicated online furniture marketplace committed to bringing the finest UK furniture manufacturers together with customers across the country. Our goal is simple—to make high-quality furniture more accessible, more affordable, and easier to purchase than ever before.

By streamlining the buying process, we also help manufacturers receive faster payments, reducing delays from months to just days. This allows them to focus on what they do best—designing and crafting exceptional furniture while continuing to invest in quality and innovation.

To provide a complete customer experience, we proudly work with some of the UK's leading delivery partners, ensuring every order is delivered safely, efficiently, and professionally. From bedrooms and living rooms to dining spaces and home offices, we offer a carefully selected collection of furniture designed to suit every style and budget. Professional room-of-choice delivery and assembly services are also available for added convenience.

At Time To Furnish, we believe that everyone deserves stylish, durable, and affordable furniture without compromising on quality. Every product on our platform reflects our commitment to craftsmanship, value, customer satisfaction, and innovation. On behalf of the entire Time To Furnish team, thank you for choosing us. We are honoured to be part of your home furnishing journey and look forward to helping you create beautiful living spaces for many years to come.");

$introSignature = get_setting('team_members_intro_signature', "Mrs. H. Kaur\nManaging Director\n(Time To Furnish)");
?>

<style>
    .team-page {
        --team-bg: #f8f4ef;
        --team-surface: #ffffff;
        --team-primary: #a77951;
        --team-primary-dark: #39322a;
        --team-text: #39322a;
        --team-muted: #74695d;
        --team-line: #eadfd4;
        --team-shadow: 0 18px 45px rgba(57, 50, 42, 0.08);
    }

    .team-hero {
        position: relative;
        overflow: hidden;
        padding: 82px 0 74px;
        color: #fff;
        background: linear-gradient(135deg, rgba(38, 30, 23, 0.78), rgba(104, 91, 78, 0.72)),
        url('{{ $bannerBg }}');
        background-size: cover;
        background-position: center;
    }

    .team-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at top right, rgba(218, 203, 188, 0.24), transparent 32%),
            radial-gradient(circle at bottom left, rgba(255, 255, 255, 0.12), transparent 26%);
        pointer-events: none;
    }

    .team-hero .container {
        position: relative;
        z-index: 1;
    }

    .hero-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        color: rgba(255, 255, 255, 0.92);
        text-transform: uppercase;
        letter-spacing: 0.09em;
        font-size: 0.74rem;
        font-weight: 700;
        backdrop-filter: blur(10px);
        margin-bottom: 18px;
    }

    .hero-title {
        color: #fff;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.02;
        margin-bottom: 14px;
        font-family: playfair display;
        text-align: center;
    }

    .hero-subtitle,
    .hero-description {
        color: rgba(255, 255, 255, 0.9);
        max-width: 48rem;
    }

    .team-content {
        background: linear-gradient(180deg, #f8f4ef 0%, #f5efe8 100%);
        padding: 52px 0 72px;
    }

    .section-kicker {
        text-transform: uppercase;
        letter-spacing: 0.18em;
        font-size: 0.72rem;
        font-weight: 800;
        color: var(--team-primary);
        margin-bottom: 10px;
    }

    .section-title {
        color: var(--team-text);
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 10px;
    }

    .section-subtitle {
        color: var(--team-muted);
        max-width: 54rem;
        margin-left: auto;
        margin-right: auto;
    }

    /*
    |--------------------------------------------------------------------------
    | INTRODUCTION SECTION
    |--------------------------------------------------------------------------
    */
    .intro-panel {
        position: relative;

        border: 1px solid #E5E0DB;
        padding: 40px;
        margin-bottom: 50px;
        overflow: visible;
        border-radius: 10px;
    }

    .intro-hero {
        position: relative;
        padding: 0;

        border-bottom: 0;
    }

    .intro-hero-copy {
        position: relative;
        z-index: 1;
        max-width: none;
        width: 100%;
        text-align: left;
        color: var(--team-primary-dark);
        margin: 0;
    }

    .intro-heading-wrapper {
        position: absolute;
        top: -18px;
        left: 28px;

        padding: 0 14px;
        z-index: 20;
    }



    .intro-heading-label {
        display: block;
        background: #f8f4ef;
        font-family: "Playfair Display", serif;
        font-size: 28px;
        font-weight: 700;
        line-height: 1;
        color: #393939;
    }

    .director-title {
        margin: 0;
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        line-height: 50px;
        letter-spacing: 0;
        color: #393939;
        margin-bottom: 13px;
    }

    .director-title-first-letter {
        color: #C27325;
    }

    .director-subtitle {
        display: none;
    }

    .intro-body {
        padding: 0;

    }

    .intro-body-subtitle {
        display: none;
    }

    .decor-line {
        display: none;
    }

    .intro-message {
        max-width: none;
        margin: 0;
        position: relative;
        padding: 0;
    }

    .intro-message-body {
        color: #393939;
        line-height: 1.85;
        text-align: left;
        font-size: 1rem;
        font-family: "Poppins", sans-serif;
    }

    .intro-message-body p {
        margin: 0 0 22px;
    }

    .intro-message-body p:last-child {
        margin-bottom: 0;
    }

    .intro-message-body strong {
        font-weight: 700;
    }

    .intro-message-body .intro-opening {
        font-weight: 600;
    }

    .intro-message-body .intro-heading {
        display: block;
        margin: 24px 0 12px;
        color: var(--team-text);
        font-family: "Playfair Display", Georgia, serif;
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.3;
    }

    .signature-block {
        margin-top: 20px;
        padding-top: 0;
        border-top: none;
        text-align: left;
        white-space: pre-line;
        color: #393939;
        font-size: 18px;
        font-weight: 700;
        line-height: 33px;
        letter-spacing: 0;
    }

    .team-grid {
        margin-top: 12px;
    }

    .team-carousel-item {
        height: 100%;
    }

    .team-member-card {
        position: relative;
        height: 100%;
        border-radius: 20px;
        background: #fff;
        border: 1px solid var(--team-line);
        box-shadow: 0 12px 30px rgba(57, 50, 42, 0.06);
        overflow: hidden;
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .team-member-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 44px rgba(57, 50, 42, 0.12);
        border-color: #d7bea5;
    }

    .team-member-card-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0;
        padding: 30px 24px 24px;
        text-align: center;
        min-height: 100%;
    }

    .team-member-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        width: 100%;
    }

    .team-member-monogram {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        flex: 0 0 76px;
        display: grid;
        place-items: center;
        background: #fff;
        color: var(--team-primary-dark);
        font-size: 1.8rem;
        font-weight: 800;
        border: 1px solid #b58c67;
        box-shadow: 0 8px 18px rgba(57, 50, 42, 0.06);
        overflow: hidden;
    }

    .team-member-monogram img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }

    .team-member-heading {
        min-width: 0;
        width: 100%;
        display: grid;
        gap: 5px;
    }

    .team-member-name {
        margin-bottom: 0;
        color: var(--team-text);
        font-size: 1.1rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .team-member-email {
        margin: 0 auto;
        color: var(--team-primary);
        font-size: 0.9rem;
        font-weight: 600;
        word-break: break-word;
    }

    .team-member-designation {
        color: var(--team-muted);
        font-weight: 500;
        font-size: 0.92rem;
        margin-bottom: 0;
    }

    .team-member-body {
        display: grid;
        gap: 10px;
        width: 100%;
    }

    .team-member-text,
    .team-member-bio,
    .team-member-contact {
        color: #4d443c;
        font-size: 0.95rem;
        line-height: 1.75;
        margin-bottom: 0;
    }

    .team-member-divider {
        width: 44px;
        height: 1px;
        background: #c3a17e;
        margin: 0 auto;
    }

    .team-empty {
        border: 0;
        border-radius: 18px;
        background: rgba(104, 91, 78, 0.08);
        color: var(--team-primary-dark);
    }

    @media (max-width: 991.98px) {
        .team-hero {
            padding: 74px 0 66px;
        }

        .team-content {
            padding: 44px 0 60px;
        }

        .team-member-card-inner {
            padding: 26px 20px 22px;
        }
    }

    @media (max-width: 767.98px) {
        .intro-panel {
            border-radius: 10px;
            padding: 10px;
        }

        .intro-hero {
            padding: 0;
        }

        .intro-body {
            padding: 0;
        }

        .director-title {
            font-size: 1.55rem;
        }

        .intro-message-body {
            font-size: 0.95rem;
            line-height: 1.8;
        }

        .intro-message-body .intro-heading {
            font-size: 1.3rem;
        }

        .signature-block {
            font-size: 0.9rem;
        }

        .team-member-card {
            min-height: 0;
        }

        .team-member-card-inner {
            gap: 12px;
            padding: 24px 18px 20px;
        }

        .team-member-monogram {
            width: 74px;
            height: 74px;
            flex-basis: 74px;
            font-size: 1.55rem;
        }

        .team-member-name {
            font-size: 1.1rem;
        }

        .team-member-designation {
            font-size: 0.92rem;
        }
    }

    @media (min-width: 768px) {
        .team-mobile-carousel-wrap {
            display: none;
        }
    }

    @media (max-width: 767.98px) {
        .team-desktop-grid {
            display: none;
        }

        .team-mobile-carousel-wrap {
            display: block;
        }

        .team-mobile-carousel-wrap .slick-list {
            border-radius: 20px;
        }

        .team-mobile-carousel-wrap .slick-track {
            display: flex !important;
            align-items: stretch !important;
        }

        .team-mobile-carousel-wrap .slick-slide {
            height: auto !important;
            display: flex !important;
        }

        .team-mobile-carousel-wrap .slick-slide>div {
            display: flex !important;
            flex: 1 1 auto !important;
            width: 100% !important;
        }

        .team-mobile-carousel-wrap .team-carousel-item {
            display: flex;
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .team-hero {
            padding: 62px 0 58px;
        }

        .hero-title {
            font-size: 2rem;
        }

        .section-title {
            font-size: 1.45rem;
        }

        .director-title {
            font-size: 1.35rem;
        }
    }

    .team-hero {
        position: relative;
        text-align: center;
    }

    .team-hero .container {
        position: relative;
        z-index: 2;
    }

    .hero-title {
        font-family: "Playfair Display", serif;
        font-size: 40px;
        font-weight: 700;
        color: #fff;
        margin-bottom: 10px;
    }

    .hero-subtitle {
        display: none;
    }

    .team-hero .breadcrumb {
        justify-content: center;
    }

    .team-hero .breadcrumb-item,
    .team-hero .breadcrumb-item a {
        color: #fff !important;
        font-size: 18px;
        font-weight: 500;
        text-decoration: none;
        font-family: Be Vietnam Pro;
    }

    .team-hero .breadcrumb-item+.breadcrumb-item::before {
        color: #fff;
        content: "»";
    }
</style>

<div class="team-page">

  {{-- <section class="team-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-12">
                    <div class="hero-pill">
                        
                    </div>

                    <h1 class="display-5 hero-title">
                        {{ $bannerTitle }}
                    </h1>

                    <p class="lead hero-subtitle mb-3">
                        {{ $bannerSubtitle }}
                    </p>

                    @if($bannerDescription)
                    <p class="hero-description mb-4">
                        {{ $bannerDescription }}
                    </p>
                    @endif

                    <nav aria-label="breadcrumb" class="d-flex justify-content-center">
                        <ol class="breadcrumb bg-transparent px-0 mb-0"
                            style="--bs-breadcrumb-divider: '»';">

                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-white">
                                    {{ translate('Home') }}
                                </a>
                            </li>

                            <li class="breadcrumb-item active text-white"
                                aria-current="page">
                                {{ translate('Meet the Team') }}
                            </li>

                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>--}}
	{{-- Global Banner --}}
@include('frontend.custom-pages.partials.banner', [
    'banner' => $banner
])

    <section class="team-content">

        <div class="container">

            <div class="intro-panel1 mb-5">

                <div class="intro-panel">

                    <div class="intro-heading-wrapper">
                        <span class="intro-heading-label">
                            {{ translate('Mrs H Kaur') }}
                        </span>
                    </div>

                    <div class="intro-hero">

                        <div class="intro-hero-copy">

                            <h3 class="director-title">
                                A <span class="director-title-first-letter">Message</span>
                                from our Managing Director
                            </h3>

                        </div>

                    </div>


                    <div class="intro-body">

                        <div class="intro-message">

                            <div class="intro-message-body">

                                <p class="intro-opening">
                                    My name is Mrs. H. Kaur, and it is my pleasure to welcome you to a company built on generations of craftsmanship, dedication, and trust.
                                </p>

                                <p>
                                    Our story began in the early 1980s when my
                                    <strong>father</strong>
                                    established a furniture business alongside a sawmill in North India. For more than two decades, he devoted himself to the furniture and timber industry, earning a reputation for exceptional quality, honest workmanship, and outstanding customer service. Growing up surrounded by skilled craftsmen, premium timber, and furniture manufacturing gave me not only invaluable industry knowledge but also a lifelong passion for creating beautiful furniture.
                                </p>

                                <p>
                                    Inspired by my
                                    <strong>father's legacy</strong>,
                                    I envisioned building a business that would connect trusted furniture manufacturers directly with customers. As technology transformed the retail industry, we recognised the opportunity to create a modern marketplace that removes unnecessary barriers between manufacturers and buyers.
                                </p>

                                <span class="intro-heading">
                                    That vision became Time To Furnish.
                                </span>

                                <p>
                                    Today, Time To Furnish is a dedicated online furniture marketplace committed to bringing the finest UK furniture manufacturers together with customers across the country. Our goal is simple—to make high-quality furniture more accessible, more affordable, and easier to purchase than ever before.
                                </p>

                                <p>
                                    By streamlining the buying process, we also help manufacturers receive faster payments, reducing delays from months to just days. This allows them to focus on what they do best—designing and crafting exceptional furniture while continuing to invest in quality and innovation.
                                </p>

                                <p>
                                    To provide a complete
                                    <strong>customer experience</strong>,
                                    we proudly work with some of the UK's leading delivery partners, ensuring every order is delivered safely, efficiently, and professionally. From
                                    <strong>bedrooms and living rooms</strong>
                                    to dining spaces and
                                    <strong>home offices</strong>,
                                    we offer a carefully selected collection of furniture designed to suit every style and budget. Professional room-of-choice delivery and assembly services are also available for added convenience.
                                </p>

                                <p>
                                    At Time To Furnish, we believe that everyone deserves stylish, durable, and affordable furniture without compromising on quality. Every product on our platform reflects our commitment to craftsmanship, value, customer satisfaction, and innovation. On behalf of the entire Time To Furnish team, thank you for choosing us. We are honoured to be part of your home furnishing journey and look forward to helping you create beautiful living spaces for many years to come.
                                </p>

                            </div>

                        </div>

                        <div class="signature-block">
                            {{ $introSignature }}
                        </div>

                    </div>

                </div>

                <div class="row justify-content-center mb-4">

                    <div class="col-xl-9 col-lg-10 text-center">

                        <p class="section-kicker">
                            {{ translate('Meet our dedicated team') }}
                        </p>

                        <h2 class="section-title">
                            {{ translate('Team Members') }}
                        </h2>

                        <div class="decor-line">
                            <span></span>
                        </div>

                    </div>

                </div>

                @if($team_members->isEmpty())

                <div class="alert team-empty mb-0">
                    {{ translate('No team members have been added yet.') }}
                </div>

                @else

                <div class="row g-4 team-grid team-desktop-grid"
                    style="row-gap: 1.5rem;">

                    @foreach($team_members as $member)

                    @php

                    $initial = strtoupper(substr($member->name, 0, 1));

                    $bio = $member->bio
                    ?: translate('No biography added yet.');

                    $photoUrl = null;

                    if ($member->photo) {

                    if (is_numeric($member->photo)) {

                    $photoUrl = uploaded_asset($member->photo);

                    } elseif (file_exists(public_path($member->photo))) {

                    $photoUrl = asset($member->photo);

                    }

                    }

                    @endphp

                    <div class="col-12 col-md-6 col-lg-4">

                        <article class="team-member-card h-100">

                            <div class="team-member-card-inner">

                                <div class="team-member-header">

                                    <div class="team-member-monogram">

                                        @if($photoUrl)

                                        <img src="{{ $photoUrl }}"
                                            alt="{{ $member->name }}"
                                            class="img-fluid rounded-circle">

                                        @else

                                        {{ $initial }}

                                        @endif

                                    </div>

                                    <div class="team-member-heading">

                                        <h3 class="team-member-name">
                                            {{ $member->name }}
                                        </h3>

                                        @if($member->email)

                                        <p class="team-member-email">
                                            {{ $member->email }}
                                        </p>

                                        @endif

                                        <div class="team-member-designation">
                                            {{ $member->designation ?: translate('Team Member') }}
                                        </div>

                                        <div class="team-member-divider"></div>

                                    </div>

                                </div>

                                <div class="team-member-body">

                                    <p class="team-member-bio">
                                        {{ $bio }}
                                    </p>

                                </div>

                            </div>

                        </article>

                    </div>

                    @endforeach

                </div>


                <div class="team-mobile-carousel-wrap d-md-none">

                    <div class="aiz-carousel sm-gutters-16 arrow-none team-mobile-carousel"
                        data-items="1"
                        data-xl-items="1"
                        data-lg-items="1"
                        data-md-items="1"
                        data-sm-items="1"
                        data-xs-items="1"
                        data-arrows="false"
                        data-dots="true"
                        data-infinite="true"
                        data-autoplay="true">

                        @foreach($team_members as $member)

                        @php

                        $initial = strtoupper(substr($member->name, 0, 1));

                        $bio = $member->bio
                        ?: translate('No biography added yet.');

                        $photoUrl = null;

                        if ($member->photo) {

                        if (is_numeric($member->photo)) {

                        $photoUrl = uploaded_asset($member->photo);

                        } elseif (file_exists(public_path($member->photo))) {

                        $photoUrl = asset($member->photo);

                        }

                        }

                        @endphp

                        <div class="team-carousel-item">

                            <article class="team-member-card h-100">

                                <div class="team-member-card-inner">

                                    <div class="team-member-header">

                                        <div class="team-member-monogram">

                                            @if($photoUrl)

                                            <img src="{{ $photoUrl }}"
                                                alt="{{ $member->name }}"
                                                class="img-fluid rounded-circle">

                                            @else

                                            {{ $initial }}

                                            @endif

                                        </div>

                                        <div class="team-member-heading">

                                            <h3 class="team-member-name">
                                                {{ $member->name }}
                                            </h3>

                                            @if($member->email)

                                            <p class="team-member-email">
                                                {{ $member->email }}
                                            </p>

                                            @endif

                                            <div class="team-member-designation">
                                                {{ $member->designation ?: translate('Team Member') }}
                                            </div>

                                            <div class="team-member-divider"></div>

                                        </div>

                                    </div>

                                    <div class="team-member-body">

                                        <p class="team-member-bio">
                                            {{ $bio }}
                                        </p>

                                    </div>

                                </div>

                            </article>

                        </div>

                        @endforeach

                    </div>

                </div>

                @endif

            </div>

    </section>

</div>

@endsection