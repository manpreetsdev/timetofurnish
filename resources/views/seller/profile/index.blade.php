@extends('seller.layouts.app')

@section('panel_content')
    @php
        $shop = $user->shop;
    @endphp

    <style>
        .seller-profile-page {
            color: var(--brown);
        }

        .seller-profile-page .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            margin-bottom: 20px;
            background: var(--white);
            border: 1px solid rgba(57, 50, 42, 0.08);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(57, 50, 42, 0.06);
        }

        .seller-profile-page .profile-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 6px;
            color: var(--light-brown);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .seller-profile-page .profile-title {
            margin: 0;
            color: var(--primary);
            font-size: 24px;
            font-weight: 700;
        }

        .seller-profile-page .profile-subtitle {
            margin: 6px 0 0;
            color: var(--light-brown);
            font-size: 13px;
        }

        .seller-profile-page .profile-avatar {
            width: 64px;
            height: 64px;
            flex: 0 0 64px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--soft-primary);
            border: 2px solid var(--Soft-Beige);
        }

        .seller-profile-page .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .seller-profile-page .profile-avatar-fallback {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: var(--primary);
            font-size: 24px;
            font-weight: 700;
        }

        .seller-profile-page .profile-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(300px, 420px);
            gap: 18px;
            align-items: start;
        }

        .seller-profile-page .profile-card {
            margin-bottom: 18px;
            border: 1px solid rgba(57, 50, 42, 0.08);
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(57, 50, 42, 0.05);
            overflow: hidden;
        }

        .seller-profile-page .profile-card .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 16px 18px;
            background: var(--white);
            border-bottom: 1px solid rgba(57, 50, 42, 0.08);
        }

        .seller-profile-page .profile-card .card-header h5 {
            color: var(--primary);
            font-size: 15px;
            font-weight: 700;
        }

        .seller-profile-page .profile-card .card-body {
            padding: 18px;
        }

        .seller-profile-page .form-group,
        .seller-profile-page .profile-form-row {
            margin-bottom: 16px;
        }

        .seller-profile-page .profile-form-row {
            display: grid;
            grid-template-columns: 170px minmax(0, 1fr);
            gap: 14px;
            align-items: center;
        }

        .seller-profile-page .profile-form-row label,
        .seller-profile-page .col-form-label {
            margin-bottom: 0;
            color: var(--brown);
            font-weight: 600;
        }

        .seller-profile-page .form-control,
        .seller-profile-page .input-group-text {
            border-color: rgba(57, 50, 42, 0.14);
            border-radius: 6px;
        }

        .seller-profile-page .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(46, 41, 78, 0.12);
        }

        .seller-profile-page .btn-primary,
        .seller-profile-page .btn-primary:hover,
        .seller-profile-page .btn-primary:focus,
        .seller-profile-page .btn-primary:active {
            background: var(--primary);
            border-color: var(--primary);
        }

        .seller-profile-page .btn-theme-outline {
            color: var(--primary);
            background: var(--white);
            border: 1px solid rgba(46, 41, 78, 0.25);
        }

        .seller-profile-page .btn-theme-outline:hover {
            color: var(--white);
            background: var(--primary);
            border-color: var(--primary);
        }

        .seller-profile-page .aiz-switch-success input:checked ~ .slider {
            background-color: var(--primary);
        }

        .seller-profile-page .payment-toggle-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 12px;
            background: var(--off-white);
            border: 1px solid rgba(57, 50, 42, 0.08);
            border-radius: 8px;
        }

        .seller-profile-page .payment-toggle-row label {
            margin: 0;
        }

        .seller-profile-page .address-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
        }

        .seller-profile-page .address-card,
        .seller-profile-page .add-address-card {
            min-height: 100%;
            border: 1px solid rgba(57, 50, 42, 0.12);
            border-radius: 8px;
            background: var(--white);
        }

        .seller-profile-page .address-card {
            position: relative;
            padding: 16px 48px 16px 16px;
        }

        .seller-profile-page .address-card .dropdown {
            top: 10px;
            right: 8px;
        }

        .seller-profile-page .address-menu-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            color: var(--primary);
            background: var(--soft-primary);
            border-radius: 6px;
        }

        .seller-profile-page .address-line {
            display: grid;
            grid-template-columns: 112px minmax(0, 1fr);
            gap: 8px;
            margin-bottom: 9px;
            color: var(--light-brown);
            line-height: 1.45;
            word-break: break-word;
        }

        .seller-profile-page .address-line strong {
            color: var(--brown);
            font-weight: 700;
        }

        .seller-profile-page .badge-primary {
            background: var(--primary);
        }

        .seller-profile-page .add-address-card {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 170px;
            padding: 18px;
            color: var(--primary);
            background: var(--off-white);
            border-style: dashed;
            cursor: pointer;
            text-align: center;
        }

        .seller-profile-page .add-address-card i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            margin-bottom: 8px;
            border-radius: 50%;
            background: var(--soft-primary);
        }

        .seller-profile-page .empty-address {
            padding: 18px;
            color: var(--light-brown);
            background: var(--off-white);
            border-radius: 8px;
            text-align: center;
        }

        .seller-profile-page .profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 18px;
        }

        .seller-profile-modal .modal-content {
            border: 0;
            border-radius: 8px;
            overflow: hidden;
        }

        .seller-profile-modal .modal-header {
            background: var(--off-white);
            border-bottom: 1px solid rgba(57, 50, 42, 0.08);
        }

        .seller-profile-modal .modal-title {
            color: var(--primary);
            font-weight: 700;
        }

        .seller-profile-modal .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(46, 41, 78, 0.12);
        }

        @media (max-width: 1199.98px) {
            .seller-profile-page .profile-grid,
            .seller-profile-page .address-grid {
                grid-template-columns: 1fr 1fr;
            }

            .seller-profile-page .profile-main {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 767.98px) {
            .seller-profile-page .profile-header {
                align-items: flex-start;
                padding: 16px;
            }

            .seller-profile-page .profile-title {
                font-size: 21px;
            }

            .seller-profile-page .profile-grid,
            .seller-profile-page .address-grid {
                grid-template-columns: 1fr;
            }

            .seller-profile-page .profile-form-row {
                grid-template-columns: 1fr;
                gap: 6px;
            }

            .seller-profile-page .profile-card .card-header,
            .seller-profile-page .profile-card .card-body {
                padding: 14px;
            }

            .seller-profile-page .address-line {
                grid-template-columns: 1fr;
                gap: 2px;
            }

            .seller-profile-page .profile-actions .btn {
                width: 100%;
            }

            .seller-profile-page .input-group {
                flex-wrap: nowrap;
            }

            .seller-profile-page .input-group-append .btn {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>

    <div class="seller-profile-page">
        <div class="profile-header">
            <div>
                <div class="profile-eyebrow">{{ translate('Seller Panel') }}</div>
                <h1 class="profile-title">{{ translate('Manage Profile') }}</h1>
                <p class="profile-subtitle">{{ translate('Update account details, payment settings, address and email from one place.') }}</p>
            </div>
            <div class="profile-avatar">
                @if ($user->avatar_original)
                    <img src="{{ uploaded_asset($user->avatar_original) }}" alt="{{ $user->name }}">
                @else
                    <div class="profile-avatar-fallback">{{ strtoupper(substr($user->name ?? 'S', 0, 1)) }}</div>
                @endif
            </div>
        </div>

        <form action="{{ route('seller.profile.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="POST">
            @csrf

            <div class="profile-grid">
                <div class="profile-main">
                    <div class="card profile-card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ translate('Business Info')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="profile-form-row">
                                <label for="name">{{ translate('Your Name') }}</label>
                                <div>
                                    <input type="text" name="name" value="{{ $user->name }}" id="name" class="form-control" placeholder="{{ translate('Your Name') }}" required>
                                    @error('name')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="profile-form-row">
                                <label for="phone">{{ translate('Mobile Number') }}</label>
                                <div>
                                    <input type="text" name="phone" value="{{ $user->phone }}" id="phone" class="form-control" placeholder="{{ translate('Your Phone')}}" maxlength="14" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('phone')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="profile-form-row">
                                <label>{{ translate('Photo') }}</label>
                                <div>
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="photo" value="{{ $user->avatar_original }}" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <div class="profile-form-row">
                                <label for="password">{{ translate('Your Password') }}</label>
                                <div>
                                    <input type="password" name="new_password" id="password" class="form-control" placeholder="{{ translate('New Password') }}">
                                    @error('new_password')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="profile-form-row mb-0">
                                <label for="confirm_password">{{ translate('Confirm Password') }}</label>
                                <div>
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="{{ translate('Confirm Password') }}">
                                    @error('confirm_password')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="card profile-card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ translate('Payment Setting')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="payment-toggle-row">
                                <span class="font-weight-bold">{{ translate('Cash Payment') }}</span>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="cash_on_delivery_status" type="checkbox" @if (optional($shop)->cash_on_delivery_status == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="payment-toggle-row">
                                <span class="font-weight-bold">{{ translate('Bank Payment') }}</span>
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="bank_payment_status" type="checkbox" @if (optional($shop)->bank_payment_status == 1) checked @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>

                            <div class="form-group">
                                <label for="bank_name">{{ translate('Bank Name') }}</label>
                                <input type="text" name="bank_name" value="{{ optional($shop)->bank_name }}" id="bank_name" class="form-control" placeholder="{{ translate('Bank Name')}}" maxlength="40" pattern="^[A-Za-z ]+$" oninput="this.value = this.value.replace(/[^A-Za-z ]/g, '')">
                                @error('bank_name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="bank_acc_name">{{ translate('Bank Account Name') }}</label>
                                <input type="text" name="bank_acc_name" value="{{ optional($shop)->bank_acc_name }}" id="bank_acc_name" class="form-control" placeholder="{{ translate('Bank Account Name')}}" maxlength="30" oninput="if(this.value.length > 30) this.value = this.value.slice(0,30);">
                                @error('bank_acc_name')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="bank_acc_no">{{ translate('Bank Account Number') }}</label>
                                <input type="text" name="bank_acc_no" value="{{ optional($shop)->bank_acc_no }}" id="bank_acc_no" class="form-control" placeholder="{{ translate('Bank Account Number')}}" pattern="\d{12}" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);">
                                @error('bank_acc_no')
                                    <small class="form-text text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-0">
                                <label for="bank_routing_no">{{ translate('Bank Sort Code') }}</label>
                                @php
                                    $sortCode = old('bank_routing_no', optional($shop)->bank_routing_no);
                                    $formattedSortCode = '';
                                    if ($sortCode && strlen($sortCode) == 6) {
                                        $formattedSortCode = substr($sortCode, 0, 2).'-'.substr($sortCode, 2, 2).'-'.substr($sortCode, 4, 2);
                                    }
                                @endphp
                                <input type="text" name="bank_routing_no" id="bank_routing_no" class="form-control" placeholder="00-00-00" maxlength="8" value="{{ $formattedSortCode }}" oninput="formatSortCode(this)">
                                @error('bank_routing_no')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="profile-actions">
                <button type="submit" class="btn btn-primary">{{translate('Update Profile')}}</button>
            </div>
        </form>

        <div class="card profile-card">
            <div class="card-header">
                <h5 class="mb-0">{{ translate('Address')}}</h5>
                <button type="button" class="btn btn-sm btn-theme-outline" onclick="add_new_address()">{{ translate('Add New Address') }}</button>
            </div>
            <div class="card-body">
                @if ($addresses->count())
                    <div class="address-grid">
                        @foreach ($addresses as $key => $address)
                            <div class="address-card">
                                <div class="address-line">
                                    <strong>{{ translate('Flat/building number or name') }}</strong>
                                    <span>{{ $address->address }}</span>
                                </div>
                                <div class="address-line">
                                    <strong>{{ translate('Post Code') }}</strong>
                                    <span>{{ $address->postal_code }}</span>
                                </div>
                                <div class="address-line">
                                    <strong>{{ translate('Country') }}</strong>
                                    <span>{{ optional($address->country)->name }}</span>
                                </div>
                                <div class="address-line">
                                    <strong>{{ translate('City') }}</strong>
                                    <span>{{ $address->city_id }}</span>
                                </div>
                                <div class="address-line">
                                    <strong>{{ translate('Mobile Number') }}</strong>
                                    <span>{{ $address->phone }}</span>
                                </div>
                                <div class="address-line">
                                    <strong>{{ translate('Landline Number') }}</strong>
                                    <span>{{ $address->landline_no }}</span>
                                </div>
                                @if ($address->set_default)
                                    <div class="mt-2">
                                        <span class="badge badge-inline badge-primary">{{ translate('Default') }}</span>
                                    </div>
                                @endif
                                <div class="dropdown position-absolute">
                                    <button class="btn address-menu-btn" type="button" data-toggle="dropdown" aria-label="{{ translate('Address actions') }}">
                                        <i class="la la-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" onclick="edit_address('{{$address->id}}')">{{ translate('Edit') }}</a>
                                        @if (!$address->set_default)
                                            <a class="dropdown-item" href="{{ route('seller.addresses.set_default', $address->id) }}">{{ translate('Make This Default') }}</a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('seller.addresses.destroy', $address->id) }}">{{ translate('Delete') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="add-address-card" onclick="add_new_address()">
                            <div>
                                <i class="la la-plus la-2x"></i>
                                <div class="font-weight-bold">{{ translate('Add New Address') }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-address">
                        {{ translate('No address added yet.') }}
                    </div>
                @endif
            </div>
        </div>

        <form action="{{ route('user.change.email') }}" method="POST">
            @csrf
            <div class="card profile-card">
                <div class="card-header">
                    <h5 class="mb-0">{{ translate('Change your email')}}</h5>
                </div>
                <div class="card-body">
                    <div class="profile-form-row mb-0">
                        <label>{{ translate('Your Email') }}</label>
                        <div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control" placeholder="{{ translate('Your Email')}}" name="email" value="{{ $user->email }}" />
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-theme-outline new-email-verification">
                                        <span class="d-none loading">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> {{ translate('Sending Email...') }}
                                        </span>
                                        <span class="default">{{ translate('Verify') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group mb-0 text-right">
                                <button type="submit" class="btn btn-primary">{{translate('Update Email')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection

@section('modal')
    {{-- New Address Modal --}}
    {{-- New Address Modal --}}
<div class="modal fade seller-profile-modal" id="new-address-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">{{ translate('New Address') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="{{ route('seller.addresses.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="p-3">

                        {{-- Flat / Building --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Flat/building number or name') }}
                            </label>
                            <div class="col-md-9">
                                <textarea class="form-control"
                                    name="address"
                                    rows="2"
                                    placeholder="{{ translate('Your Address') }}"
                                    required></textarea>
                            </div>
                        </div>

                        {{-- Country --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Country') }}
                            </label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker"
                                    data-live-search="true"
                                    data-placeholder="{{ translate('Select your country') }}"
                                    name="country_id"
                                    required>
                                    <option value="">{{ translate('Select your country') }}</option>
                                    @foreach (\App\Models\Country::where('status',1)->get() as $country)
                                        <option value="{{ $country->id }}">
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- City --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                {{ translate('City') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text"
                                    class="form-control"
                                    name="city_id"
                                    required>
                            </div>
                        </div>

                        {{-- Post Code --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Post Code') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text"
                                    class="form-control"
                                    name="postal_code"
                                    placeholder="{{ translate('Your Post Code') }}"
                                    required>
                            </div>
                        </div>

                        {{-- Mobile --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Mobile Number') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text"
                                    class="form-control"
                                    name="phone"
                                    maxlength="14"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    required>
                            </div>
                        </div>

                        {{-- Landline --}}
                        <div class="form-group row mb-3">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Landline Number') }}
                            </label>
                            <div class="col-md-9">
                                <input type="text"
                                    class="form-control"
                                    name="landline_no"
                                    maxlength="14"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>
                        </div>

                        {{-- Save Button --}}
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">
                                {{ translate('Save') }}
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


    {{-- Edit Address Modal --}}
    <div class="modal fade seller-profile-modal" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ translate('New Address') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body" id="edit_modal_body">

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
            
        
        $('.new-email-verification').on('click', function() {
            $(this).find('.loading').removeClass('d-none');
            $(this).find('.default').addClass('d-none');
            var email = $("input[name=email]").val();

            $.post('{{ route('user.new.verify') }}', {_token:'{{ csrf_token() }}', email: email}, function(data){
                data = JSON.parse(data);
                $('.default').removeClass('d-none');
                $('.loading').addClass('d-none');
                if(data.status == 2)
                    AIZ.plugins.notify('warning', data.message);
                else if(data.status == 1)
                    AIZ.plugins.notify('success', data.message);
                else
                    AIZ.plugins.notify('danger', data.message);
            });
        });

        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route("seller.addresses.edit", ":id") }}';
            url = url.replace(':id', address);
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#edit_modal_body').html(response.html);
                    $('#edit-address-modal').modal('show');
                    AIZ.plugins.bootstrapSelect('refresh');

                    @if (get_setting('google_map') == 1)
                        var lat     = -33.8688;
                        var long    = 151.2195;

                        if(response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat     = parseFloat(response.data.address_data.latitude);
                            long    = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }
        
        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            get_states(country_id);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            get_city(state_id);
        });
        
        function get_states(country_id) {
            $('[name="state"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.get-state')}}",
                type: 'POST',
                data: {
                    country_id  : country_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="state_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
            });
        }

        function get_city(state_id) {
            $('[name="city"]').html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.get-city')}}",
                type: 'POST',
                data: {
                    state_id: state_id
                },
                success: function (response) {
                    var obj = JSON.parse(response);
                    if(obj != '') {
                        $('[name="city_id"]').html(obj);
                        AIZ.plugins.bootstrapSelect('refresh');
                    }
                }
                
                
            });
           


        }
        function formatSortCode(input) {
    let numbers = input.value.replace(/\D/g, '').slice(0, 6); // only 6 digits

    let formatted = numbers
        .replace(/(\d{2})(\d{0,2})(\d{0,2})/, function(_, p1, p2, p3) {
            let result = p1;
            if (p2) result += '-' + p2;
            if (p3) result += '-' + p3;
            return result;
        });

    input.value = formatted;
}

    </script>

    @if (get_setting('google_map') == 1)
        
        @include('frontend.'.get_setting('homepage_select').'.partials.google_map')
        
    @endif

@endsection
