@extends('backend.layouts.app')

@section('content')
    <div class="set-screen">
        {{-- Header --}}
        <div class="set-hero">
            <div>
                <span class="set-eyebrow">{{ translate('Sales') }}</span>
                <h1>{{ translate('Service Email Templates') }}</h1>
                <p>{{ translate('One email per service. When an order is paid, staff open it from All Orders, pick the template and send it to the customer.') }}</p>
            </div>
            <div class="set-hero-actions">
                <a href="{{ route('order-emails.templates.create', array_filter(['service' => optional($filterService)->id])) }}" class="btn set-btn set-btn-primary"><i class="las la-plus"></i> {{ translate('New template') }}</a>
            </div>
        </div>

        {{-- Summary --}}
        <div class="set-stats">
            <div class="set-stat"><span>{{ translate('Templates') }}</span><strong>{{ $stats['templates'] }}</strong></div>
            <div class="set-stat"><span>{{ translate('Active') }}</span><strong>{{ $stats['active'] }}</strong></div>
            <div class="set-stat"><span>{{ translate('Services') }}</span><strong>{{ $stats['services'] }}</strong></div>
            <div class="set-stat {{ $stats['uncovered'] > 0 ? 'is-warning' : '' }}">
                <span>{{ translate('Services without a template') }}</span><strong>{{ $stats['uncovered'] }}</strong>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        {{-- Templates list --}}
        <div class="set-card">
            <div class="set-card-head">
                <div>
                    <h2>{{ translate('Templates') }}</h2>
                    @if ($filterService)
                        <p>
                            {{ translate('Showing templates for') }} <strong>{{ $filterService->name }}</strong>
                            &nbsp;·&nbsp; <a href="{{ route('order-emails.templates') }}">{{ translate('Show all') }}</a>
                        </p>
                    @else
                        <p>{{ translate('A template linked to a service is offered first on orders that include that service.') }}</p>
                    @endif
                </div>
                <div class="set-hero-actions">
                    <form action="{{ route('order-emails.templates.generate') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn set-btn" title="{{ translate('Creates a ready-made template for every service that does not have one yet') }}">
                            <i class="las la-magic"></i> {{ translate('Create for all services') }}
                        </button>
                    </form>
                    <a href="{{ route('order-emails.templates.export') }}" class="btn set-btn"><i class="las la-download"></i> {{ translate('Export') }}</a>
                    <button type="button" class="btn set-btn" data-toggle="modal" data-target="#import-templates-modal"><i class="las la-upload"></i> {{ translate('Import') }}</button>
                    <button type="button" class="btn set-btn" data-toggle="modal" data-target="#from-addresses-modal"><i class="las la-at"></i> {{ translate('From addresses') }}</button>
                    <a href="{{ route('order-emails.history') }}" class="btn set-btn"><i class="las la-history"></i> {{ translate('Email history') }}</a>
                </div>
            </div>

            <x-admin.table min-width="900px">
                <thead>
                    <tr>
                        <th>{{ translate('Template') }}</th>
                        <th>{{ translate('Service') }}</th>
                        <th>{{ translate('Email subject') }}</th>
                        <th>{{ translate('From') }}</th>
                        <th>{{ translate('Status') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($templates as $template)
                        <tr>
                            <td class="ttf-wrap" style="max-width: 260px;"><strong>{{ $template->name }}</strong></td>
                            <td class="ttf-wrap" style="max-width: 240px;">
                                @if ($template->service)
                                    <span class="set-pill">{{ $template->service->name }}</span>
                                @else
                                    <span class="set-pill is-general">{{ translate('General – any order') }}</span>
                                @endif
                            </td>
                            <td class="ttf-wrap text-muted" style="max-width: 320px;">{{ $template->subject }}</td>
                            <td class="text-muted">{{ $template->from_email ?: translate('Chosen when sending') }}</td>
                            <td>
                                @if ($template->status)
                                    <span class="set-pill is-on">{{ translate('Active') }}</span>
                                @else
                                    <span class="set-pill is-off">{{ translate('Off') }}</span>
                                @endif
                            </td>
                            <td class="text-right">
                                <button type="button" class="btn btn-soft-info btn-icon btn-circle btn-sm js-template-preview"
                                    data-url="{{ route('order-emails.templates.preview', $template->id) }}" title="{{ translate('Preview') }}">
                                    <i class="las la-eye"></i>
                                </button>
                                <a href="{{ route('order-emails.templates.edit', $template->id) }}"
                                    class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <form action="{{ route('order-emails.templates.destroy', $template->id) }}" method="POST" class="d-inline"
                                    onsubmit="return confirm('{{ translate('Delete this template?') }}');">
                                    @csrf
                                    <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm" title="{{ translate('Delete') }}">
                                        <i class="las la-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted mb-2">
                                    {{ $filterService ? translate('No template for this service yet.') : translate('No templates yet.') }}
                                </div>
                                <a href="{{ route('order-emails.templates.create', array_filter(['service' => optional($filterService)->id])) }}" class="btn set-btn set-btn-primary"><i class="las la-plus"></i> {{ translate('New template') }}</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </x-admin.table>
        </div>

    </div>

    @include('backend.order_emails._styles')
@endsection

@section('modal')

    <div class="modal fade" id="import-templates-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('order-emails.templates.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('Import templates') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted fs-13">{{ translate('Choose a file created with the Export button, for example from your local site. Templates are linked to services by the service name.') }}</p>
                    <div class="form-group">
                        <input type="file" name="file" accept=".json,application/json" class="form-control-file" required>
                    </div>
                    <div class="form-group mb-2">
                        <label class="aiz-checkbox mb-0">
                            <input type="checkbox" name="overwrite" value="1">
                            <span class="aiz-square-check"></span>
                            {{ translate('Update templates that already exist here (same name and service)') }}
                        </label>
                    </div>
                    <div class="form-group mb-0">
                        <label class="aiz-checkbox mb-0">
                            <input type="checkbox" name="import_senders" value="1" checked>
                            <span class="aiz-square-check"></span>
                            {{ translate('Also import the "From" addresses if none are set here') }}
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Import') }}</button>
                </div>
            </form>
        </div>
    </div>

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

    <div class="modal fade" id="from-addresses-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form class="modal-content" action="{{ route('order-emails.senders') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('"From" addresses') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted fs-13 mb-2">{{ translate('Always available') }}:</p>
                    <div class="set-from-list">
                        @foreach ($fromOptions as $option)
                            <div class="set-from-item"><strong>{{ $option['name'] }}</strong> &lt;{{ $option['email'] }}&gt;</div>
                        @endforeach
                    </div>
                    <label class="set-label">{{ translate('Extra addresses staff can pick when sending') }}</label>
                    <textarea name="order_email_from_addresses" rows="4" class="form-control"
                        placeholder="Time To Furnish Sales <sales@timetofurnish.com>">{{ old('order_email_from_addresses', $fromAddressesRaw) }}</textarea>
                    <small class="text-muted">{{ translate('One per line, as') }} <code>Name &lt;email@domain.com&gt;</code> {{ translate('or just the email.') }}</small>
                    <div class="set-note mt-3">
                        {{ translate('Your mail server must be allowed to send as each address, otherwise it may show the main account as sender. Replies still go to the address chosen.') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ translate('Save addresses') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).on('click', '.js-template-preview', function () {
            var frame = document.getElementById('template-preview-frame');
            frame.srcdoc = '';
            $('#template-preview-subject').text('…');
            $('#template-preview-modal').modal('show');

            $.get(this.getAttribute('data-url'), function (data) {
                $('#template-preview-subject').text(data.subject);
                frame.srcdoc = data.html;
            });
        });
    </script>
@endsection
