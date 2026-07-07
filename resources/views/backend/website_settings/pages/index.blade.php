@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Website Pages') }}</h1>
		</div>
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
				<td><a href="{{ route('custom-pages.show_custom_page', $page->slug) }}" class="text-reset">{{ $page->getTranslation('title') }}</a></td>
				<td>{{ route('home') }}/{{ $page->slug }}</td>
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
					@if($page->type == 'custom_page' && auth()->user()->can('delete_website_page'))
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
