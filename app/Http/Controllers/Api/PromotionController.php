<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;

class PromotionController extends Controller
{
    // List all promotions for seller
    public function index()
    {
        $promotions = Promotion::with('product')
            ->whereHas('product', fn($q) => $q->where('seller_id', Auth::id()))
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
}
