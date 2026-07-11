@extends('backend.layouts.app')

@section('content')
<link rel="stylesheet" href="{{ static_asset('assets/css/custom-footer.css') }}">
<style>
    /* Premium Visual Editor Layout Styles */
    .ttf-editor-layout {
        display: flex;
        gap: 24px;
        margin-top: 15px;
    }
    .ttf-preview-pane {
        flex: 1;
        min-width: 0;
        background: #f1f2f7;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e4e5eb;
    }
    .ttf-preview-title {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .ttf-preview-wrapper {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        border: 2px dashed #b5b5bf;
        position: relative;
    }

    /* Config Panel styling */
    .ttf-config-pane {
        width: 440px;
        flex-shrink: 0;
    }
    .ttf-config-card {
        border-radius: 12px;
        background: #fff;
        border: 1px solid #e4e5eb;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
        position: sticky;
        top: 20px;
    }

    /* Interactive Preview Hotspots */
    .ttf-hotspot {
        position: relative;
        cursor: pointer;
        transition: all 0.25s ease;
        border: 2px solid transparent !important;
    }
    .ttf-hotspot:hover {
        border-color: #3390f3 !important;
        background: rgba(51, 144, 243, 0.03) !important;
    }
    .ttf-hotspot.active {
        border-color: #3390f3 !important;
        box-shadow: 0 0 0 4px rgba(51, 144, 243, 0.15);
        background: rgba(51, 144, 243, 0.05) !important;
    }
    .ttf-edit-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background: #3390f3;
        color: #fff;
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 20px;
        font-weight: 600;
        z-index: 10;
        opacity: 0;
        transform: translateY(-5px);
        transition: all 0.25s ease;
        pointer-events: none;
        box-shadow: 0 4px 10px rgba(51, 144, 243, 0.3);
    }
    .ttf-hotspot:hover .ttf-edit-badge {
        opacity: 1;
        transform: translateY(0);
    }

    /* Settings panel tabs */
    .config-tabs {
        border-bottom: 1px solid #e4e5eb;
        background: #f8f9fa;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        overflow: hidden;
    }
    .config-tab-btn {
        border: none;
        background: none;
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
    }
    .config-tab-btn:hover {
        color: #3390f3;
        background: rgba(51, 144, 243, 0.03);
    }
    .config-tab-btn.active {
        color: #3390f3;
        border-bottom-color: #3390f3;
        background: #ffffff;
    }

    .tab-content-pane {
        display: none;
        padding: 20px;
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .tab-content-pane.active {
        display: block;
    }

    /* Widget card builder styling */
    .widget-card {
        border: 1px solid #e4e5eb;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    .widget-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .menu-link-row {
        background: #f8f9fa;
        border: 1px solid #e4e5eb;
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
    }
    .menu-link-row .btn-remove-row {
        position: absolute;
        top: -6px;
        right: -6px;
        padding: 2px;
        font-size: 10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .widget-mobile-settings {
        background: #f7f9fc;
        border: 1px solid #dfe6ee;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 12px;
    }
    .widget-mobile-settings .mobile-settings-title {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .extra-social-row {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 8px;
        margin-bottom: 8px;
        background: #fff;
    }
    .footer-layout-list {
        display: grid;
        gap: 10px;
    }
    .footer-layout-row {
        border: 1px solid #dbe3ee;
        border-radius: 10px;
        background: #fff;
        padding: 12px;
        cursor: grab;
    }
    .footer-layout-row.is-dragging {
        opacity: 0.45;
    }
    .footer-layout-row-top {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 10px;
    }
    .footer-layout-grip {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #f8fafc;
        border: 1px solid #dbe3ee;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .footer-layout-meta {
        flex: 1;
        min-width: 0;
    }
    .footer-layout-title {
        font-size: 12px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .footer-layout-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
    }
    .footer-layout-order-badge {
        font-size: 10px;
        font-weight: 700;
        color: #475569;
        background: #eef2f7;
        border-radius: 999px;
        padding: 4px 8px;
        white-space: nowrap;
    }

    .ttf-builder-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 14px 18px;
        border: 1px solid #e4e5eb;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
        margin-bottom: 18px;
    }
    .ttf-builder-brand {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .ttf-builder-brand-mark {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: linear-gradient(135deg, #9c542a, #c78b61);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        letter-spacing: 0.04em;
    }
    .ttf-builder-brand-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }
    .ttf-builder-brand-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }
    .ttf-builder-topbar-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }
    .ttf-device-switch {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px;
        border-radius: 11px;
        background: #f1f5f9;
    }
    .ttf-device-switch-btn {
        border: 0;
        background: transparent;
        color: #64748b;
        min-width: 40px;
        height: 34px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        transition: all 0.2s ease;
    }
    .ttf-device-switch-btn.active {
        background: #fff;
        color: #9c542a;
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.12);
    }
    .ttf-editor-layout {
        display: flex;
        gap: 20px;
        margin-top: 0;
        align-items: start;
        width: 100%;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .ttf-left-pane {
        width: 280px;
        flex-shrink: 0;
        position: sticky;
        top: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: left center;
    }
    .ttf-config-pane {
        width: 430px;
        flex-shrink: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform-origin: right center;
    }
    .ttf-left-pane,
    .ttf-config-card {
        border-radius: 14px;
        background: #fff;
        border: 1px solid #e4e5eb;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.04);
    }
    .ttf-config-card {
        position: sticky;
        top: 20px;
    }
    .left-collapsed .ttf-left-pane {
        width: 0 !important;
        min-width: 0 !important;
        opacity: 0 !important;
        visibility: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
    }
    .right-collapsed .ttf-config-pane {
        width: 0 !important;
        min-width: 0 !important;
        opacity: 0 !important;
        visibility: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
    }

    /* Columns inspector redesign */
    #columns-accordion .card-col-settings {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        margin-bottom: 0 !important;
        display: none;
    }
    #columns-accordion .card-col-settings.active {
        display: block !important;
    }
    #columns-accordion .card-col-settings > .card-header {
        display: none !important; /* Hide the old accordion header entirely */
    }
    #columns-accordion .card-col-settings > .collapse {
        display: block !important; /* Let body display when container card is active */
    }
    .active-col-pills .col-pill-btn {
        transition: all 0.2s ease;
        background-color: #ffffff !important;
        color: #685b4e !important;
        border-color: rgba(135, 106, 75, 0.2) !important;
    }
    .active-col-pills .col-pill-btn:hover {
        background-color: rgba(135, 106, 75, 0.05) !important;
        color: #876a4b !important;
        border-color: #876a4b !important;
    }
    .active-col-pills .col-pill-btn.active {
        background-color: #876a4b !important;
        color: #ffffff !important;
        border-color: #876a4b !important;
        box-shadow: 0 4px 10px rgba(135, 106, 75, 0.2) !important;
    }
    .active-col-pills .col-pill-btn.active .status-indicator-dot {
        border: 1px solid #fff;
    }
    .grid-preset-card {
        transition: all 0.2s ease !important;
    }
    .grid-preset-card:hover {
        transform: translateY(-2px);
        border-color: #876a4b !important;
        box-shadow: 0 4px 12px rgba(135, 106, 75, 0.1);
    }
    .grid-preset-card:active {
        transform: translateY(0);
    }
    .quick-add-widget-btn {
        transition: all 0.2s ease !important;
    }
    .quick-add-widget-btn:hover {
        background-color: #876a4b !important;
        color: #ffffff !important;
        border-color: #876a4b !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(135, 106, 75, 0.15);
    }

    /* Premium Widget Cards inside Columns Settings */
    .widgets-list .widget-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        overflow: hidden !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.25s ease !important;
        background: #ffffff !important;
        margin-bottom: 16px !important;
    }
    .widgets-list .widget-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02) !important;
        border-color: #cbd5e1 !important;
        transform: translateY(-2px);
    }
    .widgets-list .widget-card .card-header {
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 10px 16px !important;
        cursor: grab;
    }
    .widgets-list .widget-card .card-body {
        padding: 16px !important;
    }

    /* Themed highlight headers for each widget type */
    .widgets-list .widget-card[data-type="menu_links"] .card-header {
        background: #f0f7ff !important;
        border-bottom-color: #e0f2fe !important;
    }
    .widgets-list .widget-card[data-type="menu_links"] .card-header span {
        color: #0284c7 !important;
    }
    .widgets-list .widget-card[data-type="important_links"] .card-header {
        background: #f5f3ff !important;
        border-bottom-color: #ede9fe !important;
    }
    .widgets-list .widget-card[data-type="important_links"] .card-header span {
        color: #7c3aed !important;
    }
    .widgets-list .widget-card[data-type="my_account"] .card-header {
        background: #f0fdf4 !important;
        border-bottom-color: #dcfce7 !important;
    }
    .widgets-list .widget-card[data-type="my_account"] .card-header span {
        color: #16a34a !important;
    }
    .widgets-list .widget-card[data-type="text_html"] .card-header {
        background: #fefce8 !important;
        border-bottom-color: #fef9c3 !important;
    }
    .widgets-list .widget-card[data-type="text_html"] .card-header span {
        color: #ca8a04 !important;
    }
    .widgets-list .widget-card[data-type="seller_zone"] .card-header {
        background: #fff7ed !important;
        border-bottom-color: #ffedd5 !important;
    }
    .widgets-list .widget-card[data-type="seller_zone"] .card-header span {
        color: #ea580c !important;
    }
    .widgets-list .widget-card[data-type="images_widget"] .card-header {
        background: #ecfeff !important;
        border-bottom-color: #cffafe !important;
    }
    .widgets-list .widget-card[data-type="images_widget"] .card-header span {
        color: #0891b2 !important;
    }
    .widgets-list .widget-card[data-type="social_icons"] .card-header {
        background: #fdf2f8 !important;
        border-bottom-color: #fbcfe8 !important;
    }
    .widgets-list .widget-card[data-type="social_icons"] .card-header span {
        color: #db2777 !important;
    }

    .widgets-list .widget-card .btn-group .btn {
        padding: 4px 6px !important;
        font-size: 14px !important;
        color: #64748b !important;
        transition: color 0.15s ease;
    }
    .widgets-list .widget-card .btn-group .btn:hover {
        color: #0f172a !important;
    }
    .widgets-list .widget-card .btn-group .btn.text-danger:hover {
        color: #ef4444 !important;
    }
    .ttf-side-head {
        padding: 18px 16px 14px;
        border-bottom: 1px solid #e4e5eb;
        background: #fbfcfd;
    }
    .ttf-side-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }
    .ttf-side-subtitle {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 4px;
    }
    .ttf-side-search {
        margin-top: 12px;
    }
    .ttf-side-search input {
        width: 100%;
        height: 40px;
        border-radius: 10px;
        border: 1px solid #dbe3ee;
        padding: 0 12px;
        font-size: 13px;
    }
    .ttf-side-tabs {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        padding: 12px 12px 0;
    }
    .ttf-side-tab-btn {
        border: 0;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 700;
        padding: 10px 12px;
        transition: all 0.2s ease;
    }
    .ttf-side-tab-btn.active {
        background: #f7eee8;
        color: #9c542a;
    }
    .ttf-side-panel {
        display: none;
        padding: 14px 12px 16px;
        max-height: calc(100vh - 270px);
        overflow-y: auto;
    }
    .ttf-side-panel.active {
        display: block;
    }
    .ttf-widget-library {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .ttf-widget-library-card {
        width: 100%;
        border: 1px solid #dbe3ee;
        background: #fff;
        border-radius: 12px;
        padding: 12px 10px;
        text-align: left;
        transition: all 0.2s ease;
    }
    .ttf-widget-library-card:hover {
        border-color: #c78b61;
        box-shadow: 0 8px 20px rgba(156, 84, 42, 0.08);
        transform: translateY(-1px);
    }
    .ttf-widget-library-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f7eee8;
        color: #9c542a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-bottom: 10px;
    }
    .ttf-widget-library-title {
        font-size: 12px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.35;
    }
    .ttf-widget-library-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 4px;
    }
    .ttf-library-footer {
        margin-top: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 12px;
        color: #475569;
        line-height: 1.5;
    }
    .ttf-library-footer strong {
        color: #0f172a;
    }
    .ttf-navigator {
        display: grid;
        gap: 8px;
    }
    .ttf-nav-item {
        width: 100%;
        border: 1px solid #dbe3ee;
        background: #fff;
        border-radius: 10px;
        padding: 10px 12px;
        text-align: left;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        color: #334155;
        transition: all 0.2s ease;
    }
    .ttf-nav-item:hover,
    .ttf-nav-item.active {
        border-color: #c78b61;
        background: #fff9f5;
        color: #9c542a;
    }
    .ttf-nav-item span:last-child {
        margin-left: auto;
        font-size: 10px;
        color: #94a3b8;
    }
    .ttf-nav-item.is-child {
        margin-left: 14px;
        width: calc(100% - 14px);
    }
    .ttf-nav-item.is-grandchild {
        margin-left: 28px;
        width: calc(100% - 28px);
    }
    .ttf-preview-pane {
        padding: 0;
        background: transparent;
        border: 0;
    }
    .ttf-canvas-area {
        background: #f1f2f7;
        border-radius: 16px;
        padding: 18px;
        border: 1px solid #e4e5eb;
    }
    .ttf-canvas-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 12px;
    }
    .ttf-preview-title {
        margin-bottom: 0;
    }
    .ttf-preview-meta {
        font-size: 12px;
        color: #64748b;
    }
    .ttf-preview-shell {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #dbe3ee;
        box-shadow: 0 16px 40px rgba(15, 23, 42, 0.08);
        overflow: hidden;
    }
    .ttf-preview-shell-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 18px;
        border-bottom: 1px solid #e4e5eb;
        background: #fff;
    }
    .ttf-preview-shell-title {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
    }
    .ttf-preview-shell-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: 2px;
    }
    .ttf-preview-stage {
        padding: 18px;
        overflow-x: auto;
    }
    .ttf-preview-wrapper {
        max-width: 1180px;
        margin: 0 auto;
        background: #f8fafc;
    }
    .ttf-preview-wrapper.ttf-device-tablet {
        max-width: 820px;
    }
    .ttf-preview-wrapper.ttf-device-mobile {
        max-width: 430px;
    }
    .ttf-preview-wrapper.ttf-device-mobile .d-none.d-md-block,
    .ttf-preview-wrapper.ttf-device-mobile .d-md-block {
        display: none !important;
    }
    .ttf-preview-wrapper.ttf-device-mobile .d-md-none {
        display: block !important;
    }
    .ttf-preview-wrapper.ttf-device-mobile .ttf-footer-bottom-bar .row {
        flex-direction: column;
        align-items: flex-start !important;
    }
    .ttf-preview-wrapper.ttf-device-mobile .ttf-footer-bottom-bar .text-right {
        text-align: left !important;
        margin-top: 12px;
    }
    .ttf-config-pane {
        width: 430px;
        flex-shrink: 0;
    }
    .ttf-config-card {
        top: 20px;
        overflow: hidden;
    }
    .ttf-config-head {
        padding: 18px 18px 12px;
        border-bottom: 1px solid #e4e5eb;
        background: #fbfcfd;
    }
    .ttf-config-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
    }
    .ttf-config-subtitle {
        font-size: 12px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 4px;
    }
    .config-tabs {
        background: #fff;
    }
    .tab-content-pane {
        max-height: calc(100vh - 310px);
    }

    @media (max-width: 1399px) {
        .ttf-editor-layout {
            grid-template-columns: 260px minmax(0, 1fr) 390px;
        }
    }

    @media (max-width: 1199px) {
        .ttf-editor-layout {
            grid-template-columns: 1fr;
        }
        .ttf-left-pane,
        .ttf-config-card {
            position: static;
        }
        .ttf-side-panel,
        .tab-content-pane {
            max-height: none;
        }
    }

    @media (max-width: 767px) {
        .ttf-builder-topbar,
        .ttf-canvas-head,
        .ttf-preview-shell-head {
            flex-direction: column;
            align-items: flex-start;
        }
        .ttf-builder-topbar-actions {
            width: 100%;
            justify-content: flex-start;
        }
        .ttf-widget-library {
            grid-template-columns: 1fr;
        }
        .ttf-canvas-area,
        .ttf-preview-stage {
            padding: 12px;
        }
    }
</style>

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Website Footer Builder') }}</h1>
        </div>
    </div>
</div>

<!-- Language Selector Tabs -->
<ul class="nav nav-tabs nav-fill border-light bg-white rounded shadow-sm mb-3">
    @foreach (get_all_active_language() as $key => $language)
        <li class="nav-item">
            <a class="nav-link text-reset @if ($language->code == $lang) active font-weight-bold text-primary @else bg-soft-dark border-light @endif py-3" href="{{ route('website.footer', ['lang'=> $language->code] ) }}">
                <img src="{{ static_asset('assets/img/flags/'.$language->code.'.png') }}" height="11" class="mr-1">
                <span>{{ $language->name }}</span>
            </a>
        </li>
    @endforeach
</ul>

<!-- Footer Backup & Restore Section -->
<div class="card shadow-sm mb-3">
    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-light">
        <h6 class="mb-0 font-weight-bold text-dark"><i class="las la-sync-alt"></i> {{ translate('Footer Backup & Restore (JSON)') }}</h6>
    </div>
    <div class="card-body py-3">
        <div class="row align-items-center">
            <div class="col-md-6 border-right">
                <p class="text-muted fs-12 mb-2">{{ translate('Export your current footer configurations (all widgets, links, text, menus, styling, colors) to a JSON file for backup.') }}</p>
                <a href="{{ route('website.footer.export') }}" class="btn btn-xs btn-primary">
                    <i class="las la-download"></i> {{ translate('Export Footer Settings') }}
                </a>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <p class="text-muted fs-12 mb-2">{{ translate('Restore/import footer configurations from a previously exported JSON file.') }}</p>
                <form action="{{ route('website.footer.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center" id="footer-import-form">
                    @csrf
                    <div class="mr-2 flex-grow-1" style="min-height:28px;display:flex;align-items:center;">
                        <input type="file" name="footer_file" id="footerFile" accept=".json" required class="d-none"
                               onchange="if (this.files && this.files.length) { document.getElementById('footer-import-file-name').textContent = this.files[0].name; }">
                        <small class="text-muted" id="footer-import-file-name">{{ translate('No file selected') }}</small>
                    </div>
                    <button type="button" class="btn btn-xs btn-success flex-shrink-0"
                            onclick="(function(){ var input = document.getElementById('footerFile'); var form = document.getElementById('footer-import-form'); if (input && input.files && input.files.length > 0) { form.submit(); } else if (input) { input.click(); } })();">
                        <i class="las la-upload"></i> {{ translate('Import Settings') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data" onsubmit="refreshColumnIndices()">
    @csrf

    <input type="hidden" name="tab" value="footer-builder">
    <input type="hidden" name="lang_edit" value="{{ $lang }}">

    <div class="ttf-builder-topbar">
        <div class="ttf-builder-brand">
            <div class="ttf-builder-brand-mark">TF</div>
            <div>
                <div class="ttf-builder-brand-title">{{ translate('Elementor Style Footer Builder') }}</div>
                <div class="ttf-builder-brand-subtitle">{{ translate('Manage desktop and mobile footer sections from one structured builder.') }}</div>
            </div>
        </div>
        <div class="ttf-builder-topbar-actions">
            <div class="ttf-device-switch mr-2">
                <button type="button" class="ttf-device-switch-btn active" id="btn-toggle-left" onclick="toggleLeftSidebar(); event.preventDefault();" title="{{ translate('Toggle Left Sidebar (Footer Builder)') }}">
                    <i class="las la-columns" style="font-size: 16px;"></i>
                </button>
                <button type="button" class="ttf-device-switch-btn active" id="btn-toggle-right" onclick="toggleRightSidebar(); event.preventDefault();" title="{{ translate('Toggle Right Sidebar (Settings)') }}">
                    <i class="las la-cog" style="font-size: 16px;"></i>
                </button>
            </div>
            <div class="ttf-device-switch" role="tablist" aria-label="{{ translate('Preview device switcher') }}">
                <button type="button" class="ttf-device-switch-btn active" data-preview-device="desktop">{{ translate('Desktop') }}</button>
                <button type="button" class="ttf-device-switch-btn" data-preview-device="tablet">{{ translate('Tablet') }}</button>
                <button type="button" class="ttf-device-switch-btn" data-preview-device="mobile">{{ translate('Mobile') }}</button>
            </div>
            <a href="{{ route('website.footer.export') }}" class="btn btn-sm btn-soft-primary">
                <i class="las la-download"></i> {{ translate('Export JSON') }}
            </a>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="las la-save"></i> {{ translate('Save Footer') }}
            </button>
        </div>
    </div>

    <div class="ttf-editor-layout">

        <aside class="ttf-left-pane">
            <div class="ttf-side-head">
                <div class="ttf-side-title">{{ translate('Footer Builder') }}</div>
                <div class="ttf-side-subtitle">{{ translate('Browse widgets, jump between columns, and keep the footer structure manageable.') }}</div>
                <div class="ttf-side-search">
                    <input type="text" id="ttf-widget-search" placeholder="{{ translate('Search widgets...') }}">
                </div>
            </div>
            <div class="ttf-side-tabs">
                <button type="button" class="ttf-side-tab-btn active" data-ttf-side-tab="widgets">{{ translate('Widgets') }}</button>
                <button type="button" class="ttf-side-tab-btn" data-ttf-side-tab="navigator">{{ translate('Navigator') }}</button>
            </div>
            <div class="ttf-side-panel active" id="ttf-side-panel-widgets">
                <div class="ttf-widget-library">
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="menu_links">
                        <span class="ttf-widget-library-icon"><i class="las la-list"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('Custom Links') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Editable menu blocks with repeater links.') }}</div>
                    </button>
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="important_links">
                        <span class="ttf-widget-library-icon"><i class="las la-link"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('Important Links') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Auto-load footer CMS pages.') }}</div>
                    </button>
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="my_account">
                        <span class="ttf-widget-library-icon"><i class="las la-user"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('My Account') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Login, orders, wishlist, and tracking links.') }}</div>
                    </button>
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="text_html">
                        <span class="ttf-widget-library-icon"><i class="las la-code"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('Custom HTML') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Free text, HTML, or utility content.') }}</div>
                    </button>
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="seller_zone">
                        <span class="ttf-widget-library-icon"><i class="las la-store"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('Seller Zone') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Separate login, register, and follow sections for mobile.') }}</div>
                    </button>
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="images_widget">
                        <span class="ttf-widget-library-icon"><i class="las la-images"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('Delivery / Payment') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Delivery partners, secure payment, and trust blocks.') }}</div>
                    </button>
                    <button type="button" class="ttf-widget-library-card" data-quick-widget="social_icons">
                        <span class="ttf-widget-library-icon"><i class="las la-share-alt"></i></span>
                        <div class="ttf-widget-library-title">{{ translate('Follow Us') }}</div>
                        <div class="ttf-widget-library-desc">{{ translate('Standalone social widget with extra icons.') }}</div>
                    </button>
                </div>
                <div class="ttf-library-footer">
                    <strong>{{ translate('Selected column:') }}</strong>
                    <span id="ttf-selected-column-label">{{ translate('Column 1') }}</span><br>
                    {{ translate('Quick add uses the currently selected column from the preview or navigator.') }}
                </div>
            </div>
            <div class="ttf-side-panel" id="ttf-side-panel-navigator">
                <div id="ttf-footer-navigator" class="ttf-navigator"></div>
            </div>
        </aside>

        <!-- Live Preview Area -->
        <div class="ttf-preview-pane">
            <div class="ttf-canvas-area">
                <div class="ttf-canvas-head">
                    <div class="ttf-preview-title">
                        <i class="las la-eye"></i>
                        <span>{{ translate('Live Preview Workspace') }}</span>
                    </div>
                    <div class="ttf-preview-meta">{{ translate('Click any section or column to open its settings. Use the left panel to add widgets quickly.') }}</div>
                </div>
                <div class="ttf-preview-shell">
                    <div class="ttf-preview-shell-head">
                        <div>
                            <div class="ttf-preview-shell-title">{{ translate('Website Footer Preview') }}</div>
                            <div class="ttf-preview-shell-subtitle">{{ translate('Desktop and mobile structure stays linked to the same global fields and repeaters.') }}</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-soft-secondary" onclick="activateGlobalStyles()">
                            <i class="las la-cog"></i> {{ translate('Global Settings') }}
                        </button>
                    </div>
                    <div class="ttf-preview-stage">
                        <div class="ttf-preview-wrapper ttf-device-desktop" id="ttf-preview-wrapper">

                @php
                    // Pre-fill colors and values
                    $foot_bg_color = get_setting('foot_bg_color', '#fdfbf9');
                    $foot_border_color = get_setting('foot_border_color', 'rgba(104, 91, 78, 0.2)');
                    $foot_head_color = get_setting('foot_head_color', '#000000');
                    $foot_text_color = get_setting('foot_text_color', '#39322a');
                    $foot_hover_color = get_setting('foot_hover_color', '#876a4b');
                    $foot_pad_top = get_setting('foot_pad_top', '45px');
                    $foot_pad_bot = get_setting('foot_pad_bot', '45px');
                    $foot_pad_left = get_setting('foot_pad_left', '0px');
                    $foot_pad_right = get_setting('foot_pad_right', '0px');
                    $foot_mob_pad_top = get_setting('foot_mob_pad_top', '12px');
                    $foot_mob_pad_bot = get_setting('foot_mob_pad_bot', '12px');
                    $foot_mob_pad_left = get_setting('foot_mob_pad_left', '0px');
                    $foot_mob_pad_right = get_setting('foot_mob_pad_right', '0px');
                    $foot_bg_img = get_setting('foot_bg_img');
                    $foot_mob_bg_img = get_setting('foot_mob_bg_img');
                    $foot_bg_pattern_left = get_setting('foot_bg_pattern_left');
                    $foot_bg_pattern_right = get_setting('foot_bg_pattern_right');
                    $foot_social_radius = get_setting('foot_social_radius', '4px');

                    // Newsletter
                    $foot_news_show = get_setting('foot_news_show', 'on');
                    $foot_news_title = get_setting('foot_news_title', 'Subscribe to our newsletter for regular updates about Offers, Coupons & more', $lang);
                    $foot_news_btn = get_setting('foot_news_btn', 'Subscribe', $lang);
                    $foot_news_highlight_img = get_setting('foot_news_highlight_img');
                    $foot_news_bg = get_setting('foot_news_bg', '#ffffff');
                    $foot_news_border = get_setting('foot_news_border', '#eadfd3');
                    $foot_news_btn_bg = get_setting('foot_news_btn_bg', '#685b4e');
                    $foot_news_btn_tx = get_setting('foot_news_btn_tx', '#ffffff');
                    $foot_news_border_pos = get_setting('foot_news_border_pos', 'top-bottom');
                    $foot_news_border_color = get_setting('foot_news_border_color', 'rgba(104, 91, 78, 0.2)');
                    $foot_news_border_width = get_setting('foot_news_border_width', '1.5px');
                    $foot_news_pad_top = get_setting('foot_news_pad_top', '24px');
                    $foot_news_pad_bot = get_setting('foot_news_pad_bot', '24px');
                    $foot_news_pad_left = get_setting('foot_news_pad_left', '0px');
                    $foot_news_pad_right = get_setting('foot_news_pad_right', '0px');
                    $foot_news_mob_pad_top = get_setting('foot_news_mob_pad_top', '8px');
                    $foot_news_mob_pad_bot = get_setting('foot_news_mob_pad_bot', '8px');
                    $foot_news_mob_pad_left = get_setting('foot_news_mob_pad_left', '0px');
                    $foot_news_mob_pad_right = get_setting('foot_news_mob_pad_right', '0px');

                    // Bottom Copyright Bar
                    $foot_copy_bg = get_setting('foot_copy_bg', '#5f4d3e');
                    $foot_copy_text = get_setting('foot_copy_text', '#ffffff');
                    $frontend_copyright_text = get_setting('frontend_copyright_text', 'Copyright &copy; 2026 Time to Furnish. All Right Reserved.', $lang);
                    $footer_disclaimer_text = get_setting('footer_disclaimer_text', 'We operate as an independent third-party marketplace and are not liable for the accuracy, originality, or legality of any images or content uploaded by sellers. All such materials are the sole responsibility of the seller, including any content copied or reproduced from external platforms. Please read our <a href="/seller-terms-conditions" target="_blank" rel="noopener"><b>Terms and Conditions</b></a>.', $lang);
                    $footer_disclaimer_plain = trim(preg_replace('/\s+/', ' ', strip_tags($footer_disclaimer_text)));
                    $footer_disclaimer_needs_toggle = \Illuminate\Support\Str::length($footer_disclaimer_plain) > 55;
                    $foot_bar_pad_top = get_setting('foot_bar_pad_top', '10px');
                    $foot_bar_pad_bot = get_setting('foot_bar_pad_bot', '10px');
                    $foot_bar_pad_left = get_setting('foot_bar_pad_left', '0px');
                    $foot_bar_pad_right = get_setting('foot_bar_pad_right', '0px');
                    $foot_bar_mob_pad_top = get_setting('foot_bar_mob_pad_top', '10px');
                    $foot_bar_mob_pad_bot = get_setting('foot_bar_mob_pad_bot', '12px');
                    $foot_bar_mob_pad_left = get_setting('foot_bar_mob_pad_left', '0px');
                    $foot_bar_mob_pad_right = get_setting('foot_bar_mob_pad_right', '0px');

                    // Mobile Font Sizes
                    $foot_mob_head_font_size = get_setting('foot_mob_head_font_size', '14px');
                    $foot_mob_body_font_size = get_setting('foot_mob_body_font_size', '13px');

                    $columns = \App\Support\FooterDefaults::columns($lang);
                @endphp

                <!-- Simulated Frontend Footer Container with CSS Variables mapping -->
                <div class="footer-widget ttf-footer-links-section ttf-hotspot" id="hotspot-general" onclick="activateSection('tab-general', this)" style="--foot-bg-color: {{ $foot_bg_color }}; --foot-head-color: {{ $foot_head_color }}; --foot-text-color: {{ $foot_text_color }}; --foot-hover-color: {{ $foot_hover_color }}; --foot-pad-top: {{ $foot_pad_top }}; --foot-pad-bot: {{ $foot_pad_bot }}; --foot-border-color: {{ $foot_border_color }}; --foot-copy-bg: {{ $foot_copy_bg }}; --foot-copy-text: {{ $foot_copy_text }}; --foot-news-bg: {{ $foot_news_bg }}; --foot-news-border: {{ $foot_news_border }}; --foot-news-btn_bg: {{ $foot_news_btn_bg }}; --foot-social-radius: {{ $foot_social_radius }};
                --foot-news-btn-tx: {{ $foot_news_btn_tx }}; --foot-head-font-size: {{ get_setting('foot_head_font_size', '16px') }}; --foot-body-font-size: {{ get_setting('foot_body_font_size', '13px') }}; --foot-body-line-height: {{ get_setting('foot_body_line_height', '1.8') }}; --foot-col-spacing: {{ get_setting('foot_col_spacing', '20px') }}; --foot-head-margin-bottom: {{ get_setting('foot_head_margin_bottom', '18px') }}; @if(!empty($foot_bg_pattern_left)) --foot-bg-pattern-left: url('{{ uploaded_asset($foot_bg_pattern_left) }}'); @else --foot-bg-pattern-left: none; @endif @if(!empty($foot_bg_pattern_right)) --foot-bg-pattern-right: url('{{ uploaded_asset($foot_bg_pattern_right) }}'); @else --foot-bg-pattern-right: none; @endif @if(!empty($foot_news_highlight_img)) --foot-news-highlight-img: url('{{ uploaded_asset($foot_news_highlight_img) }}'); @endif">
                    <span class="ttf-edit-badge"><i class="las la-cog"></i> {{ translate('General Styles') }}</span>

                    <!-- Sim Newsletter Section -->
                    <div id="preview-newsletter-section" class="footer-widget iuytrey footer-newsletter-section ttf-hotspot @if($foot_news_show == 'off') d-none @endif" onclick="activateSection('tab-newsletter', this); event.stopPropagation();">
                        <span class="ttf-edit-badge"><i class="las la-envelope"></i> {{ translate('Newsletter Settings') }}</span>
                        <div class="container py-2">
                            <div class="col-12 text-center">
                                <h5 class="mb-3 textheading" id="preview-news-title">
                                    {!! str_ireplace('newsletter', '<span class="text-highlight">newsletter</span>', $foot_news_title) !!}
                                </h5>
                                <div class="mx-auto" style="max-width: 480px; display: flex; gap: 6px;">
                                    <input type="text" class="form-control email_input_footer" placeholder="{{ translate('Your Email') }}" disabled style="height: 38px;">
                                    <button type="button" class="btn footer_submit_btn" id="preview-news-btn" style="height: 38px; min-width: 100px;">{{ $foot_news_btn }}</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sim Columns Grid -->
                    <div class="container mt-4">
                        <div class="row gutters-20">
                            @for($col = 1; $col <= 8; $col++)
                                @php
                                    $col_status = $columns[$col]['status'];
                                    $col_width = $columns[$col]['width'];
                                    $is_bootstrap = str_starts_with($col_width, 'col-') || str_starts_with($col_width, 'ttf-');
                                    $widgets = $columns[$col]['widgets'];
                                @endphp

                                <div id="preview-col-{{ $col }}" class="ttf-hotspot @if($col_status == 'off') d-none @endif {{ $is_bootstrap ? $col_width : '' }}" style="@if(!$is_bootstrap) width: {{ $col_width }} !important; flex: 0 0 {{ $col_width }} !important; max-width: {{ $col_width }} !important; @endif" onclick="activateSection('tab-col-{{ $col }}', this); event.stopPropagation();">
                                    <span class="ttf-edit-badge"><i class="las la-edit"></i> {{ translate('Column') }} {{ $col }}</span>
                                    <div class="ttf-footer-card">
                                        @foreach($widgets as $w)
                                            @php $wType = $w['type'] ?? 'menu_links'; @endphp
                                            @if($wType == 'menu_links')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Menu' }}</h4>
                                                <ul>
                                                    @php $wLbls = $w['lbls'] ?? []; @endphp
                                                    @foreach($wLbls as $lbl)
                                                        <li><a href="#" onclick="return false;">{{ $lbl }}</a></li>
                                                    @endforeach
                                                </ul>
                                            @elseif($wType == 'important_links')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Important Links' }}</h4>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Return Policy</a></li>
                                                    <li><a href="#" onclick="return false;">Privacy Policy</a></li>
                                                </ul>
                                            @elseif($wType == 'my_account')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'My Account' }}</h4>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Login</a></li>
                                                    <li><a href="#" onclick="return false;">Order History</a></li>
                                                </ul>
                                            @elseif($wType == 'text_html')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Text Widget' }}</h4>
                                                <div style="font-size: 13px; line-height: 1.8;">{!! $w['html'] ?? '' !!}</div>
                                            @elseif($wType == 'seller_zone')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Seller Zone' }}</h4>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Login to Seller Panel</a></li>
                                                </ul>
                                                <div class="sub-widget-title">{{ $w['subheading_2'] ?? translate('Join Our Partner Network') }}</div>
                                                <ul>
                                                    <li><a href="#" onclick="return false;">Register your shop</a></li>
                                                </ul>
                                                @if(!empty($w['subheading_3']))
                                                    <div class="sub-widget-title">{{ $w['subheading_3'] }}</div>
                                                    <ul class="footer-social-list">
                                                        <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                                                        <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                                                        <li><a href="#" onclick="return false;"><i class="lab la-twitter"></i></a></li>
                                                    </ul>
                                                @endif
                                            @elseif($wType == 'social_icons')
                                                <h4 id="preview-col-title-{{ $col }}">{{ $w['title'] ?? 'Follow Us' }}</h4>
                                                <ul class="footer-social-list">
                                                    <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                                                    <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                                                </ul>
                                            @elseif($wType == 'images_widget')
                                                @php
                                                    $show_deliv = ($w['show_deliv'] ?? 'on') == 'on';
                                                    $show_pay = ($w['show_pay'] ?? 'on') == 'on';
                                                    $show_trust = ($w['show_trust'] ?? 'on') == 'on';
                                                @endphp
                                                @if($show_deliv)
                                                    <div class="secure-payment-box mb-3">
                                                        <h5 class="secure-payment-title textheading">{{ $w['title'] ?? translate('Delivery Partners') }}</h5>
                                                        @php
                                                            $deliv_img = !empty($w['deliv_img']) ? uploaded_asset($w['deliv_img']) : (get_setting('foot_img_deliv') ? uploaded_asset(get_setting('foot_img_deliv')) : static_asset('assets/img/delivery_partners_logo.png'));
                                                        @endphp
                                                        <img src="{{ $deliv_img }}" alt="" class="secure-payment-img">
                                                    </div>
                                                @endif
                                                @if($show_pay)
                                                    <div class="secure-payment-box mb-3">
                                                        <h5 class="secure-payment-title textheading">{{ $show_deliv ? translate('Pay Securely With') : ($w['title'] ?? translate('Pay Securely With')) }}</h5>
                                                        @php
                                                            $pay_img = !empty($w['pay_img']) ? uploaded_asset($w['pay_img']) : (get_setting('foot_img_pay') ? uploaded_asset(get_setting('foot_img_pay')) : static_asset('assets/img/securelypayments.png'));
                                                        @endphp
                                                        <img src="{{ $pay_img }}" alt="" class="secure-payment-img">
                                                    </div>
                                                @endif
                                                @if($show_trust)
                                                    <div class="secure-payment-box">
                                                        <h5 class="secure-payment-title textheading">{{ translate('What Trustpilot Say’s') }}</h5>
                                                        @php
                                                            $trust_img = !empty($w['trust_img']) ? uploaded_asset($w['trust_img']) : (get_setting('foot_img_trust') ? uploaded_asset(get_setting('foot_img_trust')) : static_asset('assets/img/trustpilot.png'));
                                                        @endphp
                                                        <img src="{{ $trust_img }}" alt="" class="secure-payment-img trustpilot-img">
                                                    </div>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                </div>

                <!-- Bottom Copyright Disclaimer -->
                <div class="ttf-footer-bottom-bar ttf-hotspot" onclick="activateSection('tab-bottom-bar', this); event.stopPropagation();">
                    <span class="ttf-edit-badge"><i class="las la-copyright"></i> {{ translate('Bottom Bar') }}</span>
                    <div class="container">
                        <div class="row align-items-center">
                            <div class="col-lg-6 text-left">
                                <div class="sim-copyright" id="preview-copyright">{!! $frontend_copyright_text !!}</div>
                            </div>
                            <div class="col-lg-6 text-right">
                                <div class="sim-disclaimer" id="preview-disclaimer">
                                    @if($footer_disclaimer_needs_toggle)
                                        <span class="footer-text-short">We operate as an independent third-party marketplace.</span>
                                        <span class="footer-text-full d-none">{!! $footer_disclaimer_text !!}</span>
                                        <a href="javascript:void(0);" class="footer-read-more-btn ml-1" style="text-decoration: underline; font-weight: bold; color: inherit; cursor: pointer;">Read More</a>
                                    @else
                                        <span class="footer-text-full">{!! $footer_disclaimer_text !!}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Config panel on the right -->
        <div class="ttf-config-pane">
            <div class="ttf-config-card">
                <div class="ttf-config-head">
                    <div class="ttf-config-title" id="ttf-settings-title">{{ translate('Footer Settings') }}</div>
                    <div class="ttf-config-subtitle" id="ttf-settings-subtitle">{{ translate('Select a footer area to manage content, spacing, typography, and mobile behavior.') }}</div>
                </div>

                <div class="config-tabs d-flex">
                    <button type="button" class="config-tab-btn active" onclick="showTab('tab-general', this)">
                        {{ translate('Styles') }}
                    </button>
                    <button type="button" class="config-tab-btn" onclick="showTab('tab-newsletter', this)">
                        {{ translate('Newsletter') }}
                    </button>
                    <button type="button" class="config-tab-btn" onclick="showTab('tab-columns', this)">
                        {{ translate('Columns') }}
                    </button>
                    <button type="button" class="config-tab-btn" onclick="showTab('tab-bottom-bar', this)">
                        {{ translate('Bottom Bar') }}
                    </button>
                </div>

                <!-- Tab Pane: General Styles -->
                <div id="tab-general" class="tab-content-pane active">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('General Styling & Dimensions') }}</h6>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_bg_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_bg_color" id="color-input-foot_bg_color" value="{{ $foot_bg_color }}" oninput="updateLiveStyle('foot_bg_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_bg_color, '#') && strlen($foot_bg_color) == 7 ? $foot_bg_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_bg_color').value = this.value; updateLiveStyle('foot_bg_color', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_bg_img">
                            <input type="hidden" name="foot_bg_img" class="selected-files" value="{{ get_setting('foot_bg_img') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Mobile Background Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_mob_bg_img">
                            <input type="hidden" name="foot_mob_bg_img" class="selected-files" value="{{ get_setting('foot_mob_bg_img') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <!-- Figma Pattern Images Left and Right -->
                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Left Pattern Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_bg_pattern_left">
                            <input type="hidden" name="foot_bg_pattern_left" class="selected-files" value="{{ get_setting('foot_bg_pattern_left') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Background Right Pattern Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_bg_pattern_right">
                            <input type="hidden" name="foot_bg_pattern_right" class="selected-files" value="{{ get_setting('foot_bg_pattern_right') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_top">
                                <input type="text" class="form-control" name="foot_pad_top" value="{{ $foot_pad_top }}" placeholder="45px" oninput="updateLiveStyle('foot_pad_top', this.value)">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_bot">
                                <input type="text" class="form-control" name="foot_pad_bot" value="{{ $foot_pad_bot }}" placeholder="45px" oninput="updateLiveStyle('foot_pad_bot', this.value)">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_left">
                                <input type="text" class="form-control" name="foot_pad_left" value="{{ $foot_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_pad_right">
                                <input type="text" class="form-control" name="foot_pad_right" value="{{ $foot_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_top">
                                <input type="text" class="form-control" name="foot_mob_pad_top" value="{{ $foot_mob_pad_top }}" placeholder="12px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_bot">
                                <input type="text" class="form-control" name="foot_mob_pad_bot" value="{{ $foot_mob_pad_bot }}" placeholder="12px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_left">
                                <input type="text" class="form-control" name="foot_mob_pad_left" value="{{ $foot_mob_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_pad_right">
                                <input type="text" class="form-control" name="foot_mob_pad_right" value="{{ $foot_mob_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <!-- Advanced Typography & Spacing -->
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Header Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_head_font_size">
                                <input type="text" class="form-control" name="foot_head_font_size" value="{{ get_setting('foot_head_font_size', '16px') }}" placeholder="16px" oninput="updateLiveStyle('foot_head_font_size', this.value)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Body Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_body_font_size">
                                <input type="text" class="form-control" name="foot_body_font_size" value="{{ get_setting('foot_body_font_size', '13px') }}" placeholder="13px" oninput="updateLiveStyle('foot_body_font_size', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Header Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_head_font_size">
                                <input type="text" class="form-control" name="foot_mob_head_font_size" value="{{ $foot_mob_head_font_size }}" placeholder="14px">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Mobile Body Font Size') }}</label>
                                <input type="hidden" name="types[]" value="foot_mob_body_font_size">
                                <input type="text" class="form-control" name="foot_mob_body_font_size" value="{{ $foot_mob_body_font_size }}" placeholder="13px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Column Gap') }}</label>
                                <input type="hidden" name="types[]" value="foot_col_spacing">
                                <input type="text" class="form-control" name="foot_col_spacing" value="{{ get_setting('foot_col_spacing', '20px') }}" placeholder="20px" oninput="updateLiveStyle('foot_col_spacing', this.value)">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="form-label">{{ translate('Body Line Height') }}</label>
                                <input type="hidden" name="types[]" value="foot_body_line_height">
                                <input type="text" class="form-control" name="foot_body_line_height" value="{{ get_setting('foot_body_line_height', '1.8') }}" placeholder="1.8" oninput="updateLiveStyle('foot_body_line_height', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Heading Bottom Margin') }}</label>
                        <input type="hidden" name="types[]" value="foot_head_margin_bottom">
                        <input type="text" class="form-control" name="foot_head_margin_bottom" value="{{ get_setting('foot_head_margin_bottom', '18px') }}" placeholder="18px" oninput="updateLiveStyle('foot_head_margin_bottom', this.value)">
                    </div>

                    <!-- Colors Config -->
                    <div class="form-group">
                        <label class="form-label">{{ translate('Border / Divider Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_border_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_border_color" id="color-input-foot_border_color" value="{{ $foot_border_color }}" oninput="updateLiveStyle('foot_border_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_border_color, '#') && strlen($foot_border_color) == 7 ? $foot_border_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_border_color').value = this.value; updateLiveStyle('foot_border_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Heading Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_head_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_head_color" id="color-input-foot_head_color" value="{{ $foot_head_color }}" oninput="updateLiveStyle('foot_head_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_head_color, '#') && strlen($foot_head_color) == 7 ? $foot_head_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_head_color').value = this.value; updateLiveStyle('foot_head_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Body Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_text_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_text_color" id="color-input-foot_text_color" value="{{ $foot_text_color }}" oninput="updateLiveStyle('foot_text_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_text_color, '#') && strlen($foot_text_color) == 7 ? $foot_text_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_text_color').value = this.value; updateLiveStyle('foot_text_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Hover / Highlight Underline Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_hover_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_hover_color" id="color-input-foot_hover_color" value="{{ $foot_hover_color }}" oninput="updateLiveStyle('foot_hover_color', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_hover_color, '#') && strlen($foot_hover_color) == 7 ? $foot_hover_color : '#ffffff' }}" oninput="document.getElementById('color-input-foot_hover_color').value = this.value; updateLiveStyle('foot_hover_color', this.value)">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ translate('Social Icons Radius') }}</label>
                        <input type="hidden" name="types[]" value="foot_social_radius">
                        <input type="text" class="form-control" name="foot_social_radius" value="{{ $foot_social_radius }}" placeholder="4px" oninput="updateLiveStyle('foot_social_radius', this.value)">
                    </div>
                </div>

                <!-- Tab Pane: Newsletter Settings -->
                <div id="tab-newsletter" class="tab-content-pane">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('Newsletter Widget Settings') }}</h6>

                    <div class="form-group row align-items-center">
                        <label class="col-8 form-label font-weight-medium mb-0">{{ translate('Show Newsletter Section?') }}</label>
                        <div class="col-4 text-right">
                            <input type="hidden" name="types[]" value="foot_news_show">
                            <input type="hidden" name="foot_news_show" id="foot_news_show_val" value="{{ $foot_news_show }}">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" onchange="toggleNewsletter(this)" @if($foot_news_show == 'on') checked @endif>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Title') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="foot_news_title">
                        <input type="text" class="form-control" name="foot_news_title" value="{{ $foot_news_title }}" oninput="updateNewsletterTitle(this.value)">
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Button Text') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="foot_news_btn">
                        <input type="text" class="form-control" name="foot_news_btn" value="{{ $foot_news_btn }}" oninput="updateLiveText('preview-news-btn', this.value)">
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-medium">{{ translate('Newsletter Highlight Image') }}</label>
                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                            </div>
                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                            <input type="hidden" name="types[]" value="foot_news_highlight_img">
                            <input type="hidden" name="foot_news_highlight_img" class="selected-files" value="{{ get_setting('foot_news_highlight_img') }}">
                        </div>
                        <div class="file-preview box sm"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Input Box Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_bg">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_bg" id="color-input-foot_news_bg" value="{{ $foot_news_bg }}" oninput="updateLiveStyle('foot_news_bg', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_bg, '#') && strlen($foot_news_bg) == 7 ? $foot_news_bg : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_bg').value = this.value; updateLiveStyle('foot_news_bg', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Input Box Border Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_border" id="color-input-foot_news_border" value="{{ $foot_news_border }}" oninput="updateLiveStyle('foot_news_border', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_border, '#') && strlen($foot_news_border) == 7 ? $foot_news_border : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_border').value = this.value; updateLiveStyle('foot_news_border', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Button Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_btn_bg">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_btn_bg" id="color-input-foot_news_btn_bg" value="{{ $foot_news_btn_bg }}" oninput="updateLiveStyle('foot_news_btn_bg', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_btn_bg, '#') && strlen($foot_news_btn_bg) == 7 ? $foot_news_btn_bg : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_btn_bg').value = this.value; updateLiveStyle('foot_news_btn_bg', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Button Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_btn_tx">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_btn_tx" id="color-input-foot_news_btn_tx" value="{{ $foot_news_btn_tx }}" oninput="updateLiveStyle('foot_news_btn_tx', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_btn_tx, '#') && strlen($foot_news_btn_tx) == 7 ? $foot_news_btn_tx : '#ffffff' }}" oninput="document.getElementById('color-input-foot_news_btn_tx').value = this.value; updateLiveStyle('foot_news_btn_tx', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Section Border Position') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border_pos">
                        <select class="form-control" name="foot_news_border_pos">
                            <option value="none" @if($foot_news_border_pos == 'none') selected @endif>{{ translate('None') }}</option>
                            <option value="top" @if($foot_news_border_pos == 'top') selected @endif>{{ translate('Top Only') }}</option>
                            <option value="bottom" @if($foot_news_border_pos == 'bottom') selected @endif>{{ translate('Bottom Only') }}</option>
                            <option value="top-bottom" @if($foot_news_border_pos == 'top-bottom') selected @endif>{{ translate('Top & Bottom') }}</option>
                            <option value="all" @if($foot_news_border_pos == 'all') selected @endif>{{ translate('All Sides') }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Section Border Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border_color">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_news_border_color" id="color-input-foot_news_border_color" value="{{ $foot_news_border_color }}">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_news_border_color, '#') && strlen($foot_news_border_color) == 7 ? $foot_news_border_color : '#685b4e' }}" oninput="document.getElementById('color-input-foot_news_border_color').value = this.value">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Newsletter Section Border Width') }}</label>
                        <input type="hidden" name="types[]" value="foot_news_border_width">
                        <input type="text" class="form-control" name="foot_news_border_width" value="{{ $foot_news_border_width }}" placeholder="e.g. 1.5px">
                    </div>

                    <h6 class="fw-700 text-dark mb-2 mt-3 border-bottom pb-1 fs-12">{{ translate('Newsletter Padding Settings') }}</h6>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_top">
                                <input type="text" class="form-control" name="foot_news_pad_top" value="{{ $foot_news_pad_top }}" placeholder="24px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_bot">
                                <input type="text" class="form-control" name="foot_news_pad_bot" value="{{ $foot_news_pad_bot }}" placeholder="24px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_left">
                                <input type="text" class="form-control" name="foot_news_pad_left" value="{{ $foot_news_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_pad_right">
                                <input type="text" class="form-control" name="foot_news_pad_right" value="{{ $foot_news_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_top">
                                <input type="text" class="form-control" name="foot_news_mob_pad_top" value="{{ $foot_news_mob_pad_top }}" placeholder="8px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_bot">
                                <input type="text" class="form-control" name="foot_news_mob_pad_bot" value="{{ $foot_news_mob_pad_bot }}" placeholder="8px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_left">
                                <input type="text" class="form-control" name="foot_news_mob_pad_left" value="{{ $foot_news_mob_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_news_mob_pad_right">
                                <input type="text" class="form-control" name="foot_news_mob_pad_right" value="{{ $foot_news_mob_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Pane: Footer Columns (1 to 8) -->
                <div id="tab-columns" class="tab-content-pane">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('Configure Grid Columns') }}</h6>

                    <div class="form-group border rounded-lg p-3 bg-soft-light" style="border-color: #eadfd3 !important; border-radius: 12px;">
                        <label class="form-label font-weight-bold mb-2 text-dark" style="font-size: 13px; letter-spacing: 0.3px;">{{ translate('Grid Layout Templates (Presets)') }}</label>
                        <div class="grid-presets-wrapper d-grid mb-2" style="grid-template-columns: repeat(5, 1fr); gap: 8px;">
                            @for($preset = 1; $preset <= 5; $preset++)
                                @php
                                    $colCount = 4;
                                    $gridPattern = 'repeat(4, 1fr)';
                                    if ($preset == 1) { $colCount = 4; $gridPattern = 'repeat(4, 1fr)'; }
                                    elseif ($preset == 2) { $colCount = 3; $gridPattern = 'repeat(3, 1fr)'; }
                                    elseif ($preset == 3) { $colCount = 2; $gridPattern = 'repeat(2, 1fr)'; }
                                    elseif ($preset == 4) { $colCount = 6; $gridPattern = 'repeat(6, 1fr)'; }
                                    elseif ($preset == 5) { $colCount = 5; $gridPattern = 'repeat(5, 1fr)'; }
                                @endphp
                                <button type="button" class="btn btn-light p-2 d-flex flex-column align-items-center justify-content-between grid-preset-card border" onclick="setFooterGridColumns({{ $preset }})" style="border-radius: 8px; border-color: rgba(135, 106, 75, 0.15) !important; background: #fff; transition: all 0.2s ease; cursor: pointer; min-height: 52px; width: 100%;">
                                    <!-- Visual column bars -->
                                    <div class="w-100 d-grid gap-1 mb-2" style="grid-template-columns: {{ $gridPattern }}; height: 8px; content-visibility: auto;">
                                        @for($i = 0; $i < $colCount; $i++)
                                            <div style="background: #876a4b; opacity: 0.35; border-radius: 2px; height: 100%;"></div>
                                        @endfor
                                    </div>
                                    <span style="font-size: 11px; font-weight: 700; color: #685b4e; line-height: 1;">{{ $colCount }} {{ translate('Cols') }}</span>
                                </button>
                            @endfor
                        </div>
                        <small class="form-text text-muted" style="font-size: 11px; color: #8a7c6e !important;">{{ translate('Select a preset for equal-width columns. You can manually adjust widths below.') }}</small>
                    </div>

                    <!-- Column selector pills -->
                    <div class="form-group border-bottom pb-3 mb-3" style="border-color: #eadfd3 !important;">
                        <label class="form-label font-weight-bold text-dark" style="font-size: 13px; letter-spacing: 0.3px;">{{ translate('Select Column to Edit') }}</label>
                        <div class="d-flex flex-wrap active-col-pills" style="gap: 8px;">
                            @for($c = 1; $c <= 8; $c++)
                                @php
                                    $c_status = $columns[$c]['status'] ?? 'off';
                                @endphp
                                <button type="button" class="btn btn-sm col-pill-btn col-pill-{{ $c }} @if($c == 4) active @endif" id="col-pill-{{ $c }}" onclick="setSelectedColumn({{ $c }}); event.preventDefault(); event.stopPropagation();" style="border-radius: 30px; padding: 6px 14px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border: 1.5px solid; transition: all 0.2s ease;">
                                    <span class="status-indicator-dot" style="width: 6px; height: 6px; border-radius: 50%; background-color: @if($c_status == 'on') #28a745 @else #dc3545 @endif; display: inline-block;"></span>
                                    <span>Col {{ $c }}</span>
                                </button>
                            @endfor
                        </div>
                    </div>

                    <div class="accordion" id="columns-accordion">
                        @for($col = 1; $col <= 8; $col++)
                            @php
                                $col_status = $columns[$col]['status'];
                                $col_width = $columns[$col]['width'];
                                $widgets = $columns[$col]['widgets'];
                            @endphp

                            <div class="card shadow-none border mb-3 card-col-settings @if($col == 4) active @endif @if($col_status == 'off') d-none @endif" id="card-col-settings-{{ $col }}">
                                <div id="collapse-col-{{ $col }}" class="collapse show" data-parent="#columns-accordion">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom" style="border-color: #eadfd3 !important;">
                                            <div>
                                                <h5 class="mb-0 text-dark font-weight-bold" style="color: #685b4e !important;"><i class="las la-columns text-primary mr-1" style="color: #876a4b !important;"></i> {{ translate('Column') }} <span class="active-col-num">{{ $col }}</span></h5>
                                                <small class="text-muted fs-11">{{ translate('Configure widget components and layout.') }}</small>
                                            </div>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-xs btn-light btn-icon text-dark btn-move-col-up" onclick="moveColumnUp(this); event.stopPropagation();" title="{{ translate('Move Left/Up') }}" style="border-color: rgba(135,106,75,0.2) !important;"><i class="las la-arrow-left"></i></button>
                                                <button type="button" class="btn btn-xs btn-light btn-icon text-dark btn-move-col-down" onclick="moveColumnDown(this); event.stopPropagation();" title="{{ translate('Move Right/Down') }}" style="border-color: rgba(135,106,75,0.2) !important;"><i class="las la-arrow-right"></i></button>
                                                <button type="button" class="btn btn-xs btn-light btn-icon text-info btn-copy-col" onclick="copyColumn({{ $col }}, this); event.stopPropagation();" title="{{ translate('Copy Column') }}" style="border-color: rgba(135,106,75,0.2) !important;"><i class="las la-copy"></i></button>
                                                <button type="button" class="btn btn-xs btn-light btn-icon text-danger btn-delete-col" onclick="deleteColumn({{ $col }}, this); event.stopPropagation();" title="{{ translate('Delete Column') }}" style="border-color: rgba(135,106,75,0.2) !important;"><i class="las la-trash"></i></button>
                                            </div>
                                        </div>

                                        <!-- Column Status -->
                                        <div class="form-group row align-items-center mb-3">
                                            <label class="col-8 form-label font-weight-medium mb-0">{{ translate('Show Column?') }}</label>
                                            <div class="col-4 text-right">
                                                <input type="hidden" name="types[]" value="foot_col_{{ $col }}_status">
                                                <input type="hidden" name="foot_col_{{ $col }}_status" id="foot_col_{{ $col }}_status_val" value="{{ $col_status }}">
                                                <label class="aiz-switch aiz-switch-success mb-0">
                                                    <input type="checkbox" onchange="toggleColumn({{ $col }}, this)" @if($col_status == 'on') checked @endif>
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Custom Column Width in % or px (or Bootstrap class) -->
                                        <div class="form-group border-bottom pb-3" style="border-color: #eadfd3 !important;">
                                            <label class="form-label font-weight-bold">{{ translate('Column Width') }}</label>
                                            <input type="hidden" name="types[]" value="foot_col_{{ $col }}_width">
                                            <input type="text" class="form-control" name="foot_col_{{ $col }}_width" value="{{ $col_width }}" placeholder="e.g. 20%, 18%, 250px, col-lg-3" oninput="updateColumnWidth({{ $col }}, this.value)">
                                            <small class="form-text text-muted">{{ translate('Enter percentage (e.g. 20%), pixels (e.g. 250px), or bootstrap class (e.g. col-lg-3)') }}</small>
                                        </div>

                                        <!-- Add widget selector (Hidden but kept for JS compatibility) -->
                                        <div style="display: none !important;">
                                            <select class="form-control form-control-sm mr-2" id="add-widget-select-{{ $col }}">
                                                <option value="menu_links">{{ translate('Custom Menu Links') }}</option>
                                                <option value="important_links">{{ translate('Important Links (Auto Pages)') }}</option>
                                                <option value="my_account">{{ translate('My Account Links') }}</option>
                                                <option value="text_html">{{ translate('Custom Text / HTML') }}</option>
                                                <option value="seller_zone">{{ translate('Seller Zone Composite') }}</option>
                                                <option value="images_widget">{{ translate('Delivery & Secure Payment Logos') }}</option>
                                                <option value="social_icons">{{ translate('Social Follow Icons') }}</option>
                                            </select>
                                            <button type="button" onclick="addWidget({{ $col }})">{{ translate('Add') }}</button>
                                        </div>

                                        <!-- Quick Widget Adder Panel -->
                                        <div class="column-widget-adder-box mt-3 border-top pt-3" style="border-color: #eadfd3 !important;">
                                            <label class="form-label font-weight-bold text-dark mb-2" style="font-size: 12px; opacity: 0.85; display: block;">{{ translate('Quick Add Widget') }}</label>
                                            <div class="d-grid gap-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px;">
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'menu_links')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff;">
                                                    <i class="las la-list"></i> {{ translate('Custom Links') }}
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'important_links')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff;">
                                                    <i class="las la-link"></i> {{ translate('Important Links') }}
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'my_account')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff;">
                                                    <i class="las la-user"></i> {{ translate('My Account') }}
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'text_html')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff;">
                                                    <i class="las la-code"></i> {{ translate('Custom HTML') }}
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'seller_zone')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff;">
                                                    <i class="las la-store"></i> {{ translate('Seller Zone') }}
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'images_widget')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff;">
                                                    <i class="las la-images"></i> {{ translate('Logo Images') }}
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-primary d-flex align-items-center justify-content-center p-2 text-center quick-add-widget-btn" onclick="addDirectWidget({{ $col }}, 'social_icons')" style="border-radius: 6px; font-size: 11px; font-weight: 600; gap: 4px; border-color: rgba(135,106,75,0.25) !important; color: #876a4b; background: #fff; grid-column: span 2;">
                                                    <i class="las la-share-alt"></i> {{ translate('Follow Us') }}
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Repeater List Container for Drag and Drop Widgets -->
                                        <div class="widgets-list mt-3" id="widgets-list-{{ $col }}" data-col="{{ $col }}">
                                            <input type="hidden" name="types[][{{ $lang }}]" value="foot_col_{{ $col }}_widgets">
                                            <input type="hidden" name="types[][{{ $lang }}]" value="foot_col_{{ $col }}_extra_blocks">

                                            @foreach($widgets as $wIndex => $w)
                                                @php
                                                    $wType = $w['type'] ?? 'menu_links';
                                                    $wTitle = $w['title'] ?? '';
                                                @endphp

                                                @if ($wType == 'menu_links')
                                                    <div class="widget-card card mb-3 border" data-type="menu_links" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                                                            <span class="font-weight-bold text-primary"><i class="las la-list"></i> {{ translate('Menu Links') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="menu_links">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Menu Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Menu Title" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="menu-links-container">
                                                                @php
                                                                    $wLbls = $w['lbls'] ?? [];
                                                                    $wLnks = $w['lnks'] ?? [];
                                                                @endphp
                                                                @foreach($wLbls as $lIdx => $lbl)
                                                                    @php $lnk = $wLnks[$lIdx] ?? ''; @endphp
                                                                    <div class="menu-link-row">
                                                                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, {{ $col }})"><i class="las la-times"></i></button>
                                                                        <div class="form-group mb-1">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lbls][]" value="{{ $lbl }}" placeholder="Link Label" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                        <div class="form-group mb-0">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lnks][]" value="{{ $lnk }}" placeholder="Link URL" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, {{ $col }}, {{ $wIndex }})">
                                                                <i class="las la-plus"></i> {{ translate('Add New Link') }}
                                                            </button>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'menu_links'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'important_links')
                                                    <div class="widget-card card mb-3 border" data-type="important_links" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                                                            <span class="font-weight-bold text-primary"><i class="las la-link"></i> {{ translate('Important Links') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="important_links">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Important Links" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="menu-links-container">
                                                                @php
                                                                    $wLbls = $w['lbls'] ?? null;
                                                                    $wLnks = $w['lnks'] ?? null;
                                                                    if ($wLbls === null) {
                                                                        $page_ids_str = $w['page_ids'] ?? '2,3,4,5,6,7,8,10,11';
                                                                        $default_pages = get_pages_footer($page_ids_str);
                                                                        $wLbls = [];
                                                                        $wLnks = [];
                                                                        foreach($default_pages as $p) {
                                                                            $wLbls[] = $p->title;
                                                                            $wLnks[] = url($p->slug);
                                                                        }
                                                                    }
                                                                @endphp
                                                                @foreach($wLbls as $lIdx => $lbl)
                                                                    @php $lnk = $wLnks[$lIdx] ?? ''; @endphp
                                                                    <div class="menu-link-row">
                                                                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, {{ $col }})"><i class="las la-times"></i></button>
                                                                        <div class="form-group mb-1">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lbls][]" value="{{ $lbl }}" placeholder="{{ translate('Link Label') }}" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                        <div class="form-group mb-0">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lnks][]" value="{{ $lnk }}" placeholder="{{ translate('Link URL') }}" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, {{ $col }}, {{ $wIndex }})">
                                                                <i class="las la-plus"></i> {{ translate('Add New Link') }}
                                                            </button>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'important_links'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'my_account')
                                                    <div class="widget-card card mb-3 border" data-type="my_account" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                                                            <span class="font-weight-bold text-primary"><i class="las la-user"></i> {{ translate('My Account Links') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="my_account">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="My Account" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="menu-links-container">
                                                                @php
                                                                    $wLbls = $w['lbls'] ?? null;
                                                                    $wLnks = $w['lnks'] ?? null;
                                                                    if ($wLbls === null) {
                                                                        $wLbls = [
                                                                            'Login',
                                                                            'Order History',
                                                                            'My Wishlist',
                                                                            'Track Order'
                                                                        ];
                                                                        $wLnks = [
                                                                            route('user.login'),
                                                                            route('purchase_history.index'),
                                                                            route('wishlists.index'),
                                                                            route('orders.track')
                                                                        ];
                                                                    }
                                                                @endphp
                                                                @foreach($wLbls as $lIdx => $lbl)
                                                                    @php $lnk = $wLnks[$lIdx] ?? ''; @endphp
                                                                    <div class="menu-link-row">
                                                                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, {{ $col }})"><i class="las la-times"></i></button>
                                                                        <div class="form-group mb-1">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lbls][]" value="{{ $lbl }}" placeholder="{{ translate('Link Label') }}" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                        <div class="form-group mb-0">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][lnks][]" value="{{ $lnk }}" placeholder="{{ translate('Link URL') }}" oninput="updateColumnPreview({{ $col }})">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, {{ $col }}, {{ $wIndex }})">
                                                                <i class="las la-plus"></i> {{ translate('Add New Link') }}
                                                            </button>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'my_account'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'text_html')
                                                    <div class="widget-card card mb-3 border" data-type="text_html" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-success">
                                                            <span class="font-weight-bold text-success"><i class="las la-code"></i> {{ translate('Custom Text / HTML') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="text_html">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Widget Title" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group mb-0">
                                                                <label class="form-label">{{ translate('HTML Content') }}</label>
                                                                <textarea class="form-control" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][html]" rows="5" placeholder="HTML or Text content" oninput="updateColumnPreview({{ $col }})">{{ $w['html'] ?? '' }}</textarea>
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'text_html'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'seller_zone')
                                                    <div class="widget-card card mb-3 border" data-type="seller_zone" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-warning">
                                                            <span class="font-weight-bold text-warning"><i class="las la-store"></i> {{ translate('Seller Zone Composite') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="seller_zone">
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Seller Zone" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="widget-mobile-settings">
                                                                <div class="mobile-settings-title">{{ translate('Section Layout Manager') }}</div>
                                                                <small class="text-muted d-block mb-2">{{ translate('Manage each Seller Zone subsection separately for desktop visibility and mobile placement.') }}</small>
                                                                <div class="footer-layout-list" data-layout-list>
                                                                    <div class="footer-layout-row" data-layout-row draggable="true">
                                                                        <div class="footer-layout-row-top">
                                                                            <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                                                            <div class="footer-layout-meta">
                                                                                <div class="footer-layout-title">{{ translate('Seller Zone') }}</div>
                                                                                <div class="footer-layout-desc">{{ translate('Seller login link and optional seller app link.') }}</div>
                                                                            </div>
                                                                            <span class="footer-layout-order-badge" data-layout-position>#1</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Desktop') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_seller_panel]">
                                                                                    <option value="on" @if(($w['show_seller_panel'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                                    <option value="off" @if(($w['show_seller_panel'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Mobile') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_login_display]">
                                                                                    <option value="toggle" @if(($w['mobile_login_display'] ?? 'toggle') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                                    <option value="section" @if(($w['mobile_login_display'] ?? '') == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                                    <option value="hidden" @if(($w['mobile_login_display'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Order') }}</label>
                                                                                <input type="number" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_login_order]" value="{{ $w['mobile_login_order'] ?? '10' }}" min="0" step="1" data-layout-order>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="footer-layout-row" data-layout-row draggable="true">
                                                                        <div class="footer-layout-row-top">
                                                                            <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                                                            <div class="footer-layout-meta">
                                                                                <div class="footer-layout-title">{{ translate('Become A Seller') }}</div>
                                                                                <div class="footer-layout-desc">{{ translate('Register shop CTA section shown under Seller Zone on desktop and as its own mobile block if needed.') }}</div>
                                                                            </div>
                                                                            <span class="footer-layout-order-badge" data-layout-position>#2</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Desktop') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_become_seller]">
                                                                                    <option value="on" @if(($w['show_become_seller'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                                    <option value="off" @if(($w['show_become_seller'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Mobile') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_register_display]">
                                                                                    <option value="section" @if(($w['mobile_register_display'] ?? 'section') == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                                    <option value="toggle" @if(($w['mobile_register_display'] ?? '') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                                    <option value="hidden" @if(($w['mobile_register_display'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Order') }}</label>
                                                                                <input type="number" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_register_order]" value="{{ $w['mobile_register_order'] ?? '20' }}" min="0" step="1" data-layout-order>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="footer-layout-row" data-layout-row draggable="true">
                                                                        <div class="footer-layout-row-top">
                                                                            <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                                                            <div class="footer-layout-meta">
                                                                                <div class="footer-layout-title">{{ translate('Follow Us') }}</div>
                                                                                <div class="footer-layout-desc">{{ translate('Show social icons as a separate mobile section instead of nesting them under Seller Zone.') }}</div>
                                                                            </div>
                                                                            <span class="footer-layout-order-badge" data-layout-position>#3</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Desktop') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_follow_us]">
                                                                                    <option value="on" @if(($w['show_follow_us'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                                    <option value="off" @if(($w['show_follow_us'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Mobile') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_social_display]">
                                                                                    <option value="section" @if(($w['mobile_social_display'] ?? 'section') == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                                    <option value="toggle" @if(($w['mobile_social_display'] ?? '') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                                    <option value="hidden" @if(($w['mobile_social_display'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Order') }}</label>
                                                                                <input type="number" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_social_order]" value="{{ $w['mobile_social_order'] ?? '30' }}" min="0" step="1" data-layout-order>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group mt-2 mb-0">
                                                                    <label class="form-label fs-10">{{ translate('Seller App Link Inside Seller Zone') }}</label>
                                                                    <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_download_app]">
                                                                        <option value="on" @if(($w['show_download_app'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                        <option value="off" @if(($w['show_download_app'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Seller Login URL') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][seller_url]" value="{{ $w['seller_url'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Seller Login Link Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][seller_login_text]" value="{{ $w['seller_login_text'] ?? 'Login to Seller Panel' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Become Seller URL') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][become_seller_url]" value="{{ $w['become_seller_url'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Register Shop Link Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][become_seller_text]" value="{{ $w['become_seller_text'] ?? 'Register your shop' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Download App Link Text') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][download_seller_app_text]" value="{{ $w['download_seller_app_text'] ?? 'Download Seller App' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Join Network Header') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][subheading_2]" value="{{ $w['subheading_2'] ?? 'Join Our Partner Network' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group mb-3 border-bottom pb-3">
                                                                <label class="form-label font-weight-bold">{{ translate('Follow Us Header') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][subheading_3]" value="{{ $w['subheading_3'] ?? 'Follow Us' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Seller social links -->
                                                            <h6 class="fs-10 font-weight-bold text-dark mb-2">{{ translate('Seller Social Link Overrides') }}</h6>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Facebook Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][facebook_link]" value="{{ $w['facebook_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Twitter (X) Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][twitter_link]" value="{{ $w['twitter_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Instagram Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][instagram_link]" value="{{ $w['instagram_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Youtube Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][youtube_link]" value="{{ $w['youtube_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Pinterest Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pinterest_link]" value="{{ $w['pinterest_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('TikTok Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][tiktok_link]" value="{{ $w['tiktok_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <h6 class="fs-10 font-weight-bold text-dark mb-2">{{ translate('Extra Social Icons (Repeater)') }}</h6>
                                                            <div class="extra-social-list mb-2" data-extra-social-list>
                                                                @foreach(($w['extra_social'] ?? []) as $sIdx => $sItem)
                                                                    <div class="extra-social-row">
                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                            <span class="fs-10 text-muted">{{ translate('Icon') }} #{{ $sIdx + 1 }}</span>
                                                                            <button type="button" class="btn btn-xs btn-danger" onclick="removeExtraSocialRow(this)"><i class="las la-times"></i></button>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm mb-1" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][extra_social][{{ $sIdx }}][icon]" value="{{ $sItem['icon'] ?? '' }}" placeholder="lab la-link">
                                                                        <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][extra_social][{{ $sIdx }}][url]" value="{{ $sItem['url'] ?? '' }}" placeholder="https://...">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraSocialRow(this)">
                                                                <i class="las la-plus"></i> {{ translate('Add Social Icon') }}
                                                            </button>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'seller_zone'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'images_widget')
                                                    <div class="widget-card card mb-3 border" data-type="images_widget" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-info">
                                                            <span class="font-weight-bold text-info"><i class="las la-images"></i> {{ translate('Delivery & Payment Logos') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="images_widget">

                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Delivery Heading') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Delivery Partners" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Payment Heading') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pay_title]" value="{{ $w['pay_title'] ?? 'Pay Securely With' }}" placeholder="Pay Securely With" oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Trustpilot Heading') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trust_title]" value="{{ $w['trust_title'] ?? 'What Trustpilot Say’s' }}" placeholder="What Trustpilot Say’s" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <div class="widget-mobile-settings">
                                                                <div class="mobile-settings-title">{{ translate('Section Layout Manager') }}</div>
                                                                <small class="text-muted d-block mb-2">{{ translate('Manage Delivery, Payment, and Trustpilot as separate mobile sections or toggles.') }}</small>
                                                                <div class="footer-layout-list" data-layout-list>
                                                                    <div class="footer-layout-row" data-layout-row draggable="true">
                                                                        <div class="footer-layout-row-top">
                                                                            <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                                                            <div class="footer-layout-meta">
                                                                                <div class="footer-layout-title">{{ translate('Delivery Partners') }}</div>
                                                                                <div class="footer-layout-desc">{{ translate('Carrier logo group shown in the footer.') }}</div>
                                                                            </div>
                                                                            <span class="footer-layout-order-badge" data-layout-position>#1</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Desktop') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_deliv]" onchange="updateColumnPreview({{ $col }})">
                                                                                    <option value="on" @if(($w['show_deliv'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                                    <option value="off" @if(($w['show_deliv'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Mobile') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][deliv_mobile_display]">
                                                                                    <option value="section" @if(($w['deliv_mobile_display'] ?? ($w['mobile_view'] ?? 'section')) == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                                    <option value="toggle" @if(($w['deliv_mobile_display'] ?? '') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                                    <option value="hidden" @if(($w['deliv_mobile_display'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Order') }}</label>
                                                                                <input type="number" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][deliv_mobile_order]" value="{{ $w['deliv_mobile_order'] ?? '10' }}" min="0" step="1" data-layout-order>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="footer-layout-row" data-layout-row draggable="true">
                                                                        <div class="footer-layout-row-top">
                                                                            <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                                                            <div class="footer-layout-meta">
                                                                                <div class="footer-layout-title">{{ translate('Pay Securely With') }}</div>
                                                                                <div class="footer-layout-desc">{{ translate('Payment logos block rendered as a separate section on mobile if required.') }}</div>
                                                                            </div>
                                                                            <span class="footer-layout-order-badge" data-layout-position>#2</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Desktop') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_pay]" onchange="updateColumnPreview({{ $col }})">
                                                                                    <option value="on" @if(($w['show_pay'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                                    <option value="off" @if(($w['show_pay'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Mobile') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pay_mobile_display]">
                                                                                    <option value="section" @if(($w['pay_mobile_display'] ?? ($w['mobile_view'] ?? 'section')) == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                                    <option value="toggle" @if(($w['pay_mobile_display'] ?? '') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                                    <option value="hidden" @if(($w['pay_mobile_display'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Order') }}</label>
                                                                                <input type="number" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pay_mobile_order]" value="{{ $w['pay_mobile_order'] ?? '20' }}" min="0" step="1" data-layout-order>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="footer-layout-row" data-layout-row draggable="true">
                                                                        <div class="footer-layout-row-top">
                                                                            <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                                                            <div class="footer-layout-meta">
                                                                                <div class="footer-layout-title">{{ translate('Trustpilot') }}</div>
                                                                                <div class="footer-layout-desc">{{ translate('Trustpilot proof block with optional link.') }}</div>
                                                                            </div>
                                                                            <span class="footer-layout-order-badge" data-layout-position>#3</span>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Desktop') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][show_trust]" onchange="updateColumnPreview({{ $col }})">
                                                                                    <option value="on" @if(($w['show_trust'] ?? 'on') == 'on') selected @endif>{{ translate('Show') }}</option>
                                                                                    <option value="off" @if(($w['show_trust'] ?? '') == 'off') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Mobile') }}</label>
                                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trust_mobile_display]">
                                                                                    <option value="section" @if(($w['trust_mobile_display'] ?? ($w['mobile_view'] ?? 'section')) == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                                    <option value="toggle" @if(($w['trust_mobile_display'] ?? '') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                                    <option value="hidden" @if(($w['trust_mobile_display'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4">
                                                                                <label class="form-label fs-10">{{ translate('Order') }}</label>
                                                                                <input type="number" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trust_mobile_order]" value="{{ $w['trust_mobile_order'] ?? '30' }}" min="0" step="1" data-layout-order>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Delivery Image -->
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Delivery Image(s)') }}</label>
                                                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                                    </div>
                                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                    <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][deliv_img]" class="selected-files" value="{{ $w['deliv_img'] ?? '' }}">
                                                                </div>
                                                                <div class="file-preview box sm"></div>
                                                            </div>

                                                            <!-- Payment Image -->
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Pay Securely Image(s)') }}</label>
                                                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                                    </div>
                                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                    <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pay_img]" class="selected-files" value="{{ $w['pay_img'] ?? '' }}">
                                                                </div>
                                                                <div class="file-preview box sm"></div>
                                                            </div>

                                                            <!-- Trustpilot Image -->
                                                            <div class="form-group">
                                                                <label class="form-label">{{ translate('Trustpilot Image(s)') }}</label>
                                                                <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                                    </div>
                                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                                    <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trust_img]" class="selected-files" value="{{ $w['trust_img'] ?? '' }}">
                                                                </div>
                                                                <div class="file-preview box sm"></div>
                                                            </div>

                                                            <div class="form-group mb-2">
                                                                <label class="form-label">{{ translate('Trustpilot Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][trustpilot_lnk]" value="{{ $w['trustpilot_lnk'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'images_widget'])
                                                        </div>
                                                    </div>
                                                @elseif ($wType == 'social_icons')
                                                    <div class="widget-card card mb-3 border" data-type="social_icons" draggable="true">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-purple">
                                                            <span class="font-weight-bold text-purple"><i class="las la-share-alt"></i> {{ translate('Social Follow Icons') }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-3">
                                                            <input type="hidden" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][type]" value="social_icons">
                                                            <div class="form-group mb-3 border-bottom pb-3">
                                                                <label class="form-label">{{ translate('Widget Title') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][title]" value="{{ $wTitle }}" placeholder="Follow Us" oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <div class="widget-mobile-settings">
                                                                <div class="mobile-settings-title">{{ translate('Mobile Settings') }}</div>
                                                                <div class="form-group mb-0">
                                                                    <label class="form-label fs-10">{{ translate('Display As') }}</label>
                                                                    <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][mobile_view]">
                                                                        <option value="section" @if(($w['mobile_view'] ?? 'section') == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                        <option value="toggle" @if(($w['mobile_view'] ?? '') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                        <option value="hidden" @if(($w['mobile_view'] ?? '') == 'hidden') selected @endif>{{ translate('Hide') }}</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <!-- Social URL fields -->
                                                            <h6 class="fs-10 font-weight-bold text-dark mb-2">{{ translate('Social Link Connections') }}</h6>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Facebook Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][facebook_link]" value="{{ $w['facebook_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Twitter (X) Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][twitter_link]" value="{{ $w['twitter_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Instagram Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][instagram_link]" value="{{ $w['instagram_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Youtube Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][youtube_link]" value="{{ $w['youtube_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('Pinterest Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][pinterest_link]" value="{{ $w['pinterest_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="form-label fs-10">{{ translate('TikTok Link') }}</label>
                                                                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][tiktok_link]" value="{{ $w['tiktok_link'] ?? '' }}" placeholder="http://..." oninput="updateColumnPreview({{ $col }})">
                                                            </div>

                                                            <h6 class="fs-10 font-weight-bold text-dark mb-2">{{ translate('Extra Social Icons (Repeater)') }}</h6>
                                                            <div class="extra-social-list mb-2" data-extra-social-list>
                                                                @foreach(($w['extra_social'] ?? []) as $sIdx => $sItem)
                                                                    <div class="extra-social-row">
                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                            <span class="fs-10 text-muted">{{ translate('Icon') }} #{{ $sIdx + 1 }}</span>
                                                                            <button type="button" class="btn btn-xs btn-danger" onclick="removeExtraSocialRow(this)"><i class="las la-times"></i></button>
                                                                        </div>
                                                                        <input type="text" class="form-control form-control-sm mb-1" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][extra_social][{{ $sIdx }}][icon]" value="{{ $sItem['icon'] ?? '' }}" placeholder="lab la-link">
                                                                        <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][extra_social][{{ $sIdx }}][url]" value="{{ $sItem['url'] ?? '' }}" placeholder="https://...">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraSocialRow(this)">
                                                                <i class="las la-plus"></i> {{ translate('Add Social Icon') }}
                                                            </button>

                                                            <!-- Inject Collapsible Style Block -->
                                                            @include('backend.website_settings.footer_widget_styles', ['col' => $col, 'wIndex' => $wIndex, 'w' => $w, 'wType' => 'social_icons'])
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>

                                        <!-- ═══ EXTRA LINK BLOCKS — per column repeater ═══ -->
                                        @php $extra_blocks = $columns[$col]['extra_blocks'] ?? []; @endphp
                                        <div class="border-top mt-3 pt-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="font-weight-bold text-dark fs-12">
                                                    <i class="las la-layer-group text-secondary"></i>
                                                    {{ translate('Extra Link Blocks') }}
                                                </span>
                                                <button type="button" class="btn btn-xs btn-soft-secondary" onclick="addExtraBlock({{ $col }})">
                                                    <i class="las la-plus"></i> {{ translate('Add Block') }}
                                                </button>
                                            </div>
                                            <small class="form-text text-muted d-block mb-2">{{ translate('Add extra custom link sections below all widgets in this column. Each block has a heading and unlimited links. Choose if it shows on desktop, mobile, or both.') }}</small>

                                            <div class="extra-blocks-list" id="extra-blocks-list-{{ $col }}" data-col="{{ $col }}">
                                                @foreach($extra_blocks as $bIdx => $block)
                                                    <div class="extra-block-card card mb-2 border border-secondary">
                                                        <div class="card-header py-2 d-flex justify-content-between align-items-center" style="background:#f5f3f0;">
                                                            <span class="text-secondary font-weight-bold fs-11"><i class="las la-grip-vertical mr-1"></i>{{ translate('Link Block') }} #{{ $bIdx + 1 }}</span>
                                                            <div class="btn-group">
                                                                <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockUp(this)"><i class="las la-arrow-up"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockDown(this)"><i class="las la-arrow-down"></i></button>
                                                                <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeExtraBlock(this, {{ $col }})"><i class="las la-trash"></i></button>
                                                            </div>
                                                        </div>
                                                        <div class="card-body p-2">
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <div class="form-group mb-2">
                                                                        <label class="form-label fs-10 mb-1">{{ translate('Block Heading') }}</label>
                                                                        <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][title]" value="{{ $block['title'] ?? '' }}" placeholder="{{ translate('e.g. Before a Seller') }}">
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-group mb-2">
                                                                        <label class="form-label fs-10 mb-1">{{ translate('Show On') }}</label>
                                                                        <select class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][show_on]">
                                                                            <option value="both" @if(($block['show_on'] ?? 'both') == 'both') selected @endif>{{ translate('Both') }}</option>
                                                                            <option value="desktop" @if(($block['show_on'] ?? '') == 'desktop') selected @endif>{{ translate('Desktop only') }}</option>
                                                                            <option value="mobile" @if(($block['show_on'] ?? '') == 'mobile') selected @endif>{{ translate('Mobile only') }}</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="form-group mb-2">
                                                                        <label class="form-label fs-10 mb-1">{{ translate('Mobile Order') }}</label>
                                                                        <input type="number" class="form-control form-control-sm"
                                                                            name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][mobile_order]"
                                                                            value="{{ $block['mobile_order'] ?? (($bIdx + 1) * 10) }}" min="0" step="1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group mb-2">
                                                                <label class="form-label fs-10 mb-1">{{ translate('Mobile Display') }}</label>
                                                                <select class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][mobile_view]">
                                                                    <option value="toggle" @if(($block['mobile_view'] ?? 'toggle') == 'toggle') selected @endif>{{ translate('Accordion Toggle') }}</option>
                                                                    <option value="section" @if(($block['mobile_view'] ?? '') == 'section') selected @endif>{{ translate('Open Section') }}</option>
                                                                </select>
                                                            </div>
                                                            <div class="extra-block-links-container mb-1">
                                                                @foreach($block['lbls'] ?? [] as $lIdx => $lbl)
                                                                    @php $lnk = $block['lnks'][$lIdx] ?? ''; @endphp
                                                                    <div class="extra-link-row d-flex align-items-start gap-1 mb-1">
                                                                        <button type="button" class="btn btn-xs btn-danger flex-shrink-0 mt-1" onclick="removeExtraLinkRow(this, {{ $col }})"><i class="las la-times"></i></button>
                                                                        <div class="flex-grow-1">
                                                                            <input type="text" class="form-control form-control-sm mb-1" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][lbls][]" value="{{ $lbl }}" placeholder="{{ translate('Link Label') }}">
                                                                            <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_extra_blocks[{{ $bIdx }}][lnks][]" value="{{ $lnk }}" placeholder="{{ translate('Link URL') }}">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraLinkRow(this, {{ $col }})">
                                                                <i class="las la-plus"></i> {{ translate('Add Link') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <!-- ═══ END EXTRA LINK BLOCKS ═══ -->

                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="mt-3 text-right">
                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="addNewColumn()"><i class="las la-plus"></i> {{ translate('Add New Column') }}</button>
                    </div>
                </div>

                <!-- Tab Pane: Bottom Bar Settings -->
                <div id="tab-bottom-bar" class="tab-content-pane">
                    <h6 class="fw-700 text-dark mb-3 border-bottom pb-2">{{ translate('Copyright & Disclaimer Panel') }}</h6>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Bottom Bar Background Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_copy_bg">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_copy_bg" id="color-input-foot_copy_bg" value="{{ $foot_copy_bg }}" oninput="updateLiveStyle('foot_copy_bg', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_copy_bg, '#') && strlen($foot_copy_bg) == 7 ? $foot_copy_bg : '#ffffff' }}" oninput="document.getElementById('color-input-foot_copy_bg').value = this.value; updateLiveStyle('foot_copy_bg', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Bottom Bar Text Color') }}</label>
                        <input type="hidden" name="types[]" value="foot_copy_text">
                        <div class="input-group">
                            <input type="text" class="form-control" name="foot_copy_text" id="color-input-foot_copy_text" value="{{ $foot_copy_text }}" oninput="updateLiveStyle('foot_copy_text', this.value)">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" style="width: 40px; height: 38px; border: 1px solid #ced4da; cursor: pointer;" value="{{ str_starts_with($foot_copy_text, '#') && strlen($foot_copy_text) == 7 ? $foot_copy_text : '#ffffff' }}" oninput="document.getElementById('color-input-foot_copy_text').value = this.value; updateLiveStyle('foot_copy_text', this.value)">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Copyright Text') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="frontend_copyright_text">
                        <textarea class="form-control aiz-text-editor" name="frontend_copyright_text" rows="4" data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]'>{{ $frontend_copyright_text }}</textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">{{ translate('Disclaimer Text') }} ({{ translate('Translatable') }})</label>
                        <input type="hidden" name="types[][{{ $lang }}]" value="footer_disclaimer_text">
                        <textarea class="form-control aiz-text-editor" name="footer_disclaimer_text" rows="5" data-buttons='[["font", ["bold", "underline", "italic"]],["insert", ["link"]],["view", ["undo","redo"]]]'>{{ $footer_disclaimer_text }}</textarea>
                    </div>

                    <h6 class="fw-700 text-dark mb-2 mt-3 border-bottom pb-1 fs-12">{{ translate('Bottom Bar Padding Settings') }}</h6>
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_top">
                                <input type="text" class="form-control" name="foot_bar_pad_top" value="{{ $foot_bar_pad_top }}" placeholder="10px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_bot">
                                <input type="text" class="form-control" name="foot_bar_pad_bot" value="{{ $foot_bar_pad_bot }}" placeholder="10px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_left">
                                <input type="text" class="form-control" name="foot_bar_pad_left" value="{{ $foot_bar_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Padding Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_pad_right">
                                <input type="text" class="form-control" name="foot_bar_pad_right" value="{{ $foot_bar_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Top') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_top">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_top" value="{{ $foot_bar_mob_pad_top }}" placeholder="10px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Bottom') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_bot">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_bot" value="{{ $foot_bar_mob_pad_bot }}" placeholder="12px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Left') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_left">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_left" value="{{ $foot_bar_mob_pad_left }}" placeholder="0px">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label class="form-label fs-10">{{ translate('Mobile Right') }}</label>
                                <input type="hidden" name="types[]" value="foot_bar_mob_pad_right">
                                <input type="text" class="form-control" name="foot_bar_mob_pad_right" value="{{ $foot_bar_mob_pad_right }}" placeholder="0px">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Button footer -->
                <div class="p-3 bg-light border-top text-right" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                    <button type="submit" class="btn btn-primary btn-block shadow-sm font-weight-bold py-2">{{ translate('Save Footer Settings') }}</button>
                </div>

            </div>
        </div>

    </div>
</form>

@section('script')
@php
    $cms_pages = \App\Models\Page::where('id', '!=', 1)->get();
    $cms_pages_json = [];
    foreach($cms_pages as $p) {
        $cms_pages_json[] = [
            'title' => $p->title,
            'url' => url($p->slug)
        ];
    }
@endphp
<script>
    const defaultCmsPages = @json($cms_pages_json);
    const defaultMyAccountLinks = [
        { title: 'Login', url: '{{ route("user.login") }}' },
        { title: 'Order History', url: '{{ route("purchase_history.index") }}' },
        { title: 'My Wishlist', url: '{{ route("wishlists.index") }}' },
        { title: 'Track Order', url: '{{ route("orders.track") }}' }
    ];
    const footerBuilderLang = @json($lang);

    function toggleLeftSidebar() {
        let layout = document.querySelector('.ttf-editor-layout');
        let btn = document.getElementById('btn-toggle-left');
        if (layout.classList.contains('left-collapsed')) {
            layout.classList.remove('left-collapsed');
            if (btn) btn.classList.add('active');
        } else {
            layout.classList.add('left-collapsed');
            if (btn) btn.classList.remove('active');
        }
    }

    function toggleRightSidebar(forceShow = false) {
        let layout = document.querySelector('.ttf-editor-layout');
        let btn = document.getElementById('btn-toggle-right');
        if (forceShow) {
            layout.classList.remove('right-collapsed');
            if (btn) btn.classList.add('active');
        } else {
            if (layout.classList.contains('right-collapsed')) {
                layout.classList.remove('right-collapsed');
                if (btn) btn.classList.add('active');
            } else {
                layout.classList.add('right-collapsed');
                if (btn) btn.classList.remove('active');
            }
        }
    }

    function addExtraCustomLinkRow(btn, col, wIndex) {
        let container = btn.previousElementSibling;
        let tempDiv = document.createElement('div');
        tempDiv.className = 'menu-link-row';
        tempDiv.innerHTML = `
            <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeExtraCustomLinkRow(this, ${col})"><i class="las la-times"></i></button>
            <div class="form-group mb-1">
                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${wIndex}][extra_lbls][]" placeholder="Link Label" oninput="updateColumnPreview(${col})">
            </div>
            <div class="form-group mb-0">
                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${wIndex}][extra_lnks][]" placeholder="Link URL" oninput="updateColumnPreview(${col})">
            </div>`;
        container.appendChild(tempDiv);
    }

    function removeExtraCustomLinkRow(btn, col) {
        btn.closest('.menu-link-row').remove();
        updateColumnPreview(col);
    }

    function columnCards() {
        let container = document.getElementById('columns-accordion');
        if (!container) return [];
        return Array.from(container.children).filter(function(child) {
            return child.classList.contains('card');
        });
    }

    function widgetsTypeInputHtml(col) {
        return '<input type="hidden" name="types[][' + footerBuilderLang + ']" value="foot_col_' + col + '_widgets">';
    }

    function ensureWidgetsTypeInput(container, col) {
        if (!container) return;
        let input = container.querySelector('input[name^="types"]');
        if (!input) {
            container.insertAdjacentHTML('afterbegin', widgetsTypeInputHtml(col));
            return;
        }
        input.value = 'foot_col_' + col + '_widgets';
    }

    function resetWidgetsList(container, col) {
        if (!container) return;
        container.innerHTML = widgetsTypeInputHtml(col);
    }

    function syncLayoutOrders(list) {
        if (!list) return;
        let rows = list.querySelectorAll('[data-layout-row]');
        rows.forEach(function(row, index) {
            let badge = row.querySelector('[data-layout-position]');
            if (badge) {
                badge.textContent = '#' + (index + 1);
            }

            let orderInput = row.querySelector('[data-layout-order]');
            if (orderInput) {
                orderInput.value = String((index + 1) * 10);
            }
        });
    }

    function bindLayoutList(list) {
        if (!list || list.dataset.layoutBound === '1') return;
        list.dataset.layoutBound = '1';

        Array.from(list.querySelectorAll('[data-layout-row]'))
            .sort(function(a, b) {
                let aOrder = parseInt(a.querySelector('[data-layout-order]')?.value || '0', 10);
                let bOrder = parseInt(b.querySelector('[data-layout-order]')?.value || '0', 10);
                return aOrder - bOrder;
            })
            .forEach(function(row) {
                list.appendChild(row);
            });

        let dragRow = null;

        list.addEventListener('dragstart', function(event) {
            let row = event.target.closest('[data-layout-row]');
            if (!row) return;
            dragRow = row;
            row.classList.add('is-dragging');
            event.dataTransfer.effectAllowed = 'move';
        });

        list.addEventListener('dragover', function(event) {
            if (!dragRow) return;
            event.preventDefault();
            let row = event.target.closest('[data-layout-row]');
            if (!row || row === dragRow) return;

            let rect = row.getBoundingClientRect();
            let insertAfter = (event.clientY - rect.top) > (rect.height / 2);
            if (insertAfter) {
                row.parentNode.insertBefore(dragRow, row.nextSibling);
            } else {
                row.parentNode.insertBefore(dragRow, row);
            }
        });

        list.addEventListener('dragend', function() {
            if (dragRow) {
                dragRow.classList.remove('is-dragging');
            }
            dragRow = null;
            syncLayoutOrders(list);
        });

        syncLayoutOrders(list);
    }

    function bindAllLayoutLists(scope) {
        (scope || document).querySelectorAll('[data-layout-list]').forEach(function(list) {
            bindLayoutList(list);
        });
    }

    let activeFooterColumn = 1;
    let activePreviewDevice = 'desktop';

    function visibleFooterColumns() {
        return columnCards().filter(function(card) {
            return !card.classList.contains('d-none');
        });
    }

    function setSelectedColumn(colNum) {
        let visible = visibleFooterColumns();
        if (!visible.length) return;

        let exists = visible.some(function(card, index) {
            return (index + 1) === parseInt(colNum, 10);
        });

        activeFooterColumn = exists ? parseInt(colNum, 10) : 1;

        let label = document.getElementById('ttf-selected-column-label');
        if (label) {
            label.textContent = 'Column ' + activeFooterColumn;
        }

        // Columns inspector active card sync
        document.querySelectorAll('#columns-accordion .card-col-settings').forEach(function(card) {
            card.classList.remove('active');
        });
        let activeCard = document.getElementById('card-col-settings-' + activeFooterColumn);
        if (activeCard) {
            activeCard.classList.add('active');
        }

        // Active column pills sync
        document.querySelectorAll('.col-pill-btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        let activePill = document.getElementById('col-pill-' + activeFooterColumn);
        if (activePill) {
            activePill.classList.add('active');
        }

        renderFooterNavigator();
    }

    function updateSettingsMeta(title, subtitle) {
        let titleEl = document.getElementById('ttf-settings-title');
        let subtitleEl = document.getElementById('ttf-settings-subtitle');
        if (titleEl) {
            titleEl.textContent = title;
        }
        if (subtitleEl) {
            subtitleEl.textContent = subtitle;
        }
    }

    function activateGlobalStyles() {
        toggleRightSidebar(true);
        let hotspot = document.getElementById('hotspot-general');
        if (hotspot) {
            activateSection('tab-general', hotspot);
        } else {
            let btn = document.querySelector('.config-tab-btn[onclick*="tab-general"]');
            showTab('tab-general', btn);
        }
    }

    function setPreviewDevice(device) {
        activePreviewDevice = device;
        let wrapper = document.getElementById('ttf-preview-wrapper');
        if (wrapper) {
            wrapper.classList.remove('ttf-device-desktop', 'ttf-device-tablet', 'ttf-device-mobile');
            wrapper.classList.add('ttf-device-' + device);
        }

        document.querySelectorAll('[data-preview-device]').forEach(function(btn) {
            btn.classList.toggle('active', btn.getAttribute('data-preview-device') === device);
        });
    }

    function renderFooterNavigator() {
        let container = document.getElementById('ttf-footer-navigator');
        if (!container) return;

        let isGeneralActive = document.getElementById('hotspot-general')?.classList.contains('active');
        let isNewsletterActive = document.getElementById('preview-newsletter-section')?.classList.contains('active');
        let bottomBar = document.querySelector('.ttf-footer-bottom-bar.ttf-hotspot');
        let isBottomActive = bottomBar?.classList.contains('active');

        let html = '';
        html += '<button type="button" class="ttf-nav-item' + (isGeneralActive ? ' active' : '') + '" data-nav-target="general"><span><i class="las la-palette"></i></span><span>Global Styles</span><span>Base</span></button>';
        if (document.getElementById('preview-newsletter-section')) {
            html += '<button type="button" class="ttf-nav-item' + (isNewsletterActive ? ' active' : '') + '" data-nav-target="newsletter"><span><i class="las la-envelope"></i></span><span>Newsletter</span><span>Section</span></button>';
        }

        columnCards().forEach(function(card, index) {
            if (card.classList.contains('d-none')) {
                return;
            }

            let colNum = index + 1;
            let widgets = Array.from(card.querySelectorAll('.widget-card'));
            let isActive = activeFooterColumn === colNum;
            html += '<button type="button" class="ttf-nav-item' + (isActive ? ' active' : '') + '" data-nav-target="column" data-col="' + colNum + '"><span><i class="las la-columns"></i></span><span>Column ' + colNum + '</span><span>' + widgets.length + ' widgets</span></button>';

            widgets.forEach(function(widget, widgetIndex) {
                let widgetTitleInput = widget.querySelector('input[name*="[title]"]');
                let widgetType = widget.getAttribute('data-type') || 'widget';
                let widgetTitle = widgetTitleInput && widgetTitleInput.value
                    ? widgetTitleInput.value
                    : widgetType.replace(/_/g, ' ').replace(/\b\w/g, function(chr) { return chr.toUpperCase(); });
                html += '<button type="button" class="ttf-nav-item is-child" data-nav-target="column" data-col="' + colNum + '" data-widget-index="' + widgetIndex + '"><span><i class="las la-cube"></i></span><span>' + widgetTitle + '</span><span>' + (widgetIndex + 1) + '</span></button>';
            });
        });

        html += '<button type="button" class="ttf-nav-item' + (isBottomActive ? ' active' : '') + '" data-nav-target="bottom"><span><i class="las la-copyright"></i></span><span>Bottom Bar</span><span>Footer</span></button>';
        container.innerHTML = html;

        container.querySelectorAll('[data-nav-target]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let target = btn.getAttribute('data-nav-target');
                if (target === 'general') {
                    activateGlobalStyles();
                    return;
                }
                if (target === 'newsletter') {
                    let newsletter = document.getElementById('preview-newsletter-section');
                    if (newsletter) {
                        activateSection('tab-newsletter', newsletter);
                    }
                    return;
                }
                if (target === 'bottom') {
                    if (bottomBar) {
                        activateSection('tab-bottom-bar', bottomBar);
                    }
                    return;
                }

                let colNum = parseInt(btn.getAttribute('data-col') || '1', 10);
                let previewCol = document.getElementById('preview-col-' + colNum);
                if (previewCol) {
                    activateSection('tab-col-' + colNum, previewCol);
                } else {
                    let tabBtn = document.querySelector('.config-tab-btn[onclick*="tab-columns"]');
                    showTab('tab-columns', tabBtn);
                }

                let widgetIndex = btn.getAttribute('data-widget-index');
                if (widgetIndex !== null) {
                    let widgetCard = document.querySelector('#widgets-list-' + colNum + ' .widget-card:nth-of-type(' + (parseInt(widgetIndex, 10) + 1) + ')');
                    if (widgetCard) {
                        widgetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
            });
        });
    }

    function initializeFooterBuilderUi() {
        // Handle read-more toggle inside the live preview
        let previewPane = document.querySelector('.ttf-preview-pane');
        if (previewPane) {
            previewPane.addEventListener('click', function(e) {
                let btn = e.target.closest('.footer-read-more-btn');
                if (btn) {
                    e.preventDefault();
                    e.stopPropagation();
                    let container = btn.closest('#preview-disclaimer');
                    if (container) {
                        let shortText = container.querySelector('.footer-text-short');
                        let fullText = container.querySelector('.footer-text-full');
                        if (shortText && fullText) {
                            if (fullText.classList.contains('d-none')) {
                                fullText.classList.remove('d-none');
                                shortText.classList.add('d-none');
                                btn.textContent = 'Read Less';
                            } else {
                                fullText.classList.add('d-none');
                                shortText.classList.remove('d-none');
                                btn.textContent = 'Read More';
                            }
                        }
                    }
                }
            });
        }

        document.querySelectorAll('[data-ttf-side-tab]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let target = btn.getAttribute('data-ttf-side-tab');
                document.querySelectorAll('[data-ttf-side-tab]').forEach(function(tabBtn) {
                    tabBtn.classList.remove('active');
                });
                document.querySelectorAll('.ttf-side-panel').forEach(function(panel) {
                    panel.classList.remove('active');
                });

                btn.classList.add('active');
                let targetPanel = document.getElementById('ttf-side-panel-' + target);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                }
            });
        });

        document.querySelectorAll('[data-preview-device]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                setPreviewDevice(btn.getAttribute('data-preview-device'));
            });
        });

        document.querySelectorAll('[data-quick-widget]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let type = btn.getAttribute('data-quick-widget');
                let selectedColumn = activeFooterColumn || 1;
                let select = document.getElementById('add-widget-select-' + selectedColumn);
                if (!select) {
                    let firstVisible = visibleFooterColumns()[0];
                    if (!firstVisible) return;
                    selectedColumn = 1;
                    select = document.getElementById('add-widget-select-' + selectedColumn);
                }
                if (!select) return;

                select.value = type;
                addWidget(selectedColumn);

                let previewCol = document.getElementById('preview-col-' + selectedColumn);
                if (previewCol) {
                    activateSection('tab-col-' + selectedColumn, previewCol);
                }
            });
        });

        let widgetSearch = document.getElementById('ttf-widget-search');
        if (widgetSearch) {
            widgetSearch.addEventListener('input', function() {
                let query = widgetSearch.value.toLowerCase().trim();
                document.querySelectorAll('[data-quick-widget]').forEach(function(card) {
                    let matches = card.textContent.toLowerCase().includes(query);
                    card.style.display = matches ? '' : 'none';
                });
            });
        }

        setPreviewDevice(activePreviewDevice);
        setSelectedColumn(1);
        renderFooterNavigator();
        activateGlobalStyles();
    }

    // Tab Panel Switcher
    function showTab(tabId, btn) {
        document.querySelectorAll('.config-tab-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        if (!btn) {
            btn = document.querySelector('.config-tab-btn[onclick*="' + tabId + '"]');
        }
        if (btn) {
            btn.classList.add('active');
        }

        document.querySelectorAll('.tab-content-pane').forEach(function(p) {
            p.classList.remove('active');
        });
        let targetPane = document.getElementById(tabId);
        if (targetPane) {
            targetPane.classList.add('active');
        }

        if (tabId === 'tab-general') {
            updateSettingsMeta('Global Footer Settings', 'Manage overall spacing, typography, colors, and background fields used across the footer.');
        } else if (tabId === 'tab-newsletter') {
            updateSettingsMeta('Newsletter Settings', 'Control newsletter content, colors, borders, and responsive padding.');
        } else if (tabId === 'tab-columns') {
            updateSettingsMeta('Column & Widget Settings', 'Manage each column, reorder widgets, control mobile display, and add repeaters or extra blocks.');
        } else if (tabId === 'tab-bottom-bar') {
            updateSettingsMeta('Bottom Bar Settings', 'Edit copyright text, disclaimer content, and bottom spacing for desktop and mobile.');
        }
    }

    // When clicking a simulated hotspot, switch to the right tab/group
    function activateSection(tabId, el) {
        toggleRightSidebar(true);
        document.querySelectorAll('.ttf-hotspot').forEach(function(h) {
            h.classList.remove('active');
        });
        el.classList.add('active');

        let tabType = tabId.split('-')[1]; // e.g. general, newsletter, col, bottom
        let btn = null;
        if(tabType === 'col') {
            btn = document.querySelector('.config-tab-btn[onclick*="tab-columns"]');
            showTab('tab-columns', btn);

            let colNum = tabId.split('-')[2];
            setSelectedColumn(colNum);
            $('.collapse').collapse('hide');
            $('#collapse-col-' + colNum).collapse('show');

            let targetCard = document.getElementById('card-col-settings-' + colNum);
            if(targetCard) {
                targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        } else {
            btn = document.querySelector('.config-tab-btn[onclick*="' + tabId + '"]');
            if(btn) {
                showTab(tabId, btn);
            }
        }

        renderFooterNavigator();
    }

    // Live update functions using CSS Variables mapping
    function updateLiveStyle(key, val) {
        let root = document.getElementById('hotspot-general');
        if (!root) return;

        if (key === 'foot_bg_color') {
            root.style.setProperty('--foot-bg-color', val);
        } else if (key === 'foot_border_color') {
            root.style.setProperty('--foot-border-color', val);
        } else if (key === 'foot_text_color') {
            root.style.setProperty('--foot-text-color', val);
        } else if (key === 'foot_head_color') {
            root.style.setProperty('--foot-head-color', val);
        } else if (key === 'foot_hover_color') {
            root.style.setProperty('--foot-hover-color', val);
        } else if (key === 'foot_pad_top') {
            root.style.setProperty('--foot-pad-top', val);
        } else if (key === 'foot_pad_bot') {
            root.style.setProperty('--foot-pad-bot', val);
        } else if (key === 'foot_copy_bg') {
            root.style.setProperty('--foot-copy-bg', val);
        } else if (key === 'foot_copy_text') {
            root.style.setProperty('--foot-copy-text', val);
        } else if (key === 'foot_news_bg') {
            root.style.setProperty('--foot-news-bg', val);
        } else if (key === 'foot_news_border') {
            root.style.setProperty('--foot-news-border', val);
        } else if (key === 'foot_news_btn_bg') {
            root.style.setProperty('--foot-news-btn_bg', val);
        } else if (key === 'foot_news_btn_tx') {
            root.style.setProperty('--foot-news-btn-tx', val);
        } else if (key === 'foot_head_font_size') {
            root.style.setProperty('--foot-head-font-size', val);
        } else if (key === 'foot_body_font_size') {
            root.style.setProperty('--foot-body-font-size', val);
        } else if (key === 'foot_col_spacing') {
            root.style.setProperty('--foot-col-spacing', val);
        } else if (key === 'foot_body_line_height') {
            root.style.setProperty('--foot-body-line-height', val);
        } else if (key === 'foot_head_margin_bottom') {
            root.style.setProperty('--foot-head-margin-bottom', val);
        } else if (key === 'foot_social_radius') {
            root.style.setProperty('--foot-social-radius', val);
        }
    }

    // Live update column width preview
    function updateColumnWidth(colNum, val) {
        let el = document.getElementById('preview-col-' + colNum);
        if (!el) return;

        let isBootstrap = val.startsWith('col-') || val.startsWith('ttf-');
        if (isBootstrap) {
            el.style.width = '';
            el.style.flex = '';
            el.style.maxWidth = '';
            el.className = 'ttf-hotspot ' + val;
        } else {
            el.style.setProperty('width', val, 'important');
            el.style.setProperty('flex', '0 0 ' + val, 'important');
            el.style.setProperty('max-width', val, 'important');
        }

        renderFooterNavigator();
    }

    function updateLiveText(targetId, val) {
        let el = document.getElementById(targetId);
        if (el) {
            el.innerText = val;
        }
    }

    function updateNewsletterTitle(val) {
        let el = document.getElementById('preview-news-title');
        if (el) {
            let updatedText = val.replace(/newsletter/gi, '<span class="text-highlight">newsletter</span>');
            el.innerHTML = updatedText;
        }
    }

    // Toggle show hide live previews
    function toggleNewsletter(checkbox) {
        let valEl = document.getElementById('foot_news_show_val');
        let previewEl = document.getElementById('preview-newsletter-section');
        if (checkbox.checked) {
            valEl.value = 'on';
            if(previewEl) previewEl.classList.remove('d-none');
        } else {
            valEl.value = 'off';
            if(previewEl) previewEl.classList.add('d-none');
        }
    }

    function toggleColumn(colNum, checkbox) {
        let valEl = document.getElementById('foot_col_' + colNum + '_status_val');
        let previewEl = document.getElementById('preview-col-' + colNum);
        let settingsCard = document.getElementById('card-col-settings-' + colNum);
        let dot = document.querySelector('#col-pill-' + colNum + ' .status-indicator-dot');

        if (checkbox.checked) {
            valEl.value = 'on';
            if(previewEl) previewEl.classList.remove('d-none');
            if(settingsCard) settingsCard.classList.remove('d-none');
            if(dot) dot.style.backgroundColor = '#28a745';
            setSelectedColumn(colNum);
        } else {
            valEl.value = 'off';
            if(previewEl) previewEl.classList.add('d-none');
            if(settingsCard) settingsCard.classList.add('d-none');
            if(dot) dot.style.backgroundColor = '#dc3545';
            if (activeFooterColumn === colNum) {
                setSelectedColumn(1);
            }
        }

        renderFooterNavigator();
    }

    // Dynamic widget layout generator template
    function getWidgetTemplate(col, index, type, data = {}) {
        let title = data.title || '';
        let html = '';

        // Style variables
        let style_text_align = data.style_text_align || '';
        let style_font_size = data.style_font_size || '';
        let style_line_height = data.style_line_height || '';
        let style_margin_bottom = data.style_margin_bottom || '';
        let style_head_weight = data.style_head_weight || '';
        let style_text_weight = data.style_text_weight || '';
        let style_head_color = data.style_head_color || '';
        let style_text_color = data.style_text_color || '';
        let style_hover_color = data.style_hover_color || '';

        let style_social_radius = data.style_social_radius || '';
        let style_social_bg = data.style_social_bg || '';
        let style_social_color = data.style_social_color || '';
        let style_social_hover_bg = data.style_social_hover_bg || '';
        let style_social_hover_color = data.style_social_hover_color || '';
        let style_social_width = data.style_social_width || '36px';

        let stylesCollapseHtml = `
            <div class="border-top pt-2 mt-2">
                <a href="javascript:void(0);" class="btn btn-xs btn-soft-secondary btn-block mb-2" onclick="$(this).next('.widget-custom-styles-panel').slideToggle();">
                    <i class="las la-cog"></i> Widget Styles & Design Options
                </a>
                <div class="widget-custom-styles-panel" style="display:none; background:#fafafa; border:1px solid #eee; border-radius:6px; padding:10px;">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Text Align</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_text_align]" onchange="updateColumnPreview(${col})">
                                    <option value="" ${style_text_align === '' ? 'selected' : ''}>Default</option>
                                    <option value="left" ${style_text_align === 'left' ? 'selected' : ''}>Left</option>
                                    <option value="center" ${style_text_align === 'center' ? 'selected' : ''}>Center</option>
                                    <option value="right" ${style_text_align === 'right' ? 'selected' : ''}>Right</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Font Size Override</label>
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_font_size]" value="${style_font_size}" placeholder="e.g. 13px" oninput="updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Line Height</label>
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_line_height]" value="${style_line_height}" placeholder="e.g. 1.8" oninput="updateColumnPreview(${col})">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Bottom Margin</label>
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_margin_bottom]" value="${style_margin_bottom}" placeholder="e.g. 15px" oninput="updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Heading Weight</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_head_weight]" onchange="updateColumnPreview(${col})">
                                    <option value="" ${style_head_weight === '' ? 'selected' : ''}>Default</option>
                                    <option value="300" ${style_head_weight === '300' ? 'selected' : ''}>300 (Light)</option>
                                    <option value="400" ${style_head_weight === '400' ? 'selected' : ''}>400 (Normal)</option>
                                    <option value="500" ${style_head_weight === '500' ? 'selected' : ''}>500 (Medium)</option>
                                    <option value="600" ${style_head_weight === '600' ? 'selected' : ''}>600 (Semi Bold)</option>
                                    <option value="700" ${style_head_weight === '700' ? 'selected' : ''}>700 (Bold)</option>
                                    <option value="800" ${style_head_weight === '800' ? 'selected' : ''}>800 (Extra Bold)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10">Text Weight</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_text_weight]" onchange="updateColumnPreview(${col})">
                                    <option value="" ${style_text_weight === '' ? 'selected' : ''}>Default</option>
                                    <option value="300" ${style_text_weight === '300' ? 'selected' : ''}>300 (Light)</option>
                                    <option value="400" ${style_text_weight === '400' ? 'selected' : ''}>400 (Normal)</option>
                                    <option value="500" ${style_text_weight === '500' ? 'selected' : ''}>500 (Medium)</option>
                                    <option value="600" ${style_text_weight === '600' ? 'selected' : ''}>600 (Semi Bold)</option>
                                    <option value="700" ${style_text_weight === '700' ? 'selected' : ''}>700 (Bold)</option>
                                    <option value="800" ${style_text_weight === '800' ? 'selected' : ''}>800 (Extra Bold)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label fs-10">Heading Color Override</label>
                        <div class="input-group input-group-xs">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_head_color]" id="col-style-${col}-${index}-head" value="${style_head_color}" oninput="updateColumnPreview(${col})">
                            <div class="input-group-append">
                                <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_head_color || '#000000'}" oninput="document.getElementById('col-style-${col}-${index}-head').value = this.value; updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label fs-10">Text Color Override</label>
                        <div class="input-group input-group-xs">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_text_color]" id="col-style-${col}-${index}-text" value="${style_text_color}" oninput="updateColumnPreview(${col})">
                            <div class="input-group-append">
                                <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_text_color || '#39322a'}" oninput="document.getElementById('col-style-${col}-${index}-text').value = this.value; updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label class="form-label fs-10">Hover Color Override</label>
                        <div class="input-group input-group-xs">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_hover_color]" id="col-style-${col}-${index}-hover" value="${style_hover_color}" oninput="updateColumnPreview(${col})">
                            <div class="input-group-append">
                                <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_hover_color || '#876a4b'}" oninput="document.getElementById('col-style-${col}-${index}-hover').value = this.value; updateColumnPreview(${col})">
                            </div>
                        </div>
                    </div>

                    ${(type === 'social_icons' || type === 'seller_zone') ? `
                        <h6 class="fs-10 font-weight-bold text-dark mt-3 border-bottom pb-1">Social Follow Styling</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="form-label fs-10">Icon Width/Size</label>
                                    <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_width]" value="${style_social_width}" placeholder="36px" oninput="updateColumnPreview(${col})">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group mb-2">
                                    <label class="form-label fs-10">Border Radius</label>
                                    <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_radius]" value="${style_social_radius}" placeholder="e.g. 50% or 4px" oninput="updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Background</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_bg]" id="col-style-${col}-${index}-sbg" value="${style_social_bg}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_bg || '#685b4e'}" oninput="document.getElementById('col-style-${col}-${index}-sbg').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Color</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_color]" id="col-style-${col}-${index}-scolor" value="${style_social_color}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_color || '#ffffff'}" oninput="document.getElementById('col-style-${col}-${index}-scolor').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Hover Background</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_hover_bg]" id="col-style-${col}-${index}-shbg" value="${style_social_hover_bg}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_hover_bg || '#876a4b'}" oninput="document.getElementById('col-style-${col}-${index}-shbg').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label fs-10">Icon Hover Color</label>
                            <div class="input-group input-group-xs">
                                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][style_social_hover_color]" id="col-style-${col}-${index}-shcolor" value="${style_social_hover_color}" oninput="updateColumnPreview(${col})">
                                <div class="input-group-append">
                                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="${style_social_hover_color || '#ffffff'}" oninput="document.getElementById('col-style-${col}-${index}-shcolor').value = this.value; updateColumnPreview(${col})">
                                </div>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>`;

        if (type === 'menu_links') {
            let lbls = data.lbls || ['Link Label'];
            let lnks = data.lnks || ['#'];
            let linksHtml = '';
            for (let i = 0; i < lbls.length; i++) {
                linksHtml += `
                    <div class="menu-link-row">
                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, ${col})"><i class="las la-times"></i></button>
                        <div class="form-group mb-1">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lbls][]" value="${lbls[i]}" placeholder="Link Label" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lnks][]" value="${lnks[i]}" placeholder="Link URL" oninput="updateColumnPreview(${col})">
                        </div>
                    </div>`;
            }

            html = `
                <div class="widget-card card mb-3 border" data-type="menu_links" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                        <span class="font-weight-bold text-primary"><i class="las la-list"></i> Menu Links</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="menu_links">
                        <div class="form-group">
                            <label class="form-label">Menu Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Quick Links'}" placeholder="Menu Title" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="menu-links-container">
                            ${linksHtml}
                        </div>
                        <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, ${col}, ${index})">
                            <i class="las la-plus"></i> Add New Link
                        </button>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'text_html') {
            let innerText = data.html || '';
            html = `
                <div class="widget-card card mb-3 border" data-type="text_html" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-success">
                        <span class="font-weight-bold text-success"><i class="las la-code"></i> Custom Text / HTML</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="text_html">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Title'}" placeholder="Widget Title" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">HTML / Text Content</label>
                            <textarea class="form-control" name="foot_col_${col}_widgets[${index}][html]" rows="5" placeholder="HTML content" oninput="updateColumnPreview(${col})">${innerText}</textarea>
                        </div>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'important_links') {
            let lbls = data.lbls || null;
            let lnks = data.lnks || null;
            if (!lbls) {
                lbls = defaultCmsPages.map(p => p.title);
                lnks = defaultCmsPages.map(p => p.url);
            }
            let linksHtml = '';
            for (let i = 0; i < lbls.length; i++) {
                linksHtml += `
                    <div class="menu-link-row">
                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, ${col})"><i class="las la-times"></i></button>
                        <div class="form-group mb-1">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lbls][]" value="${lbls[i]}" placeholder="Link Label" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lnks][]" value="${lnks[i]}" placeholder="Link URL" oninput="updateColumnPreview(${col})">
                        </div>
                    </div>`;
            }

            html = `
                <div class="widget-card card mb-3 border" data-type="important_links" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                        <span class="font-weight-bold text-primary"><i class="las la-link"></i> Important Links</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="important_links">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Important Links'}" placeholder="Important Links" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="menu-links-container">
                            ${linksHtml}
                        </div>
                        <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, ${col}, ${index})">
                            <i class="las la-plus"></i> Add New Link
                        </button>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'my_account') {
            let lbls = data.lbls || null;
            let lnks = data.lnks || null;
            if (!lbls) {
                lbls = defaultMyAccountLinks.map(l => l.title);
                lnks = defaultMyAccountLinks.map(l => l.url);
            }
            let linksHtml = '';
            for (let i = 0; i < lbls.length; i++) {
                linksHtml += `
                    <div class="menu-link-row">
                        <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, ${col})"><i class="las la-times"></i></button>
                        <div class="form-group mb-1">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lbls][]" value="${lbls[i]}" placeholder="Link Label" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][lnks][]" value="${lnks[i]}" placeholder="Link URL" oninput="updateColumnPreview(${col})">
                        </div>
                    </div>`;
            }

            html = `
                <div class="widget-card card mb-3 border" data-type="my_account" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-primary">
                        <span class="font-weight-bold text-primary"><i class="las la-user"></i> My Account Links</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="my_account">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'My Account'}" placeholder="My Account" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="menu-links-container">
                            ${linksHtml}
                        </div>
                        <button type="button" class="btn btn-xs btn-soft-secondary btn-block mt-2" onclick="addMenuRowToWidget(this, ${col}, ${index})">
                            <i class="las la-plus"></i> Add New Link
                        </button>
                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'seller_zone') {
            let seller_url = data.seller_url || '';
            let become_seller_url = data.become_seller_url || '';
            let subheading_2 = data.subheading_2 || 'Join Our Partner Network';
            let subheading_3 = data.subheading_3 || '';
            let seller_login_text = data.seller_login_text || 'Login to Seller Panel';
            let become_seller_text = data.become_seller_text || 'Register your shop';
            let download_seller_app_text = data.download_seller_app_text || 'Download Seller App';
            let show_seller_panel = data.show_seller_panel || 'on';
            let show_download_app = data.show_download_app || 'on';
            let show_become_seller = data.show_become_seller || 'on';
            let show_follow_us = data.show_follow_us || 'on';
            let mobile_login_display = data.mobile_login_display || 'toggle';
            let mobile_register_display = data.mobile_register_display || 'section';
            let mobile_social_display = data.mobile_social_display || 'section';
            let mobile_login_order = data.mobile_login_order || '10';
            let mobile_register_order = data.mobile_register_order || '20';
            let mobile_social_order = data.mobile_social_order || '30';
            let seller_extra_social = Array.isArray(data.extra_social) ? data.extra_social : [];
            let sellerExtraSocialHtml = seller_extra_social.map(function(item, socialIdx) {
                return '<div class="extra-social-row">' +
                    '<div class="d-flex justify-content-between align-items-center mb-2">' +
                        '<span class="fs-10 text-muted">Icon #' + (socialIdx + 1) + '</span>' +
                        '<button type="button" class="btn btn-xs btn-danger" onclick="removeExtraSocialRow(this)"><i class="las la-times"></i></button>' +
                    '</div>' +
                    '<input type="text" class="form-control form-control-sm mb-1" name="foot_col_' + col + '_widgets[' + index + '][extra_social][' + socialIdx + '][icon]" value="' + (item.icon || '') + '" placeholder="lab la-link">' +
                    '<input type="text" class="form-control form-control-sm" name="foot_col_' + col + '_widgets[' + index + '][extra_social][' + socialIdx + '][url]" value="' + (item.url || '') + '" placeholder="https://...">' +
                '</div>';
            }).join('');

            html = `
                <div class="widget-card card mb-3 border" data-type="seller_zone" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-warning">
                        <span class="font-weight-bold text-warning"><i class="las la-store"></i> Seller Zone Composite</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="seller_zone">
                        <div class="form-group">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Seller Zone'}" placeholder="Seller Zone" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="widget-mobile-settings">
                            <div class="mobile-settings-title">Section Layout Manager</div>
                            <small class="text-muted d-block mb-2">Manage each Seller Zone subsection separately for desktop visibility and mobile placement.</small>
                            <div class="footer-layout-list" data-layout-list>
                                <div class="footer-layout-row" data-layout-row draggable="true">
                                    <div class="footer-layout-row-top">
                                        <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                        <div class="footer-layout-meta">
                                            <div class="footer-layout-title">Seller Zone</div>
                                            <div class="footer-layout-desc">Seller login link and optional seller app link.</div>
                                        </div>
                                        <span class="footer-layout-order-badge" data-layout-position>#1</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fs-10">Desktop</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_seller_panel]">
                                                <option value="on" ${show_seller_panel === 'on' ? 'selected' : ''}>Show</option>
                                                <option value="off" ${show_seller_panel === 'off' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Mobile</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_login_display]">
                                                <option value="toggle" ${mobile_login_display === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                                <option value="section" ${mobile_login_display === 'section' ? 'selected' : ''}>Open Section</option>
                                                <option value="hidden" ${mobile_login_display === 'hidden' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Order</label>
                                            <input type="number" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_login_order]" value="${mobile_login_order}" min="0" step="1" data-layout-order>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-layout-row" data-layout-row draggable="true">
                                    <div class="footer-layout-row-top">
                                        <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                        <div class="footer-layout-meta">
                                            <div class="footer-layout-title">Become A Seller</div>
                                            <div class="footer-layout-desc">Register shop CTA section shown under Seller Zone on desktop and as its own mobile block if needed.</div>
                                        </div>
                                        <span class="footer-layout-order-badge" data-layout-position>#2</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fs-10">Desktop</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_become_seller]">
                                                <option value="on" ${show_become_seller === 'on' ? 'selected' : ''}>Show</option>
                                                <option value="off" ${show_become_seller === 'off' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Mobile</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_register_display]">
                                                <option value="section" ${mobile_register_display === 'section' ? 'selected' : ''}>Open Section</option>
                                                <option value="toggle" ${mobile_register_display === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                                <option value="hidden" ${mobile_register_display === 'hidden' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Order</label>
                                            <input type="number" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_register_order]" value="${mobile_register_order}" min="0" step="1" data-layout-order>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-layout-row" data-layout-row draggable="true">
                                    <div class="footer-layout-row-top">
                                        <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                        <div class="footer-layout-meta">
                                            <div class="footer-layout-title">Follow Us</div>
                                            <div class="footer-layout-desc">Show social icons as a separate mobile section instead of nesting them under Seller Zone.</div>
                                        </div>
                                        <span class="footer-layout-order-badge" data-layout-position>#3</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fs-10">Desktop</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_follow_us]">
                                                <option value="on" ${show_follow_us === 'on' ? 'selected' : ''}>Show</option>
                                                <option value="off" ${show_follow_us === 'off' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Mobile</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_social_display]">
                                                <option value="section" ${mobile_social_display === 'section' ? 'selected' : ''}>Open Section</option>
                                                <option value="toggle" ${mobile_social_display === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                                <option value="hidden" ${mobile_social_display === 'hidden' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Order</label>
                                            <input type="number" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_social_order]" value="${mobile_social_order}" min="0" step="1" data-layout-order>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-2 mb-0">
                                <label class="form-label fs-10">Seller App Link Inside Seller Zone</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_download_app]">
                                    <option value="on" ${show_download_app === 'on' ? 'selected' : ''}>Show</option>
                                    <option value="off" ${show_download_app === 'off' ? 'selected' : ''}>Hide</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seller Login URL</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][seller_url]" value="${seller_url}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Seller Login Link Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][seller_login_text]" value="${seller_login_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Become Seller URL</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][become_seller_url]" value="${become_seller_url}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Register Shop Link Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][become_seller_text]" value="${become_seller_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Download App Link Text</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][download_seller_app_text]" value="${download_seller_app_text}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Join Network Header</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][subheading_2]" value="${subheading_2}" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-3 border-bottom pb-3">
                            <label class="form-label">Follow Us Header</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][subheading_3]" value="${subheading_3}" oninput="updateColumnPreview(${col})">
                        </div>

                        <!-- Seller Social Links -->
                        <h6 class="fs-10 font-weight-bold text-dark mb-2">Seller Social Link Overrides</h6>
                        <div class="form-group">
                            <label class="form-label fs-10">Facebook Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][facebook_link]" value="${data.facebook_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Twitter (X) Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][twitter_link]" value="${data.twitter_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Instagram Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][instagram_link]" value="${data.instagram_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Youtube Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][youtube_link]" value="${data.youtube_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Pinterest Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pinterest_link]" value="${data.pinterest_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fs-10">TikTok Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][tiktok_link]" value="${data.tiktok_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>

                        <h6 class="fs-10 font-weight-bold text-dark mb-2 mt-3">Extra Social Icons (Repeater)</h6>
                        <div class="extra-social-list mb-2" data-extra-social-list>${sellerExtraSocialHtml}</div>
                        <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraSocialRow(this)">
                            <i class="las la-plus"></i> Add Social Icon
                        </button>

                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'images_widget') {
            let trustpilot_lnk = data.trustpilot_lnk || '#';
            let deliv_img_val = data.deliv_img || '';
            let pay_img_val = data.pay_img || '';
            let trust_img_val = data.trust_img || '';
            let show_deliv = data.show_deliv || 'on';
            let show_pay = data.show_pay || 'on';
            let show_trust = data.show_trust || 'on';
            let pay_title = data.pay_title || 'Pay Securely With';
            let trust_title = data.trust_title || 'What Trustpilot Say’s';
            let image_mobile_view = data.mobile_view || 'section';
            let deliv_mobile_display = data.deliv_mobile_display || image_mobile_view;
            let pay_mobile_display = data.pay_mobile_display || image_mobile_view;
            let trust_mobile_display = data.trust_mobile_display || image_mobile_view;
            let deliv_mobile_order = data.deliv_mobile_order || '10';
            let pay_mobile_order = data.pay_mobile_order || '20';
            let trust_mobile_order = data.trust_mobile_order || '30';

            html = `
                <div class="widget-card card mb-3 border" data-type="images_widget" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-info">
                        <span class="font-weight-bold text-info"><i class="las la-images"></i> Delivery & Payment Logos</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="images_widget">

                        <div class="form-group">
                            <label class="form-label">Delivery Heading</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Delivery Partners'}" placeholder="Delivery Partners" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Payment Heading</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pay_title]" value="${pay_title}" placeholder="Pay Securely With" oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Trustpilot Heading</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][trust_title]" value="${trust_title}" placeholder="What Trustpilot Say’s" oninput="updateColumnPreview(${col})">
                        </div>

                        <div class="widget-mobile-settings">
                            <div class="mobile-settings-title">Section Layout Manager</div>
                            <small class="text-muted d-block mb-2">Manage Delivery, Payment, and Trustpilot as separate mobile sections or toggles.</small>
                            <div class="footer-layout-list" data-layout-list>
                                <div class="footer-layout-row" data-layout-row draggable="true">
                                    <div class="footer-layout-row-top">
                                        <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                        <div class="footer-layout-meta">
                                            <div class="footer-layout-title">Delivery Partners</div>
                                            <div class="footer-layout-desc">Carrier logo group shown in the footer.</div>
                                        </div>
                                        <span class="footer-layout-order-badge" data-layout-position>#1</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fs-10">Desktop</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_deliv]" onchange="updateColumnPreview(${col})">
                                                <option value="on" ${show_deliv === 'on' ? 'selected' : ''}>Show</option>
                                                <option value="off" ${show_deliv === 'off' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Mobile</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][deliv_mobile_display]">
                                                <option value="section" ${deliv_mobile_display === 'section' ? 'selected' : ''}>Open Section</option>
                                                <option value="toggle" ${deliv_mobile_display === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                                <option value="hidden" ${deliv_mobile_display === 'hidden' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Order</label>
                                            <input type="number" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][deliv_mobile_order]" value="${deliv_mobile_order}" min="0" step="1" data-layout-order>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-layout-row" data-layout-row draggable="true">
                                    <div class="footer-layout-row-top">
                                        <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                        <div class="footer-layout-meta">
                                            <div class="footer-layout-title">Pay Securely With</div>
                                            <div class="footer-layout-desc">Payment logos block rendered as a separate section on mobile if required.</div>
                                        </div>
                                        <span class="footer-layout-order-badge" data-layout-position>#2</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fs-10">Desktop</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_pay]" onchange="updateColumnPreview(${col})">
                                                <option value="on" ${show_pay === 'on' ? 'selected' : ''}>Show</option>
                                                <option value="off" ${show_pay === 'off' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Mobile</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pay_mobile_display]">
                                                <option value="section" ${pay_mobile_display === 'section' ? 'selected' : ''}>Open Section</option>
                                                <option value="toggle" ${pay_mobile_display === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                                <option value="hidden" ${pay_mobile_display === 'hidden' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Order</label>
                                            <input type="number" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pay_mobile_order]" value="${pay_mobile_order}" min="0" step="1" data-layout-order>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-layout-row" data-layout-row draggable="true">
                                    <div class="footer-layout-row-top">
                                        <span class="footer-layout-grip"><i class="las la-grip-vertical"></i></span>
                                        <div class="footer-layout-meta">
                                            <div class="footer-layout-title">Trustpilot</div>
                                            <div class="footer-layout-desc">Trustpilot proof block with optional link.</div>
                                        </div>
                                        <span class="footer-layout-order-badge" data-layout-position>#3</span>
                                    </div>
                                    <div class="row">
                                        <div class="col-4">
                                            <label class="form-label fs-10">Desktop</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][show_trust]" onchange="updateColumnPreview(${col})">
                                                <option value="on" ${show_trust === 'on' ? 'selected' : ''}>Show</option>
                                                <option value="off" ${show_trust === 'off' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Mobile</label>
                                            <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][trust_mobile_display]">
                                                <option value="section" ${trust_mobile_display === 'section' ? 'selected' : ''}>Open Section</option>
                                                <option value="toggle" ${trust_mobile_display === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                                <option value="hidden" ${trust_mobile_display === 'hidden' ? 'selected' : ''}>Hide</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label fs-10">Order</label>
                                            <input type="number" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][trust_mobile_order]" value="${trust_mobile_order}" min="0" step="1" data-layout-order>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Image -->
                        <div class="form-group">
                            <label class="form-label">Delivery Image(s)</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="foot_col_${col}_widgets[${index}][deliv_img]" class="selected-files" value="${deliv_img_val}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>

                        <!-- Payment Image -->
                        <div class="form-group">
                            <label class="form-label">Pay Securely Image(s)</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="foot_col_${col}_widgets[${index}][pay_img]" class="selected-files" value="${pay_img_val}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>

                        <!-- Trustpilot Image -->
                        <div class="form-group">
                            <label class="form-label">Trustpilot Image(s)</label>
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="true">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">Browse</div>
                                </div>
                                <div class="form-control file-amount">Choose File</div>
                                <input type="hidden" name="foot_col_${col}_widgets[${index}][trust_img]" class="selected-files" value="${trust_img_val}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label">Trustpilot Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][trustpilot_lnk]" value="${trustpilot_lnk}" oninput="updateColumnPreview(${col})">
                        </div>

                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }
        else if (type === 'social_icons') {
            let social_mobile_view = data.mobile_view || 'section';
            let extra_social = Array.isArray(data.extra_social) ? data.extra_social : [];
            let extraSocialHtml = extra_social.map(function(item, socialIdx) {
                return '<div class="extra-social-row">' +
                    '<div class="d-flex justify-content-between align-items-center mb-2">' +
                        '<span class="fs-10 text-muted">Icon #' + (socialIdx + 1) + '</span>' +
                        '<button type="button" class="btn btn-xs btn-danger" onclick="removeExtraSocialRow(this)"><i class="las la-times"></i></button>' +
                    '</div>' +
                    '<input type="text" class="form-control form-control-sm mb-1" name="foot_col_' + col + '_widgets[' + index + '][extra_social][' + socialIdx + '][icon]" value="' + (item.icon || '') + '" placeholder="lab la-link">' +
                    '<input type="text" class="form-control form-control-sm" name="foot_col_' + col + '_widgets[' + index + '][extra_social][' + socialIdx + '][url]" value="' + (item.url || '') + '" placeholder="https://...">' +
                '</div>';
            }).join('');

            html = `
                <div class="widget-card card mb-3 border" data-type="social_icons" draggable="true">
                    <div class="card-header py-2 d-flex justify-content-between align-items-center bg-soft-purple">
                        <span class="font-weight-bold text-purple"><i class="las la-share-alt"></i> Social Follow Icons</span>
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetUp(this)"><i class="las la-arrow-up"></i></button>
                            <button type="button" class="btn btn-xs btn-link" onclick="moveWidgetDown(this)"><i class="las la-arrow-down"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-info" onclick="copyWidget(this)"><i class="las la-copy"></i></button>
                            <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeWidget(this)"><i class="las la-trash"></i></button>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <input type="hidden" name="foot_col_${col}_widgets[${index}][type]" value="social_icons">
                        <div class="form-group mb-3 border-bottom pb-3">
                            <label class="form-label">Widget Title</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][title]" value="${title || 'Follow Us'}" placeholder="Follow Us" oninput="updateColumnPreview(${col})">
                        </div>

                        <div class="widget-mobile-settings">
                            <div class="mobile-settings-title">Mobile Settings</div>
                            <div class="form-group mb-0">
                                <label class="form-label fs-10">Display As</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][mobile_view]">
                                    <option value="section" ${social_mobile_view === 'section' ? 'selected' : ''}>Open Section</option>
                                    <option value="toggle" ${social_mobile_view === 'toggle' ? 'selected' : ''}>Accordion Toggle</option>
                                    <option value="hidden" ${social_mobile_view === 'hidden' ? 'selected' : ''}>Hide</option>
                                </select>
                            </div>
                        </div>

                        <!-- Social URL fields -->
                        <h6 class="fs-10 font-weight-bold text-dark mb-2">Social Link Connections</h6>
                        <div class="form-group">
                            <label class="form-label fs-10">Facebook Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][facebook_link]" value="${data.facebook_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Twitter (X) Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][twitter_link]" value="${data.twitter_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Instagram Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][instagram_link]" value="${data.instagram_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Youtube Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][youtube_link]" value="${data.youtube_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group">
                            <label class="form-label fs-10">Pinterest Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][pinterest_link]" value="${data.pinterest_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label fs-10">TikTok Link</label>
                            <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${index}][tiktok_link]" value="${data.tiktok_link || ''}" placeholder="http://..." oninput="updateColumnPreview(${col})">
                        </div>

                        <h6 class="fs-10 font-weight-bold text-dark mb-2 mt-3">Extra Social Icons (Repeater)</h6>
                        <div class="extra-social-list mb-2" data-extra-social-list>${extraSocialHtml}</div>
                        <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraSocialRow(this)">
                            <i class="las la-plus"></i> Add Social Icon
                        </button>

                        ${stylesCollapseHtml}
                    </div>
                </div>`;
        }

        return html;
    }

    function addDirectWidget(col, type) {
        let select = document.getElementById('add-widget-select-' + col);
        if (select) {
            select.value = type;
            addWidget(col);
        }
    }

    // Add Widget to repeater
    function addWidget(col) {
        let select = document.getElementById('add-widget-select-' + col);
        let type = select.value;
        if (!type) return;

        let container = document.getElementById('widgets-list-' + col);
        if (!container) return;

        let index = container.querySelectorAll('.widget-card').length;
        let template = getWidgetTemplate(col, index, type, {});

        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = template;
        let newCard = tempDiv.firstElementChild;
        container.appendChild(newCard);

        addDragHandlers(newCard);
        bindWidgetInputListeners(newCard);
        bindAllLayoutLists(newCard);
        refreshWidgetIndices(col);

        AIZ.uploader.previewGenerate();
    }

    // Duplicate widget
    function copyWidget(btn) {
        let card = btn.closest('.widget-card');
        let clone = card.cloneNode(true);
        card.parentNode.insertBefore(clone, card.nextSibling);

        addDragHandlers(clone);
        bindWidgetInputListeners(clone);
        bindAllLayoutLists(clone);

        let col = card.closest('.widgets-list').getAttribute('data-col');
        refreshWidgetIndices(col);

        AIZ.uploader.previewGenerate();
    }

    // Delete Widget
    function removeWidget(btn) {
        let card = btn.closest('.widget-card');
        let container = card.closest('.widgets-list');
        let col = container.getAttribute('data-col');
        card.remove();
        refreshWidgetIndices(col);
    }

    // Reorder Widget Up
    function moveWidgetUp(btn) {
        let card = btn.closest('.widget-card');
        let prev = card.previousElementSibling;
        if (prev && prev.classList.contains('widget-card')) {
            card.parentNode.insertBefore(card, prev);
            let col = card.closest('.widgets-list').getAttribute('data-col');
            refreshWidgetIndices(col);
        }
    }

    // Reorder Widget Down
    function moveWidgetDown(btn) {
        let card = btn.closest('.widget-card');
        let next = card.nextElementSibling;
        if (next && next.classList.contains('widget-card')) {
            card.parentNode.insertBefore(next, card);
            let col = card.closest('.widgets-list').getAttribute('data-col');
            refreshWidgetIndices(col);
        }
    }

    // Add row to menu links widget list
    function addMenuRowToWidget(btn, col, wIndex) {
        let container = btn.previousElementSibling;
        let tempDiv = document.createElement('div');
        tempDiv.className = 'menu-link-row';
        tempDiv.innerHTML = `
            <button type="button" class="btn btn-xs btn-danger btn-remove-row" onclick="removeMenuRow(this, ${col})"><i class="las la-times"></i></button>
            <div class="form-group mb-1">
                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${wIndex}][lbls][]" placeholder="Link Label" oninput="updateColumnPreview(${col})">
            </div>
            <div class="form-group mb-0">
                <input type="text" class="form-control form-control-sm" name="foot_col_${col}_widgets[${wIndex}][lnks][]" placeholder="Link URL" oninput="updateColumnPreview(${col})">
            </div>`;
        container.appendChild(tempDiv);
    }

    function removeMenuRow(btn, col) {
        let container = btn.closest('.menu-links-container');
        btn.closest('.menu-link-row').remove();
        updateColumnPreview(col);
    }

    function refreshExtraSocialIndices(card) {
        if (!card) return;
        let typeInput = card.querySelector('input[name$="[type]"]');
        let list = card.querySelector('[data-extra-social-list]');
        if (!typeInput || !list) return;

        let baseName = typeInput.getAttribute('name').replace(/\[type\]$/, '');
        let rows = list.querySelectorAll('.extra-social-row');
        rows.forEach(function(row, socialIndex) {
            let iconInput = row.querySelector('input[name*="[icon]"]');
            let urlInput = row.querySelector('input[name*="[url]"]');
            if (iconInput) {
                iconInput.setAttribute('name', baseName + '[extra_social][' + socialIndex + '][icon]');
            }
            if (urlInput) {
                urlInput.setAttribute('name', baseName + '[extra_social][' + socialIndex + '][url]');
            }

            let label = row.querySelector('.fs-10.text-muted');
            if (label) {
                label.textContent = 'Icon #' + (socialIndex + 1);
            }
        });
    }

    function addExtraSocialRow(btn) {
        let card = btn.closest('.widget-card');
        if (!card) return;
        let list = card.querySelector('[data-extra-social-list]');
        let typeInput = card.querySelector('input[name$="[type]"]');
        if (!list || !typeInput) return;

        let nextIndex = list.querySelectorAll('.extra-social-row').length;
        let baseName = typeInput.getAttribute('name').replace(/\[type\]$/, '');

        let row = document.createElement('div');
        row.className = 'extra-social-row';
        row.innerHTML = '' +
            '<div class="d-flex justify-content-between align-items-center mb-2">' +
                '<span class="fs-10 text-muted">Icon #' + (nextIndex + 1) + '</span>' +
                '<button type="button" class="btn btn-xs btn-danger" onclick="removeExtraSocialRow(this)"><i class="las la-times"></i></button>' +
            '</div>' +
            '<input type="text" class="form-control form-control-sm mb-1" name="' + baseName + '[extra_social][' + nextIndex + '][icon]" placeholder="lab la-link">' +
            '<input type="text" class="form-control form-control-sm" name="' + baseName + '[extra_social][' + nextIndex + '][url]" placeholder="https://...">';

        list.appendChild(row);
        refreshExtraSocialIndices(card);
    }

    function removeExtraSocialRow(btn) {
        let card = btn.closest('.widget-card');
        let row = btn.closest('.extra-social-row');
        if (row) row.remove();
        refreshExtraSocialIndices(card);
    }

    // Refresh repeater input names with correct order index
    function refreshWidgetIndices(col) {
        let container = document.getElementById('widgets-list-' + col);
        if (!container) return;

        let cards = container.querySelectorAll('.widget-card');
        cards.forEach(function(card, index) {
            card.querySelectorAll('input, select, textarea').forEach(function(input) {
                let name = input.getAttribute('name');
                if (name) {
                    let newName = name.replace(/foot_col_\d+_widgets\[\d+\]/, 'foot_col_' + col + '_widgets[' + index + ']');
                    input.setAttribute('name', newName);
                }
            });

            let typeInput = card.querySelector('input[name$="[type]"]');
            if (typeInput) {
                let baseName = typeInput.getAttribute('name').replace(/\[type\]$/, '');
                let widgetType = typeInput.value || '';
                let orderWrap = card.querySelector('.footer-mobile-order-wrap');
                let orderInput = orderWrap ? orderWrap.querySelector('input') : card.querySelector('input[name$="[mobile_order]"]');

                if (['seller_zone', 'images_widget'].includes(widgetType)) {
                    if (orderWrap) {
                        orderWrap.remove();
                    }
                    orderInput = null;
                } else if (!orderInput) {
                    let body = card.querySelector('.card-body');
                    if (body) {
                        let wrapper = document.createElement('div');
                        wrapper.className = 'form-group mb-2 footer-mobile-order-wrap';
                        wrapper.innerHTML = '<label class="form-label fs-10">Mobile Order</label>' +
                            '<input type="number" class="form-control form-control-sm" min="0" step="1" oninput="updateColumnPreview(' + col + ')">';

                        let anchor = body.firstElementChild;
                        if (anchor) {
                            body.insertBefore(wrapper, anchor);
                        } else {
                            body.appendChild(wrapper);
                        }

                        orderInput = wrapper.querySelector('input');
                    }
                }

                if (orderInput) {
                    orderInput.setAttribute('name', baseName + '[mobile_order]');
                    if (!orderInput.value || isNaN(parseInt(orderInput.value, 10))) {
                        orderInput.value = String((index + 1) * 10);
                    }
                }
            }

            card.querySelectorAll('.menu-link-row').forEach(function(row) {
                row.querySelectorAll('input').forEach(function(lnkInput) {
                    let lnkName = lnkInput.getAttribute('name');
                    if (lnkName) {
                        let newLnkName = lnkName.replace(/foot_col_\d+_widgets\[\d+\]/, 'foot_col_' + col + '_widgets[' + index + ']');
                        lnkInput.setAttribute('name', newLnkName);
                    }
                });
            });

            refreshExtraSocialIndices(card);
        });

        bindAllLayoutLists(container);
        updateColumnPreview(col);
        renderFooterNavigator();
    }

    // Live preview generator updates HTML in real-time
    function updateColumnPreview(col) {
        let previewCol = document.getElementById('preview-col-' + col);
        if (!previewCol) return;

        let cardContainer = previewCol.querySelector('.ttf-footer-card');
        if (!cardContainer) return;

        let widgetsList = document.getElementById('widgets-list-' + col);
        if (!widgetsList) return;

        let cards = widgetsList.querySelectorAll('.widget-card');
        let html = '';

        cards.forEach(function(card) {
            let type = card.getAttribute('data-type');
            let titleInput = card.querySelector('input[name*="[title]"]');
            let title = titleInput ? titleInput.value : '';

            if (type === 'menu_links') {
                html += `<h4>${title || 'Menu'}</h4><ul>`;
                let rows = card.querySelectorAll('.menu-link-row');
                rows.forEach(function(row) {
                    let lblInput = row.querySelector('input[name*="[lbls]"]');
                    let lbl = lblInput ? lblInput.value : '';
                    if (lbl) {
                        html += `<li><a href="#" onclick="return false;">${lbl}</a></li>`;
                    }
                });
                html += `</ul>`;
            }
            else if (type === 'important_links') {
                html += `<h4>${title || 'Important Links'}</h4><ul>`;
                html += `<li><a href="#" onclick="return false;">Return Policy</a></li>`;
                html += `<li><a href="#" onclick="return false;">Privacy Policy</a></li>`;
                let rows = card.querySelectorAll('.menu-link-row');
                rows.forEach(function(row) {
                    let lblInput = row.querySelector('input[name*="[extra_lbls]"]');
                    let lbl = lblInput ? lblInput.value : '';
                    if (lbl) {
                        html += `<li><a href="#" onclick="return false;">${lbl}</a></li>`;
                    }
                });
                html += `</ul>`;
            }
            else if (type === 'my_account') {
                html += `<h4>${title || 'My Account'}</h4><ul>`;
                html += `<li><a href="#" onclick="return false;">Login</a></li>`;
                html += `<li><a href="#" onclick="return false;">Order History</a></li>`;
                let rows = card.querySelectorAll('.menu-link-row');
                rows.forEach(function(row) {
                    let lblInput = row.querySelector('input[name*="[extra_lbls]"]');
                    let lbl = lblInput ? lblInput.value : '';
                    if (lbl) {
                        html += `<li><a href="#" onclick="return false;">${lbl}</a></li>`;
                    }
                });
                html += `</ul>`;
            }
            else if (type === 'text_html') {
                let textVal = card.querySelector('textarea[name*="[html]"]').value || '';
                html += `<h4>${title || 'Text Widget'}</h4><div style="font-size:13px; line-height:1.8;">${textVal}</div>`;
            }
            else if (type === 'seller_zone') {
                let showSellerPanel = (card.querySelector('select[name*="[show_seller_panel]"]')?.value || 'on') === 'on';
                let showBecomeSeller = (card.querySelector('select[name*="[show_become_seller]"]')?.value || 'on') === 'on';
                let showFollowUs = (card.querySelector('select[name*="[show_follow_us]"]')?.value || 'on') === 'on';
                html += `<h4>${title || 'Seller Zone'}</h4>`;
                if (showSellerPanel) {
                    html += `<ul><li><a href="#" onclick="return false;">Login to Seller Panel</a></li></ul>`;
                }
                let sub2Input = card.querySelector('input[name*="[subheading_2]"]');
                let sub2 = sub2Input ? sub2Input.value : 'Join Our Partner Network';
                if (showBecomeSeller) {
                    html += `<div class="sub-widget-title">${sub2}</div>
                        <ul><li><a href="#" onclick="return false;">Register your shop</a></li></ul>`;
                }
                let sub3Input = card.querySelector('input[name*="[subheading_3]"]');
                let sub3 = sub3Input ? sub3Input.value : '';
                if (showFollowUs && sub3) {
                    html += `<div class="sub-widget-title">${sub3}</div>
                        <ul class="footer-social-list">
                            <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                            <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                            <li><a href="#" onclick="return false;"><i class="lab la-twitter"></i></a></li>
                        </ul>`;
                }
            }
            else if (type === 'images_widget') {
                let showDelivInput = card.querySelector('[name*="[show_deliv]"]');
                let showPayInput = card.querySelector('[name*="[show_pay]"]');
                let showTrustInput = card.querySelector('[name*="[show_trust]"]');
                let showDeliv = (showDelivInput ? showDelivInput.value : 'on') === 'on';
                let showPay = (showPayInput ? showPayInput.value : 'on') === 'on';
                let showTrust = (showTrustInput ? showTrustInput.value : 'on') === 'on';
                let payTitle = card.querySelector('input[name*="[pay_title]"]')?.value || 'Pay Securely With';
                let trustTitle = card.querySelector('input[name*="[trust_title]"]')?.value || 'What Trustpilot Say’s';

                if (showDeliv) {
                    html += `
                        <div class="secure-payment-box mb-3">
                            <h5 class="secure-payment-title textheading">${title || 'Delivery Partners'}</h5>
                            <div style="background:#fff; border-radius:4px; height: 35px; width: 140px; border: 1px solid #e4e5eb;"></div>
                        </div>`;
                }
                if (showPay) {
                    html += `
                        <div class="secure-payment-box mb-3">
                            <h5 class="secure-payment-title textheading">${payTitle || (showDeliv ? 'Pay Securely With' : (title || 'Pay Securely With'))}</h5>
                            <div style="background:#fff; border-radius:4px; height: 35px; width: 140px; border: 1px solid #e4e5eb;"></div>
                        </div>`;
                }
                if (showTrust) {
                    html += `
                        <div class="secure-payment-box">
                            <h5 class="secure-payment-title textheading">${trustTitle}</h5>
                            <div style="background:#fff; border-radius:4px; height: 40px; width: 140px; border: 1px solid #e4e5eb;"></div>
                        </div>`;
                }
            }
            else if (type === 'social_icons') {
                html += `
                    <h4>${title || 'Follow Us'}</h4>
                    <ul class="footer-social-list">
                        <li><a href="#" onclick="return false;"><i class="lab la-facebook-f"></i></a></li>
                        <li><a href="#" onclick="return false;"><i class="lab la-instagram"></i></a></li>
                    </ul>`;
            }
        });

        cardContainer.innerHTML = html;
    }

    // HTML5 Drag and Drop Sorting script
    let dragSrcEl = null;

    function handleDragStart(e) {
        dragSrcEl = this;
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', this.innerHTML);
        this.style.opacity = '0.4';
    }

    // Drag-over handler
    function handleDragOver(e) {
        if (e.preventDefault) e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        return false;
    }

    // Drop handler
    function handleDrop(e) {
        if (e.stopPropagation) e.stopPropagation();

        if (dragSrcEl !== this) {
            let rect = this.getBoundingClientRect();
            let next = (e.clientY - rect.top) > (rect.height / 2);
            if (next) {
                this.parentNode.insertBefore(dragSrcEl, this.nextSibling);
            } else {
                this.parentNode.insertBefore(dragSrcEl, this);
            }
            let col = this.closest('.widgets-list').getAttribute('data-col');
            refreshWidgetIndices(col);
        }
        return false;
    }

    // Drag-end handler
    function handleDragEnd(e) {
        this.style.opacity = '1.0';
    }

    function addDragHandlers(card) {
        card.setAttribute('draggable', 'true');
        card.addEventListener('dragstart', handleDragStart, false);
        card.addEventListener('dragover', handleDragOver, false);
        card.addEventListener('drop', handleDrop, false);
        card.addEventListener('dragend', handleDragEnd, false);
    }

    function bindWidgetInputListeners(card) {
        let col = card.closest('.widgets-list').getAttribute('data-col');
        card.querySelectorAll('input, textarea, select').forEach(function(input) {
            input.addEventListener('input', function() {
                updateColumnPreview(col);
            });
            input.addEventListener('change', function() {
                updateColumnPreview(col);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.widget-card').forEach(function(card) {
            addDragHandlers(card);
            bindWidgetInputListeners(card);
        });
        bindAllLayoutLists(document);

        document.querySelectorAll('.widgets-list').forEach(function(list) {
            let col = list.getAttribute('data-col');
            if (col) refreshWidgetIndices(col);
        });

        document.querySelectorAll('.extra-blocks-list').forEach(function(list) {
            let col = list.getAttribute('data-col');
            if (col) refreshExtraBlockIndices(col);
        });

        initializeFooterBuilderUi();
    });

    function refreshColumnIndices() {
        let container = document.getElementById('columns-accordion');
        if (!container) return;

        let cards = columnCards();
        cards.forEach(function(card, index) {
            let colNum = index + 1;

            // Update Card ID
            card.setAttribute('id', 'card-col-settings-' + colNum);

            // Update title text
            let titleText = card.querySelector('.card-col-title-text');
            if (titleText) {
                titleText.innerText = 'Column ' + colNum + ' Widgets';
            }

            // Update collapse target
            let headerDiv = card.querySelector('.c-pointer');
            if (headerDiv) {
                headerDiv.setAttribute('data-target', '#collapse-col-' + colNum);
            }

            let collapseDiv = card.querySelector('.collapse');
            if (collapseDiv) {
                collapseDiv.setAttribute('id', 'collapse-col-' + colNum);
            }

            // Update status switcher checkbox onChange parameter
            let checkbox = card.querySelector('input[type="checkbox"][onchange*="toggleColumn"]');
            if (checkbox) {
                checkbox.setAttribute('onchange', 'toggleColumn(' + colNum + ', this)');
            }

            let statusVal = card.querySelector('input[id*="_status_val"]');
            if (statusVal) {
                statusVal.setAttribute('id', 'foot_col_' + colNum + '_status_val');
            }

            // Update width input oninput parameter
            let widthInput = card.querySelector('input[name*="_width"]');
            if (widthInput) {
                widthInput.setAttribute('oninput', 'updateColumnWidth(' + colNum + ', this.value)');
            }

            // Update widgets list container ID and data-col attribute
            let widgetsList = card.querySelector('.widgets-list');
            if (widgetsList) {
                widgetsList.setAttribute('id', 'widgets-list-' + colNum);
                widgetsList.setAttribute('data-col', colNum);
                ensureWidgetsTypeInput(widgetsList, colNum);
            }

            // Update add-widget-select select ID
            let addSelect = card.querySelector('select[id*="add-widget-select-"]');
            if (addSelect) {
                addSelect.setAttribute('id', 'add-widget-select-' + colNum);
            }

            // Update add button onclick
            let addButton = card.querySelector('button[onclick*="addWidget"]');
            if (addButton) {
                addButton.setAttribute('onclick', 'addWidget(' + colNum + ')');
            }

            // Update copy button onclick
            let copyButton = card.querySelector('.btn-copy-col');
            if (copyButton) {
                copyButton.setAttribute('onclick', 'copyColumn(' + colNum + ', this); event.stopPropagation();');
            }

            // Update inputs and names inside the card
            card.querySelectorAll('input, select, textarea').forEach(function(input) {
                let name = input.getAttribute('name');
                if (name) {
                    let newName = name.replace(/foot_col_\d+/, 'foot_col_' + colNum);
                    input.setAttribute('name', newName);
                }

                if (name && name.startsWith('types') && input.value.match(/^foot_col_\d+_widgets$/)) {
                    input.value = 'foot_col_' + colNum + '_widgets';
                }

                let idAttr = input.getAttribute('id');
                if (idAttr) {
                    let newId = idAttr.replace(/col-style-\d+/, 'col-style-' + colNum);
                    input.setAttribute('id', newId);
                }

                let oninputAttr = input.getAttribute('oninput');
                if (oninputAttr && oninputAttr.includes('col-style-')) {
                    let newOninput = oninputAttr.replace(/col-style-\d+-\d+/g, function(match) {
                        let parts = match.split('-');
                        return 'col-style-' + colNum + '-' + parts[3];
                    });
                    input.setAttribute('oninput', newOninput);
                }
            });

            // Also update sub-rows for menus
            card.querySelectorAll('.menu-link-row').forEach(function(row) {
                let remBtn = row.querySelector('.btn-remove-row');
                if (remBtn) {
                    remBtn.setAttribute('onclick', 'removeMenuRow(this, ' + colNum + ')');
                }
            });

            let addNewLinkBtn = card.querySelector('button[onclick*="addMenuRowToWidget"]');
            if (addNewLinkBtn) {
                let onclickVal = addNewLinkBtn.getAttribute('onclick');
                if (onclickVal) {
                    let newOnclick = onclickVal.replace(/addMenuRowToWidget\(this,\s*\d+/, 'addMenuRowToWidget(this, ' + colNum);
                    addNewLinkBtn.setAttribute('onclick', newOnclick);
                }
            }

            // Refresh extra blocks col references
            refreshExtraBlocksForColumn(colNum);
        });

        // Trigger preview updates
        for (let c = 1; c <= cards.length; c++) {
            updateColumnPreview(c);
            let widthInput = document.querySelector('input[name="foot_col_' + c + '_width"]');
            if (widthInput) {
                updateColumnWidth(c, widthInput.value);
            }
        }

        bindAllLayoutLists(container);
        setSelectedColumn(activeFooterColumn);
        renderFooterNavigator();
    }

    function moveColumnUp(btn) {
        let card = btn.closest('.card');
        let prev = card.previousElementSibling;
        if (prev && prev.classList.contains('card')) {
            card.parentNode.insertBefore(card, prev);
            refreshColumnIndices();
        }
    }

    function moveColumnDown(btn) {
        let card = btn.closest('.card');
        let next = card.nextElementSibling;
        if (next && next.classList.contains('card') && !next.classList.contains('d-none')) {
            card.parentNode.insertBefore(next, card);
            refreshColumnIndices();
        }
    }

    function addNewColumn() {
        let container = document.getElementById('columns-accordion');
        let hiddenCard = columnCards().find(function(card) {
            return card.classList.contains('d-none');
        });
        if (!hiddenCard) {
            alert('Maximum of 8 columns allowed.');
            return;
        }

        let nextCol = columnCards().indexOf(hiddenCard) + 1;
        let widgetsList = hiddenCard.querySelector('.widgets-list');
        resetWidgetsList(widgetsList, nextCol);

        let widthInput = hiddenCard.querySelector('input[name*="_width"]');
        if (widthInput) widthInput.value = '20%';

        // Mark status as on
        let checkbox = hiddenCard.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true;
        let statusVal = hiddenCard.querySelector('input[id*="_status_val"]');
        if (statusVal) statusVal.value = 'on';

        // Remove d-none
        hiddenCard.classList.remove('d-none');

        // Refresh indices
        refreshColumnIndices();

        // Expand the newly added column card
        let collapseDiv = hiddenCard.querySelector('.collapse');
        if (collapseDiv) {
            $(collapseDiv).collapse('show');
        }
    }

    function setFooterGridColumns(count) {
        let cards = columnCards();
        let width = (100 / count).toFixed(4).replace(/\.?0+$/, '') + '%';

        cards.forEach(function(card, index) {
            let colNum = index + 1;
            let isActive = index < count;
            let checkbox = card.querySelector('input[type="checkbox"][onchange*="toggleColumn"]');
            let statusVal = card.querySelector('input[id*="_status_val"]');
            let widthInput = card.querySelector('input[name*="_width"]');

            card.classList.toggle('d-none', !isActive);
            if (checkbox) checkbox.checked = isActive;
            if (statusVal) statusVal.value = isActive ? 'on' : 'off';
            if (widthInput) widthInput.value = width;
            updateColumnWidth(colNum, width);
        });

        refreshColumnIndices();
    }

    function deleteColumn(colNum, btn) {
        let container = document.getElementById('columns-accordion');
        let card = btn.closest('.card');

        // Count how many active columns are left (not having d-none class)
        let activeCards = columnCards().filter(function(columnCard) {
            return !columnCard.classList.contains('d-none');
        });
        if (activeCards.length <= 1) {
            alert('At least one column must be present in the footer.');
            return;
        }

        if (!confirm('Are you sure you want to delete this column and all its widgets?')) {
            return;
        }

        // Mark as status off
        let checkbox = card.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = false;
        let statusVal = card.querySelector('input[id*="_status_val"]');
        if (statusVal) statusVal.value = 'off';

        // Clear widgets
        let widgetsList = card.querySelector('.widgets-list');
        resetWidgetsList(widgetsList, colNum);

        // Clear extra blocks
        let extraBlocksList = card.querySelector('.extra-blocks-list');
        if (extraBlocksList) extraBlocksList.innerHTML = '';

        // Hide and move to the end of the accordion container
        card.classList.add('d-none');
        container.appendChild(card);

        // Refresh indices
        refreshColumnIndices();
    }

    function copyColumn(colNum, btn) {
        let container = document.getElementById('columns-accordion');
        let inactiveCard = columnCards().find(function(card) {
            return card.classList.contains('d-none');
        });
        if (!inactiveCard) {
            alert('Maximum of 8 columns allowed.');
            return;
        }

        let card = btn.closest('.card');

        // Copy widgets contents
        let srcWidgetsList = card.querySelector('.widgets-list');
        let destWidgetsList = inactiveCard.querySelector('.widgets-list');
        if (srcWidgetsList && destWidgetsList) {
            destWidgetsList.innerHTML = srcWidgetsList.innerHTML;
        }

        // Copy extra blocks contents
        let srcExtraList = card.querySelector('.extra-blocks-list');
        let destExtraList = inactiveCard.querySelector('.extra-blocks-list');
        if (srcExtraList && destExtraList) {
            destExtraList.innerHTML = srcExtraList.innerHTML;
        }

        // Copy column width
        let srcWidth = card.querySelector('input[name*="_width"]');
        let destWidth = inactiveCard.querySelector('input[name*="_width"]');
        if (srcWidth && destWidth) {
            destWidth.value = srcWidth.value;
        }

        // Set status on
        let checkbox = inactiveCard.querySelector('input[type="checkbox"]');
        if (checkbox) checkbox.checked = true;
        let statusVal = inactiveCard.querySelector('input[id*="_status_val"]');
        if (statusVal) statusVal.value = 'on';

        // Move inactiveCard next to card, and remove d-none
        card.parentNode.insertBefore(inactiveCard, card.nextSibling);
        inactiveCard.classList.remove('d-none');

        // Refresh indices
        refreshColumnIndices();

        // Re-register drag/drop/input handlers inside the activated card
        inactiveCard.querySelectorAll('.widget-card').forEach(function(wCard) {
            addDragHandlers(wCard);
            bindWidgetInputListeners(wCard);
        });
        bindAllLayoutLists(inactiveCard);

        AIZ.uploader.previewGenerate();
    }

    function translate(text) {
        return text;
    }

    /* ═══════════════════════════════════════════════
       EXTRA LINK BLOCKS — per-column repeater JS
    ═══════════════════════════════════════════════ */

    /**
     * Build the HTML for one extra block card (used when adding a new block dynamically).
     */
    function getExtraBlockTemplate(col, index) {
        return `
            <div class="extra-block-card card mb-2 border border-secondary">
                <div class="card-header py-2 d-flex justify-content-between align-items-center" style="background:#f5f3f0;">
                    <span class="text-secondary font-weight-bold fs-11"><i class="las la-grip-vertical mr-1"></i>Link Block #${index + 1}</span>
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockUp(this)"><i class="las la-arrow-up"></i></button>
                        <button type="button" class="btn btn-xs btn-link text-dark" onclick="moveExtraBlockDown(this)"><i class="las la-arrow-down"></i></button>
                        <button type="button" class="btn btn-xs btn-link text-danger" onclick="removeExtraBlock(this, ${col})"><i class="las la-trash"></i></button>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10 mb-1">Block Heading</label>
                                <input type="text" class="form-control form-control-sm"
                                    name="foot_col_${col}_extra_blocks[${index}][title]"
                                    placeholder="e.g. Before a Seller">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10 mb-1">Show On</label>
                                <select class="form-control form-control-sm" name="foot_col_${col}_extra_blocks[${index}][show_on]">
                                    <option value="both" selected>Both</option>
                                    <option value="desktop">Desktop only</option>
                                    <option value="mobile">Mobile only</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-2">
                                <label class="form-label fs-10 mb-1">Mobile Order</label>
                                <input type="number" class="form-control form-control-sm"
                                    name="foot_col_${col}_extra_blocks[${index}][mobile_order]"
                                    value="${(index + 1) * 10}" min="0" step="1">
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label fs-10 mb-1">Mobile Display</label>
                        <select class="form-control form-control-sm" name="foot_col_${col}_extra_blocks[${index}][mobile_view]">
                            <option value="toggle" selected>Accordion Toggle</option>
                            <option value="section">Open Section</option>
                        </select>
                    </div>
                    <div class="extra-block-links-container mb-1">
                        <div class="extra-link-row d-flex align-items-start gap-1 mb-1">
                            <button type="button" class="btn btn-xs btn-danger flex-shrink-0 mt-1" onclick="removeExtraLinkRow(this, ${col})"><i class="las la-times"></i></button>
                            <div class="flex-grow-1">
                                <input type="text" class="form-control form-control-sm mb-1"
                                    name="foot_col_${col}_extra_blocks[${index}][lbls][]"
                                    placeholder="Link Label">
                                <input type="text" class="form-control form-control-sm"
                                    name="foot_col_${col}_extra_blocks[${index}][lnks][]"
                                    placeholder="Link URL">
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-xs btn-soft-secondary btn-block" onclick="addExtraLinkRow(this, ${col})">
                        <i class="las la-plus"></i> Add Link
                    </button>
                </div>
            </div>`;
    }

    /** Add a new extra block card to a column */
    function addExtraBlock(col) {
        let container = document.getElementById('extra-blocks-list-' + col);
        if (!container) return;
        let index = container.querySelectorAll('.extra-block-card').length;
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = getExtraBlockTemplate(col, index);
        container.appendChild(tempDiv.firstElementChild);
        refreshExtraBlockIndices(col);
    }

    /** Remove an extra block card */
    function removeExtraBlock(btn, col) {
        btn.closest('.extra-block-card').remove();
        refreshExtraBlockIndices(col);
    }

    /** Move an extra block card up */
    function moveExtraBlockUp(btn) {
        let card = btn.closest('.extra-block-card');
        let prev = card.previousElementSibling;
        if (prev && prev.classList.contains('extra-block-card')) {
            card.parentNode.insertBefore(card, prev);
            let col = card.closest('.extra-blocks-list').getAttribute('data-col');
            refreshExtraBlockIndices(col);
        }
    }

    /** Move an extra block card down */
    function moveExtraBlockDown(btn) {
        let card = btn.closest('.extra-block-card');
        let next = card.nextElementSibling;
        if (next && next.classList.contains('extra-block-card')) {
            card.parentNode.insertBefore(next, card);
            let col = card.closest('.extra-blocks-list').getAttribute('data-col');
            refreshExtraBlockIndices(col);
        }
    }

    /** Add a link row inside an extra block */
    function addExtraLinkRow(btn, col) {
        let container = btn.previousElementSibling; // .extra-block-links-container
        let card = btn.closest('.extra-block-card');
        // Figure out block index from the card's title input name
        let titleInput = card.querySelector('input[name*="_extra_blocks"]');
        let blockIdx = 0;
        if (titleInput) {
            let m = titleInput.getAttribute('name').match(/extra_blocks\[(\d+)\]/);
            if (m) blockIdx = parseInt(m[1]);
        }
        let tempDiv = document.createElement('div');
        tempDiv.className = 'extra-link-row d-flex align-items-start gap-1 mb-1';
        tempDiv.innerHTML = `
            <button type="button" class="btn btn-xs btn-danger flex-shrink-0 mt-1" onclick="removeExtraLinkRow(this, ${col})"><i class="las la-times"></i></button>
            <div class="flex-grow-1">
                <input type="text" class="form-control form-control-sm mb-1"
                    name="foot_col_${col}_extra_blocks[${blockIdx}][lbls][]"
                    placeholder="Link Label">
                <input type="text" class="form-control form-control-sm"
                    name="foot_col_${col}_extra_blocks[${blockIdx}][lnks][]"
                    placeholder="Link URL">
            </div>`;
        container.appendChild(tempDiv);
    }

    /** Remove a link row inside an extra block */
    function removeExtraLinkRow(btn, col) {
        btn.closest('.extra-link-row').remove();
    }

    /** Re-index all extra block input names after add/remove/reorder */
    function refreshExtraBlockIndices(col) {
        let container = document.getElementById('extra-blocks-list-' + col);
        if (!container) return;
        let cards = container.querySelectorAll('.extra-block-card');
        cards.forEach(function(card, bIdx) {
            // Update header label
            let label = card.querySelector('.card-header span');
            if (label) label.innerHTML = '<i class="las la-grip-vertical mr-1"></i>Link Block #' + (bIdx + 1);

            // Update all input/select names
            card.querySelectorAll('input, select').forEach(function(input) {
                let name = input.getAttribute('name');
                if (name) {
                    let newName = name.replace(
                        /foot_col_\d+_extra_blocks\[\d+\]/,
                        'foot_col_' + col + '_extra_blocks[' + bIdx + ']'
                    );
                    input.setAttribute('name', newName);
                }
            });

            let orderInput = card.querySelector('input[name$="[mobile_order]"]');
            if (orderInput && (!orderInput.value || isNaN(parseInt(orderInput.value, 10)))) {
                orderInput.value = String((bIdx + 1) * 10);
            }

            // Update removeExtraBlock onclick col param
            let removeBtn = card.querySelector('button[onclick*="removeExtraBlock"]');
            if (removeBtn) removeBtn.setAttribute('onclick', 'removeExtraBlock(this, ' + col + ')');

            // Update addExtraLinkRow onclick col param
            let addLinkBtn = card.querySelector('button[onclick*="addExtraLinkRow"]');
            if (addLinkBtn) addLinkBtn.setAttribute('onclick', 'addExtraLinkRow(this, ' + col + ')');

            // Update removeExtraLinkRow onclick col param
            card.querySelectorAll('button[onclick*="removeExtraLinkRow"]').forEach(function(btn) {
                btn.setAttribute('onclick', 'removeExtraLinkRow(this, ' + col + ')');
            });
        });
    }

    /** Called by refreshColumnIndices to re-attach extra-blocks-list col number */
    function refreshExtraBlocksForColumn(colNum) {
        let container = document.getElementById('extra-blocks-list-' + colNum);
        if (!container) return;
        container.setAttribute('data-col', colNum);

        // Update all names in the container
        container.querySelectorAll('input, select').forEach(function(input) {
            let name = input.getAttribute('name');
            if (name) {
                let newName = name.replace(/foot_col_\d+_extra_blocks/, 'foot_col_' + colNum + '_extra_blocks');
                input.setAttribute('name', newName);
            }
        });

        // Update button onclick params
        container.querySelectorAll('button[onclick*="ExtraBlock"], button[onclick*="ExtraLinkRow"]').forEach(function(btn) {
            let onclick = btn.getAttribute('onclick');
            if (onclick) {
                let newOnclick = onclick.replace(/,\s*\d+\)/, ', ' + colNum + ')');
                btn.setAttribute('onclick', newOnclick);
            }
        });

        // Update add-block button (it's outside the list, in the parent section)
        let addBlockBtn = container.closest('.border-top').querySelector('button[onclick*="addExtraBlock"]');
        if (addBlockBtn) addBlockBtn.setAttribute('onclick', 'addExtraBlock(' + colNum + ')');

        // Also inject the extra_blocks types hidden input
        let widgetsList = document.getElementById('widgets-list-' + colNum);
        if (widgetsList) {
            let existing = widgetsList.querySelector('input[value*="_extra_blocks"]');
            if (!existing) {
                let inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'types[][' + (footerBuilderLang || '') + ']';
                inp.value = 'foot_col_' + colNum + '_extra_blocks';
                widgetsList.insertAdjacentElement('afterbegin', inp);
            } else {
                existing.value = 'foot_col_' + colNum + '_extra_blocks';
            }
        }
    }
</script>
@endsection

@endsection
