<!-- Mobile bottom nav -->
<div class="aiz-mobile-bottom-nav d-xl-none fixed-bottom border-top border-sm-bottom border-sm-left border-sm-right mx-auto mb-sm-2"
    style="background-color: #fff !important;">
    <div class="row align-items-center gutters-5 h-100">
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
                            transform="translate(-3499.144 602)" fill="#91919b" />
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

<script>
    function toggleFlags(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('flagDropdown');
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Outside click close
    document.addEventListener('click', function() {
        const dropdown = document.getElementById('flagDropdown');
        if (dropdown) dropdown.style.display = 'none';
    });

    // Footer read more/less toggle
    document.addEventListener('DOMContentLoaded', function() {
        const readMoreBtn = document.querySelector('.footer-read-more-btn');
        const shortText = document.querySelector('.footer-text-short');
        const fullText = document.querySelector('.footer-text-full');

        if (readMoreBtn && shortText && fullText) {
            const normalizedShort = shortText.textContent.replace(/\.\.\.$/, '').replace(/\s+/g, ' ').trim();
            const normalizedFull = fullText.textContent.replace(/\s+/g, ' ').trim();

            if (!normalizedFull || normalizedShort === normalizedFull || normalizedFull.length <= 140) {
                readMoreBtn.remove();
                shortText.classList.add('d-none');
                fullText.classList.remove('d-none');
                return;
            }

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

                            const heading = wrap.querySelector('.aiz-accordion-heading');
                            if (heading) {
                                heading.classList.remove('active');
                            }
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
