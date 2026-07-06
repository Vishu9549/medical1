<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect('/admin')->with('success', 'Logged in as Admin!');
            } elseif ($user->role === 'shop_owner') {
                return redirect('/shop/dashboard')->with('success', 'Logged in to store dashboard!');
            }
            return redirect()->intended('/')->with('success', 'Logged in successfully!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Account registered successfully!');
    }

    public function showShopRegister()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.register_shop');
    }

    public function shopRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string|max:15',
            'shop_name' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'address' => 'required|string|max:1000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'shop_owner',
            ]);

            $shop = \App\Models\Shop::create([
                'name' => $request->shop_name,
                'owner_name' => $request->name,
                'phone' => $request->phone,
                'area' => $request->area,
                'address' => $request->address,
                'rating' => 5.0,
                'reviews' => 0,
                'distance_km' => round(rand(5, 45) / 10, 1),
                'is_top' => false,
                'delivery_enabled' => true,
                'is_online' => true,
                'status' => 'approved',
                'user_id' => $user->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            \App\Models\Wallet::create([
                'shop_id' => $shop->id,
                'total_sales' => 0,
                'due_commission' => 0,
                'credit_limit' => 100,
                'status' => 'active'
            ]);



            Auth::login($user);
        });

        return redirect('/shop/dashboard')->with('success', 'Pharmacy registered successfully!');
    }

    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.forgot_password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'phone' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::where('email', $request->email)
                    ->where('phone', $request->phone)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided email and phone number combination does not match our records.',
            ])->withInput($request->only('email', 'phone'));
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')->with('success', 'Password reset successfully! Please login with your new password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
