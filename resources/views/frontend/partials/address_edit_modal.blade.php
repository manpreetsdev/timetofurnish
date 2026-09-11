<form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    <div class="row">
        <!-- Flat / Building -->
        <div class="col-md-6 mb-3">
            <label class="address-form-label">
                {{ translate('Flat / Building Number or Name') }} <span class="req">*</span>
            </label>
            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. Flat 4B, Victoria House') }}" name="flat" value="{{ $address_data->flat }}" required>
        </div>

        <!-- Street -->
        <div class="col-md-6 mb-3">
            <label class="address-form-label">
                {{ translate('Street Address') }} <span class="req">*</span>
            </label>
            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. 12 High Street') }}" name="street" value="{{ $address_data->street }}" required>
        </div>
    </div>

    <!-- Full Address -->
    <div class="row mb-3">
        <div class="col-12">
            <label class="address-form-label">
                {{ translate('Full Address Details') }} <span class="req">*</span>
            </label>
            <textarea class="form-control address-form-control" rows="2" placeholder="{{ translate('Full address summary') }}" name="address" required>{{ $address_data->address }}</textarea>
        </div>
    </div>

    <div class="row">
        <!-- Country -->
        <div class="col-md-6 mb-3">
            <label class="address-form-label">
                {{ translate('Country') }} <span class="req">*</span>
            </label>
            <select class="form-control address-form-control address-select-native" name="country_id" id="edit_country" required>
                <option value="">{{ translate('Select your country') }}</option>
                @foreach (get_active_countries() as $country)
                    <option value="{{ $country->id }}" @if($address_data->country_id == $country->id) selected @endif>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Town or City -->
        <div class="col-md-6 mb-3">
            <label class="address-form-label">
                {{ translate('Town or City') }} <span class="req">*</span>
            </label>
            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. London') }}" name="city_id" value="{{ $address_data->city_id }}" required>
        </div>
    </div>

    <div class="row">
        <!-- Post Code -->
        <div class="col-md-6 mb-3">
            <label class="address-form-label">
                {{ translate('Postcode / Postal Code') }} <span class="req">*</span>
            </label>
            <input type="text" class="form-control address-form-control" placeholder="{{ translate('e.g. SW1A 1AA') }}" name="postal_code" value="{{ $address_data->postal_code }}" required>
        </div>

        <!-- Mobile Phone -->
        <div class="col-md-6 mb-3">
            <label class="address-form-label">
                {{ translate('Mobile Phone Number') }} <span class="req">*</span>
            </label>
            <input type="text" class="form-control address-form-control" inputmode="numeric" maxlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="phone" value="{{ $address_data->phone }}" required>
        </div>
    </div>

    <div class="row">
        <!-- Landline Number -->
        <div class="col-md-12 mb-3">
            <label class="address-form-label">
                {{ translate('Landline Number (Optional)') }}
            </label>
            <input type="text" class="form-control address-form-control" inputmode="numeric" maxlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" oninput="this.value = this.value.replace(/[^0-9]/g, '')" name="landline_no" value="{{ $address_data->landline_no }}">
        </div>
    </div>

    @if (get_setting('google_map') == 1)
        <!-- Google Map Integration -->
        <div class="row mt-2 mb-3">
            <div class="col-12">
                <input id="edit_searchInput" class="controls form-control address-form-control mb-2" type="text" placeholder="{{ translate('Search location on map') }}">
                <div id="edit_map" style="height: 220px; border-radius: 8px;"></div>
                <ul id="geoData" style="display:none;">
                    <li>Full Address: <span id="location"></span></li>
                    <li>Post Code: <span id="postal_code"></span></li>
                    <li>Country: <span id="country"></span></li>
                    <li>Latitude: <span id="lat"></span></li>
                    <li>Longitude: <span id="lon"></span></li>
                </ul>
            </div>
        </div>
        <input type="hidden" id="edit_longitude" name="longitude" value="{{ $address_data->longitude }}">
        <input type="hidden" id="edit_latitude" name="latitude" value="{{ $address_data->latitude }}">
    @endif

    <!-- Submit Button -->
    <div class="form-group mb-0 text-right mt-3">
        <button type="submit" class="btn address-save-btn">
            {{ translate('Update Address') }}
        </button>
    </div>
</form>