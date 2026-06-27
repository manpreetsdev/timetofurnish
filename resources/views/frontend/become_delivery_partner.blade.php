@extends('frontend.layouts.app')

@section('content')

<section class="delivery-partner-page">

    <div class="container">

        <!-- Page Heading -->
        <div class="delivery-page-heading text-center">
            <h1>Become Our Delivery Partner</h1>

            <ul class="breadcrumb bg-transparent p-0 m-0 justify-content-center">
                <li class="breadcrumb-item has-transition opacity-50 hov-opacity-100">
                    <a class="text-reset" href="{{ route('home') }}">
                        {{ translate('Home') }}
                    </a>
                </li>
                <li class="text-dark fw-600 breadcrumb-item">
                    Become Our Delivery Partner
                </li>
            </ul>
        </div>

        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9 col-sm-11">

                <div class="delivery-form-card">

                    <div class="delivery-form-title text-center">
                        <h2>Join Our Delivery Team</h2>
                        <p>Fill out the form below and our team will contact you shortly.</p>
                    </div>

                    @if(session()->has('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('delivery.partner.submit') }}" method="POST" id="contact-form">
                        @csrf

                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text"
                                   name="company_name"
                                   required
                                   class="form-control"
                                   placeholder="Company Name">
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email"
                                   name="email"
                                   required
                                   class="form-control"
                                   placeholder="example@mail.com">
                        </div>

                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="tel"
                                   name="contact_number"
                                   required
                                   class="form-control"
                                   placeholder="Contact Number"
                                   maxlength="14"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>

                        <div class="form-group">
                            <label>Area of Coverage</label>
                            <textarea name="area_coverage"
                                      required
                                      class="form-control textarea-control"
                                      placeholder="Area of Coverage"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Services Provided</label>
                            <textarea name="services_provided"
                                      required
                                      class="form-control textarea-control"
                                      placeholder="Services Provided"></textarea>
                        </div>

                        <button type="submit" class="delivery-submit-btn">
                            Submit Request
                        </button>

                    </form>

                </div>

            </div>
        </div>

    </div>

</section>

<style>
.delivery-partner-page {
    background: #FAF7F2;
    padding: 45px 0 70px;
    min-height: 680px;
}

.delivery-page-heading {
    margin-bottom: 34px;
}

.delivery-page-heading h1 {
    font-size: 38px;
    font-weight: 500;
    color: #241b14;
    margin-bottom: 8px;
    line-height: 1.2;
    font-family: 'DM Serif Display', serif;
}

.delivery-page-heading .breadcrumb {
    font-size: 13px;
}

.delivery-form-card {
    background: #ffffff;
    border-radius: 18px;
    padding: 40px 42px 46px;
    box-shadow: 0 18px 45px rgba(104, 91, 78, 0.12);
    border: 1px solid rgba(104, 91, 78, 0.08);
}

.delivery-form-title {
    margin-bottom: 26px;
}

.delivery-form-title h2 {
    font-size: 24px;
    font-weight: 700;
    color: #39322a;
    margin-bottom: 6px;
}

.delivery-form-title p {
    font-size: 13px;
    color: #7c7165;
    margin-bottom: 0;
    line-height: 1.5;
}

.delivery-form-card .form-group {
    margin-bottom: 16px;
}

.delivery-form-card label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #39322a;
    margin-bottom: 7px;
}

.delivery-form-card .form-control {
    width: 100%;
    height: 44px;
    border: 1px solid #ded8d1 !important;
    border-radius: 8px !important;
    background: #ffffff;
    color: #333333;
    font-size: 14px;
    padding: 10px 15px;
    box-shadow: none !important;
    outline: none !important;
}

.delivery-form-card .textarea-control {
    height: 86px;
    resize: none;
    line-height: 1.5;
}

.delivery-form-card .form-control::placeholder {
    color: #9a928a;
}

.delivery-form-card .form-control:focus {
    border-color: #685b4e !important;
    box-shadow: 0 0 0 3px rgba(104, 91, 78, 0.12) !important;
}

.delivery-submit-btn {
    width: 185px;
    height: 43px;
    border: none !important;
    border-radius: 10px !important;
    background: #685b4e !important;
    color: #ffffff !important;
    font-size: 13px;
    font-weight: 700;
    margin-top: 6px;
    box-shadow: 0 12px 22px rgba(104, 91, 78, 0.25);
    transition: all 0.25s ease;
    cursor: pointer;
}

.delivery-submit-btn:hover {
    background: #574b40 !important;
    color: #ffffff !important;
    transform: translateY(-1px);
}

.delivery-form-card .alert {
    font-size: 13px;
    border-radius: 8px;
    margin-bottom: 18px;
}

/* Mobile Responsive */
@media (max-width: 767.98px) {
    .delivery-partner-page {
        padding: 32px 12px 48px;
        min-height: auto;
    }

    .delivery-page-heading {
        margin-bottom: 24px;
    }

    .delivery-page-heading h1 {
        font-size: 28px;
    }

    .delivery-form-card {
        padding: 30px 22px 34px;
        border-radius: 15px;
    }

    .delivery-form-title h2 {
        font-size: 22px;
    }

    .delivery-form-title p {
        font-size: 12px;
    }

    .delivery-submit-btn {
        width: 100%;
    }
}

@media (max-width: 420px) {
    .delivery-page-heading h1 {
        font-size: 25px;
    }

    .delivery-form-card {
        padding: 26px 18px 30px;
    }
}
</style>

@endsection