@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-7xl mx-auto space-y-8">

        {{-- HEADER DASHBOARD --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Dashboard & Statistik</h1>
                <p class="text-sm text-[#52627A]">Ringkasan data inventaris dan aktivitas pesanan terkini.</p>
            </div>
            
            {{-- Optional: Status Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#EAF0F7] border border-[#C9D4E3] rounded-full shadow-sm">
                <span class="w-2 h-2 rounded-full bg-[#1E3A5F] animate-pulse"></span>
                <span class="text-[10px] text-[#52627A] uppercase tracking-[0.2em]">Halo</span>
                <span class="text-sm font-semibold text-[#16243A]">{{ ucfirst($nama) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Total Barang / Jasa</h3>
                    <p class="text-4xl font-extrabold text-[#1E3A5F] group-hover:scale-105 transition-transform duration-300">
                        {{ $barang_jasa->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Item terdaftar di sistem</p>
                </div>
                {{-- Decorative element --}}
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" viewBox="0 0 20 20" fill="currentColor"><path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" /><path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" /></svg>
                </div>
            </div>

            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Orderan Menunggu</h3>
                    <p class="text-4xl font-extrabold text-[#D48806] group-hover:scale-105 transition-transform duration-300">
                        {{ $pembelian_all->where('status_pembelian', 'Menunggu')->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Perlu konfirmasi segera</p>
                </div>
                 <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                </div>
            </div>

            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Orderan Diproses</h3>
                    <p class="text-4xl font-extrabold text-[#1D7BB8] group-hover:scale-105 transition-transform duration-300">
                        {{ $pembelian_all->where('status_pembelian', 'Diproses')->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Sedang dalam pengerjaan</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" /></svg>
                </div>
            </div>

            <div class="relative bg-[#F4F6FA] p-6 rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden group hover:border-[#D69E2E] transition-colors">
                <div class="relative z-10">
                    <h3 class="text-[10px] font-bold tracking-[0.22em] text-[#52627A] uppercase mb-2">Orderan Selesai</h3>
                    <p class="text-4xl font-extrabold text-[#2E7D32] group-hover:scale-105 transition-transform duration-300">
                        {{ $pembelian_all->where('status_pembelian', 'Selesai')->count() }}
                    </p>
                    <p class="text-xs text-[#52627A] mt-2 opacity-80">Total riwayat sukses</p>
                </div>
                <div class="absolute -right-4 -bottom-4 text-[#C9D4E3]/20 transform -rotate-12 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                </div>
            </div>

        </div>
        
    </div>
</div>
@endsection