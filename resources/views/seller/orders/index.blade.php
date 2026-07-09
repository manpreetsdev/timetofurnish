@extends('seller.layouts.app')

@section('panel_content')
    <style>
        .seller-orders-page {
            --orders-accent: #685b4e;
            --orders-accent-dark: #51463c;
            --orders-border: #e9e6e2;
            --orders-soft: #fbfbfa;
            --orders-soft-2: #f6f5f3;
            --orders-muted: #76716b;
            color: var(--brown);
            padding-top: 16px;
        }

        .seller-orders-page .orders-hero,
        .seller-orders-page .orders-card {
            background: #fff;
            border: 1px solid var(--orders-border);
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(57, 50, 42, 0.04);
        }

        .seller-orders-page .orders-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            margin-bottom: 16px;
        }

        .seller-orders-page .orders-eyebrow {
            margin-bottom: 6px;
            color: var(--orders-muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .seller-orders-page .orders-title {
            margin: 0;
            color: var(--brown);
            font-size: 24px;
            font-weight: 700;
        }

        .seller-orders-page .orders-subtitle {
            margin: 6px 0 0;
            color: var(--orders-muted);
            font-size: 13px;
        }

        .seller-orders-page .orders-total {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-width: 132px;
            padding: 12px 16px;
            color: #685b4e;
            background: #fff;
            border: 1px solid var(--orders-border);
            border-radius: 8px;
            font-weight: 700;
        }

        .seller-orders-page .orders-toolbar {
            display: grid;
            grid-template-columns: minmax(150px, 1fr) 220px 220px minmax(220px, 1fr) 120px;
            gap: 12px;
            align-items: center;
            padding: 16px 20px;
            border-bottom: 1px solid var(--orders-border);
        }

        .seller-orders-page .orders-toolbar-title {
            margin: 0;
            color: var(--brown);
            font-size: 17px;
            font-weight: 700;
        }

        .seller-orders-page .form-control,
        .seller-orders-page .btn,
        .seller-orders-page .dropdown-menu {
            border-radius: 6px;
        }

        .seller-orders-page .form-control {
            height: 44px;
            padding: 9px 14px;
            color: var(--brown);
            border-color: var(--orders-border);
            font-size: 14px;
            font-weight: 600;
        }

        .seller-orders-page .form-control:focus {
            border-color: #685b4e;
            box-shadow: 0 0 0 .18rem rgba(104, 91, 78, 0.14);
        }

        .seller-orders-page .btn-primary {
            height: 44px;
            padding: 9px 18px;
            color: #fff !important;
            background: #685b4e !important;
            border-color: #685b4e !important;
            font-size: 14px;
            font-weight: 700;
            box-shadow: none;
        }

        .seller-orders-page .btn-primary:hover,
        .seller-orders-page .btn-primary:focus {
            background: var(--orders-accent-dark) !important;
            border-color: var(--orders-accent-dark) !important;
        }

        .seller-orders-page .bootstrap-select {
            width: 100% !important;
            height: 44px !important;
        }

        .seller-orders-page .bootstrap-select.form-control {
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .seller-orders-page .bootstrap-select > .dropdown-toggle {
            display: flex;
            align-items: center;
            width: 100%;
            height: 44px;
            min-height: 44px;
            margin: 0 !important;
            padding: 9px 14px;
            color: #685b4e !important;
            background: #fff !important;
            border: 1px solid var(--orders-border) !important;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: none !important;
        }

        .seller-orders-page .bootstrap-select > .dropdown-toggle:hover,
        .seller-orders-page .bootstrap-select > .dropdown-toggle:focus,
        .seller-orders-page .bootstrap-select.show > .dropdown-toggle {
            color: #685b4e !important;
            background: #fff !important;
            border-color: #685b4e !important;
        }

        .seller-orders-page .bootstrap-select .filter-option {
            position: static;
            display: flex;
            align-items: center;
            width: 100%;
            height: auto;
            padding: 0;
        }

        .seller-orders-page .bootstrap-select .filter-option-inner {
            width: 100%;
        }

        .seller-orders-page .bootstrap-select .filter-option-inner-inner {
            color: #685b4e !important;
            line-height: 1.4;
        }

        .seller-orders-page .bootstrap-select .dropdown-toggle::after {
            margin-left: auto;
            border-top-color: #685b4e !important;
        }

        .seller-orders-page .dropdown-menu {
            padding: 8px;
            background: #fff !important;
            border: 1px solid var(--orders-border);
            box-shadow: 0 12px 28px rgba(57, 50, 42, 0.10);
        }

        .seller-orders-page .dropdown-item {
            padding: 10px 12px;
            color: var(--orders-muted) !important;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .seller-orders-page .dropdown-item:hover,
        .seller-orders-page .dropdown-item:focus,
        .seller-orders-page .dropdown-item.active {
            color: #685b4e !important;
            background: var(--orders-soft-2) !important;
        }

        .seller-orders-page .orders-table-wrap {
            padding: 0 20px 20px;
        }

        .seller-orders-page .orders-table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        .seller-orders-page .orders-table thead th {
            color: var(--orders-muted);
            background: transparent;
            border-top: 0;
            border-bottom: 1px solid var(--orders-border);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .seller-orders-page .orders-table tbody tr {
            background: #fff;
            box-shadow: none;
        }

        .seller-orders-page .orders-table tbody td {
            padding: 16px 12px;
            color: var(--brown);
            border-top: 0;
            border-bottom: 1px solid var(--orders-border);
            vertical-align: middle;
            font-weight: 600;
        }

        .seller-orders-page .orders-table tbody td:first-child {
            border-radius: 0;
        }

        .seller-orders-page .orders-table tbody td:last-child {
            border-radius: 0;
        }

        .seller-orders-page .order-code-link {
            color: #685b4e;
            font-weight: 800;
        }

        .seller-orders-page .order-status {
            display: inline-flex;
            padding: 6px 10px;
            color: #685b4e;
            background: #fff;
            border: 1px solid var(--orders-border);
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .seller-orders-page .badge-success {
            color: #2f6b45;
            background: rgba(47, 107, 69, .12);
        }

        .seller-orders-page .badge-danger {
            color: #8a2e24;
            background: rgba(138, 46, 36, .12);
        }

        .seller-orders-page .order-actions {
            display: inline-flex;
            justify-content: flex-end;
            gap: 6px;
        }

        .seller-orders-page .order-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            color: #685b4e !important;
            background: #fff;
            border: 1px solid var(--orders-border);
            border-radius: 6px;
        }

        .seller-orders-page .order-action-btn:hover,
        .seller-orders-page .order-action-btn:focus {
            color: #fff !important;
            background: #685b4e;
            border-color: #685b4e;
        }

        .seller-orders-page .orders-empty {
            padding: 36px 18px;
            color: var(--orders-muted);
            background: var(--orders-soft);
            border: 1px dashed var(--orders-border);
            border-radius: 8px;
            text-align: center;
        }

        @media (max-width: 1199.98px) {
            .seller-orders-page .orders-toolbar {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .seller-orders-page .orders-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px;
            }

            .seller-orders-page .orders-total {
                width: 100%;
            }

            .seller-orders-page .orders-title {
                font-size: 21px;
            }

            .seller-orders-page .orders-toolbar {
                grid-template-columns: 1fr;
                padding: 14px;
            }

            .seller-orders-page .orders-toolbar .btn,
            .seller-orders-page .orders-toolbar .bootstrap-select {
                width: 100% !important;
            }

            .seller-orders-page .orders-table-wrap {
                padding: 12px;
            }
        }
    </style>

    <div class="seller-orders-page">
        <div class="orders-hero">
            <div>
                <div class="orders-eyebrow">{{ translate('Seller Orders') }}</div>
                <h1 class="orders-title">{{ translate('Orders') }}</h1>
                <p class="orders-subtitle">{{ translate('Track order status, payment and customer details from one place.') }}</p>
            </div>
            <div class="orders-total">
                <i class="las la-receipt"></i>
                <span>{{ $orders->total() }} {{ translate('Orders') }}</span>
            </div>
        </div>

        <div class="card orders-card">
        <form id="sort_orders" action="" method="GET">
            <div class="orders-toolbar">
                <div>
                    <h5 class="orders-toolbar-title">{{ translate('All Orders') }}</h5>
                </div>
                <div>
                    <select class="form-control aiz-selectpicker"
                        data-placeholder="{{ translate('Filter by Payment Status') }}" name="payment_status"
                        onchange="sort_orders()">
                        <option value="">{{ translate('Filter by Payment Status') }}</option>
                        <option value="paid"
                            @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>
                            {{ translate('Paid') }}</option>
                        <option value="unpaid"
                            @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>
                            {{ translate('Unpaid') }}</option>
                    </select>
                </div>

                <div>
                    <select class="form-control aiz-selectpicker"
                        data-placeholder="{{ translate('Filter by Payment Status') }}" name="delivery_status"
                        onchange="sort_orders()">
                        <option value="">{{ translate('Filter by Deliver Status') }}</option>
                        <option value="pending"
                            @isset($delivery_status) @if ($delivery_status == 'pending') selected @endif @endisset>
                            {{ translate('Pending') }}</option>
                        <option value="confirmed"
                            @isset($delivery_status) @if ($delivery_status == 'confirmed') selected @endif @endisset>
                            {{ translate('Confirmed') }}</option>
                        <option value="on_the_way"
                            @isset($delivery_status) @if ($delivery_status == 'on_the_way') selected @endif @endisset>
                            {{ translate('On The Way') }}</option>
                        <option value="delivered"
                            @isset($delivery_status) @if ($delivery_status == 'delivered') selected @endif @endisset>
                            {{ translate('Delivered') }}</option>
                    </select>
                </div>
                <div>
                    <div class="from-group mb-0">
                        <input type="text" class="form-control" id="search" name="search"
                            @isset($sort_search) value="{{ $sort_search }}" @endisset
                            placeholder="{{ translate('Type Order code & hit Enter') }}">
                        </div>
                </div>
                <div>
                    <a href="{{route('seller.orders.exports', ['delivery_status' => (isset($_GET['delivery_status']))? $_GET['delivery_status'] : null, 'search' => (isset($_GET['search']))? $_GET['search'] : null, 'payment_status' => (isset($_GET['payment_status']))? $_GET['payment_status'] : null ])}}" class="btn btn-primary">Export</a>
                </div>
            </div>
        </form>

        @if (count($orders) > 0)
            <div class="card-body orders-table-wrap">
                <table class="table aiz-table mb-0 orders-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ translate('Order Code') }}</th>
                            <th data-breakpoints="lg">{{ translate('Num. of Products') }}</th>
                            <th data-breakpoints="lg">{{ translate('Customer') }}</th>
                            <th data-breakpoints="md">{{ translate('Amount') }}</th>
                            <th data-breakpoints="lg">{{ translate('Delivery Status') }}</th>
                            <th>{{ translate('Payment Status') }}</th>
                            <th class="text-right">{{ translate('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order_id)
                            @php
                                $order = \App\Models\Order::find($order_id->id);
                            @endphp
                            @if ($order != null)
                                <tr>
                                    <td>
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a class="order-code-link" href="#{{ $order->code }}"
                                            onclick="show_order_details({{ $order->id }})">{{ $order->code }}</a>
                                        @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                            <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ count($order->orderDetails->where('seller_id', Auth::user()->id)) }}
                                    </td>
                                    <td>
                                        @if ($order->user_id != null)
                                            {{ optional($order->user)->name }}
                                        @else
                                            {{ translate('Guest') }} ({{ $order->guest_id }})
                                        @endif
                                    </td>
                                    <td>
                                        {{ single_price($order->grand_total) }}
                                    </td>
                                    <td>
                                        @php
                                            $status = $order->delivery_status;
                                        @endphp
                                        <span class="order-status">{{ translate(ucfirst(str_replace('_', ' ', $status))) }}</span>
                                    </td>
                                    <td>
                                        @if ($order->payment_status == 'paid')
                                            <span class="badge badge-inline badge-success">{{ translate('Paid') }}</span>
                                        @else
                                            <span class="badge badge-inline badge-danger">{{ translate('Unpaid') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <span class="order-actions">
                                        @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                            <a class="order-action-btn"
                                                href="{{ route('seller.invoice.thermal_printer', $order->id) }}"
                                                target="_blank" title="{{ translate('Thermal Printer') }}">
                                                <i class="las la-print"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('seller.orders.show', encrypt($order->id)) }}"
                                            class="order-action-btn"
                                            title="{{ translate('Order Details') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                          @if ($order->payment_status == 'paid')
                                        <a href="{{ route('seller.invoice.download', $order->id) }}"
                                            class="order-action-btn"
                                            title="{{ translate('Download Invoice') }}">
                                            <i class="las la-download"></i>
                                        </a>
                                        @endif
                                        @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                        <a href="javascript:void(0)" class="order-action-btn confirm-delete" data-href="{{route('purchase_history.destroy', $order->id)}}" title="{{ translate('Cancel') }}">
                                            <!--<svg xmlns="http://www.w3.org/2000/svg" width="9.202" height="12" viewBox="0 0 9.202 12">-->
                                            <!--    <path id="Path_28714" data-name="Path 28714" d="M15.041,7.608l-.193,5.85a1.927,1.927,0,0,1-1.933,1.864H9.243A1.927,1.927,0,0,1,7.31,13.46L7.117,7.608a.483.483,0,0,1,.966-.032l.193,5.851a.966.966,0,0,0,.966.929h3.672a.966.966,0,0,0,.966-.931l.193-5.849a.483.483,0,1,1,.966.032Zm.639-1.947a.483.483,0,0,1-.483.483H6.961a.483.483,0,1,1,0-.966h1.5a.617.617,0,0,0,.615-.555,1.445,1.445,0,0,1,1.442-1.3h1.126a1.445,1.445,0,0,1,1.442,1.3.617.617,0,0,0,.615.555h1.5a.483.483,0,0,1,.483.483ZM9.913,5.178h2.333a1.6,1.6,0,0,1-.123-.456.483.483,0,0,0-.48-.435H10.516a.483.483,0,0,0-.48.435,1.6,1.6,0,0,1-.124.456ZM10.4,12.5V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Zm2.326,0V8.385a.483.483,0,0,0-.966,0V12.5a.483.483,0,1,0,.966,0Z" transform="translate(-6.478 -3.322)" fill="#d43533"/>-->
                                            <!--</svg>-->
                                            <!--<i class="las la-times" style="font-size: 20px;"></i>-->
                                            <i class="las la-times" style="color: #d43533; font-size: 18px;"></i>

                                        </a>
                                    @endif
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination">
                    {{ $orders->links() }}
                </div>
            </div>
        @else
            <div class="card-body orders-table-wrap">
                <div class="orders-empty">{{ translate('No orders found.') }}</div>
            </div>
        @endif
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        function sort_orders(el) {
            $('#sort_orders').submit();
        }
    </script>
@endsection
