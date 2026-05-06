@extends('layouts.admin')
@section('title', 'Manajemen Kategori')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Manajemen Kategori</h2>
        <p class="text-sm text-gray-500">Kelola kategori produk</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Kategori
    </a>
</div>

<div class="card p-0 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Jumlah Produk</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($categories as $category)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-800">{{ $category->name }}</p>
                        <p class="text-xs text-gray-400">{{ Str::limit($category->description, 60) }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-semibold">{{ $category->products_count }}</span>
                        <span class="text-gray-400 text-xs"> produk</span>
                    </td>
                    <td class="px-6 py-4">
                        @if($category->is_active)
                            <span class="badge-delivered">Aktif</span>
                        @else
                            <span class="badge-cancelled">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        Belum ada kategori. <a href="{{ route('admin.categories.create') }}" class="text-blue-600">Tambah sekarang</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if($categories->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $categories->links() }}</div>
    @endif
</div>
@endsection