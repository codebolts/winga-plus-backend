<?php

namespace App\Http\Controllers\Api\Buyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\BusinessProfile;
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
            'items.*.quantity'=>'required|integer|min:1',
            'delivery_address' => 'required|string|max:500',
            'delivery_location' => 'nullable|string|max:255',
            'payment_method' => 'required|in:on_delivery,before_delivery',
            'special_instructions' => 'nullable|string|max:500'
        ]);

        $userId = Auth::id();

        return DB::transaction(function() use ($request, $userId) {
            $total = 0;
            $deliveryCost = 0;
            $sellerIds = [];

            // Lock rows for update to avoid race conditions and collect seller info
            foreach($request->items as $item){
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);
                if($product->stock < $item['quantity']){
                    throw new \Exception("Insufficient stock for {$product->name}");
                }
                $total += $product->price * $item['quantity'];
                $sellerIds[] = $product->seller_id;
            }

            // Get unique sellers and check their delivery settings
            $uniqueSellerIds = array_unique($sellerIds);
            if (count($uniqueSellerIds) > 1) {
                // Multiple sellers - use default delivery cost
                $deliveryCost = 5.00; // Default delivery cost for multi-seller orders
            } else {
                // Single seller - check their business profile
                $sellerId = $uniqueSellerIds[0];
                $businessProfile = BusinessProfile::where('seller_id', $sellerId)->first();

                if ($businessProfile && $businessProfile->free_delivery) {
                    $deliveryCost = 0;
                } elseif ($businessProfile && $businessProfile->delivery_cost) {
                    $deliveryCost = $businessProfile->delivery_cost;
                } else {
                    $deliveryCost = 5.00; // Default delivery cost
                }
            }

            // Validate payment method based on seller settings
            if (count($uniqueSellerIds) === 1) {
                $sellerId = $uniqueSellerIds[0];
                $businessProfile = BusinessProfile::where('seller_id', $sellerId)->first();

                if ($businessProfile) {
                    if ($request->payment_method === 'on_delivery' && !$businessProfile->payment_on_delivery) {
                        throw new \Exception("This seller does not accept payment on delivery");
                    }
                    if ($request->payment_method === 'before_delivery' && !$businessProfile->payment_before_delivery) {
                        throw new \Exception("This seller does not accept payment before delivery");
                    }
                }
            }

            $order = Order::create([
                'buyer_id' => $userId,
                'total_price' => $total + $deliveryCost,
                'status' => 'pending',
                'delivery_cost' => $deliveryCost,
                'delivery_address' => $request->delivery_address,
                'delivery_location' => $request->delivery_location,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'before_delivery' ? 'pending' : 'pending',
                'special_instructions' => $request->special_instructions
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

            return ApiResponse::success('Order placed successfully', $order->load('items.product'), 201);
        }, 5);
    }

    public function cancel($id)
    {
        $order = Order::where('buyer_id', Auth::id())->findOrFail($id);

        if(!in_array($order->status, ['pending', 'processing'])){
            return ApiResponse::error('Only pending or processing orders can be cancelled', null, 400);
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
