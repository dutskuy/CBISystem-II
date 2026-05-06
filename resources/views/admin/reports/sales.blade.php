@extends('layouts.admin')
@section('title', 'Laporan Penjualan')

@section('content')

{{-- Filter --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Laporan Penjualan</h2>
        <p class="text-sm text-gray-500">Analisis revenue dan performa penjualan</p>
    </div>
    <a href="{{ route('admin.reports.sales.export', request()->query()) }}"
       class="btn-success flex items-center gap-2 text-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        Export CSV
    </a>
</div>

<div class="card mb-6">
    <form method="GET" class="flex gap-3 items-end">
        <div>
            <label class="block text-xs text-gray-500 mb-1">Tahun</label>
            <select name="year" class="form-input w-28">
                @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs text-gray-500 mb-1">Bulan</label>
            <select name="month" class="form-input w-36">
                <option value="">Semua Bulan</option>
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMMM') }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">Tampilkan</button>
        @if($month)
            <a href="{{ route('admin.reports.sales', ['year' => $year]) }}" class="btn-secondary">Reset Bulan</a>
        @endif
    </form>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    <div class="card p-4">
        <p class="text-xs text-gray-500 mb-1">Total Revenue</p>
        <p class="text-xl font-bold text-green-700">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-gray-500 mb-1">Total Pesanan</p>
        <p class="text-xl font-bold text-blue-700">{{ number_format($summary['total_orders']) }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-gray-500 mb-1">Rata-rata per Pesanan</p>
        <p class="text-xl font-bold text-purple-700">Rp {{ number_format($summary['avg_order'], 0, ',', '.') }}</p>
    </div>
    <div class="card p-4">
        <p class="text-xs text-gray-500 mb-1">Total Item Terjual</p>
        <p class="text-xl font-bold text-orange-700">{{ number_format($summary['total_items']) }}</p>
    </div>
</div>
<div class="card p-4">
    <p class="text-xs text-gray-500 mb-1">Total PPN (11%)</p>
    <p class="text-xl font-bold text-orange-600">Rp {{ number_format($summary['total_tax'], 0, ',', '.') }}</p>
</div>
<div class="card p-4">
    <p class="text-xs text-gray-500 mb-1">Revenue (sebelum pajak)</p>
    <p class="text-xl font-bold text-blue-700">Rp {{ number_format($summary['total_subtotal'], 0, ',', '.') }}</p>
</div>
<div class="card mb-6 border-l-4 border-green-500">
    <h3 class="font-semibold text-gray-700 mb-4">Ringkasan Keuntungan</h3>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <div class="text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Pendapatan</p>
            <p class="text-2xl font-bold text-blue-700">
                Rp {{ number_format($profitData->total_revenue ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">(harga jual)</p>
        </div>

        <div class="text-center">
            <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total Modal</p>
            <p class="text-2xl font-bold text-red-600">
                Rp {{ number_format($profitData->total_cost ?? 0, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">(harga beli)</p>
        </div>

        <div class="text-center p-4 bg-green-50 rounded-xl">
            <p class="text-xs text-green-600 uppercase tracking-wide mb-1 font-semibold">Keuntungan Kotor</p>
            <p class="text-3xl font-bold text-green-700">
                Rp {{ number_format($profitData->total_profit ?? 0, 0, ',', '.') }}
            </p>
            @php
                $margin = $profitData->total_revenue > 0
                    ? round(($profitData->total_profit / $profitData->total_revenue) * 100, 1)
                    : 0;
            @endphp
            <p class="text-sm font-semibold mt-1 {{ $margin >= 20 ? 'text-green-600' : ($margin >= 10 ? 'text-yellow-600' : 'text-red-500') }}">
                Margin: {{ $margin }}%
            </p>
        </div>

    </div>
</div>
{{-- Chart Revenue Bulanan --}}
@if(!$month)
<div class="card mb-6">
    <h3 class="font-semibold text-gray-700 mb-4">Revenue per Bulan — {{ $year }}</h3>
    <div class="flex items-end gap-2 h-48">
        @php
            $maxRevenue = collect(range(1,12))->map(fn($m) => $monthlyRevenue[$m]->total ?? 0)->max();
            $maxRevenue = $maxRevenue ?: 1;
        @endphp
        @foreach(range(1,12) as $m)
            @php
                $rev   = $monthlyRevenue[$m]->total ?? 0;
                $count = $monthlyRevenue[$m]->count ?? 0;
                $height = $maxRevenue > 0 ? round(($rev / $maxRevenue) * 160) : 0;
                $isCurrentMonth = $m == now()->month && $year == now()->year;
            @endphp
            <div class="flex-1 flex flex-col items-center gap-1">
                <div class="text-xs text-gray-500 font-medium">
                    @if($rev > 0) {{ number_format($rev/1000000, 1) }}M @endif
                </div>
                <div class="w-full rounded-t-md transition-all duration-500 cursor-pointer group relative"
                     style="height: {{ max($height, 4) }}px; background: {{ $isCurrentMonth ? '#1d4ed8' : '#93c5fd' }}"
                     title="Rp {{ number_format($rev, 0, ',', '.') }}">
                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity z-10">
                        {{ $count }} pesanan
                    </div>
                </div>
                <div class="text-xs text-gray-400">
                    {{ \Carbon\Carbon::create()->month($m)->isoFormat('MMM') }}
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Top Produk --}}
    <div class="card">
        <h3 class="font-semibold text-gray-700 mb-4">Top 10 Produk Terlaris</h3>
        @if($topProducts->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
        @else
            <div class="space-y-3">
                @foreach($topProducts as $i => $product)
                    @php $maxQty = $topProducts->first()->total_qty ?: 1; @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <div class="flex items-center gap-2">
                                <span class="w-5 h-5 bg-blue-100 text-blue-700 rounded text-xs font-bold flex items-center justify-center">
                                    {{ $i+1 }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-800 text-xs">{{ Str::limit($product->product_name, 35) }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $product->product_sku }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-800">{{ number_format($product->total_qty) }} pcs</p>
                                <p class="text-xs text-green-600">Rp {{ number_format($product->total_revenue/1000000, 1) }}M</p>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: {{ ($product->total_qty / $maxQty) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Revenue per Brand --}}
    <div class="card">
        <h3 class="font-semibold text-gray-700 mb-4">Revenue per Brand</h3>
        @if($brandRevenue->isEmpty())
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
        @else
            @php $maxBrandRev = $brandRevenue->first()->total_revenue ?: 1; @endphp
            <div class="space-y-3">
                @foreach($brandRevenue as $brand)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-gray-700">{{ $brand->brand_name }}</span>
                            <div class="text-right">
                                <span class="font-bold text-gray-800">Rp {{ number_format($brand->total_revenue/1000000, 1) }}M</span>
                                <span class="text-xs text-gray-400 ml-1">{{ number_format($brand->total_qty) }} pcs</span>
                            </div>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-blue-900 h-2 rounded-full" style="width: {{ ($brand->total_revenue / $maxBrandRev) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

{{-- Tabel Pesanan --}}
<div class="card p-0 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-700">Daftar Pesanan</h3>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">No. Pesanan</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Pelanggan</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Item</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-3 font-mono text-blue-700 text-xs">
                        <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a>
                    </td>
                    <td class="px-6 py-3 font-medium text-gray-800">{{ $order->user->name }}</td>
                    <td class="px-6 py-3 text-gray-500">{{ $order->items->sum('quantity') }} pcs</td>
                    <td class="px-6 py-3 font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td class="px-6 py-3"><span class="badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td class="px-6 py-3 text-gray-500 text-xs">{{ $order->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">Belum ada data penjualan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $orders->links() }}</div>
    @endif
</div>

@endsection