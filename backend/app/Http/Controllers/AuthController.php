<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class AuthController extends Controller
{
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

        return response()->json([
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            if (!Auth::attempt($credentials)) {
                return response()->json(['message' => 'Nederīgi dati'], 401);
            }
        } catch (RuntimeException $exception) {
            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !hash_equals((string) $user->password, $credentials['password'])) {
                return response()->json(['message' => 'Nederīgi dati'], 401);
            }

            $user->password = Hash::make($credentials['password']);
            $user->save();
            Auth::login($user);
        }

        return response()->json([
            'user' => $request->user(),
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Izrakstīts veiksmīgi']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Nepieejams lietotājs'], 401);
        }

        $recentTopics = Topic::orderByDesc('created_at')->take(5)->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'created_at' => $user->created_at?->toISOString(),
            ],
            'recent_topics' => $recentTopics,
        ]);
    }
}