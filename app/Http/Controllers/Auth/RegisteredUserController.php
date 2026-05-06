<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|string|email|max:255|unique:users',
            'phone'        => 'required|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'address'      => 'nullable|string',
            'password'     => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'phone'        => $request->phone,
            'company_name' => $request->company_name,
            'address'      => $request->address,
            'password'     => Hash::make($request->password),
            'role'         => 'customer',
            'is_active'    => true,
        ]);

        $user->assignRole('customer');

        // Buat cart kosong
        Cart::create(['user_id' => $user->id]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('customer.dashboard')
            ->with('success', 'Selamat datang, '.$user->name.'! Akun Anda berhasil dibuat.');
    }
}