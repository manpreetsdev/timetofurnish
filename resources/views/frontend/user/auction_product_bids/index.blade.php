@extends('frontend.layouts.user_panel')

@section('panel_content')
    <div class="card shadow-none rounded-0 border">
        <div class="card-header border-bottom-0">
            <h5 class="mb-0 fs-20 fw-700 text-dark">{{ translate('Bidded Products') }}</h5>
        </div>

        <div class="card-body">
            @if ($bids->count() > 0)
                <div class="table-responsive">
                    <table class="table aiz-table mb-0">
                        <thead class="text-black fs-12">
                            <tr>
                                <th class="pl-0">{{ translate('Product') }}</th>
                                <th>{{ translate('Your Bid') }}</th>
                                <th>{{ translate('Highest Bid') }}</th>
                                <th data-breakpoints="md">{{ translate('Date') }}</th>
                                <th class="text-right pr-0">{{ translate('Options') }}</th>
                            </tr>
                        </thead>
                        <tbody class="fs-14">
                            @foreach ($bids as $bid)
                                @php
                                    $product = $bid->product;
                                    $highestBid = $product ? ($product->bids->max('amount') ?? 0) : 0;
                                @endphp
                                <tr>
                                    <td class="pl-0">
                                        @if ($product)
                                            <a href="{{ route('product', $product->slug) }}" class="text-reset hov-text-primary fw-600">
                                                {{ $product->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">{{ translate('Product unavailable') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ single_price($bid->amount) }}</td>
                                    <td>{{ single_price($highestBid) }}</td>
                                    <td>{{ date('d-m-Y h:i A', strtotime($bid->created_at)) }}</td>
                                    <td class="text-right pr-0">
                                        @if ($product)
                                            <a href="{{ route('product', $product->slug) }}" class="btn btn-soft-info btn-icon btn-circle btn-sm" title="{{ translate('View Product') }}">
                                                <i class="las la-eye" style="font-size: 20px;"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="aiz-pagination mt-3">
                    {{ $bids->links() }}
                </div>
            @else
                <div class="text-center bg-white p-4 border">
                    <img class="mw-100 h-200px" src="{{ static_asset('assets/img/nothing.svg') }}" alt="Image">
                    <h5 class="mb-0 h5 mt-3">{{ translate("There isn't anything added yet") }}</h5>
                </div>
            @endif
        </div>
    </div>
@endsection
