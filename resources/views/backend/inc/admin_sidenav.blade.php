<div class="aiz-sidebar-wrap adminsidebar">
    <style>
        /* Time To Furnish Admin Sidebar - Theme Color #685b4e & White */
        .adminsidebar,
        .adminsidebar .aiz-sidebar,
        .adminsidebar .aiz-side-nav-wrap,
        .aiz-side-nav-logo-wrap,
        .sidebar-logo {
            background: #ffffff !important;
        }

        .adminsidebar {
            position: relative;
            z-index: 1040;
        }

        .adminsidebar .aiz-sidebar {
            background: #ffffff !important;
            border-right: 1px solid #e8e5e1 !important;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.03) !important;
            width: 270px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #d9d4cc transparent;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .adminsidebar .aiz-sidebar::-webkit-scrollbar {
            width: 5px;
        }
        .adminsidebar .aiz-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .adminsidebar .aiz-sidebar::-webkit-scrollbar-thumb {
            background: #d9d4cc;
            border-radius: 4px;
        }
        .adminsidebar .aiz-sidebar::-webkit-scrollbar-thumb:hover {
            background: #685b4e;
        }

        /* Logo Area - Clean White Header */
        .adminsidebar .sidebar-logo,
        .aiz-side-nav-logo-wrap {
            padding: 16px 20px !important;
            background: #ffffff !important;
            border-bottom: 1px solid #e8e5e1 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            position: sticky !important;
            top: 0 !important;
            z-index: 20 !important;
        }
        .adminsidebar .sidebar-logo img,
        .aiz-side-nav-logo-wrap img {
            max-width: 160px !important;
            max-height: 44px !important;
            width: auto !important;
            height: auto !important;
            object-fit: contain !important;
            transition: transform 0.2s ease;
        }
        .adminsidebar .sidebar-logo a:hover img {
            transform: scale(1.02);
        }

        /* Search Input Styling */
        .adminsidebar .aiz-side-nav-wrap {
            padding: 14px 0 40px !important;
        }

        .adminsidebar .sidebar-search-wrap {
            position: relative;
            margin-bottom: 14px;
            padding: 0 12px;
        }

        .adminsidebar #menu-search {
            border: 1px solid #d9d4cc !important;
            background: #faf8f5 !important;
            color: #39322a !important;
            height: 38px !important;
            border-radius: 10px !important;
            padding-left: 36px !important;
            padding-right: 12px !important;
            font-size: 13px !important;
            transition: all 0.2s ease !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.02) !important;
            width: 100% !important;
        }

        .adminsidebar #menu-search:focus {
            border-color: #685b4e !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(104, 91, 78, 0.15) !important;
        }

        .adminsidebar #menu-search::placeholder {
            color: #8c7e70 !important;
            font-size: 12.5px !important;
        }

        .adminsidebar .sidebar-search-icon {
            position: absolute;
            left: 24px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #8c7e70;
            display: flex;
            align-items: center;
        }

        /* Keep a gutter on both sides of every menu row */
        .adminsidebar #main-menu,
        .adminsidebar #search-menu {
            padding: 0 12px !important;
            margin: 0 !important;
            box-sizing: border-box !important;
        }

        .adminsidebar .aiz-side-nav-list.level-2,
        .adminsidebar .aiz-side-nav-list.level-3 {
            margin-right: 0 !important;
            width: auto !important;
            box-sizing: border-box !important;
        }

        /* Section Header Titles */
        .adminsidebar .sidebar-category-header {
            font-size: 10.5px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.8px !important;
            color: #685b4e !important;
            padding: 14px 12px 6px !important;
            margin-top: 6px !important;
            user-select: none;
        }

        /* Level-1 Items & Links */
        .adminsidebar .aiz-side-nav-item {
            margin-bottom: 3px !important;
            list-style: none !important;
            position: relative !important;
            width: 100% !important;
        }

        .adminsidebar .aiz-side-nav-link {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            min-height: 40px !important;
            padding: 9px 12px !important;
            border-radius: 10px !important;
            color: #39322a !important;
            font-size: 13px !important;
            font-weight: 500 !important;
            text-decoration: none !important;
            border: 1px solid transparent !important;
            background: transparent !important;
            transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease !important;
            width: 100% !important;
            box-sizing: border-box !important;
            transform: none !important;
        }

        .adminsidebar .aiz-side-nav-link:hover {
            background: #f8f6f3 !important;
            border-color: #e8e3dd !important;
            color: #685b4e !important;
            transform: none !important;
        }

        .adminsidebar .aiz-side-nav-link.active,
        .adminsidebar .aiz-side-nav-item.mm-active > .aiz-side-nav-link {
            background: #f3efe9 !important;
            border-color: #dcd4c9 !important;
            color: #685b4e !important;
            font-weight: 600 !important;
            box-shadow: 0 2px 8px rgba(104, 91, 78, 0.08) !important;
            margin: 0 !important;
        }

        /* Icons Container & SVG Coloring Fix */
        .adminsidebar .aiz-side-nav-icon {
            width: 22px !important;
            height: 22px !important;
            min-width: 22px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-right: 10px !important;
            color: #7d7266 !important;
            flex-shrink: 0 !important;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            opacity: 0.88;
            transition: opacity 0.2s ease;
        }

        .adminsidebar .aiz-side-nav-icon svg {
            width: 16px !important;
            height: 16px !important;
            stroke: #7d7266 !important;
            fill: none !important;
            background: transparent !important;
            transition: stroke 0.2s ease !important;
        }

        .adminsidebar .aiz-side-nav-icon svg * {
            stroke: inherit !important;
        }

        /* Support legacy filled SVGs if any */
        .adminsidebar .aiz-side-nav-icon svg path[fill] {
            fill: #7d7266;
        }

        /* Prevent canvas background rects from becoming solid dark boxes */
        .adminsidebar .aiz-side-nav-icon svg rect[width="16"][height="16"],
        .adminsidebar .aiz-side-nav-icon svg rect[width="100%"] {
            fill: transparent !important;
            stroke: none !important;
        }

        .adminsidebar .aiz-side-nav-link:hover .aiz-side-nav-icon,
        .adminsidebar .aiz-side-nav-link.active .aiz-side-nav-icon,
        .adminsidebar .aiz-side-nav-item.mm-active > .aiz-side-nav-link .aiz-side-nav-icon {
            opacity: 1;
        }

        .adminsidebar .aiz-side-nav-link:hover .aiz-side-nav-icon svg,
        .adminsidebar .aiz-side-nav-link.active .aiz-side-nav-icon svg,
        .adminsidebar .aiz-side-nav-item.mm-active > .aiz-side-nav-link .aiz-side-nav-icon svg {
            stroke: #685b4e !important;
        }

        .adminsidebar .aiz-side-nav-link:hover .aiz-side-nav-icon svg path[fill],
        .adminsidebar .aiz-side-nav-link.active .aiz-side-nav-icon svg path[fill],
        .adminsidebar .aiz-side-nav-item.mm-active > .aiz-side-nav-link .aiz-side-nav-icon svg path[fill] {
            fill: #685b4e !important;
        }

        /* Line (stroke-only) icons must never be filled, even on hover/active */
        .adminsidebar .aiz-side-nav-wrap .aiz-side-nav-item .aiz-side-nav-link .aiz-side-nav-icon svg.ttf-line-icon,
        .adminsidebar .aiz-side-nav-wrap .aiz-side-nav-item .aiz-side-nav-link .aiz-side-nav-icon svg.ttf-line-icon path {
            fill: none !important;
        }

        /* Text Element */
        .adminsidebar .aiz-side-nav-text {
            flex-grow: 1 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            letter-spacing: 0.1px !important;
        }

        /* Chevron Arrows */
        .adminsidebar .aiz-side-nav-arrow {
            width: 18px !important;
            height: 18px !important;
            min-width: 18px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-left: auto !important;
            flex-shrink: 0 !important;
            position: relative !important;
            transition: transform 0.25s ease !important;
        }

        .adminsidebar .aiz-side-nav-arrow::after {
            content: "\f105" !important;
            font-family: 'Line Awesome Free' !important;
            font-weight: 900 !important;
            font-size: 11px !important;
            color: #8c7e70 !important;
            position: static !important;
            transform: none !important;
            border: none !important;
            background: none !important;
            width: auto !important;
            height: auto !important;
        }

        .adminsidebar .aiz-side-nav-item.mm-active > .aiz-side-nav-link .aiz-side-nav-arrow,
        .adminsidebar .aiz-side-nav-link[aria-expanded="true"] .aiz-side-nav-arrow {
            transform: rotate(90deg) !important;
        }

        .adminsidebar .aiz-side-nav-item.mm-active > .aiz-side-nav-link .aiz-side-nav-arrow::after,
        .adminsidebar .aiz-side-nav-link[aria-expanded="true"] .aiz-side-nav-arrow::after {
            color: #685b4e !important;
        }

        /* Level 2 Submenus */
        .adminsidebar .aiz-side-nav-list.level-2 {
            margin-top: 3px !important;
            margin-bottom: 5px !important;
            margin-left: 18px !important;
            padding-left: 12px !important;
            border-left: 1px dashed #d9d4cc !important;
            list-style: none !important;
        }

        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link {
            min-height: 34px !important;
            padding: 6px 10px 6px 16px !important;
            border-radius: 8px !important;
            font-size: 12.5px !important;
            border: none !important;
            color: #4a4239 !important;
            position: relative !important;
            background: transparent !important;
        }

        /* Clean round bullet dot for sub-items */
        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link::before {
            content: "" !important;
            position: absolute !important;
            left: -15px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 6px !important;
            height: 6px !important;
            border-radius: 50% !important;
            background-color: #baa898 !important;
            transition: background-color 0.2s ease, transform 0.2s ease !important;
        }

        /* Override old aiz-core :after dots */
        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link::after,
        .adminsidebar .aiz-side-nav-list.level-3 .aiz-side-nav-link::after {
            display: none !important;
            content: none !important;
        }

        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link:hover {
            color: #685b4e !important;
            background: #f8f6f3 !important;
        }

        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link:hover::before,
        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link.active::before {
            background-color: #685b4e !important;
            transform: translateY(-50%) scale(1.3) !important;
        }

        .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link.active {
            color: #685b4e !important;
            font-weight: 600 !important;
            background: #f3efe9 !important;
            box-shadow: 0 2px 6px rgba(104, 91, 78, 0.06) !important;
        }

        /* Level 3 Submenus */
        .adminsidebar .aiz-side-nav-list.level-3 {
            margin-top: 2px !important;
            margin-left: 12px !important;
            padding-left: 10px !important;
            border-left: 1px dotted #d9d4cc !important;
            list-style: none !important;
        }

        .adminsidebar .aiz-side-nav-list.level-3 .aiz-side-nav-link {
            min-height: 30px !important;
            font-size: 12px !important;
            padding: 4px 8px !important;
        }

        /* Badges */
        .adminsidebar .badge {
            font-size: 10px !important;
            font-weight: 600 !important;
            padding: 2px 7px !important;
            border-radius: 10px !important;
            margin-left: 6px !important;
        }

        /* Mobile & Tablet Responsiveness Overrides */
        @media (max-width: 1199.98px) {
            .adminsidebar .aiz-sidebar {
                left: -280px !important;
                z-index: 1045 !important;
                width: 280px !important;
                box-shadow: 0 0 30px rgba(0, 0, 0, 0.15) !important;
            }
            body.side-menu-open .adminsidebar .aiz-sidebar {
                left: 0 !important;
            }
            .adminsidebar .aiz-sidebar-overlay {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                background: rgba(0, 0, 0, 0.3) !important;
                backdrop-filter: blur(2px) !important;
                z-index: 1040 !important;
                visibility: hidden !important;
                opacity: 0 !important;
                transition: opacity 0.3s ease, visibility 0.3s ease !important;
            }
            body.side-menu-open .adminsidebar .aiz-sidebar-overlay {
                visibility: visible !important;
                opacity: 1 !important;
            }
        }

        /* RTL Layout Support */
        [dir="rtl"] .adminsidebar .aiz-sidebar {
            left: auto !important;
            right: 0 !important;
            border-right: none !important;
            border-left: 1px solid #e8e5e1 !important;
        }
        [dir="rtl"] .adminsidebar .aiz-side-nav-icon {
            margin-right: 0 !important;
            margin-left: 10px !important;
        }
        [dir="rtl"] .adminsidebar .aiz-side-nav-arrow {
            margin-left: 0 !important;
            margin-right: auto !important;
        }
        [dir="rtl"] .adminsidebar .aiz-side-nav-list.level-2 {
            margin-left: 0 !important;
            margin-right: 18px !important;
            padding-left: 0 !important;
            padding-right: 12px !important;
            border-left: none !important;
            border-right: 1px dashed #d9d4cc !important;
        }
        [dir="rtl"] .adminsidebar .aiz-side-nav-list.level-2 .aiz-side-nav-link::before {
            left: auto !important;
            right: -15px !important;
        }
        @media (max-width: 1199.98px) {
            [dir="rtl"] .adminsidebar .aiz-sidebar {
                right: -280px !important;
                left: auto !important;
            }
            [dir="rtl"] body.side-menu-open .adminsidebar .aiz-sidebar {
                right: 0 !important;
            }
        }
    </style>

    <div class="aiz-sidebar left c-scrollbar">
        {{-- Logo Header --}}
        <div class="sidebar-logo text-center py-3">
            <a href="{{ route('admin.dashboard') }}">
                <img src="https://timetofurnish.com/public/assets/img/logoT.png" alt="Time To Furnish Logo">
            </a>
        </div>

        <div class="aiz-side-nav-wrap">
            {{-- Menu Search Box --}}
            <div class="sidebar-search-wrap">
                <span class="sidebar-search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 16 16">
                        <path id="search_FILL" d="M176.921-769.231l6.255-6.255a5.99,5.99,0,0,0,1.733.949,5.687,5.687,0,0,0,1.885.329,5.317,5.317,0,0,0,3.9-1.608,5.31,5.31,0,0,0,1.609-3.9,5.322,5.322,0,0,0-1.608-3.9,5.306,5.306,0,0,0-3.9-1.611,5.321,5.321,0,0,0-3.9,1.609,5.312,5.312,0,0,0-1.611,3.9,5.554,5.554,0,0,0,.35,1.946,6.043,6.043,0,0,0,.929,1.672l-6.255,6.255Zm9.874-5.82a4.51,4.51,0,0,1-3.317-1.352,4.51,4.51,0,0,1-1.352-3.317,4.51,4.51,0,0,1,1.352-3.317,4.51,4.51,0,0,1,3.317-1.352,4.51,4.51,0,0,1,3.317,1.352,4.51,4.51,0,0,1,1.352,3.317,4.51,4.51,0,0,1-1.352,3.317A4.51,4.51,0,0,1,186.8-775.051Z" transform="translate(-176.307 785.231)" fill="#a17d5c"/>
                    </svg>
                </span>
                <input class="form-control" type="text" placeholder="{{ translate('Search in menu') }}" id="menu-search" onkeyup="menuSearch()">
            </div>

            <ul class="aiz-side-nav-list" id="search-menu"></ul>

            <ul class="aiz-side-nav-list" id="main-menu" data-toggle="aiz-side-menu">

                {{-- SECTION 1: MAIN --}}
                <li class="sidebar-category-header">{{ translate('Main Navigation') }}</li>

                {{-- Dashboard --}}
                @can('admin_dashboard')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="aiz-side-nav-link {{ areActiveRoutes(['admin.dashboard']) }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M18,12.286a1.715,1.715,0,0,0-1.714-1.714h-4a1.715,1.715,0,0,0-1.714,1.714v4A1.715,1.715,0,0,0,12.286,18h4A1.715,1.715,0,0,0,18,16.286Zm-8.571,0a1.715,1.715,0,0,0-1.714-1.714h-4A1.715,1.715,0,0,0,2,12.286v4A1.715,1.715,0,0,0,3.714,18h4a1.715,1.715,0,0,0,1.714-1.714Zm7.429,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm-8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571ZM9.429,3.714A1.715,1.715,0,0,0,7.714,2h-4A1.715,1.715,0,0,0,2,3.714v4A1.715,1.715,0,0,0,3.714,9.429h4A1.715,1.715,0,0,0,9.429,7.714Zm8.571,0A1.715,1.715,0,0,0,16.286,2h-4a1.715,1.715,0,0,0-1.714,1.714v4a1.715,1.715,0,0,0,1.714,1.714h4A1.715,1.715,0,0,0,18,7.714Zm-9.714,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Zm8.571,0v4a.57.57,0,0,1-.571.571h-4a.57.57,0,0,1-.571-.571v-4a.57.57,0,0,1,.571-.571h4a.57.57,0,0,1,.571.571Z" transform="translate(-2 -2)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Dashboard') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- SECTION 2: E-COMMERCE CATALOG & SALES --}}
                <li class="sidebar-category-header">{{ translate('E-Commerce & Sales') }}</li>

                {{-- POS Addon --}}
                @if (addon_is_activated('pos_system') && (auth()->user()->can('pos_manager') || auth()->user()->can('pos_configuration')))
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="16" viewBox="0 0 13.79 16">
                                    <path d="M10.69,7H3.26a1.025,1.025,0,0,0-1,1V18.45a1.03,1.03,0,0,0,1,1.05h7.43a1.03,1.03,0,0,0,1.03-1.03V8A1.025,1.025,0,0,0,10.69,7ZM4.94,17.86H3.995v-.95H4.94Zm0-2.355H3.995v-.95H4.94Zm0-2.355H3.995V12.2H4.94Zm2.5,4.71H6.5v-.95h.955Zm0-2.355H6.5v-.95h.955Zm0-2.355H6.5V12.2h.955Zm2.5,4.71H8.99v-.95h.95Zm0-2.355H8.99v-.95h.95Zm0-2.355H8.99V12.2h.95Zm.325-3a.17.17,0,0,1-.165.17H3.835a.17.17,0,0,1-.165-.17V8.795a.165.165,0,0,1,.165-.165H10.13a.165.165,0,0,1,.165.165Zm5.09-1.45H15.13v9.09h.25a.67.67,0,0,0,.67-.67V9.375a.67.67,0,0,0-.695-.675Z" transform="translate(-2.26 -3.5)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('POS System') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('pos_manager')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('poin-of-sales.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.index', 'poin-of-sales.create']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('POS Manager') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('pos_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('poin-of-sales.activation') }}" class="aiz-side-nav-link {{ areActiveRoutes(['poin-of-sales.activation']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('POS Configuration') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                {{-- Products --}}
                @canany(['add_new_product', 'show_all_products','show_in_house_products','show_seller_products','show_digital_products','product_bulk_import','product_bulk_export','view_product_categories', 'view_all_brands','view_product_attributes','view_colors','view_product_reviews'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 16 13.714">
                                    <path d="M17.429,4H2.571A.571.571,0,0,0,2,4.571V8a.571.571,0,0,0,.571.571h.571v8.571a.571.571,0,0,0,.571.571H16.286a.571.571,0,0,0,.571-.571V8.571h.571A.571.571,0,0,0,18,8V4.571A.571.571,0,0,0,17.429,4ZM15.714,16.571H4.286v-8H15.714Zm1.143-9.143H3.143V5.143H16.857Z" transform="translate(-2 -4)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Products') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('show_all_products')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('products.all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.all']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('All Products') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('add_new_product')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('products.create') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.create']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Add New Product') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('show_in_house_products')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('products.admin') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.admin', 'products.admin.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('In House Products') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if(get_setting('vendor_system_activation') == 1)
                                @can('show_seller_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('products.seller', 'physical') }}" class="aiz-side-nav-link {{ areActiveRoutes(['products.seller', 'products.seller.edit']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Seller Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @can('show_digital_products')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('digitalproducts.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['digitalproducts.index', 'digitalproducts.create', 'digitalproducts.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Digital Products') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_product_categories')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('categories.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['categories.index', 'categories.create', 'categories.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_brands')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('brands.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['brands.index', 'brands.create', 'brands.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Brands') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_product_attributes')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('attributes.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['attributes.index','attributes.create','attributes.edit','attributes.show','edit-attribute-value']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Attributes') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('global.addons.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['global.addons.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Global Addons') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('services.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['services.index', 'services.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Services') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('shipping-charges.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping-charges.index', 'shipping-charges.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Shipping Charges') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_colors')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('colors') }}" class="aiz-side-nav-link {{ areActiveRoutes(['colors','colors.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Colors') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_product_reviews')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('reviews.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['reviews.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Reviews') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('product_bulk_import')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('product_bulk_upload.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['product_bulk_upload.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Bulk Import') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('product_bulk_export')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('product_bulk_export.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['product_bulk_export.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Bulk Export') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Auction Product Addon --}}
                @if(addon_is_activated('auction'))
                    @canany(['add_auction_product','view_all_auction_products','view_inhouse_auction_products','view_seller_auction_products','view_auction_product_orders'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 15.964 16">
                                        <path d="M4.993,20.456a.456.456,0,0,0,.456.456h8.389a.456.456,0,0,0,.456-.456V19.009a1.256,1.256,0,0,0-1.254-1.254h-.2V16.32a.456.456,0,0,0-.456-.456H6.9a.456.456,0,0,0-.456.456v1.435h-.2a1.255,1.255,0,0,0-1.254,1.254Zm2.363-3.68H11.93v.979H7.356ZM5.905,19.009a.342.342,0,0,1,.342-.342H13.04a.342.342,0,0,1,.342.342V20H5.905Zm13.717-1.79a1.405,1.405,0,0,0,1.334-1.4,1.411,1.411,0,0,0-.461-1.042l-4.466-4.009L17.6,9.031a.831.831,0,0,0-.06-1.172L14.513,5.127a.816.816,0,0,0-.6-.213.824.824,0,0,0-.574.272L8.27,10.8a.83.83,0,0,0,.059,1.173L11.354,14.7a.83.83,0,0,0,1.172-.06l1.622-1.795,4.464,4.008a1.392,1.392,0,0,0,1.011.361ZM13.779,11.9l0,0,0,0L11.9,13.972,9,11.35l4.961-5.492,2.9,2.622L13.779,11.9Zm.981.275.658-.728,4.466,4.008a.492.492,0,1,1-.661.728Z" transform="translate(-4.993 -4.912)" fill="#575b6a"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Auction Products') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('add_auction_product')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('auction_product_create.admin') }}" class="aiz-side-nav-link {{ areActiveRoutes(['auction_product_create.admin']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Add Auction Product') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_auction_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('auction.all_products') }}" class="aiz-side-nav-link {{ areActiveRoutes(['auction.all_products', 'auction_product_edit.admin', 'product_bids.admin']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('All Auction Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_inhouse_auction_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('auction.inhouse_products') }}" class="aiz-side-nav-link {{ areActiveRoutes(['auction.inhouse_products']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Inhouse Auction Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @if (get_setting('vendor_system_activation') == 1)
                                    @can('view_seller_auction_products')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('auction.seller_products') }}" class="aiz-side-nav-link {{ areActiveRoutes(['auction.seller_products']) }}">
                                                <span class="aiz-side-nav-text">{{ translate('Seller Auction Products') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                                @can('view_auction_product_orders')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('auction_products_orders') }}" class="aiz-side-nav-link {{ areActiveRoutes(['auction_products_orders']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Auction Orders') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- Wholesale Product Addon --}}
                @if(addon_is_activated('wholesale'))
                    @canany(['add_wholesale_product','view_all_wholesale_products','view_inhouse_wholesale_products','view_sellers_wholesale_products'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M1.2,14.236a1.762,1.762,0,0,1,1.2-1.657V2c0-.325-.268-.823-.6-.823H.6C.268,1.176,0,1.031,0,.7V.647A.645.645,0,0,1,.6,0H2.4A1.407,1.407,0,0,1,3.6,1.41v9.65h10a1.4,1.4,0,0,1,1.2,1.518,1.757,1.757,0,0,1,1.165,2.01,1.8,1.8,0,0,1-3.566-.353,1.761,1.761,0,0,1,1.2-1.656v-.342H3.6v.342a1.754,1.754,0,0,1,1.165,2.01A1.784,1.784,0,0,1,3.338,15.97,1.927,1.927,0,0,1,3,16,1.782,1.782,0,0,1,1.2,14.236Zm12.4,0a.594.594,0,0,0,.6.588h0a.6.6,0,0,0,.6-.589c0-.389-.272-.5-.6-.617C13.872,13.732,13.6,13.846,13.6,14.235Zm-11.2,0a.6.6,0,0,0,.6.588H3a.6.6,0,0,0,.6-.589c0-.389-.272-.5-.6-.617C2.671,13.732,2.4,13.846,2.4,14.235Zm4.216-4.158A1.615,1.615,0,0,1,5,8.462V6.692A1.615,1.615,0,0,1,6.615,5.077h5.77A1.616,1.616,0,0,1,14,6.692V8.462a1.616,1.616,0,0,1-1.616,1.615ZM6.234,6.311a.542.542,0,0,0-.157.382V8.462A.538.538,0,0,0,6.615,9h5.77a.538.538,0,0,0,.538-.538V6.692a.536.536,0,0,0-.538-.538H6.612A.535.535,0,0,0,6.234,6.311ZM5.473,3.527A1.617,1.617,0,0,1,5,2.385V1.616A1.615,1.615,0,0,1,6.615,0H9.384A1.616,1.616,0,0,1,11,1.616v.769A1.615,1.615,0,0,1,9.384,4H6.612A1.614,1.614,0,0,1,5.473,3.527Zm.761-2.293a.542.542,0,0,0-.157.382v.769a.538.538,0,0,0,.538.538H9.384a.538.538,0,0,0,.539-.538V1.616a.542.542,0,0,0-.157-.382.536.536,0,0,0-.382-.157H6.612A.535.535,0,0,0,6.234,1.234Z" fill="#575b6a"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Wholesale Products') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('add_wholesale_product')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('wholesale_product_create.admin') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_product_create.admin']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Add Wholesale Product') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_wholesale_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('wholesale_products.all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_products.all', 'wholesale_product_edit.admin']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('All Wholesale Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_inhouse_wholesale_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('wholesale_products.in_house') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_products.in_house']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('In House Wholesale') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @if (get_setting('vendor_system_activation') == 1)
                                    @can('view_sellers_wholesale_products')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('wholesale_products.seller') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wholesale_products.seller']) }}">
                                                <span class="aiz-side-nav-text">{{ translate('Seller Wholesale') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- Sales & Orders --}}
                @canany(['view_all_orders', 'view_inhouse_orders','view_seller_orders','view_pickup_point_orders'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg class="ttf-line-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/>
                                    <path d="M3 6h18"/>
                                    <path d="M16 10a4 4 0 0 1-8 0"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Sales') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('all_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['all_orders.index', 'all_orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('All Orders') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_inhouse_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('inhouse_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['inhouse_orders.index', 'inhouse_orders.show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Inhouse Orders') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (get_setting('vendor_system_activation') == 1)
                                @can('view_seller_orders')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller_orders.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_orders.index', 'seller_orders.show']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Seller Orders') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                            @can('view_pickup_point_orders')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('pick_up_point.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_point.index','pick_up_point.order_show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Pick-up Point Orders') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Delivery Boy Addon --}}
                @if (addon_is_activated('delivery_boy'))
                    @canany(['view_all_delivery_boy','add_delivery_boy','delivery_boy_payment_history','collected_histories_from_delivery_boy','order_cancle_request_by_delivery_boy','delivery_boy_configuration'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M12.406,9.375h-.625v-.84a3.28,3.28,0,0,0,1.406-2.691V4.375h2.344a.469.469,0,0,0,0-.937H13.5a3.594,3.594,0,0,0-7.184.156v.313a.469.469,0,0,0,.313.442v1.5A3.28,3.28,0,0,0,8.031,8.535v.84H7.406a3.605,3.605,0,0,0-2.113.688H1.406a.469.469,0,0,0-.419.259L.049,12.2h0a.466.466,0,0,0-.05.209v3.125A.469.469,0,0,0,.469,16H15.531A.469.469,0,0,0,16,15.531V12.969A3.6,3.6,0,0,0,12.406,9.375ZM9.906.938a2.66,2.66,0,0,1,2.652,2.5h-5.3A2.66,2.66,0,0,1,9.906.938ZM7.562,5.844V4.375H12.25V5.844a2.344,2.344,0,0,1-4.688,0ZM9.906,9.125a3.271,3.271,0,0,0,.938-.137V10a.938.938,0,0,1-1.875,0V8.988A3.27,3.27,0,0,0,9.906,9.125ZM1.7,11H5.554l.469.938h-4.8ZM.937,12.875H6.312v2.188H.937Zm14.125,2.188H7.25V12.406A.466.466,0,0,0,7.2,12.2h0l-.836-1.672a2.638,2.638,0,0,1,1.042-.212h.652a1.875,1.875,0,0,0,3.7,0h.652a2.659,2.659,0,0,1,2.656,2.656Z" fill="#575b6a"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Delivery Boy') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_all_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('delivery-boys.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boys.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('All Delivery Boys') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('add_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('delivery-boys.create') }}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boys.create']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Add Delivery Boy') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('delivery_boy_payment_history')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('delivery-boys-payment-histories') }}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boys-payment-histories']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Payment Histories') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('collected_histories_from_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('delivery-boys-collection-histories') }}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boys-collection-histories']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Collected Histories') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('order_cancle_request_by_delivery_boy')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('delivery-boy.cancel-request') }}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boy.cancel-request']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Cancel Requests') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('delivery_boy_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('delivery-boy-configuration') }}" class="aiz-side-nav-link {{ areActiveRoutes(['delivery-boy-configuration']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Configuration') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- Refunds Addon --}}
                @if (addon_is_activated('refund_request'))
                    @canany(['view_refund_requests','view_approved_refund_requests','view_rejected_refund_requests','refund_request_configuration'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M19.25,11.25a8.031,8.031,0,0,1-15.995,1,.688.688,0,0,1,1.365-.169A6.643,6.643,0,1,0,7.112,6.039h.866a.686.686,0,1,1,0,1.371H5.384A.687.687,0,0,1,4.7,6.724V4.138a.688.688,0,0,1,1.376,0v.987A8.024,8.024,0,0,1,19.25,11.25ZM11.278,6.907a.687.687,0,0,0-.688.686v.253a2.053,2.053,0,0,0-1.824,2.247,2.146,2.146,0,0,0,2.175,1.842h.8a.686.686,0,1,1,0,1.371h-1.6a.686.686,0,1,0,0,1.371h.458v.229a.688.688,0,0,0,1.376,0v-.26a2.113,2.113,0,0,0,1.824-1.811,2.062,2.062,0,0,0-2.053-2.272h-.917a.686.686,0,1,1,0-1.371h1.609a.686.686,0,1,0,0-1.371h-.462V7.593A.687.687,0,0,0,11.278,6.907Z" transform="translate(-3.25 -3.25)" fill="#575b6a"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Refunds') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_refund_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('refund_requests_all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['refund_requests_all', 'reason_show']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Refund Requests') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_approved_refund_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('paid_refund') }}" class="aiz-side-nav-link {{ areActiveRoutes(['paid_refund']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Approved Refunds') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_rejected_refund_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('rejected_refund') }}" class="aiz-side-nav-link {{ areActiveRoutes(['rejected_refund']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Rejected Refunds') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('refund_request_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('refund_time_config') }}" class="aiz-side-nav-link {{ areActiveRoutes(['refund_time_config']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Refund Configuration') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif


                {{-- SECTION 3: USER MANAGEMENT --}}
                <li class="sidebar-category-header">{{ translate('User Management') }}</li>

                {{-- Customers --}}
                @canany(['view_all_customers','view_classified_products','view_classified_packages'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M8,10.667A2.667,2.667,0,1,1,10.667,8,2.667,2.667,0,0,1,8,10.667Zm0-4A1.333,1.333,0,1,0,9.333,8,1.333,1.333,0,0,0,8,6.667Zm4,8.667a4,4,0,1,0-8,0,.667.667,0,0,0,1.333,0,2.667,2.667,0,1,1,5.333,0,.667.667,0,0,0,1.333,0Zm0-10a2.667,2.667,0,1,1,2.667-2.667A2.667,2.667,0,0,1,12,5.333Zm0-4a1.333,1.333,0,1,0,1.333,1.333A1.333,1.333,0,0,0,12,1.333ZM16,10a4,4,0,0,0-4-4,.667.667,0,0,0,0,1.333A2.667,2.667,0,0,1,14.667,10,.667.667,0,1,0,16,10ZM4,5.333A2.667,2.667,0,1,1,6.667,2.667,2.667,2.667,0,0,1,4,5.333Zm0-4A1.333,1.333,0,1,0,5.333,2.667,1.333,1.333,0,0,0,4,1.333ZM1.333,10A2.667,2.667,0,0,1,4,7.333.667.667,0,0,0,4,6a4,4,0,0,0-4,4,.667.667,0,0,0,1.333,0Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Customers') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_customers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('customers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['customers.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Customer List') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if(get_setting('classified_product') == 1)
                                @can('view_classified_products')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('classified_products') }}" class="aiz-side-nav-link {{ areActiveRoutes(['classified_products']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Classified Products') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_classified_packages')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('customer_packages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['customer_packages.index', 'customer_packages.create', 'customer_packages.edit']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Classified Packages') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                        </ul>
                    </li>
                @endcanany

                {{-- Sellers / Vendors --}}
                @if (get_setting('vendor_system_activation') == 1)
                    @canany(['view_all_seller','seller_payment_history','view_seller_payout_requests','seller_commission_configuration','view_all_seller_packages','seller_verification_form_configuration'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M19,9.625a.638.638,0,0,0-.079-.307l-2.779-5A.614.614,0,0,0,15.606,4H6.394a.614.614,0,0,0-.536.318l-2.779,5A.638.638,0,0,0,3,9.625a2.5,2.5,0,0,0,1.231,2.153V18.75A1.24,1.24,0,0,0,5.462,20H9.08a1.24,1.24,0,0,0,1.231-1.25V16.058a.759.759,0,0,1,.615-.773.684.684,0,0,1,.534.176.706.706,0,0,1,.229.521V18.75A1.24,1.24,0,0,0,12.92,20h3.618a1.24,1.24,0,0,0,1.231-1.25V11.777A2.5,2.5,0,0,0,19,9.625Zm-1.239.149a1.23,1.23,0,0,1-2.453-.149.578.578,0,0,0-.017-.086.548.548,0,0,0-.006-.084L14.114,5.25h1.132ZM9.164,5.25h1.22V9.625a1.23,1.23,0,0,1-2.455.063Zm2.451,0h1.22l1.235,4.437a1.23,1.23,0,0,1-2.455-.062Zm-4.862,0H7.886l-1.169,4.2a.548.548,0,0,0-.006.084.578.578,0,0,0-.018.086,1.23,1.23,0,0,1-2.453.149Zm9.785,13.5H12.92V15.981a1.964,1.964,0,0,0-.635-1.446,1.9,1.9,0,0,0-1.482-.491A2,2,0,0,0,9.08,16.061V18.75H5.462V12.125a2.439,2.439,0,0,0,1.846-.848A2.419,2.419,0,0,0,11,11.261a2.419,2.419,0,0,0,3.692.016,2.439,2.439,0,0,0,1.846.848Z" transform="translate(-3 -4)" fill="#575b6a"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Sellers') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_all_seller')
                                    <li class="aiz-side-nav-item">
                                        @php
                                            $sellers_count = \App\Models\Shop::where('verification_status', 0)->where('verification_info', '!=', null)->count();
                                        @endphp
                                        <a href="{{ route('sellers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sellers.index', 'sellers.create', 'sellers.edit', 'sellers.approved','sellers.show_verification_request']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('All Sellers') }}</span>
                                            @if($sellers_count > 0)<span class="badge badge-info">{{ $sellers_count }}</span>@endif
                                        </a>
                                    </li>
                                @endcan
                                @can('seller_payment_history')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('sellers.payment_histories') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sellers.payment_histories']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Payouts') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_seller_payout_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('withdraw_requests_all') }}" class="aiz-side-nav-link {{ areActiveRoutes(['withdraw_requests_all']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Payout Requests') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('seller_commission_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('business_settings.vendor_commission') }}" class="aiz-side-nav-link {{ areActiveRoutes(['business_settings.vendor_commission']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Seller Commission') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @if (addon_is_activated('seller_subscription'))
                                    @can('view_all_seller_packages')
                                        <li class="aiz-side-nav-item">
                                            <a href="{{ route('seller_packages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_packages.index', 'seller_packages.create', 'seller_packages.edit']) }}">
                                                <span class="aiz-side-nav-text">{{ translate('Seller Packages') }}</span>
                                            </a>
                                        </li>
                                    @endcan
                                @endif
                                @can('seller_verification_form_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('seller_verification_form.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_verification_form.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Verification Form') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- Staff & Permissions --}}
                @canany(['staffs','roles'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M87.867,3.07H84.133V1.72A.716.716,0,0,0,83.422,1H80.578a.716.716,0,0,0-.711.72V3.07H76.133A2.149,2.149,0,0,0,74,5.229V14.84A2.149,2.149,0,0,0,76.133,17H87.867A2.149,2.149,0,0,0,90,14.84V5.229A2.149,2.149,0,0,0,87.867,3.07Zm-6.578-.63h1.422V3.79a.711.711,0,1,1-1.422,0Zm7.289,12.4a.716.716,0,0,1-.711.72H76.133a.716.716,0,0,1-.711-.72V5.229a.716.716,0,0,1,.711-.72h3.856a2.124,2.124,0,0,0,4.022,0h3.856a.716.716,0,0,1,.711.72Z" transform="translate(-74 -1)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Staffs') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('staffs')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('staffs.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['staffs.index', 'staffs.create', 'staffs.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('All Staffs') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('roles')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('roles.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['roles.index', 'roles.create', 'roles.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Staff Permissions') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                {{-- SECTION 4: MARKETING & PROMOTIONS --}}
                <li class="sidebar-category-header">{{ translate('Marketing & Content') }}</li>

                {{-- Marketing --}}
                @canany(['view_all_flash_deals','send_newsletter','send_bulk_sms','view_all_subscribers','view_all_coupons'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M38.286,16.393a.555.555,0,0,1-.344-.119L34.032,13.2a.557.557,0,0,1-.213-.438v-5.1a.556.556,0,0,1,.212-.438l3.91-3.074a.557.557,0,0,1,.9.438V15.836a.556.556,0,0,1-.556.557Zm-3.354-3.9,2.8,2.2V5.73l-2.8,2.2Z" transform="translate(-25.364 0)" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Marketing') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_flash_deals')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('flash_deals.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['flash_deals.index', 'flash_deals.create', 'flash_deals.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Flash Deals') }}</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('offers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['offers.index','offers.create','offers.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Offers') }}</span>
                                </a>
                            </li>
                            @if (get_setting('coupon_system') == 1 && auth()->user()->can('view_all_coupons'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('coupon.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['coupon.index','coupon.create','coupon.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Coupons') }}</span>
                                    </a>
                                </li>
                            @endif
                            @can('send_newsletter')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('newsletters.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['newsletters.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Newsletters') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_subscribers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('subscribers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['subscribers.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Subscribers') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @if (addon_is_activated('otp_system') && auth()->user()->can('send_bulk_sms'))
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('sms.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sms.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Bulk SMS') }}</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endcanany

                {{-- Blog System --}}
                @canany(['view_blogs','view_blog_categories'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M9.688,16H3.75A3.754,3.754,0,0,1,0,12.25V3.75A3.754,3.754,0,0,1,3.75,0h8.5A3.754,3.754,0,0,1,16,3.75V9.734a.625.625,0,0,1-1.25,0V3.75a2.5,2.5,0,0,0-2.5-2.5H3.75a2.5,2.5,0,0,0-2.5,2.5v8.5a2.5,2.5,0,0,0,2.5,2.5H9.688a.625.625,0,0,1,0,1.25Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Blog System') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_blogs')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('blog.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog.index', 'blog.create', 'blog.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('All Posts') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_blog_categories')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('blog-category.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['blog-category.index', 'blog-category.create', 'blog-category.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Categories') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Affiliate System Addon --}}
                @if (addon_is_activated('affiliate_system'))
                    @canany(['affiliate_registration_form_config','affiliate_configurations','view_affiliate_users','view_all_referral_users','view_affiliate_withdraw_requests','view_affiliate_logs'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M199.75,1.875a1.875,1.875,0,1,0-1.875,1.875A1.877,1.877,0,0,0,199.75,1.875Zm-1.875.625a.625.625,0,1,1,.625-.625A.626.626,0,0,1,197.875,2.5Z" transform="translate(-189.875)" fill="#575b6a"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Affiliate System') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('affiliate_registration_form_config')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('affiliate.configs') }}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.configs']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Registration Form') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('affiliate_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('affiliate.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Affiliate Configurations') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_affiliate_users')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('affiliate.users') }}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.users', 'affiliate_users.show_verification_request']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Affiliate Users') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_referral_users')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('refferals.users') }}" class="aiz-side-nav-link {{ areActiveRoutes(['refferals.users']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Referral Users') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_affiliate_withdraw_requests')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('affiliate.withdraw_requests') }}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.withdraw_requests']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Withdraw Requests') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_affiliate_logs')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('affiliate.logs.admin') }}" class="aiz-side-nav-link {{ areActiveRoutes(['affiliate.logs.admin']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Affiliate Logs') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- Club Point Addon --}}
                @if (addon_is_activated('club_point'))
                    @canany(['club_point_configurations','set_club_points','view_users_club_points'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <circle cx="8" cy="8" r="7" fill="none" stroke="#575b6a" stroke-width="2"/>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Club Points') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('club_point_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('club_points.configs') }}" class="aiz-side-nav-link {{ areActiveRoutes(['club_points.configs']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Configurations') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('set_club_points')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('set_product_points') }}" class="aiz-side-nav-link {{ areActiveRoutes(['set_product_points', 'product_club_point.edit']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Set Product Points') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_users_club_points')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('club_points.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['club_points.index', 'club_point.details']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('User Points') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif


                {{-- SECTION 5: SUPPORT & CUSTOMER DESK --}}
                <li class="sidebar-category-header">{{ translate('Support & Desk') }}</li>

                {{-- Support --}}
                @canany(['view_all_support_tickets','view_all_product_conversations','view_all_product_queries'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M16,9.125a3.122,3.122,0,0,0-1.255-2.5,6.9,6.9,0,0,0-1.94-4.6,6.725,6.725,0,0,0-9.61,0,6.9,6.9,0,0,0-1.94,4.6,3.124,3.124,0,0,0,1.87,5.627h1.25A.625.625,0,0,0,5,11.625v-5A.625.625,0,0,0,4.375,6H3.125a3.129,3.129,0,0,0-.569.052,5.487,5.487,0,0,1,10.887,0A3.129,3.129,0,0,0,12.875,6h-1.25A.625.625,0,0,0,11,6.625v5a.625.625,0,0,0,.625.625h.625v.625a1.877,1.877,0,0,1-1.875,1.875H8A.625.625,0,0,0,8,16h2.375A3.129,3.129,0,0,0,13.5,12.875v-.688A3.13,3.13,0,0,0,16,9.125Z" fill="#575b6a"/>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Support Desk') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('view_all_support_tickets')
                                @php
                                    $support_ticket = DB::table('tickets')->where('viewed', 0)->count();
                                @endphp
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('support_ticket.admin_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['support_ticket.admin_index', 'support_ticket.admin_show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Support Tickets') }}</span>
                                        @if($support_ticket > 0)<span class="badge badge-info">{{ $support_ticket }}</span>@endif
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_product_conversations')
                                @php
                                    $conversation = \App\Models\Conversation::where('receiver_id', Auth::user()->id)->where('receiver_viewed', '1')->count();
                                @endphp
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('conversations.admin_index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['conversations.admin_index', 'conversations.admin_show']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Conversations') }}</span>
                                        @if ($conversation > 0)<span class="badge badge-info">{{ $conversation }}</span>@endif
                                    </a>
                                </li>
                            @endcan
                            @if (get_setting('product_query_activation') == 1)
                                @can('view_all_product_queries')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('product_query.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['product_query.index','product_query.show']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Product Queries') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endif
                        </ul>
                    </li>
                @endcanany


                {{-- SECTION 6: WEBSITE SETUP & MEDIA --}}
                <li class="sidebar-category-header">{{ translate('Website Setup') }}</li>

                {{-- Website Setup --}}
                @canany(['header_setup','footer_setup','view_all_website_pages','website_appearance','edit_website_page'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Website Setup') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('edit_website_page')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.homepage-builder') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.homepage-builder']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Homepage Builder') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('custom-pages.edit', ['id'=>'home', 'lang'=>env('DEFAULT_LANGUAGE'), 'page'=>'home']) }}" class="aiz-side-nav-link {{ (url()->current() == url('/admin/website/custom-pages/edit/home')) ? 'active' : '' }}">
                                        <span class="aiz-side-nav-text">{{ translate('Homepage Settings') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('header_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.header') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.header']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Header') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('footer_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.footer', ['lang'=> App::getLocale()]) }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.footer']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Footer') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.product-category-info') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.product-category-info']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Category Info') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('view_all_website_pages')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.pages') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.pages', 'custom-pages.create' ,'custom-pages.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Pages') }}</span>
                                    </a>
                                </li>
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('homepage-reviews.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['homepage-reviews.index', 'homepage-reviews.create', 'homepage-reviews.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Homepage Reviews') }}</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('team-members.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['team-members.index', 'team-members.create', 'team-members.edit']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Team Members') }}</span>
                                </a>
                            </li>
                            @can('website_appearance')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('website.appearance') }}" class="aiz-side-nav-link {{ areActiveRoutes(['website.appearance']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Appearance') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Uploaded Files --}}
                @can('uploaded_files')
                    <li class="aiz-side-nav-item">
                        <a href="{{ route('uploaded-files.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['uploaded-files.index', 'uploaded-files.create']) }}">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Uploaded Files') }}</span>
                        </a>
                    </li>
                @endcan


                {{-- SECTION 7: SETTINGS & CONFIGURATIONS --}}
                <li class="sidebar-category-header">{{ translate('Settings & System') }}</li>

                {{-- Setup & Configurations --}}
                @canany(['general_settings','features_activation','language_setup','currency_setup','vat_&_tax_setup',
                        'pickup_point_setup','smtp_settings','payment_methods_configurations','order_configuration','file_system_&_cache_configuration',
                        'social_media_logins','facebook_chat','facebook_comment','analytics_tools_configuration','google_recaptcha_configuration','google_map_setting',
                        'google_firebase_setting'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Setup & Configs') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('general_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('general_setting.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['general_setting.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('General Settings') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('features_activation')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('activation.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['activation.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Features Activation') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('language_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('languages.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['languages.index', 'languages.create', 'languages.show', 'languages.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Languages') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('currency_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('currency.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['currency.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Currency') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('vat_&_tax_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('tax.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['tax.index', 'tax.create', 'tax.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Vat & TAX') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('pickup_point_setup')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('pick_up_points.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['pick_up_points.index','pick_up_points.create','pick_up_points.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Pickup Points') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('smtp_settings')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('smtp_settings.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['smtp_settings.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('SMTP Settings') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('payment_methods_configurations')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('payment_method.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['payment_method.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Payment Methods') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('order_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('order_configuration.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['order_configuration.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Order Configuration') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('file_system_&_cache_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('file_system.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['file_system.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('File System & Cache') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('social_media_logins')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('social_login.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['social_login.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Social Media Logins') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @canany(['facebook_chat','facebook_comment'])
                                <li class="aiz-side-nav-item">
                                    <a href="#" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Facebook Settings') }}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('facebook_chat')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('facebook_chat.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['facebook_chat.index']) }}">
                                                    <span class="aiz-side-nav-text">{{ translate('Facebook Chat') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('facebook_comment')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('facebook-comment') }}" class="aiz-side-nav-link {{ areActiveRoutes(['facebook-comment']) }}">
                                                    <span class="aiz-side-nav-text">{{ translate('Facebook Comment') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canany(['analytics_tools_configuration','google_recaptcha_configuration','google_map_setting','google_firebase_setting'])
                                <li class="aiz-side-nav-item">
                                    <a href="#" class="aiz-side-nav-link">
                                        <span class="aiz-side-nav-text">{{ translate('Google Settings') }}</span>
                                        <span class="aiz-side-nav-arrow"></span>
                                    </a>
                                    <ul class="aiz-side-nav-list level-3">
                                        @can('analytics_tools_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google_analytics.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['google_analytics.index']) }}">
                                                    <span class="aiz-side-nav-text">{{ translate('Analytics Tools') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_recaptcha_configuration')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google_recaptcha.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['google_recaptcha.index']) }}">
                                                    <span class="aiz-side-nav-text">{{ translate('Google reCAPTCHA') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_map_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google-map.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['google-map.index']) }}">
                                                    <span class="aiz-side-nav-text">{{ translate('Google Map') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('google_firebase_setting')
                                            <li class="aiz-side-nav-item">
                                                <a href="{{ route('google-firebase.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['google-firebase.index']) }}">
                                                    <span class="aiz-side-nav-text">{{ translate('Google Firebase') }}</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                        </ul>
                    </li>
                @endcanany

                {{-- Shipping Setup --}}
                @canany(['shipping_configuration','shipping_country_setting','manage_shipping_states','manage_shipping_cities','manage_zones','manage_carriers'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="1" y="3" width="15" height="13" rx="2"></rect>
                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Shipping Setup') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('shipping_configuration')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('shipping_configuration.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['shipping_configuration.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Shipping Configuration') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('shipping_country_setting')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('countries.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['countries.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Countries') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_shipping_states')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('states.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['states.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('States') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_shipping_cities')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('cities.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['cities.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Cities') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_zones')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('zones.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['zones.index', 'zones.create', 'zones.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Zones') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('manage_carriers')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('carriers.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['carriers.index', 'carriers.create', 'carriers.edit']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Carriers') }}</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Offline Payment Addon --}}
                @if (addon_is_activated('offline_payment'))
                    @canany(['view_all_manual_payment_methods','view_all_offline_wallet_recharges','view_all_offline_customer_package_payments','view_all_offline_seller_package_payments'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                        <line x1="2" y1="10" x2="22" y2="10"></line>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('Offline Payment') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('view_all_manual_payment_methods')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('manual_payment_methods.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['manual_payment_methods.index', 'manual_payment_methods.create', 'manual_payment_methods.edit']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Manual Payment Methods') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_offline_wallet_recharges')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('offline_wallet_recharge_request.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['offline_wallet_recharge_request.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Offline Wallet Recharges') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_offline_customer_package_payments')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('offline_customer_package_payment_request.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['offline_customer_package_payment_request.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Customer Package Payments') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('view_all_offline_seller_package_payments')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('offline_seller_package_payment_request.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['offline_seller_package_payment_request.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Seller Package Payments') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- OTP System Addon --}}
                @if (addon_is_activated('otp_system'))
                    @canany(['otp_configurations','sms_templates','sms_providers_configurations'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('OTP System') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('otp_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('otp.configconfiguration') }}" class="aiz-side-nav-link {{ areActiveRoutes(['otp.configconfiguration']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('OTP Configurations') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('sms_templates')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('sms-templates.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['sms-templates.index', 'sms-templates.edit']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('SMS Templates') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('sms_providers_configurations')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('otp_credentials.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['otp_credentials.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Set OTP Credentials') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- African Payment Gateway Addon --}}
                @if(addon_is_activated('african_pg'))
                    @canany(['african_pg_configuration','african_pg_credentials_configuration'])
                        <li class="aiz-side-nav-item">
                            <a href="#" class="aiz-side-nav-link">
                                <div class="aiz-side-nav-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                </div>
                                <span class="aiz-side-nav-text">{{ translate('African Payment Gateway') }}</span>
                                <span class="aiz-side-nav-arrow"></span>
                            </a>
                            <ul class="aiz-side-nav-list level-2">
                                @can('african_pg_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('african.configuration') }}" class="aiz-side-nav-link {{ areActiveRoutes(['african.configuration']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('African PG Configs') }}</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('african_pg_credentials_configuration')
                                    <li class="aiz-side-nav-item">
                                        <a href="{{ route('african_credentials.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['african_credentials.index']) }}">
                                            <span class="aiz-side-nav-text">{{ translate('Set African Credentials') }}</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- Reports --}}
                @canany(['in_house_product_sale_report','seller_products_sale_report','products_stock_report','product_wishlist_report','user_search_report','commission_history_report','wallet_transaction_report'])
                    <li class="aiz-side-nav-item">
                        <a href="#" class="aiz-side-nav-link">
                            <div class="aiz-side-nav-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                            </div>
                            <span class="aiz-side-nav-text">{{ translate('Reports') }}</span>
                            <span class="aiz-side-nav-arrow"></span>
                        </a>
                        <ul class="aiz-side-nav-list level-2">
                            @can('in_house_product_sale_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('in_house_sale_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['in_house_sale_report.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Product Sale Report') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('seller_products_sale_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('seller_sale_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['seller_sale_report.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Seller Sale Report') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('products_stock_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('stock_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['stock_report.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Stock Report') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('product_wishlist_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('wish_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wish_report.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Wishlist Report') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('user_search_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('user_search_report.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['user_search_report.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('User Searches Report') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('commission_history_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('commission-log.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['commission-log.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Commission History') }}</span>
                                    </a>
                                </li>
                            @endcan
                            @can('wallet_transaction_report')
                                <li class="aiz-side-nav-item">
                                    <a href="{{ route('wallet-history.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['wallet-history.index']) }}">
                                        <span class="aiz-side-nav-text">{{ translate('Wallet History') }}</span>
                                    </a>
                                </li>
                            @endcan
                            <li class="aiz-side-nav-item">
                                <a href="{{ route('event-viewer.index') }}" class="aiz-side-nav-link {{ areActiveRoutes(['event-viewer.index']) }}">
                                    <span class="aiz-side-nav-text">{{ translate('Event Viewer') }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                {{-- System Update & Server --}}
                <li class="aiz-side-nav-item">
                    <a href="#" class="aiz-side-nav-link">
                        <div class="aiz-side-nav-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect>
                                <rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect>
                                <line x1="6" y1="6" x2="6.01" y2="6"></line>
                                <line x1="6" y1="18" x2="6.01" y2="18"></line>
                            </svg>
                        </div>
                        <span class="aiz-side-nav-text">{{ translate('System Status') }}</span>
                        <span class="aiz-side-nav-arrow"></span>
                    </a>
                    <ul class="aiz-side-nav-list level-2">
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('system_update') }}" class="aiz-side-nav-link {{ areActiveRoutes(['system_update']) }}">
                                <span class="aiz-side-nav-text">{{ translate('System Update') }}</span>
                            </a>
                        </li>
                        <li class="aiz-side-nav-item">
                            <a href="{{ route('system_server') }}" class="aiz-side-nav-link {{ areActiveRoutes(['system_server']) }}">
                                <span class="aiz-side-nav-text">{{ translate('Server Status') }}</span>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
    <div class="aiz-sidebar-overlay"></div>
</div>
