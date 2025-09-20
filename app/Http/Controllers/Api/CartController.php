<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Get user's cart
    public function index()
    {
        $cart = Cart::where('buyer_id', Auth::id())
            ->with(['items.product' => function($query) {
                $query->with('seller', 'images');
            }])
            ->first();

        if (!$cart) {
            return ApiResponse::success('Cart is empty', [
                'items' => [],
                'total_items' => 0,
                'total_price' => 0
            ]);
        }

        return ApiResponse::success('Cart retrieved successfully', [
            'id' => $cart->id,
            'items' => $cart->items,
            'total_items' => $cart->total_items,
            'total_price' => $cart->total_price
        ]);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;
        $quantity = $request->get('quantity', 1);

        // Check if product exists and has stock
        $product = Product::findOrFail($productId);
        if ($product->stock < $quantity) {
            return ApiResponse::error('Insufficient stock available', null, 400);
        }

        // Get or create cart
        $cart = Cart::firstOrCreate(['buyer_id' => $userId]);

        // Add or update cart item
        $cartItem = CartItem::updateOrCreate(
            [
                'cart_id' => $cart->id,
                'product_id' => $productId
            ],
            ['quantity' => $quantity]
        );

        return ApiResponse::success('Item added to cart', $cartItem->load('product'), 201);
    }

    // Update cart item quantity
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::whereHas('cart', function($query) {
            $query->where('buyer_id', Auth::id());
        })->with('product')->findOrFail($itemId);

        $quantity = $request->quantity;

        // Check stock availability
        if ($cartItem->product->stock < $quantity) {
            return ApiResponse::error('Insufficient stock available', null, 400);
        }

        $cartItem->update(['quantity' => $quantity]);

        return ApiResponse::success('Cart item updated', $cartItem->load('product'));
    }

    // Remove item from cart
    public function destroy($itemId)
    {
        $cartItem = CartItem::whereHas('cart', function($query) {
            $query->where('buyer_id', Auth::id());
        })->findOrFail($itemId);

        $cartItem->delete();

        return ApiResponse::success('Item removed from cart');
    }

    // Clear entire cart
    public function clear()
    {
        $cart = Cart::where('buyer_id', Auth::id())->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return ApiResponse::success('Cart cleared successfully');
    }

    // Get cart summary (for checkout)
    public function summary()
    {
        $cart = Cart::where('buyer_id', Auth::id())
            ->with(['items.product' => function($query) {
                $query->with('seller');
            }])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return ApiResponse::error('Cart is empty', null, 400);
        }

        $summary = [
            'total_items' => $cart->total_items,
            'subtotal' => $cart->total_price,
            'estimated_delivery' => 5.00, // Default delivery cost
            'total' => $cart->total_price + 5.00
        ];

        return ApiResponse::success('Cart summary retrieved', $summary);
    }

    // Move item from cart to wishlist
    public function moveToWishlist($itemId)
    {
        $cartItem = CartItem::whereHas('cart', function($query) {
            $query->where('buyer_id', Auth::id());
        })->findOrFail($itemId);

        // Add to wishlist
        \App\Models\Wishlist::firstOrCreate([
            'buyer_id' => Auth::id(),
            'product_id' => $cartItem->product_id
        ]);

        // Remove from cart
        $cartItem->delete();

        return ApiResponse::success('Item moved to wishlist');
    }
}
