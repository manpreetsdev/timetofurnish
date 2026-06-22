@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('Homepage Reviews') }}</h1>
        </div>
        <div class="col text-end" style="text-align:end;">
            <a href="{{ route('homepage-reviews.create') }}" class="btn" style="background:#685b4e;color:#ffffff;border-radius:24px;padding:10px 22px;border:1px solid #685b4e;">
                {{ translate('Add New Review') }}
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Homepage Reviews Settings') }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('homepage-reviews.update_settings') }}" method="POST">
            @csrf
            <div class="form-group row">
                <label class="col-md-4 col-from-label">{{ translate('Enable Reviews Section on Homepage') }}</label>
                <div class="col-md-8">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="hidden" name="section_status" value="0">
                        <input type="checkbox" name="section_status" value="1" @if(get_setting('homepage_reviews_section_status', 1) == 1) checked @endif>
                        <span></span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-4 col-from-label">{{ translate('Enable Slider for Desktop') }}</label>
                <div class="col-md-8">
                    <label class="aiz-switch aiz-switch-success mb-0">
                        <input type="hidden" name="desktop_slider" value="0">
                        <input type="checkbox" name="desktop_slider" value="1" @if(get_setting('homepage_reviews_desktop_slider', 1) == 1) checked @endif>
                        <span></span>
                    </label>
                    <small class="text-muted d-block mt-1">{{ translate('If disabled, reviews will be displayed as a grid layout on desktop. On mobile, it always functions as a slider.') }}</small>
                </div>
            </div>
            <div class="text-right">
                <button type="submit" class="btn text-white" style="background:#685b4e; border-color:#685b4e; border-radius: 20px; padding: 8px 24px;">{{ translate('Save Settings') }}</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header">
        <h5 class="mb-0 h6">{{ translate('Homepage Review List') }}</h5>
    </div>
    <div class="card-body">
        <table class="table aiz-table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ translate('Type') }}</th>
                    <th>{{ translate('Avatar / Image') }}</th>
                    <th>{{ translate('Name') }}</th>
                    <th>{{ translate('Rating') }}</th>
                    <th>{{ translate('Review Text') }}</th>
                    <th>{{ translate('Date') }}</th>
                    <th>{{ translate('Status') }}</th>
                    <th class="text-right" style="text-align: right;">{{ translate('Options') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $key => $review)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            @if ($review->type == 'text')
                                <span class="badge badge-inline badge-soft-primary">{{ translate('Custom Text') }}</span>
                            @else
                                <span class="badge badge-inline badge-soft-warning">{{ translate('Full Image') }}</span>
                            @endif
                        </td>
                        <td>
                            @if ($review->image != null)
                                <img src="{{ uploaded_asset($review->image) }}" class="img-fit border" style="max-height: 50px; max-width: 80px; border-radius: 4px;" alt="Review Image">
                            @else
                                <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="img-fit border" style="max-height: 50px; max-width: 50px; border-radius: 50%;" alt="Placeholder">
                            @endif
                        </td>
                        <td>{{ $review->name ?? '-' }}</td>
                        <td>
                            @if ($review->type == 'text')
                                <span class="text-warning">
                                    @for ($i = 0; $i < $review->rating; $i++)
                                        <i class="las la-star"></i>
                                    @endfor
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($review->type == 'text')
                                <span class="text-truncate-2" title="{{ $review->review_text }}">{{ Str::limit($review->review_text, 60) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if ($review->type == 'text' && $review->review_date != null)
                                {{ $review->review_date->format('Y-m-d') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input onchange="update_status(this)" value="{{ $review->id }}" type="checkbox" @if($review->status == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </td>
                        <td class="text-right" style="text-align: right;">
                            <a class="btn btn-soft-warning btn-icon btn-circle btn-sm" href="{{ route('homepage-reviews.duplicate', $review->id) }}" title="{{ translate('Duplicate') }}">
                                <i class="las la-copy"></i>
                            </a>
                            <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{ route('homepage-reviews.edit', $review->id) }}" title="{{ translate('Edit') }}">
                                <i class="las la-edit"></i>
                            </a>
                            <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{ route('homepage-reviews.destroy', $review->id) }}" title="{{ translate('Delete') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

@section('script')
    <script type="text/javascript">
        function update_status(el){
            var status = el.checked ? 1 : 0;
            $.post('{{ route('homepage-reviews.update_status') }}', {
                _token: '{{ csrf_token() }}',
                id: el.value,
                status: status
            }, function(data){
                if(data == 1){
                    AIZ.plugins.notify('success', '{{ translate('Review status updated successfully') }}');
                }
                else{
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            });
        }
    </script>
@endsection
