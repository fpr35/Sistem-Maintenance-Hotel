@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-7xl mx-auto">
        {{-- HEADER PAGE --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Daftar Orderan</h1>
            <p class="text-sm text-[#52627A]">Kelola status pesanan masuk, pembayaran, dan cetak resi pengiriman.</p>
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
        <div class="w-full mb-4">
            <form action="{{ route('pencarian_orderan') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari orderan..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white border border-[#C9D4E3] rounded-2xl shadow-[0_8px_20px_rgba(22,36,58,0.35)] overflow-hidden ">
            
            <div class="overflow-x-auto ">
                <table class="w-full text-left border-collapse">
                    
                    {{-- TABLE HEADER --}}
                    <thead>
                        <tr class="bg-[#EAF0F7] border-b border-[#C9D4E3]">
                            <th class="px-6 py-4 text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Nama Barang</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Jenis</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Total Harga</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Status Pembelian</th>
                            <th class="px-6 py-4 text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Status Bayar</th>
                            <th class="px-6 py-4 text-center text-xs font-bold tracking-[0.16em] text-[#52627A] uppercase whitespace-nowrap">Bukti</th>
                        </tr>
                    </thead>

                    {{-- TABLE BODY --}}
                    <tbody class="divide-y divide-[#E3E9F2]">
                        @forelse ($pembelian_all as $pembelian)
                            <tr class="hover:bg-[#F7F9FC] transition-colors">
                                
                                {{-- Tanggal --}}
                                <td class="px-6 py-4 text-sm text-[#16243A] whitespace-nowrap">
                                    {{ $pembelian->created_at->format('d M Y') }}
                                    <span class="block text-xs text-[#52627A]">{{ $pembelian->created_at->format('H:i') }}</span>
                                </td>

                                {{-- Nama Barang --}}
                                <td class="px-6 py-4">
                                    <span class="font-bold text-[#1E3A5F] text-sm block min-w-[150px]">
                                        {{ $pembelian->nama_barang }}
                                    </span>
                                </td>

                                {{-- Jenis --}}
                                <td class="px-6 py-4 text-sm text-[#16243A]">
                                    {{ $pembelian->jenis }}
                                </td>

                                {{-- Total Harga --}}
                                <td class="px-6 py-4 text-sm font-semibold text-[#16243A]">
                                    Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                                </td>

                                {{-- Status Pembelian (Dropdown) --}}
                                <td class="px-6 py-4">
                                    <form action="{{ url('ubah_status_pembelian', $pembelian->pembelian_id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="relative">
                                            <select name="status_pembelian"
                                                class="w-full min-w-[130px] appearance-none rounded-lg border border-[#C9D4E3] bg-white px-3 py-1.5 text-xs font-medium text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] cursor-pointer hover:border-[#D69E2E] transition-colors"
                                                onchange="if(!confirm('Ubah status menjadi ' + this.options[this.selectedIndex].text + '?')) { this.selectedIndex = 0; return false; } this.form.submit();">
                                                
                                                <option value="Menunggu" {{ $pembelian->status_pembelian == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="Diproses" {{ $pembelian->status_pembelian == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="Selesai" {{ $pembelian->status_pembelian == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                            </select>
                                            
                                            {{-- Custom Arrow Icon for Select --}}
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-[#52627A]">
                                                <svg class="h-3 w-3 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                            </div>
                                        </div>
                                    </form>
                                </td>

                                {{-- Status Bayar --}}
                                <td class="px-6 py-4">
                                    @if($pembelian->status_bayar == 'Sudah Bayar')
                                        <span class="inline-flex items-center rounded-full bg-[#E6F4EA] px-2.5 py-0.5 text-xs font-medium text-[#2E7D32]">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-red-200 px-2.5 py-0.5 text-xs font-medium text-red-500">
                                            {{ $pembelian->status_bayar }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Bukti Bayar --}}
                                <td class="px-6 py-4 text-center">
                                    @if($pembelian->bukti_bayar)
                                        <a href="{{ asset('storage/' . $pembelian->bukti_bayar) }}"
                                           target="_blank"
                                           class="inline-block text-[#1E3A5F] hover:text-[#152A46] font-semibold text-xs border border-[#1E3A5F] px-3 py-1 rounded-full hover:bg-[#1E3A5F] hover:text-white transition-all">
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-xs text-[#C9D4E3] italic">Tidak ada</span>
                                    @endif
                                </td>

                            </tr>

                        @empty
                        @if(request('search'))
                         <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="p-3 rounded-full bg-[#EAF0F7]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#C9D4E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-[#52627A] text-sm font-medium">Tidak ada data orderan yang anda cari.</p>
                                    </div>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-2">
                                        <div class="p-3 rounded-full bg-[#EAF0F7]">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#C9D4E3]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-[#52627A] text-sm font-medium">Belum ada data orderan terbaru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
            {{ $pembelian_all->links('pagination::tailwind') }}
        </div>
    </div>

</div>
@endsection