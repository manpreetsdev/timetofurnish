<div class="ttf-footer-bottom-bar">
    <div class="container px-xs-0">
        <div class="row align-items-center">
            <div class="col-lg-6 order-1 order-lg-0 mt-2 mb-sm-50">
                <div class="text-justify fs-14" current-verison="{{ get_setting('current_version') }}">
                    <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                        {!! $frontend_copyright_text !!}
                    </p>
                </div>
            </div>

            <div class="col-lg-6 order-1 order-lg-0 mt-2 mb-sm-50">
                <div class="text-right fs-14 " current-verison="{{ get_setting('current_version') }}">
                    <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                        @if($footer_disclaimer_needs_toggle)
                            <span class="footer-text-short">We operate as an independent third-party marketplace.</span>
                            <span class="footer-text-full d-none">{!! $footer_disclaimer_text !!}</span>
                            <a href="javascript:void(0);" class="footer-read-more-btn ml-1">Read More</a>
                        @else
                            <span class="footer-text-full">{!! $footer_disclaimer_text !!}</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
