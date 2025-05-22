<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $areas = [
            'churuata' => 'Churuata',
            'salon_principal' => 'Salón Principal',
            'POS-58' => 'Oficina (POS-58)',
        ];
        return view('auth.login', compact('areas'));
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'area' => 'required|in:churuata,salon_principal,POS-58',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->put('area_trabajo', $request->area);
            return redirect()->intended('/');
        }
        return back()->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}