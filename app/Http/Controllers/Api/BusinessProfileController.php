<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\BusinessProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BusinessProfileController extends Controller
{
    // Get current user's business profile
    public function show()
    {
        $userId = Auth::id();
        $profile = BusinessProfile::where('seller_id', $userId)->first();

        if (!$profile) {
            return ApiResponse::error('Business profile not found', null, 404);
        }

        return ApiResponse::success('Business profile retrieved successfully', $profile);
    }

    // Create business profile
    public function store(Request $request)
    {
        $userId = Auth::id();

        // Check if profile already exists
        $existingProfile = BusinessProfile::where('seller_id', $userId)->first();
        if ($existingProfile) {
            return ApiResponse::error('Business profile already exists. Use PUT to update.', null, 409);
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'free_delivery' => 'boolean',
            'delivery_cost' => 'nullable|numeric|min:0',
            'delivery_locations' => 'nullable|string', // JSON string
            'payment_on_delivery' => 'boolean',
            'payment_before_delivery' => 'boolean',
            'business_address' => 'nullable|string|max:500',
            'business_phone' => 'nullable|string|max:20'
        ]);

        $data = $request->only([
            'business_name',
            'description',
            'website',
            'free_delivery',
            'delivery_cost',
            'delivery_locations',
            'payment_on_delivery',
            'payment_before_delivery',
            'business_address',
            'business_phone'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo_' . $userId . '_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('business-logos', $logoName, 'public');
            $data['logo'] = '/storage/' . $logoPath;
        }

        $data['seller_id'] = $userId;
        $profile = BusinessProfile::create($data);

        return ApiResponse::success('Business profile created successfully', $profile, 201);
    }

    // Update business profile
    public function update(Request $request)
    {
        $userId = Auth::id();
        $profile = BusinessProfile::where('seller_id', $userId)->first();

        if (!$profile) {
            return ApiResponse::error('Business profile not found. Use POST to create.', null, 404);
        }

        $request->validate([
            'business_name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|max:2048', // 2MB max
            'free_delivery' => 'boolean',
            'delivery_cost' => 'nullable|numeric|min:0',
            'delivery_locations' => 'nullable|string', // JSON string
            'payment_on_delivery' => 'boolean',
            'payment_before_delivery' => 'boolean',
            'business_address' => 'nullable|string|max:500',
            'business_phone' => 'nullable|string|max:20'
        ]);

        $data = $request->only([
            'business_name',
            'description',
            'website',
            'free_delivery',
            'delivery_cost',
            'delivery_locations',
            'payment_on_delivery',
            'payment_before_delivery',
            'business_address',
            'business_phone'
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($profile->logo) {
                $oldPath = str_replace('/storage/', '', $profile->logo);
                Storage::disk('public')->delete($oldPath);
            }

            $logo = $request->file('logo');
            $logoName = 'logo_' . $userId . '_' . time() . '.' . $logo->getClientOriginalExtension();
            $logoPath = $logo->storeAs('business-logos', $logoName, 'public');
            $data['logo'] = '/storage/' . $logoPath;
        }

        $profile->update($data);

        return ApiResponse::success('Business profile updated successfully', $profile->fresh());
    }

    // Delete business profile
    public function destroy()
    {
        $userId = Auth::id();
        $profile = BusinessProfile::where('seller_id', $userId)->first();

        if (!$profile) {
            return ApiResponse::error('Business profile not found', null, 404);
        }

        // Delete logo file if exists
        if ($profile->logo) {
            $oldPath = str_replace('/storage/', '', $profile->logo);
            Storage::disk('public')->delete($oldPath);
        }

        $profile->delete();

        return ApiResponse::success('Business profile deleted successfully');
    }

    // Get delivery settings (public endpoint for buyers)
    public function getDeliverySettings($sellerId)
    {
        $profile = BusinessProfile::where('seller_id', $sellerId)->first();

        if (!$profile) {
            // Return default settings if no profile exists
            return ApiResponse::success('Delivery settings retrieved', [
                'free_delivery' => false,
                'delivery_cost' => 5.00,
                'payment_on_delivery' => true,
                'payment_before_delivery' => false
            ]);
        }

        return ApiResponse::success('Delivery settings retrieved', [
            'free_delivery' => $profile->free_delivery,
            'delivery_cost' => $profile->delivery_cost ?? 5.00,
            'payment_on_delivery' => $profile->payment_on_delivery,
            'payment_before_delivery' => $profile->payment_before_delivery,
            'delivery_locations' => $profile->delivery_locations,
            'business_address' => $profile->business_address,
            'business_phone' => $profile->business_phone
        ]);
    }
}
