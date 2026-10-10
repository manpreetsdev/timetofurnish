@extends('backend.layouts.app')

@section('content')

    <div class="row">
    	<div class="col-lg-8 mx-auto">
    		<div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('General') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST">
    					@csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Frontend Website Name')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="website_name">
        	                    <input type="text" name="website_name" class="form-control" placeholder="{{ translate('Website Name') }}" value="{{ get_setting('website_name') }}">
                            </div>
                        </div>
    	                <div class="form-group row">
    	                    <label class="col-md-3 col-from-label">{{translate('Site Motto')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="site_motto">
        	                    <input type="text" name="site_motto" class="form-control" placeholder="{{ translate('Best eCommerce Website') }}" value="{{  get_setting('site_motto') }}">
                            </div>
    	                </div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Site Icon') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="site_icon">
        							<input type="hidden" name="site_icon" value="{{ get_setting('site_icon') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
        						<small class="text-muted">{{ translate('Website favicon. 32x32 .png') }}</small>
                            </div>
    					</div>
						<!-- Website Base Color -->
    	                <div class="form-group row">
    	                    <label class="col-md-3 col-from-label">{{translate('Website Base Color')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="base_color">
        	                    <input type="text" name="base_color" class="form-control" placeholder="#377dff" value="{{ get_setting('base_color') }}">
        						<small class="text-muted">{{ translate('Hex Color Code') }}</small>
                            </div>
    	                </div>
						<!-- Website Base Hover Color -->
    	                <div class="form-group row">
    	                    <label class="col-md-3 col-from-label">{{translate('Website Base Hover Color')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="base_hov_color">
        	                    <input type="text" name="base_hov_color" class="form-control" placeholder="#377dff" value="{{  get_setting('base_hov_color') }}">
        						<small class="text-muted">{{ translate('Hex Color Code') }}</small>
                            </div>
    	                </div>
						<!-- Website Secondary Base Color -->
    	                <div class="form-group row">
    	                    <label class="col-md-3 col-from-label">{{translate('Website Secondary Base Color')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="secondary_base_color">
        	                    <input type="text" name="secondary_base_color" class="form-control" placeholder="#ffc519" value="{{ get_setting('secondary_base_color') }}">
        						<small class="text-muted">{{ translate('Hex Color Code') }}</small>
                            </div>
    	                </div>
						<!-- Website Secondary Base Hover Color -->
    	                <div class="form-group row">
    	                    <label class="col-md-3 col-from-label">{{translate('Website Secondary Base Hover Color')}}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="secondary_base_hov_color">
        	                    <input type="text" name="secondary_base_hov_color" class="form-control" placeholder="#dbaa17" value="{{  get_setting('secondary_base_hov_color') }}">
        						<small class="text-muted">{{ translate('Hex Color Code') }}</small>
                            </div>
    	                </div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Customer Login page Image') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="login_page_image">
        							<input type="hidden" name="login_page_image" value="{{ get_setting('login_page_image') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Customer Register page Image') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="register_page_image">
        							<input type="hidden" name="register_page_image" value="{{ get_setting('register_page_image') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
                            </div>
    					</div>
						@if (get_setting('vendor_system_activation') == 1)
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{ translate('Seller Login page Background') }}</label>
								<div class="col-md-8">
									<div class="input-group " data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend">
											<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
										</div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="types[]" value="seller_login_page_bg">
										<input type="hidden" name="seller_login_page_bg" value="{{ get_setting('seller_login_page_bg') }}" class="selected-files">
									</div>
									<div class="file-preview box"></div>
								</div>
							</div>
						@endif
						@if (addon_is_activated('delivery_boy'))
							<div class="form-group row">
								<label class="col-md-3 col-from-label">{{ translate('Delivery Boy Login page Background') }}</label>
								<div class="col-md-8">
									<div class="input-group " data-toggle="aizuploader" data-type="image">
										<div class="input-group-prepend">
											<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
										</div>
										<div class="form-control file-amount">{{ translate('Choose File') }}</div>
										<input type="hidden" name="types[]" value="delivery_boy_login_page_bg">
										<input type="hidden" name="delivery_boy_login_page_bg" value="{{ get_setting('delivery_boy_login_page_bg') }}" class="selected-files">
									</div>
									<div class="file-preview box"></div>
								</div>
							</div>
						@endif
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Flash Deal Banner large') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="flash_deal_banner">
        							<input type="hidden" name="flash_deal_banner" value="{{ get_setting('flash_deal_banner') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Flash Deal Banner Small') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="flash_deal_banner_small">
        							<input type="hidden" name="flash_deal_banner_small" value="{{ get_setting('flash_deal_banner_small') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
                            </div>
    					</div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
                    </form>
    			</div>
    		</div>
    		<div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('Global SEO') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Meta Title') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="meta_title">
        						<input type="text" class="form-control" placeholder="{{translate('Title')}}" name="meta_title" value="{{ get_setting('meta_title') }}">
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Meta description') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="meta_description">
        						<textarea class="resize-off form-control" placeholder="{{translate('Description')}}" name="meta_description">{{  get_setting('meta_description') }}</textarea>
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Keywords') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="meta_keywords">
        						<textarea class="resize-off form-control" placeholder="{{translate('Keyword, Keyword')}}" name="meta_keywords">{{ get_setting('meta_keywords') }}</textarea>
        						<small class="text-muted">{{ translate('Separate with coma') }}</small>
                            </div>
    					</div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
        						<div class="input-group " data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
        							<input type="hidden" name="types[]" value="meta_image">
        							<input type="hidden" name="meta_image" value="{{ get_setting('meta_image') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
                            </div>
    					</div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
    				</form>
    			</div>
    		</div>
            <div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('Cookies Agreement') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Cookies Agreement Text') }}</label>
                            <div class="col-md-8">
        						<input type="hidden" name="types[]" value="cookies_agreement_text">
        						<textarea name="cookies_agreement_text" rows="4" class="aiz-text-editor form-control" data-buttons='[["font", ["bold"]],["insert", ["link"]]]'>{{ get_setting('cookies_agreement_text') }}</textarea>
                            </div>
    					</div>
                        <div class="form-group row">
    						<label class="col-md-3 col-from-label">{{translate('Show Cookies Agreement?')}}</label>
    						<div class="col-md-8">
    							<label class="aiz-switch aiz-switch-success mb-0">
    								<input type="hidden" name="types[]" value="show_cookies_agreement">
    								<input type="checkbox" name="show_cookies_agreement" @if( get_setting('show_cookies_agreement') == 'on') checked @endif>
    								<span></span>
    							</label>
    						</div>
    					</div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
    				</form>
    			</div>
    		</div>
            <div class="card" id="home-promo-popup">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('Home Page Promo Popup') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
    					<div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Show popup on home page?') }}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="home_popup_enabled">
                                    <input type="checkbox" name="home_popup_enabled" @if (get_setting('home_popup_enabled') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
    					<div class="form-group row">
    						<label class="col-md-3 col-from-label">{{ translate('Popup Image') }}</label>
                            <div class="col-md-8">
        						<div class="input-group" data-toggle="aizuploader" data-type="image">
        							<div class="input-group-prepend">
        								<div class="input-group-text bg-soft-secondary">{{ translate('Browse') }}</div>
        							</div>
        							<div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="types[]" value="home_popup_image">
        							<input type="hidden" name="home_popup_image" value="{{ get_setting('home_popup_image') }}" class="selected-files">
        						</div>
        						<div class="file-preview box"></div>
        						<small class="text-muted">{{ translate('Shown on its own when no title/text is set. Recommended around 1800x800px (landscape).') }}</small>
                            </div>
    					</div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Show after (seconds)') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="home_popup_delay">
                                <input type="number" min="0" step="1" name="home_popup_delay" class="form-control" value="{{ get_setting('home_popup_delay') ?? 10 }}">
                                <small class="text-muted">{{ translate('How long a visitor stays on the home page before the popup appears.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Show how often') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="home_popup_frequency">
                                @php $homePopupFrequency = get_setting('home_popup_frequency') ?: 'session'; @endphp
                                <select name="home_popup_frequency" class="form-control aiz-selectpicker">
                                    <option value="session" @if ($homePopupFrequency == 'session') selected @endif>{{ translate('Once per visit (browser session)') }}</option>
                                    <option value="once" @if ($homePopupFrequency == 'once') selected @endif>{{ translate('Only once per visitor') }}</option>
                                    <option value="always" @if ($homePopupFrequency == 'always') selected @endif>{{ translate('Every time the home page opens') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Button text') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="home_popup_button_text">
                                <input type="text" name="home_popup_button_text" class="form-control" placeholder="{{ translate('Explore') }}" value="{{ get_setting('home_popup_button_text') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Button / image link') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="home_popup_button_link">
                                <input type="text" name="home_popup_button_link" class="form-control" placeholder="https://" value="{{ get_setting('home_popup_button_link') }}">
                                <small class="text-muted">{{ translate('Where the button (and the image) takes the visitor. Leave both button fields empty to hide the button.') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Title') }} <small class="text-muted">({{ translate('optional') }})</small></label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="home_popup_title">
                                <input type="text" name="home_popup_title" class="form-control" value="{{ get_setting('home_popup_title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Text') }} <small class="text-muted">({{ translate('optional') }})</small></label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="home_popup_text">
                                <textarea name="home_popup_text" rows="3" class="form-control">{{ get_setting('home_popup_text') }}</textarea>
                                <small class="text-muted">{{ translate('Leave title and text empty to show the image only. Fill them in to show the image on the left and the text with the button on the right.') }}</small>
                            </div>
                        </div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
    				</form>
    			</div>
    		</div>
            <div class="card">
    			<div class="card-header">
    				<h6 class="fw-600 mb-0">{{ translate('Website Popup') }}</h6>
    			</div>
    			<div class="card-body">
    				<form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
    					@csrf
    					<div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Show website popup?')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_website_popup">
                                    <input type="checkbox" name="show_website_popup" @if( get_setting('show_website_popup') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Popup content') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="website_popup_content">
                                <textarea name="website_popup_content" rows="4" class="aiz-text-editor form-control" >{{ get_setting('website_popup_content') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Show Subscriber form?')}}</label>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="hidden" name="types[]" value="show_subscribe_form">
                                    <input type="checkbox" name="show_subscribe_form" @if( get_setting('show_subscribe_form') == 'on') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
    					<div class="text-right">
    						<button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
    					</div>
    				</form>
    			</div>
    		</div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Custom Script') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Header custom script - before </head>') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="header_script">
                                <textarea name="header_script" rows="4" class="form-control" placeholder="<script>&#10;...&#10;</script>">{{ get_setting('header_script') }}</textarea>
                                <small>{{ translate('Write script with <script> tag') }}</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Footer custom script - before </body>') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="footer_script">
                                <textarea name="footer_script" rows="4" class="form-control" placeholder="<script>&#10;...&#10;</script>">{{ get_setting('footer_script') }}</textarea>
                                <small>{{ translate('Write script with <script> tag') }}</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h6 class="fw-600 mb-0">{{ translate('Custom CSS') }}</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{ translate('Custom CSS') }}</label>
                            <div class="col-md-8">
                                <input type="hidden" name="types[]" value="custom_css">
                                <textarea name="custom_css" rows="10" class="form-control" placeholder="/* Write your custom CSS here. It will be loaded inside a <style> tag on the frontend. */">{{ get_setting('custom_css') }}</textarea>
                                <small>{{ translate('Write CSS directly without wrapping it in <style> tags. Supports media queries.') }}</small>
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
    	</div>
    </div>

@endsection
