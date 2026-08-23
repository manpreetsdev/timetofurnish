@extends('frontend.layouts.app')

@section('meta_title'){{ $metaTitle }}@stop
@section('meta_description'){{ $metaDescription }}@stop
@section('meta_robots', 'noindex, follow')

@section('content')
<section class="py-5" style="min-height: 60vh; display: flex; align-items: center; background: #faf7f2;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-6">
                <div class="bg-white border rounded shadow-sm p-4 p-md-5 text-center">
                    <h1 class="fs-24 fw-700 text-dark mb-3">{{ $heading }}</h1>
                    <p class="text-muted mb-4">{{ $body }}</p>
                    <a href="{{ route('user.login') }}" class="btn btn-primary px-4 py-2 rounded-0">
                        {{ translate('Login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
