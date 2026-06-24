@extends('frontend.layouts.app')

@section('content')

<section class="seller-register-section">
    <div class="container">

        {{-- Breadcrumb --}}
        <div class="seller-breadcrumb mb-4">
            <a href="{{ route('home') }}">{{ translate('Home') }}</a>
            <span>/</span>
            <strong>{{ translate('Register Your Shop') }}</strong>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12">

                <div class="seller-register-card">
                    <div class="row no-gutters align-items-stretch">

                        {{-- LEFT PANEL --}}
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="seller-register-left">
                                <div>
                                    <div class="seller-logo-box">
                                        @if(get_setting('system_logo_black') != null)
                                            <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" alt="{{ env('APP_NAME') }}">
                                        @else
                                            <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}">
                                        @endif
                                    </div>

                                    <h2>Start Selling With Time To Furnish</h2>
                                    <p>
                                        Register your shop and manage your products, orders,
                                        customers and business details from one seller dashboard.
                                    </p>

                                    <div class="seller-benefits">
                                        <div class="seller-benefit-item">
                                            <i class="las la-store"></i>
                                            <span>Create your online shop</span>
                                        </div>

                                        <div class="seller-benefit-item">
                                            <i class="las la-box"></i>
                                            <span>Manage products easily</span>
                                        </div>

                                        <div class="seller-benefit-item">
                                            <i class="las la-chart-line"></i>
                                            <span>Grow your furniture business</span>
                                        </div>
                                    </div>
                                </div>

                                <p class="seller-copy">
                                    © {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
                                </p>
                            </div>
                        </div>

                        {{-- RIGHT FORM --}}
                        <div class="col-lg-7">
                            <div class="seller-register-form-wrap">

                                <div class="mobile-logo d-lg-none">
                                    @if(get_setting('system_logo_black') != null)
                                        <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" alt="{{ env('APP_NAME') }}">
                                    @else
                                        <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}">
                                    @endif
                                </div>

                                <div class="seller-form-heading">
                                    <span class="seller-tag">{{ translate('Seller Registration') }}</span>
                                    <h1>{{ translate('Register Your Shop') }}</h1>
                                    <p>{{ translate('Fill in your personal and shop details to create your seller account.') }}</p>
                                </div>

                                <form id="shop" action="{{ route('shops.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    {{-- PERSONAL INFO --}}
                                    <div class="seller-form-box">
                                        <div class="seller-box-title">
                                            <i class="las la-user"></i>
                                            <h3>{{ translate('Personal Information') }}</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Name') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-user"></i>
                                                        <input type="text"
                                                               class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                               value="{{ old('name') }}"
                                                               placeholder="{{ translate('Enter your name') }}"
                                                               name="name"
                                                               required>
                                                    </div>

                                                    @if ($errors->has('name'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Email') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-envelope"></i>
                                                        <input type="email"
                                                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                               value="{{ old('email') }}"
                                                               placeholder="{{ translate('Enter email address') }}"
                                                               name="email"
                                                               required>
                                                    </div>

                                                    @if ($errors->has('email'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('email') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Landline Number') }}</label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-phone"></i>
                                                        <input type="text"
                                                               class="form-control{{ $errors->has('landline_no') ? ' is-invalid' : '' }}"
                                                               name="landline_no"
                                                               value="{{ old('landline_no') }}"
                                                               inputmode="numeric"
                                                               maxlength="14"
                                                               placeholder="{{ translate('Landline Number') }}"
                                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                                    </div>

                                                    @if ($errors->has('landline_no'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('landline_no') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6"></div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Password') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-lock"></i>
                                                        <input type="password"
                                                               id="password"
                                                               class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                               placeholder="{{ translate('Password') }}"
                                                               name="password"
                                                               required>

                                                        <button type="button" class="seller-eye-btn" onclick="togglePassword('password', this)">
                                                            <i class="las la-eye"></i>
                                                        </button>
                                                    </div>

                                                    @if ($errors->has('password'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('password') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Repeat Password') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-lock"></i>
                                                        <input type="password"
                                                               id="password_confirmation"
                                                               class="form-control"
                                                               placeholder="{{ translate('Confirm Password') }}"
                                                               name="password_confirmation"
                                                               required>

                                                        <button type="button" class="seller-eye-btn" onclick="togglePassword('password_confirmation', this)">
                                                            <i class="las la-eye"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SHOP INFO --}}
                                    <div class="seller-form-box">
                                        <div class="seller-box-title">
                                            <i class="las la-store"></i>
                                            <h3>{{ translate('Shop Information') }}</h3>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Shop/Business Name') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-store-alt"></i>
                                                        <input type="text"
                                                               class="form-control{{ $errors->has('shop_name') ? ' is-invalid' : '' }}"
                                                               value="{{ old('shop_name') }}"
                                                               placeholder="{{ translate('Shop Name') }}"
                                                               name="shop_name"
                                                               required>
                                                    </div>

                                                    @if ($errors->has('shop_name'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('shop_name') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ translate('Mobile Number') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-mobile"></i>
                                                        <input type="text"
                                                               class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                               name="phone"
                                                               value="{{ old('phone') }}"
                                                               inputmode="numeric"
                                                               maxlength="14"
                                                               placeholder="{{ translate('Mobile Number') }}"
                                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                               required>
                                                    </div>

                                                    @if ($errors->has('phone'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('phone') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>{{ translate('Address') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-map-marker"></i>
                                                        <input type="text"
                                                               class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}"
                                                               value="{{ old('address') }}"
                                                               placeholder="{{ translate('Enter complete shop address') }}"
                                                               name="address"
                                                               required>
                                                    </div>

                                                    @if ($errors->has('address'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('address') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ translate('Country') }} <span>*</span></label>
                                                    <select class="form-control aiz-selectpicker seller-select"
                                                            data-live-search="true"
                                                            data-placeholder="{{ translate('Select your country') }}"
                                                            name="country_id"
                                                            id="edit_country"
                                                            required>
                                                        <option value="">{{ translate('Select Country') }}</option>
                                                        @foreach (get_active_countries() as $key => $country)
                                                            <option @if($country->id == 230) selected @endif value="{{ $country->id }}">
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ translate('City') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-city"></i>
                                                        <input type="text"
                                                               class="form-control"
                                                               name="city_id"
                                                               placeholder="{{ translate('City') }}"
                                                               value="{{ old('city_id') }}"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ translate('Post Code') }} <span>*</span></label>
                                                    <div class="seller-input-wrap">
                                                        <i class="las la-mail-bulk"></i>
                                                        <input type="text"
                                                               class="form-control{{ $errors->has('postal_code') ? ' is-invalid' : '' }}"
                                                               value="{{ old('postal_code') }}"
                                                               placeholder="{{ translate('Post Code') }}"
                                                               name="postal_code"
                                                               required>
                                                    </div>

                                                    @if ($errors->has('postal_code'))
                                                        <span class="invalid-feedback d-block" role="alert">
                                                            <strong>{{ $errors->first('postal_code') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- TERMS --}}
                                    <div class="seller-terms-box">
                                        <label class="seller-check">
                                            <input type="checkbox" name="checkbox_example_1" required>
                                            <span></span>
                                            <p>
                                                I have fully read and agree to abide by all
                                                <a href="javascript:void(0)" id="openModalBtn" data-id="5">
                                                    Terms and Conditions
                                                </a>
                                                in the Seller Policy.
                                            </p>
                                        </label>
                                    </div>

                                    @if(get_setting('google_recaptcha') == 1)
                                        <div class="form-group mt-3">
                                            <div class="g-recaptcha" data-sitekey="{{ env('CAPTCHA_KEY') }}"></div>
                                        </div>
                                    @endif

                                    <button type="submit" class="seller-submit-btn">
                                        {{ translate('Register Your Shop') }}
                                        <i class="las la-arrow-right"></i>
                                    </button>

                                    <div class="seller-login-text">
                                        Already have a seller account?
                                        <a href="{{ route('login') }}">{{ translate('Login Now') }}</a>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Terms Modal --}}
<div id="myModal" class="seller-modal">
    <div class="seller-modal-content">
        <button type="button" class="seller-modal-close">&times;</button>

        @php
            $page = \App\Models\Page::where('id', 5)->first();
        @endphp

        @if($page)
            <h2>{{ $page->title }}</h2>
            <div class="seller-policy-content">
                @php echo $page->content @endphp
            </div>
        @else
            <h2>{{ translate('Seller Policy') }}</h2>
            <p>{{ translate('Seller policy content not found.') }}</p>
        @endif
    </div>
</div>

<style>
    .seller-register-section {
        padding: 60px 0 80px;
        background:
            radial-gradient(circle at top left, rgba(218, 203, 188, 0.55), transparent 35%),
            linear-gradient(135deg, #FAF7F2 0%, #F2E8DD 100%);
        min-height: 100vh;
    }

    .seller-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #8A7C6F;
    }

    .seller-breadcrumb a {
        color: #8A7C6F;
        text-decoration: none;
        font-weight: 600;
    }

    .seller-breadcrumb strong {
        color: #3D3429;
    }

    .seller-register-card {
        background: #fff;
        border-radius: 30px;
        overflow: hidden;
        box-shadow: 0 30px 90px rgba(61, 52, 41, 0.16);
        border: 1px solid rgba(104, 91, 78, 0.12);
    }

    .seller-register-left {
        height: 100%;
        padding: 52px 42px;
        background:
            linear-gradient(145deg, rgba(86, 70, 55, 0.98), rgba(55, 42, 32, 0.98));
        color: #fff;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .seller-register-left::after {
        content: "";
        position: absolute;
        right: -90px;
        bottom: -90px;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
    }

    .seller-logo-box {
        width: 86px;
        height: 86px;
        border-radius: 25px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 34px;
        box-shadow: 0 18px 42px rgba(0, 0, 0, 0.20);
    }

    .seller-logo-box img {
        max-width: 68px;
        max-height: 68px;
        object-fit: contain;
    }

    .seller-register-left h2 {
        color: #fff;
        font-size: 34px;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 16px;
    }

    .seller-register-left p {
        color: rgba(255, 255, 255, 0.82);
        font-size: 14px;
        line-height: 1.9;
        margin: 0;
    }

    .seller-benefits {
        margin-top: 36px;
    }

    .seller-benefit-item {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 16px;
        color: rgba(255, 255, 255, 0.92);
        font-size: 14px;
        font-weight: 600;
    }

    .seller-benefit-item i {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.14);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .seller-copy {
        position: relative;
        z-index: 2;
        font-size: 12px !important;
    }

    .seller-register-form-wrap {
        padding: 46px 50px;
        background: rgba(255, 255, 255, 0.98);
    }

    .mobile-logo {
        text-align: center;
        margin-bottom: 22px;
    }

    .mobile-logo img {
        max-height: 60px;
    }

    .seller-form-heading {
        margin-bottom: 26px;
    }

    .seller-tag {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 999px;
        background: #F2E8DD;
        color: #685B4E;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 12px;
    }

    .seller-form-heading h1 {
        color: #3D3429;
        font-size: 30px;
        font-weight: 800;
        margin-bottom: 8px;
    }

    .seller-form-heading p {
        color: #8A7C6F;
        font-size: 14px;
        margin-bottom: 0;
    }

    .seller-form-box {
        background: #FFFCF8;
        border: 1px solid #E8DDD3;
        border-radius: 22px;
        padding: 24px;
        margin-bottom: 22px;
    }

    .seller-box-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #E8DDD3;
    }

    .seller-box-title i {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: #685B4E;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .seller-box-title h3 {
        color: #3D3429;
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group label {
        color: #4D4035;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 7px;
    }

    .form-group label span {
        color: #C0392B;
    }

    .seller-input-wrap {
        position: relative;
    }

    .seller-input-wrap > i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #A79687;
        font-size: 18px;
        z-index: 2;
    }

    .seller-input-wrap .form-control {
        height: 52px;
        border: 1px solid #E4D8CD !important;
        border-radius: 15px !important;
        background: #fff !important;
        color: #3D3429 !important;
        padding: 0 48px 0 46px !important;
        font-size: 14px;
        box-shadow: none !important;
        transition: all 0.25s ease;
    }

    .seller-input-wrap .form-control::placeholder {
        color: #A8988B;
    }

    .seller-input-wrap .form-control:focus {
        border-color: #685B4E !important;
        box-shadow: 0 0 0 4px rgba(104, 91, 78, 0.12) !important;
    }

    .seller-select,
    .bootstrap-select > .dropdown-toggle {
        height: 52px !important;
        border: 1px solid #E4D8CD !important;
        border-radius: 15px !important;
        background: #fff !important;
        color: #3D3429 !important;
        box-shadow: none !important;
        padding-left: 14px !important;
    }

    .seller-eye-btn {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 12px;
        background: #F4EEE8;
        color: #685B4E;
        cursor: pointer;
        z-index: 3;
        transition: all 0.25s ease;
    }

    .seller-eye-btn:hover {
        background: #E8DDD3;
        color: #3D3429;
    }

    .seller-terms-box {
        background: #F8F1EA;
        border: 1px solid #E8DDD3;
        border-radius: 18px;
        padding: 16px 18px;
        margin-bottom: 22px;
    }

    .seller-check {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin: 0;
        cursor: pointer;
    }

    .seller-check input {
        display: none;
    }

    .seller-check span {
        width: 20px;
        min-width: 20px;
        height: 20px;
        border-radius: 6px;
        border: 2px solid #685B4E;
        margin-top: 2px;
        position: relative;
        background: #fff;
    }

    .seller-check input:checked + span {
        background: #685B4E;
    }

    .seller-check input:checked + span::after {
        content: "✓";
        position: absolute;
        color: #fff;
        font-size: 13px;
        font-weight: 800;
        left: 3px;
        top: -2px;
    }

    .seller-check p {
        margin: 0;
        color: #5C4D40;
        font-size: 13px;
        line-height: 1.7;
    }

    .seller-check a {
        color: #3D3429;
        font-weight: 800;
        text-decoration: underline;
    }

    .seller-submit-btn {
        width: 100%;
        height: 56px;
        border: 0;
        border-radius: 17px;
        background: linear-gradient(135deg, #756655, #4B3A2C);
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 16px 36px rgba(75, 58, 44, 0.26);
        transition: all 0.25s ease;
    }

    .seller-submit-btn:hover {
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 22px 48px rgba(75, 58, 44, 0.34);
    }

    .seller-submit-btn i {
        margin-left: 6px;
    }

    .seller-login-text {
        text-align: center;
        margin-top: 20px;
        color: #8A7C6F;
        font-size: 13px;
    }

    .seller-login-text a {
        color: #3D3429;
        font-weight: 800;
        text-decoration: none;
    }

    .seller-modal {
        display: none;
        position: fixed;
        z-index: 9999;
        inset: 0;
        background: rgba(0, 0, 0, 0.62);
        padding: 20px;
    }

    .seller-modal-content {
        background: #fff;
        max-width: 760px;
        max-height: 78vh;
        overflow: auto;
        margin: 7vh auto;
        padding: 32px;
        border-radius: 22px;
        position: relative;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.25);
        animation: sellerFadeIn 0.25s ease;
    }

    .seller-modal-content h2 {
        color: #3D3429;
        font-size: 24px;
        font-weight: 800;
        margin-bottom: 18px;
    }

    .seller-modal-close {
        position: sticky;
        top: 0;
        float: right;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 0;
        background: #F2E8DD;
        color: #3D3429;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        z-index: 2;
    }

    @keyframes sellerFadeIn {
        from {
            opacity: 0;
            transform: translateY(12px) scale(0.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @media (max-width: 991px) {
        .seller-register-section {
            padding: 42px 0 60px;
        }

        .seller-register-card {
            max-width: 720px;
            margin: 0 auto;
        }

        .seller-register-form-wrap {
            padding: 38px 30px;
        }
    }

    @media (max-width: 575px) {
        .seller-register-section {
            padding: 28px 12px 44px;
        }

        .seller-register-card {
            border-radius: 24px;
        }

        .seller-register-form-wrap {
            padding: 30px 20px;
        }

        .seller-form-heading h1 {
            font-size: 25px;
        }

        .seller-form-box {
            padding: 18px;
            border-radius: 18px;
        }

        .seller-modal-content {
            max-height: 84vh;
            padding: 24px 18px;
            margin: 6vh auto;
        }
    }
</style>

@endsection

@section('script')
@if(get_setting('google_recaptcha') == 1)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script type="text/javascript">
    $(document).ready(function () {
        @if(get_setting('google_recaptcha') == 1)
            $("#shop").on("submit", function(evt) {
                var response = grecaptcha.getResponse();

                if(response.length == 0) {
                    alert("Please verify you are human!");
                    evt.preventDefault();
                    return false;
                }
            });
        @endif

        var modal = document.getElementById("myModal");
        var openBtn = document.getElementById("openModalBtn");
        var closeBtn = document.querySelector(".seller-modal-close");

        if(openBtn) {
            openBtn.onclick = function() {
                modal.style.display = "block";
            }
        }

        if(closeBtn) {
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });

    function togglePassword(id, button) {
        const passwordInput = document.getElementById(id);
        const icon = button.querySelector('i');

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove('la-eye');
            icon.classList.add('la-eye-slash');
        } else {
            passwordInput.type = "password";
            icon.classList.remove('la-eye-slash');
            icon.classList.add('la-eye');
        }
    }
</script>
@endsection
