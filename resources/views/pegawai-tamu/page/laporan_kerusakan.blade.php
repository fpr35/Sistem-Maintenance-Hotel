@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    {{-- CTA BUAT LAPORAN --}}
    <div class="mb-8">
        <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 flex items-center justify-between gap-4 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">
            <div>
                <h1 class="text-xl md:text-3xl font-bold text-[#16243A]">Laporan Kerusakan Fasilitas</h1>
                <p class="text-xs text-[#52627A] mt-1">Laporkan kerusakan fasilitas kamar atau area hotel dengan mudah.</p>
            </div>

            <a href="{{ route('tambah_laporan') }}"
                class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                Buat Laporan Anda
            </a>
        </div>
    </div>

    {{-- HISTORY LAPORAN USER --}}
    <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h2 class="text-xl md:text-2xl font-bold text-[#16243A]">History Laporan Kerusakan Saya</h2>
                <p class="text-xs md:text-sm text-[#52627A] mt-1">Daftar laporan kerusakan yang pernah Anda ajukan.</p>
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
            <form action="{{ route('pencarian_laporan_kerusakan') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari laporan kerusakan..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>

        <div class="overflow-x-auto rounded-xl border border-[#C9D4E3] bg-white">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-[#EAF0F7] text-left text-[#16243A] text-xs uppercase tracking-[0.18em]">
                        <th class="px-4 py-3">Tanggal Lapor</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Foto</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E3E9F2]">
                    @forelse ($laporan_riwayat as $laporan)
                        <tr class="hover:bg-[#F7F9FC]/70">
                            <td class="px-4 py-3 align-top text-[#16243A] whitespace-nowrap">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') }}</td>
                            <td class="px-4 py-3 align-top text-[#16243A]">
                                <a href="{{ route('detail_laporan', $laporan->laporan_id) }}" class="font-semibold text-[#1E3A5F] hover:underline">{{ $laporan->lokasi_fasilitas }}</a>
                            </td>
                            <td class="px-4 py-3 align-top text-[#16243A] text-sm">{{ $laporan->deskripsi_kerusakan }}</td>
                            <td class="px-4 py-3 align-top">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($laporan->status_laporan === 'Menunggu Konfirmasi') bg-[#EAF0F7] text-[#B7791F]
                                    @elseif($laporan->status_laporan === 'Diproses') bg-[#FFF9DB] text-[#D48806]
                                    @elseif($laporan->status_laporan === 'Selesai') bg-[#E6F4EA] text-[#2E7D32]
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $laporan->status_laporan }}
                                </span>
                            </td>
                            @if ($laporan->foto_kerusakan)
                                <td class="px-4 py-3 align-top text-center">
                                    <a href="{{ asset('storage/' . $laporan->foto_kerusakan) }}" target="_blank"
                                        class="inline-block overflow-hidden rounded-xl border border-[#C9D4E3] bg-[#F4F6FA]">
                                        <img src="{{ asset('storage/' . $laporan->foto_kerusakan) }}" alt="Foto Kerusakan"
                                            class="w-20 h-20 object-cover">
                                    </a>
                                </td>
                            @else
                                <td class="px-4 py-3 align-top text-center text-xs italic text-gray-400">[Tidak ada foto]</td>
                            @endif
                            <td class="px-4 py-3 align-top text-center">
                                @if ($laporan->status_laporan == 'Menunggu')
                                <div class="inline-flex items-center gap-3">
                                    <a href="{{ url('edit_laporan', $laporan->laporan_id ) }}"
                                        class="inline-flex items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-3 py-2 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                                        Edit
                                    </a>
                                    <form action="{{ url('hapus_laporan', $laporan->laporan_id ) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus laporan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex cursor-pointer items-center justify-center whitespace-nowrap rounded-full bg-[#992929] px-3 py-2 text-sm font-semibold text-white shadow-md hover:bg-[#751919] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#992929] transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                                @else
                                    {{-- Laporan yang sudah diproses admin tidak bisa diubah --}}
                                    <span class="text-xs italic text-gray-400">Sudah diproses admin</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                    @if(request('search'))
                        <tr>
                            <td colspan="6" class="text-center py-6 text-sm text-[#52627A]">
                                Tidak ada riwayat laporan yang anda cari.
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="6" class="text-center py-6 text-sm text-[#52627A]">
                                Tidak ada riwayat laporan.
                            </td>
                        </tr>
                    @endif
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
            {{ $laporan_riwayat->links('pagination::tailwind') }}
        </div>
    </div>

</div>
@endsection
