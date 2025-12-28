<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Check if a user is an admin by email
     */
    public function checkAdmin(Request $request): JsonResponse
    {
        try {
            $email = $request->input('email');
            
            if (!$email) {
                return response()->json(['isAdmin' => false], 200);
            }

            $user = User::where('email', $email)->first();
            
            if (!$user) {
                return response()->json(['isAdmin' => false], 200);
            }

            return response()->json(['isAdmin' => $user->role === 'admin'], 200);
        } catch (\Exception $e) {
            return response()->json(['isAdmin' => false, 'error' => $e->getMessage()], 200);
        }
    }

    /**
     * Create or update user from Firebase signup
     */
    public function firebaseSignup(Request $request): JsonResponse
    {
        try {
            \Log::info('firebaseSignup: Incoming request', [
                'body' => $request->all()
            ]);

            $validated = $request->validate([
                'email' => 'required|email',
                'name' => 'required|string',
                'firebaseUid' => 'required|string',
            ]);

            \Log::info('firebaseSignup: Validation passed', $validated);

            $user = User::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'name' => $validated['name'],
                    'password' => null, // Firebase handles authentication - no password stored
                    'role' => 'user', // Default role is user
                ]
            );

            \Log::info('firebaseSignup: User created/updated', [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role
            ]);

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'role' => $user->role,
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('firebaseSignup: Validation error', $e->errors());
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('firebaseSignup: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
