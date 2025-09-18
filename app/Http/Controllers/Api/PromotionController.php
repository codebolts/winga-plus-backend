<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    // List all promotions for seller
    public function index()
    {
        $promotions = Promotion::with('product')
            // ->whereHas('product', fn($q) => $q->where('seller_id', Auth::id()))
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Promotions fetched successfully',
            'data' => $promotions
        ]);
    }

    // Create a new promotion
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $promotion = Promotion::create([
            'product_id' => $request->product_id,
            'discount_percentage' => $request->discount_percentage,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Promotion created successfully',
            'data' => $promotion
        ]);
    }


        // Show a single promotion
    public function show(Promotion $promotion)
    {
        if ($promotion->product->seller_id !== Auth::id()) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'],403);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Promotion details fetched',
            'data'=>$promotion
        ]);
    }

    // Update a promotion
    public function update(Request $request, Promotion $promotion)
    {
        if ($promotion->product->seller_id !== Auth::id()) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'],403);
        }

        $request->validate([
            'discount_percentage'=>'required|numeric|min:1|max:100',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',
        ]);

        $promotion->update($request->only('discount_percentage','start_date','end_date'));

        return response()->json([
            'status'=>'success',
            'message'=>'Promotion updated successfully',
            'data'=>$promotion
        ]);
    }

    // Delete a promotion
    public function destroy(Promotion $promotion)
    {
        if ($promotion->product->seller_id !== Auth::id()) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'],403);
        }

        $promotion->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'Promotion deleted successfully',
            'data'=>null
        ]);
    }

    // Fetch active promotions for buyers
    public function activePromotions()
    {
        $today = now()->toDateString();
        $promotions = Promotion::with('product')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->get();

        return response()->json([
            'status'=>'success',
            'message'=>'Active promotions fetched',
            'data'=>$promotions
        ]);
    }

}
