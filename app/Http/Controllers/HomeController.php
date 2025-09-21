<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured categories
        $categories = Category::with('subcategories')->take(6)->get();

        // Get featured/popular products
        $featuredProducts = Product::with(['category', 'subcategory', 'images', 'seller'])
            ->where('is_popular', true)
            ->orWhere('is_favourite', true)
            ->take(8)
            ->get();

        // Get latest products
        $latestProducts = Product::with(['category', 'subcategory', 'images', 'seller'])
            ->latest()
            ->take(8)
            ->get();

        // Get active promotions
        $promotions = Promotion::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->with('product')
            ->take(4)
            ->get();

        return view('home', compact('categories', 'featuredProducts', 'latestProducts', 'promotions'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'subcategory', 'images', 'seller', 'reviews'])
            ->findOrFail($id);

        return view('product.show', compact('product'));
    }
}

