<?php

namespace App\Http\Controllers\Api\Seller;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
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
           'descriptiom'=>'nullable',
           'image' => 'nullable|image|max:2048', // max 2MB
           'images' => 'nullable|array|max:5',
           'images.*' => 'image|max:2048',
           'custom_attributes' => 'nullable|array',
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
           'custom_attributes' => $request->custom_attributes,
       ]);

       // Handle additional images
       if ($request->hasFile('images')) {
           $position = 1;
           foreach ($request->file('images') as $image) {
               $imageName = Str::slug($request->name) . '_img_' . $position . '_' . time() . '.' . $image->getClientOriginalExtension();
               $imagePath = $image->storeAs('products', $imageName, 'public');
               ProductImage::create([
                   'product_id' => $product->id,
                   'image_path' => '/storage/' . $imagePath,
                   'position' => $position,
               ]);
               $position++;
           }
       }

       return ApiResponse::success('Product created successfully', $product->load('images'));
   }

    public function show($id)
    {
        $product = Product::where('seller_id', Auth::id())->with(['category','subcategory','reviews','images'])->findOrFail($id);
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
       'description'=>'nullable',
       'image' => 'nullable|image|max:2048',
       'images' => 'nullable|array|max:5',
       'images.*' => 'image|max:2048',
       'custom_attributes' => 'nullable|array',
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

   // Handle additional images: delete existing and add new
   if ($request->has('images')) {
       // Delete existing images
       foreach ($product->images as $img) {
           $oldPath = str_replace('/storage/', '', $img->image_path);
           Storage::disk('public')->delete($oldPath);
           $img->delete();
       }
       // Add new images
       if ($request->hasFile('images')) {
           $position = 1;
           foreach ($request->file('images') as $image) {
               $imageName = Str::slug($request->name) . '_img_' . $position . '_' . time() . '.' . $image->getClientOriginalExtension();
               $imagePathStored = $image->storeAs('products', $imageName, 'public');
               ProductImage::create([
                   'product_id' => $product->id,
                   'image_path' => '/storage/' . $imagePathStored,
                   'position' => $position,
               ]);
               $position++;
           }
       }
   }

   $product->update([
       'name' => $request->name,
       'price' => $request->price,
       'stock' => $request->stock,
       'category_id' => $request->category_id,
       'subcategory_id' => $request->subcategory_id,
       'image' => $imagePath,
       'custom_attributes' => $request->custom_attributes,
   ]);

   return ApiResponse::success('Product updated successfully', $product->load('images'));
}


    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        if($product->image) {
            $oldPath = str_replace('/storage/', '', $product->image);
            Storage::disk('public')->delete($oldPath);
        }
        // Delete additional images
        foreach ($product->images as $img) {
            $oldPath = str_replace('/storage/', '', $img->image_path);
            Storage::disk('public')->delete($oldPath);
            $img->delete();
        }
        $product->delete();
        return ApiResponse::success('Product deleted', null);
    }
}
