{{-- Admin-only colour theme for the shared seller product form (the seller panel keeps its own look) --}}
<style>
    .aiz-main-content #choice_form {
        --seller-primary: #685b4e;
        --seller-primary-soft: rgba(104, 91, 78, 0.1);
        --seller-bg: #faf8f5;
        --seller-surface: #ffffff;
        --seller-section: #faf8f5;
        --seller-border: #e8e5e1;
        --seller-border-strong: #dcd4c9;
        --seller-text: #39322a;
        --seller-muted: #8c7e70;
        --ttf-form-accent: #685b4e;
        font-family: inherit;
    }

    /* 6px corners on the form's cards and controls */
    .aiz-main-content #choice_form .card,
    .aiz-main-content #choice_form .addon-block,
    .aiz-main-content #choice_form .productvariation,
    .aiz-main-content #choice_form .attributes-list-wrapper,
    .aiz-main-content #choice_form .premium-info-alert,
    .aiz-main-content #choice_form .form-control,
    .aiz-main-content #choice_form .btn,
    .aiz-main-content #choice_form .bootstrap-select > .dropdown-toggle,
    .aiz-main-content .bootstrap-select .dropdown-menu {
        border-radius: 6px !important;
    }

    /* Gold / tan accents -> sidebar brown */
    .aiz-main-content #choice_form .addon-block:hover,
    .aiz-main-content #choice_form .addon-option-row:hover { border-color: #dcd4c9 !important; box-shadow: 0 6px 18px rgba(104, 91, 78, .08) !important; }
    .aiz-main-content #choice_form .addon-block > .card-header { background: #faf8f5 !important; }
    .aiz-main-content #choice_form .form-control:focus,
    .aiz-main-content #choice_form .addon-option-row .form-control:focus,
    .aiz-main-content #choice_form .addon-block .group-name:focus,
    .aiz-main-content #choice_form .productvariation .bootstrap-select.show .dropdown-toggle,
    .aiz-main-content #choice_form .productvariation .bootstrap-select .dropdown-toggle:focus {
        border-color: #685b4e !important;
        box-shadow: 0 0 0 3px rgba(104, 91, 78, .12) !important;
    }

    .aiz-main-content .btn-solid-beige,
    .aiz-main-content .premium-icon-btn:hover {
        background: #685b4e !important;
        border-color: #685b4e !important;
        color: #fff !important;
    }
    .aiz-main-content .btn-solid-beige:hover { background: #4f453b !important; border-color: #4f453b !important; }
    .aiz-main-content .btn-outline-beige {
        background: #f3efe9 !important;
        border-color: #dcd4c9 !important;
        color: #685b4e !important;
    }
    .aiz-main-content .btn-outline-beige:hover { background: #685b4e !important; border-color: #685b4e !important; color: #fff !important; }

    .aiz-main-content .seller-variation-header { background: #faf8f5 !important; }
    .aiz-main-content .seller-variation-header-icon,
    .aiz-main-content .seller-selected-values-title,
    .aiz-main-content .seller-selected-value-row .premium-badge,
    .aiz-main-content #choice_form .productvariation .premium-info-alert .alert-icon-wrap i { color: #685b4e !important; }
    .aiz-main-content #choice_form .productvariation .premium-info-alert { border-left-color: #685b4e !important; }
    .aiz-main-content #choice_form .productvariation .premium-info-alert .alert-icon-wrap { background: #f3efe9 !important; }
    .aiz-main-content .attribute-badge.badge-custom { background: #f3efe9 !important; color: #685b4e !important; border-color: #dcd4c9 !important; }
    .aiz-main-content .premium-switch input:checked + .premium-slider { background-color: #2e9e5b; }
    .aiz-main-content .productvariation .bootstrap-select .dropdown-menu li a:hover,
    .aiz-main-content .productvariation .bootstrap-select .dropdown-menu li.active a { background: #faf8f5 !important; color: #685b4e !important; }
    .aiz-main-content .productvariation .bootstrap-select .dropdown-menu li.selected a { background: #f3efe9 !important; color: #39322a !important; }
    .aiz-main-content .productvariation .bootstrap-select .dropdown-menu li.selected a::after { color: #685b4e; }

    /* Links and small accents inside the form */
    .aiz-main-content #choice_form a:not(.btn) { color: #685b4e; }
    .aiz-main-content .bootstrap-select .injected-add-custom-option { border-color: #dcd4c9 !important; color: #685b4e !important; background: #faf8f5 !important; }
</style>
