<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locations = Location::all();
        return ApiResponse::success('Locations retrieved successfully', $locations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
        ]);

        $location = Location::create($request->all());
        return ApiResponse::success('Location created successfully', $location, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::findOrFail($id);
        return ApiResponse::success('Location retrieved successfully', $location);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
        ]);

        $location->update($request->all());
        return ApiResponse::success('Location updated successfully', $location);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::findOrFail($id);
        $location->delete();
        return ApiResponse::success('Location deleted successfully');
    }
}
