<?php

namespace App\Http\Controllers\Api;

use App\Models\BusinessType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class BusinessTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessTypes = BusinessType::all();
        return ApiResponse::success('Business types retrieved successfully', $businessTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $businessType = BusinessType::create($request->all());
        return ApiResponse::success('Business type created successfully', $businessType, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $businessType = BusinessType::findOrFail($id);
        return ApiResponse::success('Business type retrieved successfully', $businessType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $businessType = BusinessType::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $businessType->update($request->all());
        return ApiResponse::success('Business type updated successfully', $businessType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $businessType = BusinessType::findOrFail($id);
        $businessType->delete();
        return ApiResponse::success('Business type deleted successfully');
    }
}
