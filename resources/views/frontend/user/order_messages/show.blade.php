@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-3">
        <a href="{{ route('order-messages.index') }}" class="text-muted fs-13"><i class="las la-arrow-left"></i> {{ translate('All messages') }}</a>
        <div class="d-flex flex-wrap align-items-center justify-content-between mt-1" style="gap: 8px;">
            <div>
                <h1 class="fs-20 fw-700 text-dark mb-0">{{ translate('Order') }} {{ $order->code }}</h1>
                <p class="text-muted mb-0 fs-13">{{ translate('Placed on') }} {{ date('j M, Y', $order->date) }} · {{ single_price($order->grand_total) }}</p>
            </div>
            <a href="{{ route('purchase_history.details', encrypt($order->id)) }}" class="btn btn-soft-secondary btn-sm">{{ translate('Order details') }}</a>
        </div>
    </div>

    @include('partials.order_messages._thread', ['messages' => $messages, 'viewer' => 'customer'])
    @include('partials.order_messages._composer', ['action' => route('order-messages.store', $order->id), 'viewer' => 'customer'])
    @include('partials.order_messages._assets')
@endsection
