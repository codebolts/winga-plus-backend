<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdvertisementController extends Controller
{
    // List all ads for the logged-in seller
    public function index()
    {
        $ads = Advertisement::with('product')
            // ->whereHas('product', fn($q) => $q->where('seller_id', Auth::id()))
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Advertisements fetched successfully',
            'data' => $ads
        ]);
    }

    // Create new ad
   public function store(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'title' => 'required|string|max:255',
        'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $product = Product::findOrFail($request->product_id);
    if ($product->seller_id !== Auth::id()) {
        return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    }

    // Handle banner image
    $bannerPath = null;
    if ($request->hasFile('banner_image')) {
        $image = $request->file('banner_image');
        $imageName = Str::slug($request->title) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $bannerPath = $image->storeAs('ads', $imageName, 'public');
    }

    $ad = Advertisement::create([
        'product_id'   => $request->product_id,
        'title'        => $request->title,
        'banner_image' => $bannerPath ? '/storage/' . $bannerPath : null,
        'start_date'   => $request->start_date,
        'end_date'     => $request->end_date,
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Advertisement created successfully',
        'data'    => $ad
    ]);
}

    // Show a single ad
    public function show(Advertisement $advertisement)
    {
        if ($advertisement->product->seller_id !== Auth::id()) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'],403);
        }

        return response()->json([
            'status'=>'success',
            'message'=>'Advertisement details fetched',
            'data'=>$advertisement
        ]);
    }

    // Update an ad
  public function update(Request $request, Advertisement $advertisement)
{
    // if ($advertisement->product->seller_id !== Auth::id()) {
    //     return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
    // }

    $request->validate([
        'title'        => 'required|string|max:255',
        'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'start_date'   => 'required|date',
        'end_date'     => 'required|date|after_or_equal:start_date',
    ]);

    // Handle banner image upload
    if ($request->hasFile('banner_image')) {
        $image = $request->file('banner_image');
        $imageName = Str::slug($request->title) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $bannerPath = $image->storeAs('ads', $imageName, 'public');
        $advertisement->banner_image = '/storage/' . $bannerPath;
    }

    $advertisement->update([
        'title'        => $request->title,
        'start_date'   => $request->start_date,
        'end_date'     => $request->end_date,
        'banner_image' => $advertisement->banner_image, // keep old if no new file
    ]);

    return response()->json([
        'status'  => 'success',
        'message' => 'Advertisement updated successfully',
        'data'    => $advertisement
    ]);
}

    // Delete an ad
    public function destroy(Advertisement $advertisement)
    {
        if ($advertisement->product->seller_id !== Auth::id()) {
            return response()->json(['status'=>'error','message'=>'Unauthorized'],403);
        }

        $advertisement->delete();

        return response()->json([
            'status'=>'success',
            'message'=>'Advertisement deleted successfully',
            'data'=>null
        ]);
    }

    // Public: Fetch active ads
    public function activeAds()
    {
        $today = now()->toDateString();

        $ads = Advertisement::with('product')
            ->whereDate('start_date','<=',$today)
            ->whereDate('end_date','>=',$today)
            ->get();

        return response()->json([
            'status'=>'success',
            'message'=>'Active advertisements fetched',
            'data'=>$ads
        ]);
    }
}
