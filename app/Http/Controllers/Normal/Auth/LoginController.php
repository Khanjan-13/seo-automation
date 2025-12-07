<?php

namespace App\Http\Controllers\Normal\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('normal.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('normaluser')->attempt($credentials)) {
            return redirect()->route('normal.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid login credentials']);
    }

    public function logout()
    {
        Auth::guard('normaluser')->logout();
        return redirect()->route('normal.login');
    }
}
