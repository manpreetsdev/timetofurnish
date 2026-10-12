@extends('backend.layouts.app')

@section('content')
    @php
        $address = json_decode((string) $order->shipping_address);
    @endphp
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
            <div>
                <h1 class="h3 mb-1">{{ translate('Service Email') }} – {{ $order->code }}</h1>
                <p class="mb-0 text-muted">
                    {{ $address->name ?? optional($order->user)->name }} &nbsp;|&nbsp; {{ date('d M Y', $order->date) }}
                    &nbsp;|&nbsp; {{ single_price($order->grand_total) }} &nbsp;|&nbsp;
                    @if ($order->payment_status === 'paid')
                        <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                    @else
                        <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('all_orders.index') }}" class="btn btn-soft-secondary btn-sm">{{ translate('All Orders') }}</a>
                <a href="{{ route('all_orders.show', encrypt($order->id)) }}" class="btn btn-soft-secondary btn-sm">{{ translate('Order details') }}</a>
                <a href="{{ route('order-emails.templates') }}" class="btn btn-soft-primary btn-sm">{{ translate('Manage templates') }}</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Send an email to the customer') }}</h5>
                </div>
                <div class="card-body">
                    @if (!$canSend)
                        <div class="alert alert-soft-danger mb-0">
                            {{ translate('This order is not paid yet. Service emails can only be sent for paid orders.') }}
                        </div>
                    @else
                        <form action="{{ route('order-emails.send', $order->id) }}" method="POST" id="service-email-form">
                            @csrf
                            <div class="form-group">
                                <label>{{ translate('Template') }}</label>
                                <select name="template_id" id="service-email-template" class="form-control aiz-selectpicker" data-live-search="true">
                                    <option value="">{{ translate('No template – write the email myself') }}</option>
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}" @selected(old('template_id') == $template->id)>
                                            {{ $template->name }} —
                                            {{ $template->service ? $template->service->name : translate('General') }}
                                            @if (!empty($rendered[$template->id]['matches']))
                                                ★ {{ translate('service in this order') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if ($templates->isEmpty())
                                    <small class="text-muted">{{ translate('No templates yet.') }}
                                        <a href="{{ route('order-emails.templates') }}">{{ translate('Create one') }}</a></small>
                                @endif
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>{{ translate('From') }} <span class="text-danger">*</span></label>
                                    <select name="from_email" id="service-email-from" class="form-control" required>
                                        @foreach ($fromOptions as $option)
                                            <option value="{{ $option['email'] }}" @selected(old('from_email') == $option['email'])>
                                                {{ $option['name'] }} &lt;{{ $option['email'] }}&gt;
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ translate('To') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="to" class="form-control" required value="{{ old('to', $customerEmail) }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>{{ translate('CC') }} <small class="text-muted">({{ translate('optional, separate with commas') }})</small></label>
                                <input type="text" name="cc" class="form-control" value="{{ old('cc') }}" placeholder="admin@timetofurnish.com">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Subject') }} <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="service-email-subject" class="form-control" required value="{{ old('subject') }}">
                            </div>
                            <div class="form-group">
                                <label>{{ translate('Message') }} <span class="text-danger">*</span></label>
                                <textarea name="body" id="service-email-body" class="aiz-text-editor form-control" data-min-height="300"
                                    data-buttons='[["font", ["bold", "underline", "italic"]],["para", ["ul", "ol"]],["insert", ["link", "picture"]],["view", ["undo","redo","codeview"]]]'>{!! old('body') !!}</textarea>
                                <small class="text-muted">{{ translate('The template is already filled with this order\'s details. You can edit anything before sending.') }}</small>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-primary" id="service-email-review">
                                    <i class="las la-paper-plane"></i> {{ translate('Send email') }}
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Services in this order') }}</h5>
                </div>
                <div class="card-body">
                    @forelse ($orderServices as $service)
                        <div class="mb-2 pb-2 border-bottom">
                            <strong>{{ $service['name'] }}</strong>
                            @if (!empty($service['product_name']))
                                <div class="text-muted fs-12">{{ $service['product_name'] }}</div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">{{ translate('No extra services were bought with this order. You can still send a general email.') }}</p>
                    @endforelse
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Products') }}</h5>
                </div>
                <div class="card-body">
                    @foreach ($order->orderDetails as $detail)
                        @if ($detail->product)
                            <div class="mb-1 fs-13">{{ $detail->product->name }} &times; {{ $detail->quantity }}</div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 h6">{{ translate('Email history for this order') }} ({{ $logs->count() }})</h5>
            <a href="{{ route('order-emails.history') }}" class="btn btn-soft-secondary btn-sm">{{ translate('All email history') }}</a>
        </div>
        <div class="card-body">
            @include('backend.order_emails._history_table', ['logs' => $logs])
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade" id="service-email-confirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('Send this email?') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">{{ translate('Please check the details below. The email is sent to the customer as soon as you confirm.') }}</p>
                    <table class="table table-sm table-borderless mb-0">
                        <tr><th width="90" class="text-muted fw-600">{{ translate('To') }}</th><td id="sec-to" style="word-break: break-word;"></td></tr>
                        <tr id="sec-cc-row"><th class="text-muted fw-600">{{ translate('CC') }}</th><td id="sec-cc" style="word-break: break-word;"></td></tr>
                        <tr><th class="text-muted fw-600">{{ translate('From') }}</th><td id="sec-from" style="word-break: break-word;"></td></tr>
                        <tr><th class="text-muted fw-600">{{ translate('Subject') }}</th><td id="sec-subject" class="fw-600" style="word-break: break-word;"></td></tr>
                        <tr><th class="text-muted fw-600">{{ translate('Order') }}</th><td>{{ $order->code }}</td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="service-email-confirm-send">
                        <i class="las la-paper-plane"></i> {{ translate('Yes, send email') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="service-email-template-confirm" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body text-center py-4">
                    <h6 class="mb-2">{{ translate('Replace your message?') }}</h6>
                    <p class="text-muted fs-13 mb-3">{{ translate('The current subject and message will be replaced with the selected template.') }}</p>
                    <button type="button" class="btn btn-soft-secondary btn-sm" data-dismiss="modal" id="service-email-template-keep">{{ translate('Keep mine') }}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="service-email-template-replace">{{ translate('Replace') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        (function () {
            var rendered = @json($rendered);
            var select = document.getElementById('service-email-template');
            if (!select) return;

            function apply(force) {
                var data = rendered[select.value];
                if (!data) return;

                var subject = document.getElementById('service-email-subject');
                var $body = $('#service-email-body');
                var hasContent = subject.value.trim() !== '' || $($body.summernote('code')).text().trim() !== '';

                if (!force && hasContent) {
                    $('#service-email-template-confirm').modal('show');
                    return;
                }

                subject.value = data.subject;
                $body.summernote('code', data.body);

                if (data.from_email) {
                    var from = document.getElementById('service-email-from');
                    Array.prototype.forEach.call(from.options, function (option) {
                        if (option.value.toLowerCase() === data.from_email.toLowerCase()) {
                            from.value = option.value;
                        }
                    });
                }
            }

            $(select).on('change', function () { apply(false); });
            $('#service-email-template-replace').on('click', function () {
                $('#service-email-template-confirm').modal('hide');
                apply(true);
            });

            // Confirmation popup before sending
            var form = document.getElementById('service-email-form');
            $('#service-email-review').on('click', function () {
                // Let the browser show its own "required field" hints first
                if (!form.reportValidity()) {
                    return;
                }
                if ($($('#service-email-body').summernote('code')).text().trim() === '' && $('#service-email-body').summernote('isEmpty')) {
                    AIZ.plugins.notify('danger', '{{ translate('Please write a message before sending.') }}');
                    return;
                }

                var from = document.getElementById('service-email-from');
                var cc = form.querySelector('[name="cc"]').value.trim();

                document.getElementById('sec-to').textContent = form.querySelector('[name="to"]').value;
                document.getElementById('sec-cc').textContent = cc;
                document.getElementById('sec-cc-row').style.display = cc ? '' : 'none';
                document.getElementById('sec-from').textContent = from.options[from.selectedIndex].text.trim();
                document.getElementById('sec-subject').textContent = document.getElementById('service-email-subject').value;

                $('#service-email-confirm').modal('show');
            });

            $('#service-email-confirm-send').on('click', function () {
                this.disabled = true;
                this.innerHTML = '{{ translate('Sending...') }}';
                form.submit();
            });

            // Pre-select the first template that belongs to a service in this order
            @if (!old('subject') && $templates->isNotEmpty())
                $(function () {
                    var preferred = @json(optional($templates->first(fn ($t) => !empty($rendered[$t->id]['matches'])))->id);
                    if (preferred) {
                        $(select).val(String(preferred));
                        if ($.fn.selectpicker) { $(select).selectpicker('refresh'); }
                        setTimeout(function () { apply(true); }, 300);
                    }
                });
            @endif
        })();
    </script>
@endsection
