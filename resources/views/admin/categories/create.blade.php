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
    <form action="{{ route('categories.store') }}" method="post">

        {{-- CSRF Token --}}
        {{-- 1 --}}
        {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
        {{-- OR 2 --}}
        {{-- {{ csrf_field() }} --}}
        {{-- OR 3 --}}
        @csrf

        <div class="form-grou">
            <label for="">Category Name</label>
            <input type="text" class="form-control" name="name">
        </div>
        <div class="form-grou">
            <label for="">Parent</label>
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">No Parent</option>
                @foreach ($parents as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-grou">
            <label for="">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-grou">
            <label for="">Image</label>
            <input type="file" class="form-control" name="image">
        </div>
        <br>
        <div class="form-grou">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
@endsection
