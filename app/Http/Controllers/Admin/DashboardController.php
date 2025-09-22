<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get some stats for dashboard
        $totalUsers = \App\Models\User::count();
        $totalCategories = \App\Models\Category::count();
        $totalProducts = \App\Models\Product::count();
        $totalOrders = \App\Models\Order::count();

        return view('admin.dashboard', compact('totalUsers', 'totalCategories', 'totalProducts', 'totalOrders'));
    }
}
