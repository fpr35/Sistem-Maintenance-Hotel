@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Kelola User</h1>
            <p class="text-sm text-[#52627A]">Daftar semua akun. Akun yang dinonaktifkan tidak bisa login, tetapi datanya tetap tersimpan.</p>
        </div>

        @include('snippet.alert')

        {{-- Pencarian & filter role --}}
        <form action="{{ route('kelola_user') }}" method="GET" class="flex flex-col sm:flex-row gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, username, atau email..."
                   class="flex-1 rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
            <select name="role" class="rounded-lg border border-[#C9D4E3] bg-white px-3 py-2 text-sm">
                <option value="">Semua Role</option>
                @foreach (['Admin', 'Pegawai', 'Tamu', 'Mitra'] as $role)
                    <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>{{ $role }}</option>
                @endforeach
            </select>
            <button type="submit" class="cursor-pointer rounded-lg bg-[#1E3A5F] px-4 py-2 text-sm font-bold text-white hover:bg-[#152A46] transition">
                <i class="bx bx-search"></i> Cari
            </button>
        </form>

        <div class="bg-white rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-[#EAF0F7] border-b border-[#C9D4E3]">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Nama</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Username</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Role</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Email</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Kontak</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#C9D4E3]/30">
                        @forelse ($users as $data)
                            <tr class="hover:bg-[#F7F9FC] transition-colors {{ $data->aktif ? '' : 'opacity-60' }}">
                                <td class="px-6 py-4 text-sm font-semibold text-[#16243A]">{{ $data->nama }}</td>
                                <td class="px-6 py-4 text-sm text-[#16243A]">{{ $data->username }}</td>
                                <td class="px-6 py-4 text-xs uppercase tracking-wide text-[#52627A]">{{ $data->role ?: '-' }}</td>
                                <td class="px-6 py-4 text-sm text-[#16243A]">{{ $data->email ?: '-' }}</td>
                                <td class="px-6 py-4 text-sm text-[#16243A]">{{ $data->kontak ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($data->aktif)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 border border-green-200 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 border border-red-200 text-red-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($data->role !== 'Admin')
                                        <form action="{{ route('ubah_status_user', $data->user_id) }}" method="POST"
                                              onsubmit="return confirm('{{ $data->aktif ? 'Nonaktifkan' : 'Aktifkan' }} akun {{ $data->username }}?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="cursor-pointer rounded-lg px-3 py-1.5 text-xs font-bold uppercase tracking-wider transition
                                                {{ $data->aktif ? 'border border-red-200 text-red-600 hover:bg-red-50' : 'bg-[#1E3A5F] text-white hover:bg-[#152A46]' }}">
                                                {{ $data->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs italic text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-[#52627A] italic">Tidak ada user yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
