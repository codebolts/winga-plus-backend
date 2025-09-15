<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Helpers\ApiResponse;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->get();
        return ApiResponse::success('Categories retrieved', $categories);
    }

    public function store(Request $request)
    {
        $request->validate(['name'=>'required|string|max:255|unique:categories','description'=>'nullable|string']);
        $category = Category::create($request->only('name','description'));
        return ApiResponse::success('Category created', $category, 201);
    }

    public function show($id)
    {
        $category = Category::with('subcategories')->findOrFail($id);
        return ApiResponse::success('Category retrieved', $category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'name'=>['required','string','max:255', Rule::unique('categories')->ignore($category->id)],
            'description'=>'nullable|string'
        ]);

        $category->update($request->only('name','description'));
        return ApiResponse::success('Category updated', $category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return ApiResponse::success('Category deleted', null);
    }
}
