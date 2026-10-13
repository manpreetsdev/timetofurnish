@extends('frontend.layouts.app')
@section('meta_description', ' Manage your Time to Furnish account online. View your order history, track purchases, update your profile details, manage your wishlist, and access account settings.')
@section('meta_robots', 'noindex, nofollow')
@section('content')
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-start">
			@include('frontend.inc.user_side_nav')
			<div class="aiz-user-panel">
				@yield('panel_content')
            </div>
        </div>
    </div>
</section>
@endsection