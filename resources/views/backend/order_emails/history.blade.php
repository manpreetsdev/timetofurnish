@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
            <div>
                <h1 class="h3 mb-1">{{ translate('Email History') }}</h1>
                <p class="mb-0 text-muted">{{ translate('Every email sent from the site: service emails sent by staff and automatic emails.') }}</p>
            </div>
            <a href="{{ route('order-emails.templates') }}" class="btn btn-soft-primary btn-sm">{{ translate('Service email templates') }}</a>
        </div>
    </div>

    @if (!empty($customer))
        <div class="card mb-3" style="border-color:#dcd4c9;background:#faf8f5;">
            <div class="card-body py-3 d-flex flex-wrap align-items-center justify-content-between" style="gap: 10px;">
                <div>
                    <div class="text-muted fs-12 text-uppercase fw-700">{{ translate('Showing emails for customer') }}</div>
                    <div class="fs-16 fw-700">{{ $customer['name'] }}
                        @if ($customer['email'])
                            <span class="text-muted fw-400 fs-13">&lt;{{ $customer['email'] }}&gt;</span>
                        @endif
                    </div>
                    <div class="text-muted fs-12">
                        {{ translate('Opened from order') }} {{ $customer['order']->code }}
                        · {{ $customer['orders'] }} {{ translate('order(s) by this customer') }}
                        · {{ $logs->total() }} {{ translate('email(s)') }}
                    </div>
                </div>
                <div>
                    @if ($customer['order']->payment_status === 'paid')
                        <a href="{{ route('order-emails.compose', $customer['order']->id) }}" class="btn btn-primary btn-sm"><i class="las la-envelope"></i> {{ translate('Send email') }}</a>
                    @endif
                    <a href="{{ route('order-emails.history') }}" class="btn btn-soft-secondary btn-sm">{{ translate('Show all customers') }}</a>
                </div>
            </div>
        </div>
    @endif

    <div class="row gutters-10 mb-2">
        @foreach ([['All emails', $totals['all']], ['Service emails', $totals['service']], ['Automatic emails', $totals['system']], ['Failed', $totals['failed']]] as $stat)
            <div class="col-6 col-lg-3">
                <div class="card mb-2">
                    <div class="card-body py-3">
                        <div class="text-muted fs-12 text-uppercase fw-700">{{ translate($stat[0]) }}</div>
                        <div class="h4 mb-0 fw-700">{{ number_format($stat[1]) }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <form action="{{ route('order-emails.history') }}" method="GET">
            @if (request('order_id'))
                <input type="hidden" name="order_id" value="{{ request('order_id') }}">
            @endif
            <div class="card-header row gutters-5">
                <div class="col-12 col-lg-5 mb-2 mb-lg-0">
                    <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}"
                        placeholder="{{ translate('Search order code, customer email or subject') }}">
                </div>
                <div class="col-6 col-lg-3">
                    <select class="form-control form-control-sm aiz-selectpicker" name="type">
                        <option value="">{{ translate('All types') }}</option>
                        <option value="service_email" @selected(request('type') === 'service_email')>{{ translate('Service emails') }}</option>
                        <option value="system" @selected(request('type') === 'system')>{{ translate('Automatic emails') }}</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <select class="form-control form-control-sm aiz-selectpicker" name="status">
                        <option value="">{{ translate('Any status') }}</option>
                        <option value="sent" @selected(request('status') === 'sent')>{{ translate('Sent') }}</option>
                        <option value="failed" @selected(request('status') === 'failed')>{{ translate('Failed') }}</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 mt-2 mt-lg-0">
                    <button type="submit" class="btn btn-primary btn-sm btn-block">{{ translate('Filter') }}</button>
                </div>
            </div>
        </form>
        <div class="card-body">
            @include('backend.order_emails._history_table', ['logs' => $logs, 'showOrder' => true])
            <div class="aiz-pagination mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
@endsection
