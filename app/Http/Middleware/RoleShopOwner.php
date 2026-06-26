<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleShopOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->role === 'shop_owner' || $user->role === 'admin') {
                if ($user->shop || $user->role === 'admin') {
                    return $next($request);
                }
                return redirect('/profile?showRegisterForm=1')->with('error', 'Pehle store register karein.');
            }
        }

        return redirect('/profile')->with('error', 'Store owner access required.');
    }
}
