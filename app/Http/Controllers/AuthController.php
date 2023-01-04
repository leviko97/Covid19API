<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Http\Response;
use \Illuminate\Contracts\Foundation\Application;
use \Illuminate\Contracts\Routing\ResponseFactory;

class AuthController extends Controller
{
    public function register(Request $request): Response|Application|ResponseFactory
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
            ],
        ], [
            'regex' => 'Password should contain at least 1 lowercase letter, 1 uppercase letter, 1 digit and special char'
        ]);

        DB::beginTransaction();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken('UserApiToken');

        DB::commit();

        return response([
            'user' => $user,
            'token' => $token->plainTextToken
        ], 201);
    }

    public function login(Request $request)//: Response|Application|ResponseFactory
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)
            ->first();

        if (!isset($user) || !Hash::check($request->password, $user->password))
            abort(422, 'Wrong user and/or password');

        return response([
            'user' => $user,
            'token' => $user->createToken('UserApiToken')->plainTextToken
        ]);
    }

    public function logout(): Response|Application|ResponseFactory
    {
        Auth::user()->tokens()->delete();

        return response('Logged out');
    }

    public function user(): Response|Application|ResponseFactory
    {
        return \response(Auth::user());
    }
}
