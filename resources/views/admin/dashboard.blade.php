@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text">{{ $totalCategories }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <p class="card-text">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-primary">Manage Categories</a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Manage Products</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Manage Users</a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Manage Orders</a>
    </div>
</div>
@endsection
