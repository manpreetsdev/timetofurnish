@extends('backend.layouts.app')

@section('content')
    @php
        $filter = request('filter');
        $filters = [
            '' => ['label' => translate('All conversations'), 'count' => $stats['conversations']],
            'unread' => ['label' => translate('Customer replied'), 'count' => $stats['unread']],
            'waiting' => ['label' => translate('Waiting for customer'), 'count' => $stats['waiting']],
            'files' => ['label' => translate('With files'), 'count' => null],
        ];
    @endphp

    <div class="set-screen">
        <div class="set-hero">
            <div>
                <span class="set-eyebrow">{{ translate('Sales') }}</span>
                <h1>{{ translate('Customer Messages') }}</h1>
                <p>{{ translate('Conversations with customers about their orders, including the photos, videos and files they upload.') }}</p>
            </div>
            <div class="set-hero-actions">
                <button type="button" class="btn set-btn set-btn-primary" data-toggle="modal" data-target="#new-message-modal">
                    <i class="las la-plus"></i> {{ translate('New message') }}
                </button>
            </div>
        </div>

        <div class="set-stats">
            <div class="set-stat"><span>{{ translate('Conversations') }}</span><strong>{{ $stats['conversations'] }}</strong></div>
            <div class="set-stat {{ $stats['unread'] > 0 ? 'is-warning' : '' }}"><span>{{ translate('Customer replied – to read') }}</span><strong>{{ $stats['unread'] }}</strong></div>
            <div class="set-stat"><span>{{ translate('Waiting for customer') }}</span><strong>{{ $stats['waiting'] }}</strong></div>
            <div class="set-stat"><span>{{ translate('Files uploaded') }}</span><strong>{{ $stats['files'] }}</strong></div>
        </div>

        <div class="set-card">
            {{-- Filters --}}
            <div class="omi-tabs">
                @foreach ($filters as $key => $item)
                    <a href="{{ route('admin.order-messages.index', array_filter(['filter' => $key, 'search' => request('search'), 'date' => request('date')])) }}"
                        class="omi-tab {{ (string) $filter === (string) $key ? 'is-active' : '' }}">
                        {{ $item['label'] }}
                        @if ($item['count'] !== null)
                            <span>{{ $item['count'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.order-messages.index') }}" method="GET" class="omi-filters">
                @if ($filter)
                    <input type="hidden" name="filter" value="{{ $filter }}">
                @endif
                <div class="omi-search">
                    <i class="las la-search"></i>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="{{ translate('Search order code, customer name or email') }}">
                </div>
                <input type="text" name="date" value="{{ request('date') }}" class="aiz-date-range form-control omi-date"
                    placeholder="{{ translate('Any date') }}" data-format="DD-MM-Y" data-separator=" to " autocomplete="off">
                <button type="submit" class="btn set-btn set-btn-primary">{{ translate('Apply') }}</button>
                @if (request('search') || request('date') || $filter)
                    <a href="{{ route('admin.order-messages.index') }}" class="btn set-btn">{{ translate('Reset') }}</a>
                @endif
            </form>

            {{-- One row per conversation (customer + order). Messages are on the conversation screen. --}}
            <div class="omr-list">
                @php $rowCount = 0; @endphp
                @foreach ($groups as $customerId => $threads)
                    @foreach ($threads as $thread)
                        @php
                            $order = $orders[$thread->order_id] ?? null;
                            $customer = optional($order)->user;
                            $fileCount = ($messages[$thread->order_id] ?? collect())->sum(fn ($message) => $message->files->count());
                        @endphp
                        @if ($order)
                            @php $rowCount++; @endphp
                            <a href="{{ route('admin.order-messages.show', $order->id) }}" class="omr-row {{ $thread->unread > 0 ? 'has-new' : '' }}">
                                <span class="omr-avatar">{{ mb_strtoupper(mb_substr(optional($customer)->name ?: 'G', 0, 1)) }}</span>
                                <span class="omr-customer">
                                    <strong>{{ optional($customer)->name ?: translate('Guest') }}</strong>
                                    <small>{{ optional($customer)->email }}</small>
                                </span>
                                <span class="omr-order">
                                    <strong>
                                        {{ $order->code }}
                                        <span class="omg-pill {{ $order->payment_status === 'paid' ? 'is-soft' : '' }}">{{ translate(ucfirst($order->payment_status)) }}</span>
                                    </strong>
                                    <small>{{ \Illuminate\Support\Str::limit($order->orderDetails->map(fn ($d) => optional($d->product)->name)->filter()->implode(', '), 90) ?: '-' }}</small>
                                </span>
                                <span class="omr-status">
                                    @if ($thread->unread > 0)
                                        <span class="omg-pill is-solid">{{ $thread->unread }} {{ $thread->unread == 1 ? translate('new message') : translate('new messages') }}</span>
                                    @elseif ($thread->waiting > 0)
                                        <span class="omg-pill">{{ translate('Not seen by customer yet') }}</span>
                                    @endif
                                </span>
                                <span class="omr-meta">
                                    <span><i class="las la-comments"></i> {{ $thread->total }}</span>
                                    <span><i class="las la-paperclip"></i> {{ $fileCount }}</span>
                                </span>
                                <span class="omr-time">
                                    {{ \Carbon\Carbon::parse($thread->last_at)->format('j M, Y') }}
                                    <small>{{ \Carbon\Carbon::parse($thread->last_at)->format('g:i A') }}</small>
                                </span>
                                <i class="las la-angle-right omr-arrow"></i>
                            </a>
                        @endif
                    @endforeach
                @endforeach

                @if ($rowCount === 0)
                    <div class="omi-empty">
                        <i class="las la-comments"></i>
                        <div>{{ $filter || request('search') || request('date') ? translate('No conversations match these filters.') : translate('No conversations yet.') }}</div>
                        <button type="button" class="btn set-btn set-btn-primary mt-3" data-toggle="modal" data-target="#new-message-modal">
                            <i class="las la-plus"></i> {{ translate('Send the first message') }}
                        </button>
                    </div>
                @endif
            </div>

            <div class="aiz-pagination mt-3">{{ $groups->links() }}</div>
        </div>
    </div>

    @include('backend.order_emails._styles')
    <style>
        /* Sidebar palette only: #685b4e brand, #39322a text, #8c7e70 muted, #f3efe9 / #faf8f5 surfaces, #e8e5e1 / #dcd4c9 borders */
        .set-screen .set-stat.is-warning { background: #f3efe9; border-color: #dcd4c9; }
        .set-screen .set-stat.is-warning strong { color: #685b4e; }

        .omi-tabs { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 14px; }
        .omi-tab { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; border: 1px solid #e8e5e1; border-radius: 999px; background: #fff; color: #685b4e; font-size: 12.5px; font-weight: 600; text-decoration: none !important; transition: all .15s ease; }
        .omi-tab span { padding: 1px 7px; border-radius: 999px; background: #f3efe9; font-size: 11px; }
        .omi-tab:hover { border-color: #685b4e; color: #685b4e; }
        .omi-tab.is-active { background: #685b4e; border-color: #685b4e; color: #fff; }
        .omi-tab.is-active span { background: rgba(255, 255, 255, .2); color: #fff; }
        .omi-filters { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; padding-bottom: 18px; border-bottom: 1px solid #f0ece7; }
        .omi-search { position: relative; flex: 1 1 300px; max-width: 460px; }
        .omi-search i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #8c7e70; }
        .omi-search .form-control { padding-left: 34px; }
        .omi-filters .form-control { height: 38px; border-radius: 8px; border-color: #dcd4c9; font-size: 13px; }
        .omi-filters .form-control:focus { border-color: #685b4e; box-shadow: 0 0 0 3px rgba(104, 91, 78, .12); }
        .omi-date { flex: 0 1 220px; }
        .omi-empty { padding: 48px 16px; border: 1px dashed #dcd4c9; border-radius: 12px; color: #8c7e70; text-align: center; }
        .omi-empty i { display: block; font-size: 42px; color: #dcd4c9; margin-bottom: 6px; }

        .omg-pill { display: inline-flex; align-items: center; padding: 2px 9px; border: 1px solid #dcd4c9; border-radius: 999px; background: #fff; color: #685b4e; font-size: 11px; font-weight: 700; line-height: 1.5; white-space: nowrap; }
        .omg-pill.is-soft { background: #f3efe9; }
        .omg-pill.is-solid { background: #685b4e; border-color: #685b4e; color: #fff; }

        .omr-list { display: grid; gap: 8px; }
        .omr-row { display: grid; grid-template-columns: 40px minmax(160px, 1.1fr) minmax(200px, 1.6fr) auto auto auto 16px; align-items: center; gap: 16px; padding: 12px 16px; border: 1px solid #e8e5e1; border-radius: 12px; background: #fff; color: #39322a; text-decoration: none !important; transition: border-color .15s ease, box-shadow .15s ease, background .15s ease; }
        .omr-row:hover { border-color: #685b4e; background: #faf8f5; color: #39322a; box-shadow: 0 6px 18px rgba(104, 91, 78, .08); }
        .omr-row.has-new { border-color: #685b4e; background: #faf8f5; }
        .omr-avatar { display: inline-flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background: #f3efe9; color: #685b4e; font-size: 15px; font-weight: 700; }
        .omr-row.has-new .omr-avatar { background: #685b4e; color: #fff; }
        .omr-customer, .omr-order { min-width: 0; }
        .omr-customer strong, .omr-order strong { display: flex; align-items: center; gap: 6px; font-size: 13.5px; font-weight: 700; color: #39322a; }
        .omr-customer small, .omr-order small { display: block; margin-top: 1px; color: #8c7e70; font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .omr-status { min-width: 0; text-align: right; }
        .omr-meta { display: inline-flex; gap: 12px; color: #8c7e70; font-size: 12px; white-space: nowrap; }
        .omr-time { color: #4a4239; font-size: 12px; text-align: right; white-space: nowrap; }
        .omr-time small { display: block; color: #8c7e70; font-size: 11px; }
        .omr-arrow { color: #8c7e70; font-size: 15px; }

        .nm-order-info { margin-top: 6px; padding: 8px 11px; border-radius: 8px; background: #faf8f5; color: #5d544a; font-size: 12px; line-height: 1.5; display: none; }
        #new-message-modal .modal-content, #new-message-modal .modal-body { overflow: visible; }

        @media (max-width: 991.98px) {
            .omr-row { grid-template-columns: 40px minmax(0, 1fr) auto; row-gap: 6px; }
            .omr-order { grid-column: 2 / -1; }
            .omr-status { grid-column: 2 / 3; text-align: left; }
            .omr-meta { grid-column: 3; justify-self: end; }
            .omr-time { grid-row: 1; grid-column: 3; }
            .omr-arrow { display: none; }
        }
    </style>
@endsection

@section('modal')
    <div class="modal fade" id="new-message-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <form class="modal-content" method="POST" id="new-message-form" action="#">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title h6">{{ translate('New message to a customer') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ translate('Close') }}"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="set-label">{{ translate('Customer') }} <span class="text-danger">*</span></label>
                        <div class="ttfp" id="nm-customer"
                            data-placeholder="{{ translate('Choose a customer') }} ({{ count($customers) }})"
                            data-search="{{ translate('Search by name, email or phone') }}"
                            data-label="{{ translate('customers with paid orders') }}"
                            data-empty="{{ translate('No customer found') }}"></div>
                    </div>
                    <div class="form-group">
                        <label class="set-label">{{ translate('Paid order') }} <span class="text-danger">*</span></label>
                        <div class="ttfp is-disabled" id="nm-order"
                            data-placeholder="{{ translate('Choose a customer first') }}"
                            data-search="{{ translate('Search by order code or product') }}"
                            data-label="{{ translate('paid orders') }}"
                            data-empty="{{ translate('No paid order found') }}"></div>
                        <div class="nm-order-info" id="nm-order-info"></div>
                    </div>

                    <label class="set-label">{{ translate('Message') }} <span class="text-danger">*</span></label>
                    <textarea name="body" rows="5" class="form-control" required maxlength="5000"
                        placeholder="{{ translate('Write your message to the customer...') }}"></textarea>

                    <div class="mt-3" style="display: grid; gap: 6px; font-size: 12.5px;">
                        <label class="aiz-checkbox mb-0">
                            <input type="checkbox" name="is_alert" value="1">
                            <span class="aiz-square-check"></span>
                            <strong>{{ translate('Mark as important') }}</strong>
                            <span class="text-muted">– {{ translate('highlighted on the customer\'s dashboard until they open it') }}</span>
                        </label>
                        <label class="aiz-checkbox mb-0">
                            <input type="checkbox" name="notify_email" value="1" checked>
                            <span class="aiz-square-check"></span>
                            {{ translate('Also email the customer that a message is waiting') }}
                        </label>
                    </div>
                    <p class="text-muted fs-12 mt-3 mb-0">{{ translate('To attach files, send the message first, then add them in the conversation that opens.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft-secondary" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary" id="nm-send" disabled><i class="las la-paper-plane"></i> {{ translate('Send message') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    @include('partials.order_messages._picker')
    <script>
        (function () {
            var lookupUrl = '{{ route('admin.order-messages.lookup') }}';
            var storeUrl = '{{ route('admin.order-messages.store', '__ID__') }}';
            var form = document.getElementById('new-message-form');
            var send = document.getElementById('nm-send');
            var info = document.getElementById('nm-order-info');

            var orderPicker = TTFPicker(document.getElementById('nm-order'), function (order) {
                send.disabled = !order;
                if (!order) { info.style.display = 'none'; return; }
                form.action = storeUrl.replace('__ID__', order.value);
                info.textContent = order.title + ' · ' + order.subtitle;
                info.style.display = 'block';
            });

            var customerPicker = TTFPicker(document.getElementById('nm-customer'), function (customer) {
                send.disabled = true;
                info.style.display = 'none';
                orderPicker.setOptions([]);
                if (!customer) { return orderPicker.setDisabled(true, '{{ translate('Choose a customer first') }}'); }

                orderPicker.setDisabled(true, '{{ translate('Loading orders...') }}');
                $.get(lookupUrl, { customer_id: customer.value }, function (data) {
                    var orders = (data.orders || []).map(function (order) {
                        return {
                            value: order.id,
                            title: order.products || order.code,
                            subtitle: order.code + ' · ' + order.date,
                            meta: order.total.replace(/<[^>]*>/g, ''),
                            search: order.code + ' ' + order.products
                        };
                    });
                    orderPicker.setOptions(orders);
                    orderPicker.setDisabled(!orders.length, orders.length
                        ? '{{ translate('Choose a paid order') }} (' + orders.length + ')'
                        : '{{ translate('This customer has no paid orders') }}');
                    // only one paid order: select it straight away
                    if (orders.length === 1) { orderPicker.setValue(orders[0].value, true); }
                });
            });

            customerPicker.setOptions(@json($customers));

            form.addEventListener('submit', function (event) {
                if (!orderPicker.getValue()) { event.preventDefault(); return; }
                send.disabled = true;
            });
        })();
    </script>
@endsection
