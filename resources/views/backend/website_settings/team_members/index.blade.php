@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Team Members') }}</h1>
        </div>
        <div class="col text-end" style="text-align:end; display:flex; gap:10px; justify-content:flex-end; flex-wrap:wrap;">
            @can('add_website_page')
            <form action="{{ route('team-members.seed-default-members') }}" method="POST" onsubmit="return confirm('{{ translate('This will create or update the default team members. Continue?') }}');">
                @csrf
                <button type="submit" class="btn" style="background:#dacbbc;color:#39322a;border-radius:24px;padding:10px 18px;border:1px solid #dacbbc;">{{ translate('Seed Default Team') }}</button>
            </form>
            <a href="{{ route('team-members.create') }}" class="btn" style="background:#685b4e;color:#ffffff;border-radius:24px;padding:10px 22px;border:1px solid #685b4e;">{{ translate('Add Team Member') }}</a>
            @endcan
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <style>
            .theme-switch .form-check-input{
                -webkit-appearance:none;appearance:none; width:46px;height:26px;background:#e9e7e4;border-radius:999px;position:relative;outline:none;cursor:pointer;transition:background .2s;border:none;
            }
            .team_badge{display:inline-block;margin-left:12px;padding:4px 10px;font-size:12px;border-radius:12px;}
            .theme-switch .form-check-input::after{content:'';position:absolute;width:20px;height:20px;border-radius:50%;background:#fff;top:3px;left:3px;box-shadow:0 1px 2px rgba(0,0,0,0.2);transition:transform .2s;}
            .theme-switch .form-check-input:checked{background:#685b4e;}
            .theme-switch .form-check-input:checked::after{transform:translateX(20px);}
            .form-check.form-switch{display:inline-flex;align-items:center;}
            .theme-add-btn{background:#685b4e;color:#fff;border-radius:30px;padding:10px 22px;border:1px solid #685b4e;}
        </style>
        <form action="{{ route('team-members.update-status') }}" method="POST" class="row g-3 align-items-center">
            @csrf
            <div class="col-3">
                <h6 class="mb-0">{{ translate('Team page status') }}</h6>
            </div>
            <div class="col-3">
                <input type="hidden" name="status" value="0">
                <div class="form-check form-switch theme-switch" style="display:flex;align-items:center;gap:8px;">
                    <input class="form-check-input" type="checkbox" id="teamPageStatus" name="status" value="1" {{ $team_page_status == 1 ? 'checked' : '' }} onchange="this.form.submit()">
                    <label class="form-check-label ms-2" for="teamPageStatus" style="color:#39322a;">{{ $team_page_status == 1 ? translate('Enabled') : translate('Disabled') }}</label>

                 <span class="team_badge" style="background: #dacbbc; color: #39322a;">{{ $team_page_status == 1 ? translate('Visible on frontend') : translate('Hidden from frontend') }}</span>
            </div>
            </div>

        </form>
    </div>
</div>

<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header" style="background: #2f2924; border-bottom: 1px solid #685b4e;">
        <h6 class="mb-0 fw-600" style="color: #dacbbc;">{{ translate('Managing Director welcome section') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('team-members.update-welcome-section') }}" method="POST">
            @csrf
            <div class="row gy-3">
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Welcome Title') }}</label>
                    <input type="text" name="intro_title" value="{{ old('intro_title', get_setting('team_members_intro_title', 'Welcome from the Managing Director')) }}" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Welcome Subtitle') }}</label>
                    <input type="text" name="intro_subtitle" value="{{ old('intro_subtitle', get_setting('team_members_intro_subtitle', 'Welcome to Time To Furnish.')) }}" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label fw-600">{{ translate('Welcome Content') }}</label>
                    <textarea name="intro_body" class="form-control" rows="8" placeholder="{{ translate('Enter the welcome message shown on the team page') }}">{{ old('intro_body', get_setting('team_members_intro_body', 'My name is Mrs. H. Kaur, and I am proud to welcome you to a company built on generations of passion, craftsmanship, and trust. Our journey began in the early 1980s, when my father established a furniture business alongside a sawmill in North India. For over two decades, he dedicated his life to the furniture and timber industry, mastering the art of woodworking while earning a reputation for quality and integrity. Growing up around timber, furniture manufacturing, and skilled craftsmen gave me not only valuable knowledge but also a deep appreciation for fine furniture and the people who create it. Inspired by my father\'s legacy, I always dreamed of building something that would connect exceptional manufacturers directly with customers. As technology transformed the way people shop, we saw an opportunity to remove unnecessary barriers between manufacturers and buyers. That vision became Time To Furnish. Our mission is simple: to bring the UK\'s finest furniture manufacturers just one click away from every customer. We have created a platform where quality, affordability, and convenience come together. By simplifying the buying process, we also help manufacturers receive faster payments - reducing waiting times from months to just days - allowing them to focus on what they do best: creating outstanding furniture. We proudly partner with some of the UK\'s leading delivery companies to ensure your furniture arrives safely, quickly, and professionally. Whether you\'re furnishing your bedroom, living room, dining room, or any other space, we are committed to delivering beautiful, high-quality furniture directly to your doorstep, with professional installation available for your convenience. At Time To Furnish, we believe that everyone deserves stylish, durable, and affordable furniture without compromise. Every product reflects our commitment to craftsmanship, customer satisfaction, and innovation. Thank you for choosing Time To Furnish. We look forward to helping you create a home you\'ll love for years to come.')) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Signature / Closing') }}</label>
                    <textarea name="intro_signature" class="form-control" rows="4" placeholder="{{ translate('Enter the sign-off text') }}">{{ old('intro_signature', get_setting('team_members_intro_signature', "Mrs. H. Kaur\nManaging Director\nTime To Furnish")) }}</textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn" style="background: #685b4e; color: #ffffff; border: 1px solid #685b4e;">{{ translate('Save Welcome Section') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card mb-3 border-0 shadow-sm">
    <div class="card-header" style="background: #39322a; border-bottom: 1px solid #685b4e;">
        <h6 class="mb-0 fw-600" style="color: #dacbbc;">{{ translate('Team page banner and card settings') }}</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('team-members.update-banner-section') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row gy-3">
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Banner Title') }}</label>
                    <input type="text" name="banner_title" value="{{ old('banner_title', get_setting('team_members_banner_title')) }}" class="form-control" placeholder="{{ translate('Enter banner title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Banner Subtitle') }}</label>
                    <input type="text" name="banner_subtitle" value="{{ old('banner_subtitle', get_setting('team_members_banner_subtitle')) }}" class="form-control" placeholder="{{ translate('Enter banner subtitle') }}">
                </div>
                <div class="col-12">
                    <label class="form-label fw-600">{{ translate('Banner Description') }}</label>
                    <textarea name="banner_description" class="form-control" rows="4" placeholder="{{ translate('Enter banner description') }}">{{ old('banner_description', get_setting('team_members_banner_desc')) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-600">{{ translate('Banner Image') }}</label>
                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                        </div>
                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                        <input type="hidden" name="banner_image" value="{{ get_setting('team_members_banner_image') }}" class="selected-files">
                    </div>
                    <div class="file-preview box"></div>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn" style="background: #685b4e; color: #ffffff; border: 1px solid #685b4e;">{{ translate('Save Team Page Settings') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h6 class="mb-0 fw-600">{{ translate('Team Member List') }}</h6>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Department') }}</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('Designation') }}</th>
                    <th>{{ translate('Email') }}</th>
                    <th>{{ translate('Sort Order') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right">{{ translate('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team_members as $key => $member)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $member->department ?: translate('Other Team Members') }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->designation ?: '-' }}</td>
                    <td>{{ $member->email }}</td>
                    <td>
                        <span class="badge badge-inline badge-soft-info">{{ $member->department_sort_order }} / {{ $member->sort_order }}</span>
                    </td>
                    <td>
                        @if($member->is_active)
                            <span class="badge badge-inline badge-success">{{ translate('Active') }}</span>
                        @else
                            <span class="badge badge-inline badge-secondary">{{ translate('Inactive') }}</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @can('edit_website_page')
                        <a href="{{ route('team-members.edit', $member->id) }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="{{ translate('Edit') }}">
                            <i class="las la-pen"></i>
                        </a>
                        @endcan
                        @can('delete_website_page')
                        <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('team-members.destroy', $member->id) }}" title="{{ translate('Delete') }}">
                            <i class="las la-trash"></i>
                        </a>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
