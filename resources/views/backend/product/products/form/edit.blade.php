@extends('backend.layouts.app')

@section('content')
@include('seller.product.products.form', [
    'action' => route('admin.products.update', $product->id),
    'method' => 'PUT',
    'product' => $product,
    'formRoutePrefix' => 'admin.',
    'addedBy' => 'admin',
    'attributesIndexUrl' => route('attributes.index'),
])
@endsection
