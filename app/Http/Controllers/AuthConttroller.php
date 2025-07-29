<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthConttroller extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'terapis') {
                return redirect()->route('terapis.pasien.index');
            } elseif (Auth::user()->role === 'dokter') {
                return redirect()->route('dokter.rekamMedis.index');
            }

            return redirect('/');
        }

        return back()->withErrors([
            'username' => 'Akun belum terdaftar',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
