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
        })->with(['items.product'=>function($q){ $q->with('seller'); },'buyer'])->latest()->get();

        return ApiResponse::success('Seller orders retrieved', $orders);
    }

    // Show order - include items and flag which items belong to this seller
    public function show($id)
    {
        $sellerId = Auth::id();
        $order = Order::with('items.product.seller','buyer')->findOrFail($id);

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

    // Update order status (more flexible than just marking completed)
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'delivered_at' => 'nullable|date|after:now',
            'special_instructions' => 'nullable|string|max:500'
        ]);

            // |in:pending,processing,completed,cancelled,shipped,delivered
        $sellerId = Auth::id();
        $order = Order::with('items.product')->findOrFail($id);

        // Check that at least one item belongs to this seller
        $hasItems = $order->items->contains(function($item) use ($sellerId) {
            return $item->product && $item->product->seller_id == $sellerId;
        });

        if (!$hasItems) {
            return ApiResponse::error('Order not found for this seller', null, 404);
        }

        $updateData = ['status' => $request->status];

        // If marking as delivered, set delivered_at timestamp
        if ($request->status === 'delivered') {
            $updateData['delivered_at'] = now();
        }

        // Update special instructions if provided
        if ($request->has('special_instructions')) {
            $updateData['special_instructions'] = $request->special_instructions;
        }

        $order->update($updateData);

        return ApiResponse::success('Order status updated successfully', $order->fresh());
    }

}
