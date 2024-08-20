<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('username', 'password');


        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Invalid credentials'
            ], 401);
        }


        return response()->json([
            'access_token'  => $token,
            'status'        => 'success',
            'user'          => auth()->user()
        ], 200);
    }

    public function logout()
    {
        auth()->logout();
        return response()->json([
            'status'        => 'success',
            'message'       => 'Logout Successfully'
        ], 200);
    }
}