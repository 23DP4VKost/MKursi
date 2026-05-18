<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    private function generateToken(User $user): string
    {
        $token = Str::random(80);
        $user->update(['api_token' => $token]);
        return $token;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $baseUsername = Str::before($validated['email'], '@');
        $username = $baseUsername;
        $i = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $i;
            $i++;
        }

        $user = User::create([
            'email' => $validated['email'],
            'username' => $username,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $token = $this->generateToken($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Nederīgi dati'], 401);
        }

        $user = $request->user();
        $token = $this->generateToken($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function getToken(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Nav autentificēta lietotāja'], 401);
        }

        // Generate new token for current user
        $token = $this->generateToken($user);

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Izrakstīts veiksmīgi']);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Nepieejams lietotājs'], 401);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return response()->json(['message' => 'Konts dzēsts veiksmīgi']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Nepieejams lietotājs'], 401);
        }

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'created_at' => $user->created_at?->toISOString(),
            ],
        ]);
    }
}