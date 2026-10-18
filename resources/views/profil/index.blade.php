@extends('template')

@section('content')
@php
    $input = 'w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]';
    $label = 'block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1';
@endphp

<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-3xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Profil Saya</h1>
            <p class="text-sm text-[#52627A]">Kelola data diri dan password akun anda.</p>
        </div>

        @include('snippet.alert')

        {{-- DATA DIRI --}}
        <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 md:p-8 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">
            <h2 class="text-lg font-bold text-[#1E3A5F] mb-4 flex items-center gap-2"><i class='bx bx-user'></i> Data Diri</h2>

            <form action="{{ route('profil_update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="{{ $label }}">Username</label>
                        <input type="text" value="{{ $user->username }}" disabled class="{{ $input }} bg-gray-100 text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-[11px] text-[#52627A]/70 italic">Username tidak dapat diubah.</p>
                    </div>
                    <div>
                        <label class="{{ $label }}">Role</label>
                        <input type="text" value="{{ $user->role }}" disabled class="{{ $input }} bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label for="nama" class="{{ $label }}">Nama</label>
                    <input type="text" name="nama" id="nama" required maxlength="100" value="{{ old('nama', $user->nama) }}" class="{{ $input }}">
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="{{ $label }}">Email</label>
                        <input type="email" name="email" id="email" required maxlength="100" value="{{ old('email', $user->email) }}" class="{{ $input }}">
                    </div>
                    <div>
                        <label for="kontak" class="{{ $label }}">Kontak</label>
                        <input type="tel" inputmode="numeric" name="kontak" id="kontak" required pattern="\+?[0-9]{8,15}" title="8-15 digit angka"
                               value="{{ old('kontak', $user->kontak) }}" class="{{ $input }}">
                    </div>
                </div>

                @if ($mitra_bisnis)
                    {{-- Data tambahan khusus Mitra --}}
                    <div>
                        <label for="alamat" class="{{ $label }}">Alamat Usaha</label>
                        <input type="text" name="alamat" id="alamat" required maxlength="255" value="{{ old('alamat', $mitra_bisnis->alamat) }}" class="{{ $input }}">
                    </div>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_bank" class="{{ $label }}">Nama Bank</label>
                            <input type="text" name="nama_bank" id="nama_bank" required maxlength="100" value="{{ old('nama_bank', $mitra_bisnis->nama_bank) }}" class="{{ $input }}">
                        </div>
                        <div>
                            <label for="no_rekening" class="{{ $label }}">No Rekening</label>
                            <input type="text" inputmode="numeric" name="no_rekening" id="no_rekening" required pattern="[0-9]{5,20}" title="5-20 digit angka"
                                   value="{{ old('no_rekening', $mitra_bisnis->no_rekening) }}" class="{{ $input }}">
                        </div>
                    </div>
                @endif

                <button type="submit" class="cursor-pointer w-full bg-[#1E3A5F] hover:bg-[#152A46] text-white font-bold py-3 px-4 rounded-xl uppercase tracking-widest shadow-lg transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- GANTI PASSWORD --}}
        <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 md:p-8 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">
            <h2 class="text-lg font-bold text-[#1E3A5F] mb-4 flex items-center gap-2"><i class='bx bx-lock-alt'></i> Ganti Password</h2>

            <form action="{{ route('profil_password') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="password_lama" class="{{ $label }}">Password Lama</label>
                    <input type="password" name="password_lama" id="password_lama" required class="{{ $input }}">
                </div>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="{{ $label }}">Password Baru</label>
                        <input type="password" name="password" id="password" required minlength="6" class="{{ $input }}" placeholder="Minimal 6 karakter">
                    </div>
                    <div>
                        <label for="password_confirmation" class="{{ $label }}">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="6" class="{{ $input }}">
                    </div>
                </div>

                <button type="submit" class="cursor-pointer w-full bg-white border border-[#1E3A5F] text-[#1E3A5F] hover:bg-[#1E3A5F] hover:text-white font-bold py-3 px-4 rounded-xl uppercase tracking-widest transition">
                    Ganti Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
