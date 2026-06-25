@extends('frontend.layouts.app')

@section('meta_title'){{ translate('Meet the Team') }} - {{ get_setting('website_name') }}@stop
@section('meta_description'){{ translate('Meet the team behind the brand and learn more about their experience and expertise.') }}@stop

@section('content')
<?php
    $bannerImage = get_setting('team_members_banner_image');
    if ($bannerImage) {
        $bannerBg = is_numeric($bannerImage) ? uploaded_asset($bannerImage) : asset($bannerImage);
    } else {
        $bannerBg = asset('assets/img/team/team-banner.png');
    }
    $bannerTitle = get_setting('team_members_banner_title', translate('Meet Our Team'));
    $bannerSubtitle = get_setting('team_members_banner_subtitle', translate('Discover the team members who design, build, and support your products. Each profile includes a short introduction so your visitors can get to know the people behind the brand.'));
    $bannerDescription = get_setting('team_members_banner_desc', '');
    $cardFallbackSetting = get_setting('team_members_card_image');
    $cardFallback = $cardFallbackSetting ? (is_numeric($cardFallbackSetting) ? uploaded_asset($cardFallbackSetting) : asset($cardFallbackSetting)) : null;
?>

<style>
    .team-page {
        --team-primary: #685b4e;
        --team-primary-dark: #564a3f;
        --team-primary-soft: #dacbbc;
        --team-surface: #f7f3ee;
    }

    .team-hero {
        background: linear-gradient(rgba(86, 74, 63, 0.9), rgba(104, 91, 78, 0.92));
        color: #fff;
        padding: 72px 0 64px;
    }

    .team-hero .hero-title {
        color: #fff;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .team-hero .hero-subtitle,
    .team-hero .hero-description {
        color: rgba(255, 255, 255, 0.9);
    }

    .team-hero .breadcrumb,
    .team-hero .breadcrumb-item,
    .team-hero .breadcrumb-item + .breadcrumb-item::before {
        color: rgba(255, 255, 255, 0.75);
    }

    .team-hero .breadcrumb a {
        color: rgba(255, 255, 255, 0.85);
    }

    .team-content {
        background: var(--team-surface);
        padding: 56px 0;
    }

    .team-heading-kicker {
        color: var(--team-primary);
        text-transform: uppercase;
        letter-spacing: 1.4px;
        font-size: 0.8rem;
        font-weight: 700;
    }

    .team-heading-title {
        color: #2f2924;
        font-weight: 700;
    }

    .team-heading-description {
        color: #6f6f6f;
    }

    .team-card {
        background: ;
        color: #3f342a;
        border: 1px solid #e2d6c8;
        border-radius: 16px;
        padding: 24px 20px;
        min-height: 100%;
        box-shadow: 0 6px 14px rgba(57, 50, 42, 0.08);
    }

    .team-avatar,
    .team-avatar-placeholder {
        width: 92px;
        height: 92px;
        border-radius: 50%;
        margin: 0 auto 14px;
    }

    .team-avatar {
        background-size: cover;
        background-position: center;
        border: 3px solid #d8cab9;
    }

    .team-avatar-placeholder {
        background: #d8cab9;
        border: 2px solid #cab9a5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4d4033;
        font-size: 1.8rem;
        font-weight: 700;
    }

    .team-member-name {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: #3f342a;
    }

    .team-member-email {
        color: #6f5f50;
        font-size: 0.92rem;
        margin-bottom: 12px;
    }

    .team-member-bio {
        color: #5f5347;
        line-height: 1.65;
        font-size: 0.95rem;
        margin-bottom: 0;
    }

    .team-empty {
        border: 0;
        border-radius: 12px;
        background: #ebe2d8;
        color: #4b3f34;
    }
</style>

<div class="team-page">
    <section class="team-hero" style="background-image: linear-gradient(rgb(86 74 63 / 49%), rgba(104, 91, 78, 0.92)), url('{{ $bannerBg }}'); background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-xl-8">
                    <h1 class="display-5 hero-title">{{ $bannerTitle }}</h1>
                    <p class="lead hero-subtitle mb-3">{{ $bannerSubtitle }}</p>
                    @if($bannerDescription)
                        <p class="hero-description mb-3">{{ $bannerDescription }}</p>
                    @endif
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent px-0 mb-0" style="--bs-breadcrumb-divider: '>' ;">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ translate('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ translate('Meet the Team') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>

    <section class="team-content">
        <div class="container">
            <div class="row justify-content-center mb-5">
                <div class="col-xl-8 col-lg-9 text-center">
                    <p class="team-heading-kicker mb-2">{{ translate('Our professionals') }}</p>
                    <h2 class="team-heading-title">{{ translate('Empowered by experience, driven by results') }}</h2>
                    <p class="team-heading-description mt-3">{{ translate('Every team member brings skills, passion, and deep product knowledge to help your business succeed.') }}</p>
                </div>
            </div>

            <div class="row g-4" style="row-gap:20px;">
                @foreach($team_members as $member)
                    <div class="col-12 col-md-6 col-lg-4">
                        <article class="team-card text-center">
                            @if($member->photo)
                                <div class="team-avatar" style="background-image: url('{{ asset($member->photo) }}');"></div>
                            @elseif($cardFallback)
                                <div class="team-avatar" style="background-image: url('{{ $cardFallback }}');"></div>
                            @else
                                <div class="team-avatar-placeholder">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                            @endif

                            <h3 class="team-member-name">{{ $member->name }}</h3>
                            @if($member->email)
                                <p class="team-member-email">{{ $member->email }}</p>
                            @endif
                            <p class="team-member-bio">{{ $member->bio ?: translate('No biography added yet.') }}</p>
                        </article>
                    </div>
                @endforeach

                @if($team_members->isEmpty())
                    <div class="col-12">
                        <div class="alert team-empty mb-0">{{ translate('No team members have been added yet.') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
