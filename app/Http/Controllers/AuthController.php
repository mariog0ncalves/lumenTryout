<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required',
        ]);

        try {

            if (! $token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], 500);

        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent' => $e->getMessage()], 500);

        }

        return response()->json(compact('token'));
    }


    public function register(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required',
        ]);
        $newUser = User::create(
            [
            'email'     => $request->input('email'),
            'password'  => password_hash($request->input('password'), PASSWORD_BCRYPT),
            'name'      => $request->input('name') ?? ''
            ]
        );
        if ($newUser) {
            return response()->json(['User registered successfully'], 201);
        } else {
            return response()->json(['An error ocurred'], 500);
        }

    }


    private function doLogin($email, $pw)
    {

    }

    
}
