<?php

namespace App\Http\Controllers\Api\Seller;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('products', $imageName, 'public');
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'image' => $imagePath ? '/storage/' . $imagePath : null,
            'seller_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
            'data' => $product,
        ]);
    }

    public function show($id)
    {
        $product = Product::where('seller_id', Auth::id())->with(['category','subcategory','reviews'])->findOrFail($id);
        return ApiResponse::success('Product retrieved', $product);
    }

   public function update(Request $request, Product $product)
{
    // $this->authorize('update', $product); // optional if using policies

    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'nullable',
        'image' => 'nullable|image|max:2048',
    ]);

    $imagePath = $product->image;
    if ($request->hasFile('image')) {
        if ($imagePath) {
            // delete old image if exists
            $oldPath = str_replace('/storage/', '', $imagePath);
            Storage::disk('public')->delete($oldPath);
        }
        $image = $request->file('image');
        $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('products', $imageName, 'public');
        $imagePath = '/storage/' . $imagePath;
    }

    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'stock' => $request->stock,
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
        'image' => $imagePath,
    ]);

    return response()->json([
        'status' => 'success',
        'message' => 'Product updated successfully',
        'data' => $product
    ]);
}


    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        if($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return ApiResponse::success('Product deleted', null);
    }
}
