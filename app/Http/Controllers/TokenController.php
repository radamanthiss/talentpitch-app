<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{
    /**
     * Generate token
     */
    public function createToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        error_log('Request: ' . print_r($request->all(), true));
        // Attempt to authenticate the user
        $user = User::where('email', $request->email)->first();
        error_log('User: ' . print_r($user, true));
        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }
        $token = $user->createToken('apiToken')->plainTextToken;
        return response()->json([
            'token' => $token,
        ]);
    }
}