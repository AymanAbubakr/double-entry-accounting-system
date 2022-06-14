<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\AuthRequestLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(AuthRequest $authRequest)
    {
        $user = User::create([
            'name' => $authRequest->name,
            'email' => $authRequest->email,
            'password' => Hash::make($authRequest->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(AuthRequestLogin $authRequestLogin)
    {
        if (!Auth::attempt($authRequestLogin->only(['email', 'password']))) {
            return response()->json([
                'status' => false,
                'message' => 'Wrong Email Or Password !',
            ], 401);
        }

        $user = User::where('email', $authRequestLogin->email)->first();

        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }
}
