<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\SubcategoryController;
use App\Http\Controllers\Api\AdvertisementController;

use App\Http\Controllers\Api\Buyer\OrderController as BuyerOrderController;
use App\Http\Controllers\Api\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Api\Buyer\ProductController as BuyerProductController;
use App\Http\Controllers\Api\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\BusinessProfileController;
use App\Http\Controllers\Api\LegalDocumentController;
use App\Http\Controllers\Api\BusinessTypeController;
use App\Http\Controllers\Api\LocationController;


// Public endpoint
Route::get('/ads/active', [AdvertisementController::class,'activeAds']);

// Public business profile endpoints (for buyers to get delivery settings)
Route::get('/sellers/{sellerId}/delivery-settings', [BusinessProfileController::class, 'getDeliverySettings']);

// Legal documents (public access)
Route::get('/legal-documents/active', [LegalDocumentController::class, 'active']);
Route::get('/legal-documents/{type}', [LegalDocumentController::class, 'show']);
Route::get('/terms-and-conditions', [LegalDocumentController::class, 'termsAndConditions']);
Route::get('/privacy-policy', [LegalDocumentController::class, 'privacyPolicy']);


Route::middleware('auth:sanctum')->group(function () {
    // Fetch subcategories for a specific category
    Route::get('/categories/{category}/subcategories', [SubCategoryController::class, 'fetchByCategory']);
});


Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

// auth helpers
Route::middleware('auth:sanctum')->group(function(){
    Route::get('profile',[AuthController::class,'me']);
    Route::post('profile/update',[AuthController::class,'updateProfile']);
    Route::post('logout',[AuthController::class,'logout']);

    // Wishlist routes (buyer only)
    Route::prefix('wishlist')->group(function(){
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/', [WishlistController::class, 'store']);
        Route::delete('/{productId}', [WishlistController::class, 'destroy']);
        Route::get('/check/{productId}', [WishlistController::class, 'check']);
        Route::post('/move-to-cart/{productId}', [WishlistController::class, 'moveToCart']);
    });

    // Cart routes (buyer only)
    Route::prefix('cart')->group(function(){
        Route::get('/', [CartController::class, 'index']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/items/{itemId}', [CartController::class, 'update']);
        Route::delete('/items/{itemId}', [CartController::class, 'destroy']);
        Route::delete('/', [CartController::class, 'clear']);
        Route::get('/summary', [CartController::class, 'summary']);
        Route::post('/move-to-wishlist/{itemId}', [CartController::class, 'moveToWishlist']);
    });
});

// categories/subcategories
Route::get('categories',[CategoryController::class,'index']);
Route::get('categories/{id}',[CategoryController::class,'show']);
Route::get('subcategories',[SubcategoryController::class,'index']);
Route::get('subcategories/{id}',[SubcategoryController::class,'show']);

// product browsing
Route::get('home',[BuyerProductController::class,'home']);
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
        Route::put('orders/{id}/status', [SellerOrderController::class,'updateStatus']);
        Route::post('categories',[CategoryController::class,'store']);
        Route::post('subcategories',[SubcategoryController::class,'store']);

        // Business profile management
        Route::get('business-profile', [BusinessProfileController::class, 'show']);
        Route::post('business-profile', [BusinessProfileController::class, 'store']);
        Route::put('business-profile', [BusinessProfileController::class, 'update']);
        Route::delete('business-profile', [BusinessProfileController::class, 'destroy']);
    });

    // reports (seller only)
    Route::middleware('role:seller')->group(function(){
        Route::get('reports/sales', [ReportController::class, 'getSalesReport']);
        Route::get('reports/products', [ReportController::class, 'getProductReports']);
        Route::get('reports/customers', [ReportController::class, 'getCustomerReport']);
        Route::get('reports/dashboard', [ReportController::class, 'getDashboardStats']);
    });

    // buyer
    Route::prefix('buyer')->middleware('role:buyer')->group(function(){
        Route::apiResource('orders', BuyerOrderController::class)->only(['index','store','show']);
        Route::post('orders/{id}/cancel',[BuyerOrderController::class,'cancel']);
        Route::post('products/{product}/reviews',[ReviewController::class,'store']);
        Route::put('reviews/{id}', [ReviewController::class,'update']);
        Route::delete('reviews/{id}', [ReviewController::class,'destroy']);
    });

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/promotions', [PromotionController::class,'index']); // seller view
    Route::post('/promotions', [PromotionController::class,'store']);
    Route::get('/promotions/{promotion}', [PromotionController::class,'show']);
    Route::put('/promotions/{promotion}', [PromotionController::class,'update']);
    Route::delete('/promotions/{promotion}', [PromotionController::class,'destroy']);

    // Business types and locations
    Route::apiResource('business-types', BusinessTypeController::class);
    Route::apiResource('locations', LocationController::class);
});

// Public endpoint for buyers
Route::get('/promotions/active', [PromotionController::class,'activePromotions']);

});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/ads', [AdvertisementController::class,'index']);
    Route::post('/ads', [AdvertisementController::class,'store']);
    Route::get('/ads/{advertisement}', [AdvertisementController::class,'show']);
    Route::put('/ads/{advertisement}', [AdvertisementController::class,'update']);
    Route::delete('/ads/{advertisement}', [AdvertisementController::class,'destroy']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/messages/send', [MessageController::class, 'send']);
    Route::get('/messages/conversation/{userId}', [MessageController::class, 'conversation']);
    Route::post('/messages/read/{userId}', [MessageController::class, 'markAsRead']);
    Route::delete('/messages/conversation/{userId}', [MessageController::class, 'deleteConversation']);
});
Route::get('/messages/conversations', [MessageController::class, 'conversations'])->middleware('auth:sanctum');


// Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);
