<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticateWithToken
{
    public function handle(Request $request, Closure $next)
    {
        $authHeader = $request->header('Authorization');
        
        if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
            $token = substr($authHeader, 7);
            
            try {
                $user = User::where('api_token', $token)->first();
                
                if ($user) {
                    Auth::login($user);
                    return $next($request);
                }
            } catch (\Exception $e) {
                \Log::error('Token auth error: ' . $e->getMessage());
            }
        }
        return $next($request);
    }
}
