@extends('frontend.layouts.app')

@section('content')

<section class="login-card-container py-5 py-md-6" style="background: #FAF7F2; min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-9 col-lg-10 col-md-12">
                <div class="login-main-card border-0">
                    <div class="row gx-0 align-items-stretch">
                        
                        {{-- Left side image (visible only on desktop) --}}
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="login-image-side h-100" style="background-image: url('https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?auto=format&fit=crop&w=800&q=80');">
                                <div class="login-image-overlay"></div>
                                <div class="h-100 p-5 d-flex flex-column justify-content-between text-white position-relative" style="z-index: 2;">
                                    <div>
                                        <h3 class="fw-700 fs-24 tracking-wider mb-2" style="font-family: 'DM Serif Display', serif;">Time To Furnish</h3>
                                        <p class="opacity-80 fs-13">{{ translate('Discover premium quality furniture crafted for your modern lifestyle.') }}</p>
                                    </div>
                                    <div class="mt-auto">
                                        <h4 class="fw-600 fs-18 mb-1" style="font-family: 'DM Serif Display', serif;">"{{ translate('Bringing comfort and style to your home.') }}"</h4>
                                        <p class="opacity-70 fs-12 mb-0">{{ translate('Join our community of happy homeowners.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right side form ( Buyer / Seller login ) --}}
                        <div class="col-lg-6 col-md-12">
                            <div class="login-form-side h-100">
                                
                                {{-- Header & Role Switcher --}}
                                <div class="text-center mb-4">
                                    <h1 class="fs-22 fw-700 mb-1" id="login-title" style="color: #39322a; font-family: 'DM Serif Display', serif;">{{ translate('Buyer Login') }}</h1>
                                    <p class="fs-13 text-muted" id="login-subtitle">{{ translate('Login to your Account') }}</p>
                                    
                                    {{-- Unified Role Switcher --}}
                                    <div class="role-switcher-container mt-3">
                                        <button type="button" class="btn-role active" data-role="buyer">
                                            {{ translate('Buyer') }}
                                        </button>
                                        <button type="button" class="btn-role" data-role="seller">
                                            {{ translate('Seller') }}
                                        </button>
                                    </div>
                                </div>

                                {{-- Unified Form --}}
                                <form class="form-default" action="{{ route('login') }}" method="POST" id="login-form">
                                    @csrf

                                    {{-- EMAIL / PHONE INPUT FOR BUYER --}}
                                    @if (addon_is_activated('otp_system'))
                                        {{-- Phone Input --}}
                                        <div class="form-group-custom phone-form-group">
                                            <label for="phone">{{ translate('Phone') }}</label>
                                            <input type="tel" id="phone-code" class="form-control {{ $errors->has('phone') ? ' is-invalid' : '' }}" value="{{ old('phone') }}" name="phone" autocomplete="off">
                                        </div>

                                        <input type="hidden" name="country_code" value="">
                                        
                                        {{-- Email Input (toggled) --}}
                                        <div class="form-group-custom email-form-group d-none">
                                            <label for="email">{{ translate('Email') }}</label>
                                            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="johndoe@example.com" name="email" id="email" autocomplete="off">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        
                                        {{-- Toggle between Email & Phone --}}
                                        <div class="form-group text-right mb-3" id="otp-toggle-wrap">
                                            <button class="btn btn-link p-0 text-primary fs-12 fw-500" type="button" onclick="toggleEmailPhone(this)" id="toggle-identifier-btn" style="background: transparent; border: none; color: #685b4e !important; text-decoration: underline;">
                                                <i>*{{ translate('Use Email Instead') }}</i>
                                            </button>
                                        </div>
                                    @else
                                        {{-- Standard Email Input (without OTP) --}}
                                        <div class="form-group-custom email-form-group">
                                            <label for="email">{{ translate('Email') }}</label>
                                            <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" placeholder="johndoe@example.com" name="email" id="email" autocomplete="off">
                                            @if ($errors->has('email'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('email') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- PASSWORD WITH TOGGLE --}}
                                    <div class="form-group-custom">
                                        <label for="password">{{ translate('Password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ translate('Password') }}" name="password" id="password">
                                            <div class="input-group-append-custom">
                                                <i class="las la-eye" id="password-toggle-icon" onclick="togglePassword('password')"></i>
                                            </div>
                                        </div>
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    {{-- REMEMBER ME & FORGOT PASSWORD --}}
                                    <div class="row align-items-center mb-4">
                                        <div class="col-6">
                                            <label class="aiz-checkbox mb-0">
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                <span class="fs-12" style="color: #5d5247; font-weight: 500;">{{ translate('Remember Me') }}</span>
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                        <div class="col-6 text-right">
                                            <a href="{{ route('password.request') }}" class="fs-12 fw-500 text-decoration-underline" style="color: #685b4e;">
                                                {{ translate('Forgot password?') }}
                                            </a>
                                        </div>
                                    </div>

                                    {{-- SUBMIT BUTTON --}}
                                    <button type="submit" class="btn btn-block btn-login text-white w-100 mb-4" id="submit-btn">
                                        {{ translate('Login As Buyer') }}
                                    </button>
                                </form>

                                {{-- DEMO AUTOFILL BLOCK --}}
                                @if (env("DEMO_MODE") == "On")
                                    <div class="mb-4 p-3 bg-light rounded text-center border" style="background-color: #FAF7F2 !important; border-color: rgba(104, 91, 78, 0.15) !important;">
                                        <div class="fw-700 fs-12 mb-2" style="color: #39322a;">{{ translate('Demo Accounts') }}</div>
                                        <div class="d-flex flex-wrap justify-content-center gap-2">
                                            <button class="btn btn-xs px-3 py-1 btn-outline-secondary mr-2" onclick="autoFillCustomer()" style="background: transparent; color: #685b4e; border-color: #685b4e; font-size: 11px; font-weight: 600; border-radius: 20px;">
                                                {{ translate('Buyer Creds') }}
                                            </button>
                                            <button class="btn btn-xs px-3 py-1 btn-outline-secondary" onclick="autoFillSeller()" style="background: transparent; color: #685b4e; border-color: #685b4e; font-size: 11px; font-weight: 600; border-radius: 20px;">
                                                {{ translate('Seller Creds') }}
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                {{-- SOCIAL LOGINS --}}
                                @if(get_setting('google_login') || get_setting('facebook_login') || get_setting('apple_login'))
                                    <div id="social-login-wrap">
                                        <div class="text-center my-3 fs-12 text-muted">
                                            {{ translate('Or Login With') }}
                                        </div>

                                        <div class="text-center mb-4">
                                            @if(get_setting('facebook_login'))
                                                <a href="{{ route('social.login',['provider'=>'facebook']) }}" class="social-login-btn facebook">
                                                    <i class="lab la-facebook-f fs-18"></i>
                                                </a>
                                            @endif
                                            @if(get_setting('google_login'))
                                                <a href="{{ route('social.login',['provider'=>'google']) }}" class="social-login-btn google">
                                                    <i class="lab la-google fs-18"></i>
                                                </a>
                                            @endif
                                            @if(get_setting('apple_login'))
                                                <a href="{{ route('social.login',['provider'=>'apple']) }}" class="social-login-btn apple">
                                                    <i class="lab la-apple fs-18"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- BOTTOM LINK REGISTER --}}
                                <div class="text-center mt-2" id="register-link-wrap">
                                    <p class="fs-12 mb-1" style="color: #5d5247;">{{ translate("Don't have an account?") }}</p>
                                    <a href="{{ route('user.registration') }}" class="fw-700 text-decoration-underline" style="color: #685b4e;">
                                        {{ translate('Register Now') }}
                                    </a>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.login-card-container {
    background: #FAF7F2;
    min-height: 80vh;
}
.login-main-card {
    background: #ffffff;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(104, 91, 78, 0.06);
    border: 1px solid rgba(104, 91, 78, 0.1);
    overflow: hidden;
}
.login-image-side {
    background-size: cover;
    background-position: center;
    position: relative;
    min-height: 520px;
}
.login-image-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(180deg, rgba(104, 91, 78, 0.15) 0%, rgba(57, 50, 42, 0.8) 100%);
}
.login-form-side {
    padding: 3.5rem 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.role-switcher-container {
    background: #F3ECE4;
    border-radius: 30px;
    padding: 4px;
    display: inline-flex;
    position: relative;
}
.btn-role {
    border: none;
    background: transparent;
    color: #685b4e;
    border-radius: 30px;
    padding: 8px 26px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    outline: none !important;
    box-shadow: none !important;
}
.btn-role.active {
    background: #685b4e;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(104, 91, 78, 0.15);
}
.form-group-custom {
    position: relative;
    margin-bottom: 0.75rem;
}
.form-group-custom label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #8c7e70;
    margin-bottom: 8px;
    display: block;
}
.form-group-custom .form-control {
    border: 1px solid rgba(104, 91, 78, 0.25) !important;
    border-radius: 10px !important;
    padding: 12px 16px !important;
    height: 48px !important;
    background-color: #ffffff !important;
    box-shadow: none !important;
    font-size: 14px;
    color: #39322a;
    transition: all 0.3s ease;
}
.form-group-custom .form-control:focus {
    border-color: #685b4e !important;
    background-color: #ffffff !important;
}
.input-group-append-custom {
    position: absolute;
    right: 16px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    line-height: 1;
}
.input-group-append-custom i {
    font-size: 20px;
    color: #8c7e70;
    cursor: pointer;
    transition: color 0.2s ease;
}
.input-group-append-custom i:hover {
    color: #685b4e;
}
/* Autofill browser styling fix */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #ffffff inset !important;
    -webkit-text-fill-color: #39322a !important;
    transition: background-color 5000s ease-in-out 0s;
}
.btn-login {
    background: #685b4e;
    color: #ffffff !important;
    border: none;
    border-radius: 12px;
    padding: 12px 20px;
    font-size: 14.5px;
    font-weight: 700;
    letter-spacing: 0.5px;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    box-shadow: 0 8px 24px rgba(104, 91, 78, 0.15);
}
.btn-login:hover {
    background: #54493e;
    transform: translateY(-2px);
    box-shadow: 0 12px 30px rgba(104, 91, 78, 0.25);
    color: #ffffff !important;
}
.social-login-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(104, 91, 78, 0.15);
    background: #ffffff;
    color: #685b4e;
    margin: 0 6px;
    transition: all 0.3s ease;
    text-decoration: none !important;
}
.social-login-btn:hover {
    background: #F3ECE4;
    border-color: #685b4e;
    color: #685b4e !important;
    transform: translateY(-2px);
}
.social-login-btn.facebook:hover {
    background: #3b5998;
    color: #ffffff !important;
    border-color: #3b5998;
}
.social-login-btn.google:hover {
    background: #db4437;
    color: #ffffff !important;
    border-color: #db4437;
}
.social-login-btn.apple:hover {
    background: #000000;
    color: #ffffff !important;
    border-color: #000000;
}
.aiz-checkbox {
    padding-left: 22px;
}
.aiz-checkbox .aiz-square-check {
    border: 1px solid rgba(104, 91, 78, 0.3) !important;
    border-radius: 4px !important;
}
.aiz-checkbox input:checked ~ .aiz-square-check {
    border-color: #685b4e !important;
    background-color: #685b4e !important;
}
@media (max-width: 991.98px) {
    .login-form-side {
        padding: 3rem 2rem;
    }
}
@media (max-width: 575.98px) {
    .login-form-side {
        padding: 2rem 1.25rem;
    }
}
</style>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.btn-role').on('click', function() {
                var role = $(this).data('role');
                
                // Update active status in buttons
                $('.btn-role').removeClass('active');
                $(this).addClass('active');
                
                // Redesign UI titles/buttons for Buyer vs Seller
                if (role === 'buyer') {
                    $('#login-title').text("{{ translate('Buyer Login') }}");
                    $('#login-subtitle').text("{{ translate('Login to your Account') }}");
                    
                    $('#otp-toggle-wrap').removeClass('d-none');
                    $('#social-login-wrap').removeClass('d-none');
                    $('#register-link-wrap').html(`
                        <p class="fs-12 mb-1" style="color: #5d5247;">{{ translate("Don't have an account?") }}</p>
                        <a href="{{ route('user.registration') }}" class="fw-700 text-decoration-underline" style="color: #685b4e;">
                            {{ translate('Register Now') }}
                        </a>
                    `);
                    $('#submit-btn').text("{{ translate('Login As Buyer') }}");
                    
                    @if (addon_is_activated('otp_system'))
                        if (isPhoneShown) {
                            $('.phone-form-group').removeClass('d-none');
                            $('.email-form-group').addClass('d-none');
                        } else {
                            $('.phone-form-group').addClass('d-none');
                            $('.email-form-group').removeClass('d-none');
                        }
                    @else
                        $('.email-form-group').removeClass('d-none');
                    @endif
                } else if (role === 'seller') {
                    $('#login-title').text("{{ translate('Seller Login') }}");
                    $('#login-subtitle').text("{{ translate('Login to Seller Account') }}");
                    
                    $('#otp-toggle-wrap').addClass('d-none');
                    $('#social-login-wrap').addClass('d-none');
                    $('#register-link-wrap').html(`
                        <p class="fs-12 mb-1" style="color: #5d5247;">{{ translate("Don't have an account?") }}</p>
                        <a href="{{ route('shops.create') }}" class="fw-700 text-decoration-underline" style="color: #685b4e;">
                            {{ translate('Become a Seller') }}
                        </a>
                    `);
                    $('#submit-btn').text("{{ translate('Login As Seller') }}");
                    
                    $('.phone-form-group').addClass('d-none');
                    $('.email-form-group').removeClass('d-none');
                }
            });
        });

        function autoFillSeller(){
            $('.btn-role[data-role="seller"]').trigger('click');
            $('#email').val('seller@example.com');
            $('#password').val('123456');
        }

        function autoFillCustomer(){
            $('.btn-role[data-role="buyer"]').trigger('click');
            @if (addon_is_activated('otp_system'))
                if (isPhoneShown) {
                    $('#toggle-identifier-btn').trigger('click');
                }
            @endif
            $('#email').val('customer@example.com');
            $('#password').val('123456');
        }
        
        function autoFillDeliveryBoy(){
            $('.btn-role[data-role="buyer"]').trigger('click');
            @if (addon_is_activated('otp_system'))
                if (isPhoneShown) {
                    $('#toggle-identifier-btn').trigger('click');
                }
            @endif
            $('#email').val('deliveryboy@example.com');
            $('#password').val('123456');
        }
        
        function togglePassword(id) {
            const passwordInput = document.getElementById(id); 
            const toggleIcon = document.getElementById('password-toggle-icon');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove('la-eye');
                toggleIcon.classList.add('la-eye-slash');
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove('la-eye-slash');
                toggleIcon.classList.add('la-eye');
            }
        }
    </script>
@endsection
