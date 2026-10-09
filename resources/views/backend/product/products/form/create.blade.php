@extends('backend.layouts.app')

@section('content')
@include('seller.product.products.form', [
    'action' => route('admin.products.store'),
    'method' => 'POST',
    'formRoutePrefix' => 'admin.',
    'addedBy' => 'admin',
    'attributesIndexUrl' => route('attributes.index'),
])
@endsection
