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
            ->with(['category', 'subcategory', 'images', 'reviews'])
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
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',   // ✅ 2MB main image
            'images.*' => 'image|max:20480',        // ✅ additional images
            'custom_attributes' => 'nullable',
        ]);

        // Handle custom_attributes: ensure it's an array
        $customAttributes = $request->custom_attributes;
        if (is_string($customAttributes)) {
            $customAttributes = json_decode($customAttributes, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ApiResponse::error('Invalid custom_attributes format', 400);
            }
        }
        if (!is_array($customAttributes) && !is_null($customAttributes)) {
            return ApiResponse::error('custom_attributes must be an array or null', 400);
        }

        // --- Handle main image ---
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $storedPath = $image->storeAs('products', $imageName, 'public');
            $imagePath = '/storage/' . $storedPath;
        }

        // --- Create product record ---
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'image' => $imagePath,
            'seller_id' => Auth::id(),
            'custom_attributes' => $customAttributes,
            'description' => $request->description,
        ]);

        // --- Handle additional images ---
        if ($request->hasFile('images')) {
            $position = 1;
            foreach ($request->file('images') as $image) {
                $imageName = Str::slug($request->name) . '_img_' . $position . '_' . time() . '.' . $image->getClientOriginalExtension();
                $storedPath = $image->storeAs('products', $imageName, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => '/storage/' . $storedPath,
                    'position' => $position,
                ]);

                $position++;
            }
        }

        return ApiResponse::success('Product created successfully', $product->load('images'));
    }


    public function show($id)
    {
        $product = Product::where('seller_id', Auth::id())->with(['category', 'subcategory', 'reviews', 'images'])->findOrFail($id);
        return ApiResponse::success('Product retrieved', $product);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'image|max:20480', // allow multiple but optional
            'custom_attributes' => 'nullable',
        ]);

        // Handle custom_attributes: ensure it's an array
        $customAttributes = $request->custom_attributes;
        if (is_string($customAttributes)) {
            $customAttributes = json_decode($customAttributes, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return ApiResponse::error('Invalid custom_attributes format', 400);
            }
        }
        if (!is_array($customAttributes) && !is_null($customAttributes)) {
            return ApiResponse::error('custom_attributes must be an array or null', 400);
        }

        // --- Handle main image ---
        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                $oldPath = str_replace('/storage/', '', $imagePath);
                Storage::disk('public')->delete($oldPath);
            }

            $image = $request->file('image');
            $imageName = Str::slug($request->name) . '_' . time() . '.' . $image->getClientOriginalExtension();
            $storedPath = $image->storeAs('products', $imageName, 'public');
            $imagePath = '/storage/' . $storedPath;
        }

        // --- Handle additional images ---
        if ($request->hasFile('images')) {
            // Delete existing images first
            foreach ($product->images as $img) {
                $oldPath = str_replace('/storage/', '', $img->image_path);
                Storage::disk('public')->delete($oldPath);
                $img->delete();
            }

            // Save new images
            $position = 1;
            foreach ($request->file('images') as $image) {
                $imageName = Str::slug($request->name) . '_img_' . $position . '_' . time() . '.' . $image->getClientOriginalExtension();
                $storedPath = $image->storeAs('products', $imageName, 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => '/storage/' . $storedPath,
                    'position' => $position,
                ]);

                $position++;
            }
        }

        // --- Update product record ---
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'image' => $imagePath,
            'custom_attributes' => $customAttributes,
            'description' => $request->description,
        ]);

        return ApiResponse::success('Product updated successfully', $product->load('images'));
    }


    public function destroy($id)
    {
        $product = Product::where('seller_id', Auth::id())->findOrFail($id);
        if ($product->image) {
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
