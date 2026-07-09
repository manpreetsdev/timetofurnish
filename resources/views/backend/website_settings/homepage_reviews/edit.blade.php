@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Edit Homepage Review') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('homepage-reviews.update', $review->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Review Type -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="type">{{ translate('Review Type') }}</label>
                        <div class="col-sm-9">
                            <select class="form-control aiz-selectpicker" name="type" id="type" required>
                                <option value="text" @if($review->type == 'text') selected @endif>{{ translate('Custom Review (Text & Avatar)') }}</option>
                                <option value="image" @if($review->type == 'image') selected @endif>{{ translate('Full Image Review (Uploaded Card)') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Custom Review Fields (Visible when type is text) -->
                    <div id="custom-fields">
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="name">{{ translate('Reviewer Name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('Name') }}" id="name" name="name" class="form-control" value="{{ $review->name }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="rating">{{ translate('Rating') }}</label>
                            <div class="col-sm-9">
                                <select class="form-control aiz-selectpicker" name="rating" id="rating">
                                    <option value="5" @if($review->rating == 5) selected @endif>5 {{ translate('Stars') }}</option>
                                    <option value="4" @if($review->rating == 4) selected @endif>4 {{ translate('Stars') }}</option>
                                    <option value="3" @if($review->rating == 3) selected @endif>3 {{ translate('Stars') }}</option>
                                    <option value="2" @if($review->rating == 2) selected @endif>2 {{ translate('Stars') }}</option>
                                    <option value="1" @if($review->rating == 1) selected @endif>1 {{ translate('Stars') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="review_text">{{ translate('Review Content') }}</label>
                            <div class="col-sm-9">
                                <textarea name="review_text" id="review_text" rows="5" class="form-control" placeholder="{{ translate('Write review here...') }}">{{ $review->review_text }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="review_date">{{ translate('Review Date') }}</label>
                            <div class="col-sm-9">
                                <input type="date" id="review_date" name="review_date" class="form-control" value="{{ $review->review_date ? $review->review_date->format('Y-m-d') : date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label" for="category_tag">{{ translate('Category Tag') }}</label>
                            <div class="col-sm-9">
                                <input type="text" placeholder="{{ translate('e.g. Bedroom Collection, Living Room') }}" id="category_tag" name="category_tag" class="form-control" value="{{ $review->category_tag }}">
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload (Used for both Avatar and Full Review Image) -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" id="image-label" for="image">{{ translate('Reviewer Avatar (Optional)') }}</label>
                        <div class="col-md-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="image" value="{{ $review->image }}" class="selected-files">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label" for="status">{{ translate('Status (Active)') }}</label>
                        <div class="col-sm-9">
                            <label class="aiz-switch aiz-switch-success mb-0">
                                <input type="checkbox" name="status" value="1" @if($review->status == 1) checked @endif>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn" style="background:#685b4e;color:#ffffff;border-radius:24px;padding:10px 22px;border:1px solid #685b4e;">
                            {{ translate('Update Review') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function() {
        function toggleFields() {
            var type = $('#type').val();
            if (type === 'text') {
                $('#custom-fields').show();
                $('#image-label').text('{{ translate("Reviewer Avatar (Optional)") }}');
                $('#name').prop('required', true);
                $('#review_text').prop('required', true);
                $('#review_date').prop('required', true);
            } else {
                $('#custom-fields').hide();
                $('#image-label').text('{{ translate("Full Review Image (Required)") }}');
                $('#name').prop('required', false);
                $('#review_text').prop('required', false);
                $('#review_date').prop('required', false);
            }
        }

        $('#type').change(toggleFields);
        toggleFields(); // Initial call
    });
</script>
@endsection
