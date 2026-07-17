@extends('seller.layouts.app')

@section('panel_content')
    <style>
        .seller-order-detail {
            --order-accent: #685b4e;
            --order-accent-dark: #51463c;
            --order-border: #e9e6e2;
            --order-soft: #fbfbfa;
            --order-soft-2: #f6f5f3;
            --order-muted: #76716b;
            color: var(--brown);
            padding-top: 16px;
        }

        .seller-order-detail .detail-hero,
        .seller-order-detail .detail-card {
            background: #fff;
            border: 1px solid var(--order-border);
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(57, 50, 42, 0.04);
        }

        .seller-order-detail .detail-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            margin-bottom: 16px;
        }

        .seller-order-detail .detail-eyebrow {
            margin-bottom: 6px;
            color: var(--order-muted);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .seller-order-detail .detail-title {
            margin: 0;
            color: var(--brown);
            font-size: 24px;
            font-weight: 700;
        }

        .seller-order-detail .detail-subtitle {
            margin: 6px 0 0;
            color: var(--order-muted);
            font-size: 13px;
        }

        .seller-order-detail .detail-header-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .seller-order-detail .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 42px;
            padding: 9px 14px;
            color: #685b4e !important;
            background: #fff;
            border: 1px solid var(--order-border);
            border-radius: 6px;
            font-size: 14px;
            font-weight: 800;
        }

        .seller-order-detail .back-button:hover,
        .seller-order-detail .back-button:focus {
            color: #fff !important;
            background: #685b4e;
            border-color: #685b4e;
            text-decoration: none;
        }

        .seller-order-detail .detail-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            color: #685b4e;
            background: #fff;
            border: 1px solid var(--order-border);
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
        }

        .seller-order-detail .detail-card {
            overflow: hidden;
        }

        .seller-order-detail .detail-card-body {
            padding: 20px;
        }

        .seller-order-detail .status-grid,
        .seller-order-detail .info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 18px;
        }

        .seller-order-detail .status-panel,
        .seller-order-detail .info-panel,
        .seller-order-detail .totals-panel {
            background: #fff;
            border: 1px solid var(--order-border);
            border-radius: 8px;
        }

        .seller-order-detail .status-panel,
        .seller-order-detail .info-panel {
            padding: 16px;
        }

        .seller-order-detail .panel-title {
            margin: 0 0 12px;
            color: var(--brown);
            font-size: 15px;
            font-weight: 800;
        }

        .seller-order-detail .detail-label {
            display: block;
            margin-bottom: 8px;
            color: var(--order-muted);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .seller-order-detail .form-control {
            height: 44px;
            padding: 9px 14px;
            color: var(--brown);
            border-color: var(--order-border);
            border-radius: 6px;
            font-weight: 600;
        }

        .seller-order-detail .form-control:focus {
            border-color: #685b4e;
            box-shadow: 0 0 0 .18rem rgba(104, 91, 78, 0.10);
        }

        .seller-order-detail .bootstrap-select {
            width: 100% !important;
            height: 44px !important;
        }

        .seller-order-detail .bootstrap-select.form-control {
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .seller-order-detail .bootstrap-select > .dropdown-toggle {
            display: flex;
            align-items: center;
            width: 100%;
            height: 44px;
            min-height: 44px;
            margin: 0 !important;
            padding: 9px 14px;
            color: #685b4e !important;
            background: #fff !important;
            border: 1px solid var(--order-border) !important;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: none !important;
        }

        .seller-order-detail .bootstrap-select > .dropdown-toggle:hover,
        .seller-order-detail .bootstrap-select > .dropdown-toggle:focus,
        .seller-order-detail .bootstrap-select.show > .dropdown-toggle {
            color: #685b4e !important;
            background: #fff !important;
            border-color: #685b4e !important;
        }

        .seller-order-detail .bootstrap-select .filter-option {
            position: static;
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0;
        }

        .seller-order-detail .bootstrap-select .filter-option-inner,
        .seller-order-detail .bootstrap-select .filter-option-inner-inner {
            width: 100%;
            color: #685b4e !important;
        }

        .seller-order-detail .bootstrap-select .dropdown-toggle::after {
            margin-left: auto;
            border-top-color: #685b4e !important;
        }

        .seller-order-detail .dropdown-menu {
            padding: 8px;
            background: #fff !important;
            border: 1px solid var(--order-border);
            border-radius: 6px;
            box-shadow: 0 12px 28px rgba(57, 50, 42, 0.10);
        }

        .seller-order-detail .dropdown-item {
            padding: 10px 12px;
            color: var(--order-muted) !important;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
        }

        .seller-order-detail .dropdown-item:hover,
        .seller-order-detail .dropdown-item:focus,
        .seller-order-detail .dropdown-item.active {
            color: #685b4e !important;
            background: var(--order-soft-2) !important;
        }

        .seller-order-detail address {
            margin: 0;
            color: var(--order-muted);
            line-height: 1.7;
            font-weight: 600;
        }

        .seller-order-detail address strong,
        .seller-order-detail .text-main {
            color: var(--brown) !important;
            font-weight: 800;
        }

        .seller-order-detail .summary-table {
            width: 100%;
        }

        .seller-order-detail .summary-table td {
            padding: 8px 0;
            color: var(--order-muted);
            border: 0;
            font-weight: 600;
        }

        .seller-order-detail .summary-table td:last-child {
            color: var(--brown);
            font-weight: 800;
        }

        .seller-order-detail .badge {
            border-radius: 999px;
            padding: 6px 10px;
            font-weight: 800;
        }

        .seller-order-detail .badge-success {
            color: #2f6b45;
            background: rgba(47, 107, 69, .12);
        }

        .seller-order-detail .badge-info {
            color: #685b4e;
            background: #fff;
        }

        .seller-order-detail .items-section-title {
            margin: 6px 0 14px;
            color: var(--brown);
            font-size: 16px;
            font-weight: 800;
        }

        .seller-order-detail .items-table {
            border-collapse: collapse;
            border-spacing: 0;
            border: 1px solid var(--order-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .seller-order-detail .items-table thead th {
            color: var(--order-muted);
            background: #fff;
            border: 0;
            border-bottom: 1px solid var(--order-border);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .seller-order-detail .items-table tbody td {
            padding: 14px 12px;
            color: var(--brown);
            border-color: var(--order-border);
            vertical-align: middle;
            font-weight: 600;
        }

        .seller-order-detail .product-thumb {
            width: 58px;
            height: 58px;
            object-fit: cover;
            border: 1px solid var(--order-border);
            border-radius: 6px;
            background: #fff;
        }

        .seller-order-detail .product-name {
            color: var(--brown) !important;
            font-weight: 800;
        }

        .seller-order-detail .addon-list {
            margin: 6px 0 0;
            padding-left: 18px;
            color: var(--order-muted);
        }

        .seller-order-detail .totals-wrap {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .seller-order-detail .totals-panel {
            width: min(100%, 390px);
            padding: 14px 16px;
            background: #fff;
        }

        .seller-order-detail .totals-panel .table {
            margin: 0;
        }

        .seller-order-detail .totals-panel td {
            padding: 8px 0;
            border: 0;
        }

        .seller-order-detail .total-row td {
            padding-top: 12px;
            color: var(--brown);
            border-top: 1px solid var(--order-border);
            font-size: 16px;
            font-weight: 800;
        }

        .seller-order-detail .btn-light {
            color: #685b4e;
            background: #fff;
            border: 1px solid var(--order-border);
            border-radius: 6px;
        }

        @media (max-width: 991.98px) {
            .seller-order-detail .status-grid,
            .seller-order-detail .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 767.98px) {
            .seller-order-detail .detail-hero {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px;
            }

            .seller-order-detail .detail-title {
                font-size: 21px;
            }

            .seller-order-detail .detail-card-body {
                padding: 14px;
            }

            .seller-order-detail .detail-header-actions,
            .seller-order-detail .detail-chip {
                width: 100%;
            }

            .seller-order-detail .totals-wrap {
                justify-content: stretch;
            }
        }
    </style>

    <div class="seller-order-detail">
        <div class="detail-hero">
            <div>
                <div class="detail-eyebrow">{{ translate('Seller Order') }}</div>
                <h1 class="detail-title">{{ translate('Order Details') }}</h1>
                <p class="detail-subtitle">{{ translate('Review customer details, ordered items, totals and fulfillment status.') }}</p>
            </div>
            <div class="detail-header-actions">
                <a href="{{ route('seller.orders.index') }}" class="back-button">
                    <i class="las la-arrow-left"></i>
                    <span>{{ translate('Back') }}</span>
                </a>
                <span class="detail-chip"><i class="las la-hashtag"></i>{{ $order->code }}</span>
                <span class="detail-chip"><i class="las la-calendar"></i>{{ date('d-m-Y h:i A', $order->date) }}</span>
            </div>
        </div>

        <div class="card detail-card">
        <div class="card-body detail-card-body">
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->orderDetails->where('seller_id', Auth::user()->id)->first()->payment_status;
                @endphp
                @if (get_setting('product_manage_by_admin') == 0)
                    <div class="status-grid">
                    <div class="status-panel">
                        <label class="detail-label" for="update_payment_status">{{ translate('Payment Status') }}</label>
                        @if (($order->payment_type == 'cash_on_delivery' || (addon_is_activated('offline_payment') == 1 && $order->manual_payment == 1)) && $payment_status == 'unpaid')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_payment_status">
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                    {{ translate('Unpaid') }}</option>
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                    {{ translate('Paid') }}</option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ translate($payment_status) }}" disabled>
                        @endif
                    </div>
                    <div class="status-panel">
                        <label class="detail-label" for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                        @if ($delivery_status != 'delivered' && $delivery_status != 'cancelled')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_delivery_status">
                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                    {{ translate('Pending') }}</option>
                                <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                    {{ translate('Confirmed') }}</option>
                                <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                    {{ translate('Picked Up') }}</option>
                                <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                    {{ translate('On The Way') }}</option>
                                <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                    {{ translate('Delivered') }}</option>
                                <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                    {{ translate('Cancel') }}</option>
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}" disabled>
                        @endif
                    </div>
                    </div>
                @endif
            <div class="info-grid">
                <div class="info-panel">
                    <h5 class="panel-title">{{ translate('Shipping Address') }}</h5>
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                           @if(isset(json_decode($order->shipping_address)->email)) {{ json_decode($order->shipping_address)->email }}<br> @endif
                           @if(isset(json_decode($order->shipping_address)->phone)) {{ json_decode($order->shipping_address)->phone }}<br> @endif
                           @if(isset(json_decode($order->shipping_address)->address)) {{ json_decode($order->shipping_address)->address }} @endif
                           @if(isset(json_decode($order->shipping_address)->city)) {{ json_decode($order->shipping_address)->city }} @endif
                            @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                                {{ $order->user->name }}
                            </strong><br>
                            {{ $order->user->email }}<br>
                            {{ $order->user->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}"
                            target="_blank"><img
                                src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100"></a>
                    @endif
                </div>
                <div class="info-panel">
                    <h5 class="panel-title">{{ translate('Order Summary') }}</h5>
                    <table class="summary-table">
                        <tbody>
                            <tr>
                                <td>{{ translate('Order #') }}</td>
                                <td class="text-right">{{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td>{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span
                                            class="badge badge-inline badge-success">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @else
                                        <span
                                            class="badge badge-inline badge-info">{{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>{{ translate('Order Date') }}</td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td>{{ ('Total Amount') }}</td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td>{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>

                            <tr>
                                <td>{{ translate('Additional Info') }}</td>
                                @php
                                    $additionalInfo = json_decode($order->additional_info, true);
                                    $additionalNote = is_array($additionalInfo)
                                        ? ($additionalInfo['note'] ?? '')
                                        : $order->additional_info;
                                @endphp
                                <td class="text-right">{{ $additionalNote }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <h5 class="items-section-title">{{ translate('Ordered Items') }}</h5>
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="aiz-table invoice-summary table items-table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                {{--<th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>--}}
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}"
                                                target="_blank"><img class="product-thumb"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                target="_blank"><img class="product-thumb"
                                                    src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}"></a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong><a href="{{ route('product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="product-name">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                            <small>{{ $orderDetail->variation }}</small>
									@php
    $addons = null;

    if (!empty($orderDetail->addon)) {
        $addons = json_decode($orderDetail->addon, true);
    } elseif (!empty($orderDetail->addons)) {
        $addons = json_decode($orderDetail->addons, true);
    }
@endphp

@if (!empty($addons))
    <br>
    
    <ul class="addon-list">
        @foreach ($addons as $addon)
            <li>
                {{ $addon['name'] ?? '' }}
                @if(isset($addon['price']))
                    ({{ single_price($addon['price']) }})
                @endif
            </li>
        @endforeach
    </ul>
@endif

@if ($orderDetail->shipping_cost > 0)
    <br>
    <small class="text-muted">{{ translate('Shipping') }}: {{ single_price($orderDetail->shipping_cost) }}</small>
@endif

@php
    $itemServices = [];
    if (!empty($order->additional_info)) {
        $additionalInfo = json_decode($order->additional_info, true);
        if (is_array($additionalInfo) && !empty($additionalInfo['services'])) {
            $itemServices = collect($additionalInfo['services'])->filter(function ($s) use ($orderDetail) {
                return ($s['product_id'] ?? null) == $orderDetail->product_id;
            });
        }
    }
@endphp
@if (count($itemServices) > 0)
    <br>
    <small class="text-muted">{{ translate('Services') }}: 
        @foreach ($itemServices as $s)
            {{ $s['name'] ?? 'Service' }} ({{ single_price($s['price'] ?? 0) }}){{ !$loop->last ? ', ' : '' }}
        @endforeach
    </small>
@endif
										
										
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong><a href="{{ route('auction-product', $orderDetail->product->slug) }}"
                                                    target="_blank"
                                                    class="product-name">{{ $orderDetail->product->getTranslation('name') }}</a></strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                   {{-- <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                    </td>--}}
                                    <td class="text-center">{{ $orderDetail->quantity }}</td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}</td>
                                    <td class="text-center">{{ single_price($order->grand_total) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="totals-wrap">
                <div class="totals-panel">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }}  :</strong>
                            </td>
                            <td>
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                       {{-- @if($order->igst)
                        <tr class="total-row">
                            <td>
                                <strong class="text-muted">{{ translate('IGST') }} <small>(18%)</small> :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        @else
                        @php 
                        $newTax = $order->orderDetails->sum('tax')/2;
                        @endphp
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('CGST') }} <small>(9%)</small> :</strong>
                            </td>
                            <td>
                                {{ single_price($newTax) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('SGST') }} <small>(9%)</small> :</strong>
                            </td>
                            <td>
                                {{ single_price($newTax) }}
                            </td>
                        </tr>
                        @endif--}}
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        @php
                            $servicesTotal = 0;
                            if (!empty($order->additional_info)) {
                                $additionalInfo = json_decode($order->additional_info, true);
                                if (is_array($additionalInfo)) {
                                    $servicesTotal = (float) ($additionalInfo['service_total'] ?? collect($additionalInfo['services'] ?? [])->sum('price'));
                                }
                            }
                        @endphp
                        @if ($servicesTotal > 0)
                            <tr>
                                <td>
                                    <strong class="text-muted">{{ translate('Additional Services') }} :</strong>
                                </td>
                                <td>
                                    {{ single_price($servicesTotal) }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} <small></small> :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                @if ($payment_status == 'paid')
                <div class="no-print text-right">
                    <a href="{{ route('seller.invoice.download', $order->id) }}" type="button"
                        class="btn btn-icon btn-light"><i class="las la-print"></i></a>
                </div>
                @endif
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('seller.orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                $('#order_details').modal('hide');
                AIZ.plugins.notify('success', '{{ translate('Order status has been updated') }}');
                location.reload().setTimeOut(500);
            });
        });

        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('seller.orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                $('#order_details').modal('hide');
                //console.log(data);
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                location.reload().setTimeOut(500);
            });
        });
    </script>
@endsection
