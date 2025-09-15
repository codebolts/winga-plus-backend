<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    // paginated seller products
    public function index(Request $request)
    {
        $perPage = (int)$request->get('per_page', 15);
        $products = Product::where('seller_id', Auth::id())
                    ->with(['category','subcategory'])
                    ->latest()
                    ->paginate($perPage);

        return ApiResponse::success('Products retrieved', $products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'category_id'=>'nullable|exists:categories,id',
            'subcategory_id'=>'nullable|exists:subcategories,id',
            'image'=>'nullable|image|max:4096'
        ]);

        $data = $request->only(['name','description','price','stock','category_id','subcategory_id']);
        $data['seller_id'] = Auth::id();

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('products','public');
        }

        $product = Product::create($data);
        return ApiResponse::success('Product created', $product, 201);
    }

    public function show($id)
    {
        $product = Product::where('seller_id', Auth::id())->with(['category','subcategory','reviews'])->findOrFail($id);
        return ApiResponse::success('Product retrieved', $product);
    }

    public function update(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name'=>'sometimes|string|max:255',
            'description'=>'nullable|string',
            'price'=>'sometimes|numeric|min:0',
            'stock'=>'sometimes|integer|min:0',
            'category_id'=>'nullable|exists:categories,id',
            'subcategory_id'=>'nullable|exists:subcategories,id',
            'image'=>'nullable|image|max:4096'
        ]);

        $data = $request->only(['name','description','price','stock','category_id','subcategory_id']);

        if($request->hasFile('image')){
            if($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products','public');
        }

        $product->update($data);
        return ApiResponse::success('Product updated', $product);
    }

    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        if($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return ApiResponse::success('Product deleted', null);
    }
}
