<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class OrderController extends Controller
{
    // List orders that include products owned by the seller
    public function index()
    {
        $sellerId = Auth::id();
        $orders = Order::whereHas('items.product', function($q) use ($sellerId){
            $q->where('seller_id', $sellerId);
        })->with(['items.product'=>function($q){ $q->with('seller'); }])->latest()->get();

        return ApiResponse::success('Seller orders retrieved', $orders);
    }

    // Show order - include items and flag which items belong to this seller
    public function show($id)
    {
        $sellerId = Auth::id();
        $order = Order::with('items.product.seller')->findOrFail($id);

        // ensure at least one item belongs to seller
        $has = $order->items->contains(function($it) use ($sellerId){
            return $it->product && $it->product->seller_id == $sellerId;
        });

        if(!$has) return ApiResponse::error('Order not found for this seller', null, 404);

        // add an attribute to each item marking ownership
        $order->items->each(function($it) use ($sellerId){
            $it->is_mine = ($it->product && $it->product->seller_id == $sellerId);
        });

        return ApiResponse::success('Order retrieved', $order);
    }

    // Mark order as completed (only when all items belong to this seller)
    public function markCompleted($id)
    {
        $sellerId = Auth::id();
        $order = Order::with('items.product')->findOrFail($id);

        // check that every order item belongs to this seller
        $allMine = $order->items->every(function($it) use ($sellerId){
            return $it->product && $it->product->seller_id == $sellerId;
        });

        if(!$allMine){
            return ApiResponse::error('Cannot complete order with items from other sellers', null, 403);
        }

        if($order->status === 'completed'){
            return ApiResponse::error('Order already completed', null, 400);
        }

        $order->update(['status' => 'completed']);
        return ApiResponse::success('Order marked completed', $order->fresh());
    }
}
