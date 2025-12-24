<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // ❌ Invalid email
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // ❌ Wrong password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        // ❌ Inactive / banned user
        if (!$user->isActive()) {
            return response()->json([
                'status' => false,
                'message' => 'Account is inactive or banned'
            ], 403);
        }

        // ✅ Old tokens revoke (optional but recommended)
        $user->tokens()->delete();

        // ✅ Create Sanctum token
        $token = $user->createToken('auth_token')->plainTextToken;

        // ✅ Update last login
        $user->update([
            'last_login_at' => now()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id'     => $user->id,
                'name'   => $user->name,
                'email'  => $user->email,
                'mobile' => $user->mobile,
            ]
        ]);
    }
}
