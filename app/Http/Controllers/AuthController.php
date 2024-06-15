<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (auth()->attempt($credentials)) {
            return redirect()->intended('/dashboard');
        } else {
            return back()->withErrors(['email' => 'Email atau password salah.'])->withInput();
        }
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => ['required', 'email', 'unique:users'],
            'nama' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = \App\Models\User::create([
            'email' => $validatedData['email'],
            'name' => $validatedData['nama'],
            'password' => bcrypt($validatedData['password']),
        ]);

        auth()->login($user);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
