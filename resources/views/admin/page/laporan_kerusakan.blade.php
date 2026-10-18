@extends('template')

@section('content')

<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-7xl mx-auto space-y-6">
        
        {{-- Header --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Data Kerusakan</h1>
            <p class="text-sm text-[#52627A]">Kelola laporan dan update status perbaikan.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p class="text-sm font-bold">Sukses</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @elseif(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="text-sm font-bold">Error</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p class="text-sm font-bold">Error</p>
                <ul class="text-sm list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Cetak rekap ke PDF berdasarkan rentang tanggal --}}
        <form action="{{ route('rekap_laporan') }}" method="GET" target="_blank" class="flex flex-wrap items-center gap-2 rounded-xl border border-[#C9D4E3] bg-white px-4 py-3">
            <span class="text-xs font-bold uppercase tracking-wider text-[#52627A]">Cetak rekap</span>
            <label for="rekap_laporan_dari" class="text-xs text-[#52627A]">Dari</label>
            <input type="date" name="dari" id="rekap_laporan_dari" required value="{{ old('dari', now()->startOfMonth()->format('Y-m-d')) }}"
                   onchange="this.form.sampai.min = this.value"
                   class="rounded-lg border border-[#C9D4E3] px-3 py-1.5 text-sm">
            <label for="rekap_laporan_sampai" class="text-xs text-[#52627A]">Sampai</label>
            <input type="date" name="sampai" id="rekap_laporan_sampai" required value="{{ old('sampai', now()->format('Y-m-d')) }}"
                   min="{{ old('dari', now()->startOfMonth()->format('Y-m-d')) }}"
                   class="rounded-lg border border-[#C9D4E3] px-3 py-1.5 text-sm">
            <button type="submit" class="ml-auto cursor-pointer inline-flex items-center gap-1 rounded-lg bg-[#1E3A5F] px-4 py-1.5 text-sm font-bold text-white hover:bg-[#152A46] transition">
                <i class='bx bxs-file-pdf'></i> Cetak PDF
            </button>
        </form>

        {{-- Search --}}
        <div class="w-full">
            <form action="{{ route('pencarian_laporan') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari lokasi, deskripsi, status, atau nama pelapor..." value="{{ request('search') }}" class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse ($laporan_all as $laporan)
            <div class="group bg-white rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden hover:shadow-[0_12px_24px_rgba(22,36,58,0.3)] hover:border-[#D69E2E] transition-all duration-300">
                
                {{-- Foto --}}
                <div class="relative h-48 w-full overflow-hidden bg-gray-100 group">
                    <a href="{{ asset('storage/' . $laporan->foto_kerusakan) }}" target="_blank">
                        <img src="{{ asset('storage/' . $laporan->foto_kerusakan) }}" alt="Bukti Foto" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                        
                        {{-- Overlay Click Hint --}}
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <span class="bg-white/90 px-3 py-1 rounded-full text-xs font-bold text-[#16243A]">Lihat Full</span>
                        </div>
                    </a>
                </div>

                {{-- Konten --}}
                <div class="p-5">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-bold text-[#1E3A5F] line-clamp-1 pr-2">
                            {{ $laporan->lokasi_fasilitas }}
                        </h3>
                        <span class="text-[10px] font-bold text-[#52627A] whitespace-nowrap bg-[#EAF0F7] px-2 py-1 rounded border border-[#C9D4E3]">
                            {{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Pelapor --}}
                    <p class="text-xs text-[#52627A] mb-2 flex items-center gap-1">
                        <i class='bx bx-user'></i>
                        {{ $laporan->pelapor->nama ?? 'User tidak ditemukan' }}
                        @if ($laporan->pelapor)
                            <span class="opacity-70">({{ $laporan->pelapor->role }})</span>
                        @endif
                    </p>

                    <p class="text-sm text-[#16243A]/80 mb-3 line-clamp-3 min-h-[40px]">
                        {{ $laporan->deskripsi_kerusakan }}
                    </p>

                    <div class="flex items-center justify-between mb-4">
                        <a href="{{ route('detail_laporan', $laporan->laporan_id) }}" class="text-xs font-bold text-[#1E3A5F] hover:underline">Lihat Detail &rarr;</a>
                        @if ($laporan->pembelian_count > 0)
                            <span class="text-[10px] font-bold text-[#52627A] bg-[#EAF0F7] border border-[#C9D4E3] rounded px-2 py-0.5">{{ $laporan->pembelian_count }} pembelian</span>
                        @endif
                    </div>

                    {{-- Form Status --}}
                    <div class="pt-4 border-t border-[#E3E9F2]">
                        <form action="{{ url('ubah_status_laporan', $laporan->laporan_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <label class="block mb-2 text-[10px] font-bold uppercase tracking-wider text-[#52627A]">Update Status:</label>
                            
                            <div class="relative">
                                <select name="status_laporan"
                                        class="w-full appearance-none border border-[#C9D4E3] rounded-lg px-3 py-2 text-sm bg-[#F4F6FA] text-[#16243A] focus:outline-none focus:ring-1 focus:ring-[#1E3A5F] font-medium cursor-pointer"
                                        onchange="if(confirm('Ubah status menjadi ' + this.options[this.selectedIndex].text + '?')) { this.form.submit(); } else { this.value = '{{ $laporan->status_laporan }}'; }">

                                    <option value="Menunggu" {{ $laporan->status_laporan == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="Diverifikasi" {{ $laporan->status_laporan == 'Diverifikasi' ? 'selected' : '' }}>Diverifikasi</option>
                                    <option value="Diperbaiki" {{ $laporan->status_laporan == 'Diperbaiki' ? 'selected' : '' }}>Diperbaiki</option>
                                    <option value="Selesai" {{ $laporan->status_laporan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                
                                {{-- Custom Chevron Icon --}}
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#52627A]">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            {{-- Visual Status Indicator (Small dot) --}}
                            <div class="mt-2 flex items-center gap-2">
                                @php
                                    $dotColor = match($laporan->status_laporan) {
                                        'Menunggu' => 'bg-gray-400',
                                        'Diverifikasi' => 'bg-blue-500',
                                        'Diperbaiki' => 'bg-orange-500',
                                        'Selesai' => 'bg-green-600',
                                        default => 'bg-gray-400'
                                    };
                                @endphp
                                <span class="h-2 w-2 rounded-full {{ $dotColor }}"></span>
                                <span class="text-xs text-gray-500 italic">Status saat ini: {{ $laporan->status_laporan }}</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            @if(request('search'))
            <div class="col-span-full p-12 text-center border-2 border-dashed border-[#C9D4E3] rounded-2xl">
                <p class="text-[#52627A] font-medium">Tidak ada laporan kerusakan yang anda cari.</p>
            </div>
            @else
            <div class="col-span-full py-12 text-center">
                <div class="inline-block p-4 rounded-full bg-[#EAF0F7] mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#C9D4E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-[#16243A] font-bold">Tidak ada laporan kerusakan</p>
            </div>
            @endif
            @endforelse
        </div>
        
        {{-- Pagination --}}
        <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
            {{ $laporan_all->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection