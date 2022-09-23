<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req)
    {
        $validated = $this->validate($req, [
            'name' => 'required|max:255|min:2',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:6'
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        return response()->json(['message' => 'Register successful!'], 201);
    }

    public function login(Request $req)
    {
        $validated = $this->validate($req, [
            'email' => 'required|exists:users,email',
            'password' => 'required'
        ]);

        $user = User::where('email', $validated['email'])->first();
        if (!Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Email/password is wrong, please check again!'], 401,);
        }

        $tokenPayload = [
            'uid' => $user->id,
            'iat' => intval(microtime(true)),
            'exp' => intval(microtime(true)) + (60 * 60 * 1000)
        ];

        $token = JWT::encode($tokenPayload, env('JWT_SECRET'), 'HS256');
        return response()->json(['token' => $token]);
    }

    public function getUserData($userId)
    {
        $userData = User::find(Auth::user()->id);
        return response()->json($userData);
    }
}
