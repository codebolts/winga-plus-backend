<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->get();
        return ApiResponse::success('Subcategories retrieved', $subcategories);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'=>'required|exists:categories,id',
            'name'=>'required|string|max:255',
            'description'=>'nullable|string'
        ]);

        $subcategory = Subcategory::create($request->only('category_id','name','description'));
        return ApiResponse::success('Subcategory created', $subcategory, 201);
    }

    public function show($id)
    {
        $sub = Subcategory::with('category')->findOrFail($id);
        return ApiResponse::success('Subcategory retrieved', $sub);
    }

    public function update(Request $request, $id)
    {
        $sub = Subcategory::findOrFail($id);
        $request->validate([
            'category_id'=>'required|exists:categories,id',
            'name'=>['required','string','max:255', Rule::unique('subcategories')->ignore($sub->id)],
            'description'=>'nullable|string'
        ]);

        $sub->update($request->only('category_id','name','description'));
        return ApiResponse::success('Subcategory updated', $sub);
    }

    public function destroy($id)
    {
        $sub = Subcategory::findOrFail($id);
        $sub->delete();
        return ApiResponse::success('Subcategory deleted', null);
    }


        public function fetchByCategory(Category $category)
    {
        $subcategories = $category->subcategories()->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Subcategories fetched successfully',
            'data' => $subcategories
        ]);
    }

}
