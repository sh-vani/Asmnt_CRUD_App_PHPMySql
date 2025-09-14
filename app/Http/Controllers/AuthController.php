<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// app/Http/Controllers/AuthController.php

use App\Models\User;

use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
   



public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'mobile' => 'required|numeric|unique:users,mobile',
        'password' => 'required|confirmed|min:6',
    ]);

    // Debug: Print request data
    \Log::info('Register Request:', $request->all());

    try {
        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Register Error:', [$e->getMessage()]);
        return response()->json(['error' => 'Registration failed'], 500);
    }
}

   public function login(Request $request)
{
    $request->validate([
        'mobile' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('mobile', $request->mobile)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // Generate token
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'mobile' => $user->mobile,
        ],
        'token' => $token,
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
