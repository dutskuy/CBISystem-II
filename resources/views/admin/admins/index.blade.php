@extends('layouts.admin')
@section('title', 'Kelola Admin')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Kelola Admin</h1>
            <p class="text-sm text-gray-500 mt-1">Manajemen akun admin dan staf sistem</p>
        </div>
        <a href="{{ route('admin.admins.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Admin
        </a>
    </div>

    <div class="card p-0 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Admin</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Role</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($admins as $admin)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0
                                    {{ $admin->role === 'super_admin' ? 'bg-red-100' : ($admin->role === 'admin_gudang' ? 'bg-orange-100' : 'bg-blue-100') }}">
                                    <span class="font-bold text-sm
                                        {{ $admin->role === 'super_admin' ? 'text-red-700' : ($admin->role === 'admin_gudang' ? 'text-orange-700' : 'text-blue-700') }}">
                                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $admin->name }}</p>
                                    @if($admin->id === auth()->id())
                                        <span class="text-xs text-blue-500">(Anda)</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $admin->email }}</td>
                        <td class="px-6 py-4">
                            @php
                                $roleLabel = [
                                    'super_admin'  => ['label' => 'Super Admin',  'class' => 'bg-red-100 text-red-700'],
                                    'admin'        => ['label' => 'Admin',        'class' => 'bg-blue-100 text-blue-700'],
                                    'admin_gudang' => ['label' => 'Admin Gudang', 'class' => 'bg-orange-100 text-orange-700'],
                                ];
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $roleLabel[$admin->role]['class'] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ $roleLabel[$admin->role]['label'] ?? $admin->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($admin->is_active)
                                <span class="badge-delivered">Aktif</span>
                            @else
                                <span class="badge-cancelled">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($admin->role !== 'super_admin')
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.admins.edit', $admin) }}"
                                    class="text-blue-600 hover:underline text-xs font-medium">Edit</a>

                                    <form method="POST" action="{{ route('admin.admins.toggle', $admin) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                onclick="return confirm('{{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')"
                                                class="{{ $admin->is_active ? 'text-red-500' : 'text-green-600' }} hover:underline text-xs font-medium">
                                            {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    <button onclick="toggleReset('reset-{{ $admin->id }}')"
                                            class="text-orange-500 hover:underline text-xs font-medium">
                                        Reset Password
                                    </button>

                                    <form method="POST"
                                        action="{{ route('admin.admins.destroy', $admin) }}"
                                        onsubmit="return confirm('Hapus akun {{ $admin->name }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline text-xs font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                {{-- Form Reset Password --}}
                                <div id="reset-{{ $admin->id }}" class="hidden mt-3 p-3 bg-orange-50 border border-orange-200 rounded-lg">
                                    <form method="POST" action="{{ route('admin.admins.reset-password', $admin) }}">
                                        @csrf @method('PATCH')
                                        <p class="text-xs font-medium text-orange-700 mb-2">Reset password {{ $admin->name }}</p>
                                        <div class="flex gap-2">
                                            <input type="password" name="new_password"
                                                placeholder="Password baru (min 8 karakter)"
                                                class="form-input text-xs flex-1" minlength="8" required>
                                            <input type="password" name="new_password_confirmation"
                                                placeholder="Konfirmasi"
                                                class="form-input text-xs w-32" required>
                                            <button type="submit" class="btn-primary text-xs py-1.5 px-3">
                                                Reset
                                            </button>
                                            <button type="button"
                                                    onclick="toggleReset('reset-{{ $admin->id }}')"
                                                    class="btn-secondary text-xs py-1.5 px-3">
                                                Batal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <span class="text-xs text-gray-300">— (dilindungi)</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
function toggleReset(id) {
    const el = document.getElementById(id);
    el.classList.toggle('hidden');
}
</script>
@endsection