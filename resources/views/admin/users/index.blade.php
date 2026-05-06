@extends('layouts.admin')
@section('title', 'Manajemen Users')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Users</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola akun customer yang terdaftar</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Customer</p>
            <p class="text-2xl font-bold text-gray-800">{{ $summary['total'] }}</p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Aktif</p>
            <p class="text-2xl font-bold text-green-600">{{ $summary['active'] }}</p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Nonaktif</p>
            <p class="text-2xl font-bold text-red-500">{{ $summary['inactive'] }}</p>
        </div>
        <div class="card p-4">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Baru Bulan Ini</p>
            <p class="text-2xl font-bold text-blue-600">{{ $summary['new_this_month'] }}</p>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="card">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, perusahaan, telepon..."
                   class="form-input flex-1 min-w-48">
            <select name="status" class="form-input w-40" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn-primary">Cari</button>
            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    {{-- Tabel --}}
    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kontak</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pesanan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Total Belanja</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors">

                        {{-- Customer --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-blue-700 font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">
                                        Daftar {{ $user->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Kontak --}}
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $user->email }}</p>
                            <p class="text-xs text-gray-400">{{ $user->phone ?? '-' }}</p>
                        </td>

                        {{-- Perusahaan --}}
                        <td class="px-6 py-4">
                            <p class="text-gray-700">{{ $user->company_name ?? '-' }}</p>
                        </td>

                        {{-- Pesanan --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $user->orders_count }}</p>
                            <p class="text-xs text-gray-400">pesanan</p>
                        </td>

                        {{-- Total Belanja --}}
                        <td class="px-6 py-4">
                            <p class="font-semibold text-blue-700">
                                Rp {{ number_format($user->orders_sum_total ?? 0, 0, ',', '.') }}
                            </p>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-600 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span> Nonaktif
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium hover:underline">
                                    Detail
                                </a>
                                <span class="text-gray-300">|</span>
                                <form method="POST" action="{{ route('admin.users.toggleActive', $user) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit"
                                            onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $user->name }}?')"
                                            class="{{ $user->is_active ? 'text-red-500 hover:text-red-700' : 'text-green-600 hover:text-green-800' }} text-xs font-medium hover:underline">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p>Tidak ada customer ditemukan</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection