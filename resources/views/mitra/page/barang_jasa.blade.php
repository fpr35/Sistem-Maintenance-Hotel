@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    {{-- CTA BARANG & JASA --}}
    <div class="mb-8">
        <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 flex items-center justify-between gap-4 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">
            <div>
                <h1 class="text-xl md:text-3xl font-bold text-[#16243A]">Barang & Jasa Mitra</h1>
                <p class="text-xs text-[#52627A] mt-1">Daftar barang dan jasa yang Anda tawarkan untuk kebutuhan maintenance hotel.</p>
            </div>

            {{-- Tombol ini bisa dihubungkan ke halaman/form tambah barang/jasa jika sudah ada routenya --}}
            <a href="{{ route('tambah_barangjasa') }}"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                Tambah Barang/Jasa
            </a>
        </div>
    </div>

    {{-- DAFTAR BARANG/JASA --}}
    <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-[#16243A]">Daftar Barang & Jasa</h2>
                <p class="text-xs md:text-sm text-[#52627A] mt-1">Ringkasan barang dan jasa yang saat ini tersedia di sistem.</p>
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
        <div class="w-full mb-4">
            <form action="{{ route('pencarian_barangjasa_mitra') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari barang/jasa anda..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-[#C9D4E3] bg-white">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-[#EAF0F7] text-left text-[#16243A] text-xs uppercase tracking-[0.18em]">
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3">Harga</th>
                        <th class="px-4 py-3">Stok</th>
                        <th class="px-4 py-3 text-center">Foto</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3E9F2]">
                    @forelse($barang_jasa as $item)
                        <tr class="hover:bg-[#F7F9FC]/70">
                            <td class="px-4 py-3 align-top text-[#16243A] font-semibold">{{ $item->nama_barang ?? $item->nama_jasa ?? '-' }}</td>
                            <td class="px-4 py-3 align-top text-[#16243A] text-sm max-w-xs">{{ $item->deskripsi ?? '-' }}</td>
                            <td class="px-4 py-3 align-top text-[#16243A]">{{ $item->jenis ?? '-' }}</td>
                            <td class="px-4 py-3 align-top text-[#16243A] whitespace-nowrap">Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 align-top text-[#16243A]">{{ $item->stok ?? '-' }}</td>

                            {{-- Foto produk --}}
                            @if ($item->foto_produk)
                                <td class="px-4 py-3 align-top text-center">
                                    <div class="inline-block overflow-hidden rounded-xl border border-[#C9D4E3] bg-[#F4F6FA]">
                                        <a href="{{ asset('storage/' . $item->foto_produk) }}" target="_blank"><img src="{{ asset('storage/' . $item->foto_produk) }}" alt="{{ $item->nama_barang ?? $item->nama_jasa ?? '-' }}" class="w-20 h-20 object-cover"></a>
                                    </div>
                                </td>
                            @else
                                <td class="px-4 py-3 align-top text-center text-xs italic text-gray-400">[Tidak ada foto]</td>
                            @endif

                            <td class="px-4 py-3 align-top text-[#16243A]">{{ $item->status_ketersediaan ?? '-' }}</td>
                            <td class="px-4 py-3 align-top text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('edit_barangjasa', $item->barang_id) }}" class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-3 py-2 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('hapus_barangjasa', $item->barang_id) }}" method="post" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-full bg-[#992929] px-3 py-2 text-sm font-semibold text-white shadow-md hover:bg-[#751919] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#992929] transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @if(request('search'))
                        <tr>
                            <td colspan="7" class="text-center py-6 text-sm text-[#52627A]">
                                Tidak ada barang/jasa yang anda cari.
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7" class="text-center py-6 text-sm text-[#52627A]">
                                Belum ada barang atau jasa terdaftar.
                            </td>
                        </tr>
                    @endif
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
            {{ $barang_jasa->links('pagination::tailwind') }}
        </div>
    </div>

</div>
@endsection