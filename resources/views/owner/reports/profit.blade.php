@extends('layouts.owner')
@section('title', 'Laporan Keuntungan')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Laporan Keuntungan</h1>
    </div>

    {{-- Filter --}}
    <div class="card">
        <form method="GET" class="flex gap-3 flex-wrap">
            <select name="year" class="form-input w-32" onchange="this.form.submit()">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
            <select name="month" class="form-input w-40" onchange="this.form.submit()">
                <option value="">Semua Bulan</option>
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $m)
                    <option value="{{ $i+1 }}" {{ $month == $i+1 ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-400 mb-1">Total Penjualan</p>
            <p class="text-xl font-bold text-blue-700">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
        </div>
        <div class="card p-4 border-l-4 border-red-400">
            <p class="text-xs text-gray-400 mb-1">Total Modal</p>
            <p class="text-xl font-bold text-red-600">Rp {{ number_format($summary['total_cost'], 0, ',', '.') }}</p>
        </div>
        <div class="card p-4 border-l-4 border-orange-400">
            <p class="text-xs text-gray-400 mb-1">Total Pajak (PPN)</p>
            <p class="text-xl font-bold text-orange-600">Rp {{ number_format($summary['total_tax'], 0, ',', '.') }}</p>
        </div>
        <div class="card p-4 border-l-4 border-green-500 bg-green-50">
            <p class="text-xs text-gray-400 mb-1">Keuntungan Kotor</p>
            <p class="text-xl font-bold text-green-700">Rp {{ number_format($summary['total_profit'], 0, ',', '.') }}</p>
            <p class="text-xs font-semibold mt-1 {{ $summary['margin'] >= 20 ? 'text-green-600' : ($summary['margin'] >= 10 ? 'text-yellow-600' : 'text-red-500') }}">
                Margin: {{ $summary['margin'] }}%
            </p>
        </div>
    </div>

    {{-- Tabel Profit per Produk --}}
    <div class="card p-0 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-700">Keuntungan per Produk</h3>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Terjual</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Revenue</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Modal</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Keuntungan</th>
                    <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Margin</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($productProfit as $i => $product)
                    @php
                        $margin = $product->total_revenue > 0
                            ? round(($product->total_profit / $product->total_revenue) * 100, 1)
                            : 0;
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-gray-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-6 py-3">
                            <p class="font-medium text-gray-800 text-xs">{{ $product->product_name }}</p>
                            <p class="text-xs text-gray-400 font-mono">{{ $product->product_sku }}</p>
                        </td>
                        <td class="px-6 py-3 font-semibold text-xs">{{ number_format($product->total_qty) }} pcs</td>
                        <td class="px-6 py-3 text-blue-700 font-semibold text-xs">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-red-500 text-xs">Rp {{ number_format($product->total_cost, 0, ',', '.') }}</td>
                        <td class="px-6 py-3 text-green-600 font-bold text-xs">Rp {{ number_format($product->total_profit, 0, ',', '.') }}</td>
                        <td class="px-6 py-3">
                            <span class="text-xs font-bold {{ $margin >= 20 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-500') }}">
                                {{ $margin }}%
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection