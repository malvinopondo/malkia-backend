<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Import the User model

class Authentication extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Find the user by email
            $user = User::where('email', $request->email)->first(); // Simplified check

            // Check if user exists and password matches
            if ($user && Hash::check($request->password, $user->password)) {
                // Create a new token for the user if using token-based auth
                $token = $user->createToken('authentication')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'user' => $user,
                    'token' => $token, // Include token if using token-based auth
                ]);
            }

            return response()->json(['error' => 'Invalid credentials'], 401);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
