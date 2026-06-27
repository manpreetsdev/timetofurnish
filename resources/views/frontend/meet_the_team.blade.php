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

    $introTitle = get_setting('team_members_intro_title', translate('Welcome from the Managing Director'));
    $introSubtitle = get_setting('team_members_intro_subtitle', translate('Welcome to Time To Furnish.'));
    $introBody = get_setting('team_members_intro_body', "My name is Mrs. H. Kaur, and I am proud to welcome you to a company built on generations of passion, craftsmanship, and trust. Our journey began in the early 1980s, when my father established a furniture business alongside a sawmill in North India. For over two decades, he dedicated his life to the furniture and timber industry, mastering the art of woodworking while earning a reputation for quality and integrity. Growing up around timber, furniture manufacturing, and skilled craftsmen gave me not only valuable knowledge but also a deep appreciation for fine furniture and the people who create it. Inspired by my father's legacy, I always dreamed of building something that would connect exceptional manufacturers directly with customers. As technology transformed the way people shop, we saw an opportunity to remove unnecessary barriers between manufacturers and buyers. That vision became Time To Furnish. Our mission is simple: to bring the UK's finest furniture manufacturers just one click away from every customer. We have created a platform where quality, affordability, and convenience come together. By simplifying the buying process, we also help manufacturers receive faster payments - reducing waiting times from months to just days - allowing them to focus on what they do best: creating outstanding furniture. We proudly partner with some of the UK's leading delivery companies to ensure your furniture arrives safely, quickly, and professionally. Whether you're furnishing your bedroom, living room, dining room, or any other space, we are committed to delivering beautiful, high-quality furniture directly to your doorstep, with professional installation available for your convenience. At Time To Furnish, we believe that everyone deserves stylish, durable, and affordable furniture without compromise. Every product reflects our commitment to craftsmanship, customer satisfaction, and innovation. Thank you for choosing Time To Furnish. We look forward to helping you create a home you'll love for years to come.");
    $introSignature = get_setting('team_members_intro_signature', "Mrs. H. Kaur\nManaging Director\nTime To Furnish");
    $introExcerpt = \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', $introBody)), 360);

?>

<style>
    .team-page {
        --team-bg: #f6f1ea;
        --team-surface: #ffffff;
        --team-primary: #4f453c;
        --team-primary-dark: #2e2720;
        --team-text: #2a241d;
        --team-muted: #6f655b;
        --team-line: rgba(79, 69, 60, 0.12);
        --team-shadow: 0 12px 30px rgba(52, 39, 28, 0.06);
    }

    .team-hero {
        position: relative;
        overflow: hidden;
        padding: 88px 0 78px;
        color: #fff;
        background:
            linear-gradient(135deg, rgba(31, 24, 18, 0.68), rgba(104, 91, 78, 0.76)),
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
        background:
            radial-gradient(circle at top left, rgba(218, 203, 188, 0.16), transparent 24%),
            linear-gradient(180deg, #f8f4ef 0%, #f3ede7 100%);
        padding: 54px 0 72px;
    }

    .section-kicker {
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 0.78rem;
        font-weight: 800;
        color: var(--team-primary);
        margin-bottom: 10px;
    }

    .section-title {
        color: var(--team-text);
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-bottom: 10px;
    }

    .section-subtitle {
        color: var(--team-muted);
        max-width: 54rem;
        margin-left: auto;
        margin-right: auto;
    }

    .intro-panel {
        border-radius: 24px;
        overflow: hidden;
        background: var(--team-surface);
        border: 1px solid var(--team-line);
        box-shadow: var(--team-shadow);
    }

    .intro-hero {
        position: relative;
        min-height: 250px;
        background:
            linear-gradient(135deg, rgba(45, 36, 28, 0.08), rgba(104, 91, 78, 0.22)),
            url('{{ static_asset('assets/img/team/team-banner.png') }}');
        background-size: cover;
        background-position: center;
    }

    .intro-hero::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.02) 0%, rgba(39, 31, 24, 0.46) 100%);
    }

    .intro-hero-copy {
        position: absolute;
        inset: auto 0 0 0;
        z-index: 1;
        padding: 22px 24px 22px;
        color: #fff;
    }

    .intro-hero-copy .eyebrow {
        display: inline-flex;
        padding: 7px 11px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.15);
        letter-spacing: 0.08em;
        text-transform: uppercase;
        font-size: 0.72rem;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .intro-hero-copy h3 {
        margin-bottom: 4px;
        font-size: 1.55rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1.08;
    }

    .intro-hero-copy p {
        margin-bottom: 0;
        color: rgba(255, 255, 255, 0.92);
    }

    .intro-body {
        padding: 22px 24px 24px;
    }

    .intro-body-title {
        margin: 0;
        color: var(--team-text);
        font-size: 1.06rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .intro-body-subtitle {
        margin: 4px 0 0;
        color: var(--team-muted);
        font-size: 0.95rem;
    }

    .intro-body-summary {
        color: #4c433b;
        line-height: 1.8;
        font-size: 0.98rem;
        margin-bottom: 14px;
        max-width: 70rem;
    }

    .intro-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 14px;
    }

    .intro-chip {
        padding: 8px 12px;
        border-radius: 999px;
        background: #f4ede4;
        color: var(--team-primary-dark);
        font-size: 0.84rem;
        font-weight: 700;
        border: 1px solid rgba(104, 91, 78, 0.08);
    }

    .intro-details {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid rgba(104, 91, 78, 0.12);
    }

    .intro-details summary {
        cursor: pointer;
        color: var(--team-primary-dark);
        font-weight: 800;
        list-style: none;
    }

    .intro-details summary::-webkit-details-marker {
        display: none;
    }

    .intro-details-body {
        color: #4c433b;
        line-height: 1.9;
        margin-top: 12px;
        
    }

    .signature-block {
        margin-top: 14px;
        padding-left: 14px;
        border-left: 3px solid rgba(104, 91, 78, 0.18);
        
        color: var(--team-primary-dark);
        font-weight: 700;
        line-height: 1.5;
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
        border-radius: 24px;
        background: #fbf8f3;
        border: 1px solid rgba(79, 69, 60, 0.14);
        box-shadow: 0 14px 34px rgba(52, 39, 28, 0.05);
        overflow: hidden;
    }

    .team-member-card-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0px;
        padding: 28px 26px 26px;
        text-align: center;
        min-height: 100%;
    }

    .team-member-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        width: 100%;
    }

    .team-member-monogram {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        flex: 0 0 88px;
        display: grid;
        place-items: center;
        background: #e3d7c7;
        color: var(--team-primary-dark);
        font-size: 2rem;
        font-weight: 800;
        border: 4px solid rgba(79, 69, 60, 0.12);
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.35);
    }

    .team-member-heading {
        min-width: 0;
        width: 100%;
        display: grid;
        gap: 6px;
    }

    .team-member-name {
        margin-bottom: 0;
        color: var(--team-text);
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .team-member-designation {
        color: var(--team-muted);
        font-weight: 500;
        font-size: 1.02rem;
        margin-bottom: 0;
    }

    .team-member-body {
        display: grid;
        gap: 12px;
        width: 100%;
        margin-top: 4px;
    }

    .team-member-text,
    .team-member-bio,
    .team-member-contact {
        color: #4d443c;
        font-size: 0.95rem;
        line-height: 1.75;
        margin-bottom: 0;
    }

    .team-member-label {
        color: var(--team-muted);
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .team-member-divider {
        height: 1px;
        background: rgba(79, 69, 60, 0.12);
        width: 100%;
        margin: 2px 0;
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
            padding: 46px 0 64px;
        }

        .team-member-card-inner {
            padding: 24px 20px 22px;
        }
    }

    @media (max-width: 767.98px) {
        .intro-panel {
            border-radius: 22px;
        }

        .intro-hero {
            min-height: 220px;
        }

        .intro-body {
            padding: 20px 18px 20px;
        }

        .team-member-card {
            min-height: 0;
        }

        .team-member-card-inner {
            gap: 14px;
            padding: 22px 18px 20px;
        }

        .team-member-monogram {
            width: 72px;
            height: 72px;
            flex-basis: 72px;
            font-size: 1.55rem;
        }

        .team-member-name {
            font-size: 1.35rem;
        }

        .team-member-designation {
            font-size: 0.98rem;
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
    }
</style>

<div class="team-page">
    <section class="team-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-8">
                    <div class="hero-pill">{{ translate('Meet the people behind the brand') }}</div>
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
            <div class="row justify-content-center mb-4">
                <div class="col-xl-9 col-lg-10 text-center">
                    <p class="section-kicker">{{ translate('Welcome') }}</p>
                    <h2 class="section-title">{{ translate('Welcome from the Managing Director') }}</h2>
                    <p class="section-subtitle">{{ translate('A clean introduction with the full message available on demand.') }}</p>
                </div>
            </div>

            <div class="intro-panel mb-5">
                <div class="intro-hero">
                    <div class="intro-hero-copy">
                        <div class="eyebrow">{{ translate('Managing Director') }}</div>
                        <h3>{{ $introTitle }}</h3>
                        <p>{{ $introSubtitle }}</p>
                    </div>
                </div>

                <div class="intro-body">
                    <div class="intro-body-title">{{ translate('A message from Mrs. H. Kaur') }}</div>
                    <p class="intro-body-subtitle">{{ translate('Welcome to Time To Furnish.') }}</p>
                    <div class="intro-body-summary">{{ $introExcerpt }}</div>

                    <div class="intro-meta">
                        <span class="intro-chip">{{ translate('Craftsmanship') }}</span>
                        <span class="intro-chip">{{ translate('Trust') }}</span>
                        <span class="intro-chip">{{ translate('Customer Focus') }}</span>
                    </div>

                    <details class="intro-details">
                        <summary>{{ translate('Read full message') }}</summary>
                        <div class="intro-details-body">{!! nl2br(e($introBody)) !!}</div>
                    </details>

                    <div class="signature-block">{{ $introSignature }}</div>
                </div>
            </div>

            <div class="row justify-content-center mb-4">
                <div class="col-xl-9 col-lg-10 text-center">
                    <p class="section-kicker">{{ translate('Meet our dedicated team') }}</p>
                    <h2 class="section-title">{{ translate('Team Members') }}</h2>
                    <p class="section-subtitle">{{ translate('Simple cards with the key details visible on every screen size.') }}</p>
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
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4">
                            <article class="team-member-card h-100">
                                <div class="team-member-card-inner">
                                    <div class="team-member-header">
                                        <div class="team-member-monogram">{{ $initial }}</div>
                                        <div class="team-member-heading">
                                            <h3 class="team-member-name">{{ $member->department ?: $member->name }}</h3>
                                            <div class="team-member-designation">{{ $member->designation ?: $member->name }}</div>
                                        </div>
                                    </div>

                                    <div class="team-member-body">
                                        @if($member->email)
                                            <div class="team-member-contact">{{ $member->email }}</div>
                                        @endif
                                        <div class="team-member-divider"></div>
                                        <p class="team-member-bio">{{ $bio }}</p>

                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="team-mobile-carousel-wrap d-md-none">
                    <div class="aiz-carousel sm-gutters-16 offer-inner-carousel arrow-none team-mobile-carousel"
                        data-items="3" data-xl-items="3" data-lg-items="3" data-md-items="2"
                        data-sm-items="2" data-xs-items="1" data-arrows="false"
                        data-dots="true" data-infinite="true" data-autoplay="true">
                        @foreach($team_members as $member)
                            @php
                                $initial = strtoupper(substr($member->name, 0, 1));
                                $bio = $member->bio ?: translate('No biography added yet.');
                            @endphp
                            <div class="team-carousel-item">
                                <article class="team-member-card h-100">
                                    <div class="team-member-card-inner">
                                        <div class="team-member-header">
                                            <div class="team-member-monogram">{{ $initial }}</div>
                                            <div class="team-member-heading">
                                                <h3 class="team-member-name">{{ $member->department ?: $member->name }}</h3>
                                                <div class="team-member-designation">{{ $member->designation ?: $member->name }}</div>
                                            </div>
                                        </div>

                                        <div class="team-member-body">
                                            @if($member->email)
                                                <div class="team-member-contact">{{ $member->email }}</div>
                                            @endif
                                            <div class="team-member-divider"></div>
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
