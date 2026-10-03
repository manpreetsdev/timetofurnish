<script>
    function account_delete_confirm_modal(delete_url)
    {
        jQuery('#account_delete_confirm').modal('show', {backdrop: 'static'});
        document.getElementById('account_delete_link').setAttribute('href' , delete_url);
    }
</script>

<style>
    /* Desktop Modal & Mobile Bottom Drawer Styling using #5f4d3e and White */
    #account_delete_confirm .modal-content {
        border-radius: 10px;
        border: 1px solid #e8dfd1;
        box-shadow: 0 10px 30px rgba(95, 77, 62, 0.15);
        background: #ffffff;
        overflow: hidden;
    }

    #account_delete_confirm .account-delete-header {
        background: #faf6f0;
        padding: 20px 24px 14px;
        border-bottom: 1px solid #e8dfd1;
        position: relative;
    }

    #account_delete_confirm .avatar-wrapper {
        position: relative;
        display: inline-block;
    }

    #account_delete_confirm .avatar-wrapper img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #5f4d3e;
        box-shadow: 0 4px 12px rgba(95, 77, 62, 0.2);
    }

    #account_delete_confirm .avatar-warning-badge {
        position: absolute;
        bottom: -2px;
        right: -2px;
        width: 26px;
        height: 26px;
        background: #5f4d3e;
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        border: 2px solid #ffffff;
    }

    #account_delete_confirm .modal-title {
        color: #5f4d3e;
        font-size: 1.3rem;
        font-weight: 700;
        margin-top: 10px;
    }

    #account_delete_confirm .modal-subtitle {
        color: #5f4d3e;
        font-weight: 600;
        font-size: 13.5px;
        margin-bottom: 0;
        opacity: 0.9;
    }

    #account_delete_confirm .consequence-card {
        background: #faf6f0;
        border: 1px solid #e8dfd1;
        border-left: 4px solid #5f4d3e;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 10px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    #account_delete_confirm .consequence-icon {
        color: #5f4d3e;
        font-size: 20px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    #account_delete_confirm .consequence-text {
        color: #5f4d3e;
        font-size: 13.5px;
        font-weight: 500;
        line-height: 1.45;
        margin: 0;
    }

    #account_delete_confirm .notice-alert {
        background: #ffffff;
        border: 1px solid #e8dfd1;
        border-radius: 10px;
        padding: 10px 14px;
        color: #5f4d3e;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    #account_delete_confirm .modal-footer {
        border-top: 1px solid #e8dfd1;
        padding: 16px 24px;
        background: #faf6f0;
        display: flex;
        gap: 12px;
    }

    #account_delete_confirm .btn-cancel {
        background: #ffffff;
        border: 1.5px solid #5f4d3e;
        color: #5f4d3e;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 14px;
        transition: all 0.2s ease;
        flex: 1;
        text-align: center;
    }

    #account_delete_confirm .btn-cancel:hover {
        background: #5f4d3e;
        color: #ffffff;
    }

    #account_delete_confirm .btn-delete-confirm {
        background: #5f4d3e;
        border: 1.5px solid #5f4d3e;
        color: #ffffff !important;
        font-weight: 600;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 14px;
        transition: all 0.2s ease;
        flex: 1;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    #account_delete_confirm .btn-delete-confirm:hover {
        background: #473a2e;
        border-color: #473a2e;
        color: #ffffff !important;
    }

    /* Mobile Bottom Drawer Overlay & Slide animation */
    @media (max-width: 767.98px) {
        #account_delete_confirm.modal {
            padding-right: 0 !important;
        }

        #account_delete_confirm .modal-dialog {
            position: fixed !important;
            bottom: 0 !important;
            left: 0 !important;
            right: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            transform: translateY(100%);
            transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        #account_delete_confirm.show .modal-dialog {
            transform: translateY(0) !important;
        }

        #account_delete_confirm .modal-content {
            border-radius: 16px 16px 0 0 !important;
            border: none !important;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 -10px 30px rgba(95, 77, 62, 0.25) !important;
        }

        #account_delete_confirm .mobile-drawer-handle {
            width: 44px;
            height: 5px;
            background: #5f4d3e;
            opacity: 0.3;
            border-radius: 10px;
            margin: 10px auto 4px auto;
        }

        #account_delete_confirm .account-delete-header {
            padding: 10px 20px 14px;
        }

        #account_delete_confirm .modal-body {
            padding: 16px 20px !important;
        }

        #account_delete_confirm .modal-footer {
            padding: 14px 20px 24px !important;
            position: sticky;
            bottom: 0;
            z-index: 10;
        }
    }
</style>

<div class="modal fade" id="account_delete_confirm" tabindex="-1" role="dialog" aria-labelledby="account_delete_confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="mobile-drawer-handle d-md-none"></div>

            <div class="account-delete-header text-center">
                <div class="d-flex justify-content-center">
                    <span class="avatar-wrapper">
                        @if (Auth::check() && Auth::user()->avatar_original != null)
                            <img src="{{ uploaded_asset(Auth::user()->avatar_original) }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';" alt="{{ translate('avatar') }}">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';" alt="{{ translate('avatar') }}">
                        @endif
                        <span class="avatar-warning-badge">
                            <i class="las la-exclamation-triangle"></i>
                        </span>
                    </span>
                </div>
                <h4 class="modal-title" id="account_delete_confirmModalLabel">{{ translate('Delete Your Account') }}</h4>
                <p class="modal-subtitle"><i class="las la-exclamation-circle mr-1"></i>{{ translate('Warning: This action is permanent and cannot be undone.') }}</p>
            </div>

            <div class="modal-body pt-3 pb-3 px-4">
                <div class="notice-alert text-center">
                    <i class="las la-info-circle mr-1"></i>{{ translate("Please do not perform any actions or close the browser during account deletion.") }}
                </div>

                <p class="fs-13 fw-700 text-uppercase mb-2" style="color: #5f4d3e; letter-spacing: 0.5px;">{{ translate('Deleting your account means:') }}</p>

                <div class="consequence-card">
                    <div class="consequence-icon">
                        <i class="las la-trash-alt"></i>
                    </div>
                    <div class="consequence-text">
                        {{ translate('If you created any classified products, those products will be permanently removed from our system.') }}
                    </div>
                </div>

                <div class="consequence-card">
                    <div class="consequence-icon">
                        <i class="las la-wallet"></i>
                    </div>
                    <div class="consequence-text">
                        {{ translate('Any remaining wallet balance associated with your account will be forfeited.') }}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" data-dismiss="modal">{{ translate('Cancel') }}</button>
                <a id="account_delete_link" class="btn btn-delete-confirm">{{ translate('Delete Account') }}</a>
            </div>
        </div>
    </div>
</div>
