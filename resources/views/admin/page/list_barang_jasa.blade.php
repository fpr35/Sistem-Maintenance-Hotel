@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">List Barang / Jasa</h1>
                <p class="text-sm text-[#52627A]">Katalog inventaris yang tersedia untuk dibeli.</p>
            </div>
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

        {{-- Search --}}
        <div class="w-full">
            <form action="{{ route('pencarian_barangjasa') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari barang/jasa..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>

        {{-- Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">

            @forelse ($barang_jasa as $data)
            <div class="group bg-white rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden hover:shadow-[0_12px_24px_rgba(22,36,58,0.3)] hover:border-[#D69E2E] transition-all duration-300 flex flex-col">
                
                {{-- Image Area --}}
                <div class="relative h-48 w-full overflow-hidden bg-gray-100 border-b border-[#C9D4E3]/30">
                    <div class="absolute top-3 right-3 z-10">
                        <span class="px-3 py-1 rounded-full bg-[#EAF0F7]/90 backdrop-blur-sm border border-[#C9D4E3] text-[10px] font-bold tracking-widest text-[#52627A] uppercase shadow-sm">
                            {{ $data->jenis }}
                        </span>
                    </div>

                    @if ($data->foto_produk)
                    <a href="{{ asset('storage/' . $data->foto_produk) }}" target="_blank">
                        <img src="{{ asset('storage/' . $data->foto_produk) }}" alt="{{ $data->nama_barang }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    </a>
                    @else
                        <div class="flex items-center justify-center h-full w-full bg-[#F4F6FA] text-[#C9D4E3]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                    @endif
                </div>

                {{-- Content Area --}}
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="text-lg font-bold text-[#16243A] mb-1 group-hover:text-[#D69E2E] transition-colors">{{ $data->nama_barang }}</h3>
                    <p class="text-xs text-[#52627A] mb-3">Mitra: <span class="font-semibold">{{ $data->mitra->nama_mitra }}</span></p>
                    
                    <p class="text-sm text-[#16243A]/80 leading-relaxed mb-4 line-clamp-2 flex-1">
                        {{ $data->deskripsi }}
                    </p>

                    <div class="flex justify-between items-center mb-4 text-sm">
                        <span class="text-[#52627A]">Stok: <strong class="text-[#16243A]">{{ $data->stok }}</strong></span>
                        <span class="font-bold text-[#1E3A5F] text-lg">Rp {{ number_format($data->harga_satuan, 0, ',', '.') }}</span>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 border-t border-[#E3E9F2] space-y-3">
                        
                        {{-- Form Beli --}}
                        @if ($data->stok > 0)
                        <form action="{{ url('beli_barangjasa') }}" method="POST" class="flex flex-wrap gap-2">
                            @csrf

                            {{-- Kaitkan pembelian dengan laporan kerusakan yang sedang ditangani (opsional) --}}
                            <select name="laporan_id" class="w-full border border-[#C9D4E3] rounded-lg px-3 py-2 text-xs bg-[#F8FAFC] text-[#16243A]">
                                <option value="">-- Untuk laporan (opsional) --</option>
                                @foreach ($laporan_aktif as $lap)
                                    <option value="{{ $lap->laporan_id }}">#{{ $lap->laporan_id }} &middot; {{ $lap->lokasi_fasilitas }} ({{ \Carbon\Carbon::parse($lap->tanggal_lapor)->format('d M Y') }})</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="mitra_id" value="{{ $data->mitra->mitra_id }}">
                            <input type="hidden" name="nama_barang" value="{{ $data->nama_barang }}">
                            <input type="hidden" name="jenis" value="{{ $data->jenis }}">
                            <input type="hidden" name="barang_id" value="{{ $data->barang_id }}">

                            <input type="number" name="jumlah_beli" min="1" max="{{ $data->stok }}"
                                class="flex-1 border border-[#C9D4E3] rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#1E3A5F] bg-[#F8FAFC]" 
                                placeholder="Jml">

                            <button class="cursor-pointer px-4 py-2 bg-[#1E3A5F] text-white rounded-lg text-sm font-bold hover:bg-[#152A46] transition-colors shadow-sm" onclick="return confirm('Yakin ingin membeli item ini?')">
                                Beli
                            </button>
                        </form>
                        @else
                            <div class="w-full py-2 bg-red-50 text-red-600 text-center text-sm font-bold rounded-lg border border-red-100">
                                Stok Habis
                            </div>
                        @endif

                        {{-- Form Hapus --}}
                        <form action="{{ url('hapus_barangjasa_admin', $data->barang_id ) }}" method="POST" class="block text-right">
                            @csrf
                            @method('DELETE')
                            <button class="cursor-pointer text-[10px] text-red-500 hover:text-red-700 font-bold uppercase tracking-wider hover:underline" onclick="return confirm('Hapus item ini?')">
                                Hapus Item
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            @if(request('search'))
            <div class="col-span-full p-12 text-center border-2 border-dashed border-[#C9D4E3] rounded-2xl">
                <p class="text-[#52627A] font-medium">Tidak ada barang/jasa yang anda cari.</p>
            </div>
            @else
            <div class="col-span-full p-12 text-center border-2 border-dashed border-[#C9D4E3] rounded-2xl">
                <p class="text-[#52627A] font-medium">Tidak ada barang/jasa tersedia saat ini.</p>
            </div>
            @endif
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
            {{ $barang_jasa->links('pagination::tailwind') }}
        </div>
    </div>
</div>
@endsection