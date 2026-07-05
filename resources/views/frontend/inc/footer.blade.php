<!-- footer Description -->
</div>
@if (get_setting('footer_title') != null || get_setting('footer_description') != null)
    <section class="bg-light border-top border-bottom mt-auto">
        <!--<h1>j</h1>-->
        <div class="container py-4">
            <h1 class="fs-18 fw-700 text-gray-dark mb-3">{{ get_setting('footer_title', null, $system_language->code) }}
            </h1>
            <p class="fs-13 text-gray-dark text-justify mb-0">
                {!! nl2br(get_setting('footer_description', null, $system_language->code)) !!}
            </p>
        </div>
    </section>
@endif


@php
    $col_values = 'ttf-footer-col col-md-6 col-sm-6';
@endphp
<section class="py-lg-5 text-light footer-widget ttf-footer-links-section">
<section class="py-4 text-light footer-widget iuytrey footer-newsletter-section">
    <div class="container">
        <div class="row align-items-center footer-newsletter-row">
            <!-- about & subscription -->
            <div class="col-lg-7  col-md-9 mx-auto text-center newsletter-column">
                <!--<div class="mb-4 text-secondary text-justify">
                    {!! get_setting('about_us_description', null, App::getLocale()) !!}
                </div>-->
                <h5 class="fs-14 fw-700 mb-3">
                    {{ translate('Subscribe to our newsletter for regular updates about Offers, Coupons  more') }}</h5>
                <div class="mb-3">
                    <form method="POST" action="{{ route('subscribers.store') }}">
                        @csrf
                        <div class="position-relative newsletter-form-wrap">
                            <input type="email" class="form-control w-100 email_input_footer"
                                placeholder="{{ translate('Your Email') }}" name="email" required
                                style="padding: 12px 160px 12px 24px; background: #fff; border:1.5px solid #eadfd3; color:#39322a;">
                            <button type="submit"
                                class="btn footer_submit_btn borderbtn position-absolute d-flex align-items-center justify-content-center"
                                style="right: 4px; top: 4px; bottom: 4px; min-width: 130px; background:#685b4e; color:#fff; border:none;">
                                <span class="d-sm-block  d-lg-block">{{ translate('Subscribe') }}</span>
                                <!-- <i class="las la-arrow-right d-sm-none" style="font-size: 20px;"></i> -->
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!--col-xxl-6-->



           



        </div>
    </div>
</section>
    <!-- Desktop Footer Widgets -->
    <div class="container d-none d-lg-block">
        <div class="row gutters-20">

            <!-- Quick Links -->
            <div class="{{ $col_values }}">
                <div class="ttf-footer-card">
                    <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                        Quick Links
                    </h4>

                    <ul class="list-unstyled">
                        <li>
                            <a href="{{ url('') }}" class="fs-13 text-light animate-underline-white">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('about-us') }}" class="fs-13 text-light animate-underline-white">
                                About Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('categories') }}" class="fs-13 text-light animate-underline-white">
                                Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('blog') }}" class="fs-13 text-light animate-underline-white">
                                Blogs
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('contact-us') }}" class="fs-13 text-light animate-underline-white">
                                Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('career') }}" class="fs-13 text-light animate-underline-white">
                                Careers
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('meet.the.team') }}" class="fs-13 text-light animate-underline-white">
                                Meet Our Team
                            </a>
                        </li>
						 <li>
                            <a href="{{ route('become_delivery_partner') }}" class="fs-13 text-light animate-underline-white">
                                Join Our Delivery Partner 
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Important Links -->
            <div class="{{ $col_values }}">
                <div class="ttf-footer-card">
                    <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                        {{ translate('Important Links') }}
                    </h4>

                    <ul class="list-unstyled">
                        @php
                            $pages = get_pages_footer('2,3,4,5,6,7,8,10,11');
                        @endphp

                        @foreach ($pages as $key => $value)
                            <li>
                                <a href="{{ url($value->slug) }}" class="fs-13 text-light animate-underline-white">
                                    {{ $value->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- My Account -->
            <div class="{{ $col_values }}">
                <div class="ttf-footer-card">
                    <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                        {{ translate('My Account') }}
                    </h4>

                    <ul class="list-unstyled">
                        @if (Auth::check())
                            <li>
                                <a class="fs-13 text-light animate-underline-white" href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="fs-13 text-light animate-underline-white" href="{{ route('user.login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endif

                        <li>
                            <a class="fs-13 text-light animate-underline-white"
                                href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>

                        <li>
                            <a class="fs-13 text-light animate-underline-white"
                                href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>

                        <li>
                            <a class="fs-13 text-light animate-underline-white"
                                href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>

                        @if (addon_is_activated('affiliate_system'))
                            <li>
                                <a class="fs-13 text-light animate-underline-white"
                                    href="{{ route('affiliate.apply') }}">
                                    {{ translate('Be an affiliate partner') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Seller & Delivery Boy -->
            @if (get_setting('vendor_system_activation') == 1 || addon_is_activated('delivery_boy'))
                <div class="ttf-footer-col col-md-6 col-sm-6">
                    <div class="ttf-footer-card">

                        @if (get_setting('vendor_system_activation') == 1)
                            <h4 class="fs-14 text-light text-uppercase fw-700 mb-3 textheading">
                                {{ translate('Seller Zone') }}
                            </h4>

                            <ul class="list-unstyled">
                                <li>
                                   
                                </li>

                                @guest
                                    <li>
                                        <a class="fs-13 text-light animate-underline-white"
                                            href="{{ route('seller.login') }}">
                                            {{ translate('Login to Seller Panel') }}
                                        </a>
                                    </li>
                                @endguest

                                @if (get_setting('seller_app_link'))
                                    <li>
                                        <a class="fs-13 text-light animate-underline-white"
                                            target="_blank"
                                            href="{{ get_setting('seller_app_link') }}">
                                            {{ translate('Download Seller App') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif

                        @if (addon_is_activated('delivery_boy'))
                            <h4 class="fs-14 text-light text-uppercase fw-700 mt-4 mb-3">
                                {{ translate('Delivery Boy') }}
                            </h4>

                            <ul class="list-unstyled">
                                @guest
                                    <li>
                                        <a class="fs-13 text-light animate-underline-white"
                                            href="{{ route('deliveryboy.login') }}">
                                            {{ translate('Login to Delivery Boy Panel') }}
                                        </a>
                                    </li>
                                @endguest

                                @if (get_setting('delivery_boy_app_link'))
                                    <li>
                                        <a class="fs-13 text-light animate-underline-white"
                                            target="_blank"
                                            href="{{ get_setting('delivery_boy_app_link') }}">
                                            {{ translate('Download Delivery Boy App') }}
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @endif

                        <div class="ttf-partner-box mt-4">
                            <h4 class="fs-14 text-light fw-700 mb-3 partner-network-title textheading">
                                Join Our Partner Network
                            </h4>

                            <ul class="list-unstyled">
                                <li>
                                  {{-- <a href="{{ route('become_delivery_partner') }}"
                                        class="fs-13 text-light animate-underline-white">
                                        {{ translate('Send us Request') }}
                                    </a>--}}
									 <a href="{{ route('shops.create') }}"
                                        class="fs-13 text-light animate-underline-white">
                                        {{ translate('Become A Seller') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
</div>
                    
                </div>
            @endif
            <!-- Delivery Partners / Payments / Trustpilot -->
<div class="ttf-footer-col col-md-6 col-sm-6">
    <div class="ttf-footer-card">

        <div class="secure-payment-box mb-3">
            <h5 class="secure-payment-title textheading">
                Delivery Partners
            </h5>

            <img 
                src="{{ asset('assets/img/delivery_partners_logo.png') }}" 
                alt="Delivery Partners" 
                class="secure-payment-img"
            >
        </div>

       <div class="secure-payment-box mb-3">
    <h5 class="secure-payment-title textheading">
        Pay Securely With
    </h5>

    @if (get_setting('payment_method_images') != null)
        <ul class="list-inline mb-0 secure-payment-list">
            @foreach (explode(',', get_setting('payment_method_images')) as $key => $value)
                <li class="list-inline-item visa-card">
                    <img 
                        src="{{ uploaded_asset($value) }}" 
                        alt="{{ translate('payment_method') }}"
                        class="secure-payment-img"
                    >
                </li>
            @endforeach
        </ul>
    @endif
</div>

        <div class="secure-payment-box">
            <h5 class="secure-payment-title textheading">
                What Trustpilot Say’s
            </h5>

            <img 
                src="{{ asset('assets/img/trustpilot.png') }}" 
                alt="Trustpilot Reviews" 
                class="secure-payment-img trustpilot-img"
            >
        </div>

    </div>
</div>

        </div>
    </div>

    <!-- Mobile Accordion Footer -->
    <div class="d-lg-none bg-transparent ttf-mobile-footer">

        <!-- Quick Links -->
        <div class="aiz-accordion-wrap ttf-mobile-accordion">
            <div class="aiz-accordion-heading container">
                <button class="aiz-accordion fs-14 text-white bg-transparent">Quick Links</button>
            </div>

            <div class="aiz-accordion-panel bg-transparent">
                <div class="container">
                    <ul class="list-unstyled">
                        <li class="mb-2 pb-2">
                            <a href="{{ asset('') }}" class="fs-13 text-light animate-underline-white">
                                Home
                            </a>
                        </li>
                        <li class="mb-2 pb-2">
                            <a href="http://localhost:8082/multivendor/categories"
                                class="fs-13 text-light animate-underline-white">
                                Categories
                            </a>
                        </li>
                        <li class="mb-2 pb-2">
                            <a href="{{ url('about-us') }}" class="fs-13 text-light animate-underline-white">
                                About Us
                            </a>
                        </li>
                        <li class="mb-2 pb-2">
                            <a href="http://localhost:8082/multivendor/brands"
                                class="fs-13 text-light animate-underline-white">
                                Blogs
                            </a>
                        </li>
                        <li class="mb-2 pb-2">
                            <a href="http://localhost:8082/multivendor/contact-us"
                                class="fs-13 text-light animate-underline-white">
                                Contact Us
                            </a>
                        </li>
                        <li class="mb-2 pb-2">
                            <a href="{{ route('career') }}" class="fs-13 text-light animate-underline-white">
                                Careers
                            </a>
                        </li>
                        <li class="mb-2 pb-2">
                            <a href="{{ route('meet.the.team') }}" class="fs-13 text-light animate-underline-white">
                                Meet Our Team
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>


        <!-- Important Links -->
        <div class="aiz-accordion-wrap ttf-mobile-accordion">
            <div class="aiz-accordion-heading container">
                <button class="aiz-accordion fs-14 text-white bg-transparent">Important Links</button>
            </div>

            <div class="aiz-accordion-panel bg-transparent">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        @php
                            $pages = get_pages_footer('2,3,4,5,6,7,8,10,11');
                        @endphp

                        @foreach ($pages as $key => $value)
                            <li class="mb-2">
                                <a href="{{ url($value->slug) }}" class="fs-13 text-light animate-underline-white">
                                    {{ $value->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Contacts -->
        <div class="aiz-accordion-wrap ttf-mobile-accordion">
            <div class="aiz-accordion-heading container">
                <button class="aiz-accordion fs-14 text-white bg-transparent">
                    {{ translate('Contacts') }}
                </button>
            </div>

            <div class="aiz-accordion-panel bg-transparent">
                <div class="container mb-3">
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2">
                            <p class="fs-13 mb-2 text-white">{{ translate('Address') }}</p>
                            <p class="fs-13 text-white">
                                20 Wenlock Road<br>
                                London, England <br>
                                N1 7GU
                            </p>
                            <p class="fs-13 text-light">
                                <strong>Registered VAT NO :</strong> 519774256
                            </p>
                        </li>

                        <li class="mb-3">
                            <p class="fs-13 mb-1">{{ translate('WhatsApp') }}</p>

                            <a href="https://wa.me/447751510365"
                                target="_blank"
                                class="fs-13 text-light d-flex align-items-center mb-3">
                                <i class="lab la-whatsapp mr-2" style="font-size:16px;"></i>
                                <span>+44 7751510365</span>
                            </a>
                        </li>

                        <li class="mb-2">
                            <p class="fs-13 mb-2">{{ translate('Email') }}</p>
                            <p>
                                <a href="mailto:{{ get_setting('contact_email') }}"
                                    class="fs-13 text-light hov-text-primary">
                                    {{ get_setting('contact_email') }}
                                </a>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- My Account -->
        <div class="aiz-accordion-wrap ttf-mobile-accordion">
            <div class="aiz-accordion-heading container">
                <button class="aiz-accordion fs-14 text-white bg-transparent">
                    {{ translate('My Account') }}
                </button>
            </div>

            <div class="aiz-accordion-panel bg-transparent">
                <div class="container">
                    <ul class="list-unstyled mt-3">
                        @auth
                            <li class="mb-2 pb-2">
                                <a class="fs-13 text-light animate-underline-white" href="{{ route('logout') }}">
                                    {{ translate('Logout') }}
                                </a>
                            </li>
                        @else
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['user.login'], ' active') }}">
                                <a class="fs-13 text-light animate-underline-white" href="{{ route('user.login') }}">
                                    {{ translate('Login') }}
                                </a>
                            </li>
                        @endauth

                        <li class="mb-2 pb-2 {{ areActiveRoutes(['purchase_history.index'], ' active') }}">
                            <a class="fs-13 text-light animate-underline-white"
                                href="{{ route('purchase_history.index') }}">
                                {{ translate('Order History') }}
                            </a>
                        </li>

                        <li class="mb-2 pb-2 {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            <a class="fs-13 text-light animate-underline-white"
                                href="{{ route('wishlists.index') }}">
                                {{ translate('My Wishlist') }}
                            </a>
                        </li>

                        <li class="mb-2 pb-2 {{ areActiveRoutes(['orders.track'], ' active') }}">
                            <a class="fs-13 text-light animate-underline-white"
                                href="{{ route('orders.track') }}">
                                {{ translate('Track Order') }}
                            </a>
                        </li>

                        @if (addon_is_activated('affiliate_system'))
                            <li class="mb-2 pb-2 {{ areActiveRoutes(['affiliate.apply'], ' active') }}">
                                <a class="fs-13 text-light animate-underline-white"
                                    href="{{ route('affiliate.apply') }}">
                                    {{ translate('Be an affiliate partner') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Seller -->
        @if (get_setting('vendor_system_activation') == 1)
            <div class="aiz-accordion-wrap ttf-mobile-accordion">
                <div class="aiz-accordion-heading container">
                    <button class="aiz-accordion fs-14 text-white bg-transparent">
                        {{ translate('Seller Zone') }}
                    </button>
                </div>

                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                           {{-- <li class="mb-2 pb-2">
                               <a href="{{ route('shops.create') }}"
                                    class="fs-13 text-light animate-underline-white">
                                    {{ translate('Become A Seller') }}
                                </a>
                            </li>--}}

                            @guest
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                    <a class="fs-13 text-light animate-underline-white"
                                        href="{{ route('seller.login') }}">
                                        {{ translate('Login to Seller Panel') }}
                                    </a>
                                </li>
                            @endguest

                            @if (get_setting('seller_app_link'))
                                <li class="mb-2 pb-2">
                                    <a class="fs-13 text-light animate-underline-white"
                                        target="_blank"
                                        href="{{ get_setting('seller_app_link') }}">
                                        {{ translate('Download Seller App') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="aiz-accordion-wrap ttf-mobile-accordion">
                <div class="aiz-accordion-heading container">
                    <button class="aiz-accordion fs-14 text-white bg-transparent">
                        {{ translate('Join Our Partner Network') }}
                    </button>
                </div>

                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            <li class="mb-2">
                             {{--   <a href="{{ route('become_delivery_partner') }}"
                                    class="fs-13 text-light animate-underline-white">
                                    {{ translate('Send us Request') }}
                                </a>--}}
								 <a href="{{ route('shops.create') }}"
                                        class="fs-13 text-light animate-underline-white">
                                        {{ translate('Become A Seller') }}
                                    </a>
                            </li>
                        </ul>
                    </div>
                </div>
				
				
				
				
            </div>
        @endif

		
		<!-- Join Delivery Partner - Direct Link -->
<div class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-direct-link-wrap">
    <div class="aiz-accordion-heading container">
        <a href="{{ route('become_delivery_partner') }}"
           class="ttf-mobile-direct-link bg-transparent">
            {{ translate('Join Our Delivery Partner') }}
        </a>
    </div>
</div>

<!-- Careers - Direct Link -->
<div class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-direct-link-wrap">
    <div class="aiz-accordion-heading container">
        <a href="{{ route('career') }}"
           class="ttf-mobile-direct-link bg-transparent">
            {{ translate('Careers') }}
        </a>
    </div>
</div>

<!-- Meet Our Team - Direct Link -->
<div class="aiz-accordion-wrap ttf-mobile-accordion ttf-mobile-direct-link-wrap">
    <div class="aiz-accordion-heading container">
        <a href="{{ route('meet.the.team') }}"
           class="ttf-mobile-direct-link bg-transparent">
            {{ translate('Meet Our Team') }}
        </a>
    </div>
</div>
<div class="secure-payment-box mt-3 ms-2">
    <h5 class="secure-payment-title">
        Pay Securely With
    </h5>

    <img 
        src="{{ asset('assets/img/securelypayments.png') }}" 
        alt="Pay Securely With" 
        class="secure-payment-img"
    >
</div>

        <!-- Delivery Boy -->
        @if (addon_is_activated('delivery_boy'))
            <div class="aiz-accordion-wrap ttf-mobile-accordion">
                <div class="aiz-accordion-heading container">
                    <button class="aiz-accordion fs-14 text-white bg-transparent">
                        {{ translate('Delivery Boy') }}
                    </button>
                </div>

                <div class="aiz-accordion-panel bg-transparent">
                    <div class="container">
                        <ul class="list-unstyled mt-3">
                            @guest
                                <li class="mb-2 pb-2 {{ areActiveRoutes(['deliveryboy.login'], ' active') }}">
                                    <a class="fs-13 text-light animate-underline-white"
                                        href="{{ route('deliveryboy.login') }}">
                                        {{ translate('Login to Delivery Boy Panel') }}
                                    </a>
                                </li>
                            @endguest

                            @if (get_setting('delivery_boy_app_link'))
                                <li class="mb-2 pb-2">
                                    <a class="fs-13 text-light animate-underline-white"
                                        target="_blank"
                                        href="{{ get_setting('delivery_boy_app_link') }}">
                                        {{ translate('Download Delivery Boy App') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        @endif

    </div>


    <div class="container px-xs-0">
      <div class="row bottom-div">
          <div class="col-lg-6 order-1 order-lg-0 mt-2  mb-sm-50">
                <div class=" text-justify fs-14" current-verison="{{ get_setting('current_version') }}">
                    <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                        Copyright © 2025 Time to Furnish. All Right Reserved. 
                    </p>
                </div>
            </div>


    <div class="col-lg-6 order-1 order-lg-0 mt-2  mb-sm-50">
        <div class=" text-justify fs-14" current-verison="{{ get_setting('current_version') }}">
            <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                <span class="footer-text-short">We operate as an independent third-party marketplace and are not liable...</span>
                <span class="footer-text-full d-none">We operate as an independent third-party marketplace and are not liable for the accuracy, originality, or legality of any images or content uploaded by sellers. All such materials are the sole responsibility of the seller, including any content copied or reproduced from external platforms. Please read our <a href="/seller-terms-conditions" target="_blank" rel="noopener"><b>Terms and Conditions</b></a>.</span>
                <a href="javascript:void(0);" class="footer-read-more-btn ml-1" style="color: #685b4e; font-weight: 700; font-size: 13px; text-decoration: underline;">Read More</a>
            </p>
        </div>
    </div>

      </div>
</div>
</section>

<!-- FOOTER -->

<!--<section class= mt-auto">-->

<!--</section>-->









<!-- Mobile bottom nav -->
<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom border-top border-sm-bottom border-sm-left border-sm-right mx-auto mb-sm-2"
    style="background-color: #fff !important;">
    <div class="row align-items-center gutters-5">
        <!-- Home -->
        <div class="col">
            <a href="{{ route('home') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['home'], 'svg-active') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_24768" data-name="Group 24768" transform="translate(3495.144 -602)">
                        <path id="Path_2916" data-name="Path 2916"
                            d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                            transform="translate(-3495.144 602)" fill="#b5b5bf" />
                    </g>
                </svg>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['home'], 'text-primary') }}">{{ translate('Home') }}</span>
            </a>
        </div>

        <!-- Categories -->
        <div class="col">
            <a href="{{ route('categories.all') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['categories.all'], 'svg-active') }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                    <g id="Group_25497" data-name="Group 25497" transform="translate(3373.432 -602)">
                        <path id="Path_2917" data-name="Path 2917"
                            d="M126.713,0h-5V5a2,2,0,0,0,2,2h3a2,2,0,0,0,2-2V2a2,2,0,0,0-2-2m1,5a1,1,0,0,1-1,1h-3a1,1,0,0,1-1-1V1h4a1,1,0,0,1,1,1Z"
                            transform="translate(-3495.144 602)" fill="#91919c" />
                        <path id="Path_2918" data-name="Path 2918"
                            d="M144.713,18h-3a2,2,0,0,0-2,2v3a2,2,0,0,0,2,2h5V20a2,2,0,0,0-2-2m1,6h-4a1,1,0,0,1-1-1V20a1,1,0,0,1,1-1h3a1,1,0,0,1,1,1Z"
                            transform="translate(-3504.144 593)" fill="#91919c" />
                        <path id="Path_2919" data-name="Path 2919"
                            d="M143.213,0a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5"
                            transform="translate(-3504.144 602)" fill="#91919c" />
                        <path id="Path_2920" data-name="Path 2920"
                            d="M125.213,18a3.5,3.5,0,1,0,3.5,3.5,3.5,3.5,0,0,0-3.5-3.5m0,6a2.5,2.5,0,1,1,2.5-2.5,2.5,2.5,0,0,1-2.5,2.5"
                            transform="translate(-3495.144 593)" fill="#91919c" />
                    </g>
                </svg>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['categories.all'], 'text-primary') }}">{{ translate('Categories') }}</span>
            </a>
        </div>
        <!-- Cart -->
        @php
            $count = count(get_user_cart());
        @endphp
        <div class="col-auto">
            <a href="{{ route('cart') }}"
                class="text-secondary d-block text-center pb-2 pt-3 px-3 {{ areActiveRoutes(['cart'], 'svg-active') }}">
                <span class="d-inline-block position-relative px-2">
                    <svg id="Group_25499" data-name="Group 25499" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="16.001" height="16"
                        viewBox="0 0 16.001 16">
                        <defs>
                            <clipPath id="clip-pathw">
                                <rect id="Rectangle_1383" data-name="Rectangle 1383" width="16" height="16"
                                    fill="#91919c" />
                            </clipPath>
                        </defs>
                        <g id="Group_8095" data-name="Group 8095" transform="translate(0 0)"
                            clip-path="url(#clip-pathw)">
                            <path id="Path_2926" data-name="Path 2926"
                                d="M8,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                transform="translate(-3 -11.999)" fill="#91919c" />
                            <path id="Path_2927" data-name="Path 2927"
                                d="M24,24a2,2,0,1,0,2,2,2,2,0,0,0-2-2m0,3a1,1,0,1,1,1-1,1,1,0,0,1-1,1"
                                transform="translate(-10.999 -11.999)" fill="#91919c" />
                            <path id="Path_2928" data-name="Path 2928"
                                d="M15.923,3.975A1.5,1.5,0,0,0,14.5,2h-9a.5.5,0,1,0,0,1h9a.507.507,0,0,1,.129.017.5.5,0,0,1,.355.612l-1.581,6a.5.5,0,0,1-.483.372H5.456a.5.5,0,0,1-.489-.392L3.1,1.176A1.5,1.5,0,0,0,1.632,0H.5a.5.5,0,1,0,0,1H1.544a.5.5,0,0,1,.489.392L3.9,9.826A1.5,1.5,0,0,0,5.368,11h7.551a1.5,1.5,0,0,0,1.423-1.026Z"
                                transform="translate(0 -0.001)" fill="#91919c" />
                        </g>
                    </svg>
                    @if ($count > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['cart'], 'text-primary') }}">
                    {{ translate('Cart') }}
                    (<span class="cart-count">{{ $count }}</span>)
                </span>
            </a>
        </div>

        <!-- Notifications -->
        <div class="col">
            <a href="{{ route('all-notifications') }}"
                class="text-secondary d-block text-center pb-2 pt-3 {{ areActiveRoutes(['all-notifications'], 'svg-active') }}">
                <span class="d-inline-block position-relative px-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13.6" height="16" viewBox="0 0 13.6 16">
                        <path id="ecf3cc267cd87627e58c1954dc6fbcc2"
                            d="M5.488,14.056a.617.617,0,0,0-.8-.016.6.6,0,0,0-.082.855A2.847,2.847,0,0,0,6.835,16h0l.174-.007a2.846,2.846,0,0,0,2.048-1.1h0l.053-.073a.6.6,0,0,0-.134-.782.616.616,0,0,0-.862.081,1.647,1.647,0,0,1-.334.331,1.591,1.591,0,0,1-2.222-.331H5.55ZM6.828,0C4.372,0,1.618,1.732,1.306,4.512h0v1.45A3,3,0,0,1,.6,7.37a.535.535,0,0,0-.057.077A3.248,3.248,0,0,0,0,9.088H0l.021.148a3.312,3.312,0,0,0,.752,2.2,3.909,3.909,0,0,0,2.5,1.232,32.525,32.525,0,0,0,7.1,0,3.865,3.865,0,0,0,2.456-1.232A3.264,3.264,0,0,0,13.6,9.249h0v-.1a3.361,3.361,0,0,0-.582-1.682h0L12.96,7.4a3.067,3.067,0,0,1-.71-1.408h0V4.54l-.039-.081a.612.612,0,0,0-1.132.208h0v1.45a.363.363,0,0,0,0,.077,4.21,4.21,0,0,0,.979,1.957,2.022,2.022,0,0,1,.312,1h0v.155a2.059,2.059,0,0,1-.468,1.373,2.656,2.656,0,0,1-1.661.788,32.024,32.024,0,0,1-6.87,0,2.663,2.663,0,0,1-1.7-.824,2.037,2.037,0,0,1-.447-1.33h0V9.151a2.1,2.1,0,0,1,.305-1.007A4.212,4.212,0,0,0,2.569,6.187a.363.363,0,0,0,0-.077h0V4.653a4.157,4.157,0,0,1,4.2-3.442,4.608,4.608,0,0,1,2.257.584h0l.084.042A.615.615,0,0,0,9.649,1.8.6.6,0,0,0,9.624.739,5.8,5.8,0,0,0,6.828,0Z"
                            fill="#91919b" />
                    </svg>
                    @if (Auth::check() && count(Auth::user()->unreadNotifications) > 0)
                        <span
                            class="badge badge-sm badge-dot badge-circle badge-primary position-absolute absolute-top-right"
                            style="right: 5px;top: -2px;"></span>
                    @endif
                </span>
                <span
                    class="d-block mt-1 fs-10 fw-600 text-reset {{ areActiveRoutes(['all-notifications'], 'text-primary') }}">{{ translate('Notifications') }}</span>
            </a>
        </div>

        <!-- Account -->
        <div class="col">
            @if (Auth::check())
                @if (isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if ($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @elseif(isSeller())
                    <a href="{{ route('dashboard') }}" class="text-secondary d-block text-center pb-2 pt-3">
                        <span class="d-block mx-auto">
                            @if ($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @else
                    <a href="javascript:void(0)"
                        class="text-secondary d-block text-center pb-2 pt-3 mobile-side-nav-thumb"
                        data-toggle="class-toggle" data-backdrop="static" data-target=".aiz-mobile-side-nav">
                        <span class="d-block mx-auto">
                            @if ($user->avatar_original != null)
                                <img src="{{ $user_avatar }}" alt="{{ translate('avatar') }}"
                                    class="rounded-circle size-20px">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                    alt="{{ translate('avatar') }}" class="rounded-circle size-20px">
                            @endif
                        </span>
                        <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                    </a>
                @endif
            @else
                <a href="{{ route('user.login') }}" class="text-secondary d-block text-center pb-2 pt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <g id="Group_8094" data-name="Group 8094" transform="translate(3176 -602)">
                            <path id="Path_2924" data-name="Path 2924"
                                d="M331.144,0a4,4,0,1,0,4,4,4,4,0,0,0-4-4m0,7a3,3,0,1,1,3-3,3,3,0,0,1-3,3"
                                transform="translate(-3499.144 602)" fill="#b5b5bf" />
                            <path id="Path_2925" data-name="Path 2925"
                                d="M332.144,20h-10a3,3,0,0,0,0,6h10a3,3,0,0,0,0-6m0,5h-10a2,2,0,0,1,0-4h10a2,2,0,0,1,0,4"
                                transform="translate(-3495.144 592)" fill="#b5b5bf" />
                        </g>
                    </svg>
                    <span class="d-block mt-1 fs-10 fw-600 text-reset">{{ translate('My Account') }}</span>
                </a>
            @endif
        </div>

    </div>
</div>

@if (Auth::check() && !isAdmin())
    <!-- User Side nav -->
    <div class="aiz-mobile-side-nav collapse-sidebar-wrap sidebar-xl d-xl-none z-1035">
        <div class="overlay dark c-pointer overlay-fixed" data-toggle="class-toggle" data-backdrop="static"
            data-target=".aiz-mobile-side-nav" data-same=".mobile-side-nav-thumb"></div>
        <div class="collapse-sidebar bg-white">
            @include('frontend.inc.user_side_nav')
        </div>
    </div>
@endif


<style>
    .borderbtn:hover {
        color: black !important;
    }

    @media (max-width: 576px) {
        .footer-content {
            margin-bottom: 70px;
            padding-bottom: 10px;
        }

        footer {
            padding-bottom: 1rem !important;
        }
    }

    @media (max-width:576px) {
        .copyright {
            text-align: center;
        }

    }


    img.pilot {
        width: 170px;
        height: 30px;
    }

    @media (max-width: 576px) {
        img.pilot {
            width: 119px;
            height: auto;
            margin: 0px;
            justify-content: center !important;
            text-align: center;
        }
    }

    .logo {
        width: 60px;
        height: 60px;
        object-fit: contain;
        /* image distortion avoid karne ke liye */
    }

    /* Wrapper */
    .footer-flag-wrapper {
        position: relative;
        display: inline-block;
    }

    /* Button */
    .flag-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .flag-icon {
        width: 32px;
    }

    /* Dropdown */


    .flag-dropdown a {
        display: flex;
        align-items: center;
        gap: 10px;

        padding: 10px 14px;
        font-size: 14px;
        color: #000;
        text-decoration: none;

        background: #fff;
    }

    .flag-dropdown a:hover {
        background-color: #f5f5f5;
    }

    .flag-dropdown img {
        width: 20px;
        height: auto;
    }


    .green-star {
        color: #00b67a;
        margin-right: 5px;
        font-size: 22px;
    }
	
	/* ==============================
       Footer Links Section Only
    ============================== */

    .ttf-footer-links-section {
          background-image: url("/assets/img/footer-bg-image.jpeg");
        position: relative;
        overflow: hidden;
        padding-top: 45px !important;
        padding-bottom: 45px !important;
    }

    .ttf-footer-links-section::before {
        content: "";
        position: absolute;
        top: -90px;
        right: -90px;
        width: 250px;
        height: 250px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.06);
    }

    .ttf-footer-links-section::after {
        content: "";
        position: absolute;
        bottom: -120px;
        left: -120px;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.045);
    }

    .ttf-footer-links-section .container {
        position: relative;
        z-index: 2;
    }

    .ttf-footer-card {
       
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 22px;
        padding: 26px 24px;
        min-height: 300px;
        backdrop-filter: blur(8px);
        transition: all 0.3s ease;
        font-family: 'Poppins';
    }

   

    .ttf-footer-card h4 {
        position: relative;
        padding-bottom: 13px;
        margin-bottom: 18px !important;
        letter-spacing: 0.7px;
    }

    .ttf-footer-card h4::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 45px;
        height: 2px;
        background: #876a4b;
        border-radius: 20px;
    }

    .ttf-footer-card ul {
        margin-bottom: 0;
    }

    .ttf-footer-card ul li {
        margin-bottom: 11px;
    }

    .ttf-footer-card ul li:last-child {
        margin-bottom: 0;
    }

    .ttf-footer-card a {
        color: #000 !important;
        line-height: 1.6;
        display: inline-block;
        transition: all 0.25s ease;
    }

    .ttf-footer-card a:hover {
        color: #000 !important;
        padding-left: 6px;
    }

    .ttf-partner-box {
        border-top: 1px solid rgba(255, 255, 255, 0.13);
        padding-top: 18px;
    }

    /* Mobile Accordion Design */
    .ttf-mobile-footer {
        
        padding: 12px 0;
    }

    .ttf-mobile-accordion {
        background: transparent !important;
      border-bottom: 1px solid rgba(255, 255, 255, 0.39);
    }

    .ttf-mobile-accordion .aiz-accordion-heading {
        background: transparent !important;
    }

   

    .ttf-mobile-accordion .aiz-accordion-panel {
        background: rgba(255, 255, 255, 0.045) !important;
    }

    .ttf-mobile-accordion a {
        color: #393939 !important;
    }

    .ttf-mobile-accordion a:hover {
        color: #393939  !important;
    }

    @media (max-width: 991px) {
        .ttf-footer-links-section {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    }
	/* Mobile footer direct link - same accordion style, no dropdown */
.ttf-mobile-single-link-wrap .ttf-mobile-single-link {
    display: block;
    width: 100%;
    padding: 22px 0;
    color: #fff !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    line-height: normal;
    text-decoration: none !important;
    background: transparent !important;
    border: 0 !important;
}

/* hover same rakho */
.ttf-mobile-single-link-wrap .ttf-mobile-single-link:hover {
    color: #fff !important;
    text-decoration: none !important;
}

/* is section ka plus icon hide */
.ttf-mobile-single-link-wrap .ttf-mobile-single-link::after {
    display: none !important;
    content: none !important;
}
	/* Careers direct link - same accordion row style */
.ttf-mobile-career-link-wrap .ttf-mobile-career-link {
    display: block;
    width: 100%;
    padding: 22px 0;
    color: #fff !important;
    font-size: 14px !important;
    font-weight: 700 !important;
    line-height: normal;
    text-decoration: none !important;
    background: transparent !important;
    border: none !important;
}

/* plus icon hide for careers */
.ttf-mobile-career-link-wrap .aiz-accordion-heading::after,
.ttf-mobile-career-link-wrap .ttf-mobile-career-link::after {
    display: none !important;
    content: none !important;
}

.ttf-mobile-career-link-wrap .ttf-mobile-career-link:hover {
    color: #fff !important;
    text-decoration: none !important;
}


/* ===== Desktop footer 5 columns fix ===== */
@media (min-width: 992px) {
    .ttf-footer-links-section .row.gutters-20 {
        display: flex;
        flex-wrap: wrap;
    }

    .ttf-footer-links-section .ttf-footer-col {
        flex: 0 0 20% !important;
        max-width: 20% !important;
        padding-left: 10px;
        padding-right: 10px;
    }
}

.ttf-footer-links-section .ttf-footer-card {
    min-height: auto !important;
}

.secure-payment-title {
    font-size: 14px;
    font-weight: 700;
    color: #000;
    margin-bottom: 12px;
    position: relative;
    padding-bottom: 10px;
}

.secure-payment-title::after {
    content: "";
    position: absolute;
    left: 0;
    bottom: 0;
    width: 45px;
    height: 2px;
    background: #876a4b;
    border-radius: 20px;
}

.secure-payment-img {
    max-width: 100%;
    height: auto;
    background: #fff;
    border-radius: 6px;
}

.trustpilot-img {
    max-width: 150px;
}

</style>
<script>
    function toggleFlags(event) {
        event.stopPropagation();

        const dropdown = document.getElementById('flagDropdown');
        dropdown.style.display =
            dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Outside click close
    document.addEventListener('click', function() {
        document.getElementById('flagDropdown').style.display = 'none';
    });

    // Footer read more/less toggle
    document.addEventListener('DOMContentLoaded', function() {
        const readMoreBtn = document.querySelector('.footer-read-more-btn');
        const shortText = document.querySelector('.footer-text-short');
        const fullText = document.querySelector('.footer-text-full');
        
        if (readMoreBtn && shortText && fullText) {
            readMoreBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (fullText.classList.contains('d-none')) {
                    fullText.classList.remove('d-none');
                    shortText.classList.add('d-none');
                    readMoreBtn.textContent = 'Read Less';
                } else {
                    fullText.classList.add('d-none');
                    shortText.classList.remove('d-none');
                    readMoreBtn.textContent = 'Read More';
                }
            });
        }
		    /* ==========================
           Mobile Footer Accordion
           Only one open at a time
        ========================== */
        const mobileFooter = document.querySelector('.ttf-mobile-footer');

        if (mobileFooter) {
            mobileFooter.addEventListener('click', function (e) {
                const currentButton = e.target.closest('.aiz-accordion');

                if (!currentButton || !mobileFooter.contains(currentButton)) {
                    return;
                }

                const currentWrap = currentButton.closest('.aiz-accordion-wrap');

                setTimeout(function () {
                    mobileFooter.querySelectorAll('.aiz-accordion-wrap').forEach(function (wrap) {
                        if (wrap !== currentWrap) {
                            const btn = wrap.querySelector('.aiz-accordion');
                            const panel = wrap.querySelector('.aiz-accordion-panel');

                            if (btn) {
                                btn.classList.remove('active');
                            }

                            if (panel) {
                                panel.classList.remove('active', 'show');
                                panel.style.maxHeight = null;
                                panel.style.display = '';
                            }
                        }
                    });
                }, 50);
            });
        }
    });
</script>
