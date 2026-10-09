@extends('backend.layouts.app')

@section('content')
    @php
        $activeFilters = collect([
            'delivery_status' => $delivery_status,
            'payment_status' => $payment_status,
            'date' => $date,
            'search' => $sort_search,
        ])->filter(function ($value) {
            return $value !== null && $value !== '';
        });

        $pageOrders = $orders->getCollection();
        $pageRevenue = $pageOrders->sum('grand_total');
        $pagePaid = $pageOrders->where('payment_status', 'paid')->count();
        $pagePending = $pageOrders->where('delivery_status', 'pending')->count();
        $filterPanelOpen = $activeFilters->isNotEmpty();
    @endphp

    <div class="orders-admin-screen">
        <form class="" action="" id="sort_orders" method="GET">
            <div class="orders-admin-hero">
                <div class="orders-hero-main">
                    <span class="orders-eyebrow">{{ translate('Operations') }}</span>
                    <h1>{{ translate('All Orders') }}</h1>
                    <p>{{ translate('Track orders, invoice copies, payments and fulfilment from one workspace.') }}</p>
                </div>

                <div class="orders-hero-actions">
                    <button class="btn orders-filter-trigger" type="button" data-toggle="collapse"
                        data-target="#ordersFilterPanel" aria-expanded="{{ $filterPanelOpen ? 'true' : 'false' }}"
                        aria-controls="ordersFilterPanel">
                        <i class="las la-sliders-h"></i>
                        {{ translate('Filters') }}
                        @if ($activeFilters->count() > 0)
                            <span>{{ $activeFilters->count() }}</span>
                        @endif
                    </button>

                    @if (Route::currentRouteName() == 'all_orders.index')
                        <a href="{{ route('all_orders.export_with_invoices', request()->query()) }}"
                            class="btn orders-export-btn">
                            <i class="las la-file-excel"></i>
                            {{ translate('Export Excel') }}
                        </a>
                    @endif

                    @can('delete_order')
                        <div class="dropdown">
                            <button class="btn orders-bulk-btn dropdown-toggle" type="button" data-toggle="dropdown">
                                <i class="las la-layer-group"></i>
                                {{ translate('Bulk') }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item confirm-alert" href="javascript:void(0)" data-target="#bulk-delete-modal">
                                    {{ translate('Delete selection') }}
                                </a>
                            </div>
                        </div>
                    @endcan
                </div>
            </div>

            <div class="orders-summary-grid">
                <div class="orders-summary-card">
                    <span>{{ translate('Total Orders') }}</span>
                    <strong>{{ $orders->total() }}</strong>
                </div>
                <div class="orders-summary-card">
                    <span>{{ translate('This Page Revenue') }}</span>
                    <strong>{{ single_price($pageRevenue) }}</strong>
                </div>
                <div class="orders-summary-card">
                    <span>{{ translate('Paid On Page') }}</span>
                    <strong>{{ $pagePaid }}</strong>
                </div>
                <div class="orders-summary-card">
                    <span>{{ translate('Pending On Page') }}</span>
                    <strong>{{ $pagePending }}</strong>
                </div>
            </div>

            @if ($activeFilters->isNotEmpty())
                <div class="orders-active-filters">
                    <span>{{ translate('Active filters') }}</span>
                    @foreach ($activeFilters as $key => $value)
                        <strong>{{ translate(ucfirst(str_replace('_', ' ', $key))) }}: {{ translate(ucfirst(str_replace('_', ' ', $value))) }}</strong>
                    @endforeach
                    <a href="{{ url()->current() }}">{{ translate('Clear all') }}</a>
                </div>
            @endif

            <div class="collapse {{ $filterPanelOpen ? 'show' : '' }}" id="ordersFilterPanel">
                <div class="orders-filter-panel">
                    <div class="orders-filter-panel-header">
                        <div>
                            <h2>{{ translate('Filter orders') }}</h2>
                            <p>{{ translate('Use status, payment, date and order code filters together.') }}</p>
                        </div>
                        <a href="{{ url()->current() }}" class="orders-clear-link">{{ translate('Reset') }}</a>
                    </div>

                    <div class="orders-filter-grid">
                        <div class="order-filter-control">
                            <label>{{ translate('Delivery Status') }}</label>
                            <select class="form-control aiz-selectpicker" name="delivery_status" id="delivery_status">
                                <option value="">{{ translate('All delivery statuses') }}</option>
                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>{{ translate('Pending') }}</option>
                                <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>{{ translate('Confirmed') }}</option>
                                <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>{{ translate('Picked Up') }}</option>
                                <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>{{ translate('On The Way') }}</option>
                                <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>{{ translate('Delivered') }}</option>
                                <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>{{ translate('Cancel') }}</option>
                            </select>
                        </div>
                        <div class="order-filter-control">
                            <label>{{ translate('Payment Status') }}</label>
                            <select class="form-control aiz-selectpicker" name="payment_status" id="payment_status">
                                <option value="">{{ translate('All payment statuses') }}</option>
                                <option value="paid" @isset($payment_status) @if ($payment_status == 'paid') selected @endif @endisset>{{ translate('Paid') }}</option>
                                <option value="unpaid" @isset($payment_status) @if ($payment_status == 'unpaid') selected @endif @endisset>{{ translate('Unpaid') }}</option>
                            </select>
                        </div>
                        <div class="order-filter-control">
                            <label>{{ translate('Date Range') }}</label>
                            <input type="text" class="aiz-date-range form-control" value="{{ $date }}"
                                name="date" placeholder="{{ translate('Select date range') }}" data-format="DD-MM-Y"
                                data-separator=" to " data-advanced-range="true" autocomplete="off">
                        </div>
                        <div class="order-filter-control orders-filter-search">
                            <label>{{ translate('Order Code') }}</label>
                            <input type="text" class="form-control" id="search"
                                name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset
                                placeholder="{{ translate('Search order code') }}">
                        </div>
                    </div>

                    <div class="orders-filter-actions">
                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                <i class="las la-filter"></i>
                                {{ translate('Apply filters') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="orders-table-card">
                <div class="orders-table-card-head">
                    <div>
                        <h2>{{ translate('Orders') }}</h2>
                        <p>{{ translate('Showing') }} {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} {{ translate('of') }} {{ $orders->total() }}</p>
                    </div>
                </div>
                <x-admin.table min-width="1400px" class="orders-table">
                    <thead>
                        <tr>
                            <!--<th>#</th>-->
                            @if (auth()->user()->can('delete_order'))
                                <th>
                                    <div class="form-group">
                                        <div class="aiz-checkbox-inline">
                                            <label class="aiz-checkbox">
                                                <input type="checkbox" class="check-all">
                                                <span class="aiz-square-check"></span>
                                            </label>
                                        </div>
                                    </div>
                                </th>
                            @else
                                <th>#</th>
                            @endif

                            <th>{{ translate('Order') }}</th>
                            <th>{{ translate('Customer') }}</th>
                            <th>{{ translate('Seller') }}</th>
                            <th class="text-center">{{ translate('Items') }}</th>
                            <th class="text-right">{{ translate('Amount') }}</th>
                            <th>{{ translate('Payment') }}</th>
                            <th>{{ translate('Delivery') }}</th>
                            <th class="order-invoices-heading">{{ translate('Invoices') }}</th>
                            @if (addon_is_activated('refund_request'))
                                <th>{{ translate('Refund') }}</th>
                            @endif
                            <th class="text-center">{{ translate('Delivery Note') }}</th>
                            <th class="text-right order-options-heading">{{ translate('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $key => $order)
                            <tr>
                                @if (auth()->user()->can('delete_order'))
                                    <td>
                                        <div class="form-group">
                                            <div class="aiz-checkbox-inline">
                                                <label class="aiz-checkbox">
                                                    <input type="checkbox" class="check-one" name="id[]"
                                                        value="{{ $order->id }}">
                                                    <span class="aiz-square-check"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                @else
                                    <td>{{ $key + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                @endif
                                <td>
                                    <div class="order-code-cell">
                                        <strong>{{ $order->code }}</strong>
                                        <span>{{ date('d M Y', $order->date) }}</span>
                                    </div>
                                    <div class="order-row-badges">
                                        @if ($order->viewed == 0)
                                            <span class="badge badge-inline badge-info">{{ translate('New') }}</span>
                                        @endif
                                        @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                            <span class="badge badge-inline badge-danger">{{ translate('POS') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if ($order->user != null)
                                        <div class="order-party-name">{{ $order->user->name }}</div>
                                    @else
                                        <div class="order-party-name">{{ translate('Guest') }}</div>
                                        <div class="order-party-meta">#{{ $order->guest_id }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($order->shop)
                                        <div class="order-party-name">{{ $order->shop->name }}</div>
                                    @else
                                        <div class="order-party-name">{{ translate('Inhouse Order') }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="order-count-pill">{{ count($order->orderDetails) }}</span>
                                </td>
                                <td class="text-right">
                                    <strong class="order-amount">{{ single_price($order->grand_total) }}</strong>
                                </td>
                                <td>
                                    @if ($order->payment_status == 'paid')
                                        <span class="order-status-pill status-success">{{ translate('Paid') }}</span>
                                    @else
                                        <span class="order-status-pill status-danger">{{ translate('Unpaid') }}</span>
                                    @endif
                                    <div class="order-payment-method">
                                        {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $deliveryBadgeClass = [
                                            'delivered' => 'status-success',
                                            'cancelled' => 'status-danger',
                                            'pending' => 'status-warning',
                                            'confirmed' => 'status-info',
                                            'picked_up' => 'status-info',
                                            'on_the_way' => 'status-info',
                                        ][$order->delivery_status] ?? 'status-muted';
                                    @endphp
                                    <span class="order-status-pill {{ $deliveryBadgeClass }}">
                                        {{ translate(ucfirst(str_replace('_', ' ', $order->delivery_status))) }}
                                    </span>
                                </td>
                                <td class="order-invoice-actions">
                                    <div class="invoice-copy-list">
                                    @foreach (\App\Services\OrderInvoiceService::copyTypes() as $copyType => $copy)
                                        @php
                                            $invoice = $order->relationLoaded('invoices') ? $order->invoices->firstWhere('copy_type', $copyType) : null;
                                        @endphp
                                        <div class="invoice-copy-row" style="--invoice-color: {{ $copy['color'] }};">
                                            <div class="invoice-copy-name">
                                                <span class="invoice-copy-dot"></span>
                                                <span>{{ translate($copy['label']) }}</span>
                                                @if ($invoice)
                                                    <i class="las la-check-circle" title="{{ translate('Generated') }}"></i>
                                                @endif
                                            </div>
                                            <div class="invoice-copy-actions">
                                                <a target="_blank" title="{{ translate('View PDF') }}"
                                                    href="{{ route('orders.invoice.copy.view', [$order->id, $copyType]) }}">
                                                    <i class="las la-eye"></i>
                                                </a>
                                                <a title="{{ translate('Download PDF') }}"
                                                    href="{{ route('orders.invoice.copy.download', [$order->id, $copyType]) }}">
                                                    <i class="las la-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>
                                </td>
                                @if (addon_is_activated('refund_request'))
                                    <td>
                                        @if (count($order->refund_requests) > 0)
                                            {{ count($order->refund_requests) }} {{ translate('Refund') }}
                                        @else
                                            {{ translate('No Refund') }}
                                        @endif
                                    </td>
                                @endif
                                <td class="text-center">
                                    <a class="order-delivery-note-btn" target="_blank"
                                        href="{{ route('orders.delivery_note', [$order->id, 'format' => 'html']) }}">
                                        <i class="las la-truck"></i>
                                        {{ translate('Delivery Note') }}
                                    </a>
                                </td>
                                <td class="text-right order-row-actions-cell">
                                    <div class="order-row-actions">
                                    @if (addon_is_activated('pos_system') && $order->order_from == 'pos')
                                        <a class="order-action-btn action-print"
                                            href="{{ route('admin.invoice.thermal_printer', $order->id) }}" target="_blank"
                                            title="{{ translate('Thermal Printer') }}">
                                            <i class="las la-print"></i>
                                        </a>
                                    @endif
                                    @can('view_order_details')
                                        @php
                                            $order_detail_route = route('orders.show', encrypt($order->id));
                                            if (Route::currentRouteName() == 'seller_orders.index') {
                                                $order_detail_route = route('seller_orders.show', encrypt($order->id));
                                            } elseif (Route::currentRouteName() == 'pick_up_point.index') {
                                                $order_detail_route = route('pick_up_point.order_show', encrypt($order->id));
                                            }
                                            if (Route::currentRouteName() == 'inhouse_orders.index') {
                                                $order_detail_route = route('inhouse_orders.show', encrypt($order->id));
                                            }
                                        @endphp
                                        <a class="order-action-btn action-view"
                                            href="{{ $order_detail_route }}" title="{{ translate('View') }}">
                                            <i class="las la-eye"></i>
                                        </a>
                                    @endcan
                                    
                                   @if ($order->payment_status == 'paid')
                                    <a class="order-action-btn action-download"
                                        href="{{ route('orders.invoice.copy.download', [$order->id, 'customer']) }}"
                                        title="{{ translate('Download Customer Invoice') }}">
                                        <i class="las la-download"></i>
                                    </a>
                                    @endif
                                    
                                    @if ($order->delivery_status == 'pending' && $order->payment_status == 'unpaid')
                                        <a href="javascript:void(0)" class="order-action-btn action-cancel confirm-delete" data-href="{{route('purchase_history.destroy', $order->id)}}" title="{{ translate('Cancel') }}">
                                            <i class="las la-times"></i>

                                        </a>
                                    @endif
                                    
                                    
                                    @can('delete_order')
                                        <a href="#"
                                            class="order-action-btn action-delete confirm-delete"
                                            data-href="{{ route('orders.destroy', $order->id) }}"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </a>
                                    @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-admin.table>

                <div class="aiz-pagination">
                    {{ $orders->appends(request()->input())->links() }}
                </div>

            </div>
        </form>
    </div>
@endsection

@section('modal')
    <!-- Delete modal -->
    @include('modals.delete_modal')
    <!-- Bulk Delete modal -->
    @include('modals.bulk_delete_modal')
@endsection
<style>
    .orders-admin-screen {
        color: #39322a;
    }

    .orders-admin-hero,
    .orders-summary-card,
    .orders-filter-panel,
    .orders-table-card {
        background: #fff;
        border: 1px solid #e8e5e1;
        border-radius: 10px;
        box-shadow: 0 10px 28px rgba(104, 91, 78, 0.06);
    }

    .orders-admin-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        padding: 24px;
        margin-bottom: 14px;
    }

    .orders-eyebrow {
        display: block;
        color: #8c7e70;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .orders-hero-main h1 {
        margin: 3px 0 5px;
        color: #39322a;
        font-size: 26px;
        font-weight: 800;
        line-height: 1.15;
    }

    .orders-hero-main p {
        max-width: 620px;
        margin: 0;
        color: #8c7e70;
        font-size: 13px;
        line-height: 1.45;
    }

    .orders-hero-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 10px;
    }

    .orders-admin-screen .orders-filter-trigger,
    .orders-admin-screen .orders-export-btn,
    .orders-admin-screen .orders-bulk-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 40px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 700;
        padding: 0 14px;
        white-space: nowrap;
    }

    .orders-admin-screen .orders-filter-trigger {
        background: #685b4e;
        color: #fff;
    }

    .orders-filter-trigger:hover,
    .orders-filter-trigger:focus {
        color: #fff;
    }

    .orders-filter-trigger span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 20px;
        margin-left: 6px;
        border-radius: 999px;
        background: #fff;
        color: #685b4e;
        font-size: 11px;
    }

    .orders-admin-screen .orders-export-btn {
        background: #f3efe9;
        border: 1px solid #dcd4c9;
        color: #685b4e;
    }

    .orders-export-btn:hover,
    .orders-bulk-btn:hover {
        background: #ece5db;
        color: #685b4e;
    }

    .orders-admin-screen .orders-bulk-btn {
        background: #faf8f5;
        border: 1px solid #e8e5e1;
        color: #4a4239;
    }

    .orders-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .orders-summary-card {
        padding: 16px;
    }

    .orders-summary-card span {
        display: block;
        color: #8c7e70;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: uppercase;
    }

    .orders-summary-card strong {
        display: block;
        margin-top: 6px;
        color: #39322a;
        font-size: 21px;
        font-weight: 800;
        line-height: 1.2;
    }

    .orders-active-filters {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        padding: 12px 14px;
        border: 1px solid #e8e3dd;
        border-radius: 8px;
        background: #faf8f5;
        color: #685b4e;
        font-size: 12px;
    }

    .orders-active-filters span {
        font-weight: 800;
    }

    .orders-active-filters strong {
        padding: 5px 8px;
        border-radius: 999px;
        background: #fff;
        color: #685b4e;
        font-size: 11px;
        font-weight: 700;
    }

    .orders-active-filters a {
        color: #b42318;
        font-weight: 800;
        text-decoration: none;
    }

    .orders-filter-panel {
        padding: 18px;
        margin-bottom: 14px;
    }

    .orders-filter-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 15px;
    }

    .orders-filter-panel-header h2 {
        margin: 0 0 4px;
        color: #39322a;
        font-size: 16px;
        font-weight: 800;
    }

    .orders-filter-panel-header p {
        margin: 0;
        color: #8c7e70;
        font-size: 12px;
    }

    .orders-clear-link {
        color: #b42318;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .orders-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .orders-filter-search {
        grid-column: span 1;
    }

    .order-filter-control label {
        display: block;
        margin-bottom: 6px;
        color: #4a4239;
        font-size: 12px;
        font-weight: 800;
    }

    .order-filter-control .form-control,
    .order-filter-control .bootstrap-select,
    .order-filter-control .bootstrap-select > .dropdown-toggle {
        width: 100% !important;
        min-height: 42px;
        border-color: #d9d4cc;
        border-radius: 7px;
        box-shadow: none;
        font-size: 13px;
    }

    .orders-filter-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 14px;
    }

    .orders-filter-actions .btn {
        min-height: 42px;
        border-radius: 7px;
        font-weight: 800;
        padding: 0 18px;
    }

    .orders-table-card {
        padding: 18px;
    }

    .orders-table-card-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .orders-table-card-head h2 {
        margin: 0;
        color: #39322a;
        font-size: 16px;
        font-weight: 700;
    }

    .orders-table-card-head p {
        margin: 2px 0 0;
        color: #8c7e70;
        font-size: 12px;
    }

    .orders-table .order-payment-method {
        display: block;
        margin-top: 5px;
    }

    .orders-table th:first-child,
    .orders-table td:first-child {
        width: 44px;
    }






    .order-code-cell strong {
        display: block;
        color: #39322a;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .order-code-cell span,
    .order-party-meta,
    .order-payment-method {
        color: #8c7e70;
        font-size: 11px;
    }

    .order-row-badges {
        margin-top: 6px;
    }

    .order-count-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 30px;
        height: 30px;
        border-radius: 999px;
        background: #f3efe9;
        color: #4a4239;
        font-size: 12px;
        font-weight: 800;
    }

    .order-party-name {
        max-width: 170px;
        overflow: hidden;
        color: #39322a;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .order-amount {
        color: #39322a;
        font-size: 13px;
        font-weight: 800;
        white-space: nowrap;
    }

    .order-status-pill {
        display: inline-block;
        min-width: 78px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        line-height: 1.2;
        text-align: center;
        white-space: nowrap;
    }

    .status-success {
        background: #e8f7ee;
        color: #137333;
    }

    .status-danger {
        background: #fdecec;
        color: #b42318;
    }

    .status-warning {
        background: #fff6df;
        color: #9a6700;
    }

    .status-info {
        background: #eaf2ff;
        color: #175cd3;
    }

    .status-muted {
        background: #f3efe9;
        color: #685b4e;
    }

    .order-invoices-heading,
    .order-options-heading {
        color: #685b4e !important;
    }

    .order-invoice-actions {
        min-width: 210px;
    }

    .invoice-copy-list {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .invoice-copy-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-height: 0;
        padding: 3px 4px 3px 8px;
        border: 1px solid #e8e5e1;
        border-radius: 7px;
        background: #fff;
    }

    .invoice-copy-name {
        display: flex;
        align-items: center;
        gap: 7px;
        min-width: 0;
        color: #39322a;
        font-size: 11px;
        font-weight: 800;
    }

    .invoice-copy-name span:not(.invoice-copy-dot) {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .invoice-copy-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: var(--invoice-color);
        flex: 0 0 7px;
    }

    .invoice-copy-name i {
        color: var(--invoice-color);
        font-size: 14px;
        flex: 0 0 auto;
    }

    .invoice-copy-actions {
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 0 0 auto;
    }

    .invoice-copy-actions a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 5px;
        color: #8c7e70;
        font-size: 14px;
        text-decoration: none;
    }

    .invoice-copy-actions a:hover {
        background: #faf8f5;
        color: var(--invoice-color);
    }

    .order-delivery-note-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 32px;
        padding: 0 12px;
        border: 1px solid #dcd4c9;
        border-radius: 8px;
        background: #f3efe9;
        color: #685b4e;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .order-delivery-note-btn i {
        font-size: 15px;
    }

    .order-delivery-note-btn:hover {
        background: #685b4e;
        border-color: #685b4e;
        color: #fff;
        text-decoration: none;
    }

    .order-row-actions-cell {
        min-width: 170px;
    }

    .order-row-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: nowrap;
        gap: 6px;
    }

    .order-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border: 1px solid #e8e5e1;
        border-radius: 7px;
        background: #fff;
        color: #8c7e70;
        font-size: 15px;
        text-decoration: none;
        transition: all 0.18s ease;
    }

    .order-action-btn:hover {
        background: #f3efe9;
        border-color: #dcd4c9;
        transform: translateY(-1px);
        text-decoration: none;
    }

    .action-view,
    .action-download {
        color: #685b4e;
    }

    .action-delete,
    .action-cancel {
        color: #f43f5e;
    }

    .action-print {
        color: #15803d;
    }

    .orders-admin-screen .aiz-pagination {
        margin-top: 18px;
    }

    .currency {
        display: inline !important;
        font-size: 14px !important;
    }

    @media (max-width: 991.98px) {
        .orders-admin-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .orders-hero-actions {
            justify-content: flex-start;
            width: 100%;
        }

        .orders-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .orders-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .order-invoice-actions {
            min-width: 250px;
        }
    }

    @media (max-width: 575.98px) {
        .orders-admin-hero,
        .orders-filter-panel,
        .orders-table-card {
            padding: 14px;
        }

        .orders-summary-grid,
        .orders-filter-grid {
            grid-template-columns: 1fr;
        }

        .orders-hero-actions .btn,
        .orders-hero-actions .dropdown {
            width: 100%;
        }

        .orders-hero-actions .btn {
            justify-content: center;
        }
    }
</style>

@section('script')
    <script type="text/javascript">
        $(document).on("change", ".check-all", function() {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function() {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function() {
                    this.checked = false;
                });
            }

        });

        //        function change_status() {
        //            var data = new FormData($('#order_form')[0]);
        //            $.ajax({
        //                headers: {
        //                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                },
        //                url: "{{ route('bulk-order-status') }}",
        //                type: 'POST',
        //                data: data,
        //                cache: false,
        //                contentType: false,
        //                processData: false,
        //                success: function (response) {
        //                    if(response == 1) {
        //                        location.reload();
        //                    }
        //                }
        //            });
        //        }
        
        

        function bulk_delete() {
            var data = new FormData($('#sort_orders')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('bulk-order-delete') }}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
