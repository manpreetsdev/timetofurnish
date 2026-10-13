{{-- Highlights unread messages / alerts from Time To Furnish on the customer dashboard --}}
@php
    $omUnread = \App\Models\OrderMessage::with('order')
        ->where('customer_id', auth()->id())
        ->where('sender_type', 'admin')
        ->whereNull('read_by_customer_at')
        ->latest('id')
        ->get()
        ->groupBy('order_id');
@endphp

@if ($omUnread->isNotEmpty())
    <div class="omd-wrap">
        @foreach ($omUnread as $orderId => $items)
            @php
                $latest = $items->first();
                $hasAlert = $items->contains('is_alert', true);
            @endphp
            @if ($latest->order)
                <a href="{{ route('order-messages.show', $orderId) }}" class="omd-banner {{ $hasAlert ? 'is-alert' : '' }}">
                    <span class="omd-icon"><i class="las {{ $hasAlert ? 'la-exclamation-circle' : 'la-envelope' }}"></i></span>
                    <span class="omd-text">
                        <strong>
                            {{ $hasAlert ? translate('Action needed for your order') : translate('New message about your order') }}
                            {{ $latest->order->code }}
                            @if ($items->count() > 1)
                                ({{ $items->count() }})
                            @endif
                        </strong>
                        <span>{{ $latest->body ? \Illuminate\Support\Str::limit($latest->body, 140) : translate('We have sent you some files.') }}</span>
                    </span>
                    <span class="omd-cta">{{ translate('View & reply') }} <i class="las la-angle-right"></i></span>
                </a>
            @endif
        @endforeach
    </div>

    <style>
        .omd-wrap { display: grid; gap: 10px; margin-bottom: 18px; }
        .omd-banner { display: flex; align-items: center; gap: 14px; padding: 14px 16px; border: 1px solid #dcd4c9; border-left: 4px solid #685b4e; border-radius: 12px; background: #faf8f5; color: #39322a; text-decoration: none !important; transition: box-shadow .15s ease; }
        .omd-banner:hover { box-shadow: 0 8px 22px rgba(104, 91, 78, .12); color: #39322a; }
        .omd-banner.is-alert { border-color: #f0c36d; border-left-color: #e0a020; background: #fffaf0; }
        .omd-icon { display: inline-flex; align-items: center; justify-content: center; flex: 0 0 40px; width: 40px; height: 40px; border-radius: 50%; background: #685b4e; color: #fff; font-size: 20px; }
        .omd-banner.is-alert .omd-icon { background: #e0a020; }
        .omd-text { flex: 1 1 auto; min-width: 0; display: flex; flex-direction: column; font-size: 13px; line-height: 1.45; }
        .omd-text strong { font-size: 14px; }
        .omd-text span { color: #6b6258; }
        .omd-cta { flex: 0 0 auto; padding: 8px 14px; border-radius: 8px; background: #685b4e; color: #fff; font-size: 12.5px; font-weight: 700; white-space: nowrap; }
        .omd-banner.is-alert .omd-cta { background: #e0a020; }
        @media (max-width: 575.98px) {
            .omd-banner { flex-wrap: wrap; }
            .omd-cta { width: 100%; text-align: center; }
        }
    </style>
@endif
