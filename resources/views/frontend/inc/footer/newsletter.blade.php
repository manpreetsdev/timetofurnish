@if ($foot_news_show == 'on')
    <section class="footer-widget iuytrey footer-newsletter-section">
        <div class="container">
            <div class="align-items-center footer-newsletter-row">
                <div class="col-lg-7 col-md-9 mx-auto text-center newsletter-column">
                    <h5 class="fs-14 fw-700 mb-3 textheading">
                        {!! str_ireplace('newsletter', '<span class="text-highlight">newsletter</span>', $foot_news_title) !!}
                    </h5>
                    <div class="mb-3">
                        <form method="POST" action="{{ route('subscribers.store') }}">
                            @csrf
                            <div class="position-relative newsletter-form-wrap">
                                <input type="email" class="form-control w-100 email_input_footer"
                                    placeholder="{{ translate('Your Email') }}" name="email" required
                                    style="padding: 12px 160px 12px 24px;">
                                <button type="submit"
                                    class="btn footer_submit_btn borderbtn position-absolute d-flex align-items-center justify-content-center"
                                    style="right: 4px; top: 4px; bottom: 4px; min-width: 130px; border:none;">
                                    <span class="d-sm-block d-lg-block">{{ $foot_news_btn }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
