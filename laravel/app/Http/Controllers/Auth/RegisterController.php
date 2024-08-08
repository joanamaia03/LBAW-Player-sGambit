<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\View\View;

use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Display a login form.
     */
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect('/mainPage');
        } else {
            return view('auth.register');
        };
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        if($request->filled('age')){
            $request->validate([
                'username' => 'required|string|max:250',
                'email' => 'required|email|max:250|unique:users',
                'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d])/'
            ],[
                'password' => 'Password must have 8 characters, a lower case letter, a upper case letter, a number and a symbol'
            ]);

            User::create([
                'username' =>  $request->username,
                'email' => $request->email,
                'is_admin' => false,
                'password' => Hash::make($request->password),
                'name' => $request->username
            ]);

            $credentials = $request->only('email', 'password');
            Auth::attempt($credentials);
            $request->session()->regenerate();
            return redirect('/mainPage')
                ->withSuccess('You have successfully registered & logged in!');
        }
        return back()->withErrors([
            'age' => 'Must be over 18 to create an account.',
        ]);
    }
}
