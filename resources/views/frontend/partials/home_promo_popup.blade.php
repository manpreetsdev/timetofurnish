{{-- Home page promo popup – managed from Admin > Website Setup > Appearance --}}
@php
    $promoImage = get_setting('home_popup_image') ? uploaded_asset(get_setting('home_popup_image')) : null;
    $promoTitle = trim((string) get_setting('home_popup_title'));
    $promoText = trim((string) get_setting('home_popup_text'));
    $promoButtonText = trim((string) get_setting('home_popup_button_text'));
    $promoButtonLink = trim((string) get_setting('home_popup_button_link'));
    // Only allow normal web links (absolute http(s) or site-relative paths)
    if ($promoButtonLink !== '' && !preg_match('~^(https?://|/(?!/))~i', $promoButtonLink)) {
        $promoButtonLink = '';
    }
    $promoDelay = max(0, (int) (get_setting('home_popup_delay') ?? 10));
    $promoFrequency = get_setting('home_popup_frequency') ?: 'session';
    $promoHasContent = $promoTitle !== '' || $promoText !== '';
    $promoShowButton = $promoButtonText !== '' && $promoButtonLink !== '';
@endphp

@if ($promoImage || $promoHasContent)
    <div class="ttf-promo-popup" id="ttf-promo-popup" role="dialog" aria-modal="true" aria-hidden="true"
        aria-label="{{ $promoTitle ?: translate('Promotion') }}"
        data-delay="{{ $promoDelay }}" data-frequency="{{ $promoFrequency }}"
        data-version="{{ md5($promoImage . $promoTitle . $promoText . $promoButtonLink) }}">
        <div class="ttf-promo-popup__backdrop" data-ttf-promo-close></div>
        <div class="ttf-promo-popup__dialog {{ $promoHasContent ? 'has-content' : 'image-only' }}">
            <button type="button" class="ttf-promo-popup__close" data-ttf-promo-close aria-label="{{ translate('Close') }}">
                <i class="la la-close"></i>
            </button>

            @if ($promoImage)
                <div class="ttf-promo-popup__media">
                    @if ($promoButtonLink !== '')
                        <a href="{{ $promoButtonLink }}"><img src="{{ $promoImage }}" alt="{{ $promoTitle ?: translate('Promotion') }}"></a>
                    @else
                        <img src="{{ $promoImage }}" alt="{{ $promoTitle ?: translate('Promotion') }}">
                    @endif
                </div>
            @endif

            @if (!$promoHasContent && $promoShowButton)
                <div class="ttf-promo-popup__footer">
                    <a href="{{ $promoButtonLink }}" class="ttf-promo-popup__btn">{{ $promoButtonText }}</a>
                </div>
            @endif

            @if ($promoHasContent)
                <div class="ttf-promo-popup__body">
                    @if ($promoTitle !== '')
                        <h2>{{ $promoTitle }}</h2>
                    @endif
                    @if ($promoText !== '')
                        <p>{!! nl2br(e($promoText)) !!}</p>
                    @endif
                    @if ($promoShowButton)
                        <a href="{{ $promoButtonLink }}" class="ttf-promo-popup__btn">{{ $promoButtonText }}</a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <style>
        .ttf-promo-popup {
            position: fixed;
            inset: 0;
            z-index: 1060;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .ttf-promo-popup.is-open {
            display: flex;
        }

        .ttf-promo-popup__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .ttf-promo-popup__dialog {
            position: relative;
            display: flex;
            width: 100%;
            max-width: 900px;
            max-height: calc(100vh - 32px);
            background: #fff;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            opacity: 0;
            transform: translateY(16px) scale(0.98);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        @media (min-width: 768px) {
            .ttf-promo-popup__dialog.has-content {
                min-height: 440px;
            }
        }

        .ttf-promo-popup__dialog.image-only {
            flex-direction: column;
            width: auto;
            max-width: min(900px, 100%);
            max-height: min(80vh, 720px);
        }

        .image-only .ttf-promo-popup__media {
            flex: 0 1 auto;
            min-height: 0;
            display: flex;
            justify-content: center;
        }

        .image-only .ttf-promo-popup__media a {
            display: flex;
            min-height: 0;
        }

        .ttf-promo-popup__footer {
            flex: 0 0 auto;
            padding: 12px 16px;
            background: #faf8f5;
            border-top: 1px solid #e8e5e1;
            text-align: center;
        }

        .ttf-promo-popup.is-visible .ttf-promo-popup__backdrop {
            opacity: 1;
        }

        .ttf-promo-popup.is-visible .ttf-promo-popup__dialog {
            opacity: 1;
            transform: none;
        }

        .ttf-promo-popup__media {
            position: relative;
            flex: 1 1 50%;
            min-width: 0;
            background: #f3efe9;
        }

        .ttf-promo-popup__media img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-only .ttf-promo-popup__media img {
            width: auto;
            height: auto;
            max-width: 100%;
            max-height: calc(min(80vh, 720px) - 76px);
            object-fit: contain;
        }

        .ttf-promo-popup__body {
            flex: 1 1 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            text-align: center;
            overflow-y: auto;
        }

        .ttf-promo-popup__body h2 {
            margin: 0 0 14px;
            color: #111;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 34px;
            line-height: 1.15;
        }

        .ttf-promo-popup__body p {
            margin: 0 0 24px;
            color: #444;
            font-size: 15px;
            line-height: 1.6;
        }

        .ttf-promo-popup__btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 11px 26px;
            border-radius: 999px;
            background: #685b4e;
            color: #fff !important;
            font-size: 12.5px;
            font-weight: 600;
            letter-spacing: 1.2px;
            line-height: 1.2;
            text-decoration: none !important;
            text-transform: uppercase;
            box-shadow: 0 6px 16px rgba(104, 91, 78, 0.28);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .ttf-promo-popup__btn::after {
            content: "\2192";
            font-size: 15px;
            line-height: 1;
            transition: transform 0.2s ease;
        }

        .ttf-promo-popup__btn:hover {
            background: #4f453b;
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(104, 91, 78, 0.35);
        }

        .ttf-promo-popup__btn:hover::after {
            transform: translateX(4px);
        }

        .ttf-promo-popup__close {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
            width: 40px;
            height: 40px;
            border: 1px solid #e5e5e5;
            border-radius: 50%;
            background: #fff;
            color: #111;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 767.98px) {
            .ttf-promo-popup__dialog.has-content {
                flex-direction: column;
                max-width: 420px;
                overflow-y: auto;
            }

            .ttf-promo-popup__dialog.has-content .ttf-promo-popup__media img {
                max-height: 38vh;
            }

            .ttf-promo-popup__body {
                padding: 26px 20px 30px;
                overflow: visible;
            }

            .ttf-promo-popup__body h2 {
                font-size: 26px;
            }


            .ttf-promo-popup__footer {
                padding: 10px 12px;
            }

            .ttf-promo-popup__btn {
                padding: 10px 22px;
                font-size: 12px;
            }
        }
    </style>

    <script>
        (function () {
            var popup = document.getElementById('ttf-promo-popup');
            if (!popup) return;

            var KEY = 'ttf_promo_popup_seen';
            var version = popup.getAttribute('data-version');
            var frequency = popup.getAttribute('data-frequency');
            var delay = (parseInt(popup.getAttribute('data-delay'), 10) || 0) * 1000;

            function storage() {
                if (frequency === 'always') return null;
                try {
                    return frequency === 'once' ? window.localStorage : window.sessionStorage;
                } catch (e) {
                    return null;
                }
            }

            var store = storage();
            if (store && store.getItem(KEY) === version) return;

            function close() {
                popup.classList.remove('is-visible');
                popup.setAttribute('aria-hidden', 'true');
                setTimeout(function () { popup.classList.remove('is-open'); }, 300);
                document.removeEventListener('keydown', onKey);
                if (store) {
                    try { store.setItem(KEY, version); } catch (e) {}
                }
            }

            function onKey(event) {
                if (event.key === 'Escape') close();
            }

            function open() {
                popup.classList.add('is-open');
                popup.setAttribute('aria-hidden', 'false');
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () { popup.classList.add('is-visible'); });
                });
                document.addEventListener('keydown', onKey);
            }

            popup.querySelectorAll('[data-ttf-promo-close]').forEach(function (el) {
                el.addEventListener('click', close);
            });
            // Remember the popup as seen when the visitor follows its link
            popup.querySelectorAll('a').forEach(function (el) {
                el.addEventListener('click', function () {
                    if (store) {
                        try { store.setItem(KEY, version); } catch (e) {}
                    }
                });
            });

            setTimeout(open, delay);
        })();
    </script>
@endif
