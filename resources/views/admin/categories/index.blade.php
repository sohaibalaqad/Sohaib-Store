@extends('layouts.admin')
@section('title')
    {{ $title }} <a href="{{ route('categories.create') }}">Create</a>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Categories</li>
    </ol>
@endsection
@section('content')

    {{-- @if ($success)
        <div class="alert alert-success">
            {{ $success }}
        </div>
    @endif --}}
    {{-- OR --}}
    @if (Session::has('success'))
        <div class="alert alert-success">
            {{ Session()->get('success') }}
        </div>
    @endif
    
    <table class="table">
        <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Parent Id</th>
                <th>Status</th>
                <th>Created At</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category) 
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->parent_name }}</td>
                    <td>{{ $category->status }}</td>
                    <td>{{ $category->created_at }}</td>
                    <td><a href="{{ route('categories.edit',  $category->id) }}" class="btn btn-sm btn-dark">Edit</a></td>
                    <td><form action="{{ route('categories.destroy',  $category->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
