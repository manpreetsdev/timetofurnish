@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="aiz-titlebar mb-4">
        <h1 class="fs-20 fw-700 text-dark">{{ translate('Messages') }}</h1>
        <p class="text-muted mb-0">{{ translate('Messages from Time To Furnish about your orders. When we write to you, you can reply and upload photos, videos or files here.') }}</p>
    </div>

    @if (session('om_error'))
        <div class="alert alert-danger">{{ session('om_error') }}</div>
    @endif

    @forelse ($threads as $thread)
        @php
            $order = $orders[$thread->order_id] ?? null;
            $last = $lastMessages[$thread->order_id] ?? null;
        @endphp
        @if ($order)
            <a href="{{ route('order-messages.show', $order->id) }}" class="omc-thread {{ $thread->unread > 0 ? 'is-unread' : '' }} {{ $thread->unread_alerts > 0 ? 'is-alert' : '' }}">
                <div class="omc-thread-main">
                    <div class="omc-thread-title">
                        {{ translate('Order') }} {{ $order->code }}
                        @if ($thread->unread_alerts > 0)
                            <span class="omc-badge is-alert"><i class="las la-exclamation-circle"></i> {{ translate('Action needed') }}</span>
                        @elseif ($thread->unread > 0)
                            <span class="omc-badge">{{ $thread->unread }} {{ translate('new') }}</span>
                        @endif
                    </div>
                    @if ($last)
                        <div class="omc-thread-preview">
                            <strong>{{ $last->sender_type === 'admin' ? 'Time To Furnish' : translate('You') }}:</strong>
                            {{ $last->body ? \Illuminate\Support\Str::limit($last->body, 110) : translate('Sent attachments') }}
                        </div>
                    @endif
                </div>
                <div class="omc-thread-side">
                    <span>{{ \Carbon\Carbon::parse($thread->last_at)->format('j M, Y') }}</span>
                    <i class="las la-angle-right"></i>
                </div>
            </a>
        @endif
    @empty
        <div class="omc-empty">
            <i class="las la-comments"></i>
            <div>{{ translate('You have no messages yet.') }}</div>
            <small>{{ translate('When we send you a message about one of your orders, it will appear here and you will be able to reply.') }}</small>
        </div>
    @endforelse

    <style>
        .omc-thread { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 16px; margin-bottom: 10px; border: 1px solid #e8e5e1; border-radius: 12px; background: #fff; color: #39322a; text-decoration: none !important; transition: all .15s ease; }
        .omc-thread:hover { border-color: #685b4e; box-shadow: 0 6px 18px rgba(104, 91, 78, .08); color: #39322a; }
        .omc-thread.is-unread { background: #faf8f5; border-color: #dcd4c9; }
        .omc-thread.is-alert { background: #fffaf0; border-color: #f0c36d; border-left: 4px solid #e0a020; }
        .omc-thread-title { font-size: 14px; font-weight: 700; }
        .omc-thread-preview { margin-top: 3px; color: #6b6258; font-size: 12.5px; }
        .omc-thread-side { display: flex; align-items: center; gap: 8px; flex: 0 0 auto; color: #8c7e70; font-size: 12px; white-space: nowrap; }
        .omc-badge { display: inline-flex; align-items: center; gap: 3px; margin-left: 6px; padding: 2px 9px; border-radius: 999px; background: #685b4e; color: #fff; font-size: 11px; font-weight: 700; }
        .omc-badge.is-alert { background: #e0a020; }
        .omc-empty { padding: 44px 16px; border: 1px dashed #dcd4c9; border-radius: 12px; color: #8c7e70; text-align: center; }
        .omc-empty i { display: block; font-size: 40px; color: #cfc5b8; margin-bottom: 6px; }
    </style>
@endsection
