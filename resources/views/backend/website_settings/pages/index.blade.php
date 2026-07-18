@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Website Pages') }}</h1>
		</div>
	</div>
</div>


<div class="card mt-4">
	<div class="card-header">
		<h6 class="mb-0 fw-600">{{ translate('Policy Pages Configuration') }}</h6>
	</div>
	<div class="card-body">
		<form action="{{ route('website.policy-pages.update') }}" method="POST">
			@csrf
			<div class="row">
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Return Policy Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="return_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('return_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Support Policy Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="support_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('support_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Seller Terms & Conditions') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="seller_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('seller_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Privacy Policy Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="privacy_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('privacy_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Delivery Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="delivery_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('delivery_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Disclaimer Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="disclaimer_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('disclaimer_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Cookie Policy Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="cookie_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('cookie_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Customer Terms Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="customer_terms_policy_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('customer_terms_policy_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-md-4 col-form-label">{{ translate('Terms & Conditions Page') }}</label>
						<div class="col-md-8">
							<select class="form-control aiz-selectpicker" name="terms_conditions_page_id" data-live-search="true">
								<option value="">-- {{ translate('Select Page') }} --</option>
								@foreach ($all_pages as $p)
									<option value="{{ $p->id }}" @selected(get_setting('terms_conditions_page_id') == $p->id)>{{ $p->getTranslation('title') }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
			</div>
			<div class="text-right mt-3">
				<button type="submit" class="btn btn-primary">{{ translate('Save Configurations') }}</button>
			</div>
		</form>
	</div>
</div>

<div class="card">
	@can('add_website_page')
		<div class="card-header">
			<h6 class="mb-0 fw-600">{{ translate('All Pages') }}</h6>
				<div class="d-flex flex-wrap gap-2 justify-content-end">
					<button type="button" class="btn btn-circle btn-primary" data-toggle="modal" data-target="#import-page-modal">
						<i class="las la-upload mr-1"></i>{{ translate('Import Page') }}
					</button>
					<a href="{{ route('custom-pages.create') }}" class="btn btn-circle btn-info">{{ translate('Add New Page') }}</a>
					<a href="{{ route('team-members.index') }}" class="btn btn-circle btn-success">{{ translate('Manage Team Members') }}</a>
				</div>
		</div>
	@endcan

	
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg">#</th>
                <th>{{translate('Name')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        	@foreach ($page as $key => $page)
        	<tr>
        		<td>{{ $key+1 }}</td>
				<td class="title-cell"><a href="{{ route('custom-pages.show_custom_page', $page->slug) }}" class="text-reset page-title-text">{{ $page->getTranslation('title') }}</a></td>
				<td class="slug-cell" data-page-id="{{ $page->id }}">
					<div class="d-flex align-items-center justify-content-between" style="max-width: 320px;">
						<span class="slug-text text-truncate">{{ route('home') }}/<span class="current-slug fw-600 text-primary">{{ $page->slug }}</span></span>
						<button type="button" class="btn btn-xs btn-icon btn-circle btn-soft-secondary edit-slug-btn" title="{{ translate('Edit Title & Slug') }}">
							<i class="las la-pen"></i>
						</button>
					</div>
					<div class="slug-edit-form d-none mt-1">
						<div class="form-group mb-2">
							<input type="text" class="form-control form-control-sm title-input" value="{{ $page->getTranslation('title') }}" placeholder="{{ translate('Title') }}">
						</div>
						<div class="input-group input-group-sm">
							<input type="text" class="form-control slug-input" value="{{ $page->slug }}" placeholder="{{ translate('Slug') }}">
							<div class="input-group-append">
								<button type="button" class="btn btn-success save-slug-btn"><i class="las la-check"></i></button>
								<button type="button" class="btn btn-light cancel-slug-btn"><i class="las la-times"></i></button>
							</div>
						</div>
					</div>
				</td>
        		<td class="text-right">
					@can('edit_website_page')
						@if($page->type == 'home_page')
							<a href="{{route('custom-pages.edit', ['id'=>$page->slug, 'lang'=>env('DEFAULT_LANGUAGE'), 'page'=>'home'] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
								<i class="las la-pen"></i>
							</a>
						@else
							<a href="{{route('custom-pages.edit', ['id'=>$page->slug, 'lang'=>env('DEFAULT_LANGUAGE')] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
								<i class="las la-pen"></i>
							</a>
							<a href="{{ route('custom-pages.export', $page->id) }}" class="btn btn-icon btn-circle btn-sm btn-soft-success" title="{{ translate('Export Page') }}">
								<i class="las la-download"></i>
							</a>
						@endif
					@endcan
					@if($page->type != 'home_page' && auth()->user()->can('delete_website_page'))
          				<a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('custom-pages.destroy', $page->id)}} " title="{{ translate('Delete') }}">
          					<i class="las la-trash"></i>
          				</a>
					@endif
        		</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>


@endsection

@section('modal')
    @include('modals.delete_modal')

    <!-- Import Page Modal -->
    <div id="import-page-modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('Import Custom Page') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                </div>
                <form action="{{ route('custom-pages.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="import_file" class="font-weight-medium">{{ translate('Select JSON File') }}</label>
                            <input type="file" name="import_file" id="import_file" class="form-control-file" accept=".json,.txt" required>
                            <small class="form-text text-muted mt-2">
                                {{ translate('Upload the .json custom page file that was previously exported. If the page slug matches an existing page, a unique slug will be automatically generated to avoid overwriting.') }}
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">{{ translate('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Toggle Edit Form using event delegation
        $(document).on('click', '.edit-slug-btn', function() {
            var cell = $(this).closest('.slug-cell');
            cell.find('.d-flex').addClass('d-none');
            cell.find('.slug-edit-form').removeClass('d-none');
            cell.find('.title-input').focus();
        });

        // Cancel Edit using event delegation
        $(document).on('click', '.cancel-slug-btn', function() {
            var cell = $(this).closest('.slug-cell');
            cell.find('.slug-edit-form').addClass('d-none');
            cell.find('.d-flex').removeClass('d-none');
        });

        // Save Slug using event delegation
        $(document).on('click', '.save-slug-btn', function() {
            var btn = $(this);
            var cell = btn.closest('.slug-cell');
            var id = cell.data('page-id');
            var slug = cell.find('.slug-input').val();
            var title = cell.find('.title-input').val();

            btn.prop('disabled', true);

            $.ajax({
                url: "{{ route('custom-pages.update-slug') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    slug: slug,
                    title: title
                },
                success: function(response) {
                    btn.prop('disabled', false);
                    if (response.success) {
                        cell.find('.current-slug').text(response.slug);
                        cell.find('.slug-input').val(response.slug);
                        cell.find('.title-input').val(response.title);
                        
                        // Update title in the title column
                        var row = cell.closest('tr');
                        var titleLink = row.find('.page-title-text');
                        titleLink.text(response.title);
                        
                        // Update title link URL since slug might have changed
                        var newUrl = "{{ route('custom-pages.show_custom_page', ':slug') }}".replace(':slug', response.slug);
                        titleLink.attr('href', newUrl);

                        cell.find('.slug-edit-form').addClass('d-none');
                        cell.find('.d-flex').removeClass('d-none');
                        AIZ.plugins.notify('success', response.message);
                    } else {
                        AIZ.plugins.notify('danger', response.message || "{{ translate('Something went wrong') }}");
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false);
                    var msg = "{{ translate('Something went wrong') }}";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    AIZ.plugins.notify('danger', msg);
                }
            });
        });
    });
</script>
@endsection
