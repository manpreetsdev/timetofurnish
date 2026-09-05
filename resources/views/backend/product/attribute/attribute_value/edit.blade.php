@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <h5 class="mb-0 h6">{{ translate('Attribute Value Information') }}</h5>
    </div>

    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body p-0">

                <form class="p-4" action="{{ route('update-attribute-value', $attribute_value->id) }}" method="POST">
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" name="attribute_id" value="{{ $attribute_value->attribute_id }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="Attribute Value">
                            {{ translate('Attribute Value') }}
                        </label>
                        <div class="col-sm-9">
                            <input type="text" placeholder="{{ translate('Attribute Value') }}" id="value"
                                name="value" class="form-control" required value="{{ $attribute_value->value }}">
                        </div>
                    </div>

                    @if($attribute_value->attribute && $attribute_value->attribute->type == 'image')
                    <div class="form-group row">
                        <label class="col-sm-3 col-from-label" for="image">
                            {{ translate('Image') }}
                        </label>
                        <div class="col-sm-9">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="image" class="selected-files" value="{{ $attribute_value->image }}">
                            </div>
                            <div class="file-preview box sm">
                                @if($attribute_value->image)
                                    <div class="d-flex justify-content-between align-items-center mt-2 file-preview-item" data-id="{{ $attribute_value->image }}">
                                        <div class="align-items-center align-self-stretch d-flex justify-content-center thumb">
                                            <img src="{{ is_numeric($attribute_value->image) ? uploaded_asset($attribute_value->image) : my_asset($attribute_value->image) }}" class="img-fit">
                                        </div>
                                        <div class="remove"><button class="btn btn-sm btn-link remove-attachment" type="button"><i class="la la-close"></i></button></div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
