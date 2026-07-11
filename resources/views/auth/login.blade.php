@extends('backend.layouts.layout')

@section('content')

<style>
    .ttf-admin-login {
        min-height: 100vh;
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        overflow: hidden;
        padding: 40px 0;
    }

    .ttf-admin-login::before {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(120deg, rgba(22, 17, 12, 0.78), rgba(74, 58, 43, 0.58)),
            rgba(0, 0, 0, 0.25);
        z-index: 0;
    }

    .ttf-admin-login .container {
        position: relative;
        z-index: 1;
    }

    .ttf-login-card {
        max-width: 920px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.96);
        border-radius: 26px;
        overflow: hidden;
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.28);
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .ttf-login-left {
        height: 100%;
        padding: 48px 36px;
        background:
            linear-gradient(145deg, rgba(85, 67, 50, 0.96), rgba(48, 37, 28, 0.98));
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .ttf-login-left::after {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        right: -80px;
        bottom: -70px;
    }

    .ttf-brand-logo-box {
        width: 268px;
        height: 72px;
        border-radius: 22px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 28px;
        box-shadow: 0 18px 40px rgba(0, 0, 0, 0.20);
    }

    .ttf-brand-logo-box img {
        max-width: 234px;
        max-height: 54px;
        object-fit: contain;
    }

    .ttf-login-left h2 {
        color: #fff;
        font-size: 30px;
        line-height: 1.25;
        font-weight: 800;
        margin-bottom: 14px;
    }

    .ttf-login-left p {
        color: rgba(255, 255, 255, 0.82);
        font-size: 14px;
        line-height: 1.8;
        margin: 0;
    }

    .ttf-login-points {
        margin-top: 34px;
    }

    .ttf-login-point {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 13px;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
    }

    .ttf-login-point i {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.14);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ttf-login-form-wrap {
        padding: 54px 48px;
    }

    .ttf-mobile-logo {
        display: none;
        text-align: center;
        margin-bottom: 22px;
    }

    .ttf-mobile-logo img {
        max-height: 52px;
        object-fit: contain;
    }

    .ttf-login-title {
        text-align: left;
        margin-bottom: 32px;
    }

    .ttf-login-title h1 {
        font-size: 30px;
        font-weight: 800;
        color: #4b3a2c;
        margin-bottom: 8px;
    }

    .ttf-login-title p {
        color: #897a6f;
        font-size: 14px;
        margin: 0;
    }

    .ttf-form-group {
        margin-bottom: 18px;
        position: relative;
    }

    .ttf-input-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9b8a7c;
        font-size: 18px;
        z-index: 2;
    }

    .ttf-form-control {
        height: 52px;
        border-radius: 14px !important;
        border: 1px solid #e2d8ce !important;
        background: #fff !important;
        color: #3d3429 !important;
        padding-left: 46px !important;
        font-size: 14px;
        box-shadow: none !important;
        transition: all 0.25s ease;
    }

    .ttf-form-control:focus {
        border-color: #7b6a58 !important;
        box-shadow: 0 0 0 4px rgba(123, 106, 88, 0.13) !important;
    }

    .ttf-password-group .ttf-form-control {
        padding-right: 52px !important;
    }

    .ttf-password-toggle {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 0;
        background: #f5f1ed;
        color: #6f5d4d;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 3;
        transition: all 0.25s ease;
    }

    .ttf-password-toggle:hover {
        background: #eadfd4;
        color: #3d3429;
    }

    .ttf-form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 4px 0 22px;
    }

    .ttf-forgot-link {
        color: #6f5d4d !important;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none !important;
    }

    .ttf-forgot-link:hover {
        color: #3d3429 !important;
    }

    .ttf-login-btn {
        height: 54px;
        border-radius: 15px !important;
        border: 0 !important;
        background: linear-gradient(135deg, #7b6a58, #4b3a2c) !important;
        color: #fff !important;
        font-size: 16px;
        font-weight: 700;
        box-shadow: 0 14px 30px rgba(75, 58, 44, 0.26);
        transition: all 0.25s ease;
    }

    .ttf-login-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 18px 38px rgba(75, 58, 44, 0.34);
    }

    .ttf-login-btn:active {
        transform: translateY(0);
    }

    .aiz-checkbox span {
        color: #4b3a2c;
        font-weight: 500;
    }

    .aiz-checkbox input:checked ~ .aiz-square-check {
        background: #6f5d4d !important;
        border-color: #6f5d4d !important;
    }

    .invalid-feedback {
        margin-top: 7px;
        font-size: 12px;
    }

    .ttf-demo-table {
        margin-top: 24px;
        border-radius: 14px;
        overflow: hidden;
    }

    @media (max-width: 991px) {
        .ttf-login-card {
            max-width: 520px;
        }

        .ttf-login-form-wrap {
            padding: 42px 34px;
        }

        .ttf-mobile-logo {
            display: block;
        }

        .ttf-login-title {
            text-align: center;
        }
    }

    @media (max-width: 575px) {
        .ttf-admin-login {
            padding: 24px 12px;
        }

        .ttf-login-card {
            border-radius: 22px;
        }

        .ttf-login-form-wrap {
            padding: 34px 22px;
        }

        .ttf-login-title h1 {
            font-size: 25px;
        }

        .ttf-form-options {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<div class="ttf-admin-login" style="background-image: url('{{ uploaded_asset(get_setting('admin_login_background')) }}');">
    <div class="container">
        <div class="ttf-login-card">
            <div class="row no-gutters align-items-stretch">

                <div class="col-lg-5 d-none d-lg-block">
                    <div class="ttf-login-left">
                        <div>
                            <div class="ttf-brand-logo-box">
                               <a href="{{ route('admin.dashboard') }}">
                                <img src="https://timetofurnish.com/public/assets/img/logoT.png" alt="Time To Furnish Logo">
                           
                            </a>
                            </div>

                            <h2>Admin Dashboard Access</h2>
                            <p>
                                Login to your admin panel and manage products, orders,
                                sellers and website settings from one secure dashboard.
                            </p>

                            <div class="ttf-login-points">
                                <div class="ttf-login-point">
                                    <i class="las la-check"></i>
                                    <span>Secure admin access</span>
                                </div>
                                <div class="ttf-login-point">
                                    <i class="las la-check"></i>
                                    <span>Clean dashboard control</span>
                                </div>
                                <div class="ttf-login-point">
                                    <i class="las la-check"></i>
                                    <span>Control dashboard operations</span>
                                </div>
                            </div>
                        </div>

                        <p class="fs-12">
                            © {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.
                        </p>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="ttf-login-form-wrap">

                        <div class="ttf-mobile-logo">
                            @if(get_setting('system_logo_black') != null)
                                <img src="{{ uploaded_asset(get_setting('system_logo_black')) }}" alt="{{ env('APP_NAME') }}">
                            @else
                                <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}">
                            @endif
                        </div>

                        <div class="ttf-login-title">
                            <h1>{{ translate('Welcome Back') }}</h1>
                            <p>{{ translate('Please login to continue to Admin account.') }}</p>
                        </div>

                        <form class="pad-hor" method="POST" role="form" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group ttf-form-group">
                                <i class="las la-envelope ttf-input-icon"></i>
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control ttf-form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="{{ translate('Email address') }}"
                                >

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="form-group ttf-form-group ttf-password-group">
                                <i class="las la-lock ttf-input-icon"></i>
                                <input
                                    id="password"
                                    type="password"
                                    class="form-control ttf-form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="{{ translate('Password') }}"
                                >

                                <button type="button" class="ttf-password-toggle">
                                    <i class="las la-eye"></i>
                                    <i class="las la-eye-slash d-none"></i>
                                </button>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="ttf-form-options">
                                <label class="aiz-checkbox mb-0">
                                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <span>{{ translate('Remember Me') }}</span>
                                    <span class="aiz-square-check"></span>
                                </label>

                                @if(env('MAIL_USERNAME') != null && env('MAIL_PASSWORD') != null)
                                    <a href="{{ route('password.request') }}" class="ttf-forgot-link">
                                        {{ translate('Forgot password ?') }}
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block ttf-login-btn">
                                {{ translate('Login') }}
                            </button>
                        </form>

                        @if (env("DEMO_MODE") == "On")
                            <div class="ttf-demo-table">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <tr>
                                            <td>admin@example.com</td>
                                            <td>123456</td>
                                            <td>
                                                <button class="btn btn-info btn-xs" onclick="autoFill()">
                                                    {{ translate('Copy') }}
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    function autoFill(){
        $('#email').val('admin@example.com');
        $('#password').val('123456');
    }

    $(document).ready(function () {
        $('.ttf-password-toggle').click(function (e) {
            e.preventDefault();

            var passwordField = $('#password');
            var openEyeIcon = $(this).find('.la-eye');
            var closedEyeIcon = $(this).find('.la-eye-slash');

            var passwordType = passwordField.attr('type');
            passwordField.attr('type', passwordType === 'password' ? 'text' : 'password');

            openEyeIcon.toggleClass('d-none');
            closedEyeIcon.toggleClass('d-none');
        });
    });
</script>
@endsection
