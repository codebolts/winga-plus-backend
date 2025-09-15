<?php 
namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request){
        $products = $request->user()->products()->latest()->get();
        return ApiResponse::success('Seller products retrieved successfully',$products);
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'description'=>'nullable|string',
            'price'=>'required|numeric|min:0',
            'stock'=>'required|integer|min:0',
            'image'=>'nullable|image|max:2048'
        ]);

        $data = $request->only(['name','description','price','stock']);
        $data['seller_id'] = $request->user()->id;

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('products','public');
        }

        $product = Product::create($data);

        return ApiResponse::success('Product created successfully',$product,201);
    }

    public function show($id){
        $product = Product::findOrFail($id);
        return ApiResponse::success('Product retrieved successfully',$product);
    }

    public function update(Request $request,$id){
        $product = Product::where('seller_id',$request->user()->id)->findOrFail($id);

        $request->validate([
            'name'=>'sometimes|string|max:255',
            'description'=>'nullable|string',
            'price'=>'sometimes|numeric|min:0',
            'stock'=>'sometimes|integer|min:0',
            'image'=>'nullable|image|max:2048'
        ]);

        $data = $request->only(['name','description','price','stock']);

        if($request->hasFile('image')){
            if($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products','public');
        }

        $product->update($data);

        return ApiResponse::success('Product updated successfully',$product);
    }

    public function destroy(Request $request,$id){
        $product = Product::where('seller_id',$request->user()->id)->findOrFail($id);

        if($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();

        return ApiResponse::success('Product deleted successfully',null);
    }
}
