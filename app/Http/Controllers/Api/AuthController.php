<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $user = User::whereEmail($request->email)->firstOrFail();
        $user->tokens()->where('name', 'auth-token')->delete();

        $token = $user->createToken('auth-token');

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $this->validate($request, ['email' => 'required|email|unique:users,email', 'name' => 'required|string|max:125', 'password' => 'required|string|min:8|confirmed']);

        $validated = $request->all();
        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->assignRole('user');

        $user->tokens()->where('name', 'auth-token')->delete();

        $token = $user->createToken('auth-token');
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json('success', 200);
    }
}
