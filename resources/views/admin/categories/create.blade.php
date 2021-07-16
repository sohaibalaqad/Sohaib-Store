@extends('layouts.admin')
@section('title', 'Create new category')
@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Categories</a></li>
        <li class="breadcrumb-item active">Create</li>
    </ol>
@endsection
@section('content')
    <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data">
        {{-- CSRF Token --}}
        {{-- 1 --}}
        {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
        {{-- OR 2 --}}
        {{-- {{ csrf_field() }} --}}
        {{-- OR 3 --}}
        @csrf
        @include('admin.categories._form', [
            'button' => 'Add',
        ])
    </form>
@endsection
