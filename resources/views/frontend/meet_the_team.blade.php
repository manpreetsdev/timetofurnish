@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Meet the Team') }} - {{ get_setting('website_name') }}@stop
@section('meta_description'){{ translate('Meet the team behind the brand and learn more about their experience and expertise.') }}@stop

@section('content')
<?php
    $bannerImage = get_setting('team_members_banner_image');
    $bannerBg = $bannerImage
        ? (is_numeric($bannerImage) ? uploaded_asset($bannerImage) : asset($bannerImage))
        : asset('assets/img/team/team-banner.png');

    $bannerTitle = get_setting('team_members_banner_title', translate('Meet Our Team'));
    $bannerSubtitle = get_setting('team_members_banner_subtitle', translate('Discover the people who keep Time To Furnish moving.'));
    $bannerDescription = get_setting('team_members_banner_desc', '');

    $introSubtitle = get_setting('team_members_intro_subtitle', translate('Welcome to Time To Furnish.'));
    $introBody = get_setting('team_members_intro_body', "My name is Mrs. H. Kaur, and I am proud to welcome you to a company built on generations of passion, craftsmanship, and trust. Our journey began in the early 1980s, when my father established a furniture business alongside a sawmill in North India. For over two decades, he dedicated his life to the furniture and timber industry, mastering the art of woodworking while earning a reputation for quality and integrity. Growing up around timber, furniture manufacturing, and skilled craftsmen gave me not only valuable knowledge but also a deep appreciation for fine furniture and the people who create it. Inspired by my father's legacy, I always dreamed of building something that would connect exceptional manufacturers directly with customers. As technology transformed the way people shop, we saw an opportunity to remove unnecessary barriers between manufacturers and buyers. That vision became Time To Furnish. Our mission is simple: to bring the UK's finest furniture manufacturers just one click away from every customer. We have created a platform where quality, affordability, and convenience come together. By simplifying the buying process, we also help manufacturers receive faster payments - reducing waiting times from months to just days - allowing them to focus on what they do best: creating outstanding furniture. We proudly partner with some of the UK's leading delivery companies to ensure your furniture arrives safely, quickly, and professionally. Whether you're furnishing your bedroom, living room, dining room, or any other space, we are committed to delivering beautiful, high-quality furniture directly to your doorstep, with professional installation available for your convenience. At Time To Furnish, we believe that everyone deserves stylish, durable, and affordable furniture without compromise. Every product reflects our commitment to craftsmanship, customer satisfaction, and innovation. Thank you for choosing Time To Furnish. We look forward to helping you create a home you'll love for years to come.");
    $introSignature = get_setting('team_members_intro_signature', "Mrs. H. Kaur\nManaging Director\nTime To Furnish");

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
        background:
            linear-gradient(135deg, rgba(38, 30, 23, 0.78), rgba(104, 91, 78, 0.72)),
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

    .intro-panel {
        border-radius: 22px;
        overflow: hidden;
        background: #fff;
        border: 1px solid var(--team-line);
        box-shadow: var(--team-shadow);
    }

    .intro-hero {
        position: relative;
        padding: 46px 36px 18px;
        background: #fff;
        border-bottom: 1px solid var(--team-line);
    }

    .intro-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
    }

    .intro-hero-copy {
        position: relative;
        z-index: 1;
        max-width: 820px;
        width: 100%;
        text-align: center;
        color: var(--team-primary-dark);
        margin: 0 auto;
    }

    .intro-hero-copy .eyebrow {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.84);
        color: var(--team-primary);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-size: 0.72rem;
        font-weight: 700;
        margin-bottom: 12px;
        border: 1px solid rgba(79, 69, 60, 0.1);
    }

    .director-title {
        margin: 0;
        font-size: 1.9rem;
        font-weight: 800;
        letter-spacing: -0.045em;
        line-height: 1.05;
        color: var(--team-text);
    }

    .director-subtitle {
        margin: 10px 0 0;
        color: var(--team-muted);
        font-size: 1rem;
    }

    .intro-hero-copy p {
        margin-bottom: 0;
        color: var(--team-muted);
        font-size: 0.98rem;
    }

    .intro-body {
        padding: 30px 36px 34px;
        background: #fff;
    }

    .intro-body-title {
        margin: 0 0 12px;
        color: var(--team-text);
        font-size: 0.86rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-align: center;
        text-transform: uppercase;
    }

    .intro-body-subtitle {
        margin: 0 0 14px;
        color: var(--team-text);
        font-size: 1.2rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        text-align: center;
    }

    .intro-message {
        max-width: 840px;
        margin: 0 auto;
        position: relative;
        padding: 12px 0 0;
    }

    .decor-line {
        width: 150px;
        height: 20px;
        margin: 14px auto 0;
        position: relative;
    }

    .decor-line::before,
    .decor-line::after {
        content: '';
        position: absolute;
        top: 10px;
        width: 60px;
        height: 1px;
        background: var(--team-primary);
        opacity: 0.72;
    }

    .decor-line::before {
        left: 0;
    }

    .decor-line::after {
        right: 0;
    }

    .decor-line span {
        width: 9px;
        height: 9px;
        background: var(--team-primary);
        display: block;
        transform: rotate(45deg);
        margin: 6px auto 0;
    }

    .intro-message-body {
        color: #4a4239;
        line-height: 2;
        text-align: center;
        font-size: 0.96rem;
    }

    .signature-block {
        margin: 24px auto 0;
        padding-top: 18px;
        border-top: 1px solid rgba(79, 69, 60, 0.1);
        text-align: center;
        white-space: pre-line;
        color: var(--team-primary-dark);
        font-weight: 700;
        line-height: 1.55;
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
            border-radius: 22px;
        }

        .intro-hero {
            padding: 30px 18px 14px;
        }

        .intro-body {
            padding: 22px 18px 26px;
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

        .director-title {
            font-size: 1.55rem;
        }

        .intro-message {
            padding-top: 10px;
        }

        .intro-message-body {
            font-size: 0.95rem;
            line-height: 1.9;
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

        .team-mobile-carousel-wrap .slick-slide > div {
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

        .intro-hero {
            padding: 26px 14px 12px;
        }

        .intro-body {
            padding: 20px 14px 24px;
        }

        .director-title {
            font-size: 1.35rem;
        }

        .intro-body-subtitle {
            font-size: 1.05rem;
        }
    }
</style>

<div class="team-page">
    <section class="team-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-8">
                    <div class="hero-pill">{{ translate('Meet the people behind the Time To Furnish') }}</div>
                    <h1 class="display-5 hero-title">{{ $bannerTitle }}</h1>
                    <p class="lead hero-subtitle mb-3">{{ $bannerSubtitle }}</p>
                    @if($bannerDescription)
                        <p class="hero-description mb-4">{{ $bannerDescription }}</p>
                    @endif
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent px-0 mb-0" style="--bs-breadcrumb-divider: '>'; ">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white text-decoration-none">{{ translate('Home') }}</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">{{ translate('Meet the Team') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="team-content">
        <div class="container">
            <!-- <div class="row justify-content-center mb-4">
                <div class="col-xl-9 col-lg-10 text-center">
                    <p class="section-kicker">{{ translate('Welcome') }}</p>
                    <h2 class="section-title">{{ translate('Welcome from the Managing Director') }}</h2>
                    <p class="section-subtitle">{{ translate('A clean introduction with the full message available on demand.') }}</p>
                </div>
            </div> -->

            <div class="intro-panel mb-5">
                <div class="intro-hero">
                    <div class="intro-hero-copy">
                        <div class="eyebrow">{{ translate('Managing Director') }}</div>
                        <h3 class="director-title">{{ translate('A Message from our Managing Director') }}</h3>
                        <p class="director-subtitle">{{ $introSubtitle }}</p>
                    </div>
                </div>

                <div class="intro-body">
                    <p class="intro-body-subtitle">{{ translate('Mrs H Kaur') }}</p>
                    <div class="decor-line"><span></span></div>
                    <div class="intro-message">
                        <div class="intro-message-body">{!! nl2br(e($introBody)) !!}</div>
                    </div>
                    <div class="signature-block">{{ $introSignature }}</div>
                </div>
            </div>

            <div class="row justify-content-center mb-4">
                <div class="col-xl-9 col-lg-10 text-center">
                    <p class="section-kicker">{{ translate('Meet our dedicated team') }}</p>
                    <h2 class="section-title">{{ translate('Team Members') }}</h2>
                    <div class="decor-line"><span></span></div>
                </div>
            </div>

            @if($team_members->isEmpty())
                <div class="alert team-empty mb-0">
                    {{ translate('No team members have been added yet.') }}
                </div>
            @else
                <div class="row g-4 team-grid team-desktop-grid" style="row-gap: 1.5rem;">
                    @foreach($team_members as $member)
                        @php
                            $initial = strtoupper(substr($member->name, 0, 1));
                            $bio = $member->bio ?: translate('No biography added yet.');
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
                                                <img src="{{ $photoUrl }}" alt="{{ $member->name }}" class="img-fluid rounded-circle">
                                            @else
                                                {{ $initial }}
                                            @endif
                                        </div>
                                        <div class="team-member-heading">
                                            <h3 class="team-member-name">{{ $member->name }}</h3>
                                            @if($member->email)
                                                <p class="team-member-email">{{ $member->email }}</p>
                                            @endif
                                            <div class="team-member-designation">{{ $member->designation ?: translate('Team Member') }}</div>
                                            <div class="team-member-divider"></div>
                                        </div>
                                    </div>

                                    <div class="team-member-body">
                                        <p class="team-member-bio">{{ $bio }}</p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="team-mobile-carousel-wrap d-md-none">
                    <div class="aiz-carousel sm-gutters-16 arrow-none team-mobile-carousel"
                        data-items="1" data-xl-items="1" data-lg-items="1" data-md-items="1"
                        data-sm-items="1" data-xs-items="1" data-arrows="false"
                        data-dots="true" data-infinite="true" data-autoplay="true">
                        @foreach($team_members as $member)
                        @php
                            $initial = strtoupper(substr($member->name, 0, 1));
                            $bio = $member->bio ?: translate('No biography added yet.');
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
                                                    <img src="{{ $photoUrl }}" alt="{{ $member->name }}" class="img-fluid rounded-circle">
                                                @else
                                                    {{ $initial }}
                                                @endif
                                            </div>
                                            <div class="team-member-heading">
                                                <h3 class="team-member-name">{{ $member->name }}</h3>
                                                @if($member->email)
                                                    <p class="team-member-email">{{ $member->email }}</p>
                                                @endif
                                                <div class="team-member-designation">{{ $member->designation ?: translate('Team Member') }}</div>
                                                <div class="team-member-divider"></div>
                                            </div>
                                        </div>

                                        <div class="team-member-body">
                                            <p class="team-member-bio">{{ $bio }}</p>
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
