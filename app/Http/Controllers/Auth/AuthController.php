<?php

namespace App\Http\Controllers\Auth;


use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Jobs\SendWelcomeEmail;
use App\Listeners\SendWelcomeMail;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('user_token')->plainTextToken;
        SendWelcomeEmail::dispatch($user->email);
        // event(new NewUserIsRegistered($user->email));

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function login(LoginRequest $request)

    {
        if (Auth::attempt($request->only(['email', 'password']))) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('user_token')->plainTextToken;

            return response()->json(['user' => $user, 'token' => $token], 200);
        }
        return response()->json(['error' => 'Something went wrong in login'], 400);
    }

    public function logout()
    {

        $user = User::find(Auth::user()->id);

        $user->tokens()->delete();

        return response()->json('User logged out!', 200);
    }
}
