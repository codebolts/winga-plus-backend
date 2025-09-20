<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\FacadesStorage;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:buyer,seller',
            'phone_number' => 'nullable|string|max:15',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
            'phone_number'=>$request->phone_number,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('User registered successfully', [
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return ApiResponse::error('Invalid credentials',401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success('Login successful',[
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . Auth::id(),
            'phone_number' => 'nullable|string|max:15',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:204800',
        ]);

        $user = Auth::user();
        $data = $request->only(['name', 'email', 'phone_number']);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            // Store new photo
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }

        $user->update($data);

        return ApiResponse::success('Profile updated successfully', $user);
    }

    public function me(Request $request)
    {

        return response()->json([
            'status' => 'success',
            'message' => 'User profile fetched successfully',
            'data' =>Auth::user(),
        ]);
    }

    public function user(Request $request)
    {
        return $this->me($request);
    }
}
