@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
            <h1 class="h3 mb-0">{{ translate('Email details') }}</h1>
            <div>
                @if ($log->order)
                    <a href="{{ route('order-emails.compose', $log->order_id) }}" class="btn btn-soft-primary btn-sm">{{ translate('Order') }} {{ $log->order->code }}</a>
                @endif
                <a href="{{ route('order-emails.history') }}" class="btn btn-soft-secondary btn-sm">{{ translate('Email history') }}</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-borderless mb-0" style="max-width: 760px;">
                <tr><th width="160">{{ translate('Subject') }}</th><td>{{ $log->subject }}</td></tr>
                <tr><th>{{ translate('Sent') }}</th><td>{{ $log->created_at->format('d M Y, h:i A') }}</td></tr>
                <tr>
                    <th>{{ translate('Status') }}</th>
                    <td>
                        @if ($log->status === 'sent')
                            <span class="badge badge-inline badge-success">{{ translate('Sent') }}</span>
                        @else
                            <span class="badge badge-inline badge-danger">{{ translate('Failed') }}</span>
                            <div class="text-danger fs-12 mt-1">{{ $log->error }}</div>
                        @endif
                    </td>
                </tr>
                <tr><th>{{ translate('Type') }}</th><td>{{ $log->type === 'service_email' ? translate('Service email') : translate('Automatic') }}</td></tr>
                <tr><th>{{ translate('From') }}</th><td>{{ $log->from_name }} &lt;{{ $log->from_email }}&gt;</td></tr>
                <tr><th>{{ translate('To') }}</th><td>{{ $log->to_email }}</td></tr>
                @if ($log->cc)
                    <tr><th>{{ translate('CC') }}</th><td>{{ $log->cc }}</td></tr>
                @endif
                <tr><th>{{ translate('Sent by') }}</th><td>{{ optional($log->sender)->name ?: translate('System') }}</td></tr>
                @if ($log->service)
                    <tr><th>{{ translate('Service') }}</th><td>{{ $log->service->name }}</td></tr>
                @endif
                @if ($log->template)
                    <tr><th>{{ translate('Template') }}</th><td>{{ $log->template->name }}</td></tr>
                @endif
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Message') }}</h5>
        </div>
        <div class="card-body">
            @if ($log->body)
                <iframe sandbox srcdoc="{{ view('emails.service_email', ['body' => $log->body, 'subject' => $log->subject, 'date' => $log->created_at])->render() }}"
                    style="width:100%;height:640px;border:1px solid #e8e5e1;border-radius:8px;background:#fff;"></iframe>
            @else
                <p class="text-muted mb-0">{{ translate('The content of automatic emails is not stored, only the subject and recipient.') }}</p>
            @endif
        </div>
    </div>
@endsection
