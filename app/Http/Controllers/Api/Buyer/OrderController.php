<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('buyer_id', Auth::id())->with('items.product.seller')->latest()->get();
        return ApiResponse::success('Orders retrieved', $orders);
    }

    public function show($id)
    {
        $order = Order::where('buyer_id', Auth::id())->with('items.product.seller')->findOrFail($id);
        return ApiResponse::success('Order retrieved', $order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'=>'required|array|min:1',
            'items.*.product_id'=>'required|exists:products,id',
            'items.*.quantity'=>'required|integer|min:1'
        ]);

        $userId = Auth::id();

        return DB::transaction(function() use ($request, $userId) {
            $total = 0;
            // Lock rows for update to avoid race conditions
            foreach($request->items as $item){
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                if($product->stock < $item['quantity']){
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                $total += $product->price * $item['quantity'];
            }

            $order = Order::create([
                'buyer_id' => $userId,
                'total_price' => $total,
                'status' => 'pending'
            ]);

            foreach($request->items as $item){
                $product = Product::findOrFail($item['product_id']);
                OrderItem::create([
                    'order_id'=>$order->id,
                    'product_id'=>$product->id,
                    'quantity'=>$item['quantity'],
                    'price'=>$product->price
                ]);
                $product->decrement('stock', $item['quantity']);
            }

            return ApiResponse::success('Order placed', $order->load('items.product'), 201);
        }, 5);
    }

    public function cancel($id)
    {
        $order = Order::where('buyer_id', Auth::id())->findOrFail($id);

        if($order->status !== 'pending'){
            return ApiResponse::error('Only pending orders can be cancelled', null, 400);
        }

        DB::transaction(function() use ($order) {
            // restore stock
            foreach($order->items as $item){
                $product = $item->product;
                if($product){
                    $product->increment('stock', $item->quantity);
                }
            }

            $order->update(['status' => 'cancelled']);
        });

        return ApiResponse::success('Order cancelled', $order->fresh()->load('items.product'));
    }
}
