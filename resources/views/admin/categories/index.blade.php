@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mb-3">Add New Category</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $categories->links() }}
</div>
@endsection
