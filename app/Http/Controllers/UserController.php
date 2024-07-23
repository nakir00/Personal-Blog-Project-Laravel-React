<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create($data);

        $token = $user->createToken($request->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'errors' => [
                    'username' => [
                        'The provided credentials are incorrect'
                    ]
                ]
            ];
        }

        $token = $user->createToken($user->username);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
            return [
                'message' => 'You logged out successfully'
            ];
    }


}
