<?php

namespace App\Http\Controllers\api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\auth\LoginUserRequest;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);
        if ($token) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout()
    {
        $cookie = Cookie::forget('token');
        request()->user()->tokens()->delete();
        return response([
            'message' => 'Vous vous êtes déconnecté avec succès.'
        ])->withCookie($cookie);
    }

    public function refresh()
    {
        $newToken = auth()->refresh();
        $cookie = Cookie('token', $newToken, 60 * 1);
        return $this->respondWithToken($newToken)->withCookie($cookie);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $cookie = Cookie('token', $token, 60 * 1);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' =>  auth('api')->factory()->getTTL() * 60,
        ])->withCookie($cookie);

    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}