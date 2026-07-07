<div class="border-top pt-2 mt-2">
    <a href="javascript:void(0);" class="btn btn-xs btn-soft-secondary btn-block mb-2" onclick="$(this).next('.widget-custom-styles-panel').slideToggle();">
        <i class="las la-cog"></i> {{ translate('Widget Styles & Design Options') }}
    </a>
    <div class="widget-custom-styles-panel" style="display:none; background:#fafafa; border:1px solid #eee; border-radius:6px; padding:10px;">
        <div class="row">
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="form-label fs-10">{{ translate('Text Align') }}</label>
                    <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_text_align]" onchange="updateColumnPreview({{ $col }})">
                        <option value="" @if(empty($w['style_text_align'])) selected @endif>{{ translate('Default') }}</option>
                        <option value="left" @if(($w['style_text_align'] ?? '') == 'left') selected @endif>{{ translate('Left') }}</option>
                        <option value="center" @if(($w['style_text_align'] ?? '') == 'center') selected @endif>{{ translate('Center') }}</option>
                        <option value="right" @if(($w['style_text_align'] ?? '') == 'right') selected @endif>{{ translate('Right') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="form-label fs-10">{{ translate('Font Size Override') }}</label>
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_font_size]" value="{{ $w['style_font_size'] ?? '' }}" placeholder="e.g. 13px" oninput="updateColumnPreview({{ $col }})">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="form-label fs-10">{{ translate('Line Height') }}</label>
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_line_height]" value="{{ $w['style_line_height'] ?? '' }}" placeholder="e.g. 1.8" oninput="updateColumnPreview({{ $col }})">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="form-label fs-10">{{ translate('Bottom Margin') }}</label>
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_margin_bottom]" value="{{ $w['style_margin_bottom'] ?? '' }}" placeholder="e.g. 15px" oninput="updateColumnPreview({{ $col }})">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="form-label fs-10">{{ translate('Heading Weight') }}</label>
                    <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_head_weight]" onchange="updateColumnPreview({{ $col }})">
                        <option value="" @if(empty($w['style_head_weight'])) selected @endif>{{ translate('Default') }}</option>
                        <option value="300" @if(($w['style_head_weight'] ?? '') == '300') selected @endif>300 (Light)</option>
                        <option value="400" @if(($w['style_head_weight'] ?? '') == '400') selected @endif>400 (Normal)</option>
                        <option value="500" @if(($w['style_head_weight'] ?? '') == '500') selected @endif>500 (Medium)</option>
                        <option value="600" @if(($w['style_head_weight'] ?? '') == '600') selected @endif>600 (Semi Bold)</option>
                        <option value="700" @if(($w['style_head_weight'] ?? '') == '700') selected @endif>700 (Bold)</option>
                        <option value="800" @if(($w['style_head_weight'] ?? '') == '800') selected @endif>800 (Extra Bold)</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group mb-2">
                    <label class="form-label fs-10">{{ translate('Text Weight') }}</label>
                    <select class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_text_weight]" onchange="updateColumnPreview({{ $col }})">
                        <option value="" @if(empty($w['style_text_weight'])) selected @endif>{{ translate('Default') }}</option>
                        <option value="300" @if(($w['style_text_weight'] ?? '') == '300') selected @endif>300 (Light)</option>
                        <option value="400" @if(($w['style_text_weight'] ?? '') == '400') selected @endif>400 (Normal)</option>
                        <option value="500" @if(($w['style_text_weight'] ?? '') == '500') selected @endif>500 (Medium)</option>
                        <option value="600" @if(($w['style_text_weight'] ?? '') == '600') selected @endif>600 (Semi Bold)</option>
                        <option value="700" @if(($w['style_text_weight'] ?? '') == '700') selected @endif>700 (Bold)</option>
                        <option value="800" @if(($w['style_text_weight'] ?? '') == '800') selected @endif>800 (Extra Bold)</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-group mb-2">
            <label class="form-label fs-10">{{ translate('Heading Color Override') }}</label>
            <div class="input-group input-group-xs">
                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_head_color]" id="col-style-{{ $col }}-{{ $wIndex }}-head" value="{{ $w['style_head_color'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                <div class="input-group-append">
                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_head_color'] ?? '#000000' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-head').value = this.value; updateColumnPreview({{ $col }})">
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <label class="form-label fs-10">{{ translate('Text Color Override') }}</label>
            <div class="input-group input-group-xs">
                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_text_color]" id="col-style-{{ $col }}-{{ $wIndex }}-text" value="{{ $w['style_text_color'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                <div class="input-group-append">
                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_text_color'] ?? '#39322a' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-text').value = this.value; updateColumnPreview({{ $col }})">
                </div>
            </div>
        </div>

        <div class="form-group mb-2">
            <label class="form-label fs-10">{{ translate('Hover Color Override') }}</label>
            <div class="input-group input-group-xs">
                <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_hover_color]" id="col-style-{{ $col }}-{{ $wIndex }}-hover" value="{{ $w['style_hover_color'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                <div class="input-group-append">
                    <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_hover_color'] ?? '#876a4b' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-hover').value = this.value; updateColumnPreview({{ $col }})">
                </div>
            </div>
        </div>
        
        @if(in_array($wType, ['social_icons', 'seller_zone']))
            <h6 class="fs-10 font-weight-bold text-dark mt-3 border-bottom pb-1">{{ translate('Social Follow Styling') }}</h6>
            <div class="row">
                <div class="col-6">
                    <div class="form-group mb-2">
                        <label class="form-label fs-10">{{ translate('Icon Width/Size') }}</label>
                        <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_social_width]" value="{{ $w['style_social_width'] ?? '36px' }}" placeholder="36px" oninput="updateColumnPreview({{ $col }})">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group mb-2">
                        <label class="form-label fs-10">{{ translate('Border Radius') }}</label>
                        <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_social_radius]" value="{{ $w['style_social_radius'] ?? '' }}" placeholder="e.g. 50% or 4px" oninput="updateColumnPreview({{ $col }})">
                    </div>
                </div>
            </div>

            <div class="form-group mb-2">
                <label class="form-label fs-10">{{ translate('Icon Background') }}</label>
                <div class="input-group input-group-xs">
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_social_bg]" id="col-style-{{ $col }}-{{ $wIndex }}-sbg" value="{{ $w['style_social_bg'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                    <div class="input-group-append">
                        <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_social_bg'] ?? '#685b4e' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-sbg').value = this.value; updateColumnPreview({{ $col }})">
                    </div>
                </div>
            </div>

            <div class="form-group mb-2">
                <label class="form-label fs-10">{{ translate('Icon Color') }}</label>
                <div class="input-group input-group-xs">
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_social_color]" id="col-style-{{ $col }}-{{ $wIndex }}-scolor" value="{{ $w['style_social_color'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                    <div class="input-group-append">
                        <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_social_color'] ?? '#ffffff' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-scolor').value = this.value; updateColumnPreview({{ $col }})">
                    </div>
                </div>
            </div>

            <div class="form-group mb-2">
                <label class="form-label fs-10">{{ translate('Icon Hover Background') }}</label>
                <div class="input-group input-group-xs">
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_social_hover_bg]" id="col-style-{{ $col }}-{{ $wIndex }}-shbg" value="{{ $w['style_social_hover_bg'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                    <div class="input-group-append">
                        <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_social_hover_bg'] ?? '#876a4b' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-shbg').value = this.value; updateColumnPreview({{ $col }})">
                    </div>
                </div>
            </div>

            <div class="form-group mb-2">
                <label class="form-label fs-10">{{ translate('Icon Hover Color') }}</label>
                <div class="input-group input-group-xs">
                    <input type="text" class="form-control form-control-sm" name="foot_col_{{ $col }}_widgets[{{ $wIndex }}][style_social_hover_color]" id="col-style-{{ $col }}-{{ $wIndex }}-shcolor" value="{{ $w['style_social_hover_color'] ?? '' }}" oninput="updateColumnPreview({{ $col }})">
                    <div class="input-group-append">
                        <input type="color" class="p-0 border-0" style="width:28px; height:28px;" value="{{ $w['style_social_hover_color'] ?? '#ffffff' }}" oninput="document.getElementById('col-style-{{ $col }}-{{ $wIndex }}-shcolor').value = this.value; updateColumnPreview({{ $col }})">
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
