<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function actionLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role_id == 1) {
                return redirect()->intended('/dashboard');
            } else if ($user->role_id == 2) {
                return redirect()->intended('/dashboard');
            } else if ($user->role_id == 3) {
                return redirect()->intended('/dashboard');
            }
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid Email or Password'])
            ->onlyInput();
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
