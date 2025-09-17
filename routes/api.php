<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Api\Buyer\ProductController as BuyerProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Api\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Api\SubcategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->group(function () {
    // Fetch subcategories for a specific category
    Route::get('/categories/{category}/subcategories', [SubCategoryController::class, 'fetchByCategory']);
});


Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

// auth helpers
Route::middleware('auth:sanctum')->group(function(){
    Route::get('me',[AuthController::class,'me']);
    Route::post('logout',[AuthController::class,'logout']);
});

// categories/subcategories
Route::get('categories',[CategoryController::class,'index']);
Route::get('categories/{id}',[CategoryController::class,'show']);
Route::get('subcategories',[SubcategoryController::class,'index']);
Route::get('subcategories/{id}',[SubcategoryController::class,'show']);

// product browsing
Route::get('products',[BuyerProductController::class,'index']);
Route::get('products/{id}',[BuyerProductController::class,'show']);
Route::get('products/{product}/reviews',[ReviewController::class,'index']);

// protected routes
Route::middleware('auth:sanctum')->group(function(){

    // seller
    Route::prefix('seller')->middleware('role:seller')->group(function(){
        Route::apiResource('products', SellerProductController::class);
        Route::get('orders', [SellerOrderController::class,'index']);
        Route::get('orders/{id}', [SellerOrderController::class,'show']);
        Route::post('orders/{id}/complete', [SellerOrderController::class,'markCompleted']);
        Route::post('categories',[CategoryController::class,'store']);
        Route::post('subcategories',[SubcategoryController::class,'store']);
    });

    // buyer
    Route::prefix('buyer')->middleware('role:buyer')->group(function(){
        Route::apiResource('orders', BuyerOrderController::class)->only(['index','store','show']);
        Route::post('orders/{id}/cancel',[BuyerOrderController::class,'cancel']);
        Route::post('products/{product}/reviews',[ReviewController::class,'store']);
        Route::put('reviews/{id}', [ReviewController::class,'update']);
        Route::delete('reviews/{id}', [ReviewController::class,'destroy']);
    });

});


Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
