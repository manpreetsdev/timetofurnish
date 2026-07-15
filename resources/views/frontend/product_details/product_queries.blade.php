@if(get_setting('product_query_activation') == 1)
    <div class="mb-4 mt-5 border" id="product_query" style="border-radius:10px;padding:10px">
        <div class="modern-section-bordered-wrap bg-white border p-3 p-sm-4">
            <!-- Section Header -->
            <div class="modern-section-header mb-4">
                <div class="home-section-heading-copy">
                    <h3 class="modern-section-title fs-16 fw-700 mb-0">
                        {{ translate('Product Queries') }} ({{ count($detailedProduct->product_queries) }})
                    </h3>
                    <div class="modern-section-subtitle fs-12 text-secondary mt-1">
                        {{ translate('Have a question about this product? Find answers here or ask the seller.') }}
                    </div>
                </div>
            </div>

            <!-- Login & Register Banner -->
            @guest
                <div class="alert alert-warning border-0 rounded-2 p-3 d-flex align-items-center mb-4" style="background-color: #fbf5ee; color: #855b32; gap: 12px; border-radius: 8px;">
                    <span class="fs-22 text-warning lh-1"><i class="las la-info-circle"></i></span>
                    <div class="fs-14">
                        {!! sprintf(translate('Please <a href="%s" class="fw-700 text-underline" style="color: #855b32; text-decoration: underline;">Login</a> or <a href="%s" class="fw-700 text-underline" style="color: #855b32; text-decoration: underline;">Register</a> to submit your questions to the seller.'), route('user.login'), route('user.registration')) !!}
                    </div>
                </div>
            @endguest

            <!-- Query Submit & Own Queries -->
            @auth
                <div class="query-form-wrap border rounded-3 p-3 mb-4" style="background-color: #fafafb; border-radius: 8px; border: 1px solid #ebebeb !important;">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3">
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('product-queries.store') }}" method="POST" class="mb-0">
                        @csrf
                        <input type="hidden" name="product" value="{{ $detailedProduct->id }}">
                        <div class="form-group mb-3">
                            <label class="fs-12 fw-700 text-secondary text-uppercase mb-2" style="letter-spacing: 0.5px;">{{ translate('Ask a Question') }}</label>
                            <textarea class="form-control rounded-2" rows="3" name="question"
                                placeholder="{{ translate('Write your question here...') }}" style="resize: none; font-size: 14px; padding: 12px; border: 1px solid #ced4da; border-radius: 6px;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm px-4 py-2 rounded-2" style="background: linear-gradient(90deg, #deb887 0%, #c59259 100%); border: none; color: #212529; font-weight: 600; cursor: pointer; border-radius: 6px; transition: transform 0.2s, box-shadow 0.2s;">{{ translate('Submit Question') }}</button>
                    </form>
                </div>

                <!-- Own Queries -->
                @php
                    $own_product_queries = $detailedProduct->product_queries->where('customer_id', Auth::id());
                @endphp
                @if ($own_product_queries->count() > 0)
                    <div class="question-area mb-4">
                        <div class="py-2 border-bottom mb-3">
                            <h4 class="fs-14 fw-700 mb-0 text-primary">
                                {{ translate('My Questions') }}
                            </h4>
                        </div>
                        @foreach ($own_product_queries as $product_query)
                            <div class="query-card border rounded-2 p-3 mb-3 bg-white shadow-sm" style="border: 1px solid #ebebeb !important; border-radius: 8px;">
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
                        @endforeach
                    </div>
                @endif
            @endauth

            <!-- Others Queries -->
            <div class="queries-area mb-0">
                @include('frontend.'.get_setting('homepage_select').'.partials.product_query_pagination')
            </div>
        </div>
    </div>
@endif