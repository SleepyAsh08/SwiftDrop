<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MobileController extends Controller
{
    //
    public function authenticate(Request $request)
    {
        // dd($request);
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        // dd($request);
        // Attempt authentication using Laravel's built-in Auth
        if (Auth::attempt($credentials)) {
            // User authenticated, redirect or return success response
            // return redirect()->intended('dashboard'); // Example redirect
            return response()->json(['success' => 'Valid credentials'], 200);
            dd();
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }
}
