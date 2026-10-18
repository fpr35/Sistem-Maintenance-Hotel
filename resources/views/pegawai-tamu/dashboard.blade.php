@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- HEADER DASHBOARD --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Dashboard Kerusakan</h1>
                <p class="text-sm text-[#52627A]">Ringkasan status laporan perbaikan fasilitas.</p>
            </div>
            
            {{-- Status Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#EAF0F7] border border-[#C9D4E3] rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#1E3A5F] animate-pulse"></span>
                <span class="text-[10px] text-[#52627A] uppercase tracking-[0.2em]">Halo</span>
                {{-- Menggunakan variabel $nama dari controller --}}
                <span class="text-sm font-semibold text-[#16243A]">{{ ucfirst($nama) }}</span>
            </div>
        </div>

        {{-- GRID KARTU STATISTIK --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- KARTU 1: LAPORAN MASUK (Menunggu) --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Laporan Masuk</h3>
                    <p class="text-4xl font-extrabold text-[#D48806] group-hover:scale-105 transition-transform duration-300">
                        {{ $laporan_masuk }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Menunggu Verifikasi</p>
                </div>
                {{-- Icon: Exclamation / Alert --}}
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            {{-- KARTU 2: TERVERIFIKASI --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Terverifikasi</h3>
                    <p class="text-4xl font-extrabold text-[#1E3A5F] group-hover:scale-105 transition-transform duration-300">
                        {{ $laporan_terverifikasi }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Siap Dikerjakan</p>
                </div>
                {{-- Icon: Check Clipboard --}}
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
            </div>

            {{-- KARTU 3: SEDANG DIPERBAIKI (Dikerjakan) --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Sedang Diperbaiki</h3>
                    <p class="text-4xl font-extrabold text-[#1D7BB8] group-hover:scale-105 transition-transform duration-300">
                        {{ $laporan_dikerjakan }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Proses Pengerjaan</p>
                </div>
                {{-- Icon: Wrench / Tools --}}
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            {{-- KARTU 4: SELESAI BULAN INI --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Selesai (Bulan Ini)</h3>
                    <p class="text-4xl font-extrabold text-[#2E7D32] group-hover:scale-105 transition-transform duration-300">
                        {{ $laporan_selesai }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Perbaikan Tuntas</p>
                </div>
                {{-- Icon: Check Circle --}}
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

        </div>
        
    </div>
</div>
@endsection