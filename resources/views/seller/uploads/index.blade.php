@extends('seller.layouts.app')

@section('panel_content')
<style>
    .seller-uploads-page {
        --uploads-accent: #685b4e;
        --uploads-accent-dark: #51463c;
        --uploads-border: rgba(104, 91, 78, 0.22);
        --uploads-soft: #faf7f2;
        --uploads-soft-2: #f5f0ea;
        --uploads-muted: #756a60;
        color: var(--brown);
        padding-top: 16px;
    }

    .seller-uploads-page .uploads-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        margin: 0 0 16px;
        background: var(--white);
        border: 1px solid var(--uploads-border);
        border-radius: 8px;
        box-shadow: 0 8px 22px rgba(104, 91, 78, 0.08);
    }

    .seller-uploads-page .uploads-eyebrow {
        margin-bottom: 6px;
        color: var(--uploads-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .seller-uploads-page .uploads-title {
        margin: 0;
        color: var(--brown);
        font-size: 24px;
        font-weight: 700;
    }

    .seller-uploads-page .uploads-subtitle {
        margin: 6px 0 0;
        color: var(--uploads-muted);
        font-size: 13px;
    }

    .seller-uploads-page .uploads-card {
        background: var(--white);
        border: 1px solid var(--uploads-border);
        border-radius: 8px;
        box-shadow: 0 10px 24px rgba(104, 91, 78, 0.07);
        overflow: hidden;
    }

    .seller-uploads-page .uploads-toolbar {
        display: grid;
        grid-template-columns: minmax(150px, 1fr) 180px 220px minmax(220px, 1fr) 132px;
        gap: 12px;
        align-items: center;
        padding: 16px 20px;
        background: var(--white);
        border-bottom: 1px solid var(--uploads-border);
    }

    .seller-uploads-page .uploads-toolbar-title {
        margin: 0;
        color: var(--brown);
        font-size: 17px;
        font-weight: 700;
    }

    .seller-uploads-page .form-control,
    .seller-uploads-page .btn,
    .seller-uploads-page .dropdown-menu {
        border-radius: 6px;
    }

    .seller-uploads-page .form-control {
        height: 44px;
        padding: 9px 14px;
        color: var(--brown);
        border-color: var(--uploads-border);
        font-size: 14px;
        font-weight: 600;
    }

    .seller-uploads-page .form-control:focus {
        border-color: var(--uploads-accent);
        box-shadow: 0 0 0 .18rem rgba(104, 91, 78, 0.14);
    }

    .seller-uploads-page .btn-primary,
    .seller-uploads-page .btn-primary:hover,
    .seller-uploads-page .btn-primary:focus,
    .seller-uploads-page .btn-primary:active {
        height: 44px;
        padding: 9px 18px;
        color: #fff;
        background: #685b4e !important;
        border-color: #685b4e !important;
        font-size: 14px;
        font-weight: 700;
        box-shadow: none;
    }

    .seller-uploads-page .btn-primary:hover,
    .seller-uploads-page .btn-primary:focus {
        background: var(--uploads-accent-dark) !important;
        border-color: var(--uploads-accent-dark) !important;
    }

    .seller-uploads-page .uploads-control,
    .seller-uploads-page .btn-theme-outline,
    .seller-uploads-page .bootstrap-select > .dropdown-toggle {
        height: 44px;
        min-height: 44px;
        padding: 9px 14px;
        color: #685b4e !important;
        background: var(--white);
        border: 1px solid var(--uploads-border) !important;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 700;
        box-shadow: none !important;
    }

    .seller-uploads-page .bootstrap-select {
        width: 100% !important;
        height: 44px !important;
    }

    .seller-uploads-page .bootstrap-select.form-control,
    .seller-uploads-page .bootstrap-select.uploads-control {
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    .seller-uploads-page .bootstrap-select .dropdown-toggle {
        display: flex;
        align-items: center;
        width: 100%;
        margin: 0 !important;
    }

    .seller-uploads-page .bootstrap-select .filter-option {
        position: static;
        display: flex;
        align-items: center;
        width: 100%;
        height: auto;
        padding: 0;
    }

    .seller-uploads-page .bootstrap-select .filter-option-inner {
        width: 100%;
    }

    .seller-uploads-page .bootstrap-select .dropdown-toggle::after {
        margin-left: auto;
    }

    .seller-uploads-page .btn-theme-outline:hover,
    .seller-uploads-page .btn-theme-outline:focus,
    .seller-uploads-page .btn-theme-outline:active,
    .seller-uploads-page .btn-theme-outline[aria-expanded="true"],
    .seller-uploads-page .bootstrap-select > .dropdown-toggle:hover,
    .seller-uploads-page .bootstrap-select > .dropdown-toggle:focus,
    .seller-uploads-page .bootstrap-select > .dropdown-toggle:active,
    .seller-uploads-page .bootstrap-select.show > .dropdown-toggle {
        color: #685b4e !important;
        background: var(--white) !important;
        border-color: #685b4e !important;
        justify-content: space-between !important;
    }

    .seller-uploads-page .bootstrap-select .dropdown-toggle .filter-option-inner-inner {
        color: #685b4e !important;
        line-height: 1.4;
    }

    .seller-uploads-page .bootstrap-select .dropdown-toggle::after,
    .seller-uploads-page .btn-theme-outline.dropdown-toggle::after {
        color: #685b4e !important;
        border-top-color: #685b4e !important;
    }

    .seller-uploads-page .dropdown-menu {
        padding: 8px;
        background: #fff !important;
        border: 1px solid var(--uploads-border);
        box-shadow: 0 16px 36px rgba(104, 91, 78, 0.18);
    }

    .seller-uploads-page .dropdown-item {
        padding: 10px 12px;
        color: var(--uploads-muted) !important;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
    }

    .seller-uploads-page .dropdown-item:hover,
    .seller-uploads-page .dropdown-item:focus,
    .seller-uploads-page .dropdown-item.active {
        color: #685b4e !important;
        background: var(--uploads-soft-2) !important;
    }

    .seller-uploads-page .dropdown-item:hover i,
    .seller-uploads-page .dropdown-item:focus i,
    .seller-uploads-page .dropdown-item.active i,
    .seller-uploads-page .dropdown-item:hover span,
    .seller-uploads-page .dropdown-item:focus span,
    .seller-uploads-page .dropdown-item.active span {
        color: #685b4e !important;
    }

    .seller-uploads-page .uploads-select-all {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 13px 16px;
        margin-bottom: 18px;
        background: var(--uploads-soft);
        border: 1px solid var(--uploads-border);
        border-radius: 8px;
    }

    .seller-uploads-page .uploads-select-all label {
        color: var(--brown);
        font-size: 14px;
        font-weight: 700;
    }

    .seller-uploads-page .uploads-select-all .text-muted {
        color: var(--uploads-muted) !important;
        font-size: 13px;
        font-weight: 600;
    }

    .seller-uploads-page .uploads-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 16px;
    }

    .seller-uploads-page .upload-item {
        min-width: 0;
    }

    .seller-uploads-page .upload-file-box {
        position: relative;
        height: 100%;
    }

    .seller-uploads-page .dropdown-file {
        top: 12px;
        right: 12px;
        z-index: 20;
    }

    .seller-uploads-page .dropdown-file .dropdown-menu {
        z-index: 1100;
        min-width: 180px;
        margin-top: 8px;
    }

    .seller-uploads-page .dropdown-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        color: #685b4e !important;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(104, 91, 78, 0.14);
        border-radius: 6px;
        box-shadow: 0 6px 16px rgba(104, 91, 78, 0.12);
    }

    .seller-uploads-page .dropdown-link:hover,
    .seller-uploads-page .dropdown-link:focus {
        color: #685b4e !important;
        background: var(--uploads-soft-2);
    }

    .seller-uploads-page .select-box {
        top: 12px;
        left: 12px;
        z-index: 2;
    }

    .seller-uploads-page .card-file {
        height: 100%;
        margin: 0;
        border: 1px solid var(--uploads-border);
        border-radius: 8px;
        box-shadow: none;
        overflow: hidden;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .seller-uploads-page .card-file:hover {
        border-color: rgba(104, 91, 78, 0.36);
        box-shadow: 0 14px 28px rgba(104, 91, 78, 0.13);
        transform: translateY(-2px);
    }

    .seller-uploads-page .card-file-thumb {
        display: flex;
        align-items: center;
        justify-content: center;
        aspect-ratio: 4 / 3;
        height: auto;
        min-height: 142px;
        background: var(--uploads-soft);
        border-bottom: 1px solid var(--uploads-border);
        overflow: hidden;
    }

    .seller-uploads-page .card-file-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .seller-uploads-page .card-file-thumb i {
        color: var(--uploads-accent);
        font-size: 48px;
    }

    .seller-uploads-page .broken-image-fallback {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        padding: 16px;
        color: var(--uploads-accent);
        font-size: 12px;
        font-weight: 600;
        line-height: 1.35;
        text-align: center;
        word-break: break-word;
    }

    .seller-uploads-page .broken-image-fallback .fallback-title {
        color: var(--uploads-accent);
        font-size: 13px;
        font-weight: 800;
    }

    .seller-uploads-page .broken-image-fallback .fallback-name {
        color: var(--uploads-muted);
        display: -webkit-box;
        overflow: hidden;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .seller-uploads-page .card-file .card-body {
        padding: 11px 13px 13px;
    }

    .seller-uploads-page .file-name {
        min-width: 0;
        margin: 0;
        color: #39322a;
        font-size: 13px;
        font-weight: 700;
    }

    .seller-uploads-page .file-meta {
        margin: 6px 0 0;
        color: #8a7f73;
        font-size: 12px;
        font-weight: 600;
    }

    .seller-uploads-page .uploads-empty {
        padding: 34px 18px;
        color: var(--uploads-muted);
        background: var(--uploads-soft);
        border: 1px dashed var(--uploads-border);
        border-radius: 8px;
        text-align: center;
    }

    #info-modal.seller-uploads-page .modal-content {
        border: 0;
        border-radius: 8px;
        overflow: hidden;
    }

    #info-modal.seller-uploads-page .modal-header {
        background: var(--uploads-soft);
        border-bottom: 1px solid var(--uploads-border);
    }

    #info-modal.seller-uploads-page .modal-title {
        color: var(--uploads-accent);
        font-weight: 700;
    }

    @media (max-width: 1199.98px) {
        .seller-uploads-page .uploads-toolbar {
            grid-template-columns: 1fr 1fr;
        }

        .seller-uploads-page .uploads-toolbar-heading,
        .seller-uploads-page .uploads-search-action {
            grid-column: auto;
        }
    }

    @media (max-width: 767.98px) {
        .seller-uploads-page .uploads-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 14px;
        }

        .seller-uploads-page .uploads-header .btn {
            width: 100%;
        }

        .seller-uploads-page .uploads-title {
            font-size: 21px;
        }

        .seller-uploads-page .uploads-toolbar {
            grid-template-columns: 1fr;
            padding: 14px;
        }

        .seller-uploads-page .uploads-toolbar .btn,
        .seller-uploads-page .uploads-toolbar .dropdown,
        .seller-uploads-page .uploads-toolbar .bootstrap-select {
            width: 100% !important;
            justify-content: space-between;
        }

        .seller-uploads-page .uploads-search-action .btn {
            width: 100%;
        }

        .seller-uploads-page .uploads-select-all {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

        .seller-uploads-page .uploads-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .seller-uploads-page .card-file-thumb {
            min-height: 112px;
        }

        .seller-uploads-page .file-name {
            font-size: 12px;
        }

        .seller-uploads-page .uploads-subtitle {
            display: none;
        }
    }

    @media (max-width: 420px) {
        .seller-uploads-page .uploads-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="seller-uploads-page">
<div class="uploads-header">
    <div>
        <div class="uploads-eyebrow">{{ translate('Seller Files') }}</div>
        <h1 class="uploads-title">{{translate('All uploaded files')}}</h1>
        <p class="uploads-subtitle">{{ translate('Manage images, documents and media used across your seller panel.') }}</p>
    </div>
    <a href="{{ route('seller.uploads.create') }}" class="btn btn-primary">
        <i class="las la-plus mr-1"></i>
        <span>{{translate('Upload New File')}}</span>
    </a>
</div>

<div class="card uploads-card">
    <form id="sort_uploads" action="">
        <div class="uploads-toolbar">
            <div class="uploads-toolbar-heading">
                <h5 class="uploads-toolbar-title">{{translate('All files')}} ({{ $all_uploads->total() }})</h5>
            </div>
            <div class="dropdown">
                <button class="btn btn-theme-outline dropdown-toggle" type="button" data-toggle="dropdown">
                    {{translate('Bulk Action')}}
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item confirm-alert" href="javascript:void(0)"  data-target="#bulk-delete-modal"> {{translate('Delete selection')}}</a>
                </div>
            </div>
            <div>
                <select class="form-control form-control-xs aiz-selectpicker uploads-control" name="sort" onchange="sort_uploads()">
                    <option value="newest" @if($sort_by == 'newest') selected="" @endif>{{ translate('Sort by newest') }}</option>
                    <option value="oldest" @if($sort_by == 'oldest') selected="" @endif>{{ translate('Sort by oldest') }}</option>
                    <option value="smallest" @if($sort_by == 'smallest') selected="" @endif>{{ translate('Sort by smallest') }}</option>
                    <option value="largest" @if($sort_by == 'largest') selected="" @endif>{{ translate('Sort by largest') }}</option>
                </select>
            </div>
            <div>
                <input type="text" class="form-control form-control-xs uploads-control" name="search" placeholder="{{ translate('Search your files') }}" value="{{ $search }}">
            </div>
            <div class="uploads-search-action">
                <button type="submit" class="btn btn-primary">{{ translate('Search') }}</button>
            </div>
        </div>
    
		<div class="card-body">
			<div class="uploads-select-all">
				<div class="aiz-checkbox-inline">
					<label class="aiz-checkbox">
						{{ translate('Select All')}}
						<input type="checkbox" class="check-all">
						<span class="aiz-square-check"></span>
					</label>
				</div>
                <span class="text-muted">{{ translate('Choose files before using bulk actions.') }}</span>
			</div>

			@if($all_uploads->count())
			<div class="uploads-grid">
				@foreach($all_uploads as $key => $file)
					@php
						if($file->file_original_name == null){
							$file_name = translate('Unknown');
						}else{
							$file_name = $file->file_original_name;
						}
					@endphp
					<div class="upload-item">
						<div class="aiz-file-box upload-file-box">
							<div class="dropdown-file" >
								<a class="dropdown-link" data-toggle="dropdown">
									<i class="la la-ellipsis-v"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-right">
									<a href="javascript:void(0)" class="dropdown-item" onclick="detailsInfo(this)" data-id="{{ $file->id }}">
										<i class="las la-info-circle mr-2"></i>
										<span>{{ translate('Details Info') }}</span>
									</a>
									<a href="{{ my_asset($file->file_name) }}" target="_blank" download="{{ $file_name }}.{{ $file->extension }}" class="dropdown-item">
										<i class="la la-download mr-2"></i>
										<span>{{ translate('Download') }}</span>
									</a>
									<a href="javascript:void(0)" class="dropdown-item" onclick="copyUrl(this)" data-url="{{ my_asset($file->file_name) }}">
										<i class="las la-clipboard mr-2"></i>
										<span>{{ translate('Copy Link') }}</span>
									</a>
									<a href="javascript:void(0)" class="dropdown-item confirm-delete" data-href="{{ route('seller.my_uploads.destroy', $file->id ) }}" data-target="#delete-modal">
										<i class="las la-trash mr-2"></i>
										<span>{{ translate('Delete') }}</span>
									</a>
								</div>
							</div>

							<div class="select-box">
								<div class="aiz-checkbox-inline">
									<label class="aiz-checkbox">
										<input type="checkbox" class="check-one" name="id[]" value="{{$file->id}}">
										<span class="aiz-square-check"></span>
									</label>
								</div>
							</div>

							<div class="card card-file aiz-uploader-select c-default" title="{{ $file_name }}.{{ $file->extension }}">
								<div class="card-file-thumb">
									@if($file->type == 'image')
										<img src="{{ my_asset($file->file_name) }}" alt="{{ $file_name }}" onerror="this.classList.add('d-none'); this.nextElementSibling.classList.remove('d-none');">
                                        <div class="broken-image-fallback d-none">
                                            <span class="fallback-title">{{ translate('Preview unavailable') }}</span>
                                        </div>
									@elseif($file->type == 'video')
										<i class="las la-file-video"></i>
									@else
										<i class="las la-file"></i>
									@endif
								</div>
								<div class="card-body" style="bottom:-5px;">
									<h6 class="d-flex file-name">
										<span class="text-truncate title">{{ $file_name }}</span>
										<span class="ext">.{{ $file->extension }}</span>
									</h6>
									<p class="file-meta">{{ formatBytes($file->file_size) }}</p>
								</div>
							</div>
						</div>
					</div>
				@endforeach
			</div>
            @else
                <div class="uploads-empty">
                    {{ translate('No files found.') }}
                </div>
            @endif
			<div class="aiz-pagination mt-3">
				{{ $all_uploads->appends(request()->input())->links() }}
			</div>
		</div>
	</form>
</div>
</div>
@endsection
@section('modal')
<div id="info-modal" class="modal fade seller-uploads-page">
	<div class="modal-dialog modal-dialog-right">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title h6">{{ translate('File Info') }}</h5>
				<button type="button" class="close" data-dismiss="modal">
				</button>
			</div>
			<div class="modal-body c-scrollbar-light position-relative" id="info-modal-content">
				<div class="c-preloader text-center absolute-center">
                    <i class="las la-spinner la-spin la-3x opacity-70"></i>
                </div>
			</div>
		</div>
	</div>
</div>
<!-- Delete modal -->
@include('modals.delete_modal')
<!-- Bulk Delete modal -->
@include('modals.bulk_delete_modal')

@endsection
@section('script')
	<script type="text/javascript">
		function detailsInfo(e){
            $('#info-modal-content').html('<div class="c-preloader text-center absolute-center"><i class="las la-spinner la-spin la-3x opacity-70"></i></div>');
			var id = $(e).data('id')
			$('#info-modal').modal('show');
			$.post('{{ route('seller.my_uploads.info') }}', {_token: AIZ.data.csrf, id:id}, function(data){
                $('#info-modal-content').html(data);
				// console.log(data);
			});
		}
		function copyUrl(e) {
			var url = $(e).data('url');
			var $temp = $("<input>");
		    $("body").append($temp);
		    $temp.val(url).select();
		    try {
			    document.execCommand("copy");
			    AIZ.plugins.notify('success', '{{ translate('Link copied to clipboard') }}');
			} catch (err) {
			    AIZ.plugins.notify('danger', '{{ translate('Oops, unable to copy') }}');
			}
		    $temp.remove();
		}
        function sort_uploads(el){
            $('#sort_uploads').submit();
        }

		$(document).on("change", ".check-all", function() {
			if(this.checked) {
				// Iterate each checkbox
				$('.check-one:checkbox').each(function() {
					this.checked = true;
				});
			} else {
				$('.check-one:checkbox').each(function() {
					this.checked = false;
				});
			}
		});

		function bulk_delete() {
            var data = new FormData($('#sort_uploads')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('seller.bulk-uploaded-files-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response == 1) {
						location.reload();
                    }
					else{
						AIZ.plugins.notify('danger', '{{ translate('Something Went Wrong.') }}');
					}
                }
            });
        }
	</script>
@endsection
