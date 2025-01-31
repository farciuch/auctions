<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class SessionController extends Controller
{
    public function create(){
        return view('auth.login');
    }
    public function store(){
        // validate
        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // attempt to login the user
        if (! Auth::attempt(['email' => $attributes['email'], 'password' => $attributes['password']])) {
            // If the user cannot be authenticated, throw a validation exception
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.',
                'password' => 'The provided credentials are incorrect.',
            ]);
        }

        // regenerate the session token
        request()->session()->regenerate();

        // redirect
        return redirect('/');
    }
    public function destroy()
    {
        Auth::logout();
        return redirect('/');
    }
}
