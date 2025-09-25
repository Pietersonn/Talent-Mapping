<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration view.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            // HAPUS 'confirmed' karena kita tidak pakai confirm password di form
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'password'  => Hash::make($validated['password']),
            // kolom khusus app kamu:
            'role'      => 'user',
            'is_active' => true,
        ]);

        event(new Registered($user));

        // langsung login & redirect ke home (sesuai requirement kamu)
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Account created successfully. Welcome!');
    }
}
