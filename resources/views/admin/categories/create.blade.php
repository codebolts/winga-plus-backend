@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Category</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
