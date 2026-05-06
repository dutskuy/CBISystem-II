@extends('layouts.admin')
@section('title', 'Manajemen Brand')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Brand</h2>
        <p class="text-sm text-gray-500">Kelola brand produk yang tersedia</p>
    </div>
    <a href="{{ route('admin.brands.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Brand
    </a>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Brand</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Negara Asal</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jumlah Produk</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($brands as $brand)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($brand->logo)
                                <img src="{{ asset('storage/'.$brand->logo) }}" alt="{{ $brand->name }}"
                                     class="w-10 h-10 object-contain rounded-lg border border-gray-100">
                            @else
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-blue-700 font-bold text-xs">{{ substr($brand->name, 0, 2) }}</span>
                                </div>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ $brand->name }}</p>
                                <p class="text-xs text-gray-400">{{ Str::limit($brand->description, 50) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $brand->origin_country ?? '-' }}</td>
                    <td class="px-6 py-4">
                        <span class="font-semibold text-gray-800">{{ $brand->products_count }}</span>
                        <span class="text-gray-400 text-xs"> produk</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($brand->is_active)
                            <span class="badge-delivered">Aktif</span>
                        @else
                            <span class="badge-cancelled">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.brands.edit', $brand) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus brand ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                        Belum ada brand. <a href="{{ route('admin.brands.create') }}" class="text-blue-600">Tambah sekarang</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($brands->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $brands->links() }}
        </div>
    @endif
</div>
@endsection