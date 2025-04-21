<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DOSAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('dos.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if the user is a DOS
            if ($user->role !== 'dos') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'You do not have permission to access the DOS panel.',
                ]);
            }
            
            $request->session()->regenerate();
            return redirect()->route('dos.dashboard');
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('dos.login');
    }
}