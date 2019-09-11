<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $user = User::create([
           'name'       => $request->name,
           'email'      => $request->email,
           'password'   => $request->password,
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if(! $token = auth()->attempt($credentials)) {
            return response()->json(['error', 'unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token'      => 'Bearer '.$token
        ]);
    }
}
