<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminManagerController extends Controller
{
    public function index()
    {
        $admins = User::whereIn('role', ['super_admin', 'admin', 'admin_gudang'])
            ->latest()
            ->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:admin,admin_gudang',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'is_active' => true,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun admin "'.$user->name.'" berhasil dibuat.');
    }

    public function edit(User $user)
    {
        abort_if($user->role === 'super_admin', 403);
        return view('admin.admins.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        abort_if($user->role === 'super_admin', 403);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role'  => 'required|in:admin,admin_gudang',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun admin "'.$user->name.'" berhasil diperbarui.');
    }

    public function toggleActive(User $user)
    {
        abort_if($user->role === 'super_admin', 403);

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun {$user->name} berhasil {$status}.");
    }

    public function resetPassword(Request $request, User $user)
    {
        abort_if($user->role === 'super_admin', 403);

        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', "Password {$user->name} berhasil direset.");
    }

    public function destroy(User $user)
    {
        abort_if($user->role === 'super_admin', 403);
        abort_if($user->id === auth()->id(), 403);

        $user->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Akun admin berhasil dihapus.');
    }
}