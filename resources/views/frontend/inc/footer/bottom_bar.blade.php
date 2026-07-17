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
                <div class="text-right fs-14" current-verison="{{ get_setting('current_version') }}">
                    <p class="footer-content" style="letter-spacing:0.5px; line-height: 1.6;">
                        @if($footer_disclaimer_needs_toggle)
                          @php
                              $truncated = \Illuminate\Support\Str::limit($footer_disclaimer_text, 80, '');
                              $needsToggle = $truncated !== $footer_disclaimer_text;
                          @endphp
                          <span class="footer-text-short" @if(!$needsToggle)style="display:none;"@endif>{!! $truncated !!}</span>
                          <span class="footer-text-full d-none">{!! $footer_disclaimer_text !!}</span>
                          @if($needsToggle)
                              <a href="#" class="footer-read-more-btn ml-1" aria-expanded="false">Read More</a>
                          @endif
                          <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                          <script>
                              $(function() {
                                  $('.footer-read-more-btn').on('click', function(e) {
                                      e.preventDefault();
                                      var $container = $(this).closest('.footer-content');
                                      var $short = $container.find('.footer-text-short');
                                      var $full = $container.find('.footer-text-full');
                                      var isExpanded = $(this).attr('aria-expanded') === 'true';
                                      if (isExpanded) {
                                          $short.removeClass('d-none');
                                          $full.addClass('d-none');
                                          $(this).text('Read More').attr('aria-expanded', 'false');
                                      } else {
                                          $short.addClass('d-none');
                                          $full.removeClass('d-none');
                                          $(this).text('Read Less').attr('aria-expanded', 'true');
                                      }
                                  });
                              });
                          </script>
                                  @else
                                      <span class="footer-text-full">{!! $footer_disclaimer_text !!}</span>
                                  @endif
                        
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
