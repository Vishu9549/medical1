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
            'role' => 'required|string|in:customer,shop_owner',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
        ]);

        Auth::login($user);

        if ($user->role === 'shop_owner') {
            return redirect('/profile?showRegisterForm=1')->with('success', 'Account created! Now please list your shop details.');
        }

        return redirect('/')->with('success', 'Account registered successfully!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
