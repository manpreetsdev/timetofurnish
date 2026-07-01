@once
    <style>
        .home-section-loader {
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(249, 246, 240, 0.95) 0%, rgba(255, 255, 255, 0.98) 100%);
            border: 1px solid rgba(223, 211, 191, 0.65);
            box-shadow: 0 18px 40px -28px rgba(72, 58, 38, 0.22);
        }

        .home-section-loader::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.58) 50%,
                rgba(255, 255, 255, 0) 100%);
            transform: translateX(-100%);
            animation: home-section-shimmer 1.35s infinite;
            pointer-events: none;
        }

        @keyframes home-section-shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .home-section-loader-bar,
        .home-section-loader-pill,
        .home-section-loader-card,
        .home-section-loader-thumb {
            background: linear-gradient(90deg, #eadfce 25%, #f7f2ea 37%, #eadfce 63%);
            background-size: 400% 100%;
            border-radius: 999px;
            min-height: 12px;
        }

        .home-section-loader-card {
            border-radius: 18px;
            min-height: 170px;
        }

        .home-section-loader-thumb {
            border-radius: 16px;
            min-height: 120px;
        }

        .home-section-loader-product {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 12px;
            border: 1px solid rgba(223, 211, 191, 0.65);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.85);
            height: 100%;
        }

        .home-section-loader-product .home-section-loader-thumb {
            min-height: 110px;
        }

        .home-section-loader-product .home-section-loader-bar:nth-child(2) {
            width: 92%;
        }

        .home-section-loader-product .home-section-loader-bar:nth-child(3) {
            width: 72%;
        }

        .home-section-loader-product .home-section-loader-bar:nth-child(4) {
            width: 44%;
        }

        .home-section-loader-deal {
            min-height: 270px;
            padding: 18px;
        }

        .home-section-loader-deal .home-section-loader-banner {
            min-height: 160px;
            border-radius: 18px;
            background: linear-gradient(90deg, #eadfce 25%, #f7f2ea 37%, #eadfce 63%);
        }

        .home-section-loader-deal .home-section-loader-strip {
            min-height: 54px;
            border-radius: 14px;
            background: linear-gradient(90deg, #eadfce 25%, #f7f2ea 37%, #eadfce 63%);
        }

        .home-section-loader-grid {
            display: grid;
            gap: 14px;
        }

        .home-section-loader-grid--products {
            grid-template-columns: repeat(5, minmax(0, 1fr));
        }

        .home-section-loader-grid--cards {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .home-section-loader-grid--categories {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        @media (max-width: 1199.98px) {
            .home-section-loader-grid--products {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.98px) {
            .home-section-loader-grid--products,
            .home-section-loader-grid--cards {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .home-section-loader-grid--categories {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .home-section-loader-grid--products,
            .home-section-loader-grid--cards {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .home-section-loader-deal {
                min-height: 220px;
                padding: 14px;
            }
        }

        @media (max-width: 575.98px) {
            .home-section-loader-grid--products,
            .home-section-loader-grid--cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endonce

@php
    $variant = $variant ?? 'products';
    $cardCount = $cardCount ?? 5;
@endphp

@if ($variant === 'deal')
    <div class="home-section-loader home-section-loader-deal">
        <div class="home-section-loader-grid home-section-loader-grid--cards">
            @for ($i = 0; $i < 4; $i++)
                <div class="home-section-loader-product">
                    <div class="home-section-loader-thumb"></div>
                    <div class="home-section-loader-bar"></div>
                    <div class="home-section-loader-bar"></div>
                </div>
            @endfor
        </div>
    </div>
@elseif ($variant === 'categories')
    <div class="home-section-loader p-3 p-md-4">
        <div class="home-section-loader-bar mb-3" style="width: 240px;"></div>
        <div class="home-section-loader-grid home-section-loader-grid--categories">
            @for ($i = 0; $i < 4; $i++)
                <div class="home-section-loader-product">
                    <div class="home-section-loader-thumb" style="min-height: 180px;"></div>
                    <div class="home-section-loader-bar" style="width: 78%;"></div>
                    <div class="home-section-loader-bar" style="width: 56%;"></div>
                </div>
            @endfor
        </div>
    </div>
@else
    <div class="home-section-loader p-3 p-md-4">
        <div class="home-section-loader-bar mb-3" style="width: 220px;"></div>
        <div class="home-section-loader-bar mb-4" style="width: 150px; opacity: 0.75;"></div>
        <div class="home-section-loader-grid home-section-loader-grid--products">
            @for ($i = 0; $i < $cardCount; $i++)
                <div class="home-section-loader-product">
                    <div class="home-section-loader-thumb"></div>
                    <div class="home-section-loader-bar"></div>
                    <div class="home-section-loader-bar"></div>
                    <div class="home-section-loader-bar"></div>
                </div>
            @endfor
        </div>
    </div>
@endif
