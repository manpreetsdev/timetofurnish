@if ($product_queries->count() > 0)
    <div class="py-3 border-bottom mb-3">
        <h4 class="fs-14 fw-700 mb-0 text-primary">
            {{ translate('Other Questions') }}
        </h4>
    </div>
@endif

<!-- Product queries -->
@forelse ($product_queries as $product_query)
    <div class="query-card border rounded-2 p-3 mb-3 bg-white shadow-sm">
        <!-- Question Row -->
        <div class="d-flex align-items-start mb-3">
            <span class="badge badge-inline text-white rounded-circle p-0 d-flex align-items-center justify-content-center mr-3" style="width: 26px; height: 26px; font-size: 12px; font-weight: 700; flex-shrink: 0; background-color: #d43533;">Q</span>
            <div class="flex-grow-1">
                <div class="fs-14 fw-600 text-dark lh-1-5">{{ strip_tags($product_query->question) }}</div>
                <div class="fs-11 text-secondary mt-1">
                    <span class="font-weight-bold">{{ $product_query->user->name }}</span>
                    @if($product_query->created_at)
                        <span class="mx-2">•</span>
                        <span>{{ date('d M Y', strtotime($product_query->created_at)) }}</span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Answer Row -->
        <div class="d-flex align-items-start pl-md-4 pt-3 border-top" style="border-top: 1px dashed #ebebeb !important;">
            <span class="badge badge-inline text-white rounded-circle p-0 d-flex align-items-center justify-content-center mr-3" style="width: 26px; height: 26px; font-size: 12px; font-weight: 700; flex-shrink: 0; background-color: #f3af3d;">A</span>
            <div class="flex-grow-1">
                @if($product_query->reply)
                    <div class="fs-14 text-dark lh-1-5">{{ strip_tags($product_query->reply) }}</div>
                    <div class="fs-11 text-secondary mt-1">
                        <span class="font-weight-bold text-warning">{{ $product_query->product->user->name }}</span>
                        <span class="badge badge-inline badge-soft-warning ml-1 fs-9">{{ translate('Seller') }}</span>
                    </div>
                @else
                    <div class="fs-14 text-secondary font-italic lh-1-5">{{ translate('Seller did not respond yet') }}</div>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-4 border rounded-2" style="background-color: #fafafb;">
        <span class="fs-24 text-secondary opacity-60"><i class="las la-comments"></i></span>
        <p class="fs-13 text-secondary mb-0 mt-2">{{ translate('No queries have been submitted for this product yet.') }}</p>
    </div>
@endforelse

<!-- Pagination -->
@if ($product_queries->lastPage() > 1)
    <div class="aiz-pagination product-queries-pagination py-2 d-flex justify-content-end">
        {{ $product_queries->links() }}
    </div>
@endif
