@extends('layouts.owner')
@section('title', 'Data Customer')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Customer</h1>

    <div class="card">
        <form method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, perusahaan..."
                   class="form-input flex-1">
            <button type="submit" class="btn-primary">Cari</button>
            @if(request('search'))
                <a href="{{ route('owner.customers.index') }}" class="btn-secondary">Reset</a>
            @endif
        </form>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Perusahaan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Pesanan</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Total Belanja</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-emerald-700 font-bold text-xs">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm">{{ $customer->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $customer->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $customer->company_name ?? '-' }}</td>
                        <td class="px-6 py-4 font-semibold text-sm">{{ $customer->orders_count }}</td>
                        <td class="px-6 py-4 font-semibold text-emerald-700 text-sm">
                            Rp {{ number_format($customer->orders_sum_total ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($customer->is_active)
                                <span class="badge-delivered">Aktif</span>
                            @else
                                <span class="badge-cancelled">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">Belum ada customer.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        @if($customers->hasPages())
            <div class="px-6 py-4 border-t">{{ $customers->links() }}</div>
        @endif
    </div>
</div>
@endsection