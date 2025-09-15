<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:buyer,seller',
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>$request->role,
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
}
