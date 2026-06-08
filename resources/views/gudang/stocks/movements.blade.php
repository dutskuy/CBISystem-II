@extends('layouts.gudang')
@section('title', 'Riwayat Stok')

@section('content')
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-800">Riwayat Pergerakan Stok</h1>

    <div class="card">
        <form method="GET" class="flex gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama produk atau SKU..." class="form-input flex-1">
            <select name="type" class="form-input w-36" onchange="this.form.submit()">
                <option value="">Semua Tipe</option>
                <option value="in"         {{ request('type') === 'in'         ? 'selected' : '' }}>Masuk</option>
                <option value="out"        {{ request('type') === 'out'        ? 'selected' : '' }}>Keluar</option>
                <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Koreksi</option>
            </select>
            <button type="submit" class="btn-primary">Cari</button>
            @if(request()->hasAny(['search','type']))
                <a href="{{ route('gudang.stocks.movements') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Sebelum</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Sesudah</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Oleh</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($movements as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800 text-sm">{{ $m->product->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $m->product->sku ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-2 py-1 rounded-full
                                {{ $m->type === 'in' ? 'bg-green-100 text-green-700' : ($m->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700') }}">
                                {{ $m->type === 'in' ? 'Masuk' : ($m->type === 'out' ? 'Keluar' : 'Koreksi') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 font-bold">{{ $m->quantity }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $m->stock_before }}</td>
                        <td class="px-6 py-4 font-semibold">{{ $m->stock_after }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $m->notes ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs text-gray-500">{{ $m->createdBy->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs text-gray-400">{{ $m->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">Belum ada riwayat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($movements->hasPages())
            <div class="px-6 py-4 border-t">{{ $movements->links() }}</div>
        @endif
    </div>
</div>
@endsection