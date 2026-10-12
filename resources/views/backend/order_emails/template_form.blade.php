@extends('backend.layouts.app')

@section('content')
    @php
        $isNew = !$editing;
        // New templates start with example content so it is clear how one is written
        $formName = old('name', $editing->name ?? '');
        $formSubject = old('subject', $editing->subject ?? $example['subject']);
        $formBody = old('body', $editing->body ?? $example['body']);
        $formService = old('checkout_service_id', $editing->checkout_service_id ?? optional($filterService)->id);
    @endphp

    <div class="set-screen">
        <div class="set-hero">
            <div>
                <a href="{{ route('order-emails.templates') }}" class="set-back"><i class="las la-arrow-left"></i> {{ translate('Back to templates') }}</a>
                <h1>{{ $editing ? translate('Edit template') : translate('New template') }}</h1>
                <p>{{ translate('Write the email once. Placeholders are replaced with each order\'s details when it is sent.') }}</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="row gutters-10">
            {{-- Form --}}
            <div class="col-lg-8">
                <div class="set-card">
                    <div class="set-card-head">
                        <div>
                            <h2>{{ translate('Template details') }}</h2>
                            <p>
                                @if ($isNew)
                                    {{ translate('The subject and message below are an example. Change them to suit the service, then save.') }}
                                @else
                                    {{ translate('Changes apply the next time this template is used. Emails already sent are not changed.') }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <form method="POST"
                        action="{{ $editing ? route('order-emails.templates.update', $editing->id) : route('order-emails.templates.store') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="set-label">{{ translate('Template name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required value="{{ $formName }}"
                                    placeholder="{{ translate('e.g.') }} {{ $example['name'] }}">
                                <small class="text-muted">{{ translate('Only staff see this name.') }}</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="set-label">{{ translate('Service') }}</label>
                                <select name="checkout_service_id" class="form-control aiz-selectpicker" data-live-search="true">
                                    <option value="">{{ translate('General – can be used for any order') }}</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}" @selected($formService == $service->id)>
                                            {{ $service->name }} ({{ single_price($service->price) }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">{{ translate('Services with the same name share the template.') }}</small>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="set-label">{{ translate('Email subject') }} <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="template-subject" class="form-control" required value="{{ $formSubject }}">
                        </div>

                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <label class="set-label mb-0">{{ translate('Email message') }} <span class="text-danger">*</span></label>
                                @if ($isNew)
                                    <button type="button" class="btn btn-link btn-sm p-0" id="template-clear-example">{{ translate('Clear the example and start empty') }}</button>
                                @endif
                            </div>
                            <textarea name="body" id="template-body" class="aiz-text-editor form-control" data-min-height="320"
                                data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["insert", ["link", "picture"]],["view", ["undo","redo","codeview"]]]'>{!! $formBody !!}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="set-label">{{ translate('Default From address') }}</label>
                                <select name="from_email" class="form-control aiz-selectpicker">
                                    <option value="">{{ translate('Choose when sending') }}</option>
                                    @foreach ($fromOptions as $option)
                                        <option value="{{ $option['email'] }}" @selected(old('from_email', optional($editing)->from_email) == $option['email'])>{{ $option['email'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="set-label">{{ translate('From name') }}</label>
                                <input type="text" name="from_name" class="form-control"
                                    value="{{ old('from_name', optional($editing)->from_name) }}"
                                    placeholder="{{ translate('e.g. TTF Delivery Team') }}">
                            </div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;">
                            <div class="d-flex align-items-center">
                                <label class="aiz-switch aiz-switch-success mb-0 mr-2">
                                    <input type="checkbox" name="status" value="1" @checked(old('status', $editing ? $editing->status : 1))>
                                    <span></span>
                                </label>
                                <span>{{ translate('Active – available when sending') }}</span>
                            </div>
                            <div class="d-flex flex-wrap" style="gap: 8px;">
                                <a href="{{ route('order-emails.templates') }}" class="btn set-btn">{{ translate('Cancel') }}</a>
                                <button type="button" class="btn set-btn" id="template-preview-btn"><i class="las la-eye"></i> {{ translate('Preview') }}</button>
                                <button type="submit" class="btn set-btn set-btn-primary px-4">
                                    {{ $editing ? translate('Update template') : translate('Save template') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Guide --}}
            <div class="col-lg-4">
                <div class="set-card">
                    <div class="set-card-head">
                        <div><h2>{{ translate('How it works') }}</h2></div>
                    </div>
                    <ol class="set-steps">
                        <li><strong>{{ translate('Create a template') }}</strong> {{ translate('and link it to a service.') }}</li>
                        <li><strong>{{ translate('Open a paid order') }}</strong> {{ translate('in All Orders and click Send Email.') }}</li>
                        <li><strong>{{ translate('The template is filled in') }}</strong> {{ translate('with that order\'s details. Edit if needed.') }}</li>
                        <li><strong>{{ translate('Send') }}</strong> {{ translate('– the email is saved in the order\'s history.') }}</li>
                    </ol>
                </div>

                <div class="set-card">
                    <div class="set-card-head">
                        <div>
                            <h2>{{ translate('Placeholders') }}</h2>
                            <p>{{ translate('Click one to add it to the message. It is replaced with the real value when the email is sent.') }}</p>
                        </div>
                    </div>
                    <div class="set-placeholders">
                        @foreach ($placeholders as $key => $label)
                            <button type="button" class="set-placeholder" data-placeholder="{{ $key }}">
                                <code>{{ $key }}</code>
                                <span>{{ translate($label) }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('backend.order_emails._styles')
@endsection

@section('modal')

    <div class="modal fade" id="template-preview-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('Email preview') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="set-preview-meta">
                        <div><span>{{ translate('Subject') }}:</span> <strong id="template-preview-subject"></strong></div>
                        <div class="mt-1"><span>{{ translate('Shown with sample order details. The real order\'s details are used when sending.') }}</span></div>
                    </div>
                    <iframe id="template-preview-frame" class="set-preview-frame" sandbox></iframe>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            // Click a placeholder to insert it where the cursor is in the message
            $('.set-placeholder').on('click', function () {
                var $body = $('#template-body');
                $body.summernote('focus');
                $body.summernote('insertText', this.getAttribute('data-placeholder'));
            });

            $('#template-clear-example').on('click', function () {
                $('#template-body').summernote('code', '');
                document.getElementById('template-subject').value = '';
                document.getElementById('template-subject').focus();
            });

            // Preview what is currently in the form (nothing is saved)
            $('#template-preview-btn').on('click', function () {
                var frame = document.getElementById('template-preview-frame');
                frame.srcdoc = '';
                $('#template-preview-subject').text('…');
                $('#template-preview-modal').modal('show');

                $.post('{{ route('order-emails.templates.preview.form') }}', {
                    _token: '{{ csrf_token() }}',
                    subject: document.getElementById('template-subject').value,
                    body: $('#template-body').summernote('code'),
                    checkout_service_id: $('[name="checkout_service_id"]').val()
                }, function (data) {
                    $('#template-preview-subject').text(data.subject);
                    frame.srcdoc = data.html;
                });
            });
        })();
    </script>
@endsection
