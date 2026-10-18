@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- HEADER DASHBOARD --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Dashboard Admin</h1>
                <p class="text-sm text-[#52627A]">Monitoring maintenance & operasional hotel secara real-time.</p>
            </div>
            
            {{-- Status Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#EAF0F7] border border-[#C9D4E3] rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#C53030] animate-pulse"></span> {{-- Merah karena Admin --}}
                <span class="text-[10px] text-[#52627A] uppercase tracking-[0.2em]">Halo</span>
                <span class="text-sm font-semibold text-[#16243A]">{{ ucfirst($nama) }}</span>
            </div>
        </div>

        {{-- GRID STATISTIK --}}
        {{--
           - grid-cols-1 (1 kolom di HP)
           - sm:grid-cols-2 (2 kolom di Tablet kecil)
           - lg:grid-cols-4 (4 kolom di Laptop/PC) 
        --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Card 1: Total Laporan --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Total Laporan</h3>
                    <p class="text-4xl font-extrabold text-[#1E3A5F] group-hover:scale-105 transition-transform duration-300">
                        {{ $laporan_all->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Akumulasi laporan masuk</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    {{-- Icon Clipboard List --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                </div>
            </div>

            {{-- Card 2: Barang / Jasa --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Barang / Jasa</h3>
                    <p class="text-4xl font-extrabold text-[#1D7BB8] group-hover:scale-105 transition-transform duration-300">
                        {{ $barang_jasa->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Item inventaris terdaftar</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    {{-- Icon Cube/Box --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </div>
            </div>

            {{-- Card 3: Pembelian --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Total Pembelian</h3>
                    <p class="text-4xl font-extrabold text-[#2E7D32] group-hover:scale-105 transition-transform duration-300">
                        {{ $pembelian_all->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Transaksi tercatat</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    {{-- Icon Shopping Bag/Cart --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                </div>
            </div>

            {{-- Card 4: Mitra Belum Verifikasi --}}
            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#C53030] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Mitra Belum Verifikasi</h3>
                    {{-- Menggunakan warna Merah Bata agar tetap masuk tema Earth Tone tapi terlihat warning --}}
                    <p class="text-4xl font-extrabold text-[#C53030] group-hover:scale-105 transition-transform duration-300">
                        {{ $mitra_belum_verifikasi_all->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Butuh persetujuan segera</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[#C53030]/10 transform -rotate-12 group-hover:scale-110 transition-transform">
                    {{-- Icon User Warning --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection