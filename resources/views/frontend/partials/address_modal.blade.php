<!-- Custom Styles for Residential Address Modal -->
<style>
    .address-modal-content {
        border: none !important;
        border-radius: 16px !important;
        overflow: hidden !important;
        box-shadow: 0 20px 50px rgba(45, 33, 26, 0.15) !important;
        background: #ffffff !important;
        font-family: 'Poppins', sans-serif !important;
    }

    .address-modal-header {
        background-color: #FAF7F2 !important;
        padding: 22px 28px !important;
        border-bottom: 1px solid #eee8e1 !important;
        position: relative !important;
    }

    .address-modal-title {
        font-family: 'DM Serif Display', 'Playfair Display', Georgia, serif !important;
        font-size: 24px !important;
        font-weight: 700 !important;
        color: #2c2520 !important;
        line-height: 1.2 !important;
        margin: 0 !important;
    }

    .address-modal-subtitle {
        font-family: 'Poppins', sans-serif !important;
        font-size: 13px !important;
        color: #786c60 !important;
        margin-top: 4px !important;
        margin-bottom: 0 !important;
    }

    .address-modal-close {
        position: absolute !important;
        top: 20px !important;
        right: 24px !important;
        width: 34px !important;
        height: 34px !important;
        background: #efe9e0 !important;
        border-radius: 50% !important;
        border: none !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        color: #5c5247 !important;
        font-size: 18px !important;
        transition: all 0.2s ease !important;
        cursor: pointer !important;
        padding: 0 !important;
    }

    .address-modal-close:hover {
        background: #e2d9cd !important;
        color: #2c2520 !important;
        transform: scale(1.05) !important;
    }

    .address-modal-body {
        padding: 28px !important;
        max-height: calc(100vh - 160px) !important;
        overflow-y: auto !important;
    }

    .address-form-label {
        font-family: 'Poppins', sans-serif !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #3b332a !important;
        margin-bottom: 6px !important;
        display: block !important;
    }

    .address-form-label span.req {
        color: #d94c43 !important;
        margin-left: 2px !important;
    }

    .address-form-control {
        font-family: 'Poppins', sans-serif !important;
        font-size: 14px !important;
        color: #2b251f !important;
        background-color: #fcfbfa !important;
        border: 1px solid #e2dad0 !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        min-height: 46px !important;
        width: 100% !important;
        transition: all 0.2s ease-in-out !important;
        box-shadow: none !important;
    }

    .address-form-control:focus {
        background-color: #ffffff !important;
        border-color: #685b4e !important;
        box-shadow: 0 0 0 3.5px rgba(104, 91, 78, 0.15) !important;
        outline: none !important;
    }

    .address-form-control:hover:not(:focus) {
        border-color: #b5a99c !important;
    }

    textarea.address-form-control {
        min-height: 80px !important;
        resize: vertical !important;
    }

    .address-select-native {
        appearance: auto !important;
        -webkit-appearance: auto !important;
        cursor: pointer !important;
        background-color: #fcfbfa !important;
    }

    .address-save-btn {
        background-color: #685b4e !important;
        color: #ffffff !important;
        font-family: 'Poppins', sans-serif !important;
        font-size: 14px !important;
        font-weight: 600 !important;
        border: none !important;
        border-radius: 8px !important;
        min-height: 48px !important;
        padding: 0 32px !important;
        box-shadow: 0 4px 12px rgba(104, 91, 78, 0.2) !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        cursor: pointer !important;
    }

    .address-save-btn:hover {
        background-color: #54493e !important;
        color: #ffffff !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 16px rgba(104, 91, 78, 0.3) !important;
    }

    @media (max-width: 575.98px) {
        .address-modal-header {
            padding: 18px 20px !important;
        }
        .address-modal-title {
            font-size: 20px !important;
        }
        .address-modal-body {
            padding: 20px !important;
        }
    }
</style>

<!-- New Address Modal -->
<div class="modal fade" id="new-address-modal" tabindex="-1" role="dialog" aria-labelledby="newAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content address-modal-content">
            <div class="modal-header address-modal-header">
                <div>
                    <h5 class="modal-title address-modal-title" id="newAddressModalLabel">
                        {{ translate('What’s Your Residential Address?') }}
                    </h5>
                    <p class="address-modal-subtitle">
                        {{ translate('Please enter your residential shipping details below.') }}
                    </p>
                </div>
                <button type="button" class="close address-modal-close" data-dismiss="modal" aria-label="Close">
                  
                </button>
            </div>
            
            <form class="form-default" role="form" action="{{ route('addresses.store') }}" method="POST">
                @csrf
                <div class="modal-body address-modal-body c-scrollbar-light">
                    <div class="row">
                        <!-- Flat / Building -->
                        <div class="col-md-6 mb-3">
                            <label class="address-form-label">
                                {{ translate('Flat / Building Number or Name') }} <span class="req">*</span>
                            </label>
                            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. Flat 4B, Victoria House') }}" name="flat" required>
                        </div>

                        <!-- Street -->
                        <div class="col-md-6 mb-3">
                            <label class="address-form-label">
                                {{ translate('Street Address') }} <span class="req">*</span>
                            </label>
                            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. 12 High Street') }}" name="street" required>
                        </div>
                    </div>

                    <!-- Full Address -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="address-form-label">
                                {{ translate('Full Address Details') }} <span class="req">*</span>
                            </label>
                            <textarea class="form-control address-form-control" rows="2" placeholder="{{ translate('Full address summary') }}" name="address" required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Country -->
                        <div class="col-md-6 mb-3">
                            <label class="address-form-label">
                                {{ translate('Country') }} <span class="req">*</span>
                            </label>
                            <select class="form-control address-form-control address-select-native" name="country_id" required>
                                <option value="">{{ translate('Select your country') }}</option>
                                @foreach (get_active_countries() as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Town or City -->
                        <div class="col-md-6 mb-3">
                            <label class="address-form-label">
                                {{ translate('Town or City') }} <span class="req">*</span>
                            </label>
                            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. London') }}" name="city_id" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Post Code -->
                        <div class="col-md-6 mb-3">
                            <label class="address-form-label">
                                {{ translate('Postcode / Postal Code') }} <span class="req">*</span>
                            </label>
                            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. SW1A 1AA') }}" name="postal_code" required>
                        </div>

                        <!-- Mobile Phone -->
                        <div class="col-md-6 mb-3">
                            <label class="address-form-label">
                                {{ translate('Mobile Phone Number') }} <span class="req">*</span>
                            </label>
                            <input type="text" class="form-control address-form-control" inputmode="numeric" maxlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="{{ translate('e.g. 07123456789') }}" name="phone" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Landline Number -->
                        <div class="col-md-12 mb-3">
                            <label class="address-form-label">
                                {{ translate('Landline Number (Optional)') }}
                            </label>
                            <input type="text" class="form-control address-form-control" inputmode="numeric" maxlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="{{ translate('e.g. 02012345678') }}" name="landline_no">
                        </div>
                    </div>

                    @if (get_setting('google_map') == 1)
                        <!-- Google Map Integration -->
                        <div class="row mt-2 mb-3">
                            <div class="col-12">
                                <input id="searchInput" class="controls form-control address-form-control mb-2" type="text" placeholder="{{ translate('Search location on map') }}">
                                <div id="map" style="height: 220px; border-radius: 8px;"></div>
                                <ul id="geoData" style="display:none;">
                                    <li>Full Address: <span id="location"></span></li>
                                    <li>Post Code: <span id="postal_code"></span></li>
                                    <li>Country: <span id="country"></span></li>
                                    <li>Latitude: <span id="lat"></span></li>
                                    <li>Longitude: <span id="lon"></span></li>
                                </ul>
                            </div>
                        </div>
                        <input type="hidden" id="longitude" name="longitude">
                        <input type="hidden" id="latitude" name="latitude">
                    @endif

                    <!-- Submit Button -->
                    <div class="form-group mb-0 text-right mt-3">
                        <button type="submit" class="btn address-save-btn">
                            {{ translate('Save Address') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content address-modal-content">
            <div class="modal-header address-modal-header">
                <div>
                    <h5 class="modal-title address-modal-title" id="editAddressModalLabel">
                        {{ translate('Edit Residential Address') }}
                    </h5>
                    <p class="address-modal-subtitle">
                        {{ translate('Update your residential shipping details below.') }}
                    </p>
                </div>
                <button type="button" class="close address-modal-close" data-dismiss="modal" aria-label="Close">
                   
                </button>
            </div>
            
            <div class="modal-body address-modal-body c-scrollbar-light" id="edit_modal_body">

            </div>
        </div>
    </div>
</div>

@section('script')
    <script type="text/javascript">
        function add_new_address(){
            $('#new-address-modal').modal('show');
        }

        function edit_address(address) {
            var url = '{{ route("addresses.edit", ":id") }}';
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
                    if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                        AIZ.plugins.bootstrapSelect('refresh');
                    }

                    @if (get_setting('google_map') == 1)
                        var lat     = -33.8688;
                        var long    = 151.2195;

                        if(response.data && response.data.address_data && response.data.address_data.latitude && response.data.address_data.longitude) {
                            lat     = parseFloat(response.data.address_data.latitude);
                            long    = parseFloat(response.data.address_data.longitude);
                        }

                        initialize(lat, long, 'edit_');
                    @endif
                }
            });
        }

        $(document).on('input', 'input[name="flat"], input[name="street"]', function() {
            var $form = $(this).closest('form');
            var flat = $form.find('input[name="flat"]').val() || '';
            var street = $form.find('input[name="street"]').val() || '';
            var combined = [flat.trim(), street.trim()].filter(Boolean).join(', ');
            var $addr = $form.find('textarea[name="address"], input[name="address"]');
            if ($addr.length && combined && !$addr.data('user-touched')) {
                $addr.val(combined);
            }
        });

        $(document).on('input', 'textarea[name="address"], input[name="address"]', function() {
            $(this).data('user-touched', true);
        });

        $(document).on('change', '[name=country_id]', function() {
            var country_id = $(this).val();
            var $form = $(this).closest('form');
            get_states(country_id, $form);
        });

        $(document).on('change', '[name=state_id]', function() {
            var state_id = $(this).val();
            var $form = $(this).closest('form');
            get_city(state_id, $form);
        });

        function get_states(country_id, $form) {
            var $stateSelect = $form ? $form.find('[name="state_id"]') : $('[name="state_id"]');
            if (!$stateSelect.length) return;
            $stateSelect.html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-state')}}",
                type: 'POST',
                data: { country_id: country_id },
                success: function (response) {
                    var obj = typeof response === 'string' ? JSON.parse(response) : response;
                    if(obj != '') {
                        $stateSelect.html(obj);
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                    }
                }
            });
        }

        function get_city(state_id, $form) {
            var $citySelect = $form ? $form.find('[name="city_id"]') : $('[name="city_id"]');
            if (!$citySelect.length || $citySelect.is('input')) return;
            $citySelect.html("");
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('get-city')}}",
                type: 'POST',
                data: { state_id: state_id },
                success: function (response) {
                    var obj = typeof response === 'string' ? JSON.parse(response) : response;
                    if(obj != '') {
                        $citySelect.html(obj);
                        if (typeof AIZ !== 'undefined' && AIZ.plugins && AIZ.plugins.bootstrapSelect) {
                            AIZ.plugins.bootstrapSelect('refresh');
                        }
                    }
                }
            });
        }
    </script>

    @if (get_setting('google_map') == 1)
        @include('frontend.partials.google_map')
    @endif
@endsection