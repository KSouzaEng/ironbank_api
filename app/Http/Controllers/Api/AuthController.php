<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
//     public function __construct()
// {
//     $this->middleware('auth:api');
// }
    public function login(LoginRequest $request)
    {
        $input = $request->validated();

        $credentials = [
            'email' => $input['email'],
            'password' => $input['password'],
        ];

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        // dd($token);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 720
        ]);
    }
    public function logout()
    {
        auth()->logout();

        return response()->json(['success   ' => 'Successfully logged out']);
    }
    public function me()
    {
        return response()->json(['user' =>auth()->user()],200);
    }
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
