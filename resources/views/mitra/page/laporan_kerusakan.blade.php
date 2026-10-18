@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-7xl mx-auto">
        
        {{-- HEADER PAGE --}}
        <div class="mb-8 ">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-2">Laporan Kerusakan</h1>
                <p class="text-sm text-[#52627A]">Laporan kerusakan fasilitas terkini.</p>
            </div>
        </div>

        {{-- Search --}}
        <div class="w-full mb-6">
            <form action="{{ route('pencarian_laporan_kerusakan_mitra') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari laporan kerusakan..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>

        {{-- WIDGET CONTAINER (GRID LAYOUT) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @foreach ($laporan_all as $lap)
                <div class="group relative bg-white rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden hover:shadow-[0_12px_24px_rgba(22,36,58,0.3)] hover:border-[#D69E2E] transition-all duration-300">
                    
                    {{-- BAGIAN FOTO --}}
                    <div class="relative h-48 w-full overflow-hidden bg-gray-100">
                        {{-- Badge Tanggal (Overlay di atas foto) --}}
                        <div class="absolute top-3 right-3 z-10">
                            <span class="px-3 py-1 rounded-full bg-[#EAF0F7]/90 backdrop-blur-sm border border-[#C9D4E3] text-[10px] font-bold tracking-widest text-[#52627A] uppercase shadow-sm">
                                {{ \Carbon\Carbon::parse($lap->tanggal_lapor)->format('d M Y') }}
                            </span>
                        </div>

                        {{-- Logika Menampilkan Foto --}}
                        {{-- Pastikan nama kolom database anda sesuai, misal: 'foto', 'gambar', atau 'bukti_foto' --}}
                        @if(!empty($lap->foto_kerusakan)) 
                        <a href="{{ asset('storage/' . $lap->foto_kerusakan) }}" target="_blank">
                            <img src="{{ asset('storage/' . $lap->foto_kerusakan) }}" 
                                 alt="Foto Kerusakan" 
                                 class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        </a>
                        @else
                            {{-- Placeholder jika tidak ada foto --}}
                            <div class="flex items-center justify-center h-full w-full bg-[#F4F6FA] text-[#C9D4E3]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                        
                        {{-- Overlay Gradient di bawah foto agar teks lebih terbaca jika mepet --}}
                        <div class="absolute inset-x-0 bottom-0 h-10 bg-gradient-to-t from-black/10 to-transparent"></div>
                    </div>

                    {{-- BAGIAN KONTEN TEXT --}}
                    <div class="p-5">
                        {{-- Lokasi / Judul --}}
                        <h3 class="flex items-center gap-1 font-bold text-[#1E3A5F] text-lg mb-2 line-clamp-1 group-hover:text-[#152A46] transition-colors">
                            <a href="{{ route('detail_laporan', $lap->laporan_id) }}" class="hover:underline">
                                <i class="bx bx-map text-[#1E3A5F]"></i> {{ $lap->lokasi_fasilitas }}
                            </a>
                        </h3>

                        {{-- Deskripsi --}}
                        <p class="text-sm text-[#16243A] leading-relaxed line-clamp-3 mb-4 opacity-80">
                            {{ $lap->deskripsi_kerusakan }}
                        </p>

                        {{-- Footer Card (Status) --}}
                        <div class="pt-4 border-t border-[#E3E9F2] flex justify-between items-center">
                            <span class="text-[10px] font-semibold text-[#52627A] uppercase tracking-wider">Status:</span>
                            
                            @if($lap->status_laporan == 'Menunggu')
                                <span class="inline-flex items-center rounded-full bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">Menunggu</span>
                            @elseif($lap->status_laporan == 'Diverifikasi' || $lap->status_laporan == 'Diperbaiki')
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">Proses</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Selesai</span>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        {{-- Empty State (Jika data kosong) --}}
        @if ($laporan_all->count() == 0)
            @if(request('search'))
                <div class="flex flex-col items-center justify-center py-20 border-2 border-dashed border-[#C9D4E3] rounded-2xl bg-white/50 mt-4">
                    <div class="p-4 rounded-full bg-[#EAF0F7] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#D48806]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-[#16243A] font-bold text-lg">Tidak ada data</p>
                    <p class="text-[#52627A] text-sm">Tidak ada laporan kerusakan yang anda cari.</p>
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-20 border-2 border-dashed border-[#C9D4E3] rounded-2xl bg-white/50 mt-4">
                    <div class="p-4 rounded-full bg-[#EAF0F7] mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#D48806]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-[#16243A] font-bold text-lg">Belum ada data</p>
                    <p class="text-[#52627A] text-sm">Belum ada laporan kerusakan yang tercatat.</p>
                </div>
            @endif
        @endif

        {{-- Pagination --}}
        <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
            {{ $laporan_all->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection