@extends('backend.layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0 h6">{{ translate('Run Migrations') }}</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    {{ translate('This will run Laravel migrations on the server. Use it only when you are sure the database is ready. Do not refresh repeatedly.') }}
                </div>

                <form action="{{ route('admin.run_migrations') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        {{ translate('Run Migrations Now') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
