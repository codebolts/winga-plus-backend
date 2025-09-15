<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class ReviewController extends Controller
{
    public function index($productId){
        $reviews = Review::where('product_id',$productId)->with('user:id,name')->latest()->get();
        return ApiResponse::success('Reviews retrieved successfully',$reviews);
    }

    public function store(Request $request,$productId){
        $request->validate([
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'nullable|string'
        ]);

        $review = Review::create([
            'product_id'=>$productId,
            'user_id'=>$request->user()->id,
            'rating'=>$request->rating,
            'comment'=>$request->comment
        ]);

        return ApiResponse::success('Review added successfully',$review,201);
    }
}
