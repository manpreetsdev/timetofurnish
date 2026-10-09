@extends('backend.layouts.app')

@section('content')
<style>
    /* Time To Furnish #685b4e Brand Theme & White Dashboard Styling */
    .dashboard-header-banner {
        background: linear-gradient(135deg, #FFFFFF 0%, #FAF8F5 100%);
        border: 1px solid #EFECE8;
        border-radius: 16px;
        padding: 24px 28px;
        color: #2B2520;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
        border-left: 4px solid #685B4E;
    }
    .dashboard-header-banner h2 {
        font-weight: 700;
        letter-spacing: -0.3px;
        color: #685B4E;
        font-size: 24px;
    }
    .dashboard-header-banner p {
        color: #666666;
        font-size: 13.5px;
    }

    .dashboard-btn-primary {
        background-color: #685B4E !important;
        border-color: #685B4E !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(104, 91, 78, 0.2);
        transition: all 0.2s ease;
    }
    .dashboard-btn-primary:hover {
        background-color: #54493E !important;
        border-color: #54493E !important;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(104, 91, 78, 0.28);
    }

    .dashboard-btn-outline {
        background-color: #FFFFFF !important;
        border: 1px solid #685B4E !important;
        color: #685B4E !important;
        transition: all 0.2s ease;
    }
    .dashboard-btn-outline:hover {
        background-color: #F8F6F3 !important;
        border-color: #54493E !important;
        color: #54493E !important;
        transform: translateY(-1px);
    }

    /* KPI Summary Cards */
    .kpi-card {
        background: #FFFFFF;
        border: 1px solid #EFECE8;
        border-radius: 14px;
        padding: 20px;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        border-color: #D9D2C9;
    }
    .kpi-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 14px;
        background: #F5F2ED;
        color: #685B4E;
    }

    .kpi-value {
        font-size: 22px;
        font-weight: 700;
        color: #2B2520;
        line-height: 1.2;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .kpi-label {
        font-size: 11.5px;
        font-weight: 600;
        color: #7D7266;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Content Cards */
    .dashboard-card {
        background: #FFFFFF;
        border: 1px solid #EFECE8;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .dashboard-card .card-header {
        background: #FAF8F5;
        border-bottom: 1px solid #EFECE8;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .dashboard-card .card-title {
        font-size: 15px;
        font-weight: 700;
        color: #2B2520;
        margin: 0;
    }

    /* Table Styling */
    .table-recent-orders {
        margin: 0;
    }
    .table-recent-orders th {
        background: #FFFFFF;
        color: #7D7266;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 1px solid #EFECE8;
        padding: 12px 18px;
    }
    .table-recent-orders td {
        padding: 14px 18px;
        vertical-align: middle;
        font-size: 13px;
        color: #39322A;
        border-bottom: 1px solid #F5F2ED;
    }
    .table-recent-orders tr:last-child td {
        border-bottom: none;
    }

    /* Scope-Isolated Order Badges */
    .order-status-badge {
        position: static !important;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 600;
        line-height: 1.3;
        white-space: nowrap;
    }
    .order-status-badge.paid { background: #E6F4EA; color: #137333; }
    .order-status-badge.unpaid { background: #FCE8E6; color: #C5221F; }
    .order-status-badge.delivered { background: #E6F4EA; color: #137333; }
    .order-status-badge.pending { background: #FEF7E0; color: #B06000; }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .dashboard-header-banner {
            padding: 18px 20px;
        }
        .dashboard-header-banner h2 {
            font-size: 20px;
        }
        .kpi-card {
            padding: 16px;
        }
        .kpi-value {
            font-size: 18px;
        }
    }
    @media (max-width: 576px) {
        .kpi-value {
            font-size: 16px;
        }
        .kpi-label {
            font-size: 10.5px;
        }
    }
</style>

@if(auth()->user()->can('smtp_settings') && env('MAIL_USERNAME') == null && env('MAIL_PASSWORD') == null)
    <div class="mb-3">
        <div class="alert alert-warning border-0 d-flex align-items-center rounded-lg shadow-sm">
            <i class="las la-exclamation-triangle fs-20 mr-2 text-warning"></i>
            <div>
                {{ translate('Please Configure SMTP Setting to enable automated email notifications.') }}
                <a class="alert-link font-weight-bold text-dark ml-2" href="{{ route('smtp_settings.index') }}">{{ translate('Configure Now') }} &rarr;</a>
            </div>
        </div>
    </div>
@endif

@can('admin_dashboard')
{{-- Welcome Header Banner --}}
<div class="dashboard-header-banner d-flex align-items-center justify-content-between flex-wrap gap-3">
    <div class="mb-2 mb-md-0">
        <h2 class="mb-1">{{ translate('Welcome back') }}, {{ auth()->user()->name }}!</h2>
        <p class="mb-0">{{ translate('Here is what is happening with your store today.') }}</p>
    </div>
    <div class="d-flex align-items-center flex-wrap">
        <a href="{{ route('products.create') }}" class="btn btn-sm font-weight-bold px-3 py-2 rounded-lg dashboard-btn-primary">
            <i class="las la-plus mr-1"></i> {{ translate('Add New Product') }}
        </a>
        <a href="{{ route('all_orders.index') }}" class="btn btn-sm font-weight-bold ml-2 px-3 py-2 rounded-lg dashboard-btn-outline">
            <i class="las la-shopping-bag mr-1"></i> {{ translate('View Orders') }}
        </a>
    </div>
</div>

{{-- Top 6 Dynamic KPI Cards --}}
<div class="row gutters-10 mb-4">
    <div class="col-xl-2 col-md-4 col-6 mb-3 mb-xl-0">
        <div class="kpi-card">
            <div class="kpi-icon-wrap sales">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <div>
                <div class="kpi-value" title="{{ single_price($total_sales_amount) }}">{{ single_price($total_sales_amount) }}</div>
                <div class="kpi-label">{{ translate('Paid Sales') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3 mb-xl-0">
        <div class="kpi-card">
            <div class="kpi-icon-wrap orders">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($total_orders) }}</div>
                <div class="kpi-label">{{ translate('Total Orders') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3 mb-xl-0">
        <div class="kpi-card">
            <div class="kpi-icon-wrap customers">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($total_customers) }}</div>
                <div class="kpi-label">{{ translate('Customers') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3 mb-xl-0">
        <div class="kpi-card">
            <div class="kpi-icon-wrap products">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($total_products) }}</div>
                <div class="kpi-label">{{ translate('Products') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3 mb-xl-0">
        <div class="kpi-card">
            <div class="kpi-icon-wrap categories">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($total_categories) }}</div>
                <div class="kpi-label">{{ translate('Categories') }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-3 mb-xl-0">
        <div class="kpi-card">
            <div class="kpi-icon-wrap brands">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg>
            </div>
            <div>
                <div class="kpi-value">{{ number_format($total_brands) }}</div>
                <div class="kpi-label">{{ translate('Brands') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Analytics Charts Row 1: Distribution Doughnuts --}}
<div class="row gutters-10">
    <div class="col-lg-6 mb-3 mb-lg-4">
        <div class="dashboard-card h-100 mb-0">
            <div class="card-header">
                <h3 class="card-title">{{ translate('Product Distribution') }}</h3>
                <span class="badge badge-pill" style="background: #F5F2ED; color: #685B4E; font-size: 11.5px; padding: 5px 12px;">{{ number_format($total_products) }} {{ translate('Total') }}</span>
            </div>
            <div class="card-body p-4">
                <canvas id="pie-1" class="w-100" height="260"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-3 mb-lg-4">
        <div class="dashboard-card h-100 mb-0">
            <div class="card-header">
                <h3 class="card-title">{{ translate('Seller Overview') }}</h3>
                <span class="badge badge-pill" style="background: #F5F2ED; color: #685B4E; font-size: 11.5px; padding: 5px 12px;">{{ number_format($total_sellers) }} {{ translate('Sellers') }}</span>
            </div>
            <div class="card-body p-4">
                <canvas id="pie-2" class="w-100" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Analytics Charts Row 2: Category Bar Graphs --}}
<div class="row gutters-10">
    <div class="col-lg-6 mb-3 mb-lg-4">
        <div class="dashboard-card h-100 mb-0">
            <div class="card-header">
                <h3 class="card-title">{{ translate('Category Wise Product Sales') }}</h3>
            </div>
            <div class="card-body p-4">
                <canvas id="graph-1" class="w-100" height="300"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-3 mb-lg-4">
        <div class="dashboard-card h-100 mb-0">
            <div class="card-header">
                <h3 class="card-title">{{ translate('Category Wise Product Stock') }}</h3>
            </div>
            <div class="card-body p-4">
                <canvas id="graph-2" class="w-100" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Recent Orders Table --}}
<div class="dashboard-card">
    <div class="card-header">
        <h3 class="card-title">{{ translate('Recent Orders') }}</h3>
        <a href="{{ route('all_orders.index') }}" class="btn btn-sm font-weight-bold" style="color: #685B4E; background-color: #F5F2ED;">
            {{ translate('View All Orders') }} &rarr;
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-recent-orders mb-0">
                <thead>
                    <tr>
                        <th>{{ translate('Order Code') }}</th>
                        <th>{{ translate('Customer') }}</th>
                        <th>{{ translate('Amount') }}</th>
                        <th>{{ translate('Delivery Status') }}</th>
                        <th>{{ translate('Payment Status') }}</th>
                        <th>{{ translate('Date') }}</th>
                        <th class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recent_orders as $order)
                        <tr>
                            <td class="font-weight-bold text-dark">
                                <a href="{{ route('all_orders.show', encrypt($order->id)) }}" class="text-reset">
                                    {{ $order->code }}
                                </a>
                            </td>
                            <td>
                                @if ($order->user != null)
                                    <div class="font-weight-bold">{{ $order->user->name }}</div>
                                    <div class="fs-12 opacity-60">{{ $order->user->email }}</div>
                                @else
                                    <span class="text-muted">{{ translate('Guest') }}</span>
                                @endif
                            </td>
                            <td class="font-weight-bold text-dark">
                                {{ single_price($order->grand_total) }}
                            </td>
                            <td>
                                @php
                                    $status = $order->delivery_status;
                                @endphp
                                <span class="order-status-badge {{ $status == 'delivered' ? 'delivered' : 'pending' }}">
                                    {{ translate(ucfirst(str_replace('_', ' ', $status))) }}
                                </span>
                            </td>
                            <td>
                                <span class="order-status-badge {{ $order->payment_status == 'paid' ? 'paid' : 'unpaid' }}">
                                    {{ translate(ucfirst($order->payment_status)) }}
                                </span>
                            </td>
                            <td class="fs-12 text-muted">
                                {{ $order->created_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="text-right">
                                <a href="{{ route('all_orders.show', encrypt($order->id)) }}" class="btn btn-icon btn-circle btn-sm" title="{{ translate('View Order') }}" style="color: #685B4E; background-color: #F5F2ED;">
                                    <i class="las la-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                {{ translate('No orders found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Top 12 Performing Products --}}
<div class="dashboard-card">
    <div class="card-header">
        <h3 class="card-title">{{ translate('Top Selling Products') }}</h3>
    </div>
    <div class="card-body p-3">
        <div class="position-relative top-products-slider-wrap">
            <div id="top-products-slider" class="aiz-carousel gutters-10" data-items="6" data-xl-items="5" data-lg-items="4" data-md-items="3" data-sm-items="2" data-arrows="true">
                @foreach (filter_products(\App\Models\Product::where('published', 1)->orderBy('num_of_sale', 'desc'))->limit(12)->get() as $product)
                    <div class="carousel-box">
                        <div class="aiz-card-box border border-light rounded-lg shadow-sm hov-shadow-md mb-2 has-transition bg-white p-2">
                            <div class="position-relative">
                                <a href="{{ route('product', $product->slug) }}" class="d-block">
                                    <img
                                        class="img-fit lazyload mx-auto rounded"
                                        style="height: 160px; object-fit: cover;"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($product->thumbnail_img) }}"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';"
                                    >
                                </a>
                            </div>
                            <div class="pt-2 text-left">
                                <div class="fs-14">
                                    <span class="fw-700" style="color: #685B4E;">{{ home_discounted_base_price($product) }}</span>
                                    @if(home_base_price($product) != home_discounted_base_price($product))
                                        <del class="fw-600 opacity-50 fs-12 ml-1">{{ home_base_price($product) }}</del>
                                    @endif
                                </div>
                                <h3 class="fw-600 fs-12 text-truncate lh-1-4 mb-0 mt-1">
                                    <a href="{{ route('product', $product->slug) }}" class="d-block text-reset" title="{{ $product->getTranslation('name') }}">
                                        {{ $product->getTranslation('name') }}
                                    </a>
                                </h3>
                                <div class="fs-11 text-muted mt-1">
                                    {{ $product->num_of_sale }} {{ translate('sales') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endcan

@endsection

@section('script')
<script type="text/javascript">
    // Products Doughnut Chart
    AIZ.plugins.chart('#pie-1', {
        type: 'doughnut',
        data: {
            labels: [
                '{{ translate("Published Products") }}',
                '{{ translate("Seller Products") }}',
                '{{ translate("Admin Products") }}'
            ],
            datasets: [{
                data: [
                    {{ $published_products }},
                    {{ $seller_products }},
                    {{ $admin_products }}
                ],
                backgroundColor: [
                    '#685B4E',
                    '#A39587',
                    '#D9D2C9'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            cutoutPercentage: 72,
            legend: {
                position: 'bottom',
                labels: {
                    fontFamily: 'Inter, sans-serif',
                    boxWidth: 12,
                    usePointStyle: true,
                    padding: 16
                }
            }
        }
    });

    // Sellers Doughnut Chart
    AIZ.plugins.chart('#pie-2', {
        type: 'doughnut',
        data: {
            labels: [
                '{{ translate("Approved Sellers") }}',
                '{{ translate("Pending Sellers") }}'
            ],
            datasets: [{
                data: [
                    {{ $approved_sellers }},
                    {{ $pending_sellers }}
                ],
                backgroundColor: [
                    '#685B4E',
                    '#D9D2C9'
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            cutoutPercentage: 72,
            legend: {
                position: 'bottom',
                labels: {
                    fontFamily: 'Inter, sans-serif',
                    boxWidth: 12,
                    usePointStyle: true,
                    padding: 16
                }
            }
        }
    });

    // Category Sales Bar Chart
    AIZ.plugins.chart('#graph-1', {
        type: 'bar',
        data: {
            labels: @json($category_names),
            datasets: [{
                label: '{{ translate("Number of Sales") }}',
                data: @json($category_sales),
                backgroundColor: '#685B4E',
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: { color: '#F5F2ED', zeroLineColor: '#F5F2ED' },
                    ticks: { fontColor: '#7D7266', fontFamily: 'Inter, sans-serif', fontSize: 11, beginAtZero: true }
                }],
                xAxes: [{
                    gridLines: { display: false },
                    ticks: { fontColor: '#7D7266', fontFamily: 'Inter, sans-serif', fontSize: 11 }
                }]
            },
            legend: { display: false }
        }
    });

    // Category Stock Bar Chart
    AIZ.plugins.chart('#graph-2', {
        type: 'bar',
        data: {
            labels: @json($category_names),
            datasets: [{
                label: '{{ translate("Number of Stock") }}',
                data: @json($category_stocks),
                backgroundColor: '#685B4E',
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    gridLines: { color: '#F5F2ED', zeroLineColor: '#F5F2ED' },
                    ticks: { fontColor: '#7D7266', fontFamily: 'Inter, sans-serif', fontSize: 11, beginAtZero: true }
                }],
                xAxes: [{
                    gridLines: { display: false },
                    ticks: { fontColor: '#7D7266', fontFamily: 'Inter, sans-serif', fontSize: 11 }
                }]
            },
            legend: { display: false }
        }
    });
</script>
@endsection