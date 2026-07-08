<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResetPasswordMail;

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
            'opens_at' => 'required|string',
            'closes_at' => 'required|string',
            'delivery_enabled' => 'nullable',
            'delivery_charge_type' => 'required|string|in:fixed,dynamic',
            'delivery_charge_rate' => 'nullable|numeric|min:0',
            'offer_min_bill' => 'nullable|numeric|min:0',
            'offer_discount_pct' => 'nullable|numeric|min:0|max:100'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'role' => 'shop_owner',
            ]);

            $deliveryEnabled = $request->has('delivery_enabled');
            $chargeType = $request->delivery_charge_type;
            $rate = (float)($request->delivery_charge_rate ?? 0.00);

            $fixedCharge = ($chargeType === 'fixed') ? $rate : 0.00;
            $perKmCharge = ($chargeType === 'dynamic') ? $rate : 0.00;

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
                'delivery_enabled' => $deliveryEnabled,
                'is_online' => true,
                'status' => 'approved',
                'user_id' => $user->id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'opens_at' => $request->opens_at,
                'closes_at' => $request->closes_at,
                'delivery_charge_type' => $chargeType,
                'delivery_charge_fixed' => $fixedCharge,
                'delivery_charge_per_km' => $perKmCharge,
                'offer_min_bill' => (float)($request->offer_min_bill ?? 0.00),
                'offer_discount_pct' => (float)($request->offer_discount_pct ?? 0.00),
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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $token = Str::random(60);

        // Delete any existing tokens for this user
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Insert new token (save plain text token for simple verification)
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        try {
            Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Could not send recovery email. Please check your SMTP mail server settings: ' . $e->getMessage(),
            ]);
        }

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetForm(Request $request, $token)
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.reset_password', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->token)
                    ->first();

        if (!$record) {
            return back()->withErrors([
                'email' => 'This password reset link is invalid or has expired.',
            ]);
        }

        // Token is valid, update user's password
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Clean up token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

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
