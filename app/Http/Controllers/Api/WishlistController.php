<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // Get user's wishlist
    public function index()
    {
        $wishlist = Wishlist::where('buyer_id', Auth::id())
            ->with(['product' => function($query) {
                $query->with('seller', 'images');
            }])
            ->get();

        return ApiResponse::success('Wishlist retrieved successfully', $wishlist);
    }

    // Add product to wishlist
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $userId = Auth::id();

        // Check if product exists and is not already in wishlist
        $existing = Wishlist::where('buyer_id', $userId)
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return ApiResponse::error('Product already in wishlist', null, 400);
        }

        $wishlistItem = Wishlist::create([
            'buyer_id' => $userId,
            'product_id' => $request->product_id
        ]);

        return ApiResponse::success('Product added to wishlist', $wishlistItem->load('product'), 201);
    }

    // Remove product from wishlist
    public function destroy($productId)
    {
        $wishlistItem = Wishlist::where('buyer_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if (!$wishlistItem) {
            return ApiResponse::error('Product not found in wishlist', null, 404);
        }

        $wishlistItem->delete();

        return ApiResponse::success('Product removed from wishlist');
    }

    // Check if product is in wishlist
    public function check($productId)
    {
        $exists = Wishlist::where('buyer_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return ApiResponse::success('Wishlist check completed', [
            'in_wishlist' => $exists
        ]);
    }

    // Move product from wishlist to cart
    public function moveToCart(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'integer|min:1'
        ]);

        $userId = Auth::id();
        $quantity = $request->get('quantity', 1);

        // Check if product is in wishlist
        $wishlistItem = Wishlist::where('buyer_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlistItem) {
            return ApiResponse::error('Product not found in wishlist', null, 404);
        }

        // Get or create cart
        $cart = \App\Models\Cart::firstOrCreate(['buyer_id' => $userId]);

        // Add to cart
        $cartItem = \App\Models\CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $productId
            ],
            ['quantity' => $quantity]
        );

        // Remove from wishlist
        $wishlistItem->delete();

        return ApiResponse::success('Product moved to cart', $cartItem->load('product'));
    }
}
