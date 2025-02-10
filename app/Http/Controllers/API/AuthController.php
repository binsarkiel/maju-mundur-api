<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Merchant;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validate the request data.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users|unique:merchants', // Unique email across users and merchants.
            'password' => 'required|string|min:6',
            'is_merchant' => 'required|boolean' // Must specify if registering as a merchant.
        ]);

        // 2. If validation fails, return a 422 Unprocessable Entity error.
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 3. Create either a Merchant or a User based on the 'is_merchant' flag.
        if ($request->is_merchant) {
            $merchant = Merchant::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Always hash passwords!
            ]);
            $token = $merchant->createToken('merchant_token')->plainTextToken; // Create a Sanctum token.
            return response()->json(['token' => $token, 'user' => $merchant, 'is_merchant' => true], 201); // 201 Created

        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Always hash passwords!
                'role'  => 'customer'
            ]);
            $token = $user->createToken('user_token')->plainTextToken;  // Create a Sanctum token.
            return response()->json(['token' => $token, 'user' => $user, 'is_merchant' => false], 201); // 201 Created
        }
    }

    public function login(Request $request)
    {
        // 1. Validate the request.
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'is_merchant' => 'required|boolean' // Must specify if logging in as a merchant.
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');

        // 2. Attempt to authenticate, checking either the 'merchant' or 'web' guard.
        if ($request->is_merchant) {
            if (Auth::guard('merchant')->attempt($credentials)) {
                $merchant = Auth::guard('merchant')->user();
                $token = $merchant->createToken('merchant_token')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $merchant, 'is_merchant' => true]);
            }
        } else {
            if (Auth::guard('web')->attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('user_token')->plainTextToken;
                return response()->json(['token' => $token, 'user' => $user, 'is_merchant' => false]);
            }
        }

        // 3. If authentication fails, return a 401 Unauthorized error.
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete(); // Delete the user's current access token.
        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $isMerchant = $user instanceof Merchant;

        return response()->json([
            'user' => $user,
            'is_merchant' => $isMerchant,
        ]);
    }
}