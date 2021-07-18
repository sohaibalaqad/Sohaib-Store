@extends('layouts.admin')
@section('title', 'Edit Category')
@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Products</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>
@endsection
@section('content')
    <form action="{{ route('products.update', $product->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        {{-- form method spoofing --}}
        {{-- Method #1 --}}
        {{-- <input type="hidden" name="_method" value="put"> --}}
        {{-- Method #2 --}}
        @method('put')
        @include('admin.products._form', [
            'button' => 'Update',
        ])
    </form>
@endsection
