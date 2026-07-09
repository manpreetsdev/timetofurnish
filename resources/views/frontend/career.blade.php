@extends('frontend.layouts.app')

@section('content')

<!-- Career Form Section -->
<section class="career-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-8 col-sm-10">

                <div class="career-card">

                    <div class="text-center career-heading">
                        <h1>Be Part of Our Team</h1>
                        <p>Apply Now</p>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('career.submit') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="name" required
                                   class="form-control"
                                   placeholder="Your Name"
                                   maxlength="30"
                                   onkeypress="return /[a-zA-Z]/i.test(event.key)">
                        </div>

                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" required
                                   class="form-control"
                                   placeholder="example@mail.com">
                        </div>

                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="tel" name="phone" required
                                   class="form-control"
                                   placeholder="Phone Number"
                                   pattern="[0-9]{1,14}"
                                   maxlength="14"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>

                        <div class="form-group">
                            <label>Role Looking For</label>
                            <input type="text" name="role" required
                                   class="form-control"
                                   placeholder="Role You Are Applying For"
                                   oninput="limitWords(this,20)">
                        </div>

                        <div class="form-group">
                            <label>Upload CV</label>
                            <input type="file" name="cv" accept=".pdf,.doc,.docx" required
                                   class="form-control file-input">
                        </div>

                        <button type="submit" class="career-submit-btn">
                            Submit Application
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>
</section>

<style>
.career-section {
    background: #FAF7F2;
    padding: 48px 0 54px;
}

.career-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 42px 40px 52px;
    box-shadow: 0 14px 35px rgba(0, 0, 0, 0.08);
}

.career-heading {
    margin-bottom: 24px;
}

.career-heading h1 {
    font-size: 20px;
    font-weight: 700;
    color: #000;
    margin-bottom: 4px;
}

.career-heading p {
    font-size: 13px;
    color: #000;
    margin-bottom: 0;
}

.career-card .form-group {
    margin-bottom: 16px;
}

.career-card label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 7px;
}

.career-card .form-control {
    height: 35px;
    border: 1px solid #dedede !important;
    border-radius: 6px !important;
    background: #ffffff;
    color: #333;
    font-size: 13px;
    padding: 8px 14px;
    box-shadow: none !important;
}

.career-card .form-control::placeholder {
    color: #8a8a8a;
}

.career-card .form-control:focus {
    border-color: #685b4e !important;
    box-shadow: 0 0 0 2px rgba(104, 91, 78, 0.12) !important;
}

.career-card .file-input {
    height: 53px;
    padding: 10px 14px;
}

.career-submit-btn {
    width: 185px;
    height: 40px;
    border: none;
    border-radius: 9px;
    background: #685b4e;
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    margin-top: 2px;
    box-shadow: 0 12px 20px rgba(104, 91, 78, 0.28);
    transition: all 0.2s ease;
}

.career-submit-btn:hover {
    background: #5b4f44;
    color: #ffffff;
    transform: translateY(-1px);
}

@media (max-width: 575px) {
    .career-section {
        padding: 35px 12px 45px;
    }

    .career-card {
        padding: 32px 22px 38px;
    }

    .career-submit-btn {
        width: 100%;
    }
}
</style>

<script>
function limitWords(field, maxWords) {
    let words = field.value.trim().split(/\s+/);

    if (field.value.trim() === '') {
        return;
    }

    if (words.length > maxWords) {
        field.value = words.slice(0, maxWords).join(" ");
    }
}
</script>

@endsection