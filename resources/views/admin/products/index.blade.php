@extends('layouts.admin')
@section('title', 'Manajemen Produk')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Produk</h2>
        <p class="text-sm text-gray-500">{{ $products->total() }} produk terdaftar</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </a>
</div>

{{-- Filter --}}
<div class="card mb-6">
    <form method="GET" class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, SKU, part number..."
               class="form-input flex-1 min-w-48">
        <select name="brand_id" class="form-input w-40">
            <option value="">Semua Brand</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}
                </option>
            @endforeach
        </select>
        <select name="category_id" class="form-input w-44">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <select name="status" class="form-input w-36">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
        </select>
        <button type="submit" class="btn-primary">Filter</button>
        @if(request()->hasAny(['search','brand_id','category_id','status']))
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">Reset</a>
        @endif
    </form>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Margin</th>
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Produk</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">SKU</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->image)
                                <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                                     class="w-12 h-12 object-contain rounded-lg border border-gray-100">
                            @else
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $product->name }}</p>
                                <p class="text-xs text-gray-400">{{ $product->brand->name }} · {{ $product->category->name }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="font-mono text-xs text-gray-700">{{ $product->sku }}</p>
                        @if($product->part_number)
                            <p class="font-mono text-xs text-gray-400">{{ $product->part_number }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($product->stock)
                            <span class="{{ $product->stock->isLowStock() ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                {{ $product->stock->quantity }}
                            </span>
                            <span class="text-gray-400 text-xs"> {{ $product->stock->unit }}</span>
                            @if($product->stock->isLowStock())
                                <span class="ml-1 text-xs text-red-500">⚠ Menipis</span>
                            @endif
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($product->is_active)
                            <span class="badge-delivered">Aktif</span>
                        @else
                            <span class="badge-cancelled">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.products.show', $product) }}"
                               class="text-gray-500 hover:text-gray-700 text-xs font-medium">Detail</a>
                            <a href="{{ route('admin.products.edit', $product) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}"
                                  onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->cost_price > 0)
                            <p class="{{ $product->margin_percent >= 20 ? 'text-green-600' : ($product->margin_percent >= 10 ? 'text-yellow-600' : 'text-red-500') }} font-semibold text-sm">
                                {{ $product->margin_percent }}%
                            </p>
                            <p class="text-xs text-gray-400">
                                +Rp {{ number_format($product->profit, 0, ',', '.') }}
                            </p>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        Belum ada produk. <a href="{{ route('admin.products.create') }}" class="text-blue-600">Tambah sekarang</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $products->links() }}</div>
    @endif
</div>
@endsection