<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class ProductController extends Controller
{
    public function home(Request $request)
    {
        $popularProducts = Product::where('is_popular', true)->with('seller', 'reviews', 'images')->latest()->take(10)->get();
        $recentlyAdded = Product::latest()->with('seller', 'reviews', 'images')->take(10)->get();

        return ApiResponse::success('Home data retrieved successfully', [
            'popular_products' => $popularProducts,
            'recently_added' => $recentlyAdded,
        ]);
    }

    public function index(Request $request){
        $query = Product::query();

        if($request->has('search')) $query->where('name','like','%'.$request->search.'%');
        if($request->has('min_price')) $query->where('price','>=',$request->min_price);
        if($request->has('max_price')) $query->where('price','<=',$request->max_price);

        $products = $query->latest()->with('seller','reviews','images')->get();

        return ApiResponse::success('Products retrieved successfully',$products);
    }

    public function show($id){
        $product = Product::with('seller','reviews','images')->findOrFail($id);
        return ApiResponse::success('Product details retrieved successfully',$product);
    }
}
