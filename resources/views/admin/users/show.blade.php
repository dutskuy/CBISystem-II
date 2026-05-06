@extends('layouts.admin')
@section('title', 'Detail Customer')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.users.index') }}"
               class="text-sm text-blue-600 hover:underline">← Kembali ke Users</a>
            <h1 class="text-2xl font-bold text-gray-800 mt-1">Detail Customer</h1>
        </div>
        <form method="POST" action="{{ route('admin.users.toggleActive', $user) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')"
                    class="{{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                {{ $user->is_active ? '✗ Nonaktifkan Akun' : '✓ Aktifkan Akun' }}
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info Customer --}}
        <div class="space-y-4">

            {{-- Profile Card --}}
            <div class="card text-center py-6">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                    <span class="text-blue-700 font-black text-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <h2 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h2>
                <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                <div class="mt-3">
                    @if($user->is_active)
                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Akun Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-medium px-3 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Akun Nonaktif
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-3">
                    Terdaftar {{ $user->created_at->isoFormat('D MMMM Y') }}
                </p>
            </div>

            {{-- Info Detail --}}
            <div class="card space-y-3">
                <h3 class="font-semibold text-gray-700 border-b pb-2">Informasi Kontak</h3>
                <div>
                    <p class="text-xs text-gray-400">No. Telepon</p>
                    <p class="text-sm font-medium text-gray-700">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Perusahaan</p>
                    <p class="text-sm font-medium text-gray-700">{{ $user->company_name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400">Alamat</p>
                    <p class="text-sm font-medium text-gray-700">{{ $user->address ?? '-' }}</p>
                </div>
            </div>

            {{-- Statistik --}}
            <div class="card space-y-3">
                <h3 class="font-semibold text-gray-700 border-b pb-2">Statistik Belanja</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-blue-700">{{ $stats['total_orders'] }}</p>
                        <p class="text-xs text-blue-500">Total Pesanan</p>
                    </div>
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-green-700">{{ $stats['completed'] }}</p>
                        <p class="text-xs text-green-500">Selesai</p>
                    </div>
                    <div class="bg-yellow-50 rounded-lg p-3 text-center">
                        <p class="text-xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                        <p class="text-xs text-yellow-500">Pending</p>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                        <p class="text-sm font-bold text-purple-700">
                            Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-purple-500">Total Belanja</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Riwayat Pesanan --}}
        <div class="lg:col-span-2">
            <div class="card p-0 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-700">Riwayat Pesanan</h3>
                </div>

                @if($orders->isEmpty())
                    <div class="py-16 text-center text-gray-400">
                        <p>Belum ada pesanan.</p>
                    </div>
                @else
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-3 font-mono text-blue-700 text-xs">{{ $order->order_number }}</td>
                                    <td class="px-6 py-3 font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-3">
                                        <span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                           class="text-blue-600 text-xs hover:underline">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($orders->hasPages())
                        <div class="px-6 py-4 border-t border-gray-50">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
{{-- Reset Password --}}
<div class="card" x-data="{ open: false }">
    <button type="button" @click="open = !open"
            class="w-full flex items-center justify-between text-left">
        <h3 class="font-semibold text-gray-700">Reset Password</h3>
        <svg :class="open ? 'rotate-180' : ''"
             class="w-4 h-4 text-gray-400 transition-transform"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="open" x-transition class="mt-4 border-t pt-4">
        <p class="text-xs text-gray-400 mb-3">
            Password baru akan langsung aktif. Customer tidak akan mendapat notifikasi email.
        </p>
        <form method="POST" action="{{ route('admin.users.resetPassword', $user) }}"
              onsubmit="return confirm('Reset password {{ $user->name }}?')">
            @csrf @method('PATCH')
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="new_password" required
                           minlength="8" class="form-input text-sm"
                           placeholder="Min. 8 karakter">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="new_password_confirmation" required
                           class="form-input text-sm" placeholder="Ulangi password baru">
                </div>
                <button type="submit" class="btn-danger w-full text-sm">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
    </div>
</div>
@endsection