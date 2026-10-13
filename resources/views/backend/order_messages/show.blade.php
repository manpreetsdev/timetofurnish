@extends('backend.layouts.app')

@section('content')
    @php
        $address = json_decode((string) $order->shipping_address);
    @endphp
    <div class="set-screen">
        <div class="set-hero">
            <div>
                <a href="{{ route('admin.order-messages.index') }}" class="set-back"><i class="las la-arrow-left"></i> {{ translate('All customer messages') }}</a>
                <h1>{{ translate('Messages') }} – {{ $order->code }}</h1>
                <p>
                    {{ $address->name ?? optional($order->user)->name ?? translate('Guest') }}
                    @if (optional($order->user)->email) · {{ $order->user->email }} @endif
                    · {{ date('j M, Y', $order->date) }} · {{ single_price($order->grand_total) }}
                    · {{ translate(ucfirst($order->payment_status)) }}
                </p>
            </div>
            <div class="set-hero-actions">
                <a href="{{ route('all_orders.show', encrypt($order->id)) }}" class="btn set-btn">{{ translate('Order details') }}</a>
                <a href="{{ route('order-emails.history', ['order_id' => $order->id]) }}" class="btn set-btn">{{ translate('Email history') }}</a>
            </div>
        </div>

        @if (!$order->user_id)
            <div class="set-note mb-3">
                {{ translate('This order was placed by a guest without an account. Guests have no dashboard, so messages cannot be shown to them. Use a service email instead.') }}
            </div>
        @endif

        <div class="row gutters-10">
            <div class="col-lg-8">
                <div class="set-card">
                    <div class="set-card-head">
                        <div>
                            <h2>{{ translate('Conversation') }}</h2>
                            <p>{{ translate('The customer sees this on their dashboard and can reply with photos or videos. Messages cannot be edited or deleted.') }}</p>
                        </div>
                    </div>
                    @include('partials.order_messages._thread', ['messages' => $messages, 'viewer' => 'admin'])
                    @if ($order->user_id)
                        @include('partials.order_messages._composer', ['action' => route('admin.order-messages.store', $order->id), 'viewer' => 'admin'])
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="set-card">
                    <div class="set-card-head">
                        <div>
                            <h2>{{ translate('Files in this order') }} ({{ $files->count() }})</h2>
                            <p>{{ translate('Everything uploaded for this order, newest first.') }}</p>
                        </div>
                    </div>
                    @forelse ($files as $file)
                        @php $url = route('order-messages.file', $file->id); @endphp
                        <a href="{{ $url }}" target="_blank" class="omf-item">
                            @if ($file->kind === 'image')
                                <img src="{{ $url }}" alt="" loading="lazy">
                            @else
                                <span class="omf-icon"><i class="las {{ $file->kind === 'video' ? 'la-video' : 'la-file' }}"></i></span>
                            @endif
                            <span class="omf-text">
                                <strong>{{ $file->original_name }}</strong>
                                <small>
                                    {{ optional($file->message)->sender_type === 'admin' ? translate('Sent by us') : translate('From customer') }}
                                    · {{ $file->size_label }} · {{ $file->created_at->format('j M, g:i A') }}
                                </small>
                            </span>
                        </a>
                    @empty
                        <p class="text-muted mb-0 fs-13">{{ translate('No files uploaded yet.') }}</p>
                    @endforelse
                </div>

                <div class="set-card">
                    <div class="set-card-head"><div><h2>{{ translate('Products') }}</h2></div></div>
                    @foreach ($order->orderDetails as $detail)
                        @if ($detail->product)
                            <div class="mb-1 fs-13">{{ $detail->product->name }} &times; {{ $detail->quantity }}</div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('backend.order_emails._styles')
    @include('partials.order_messages._assets')
    <style>
        .omf-item { display: flex; align-items: center; gap: 10px; padding: 8px; margin-bottom: 6px; border: 1px solid #e8e5e1; border-radius: 8px; color: #39322a; text-decoration: none !important; }
        .omf-item:hover { border-color: #685b4e; background: #faf8f5; color: #39322a; }
        .omf-item img, .omf-icon { flex: 0 0 46px; width: 46px; height: 46px; border-radius: 6px; object-fit: cover; }
        .omf-icon { display: inline-flex; align-items: center; justify-content: center; background: #f3efe9; color: #685b4e; font-size: 22px; }
        .omf-text { min-width: 0; font-size: 12.5px; }
        .omf-text strong { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .omf-text small { color: #8c7e70; }
    </style>
@endsection
