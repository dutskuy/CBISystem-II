@extends('layouts.admin')
@section('title', 'Riwayat Pergerakan Stok')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Riwayat Pergerakan Stok</h2>
        <p class="text-sm text-gray-500">Log semua perubahan stok produk</p>
    </div>
    <a href="{{ route('admin.stocks.index') }}" class="btn-secondary text-sm">← Kembali ke Stok</a>
</div>

{{-- Filter --}}
<div class="card mb-4">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari produk..." class="form-input flex-1 min-w-48">
        <select name="type" class="form-input w-36">
            <option value="">Semua Tipe</option>
            <option value="in"         {{ request('type') === 'in'         ? 'selected' : '' }}>Masuk</option>
            <option value="out"        {{ request('type') === 'out'        ? 'selected' : '' }}>Keluar</option>
            <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Koreksi</option>
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input w-40">
        <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="form-input w-40">
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','type','date_from','date_to']))
            <a href="{{ route('admin.stocks.movements') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jumlah</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Sebelum → Sesudah</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Referensi / Catatan</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Oleh</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($movements as $mov)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 text-gray-500 text-xs">
                        {{ $mov->created_at->format('d M Y') }}<br>
                        <span class="text-gray-400">{{ $mov->created_at->format('H:i') }}</span>
                    </td>
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-800">{{ $mov->product->name }}</p>
                        <p class="text-xs text-gray-400">{{ $mov->product->brand->name }} · {{ $mov->product->sku }}</p>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="text-xs font-bold px-2 py-1 rounded-full
                            {{ $mov->type === 'in' ? 'bg-green-100 text-green-700' : ($mov->type === 'out' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                            {{ $mov->type === 'in' ? 'Masuk' : ($mov->type === 'out' ? 'Keluar' : 'Koreksi') }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-center font-bold
                        {{ $mov->type === 'in' ? 'text-green-600' : ($mov->type === 'out' ? 'text-red-600' : 'text-yellow-600') }}">
                        {{ $mov->type === 'in' ? '+' : ($mov->type === 'out' ? '-' : '=') }}{{ $mov->quantity }}
                    </td>
                    <td class="px-6 py-3 text-center text-gray-600 font-mono text-xs">
                        {{ $mov->stock_before }} → {{ $mov->stock_after }}
                    </td>
                    <td class="px-6 py-3 text-gray-600 text-xs">
                        @if($mov->reference)
                            <p class="font-medium text-gray-700">{{ $mov->reference }}</p>
                        @endif
                        {{ $mov->notes ?? '-' }}
                    </td>
                    <td class="px-6 py-3 text-gray-600 text-xs">{{ $mov->createdBy->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada riwayat pergerakan stok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($movements->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $movements->links() }}</div>
    @endif
</div>
@endsection