@extends('template')
@section('content')

<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Riwayat Pembelian</h1>
            <p class="text-sm text-[#52627A]">Daftar transaksi pembelian barang dan jasa.</p>
        </div>

        @include('snippet.alert')


        {{-- Cetak rekap ke PDF berdasarkan rentang tanggal --}}
        <form action="{{ route('rekap_pembelian') }}" method="GET" target="_blank" class="flex flex-wrap items-center gap-2 rounded-xl border border-[#C9D4E3] bg-white px-4 py-3">
            <span class="text-xs font-bold uppercase tracking-wider text-[#52627A]">Cetak rekap</span>
            <label for="rekap_pembelian_dari" class="text-xs text-[#52627A]">Dari</label>
            <input type="date" name="dari" id="rekap_pembelian_dari" required value="{{ old('dari', now()->startOfMonth()->format('Y-m-d')) }}"
                   onchange="this.form.sampai.min = this.value"
                   class="rounded-lg border border-[#C9D4E3] px-3 py-1.5 text-sm">
            <label for="rekap_pembelian_sampai" class="text-xs text-[#52627A]">Sampai</label>
            <input type="date" name="sampai" id="rekap_pembelian_sampai" required value="{{ old('sampai', now()->format('Y-m-d')) }}"
                   min="{{ old('dari', now()->startOfMonth()->format('Y-m-d')) }}"
                   class="rounded-lg border border-[#C9D4E3] px-3 py-1.5 text-sm">
            <button type="submit" class="ml-auto cursor-pointer inline-flex items-center gap-1 rounded-lg bg-[#1E3A5F] px-4 py-1.5 text-sm font-bold text-white hover:bg-[#152A46] transition">
                <i class='bx bxs-file-pdf'></i> Cetak PDF
            </button>
        </form>

        {{-- Search --}}
        <div class="w-full">
            <form action="{{ route('pencarian_riwayat_pembelian') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari riwayat pembelian..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
                <button type="submit" class="ml-2 cursor-pointer p-2 rounded-lg hover:bg-[#1E3A5F] text-[#1E3A5F] hover:text-[#FFF] transition">
                    <i class="bx bx-search text-2xl "></i>
                </button>
            </form>
        </div>
        
        {{-- Table Container --}}
        <div class="bg-white border border-[#C9D4E3] rounded-2xl shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#EAF0F7] border-b border-[#C9D4E3]">
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Tanggal</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Total Harga</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Status</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Nama Barang</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Jenis</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Untuk Laporan</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Pembayaran</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#C9D4E3]/30">
                        @forelse ($pembelian_all as $pembelian)
                        <tr class="hover:bg-[#F7F9FC] transition-colors">
                            <td class="px-6 py-4 text-sm text-[#16243A] whitespace-nowrap">
                                {{ $pembelian->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-[#1E3A5F] whitespace-nowrap">
                                Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($pembelian->status_pembelian)
                                    @case('Diproses')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E3F2FD] border border-[#90CAF9] text-[#1565C0]">
                                            {{ $pembelian->status_pembelian }}
                                        </span>
                                        @break
                                    @case('Menunggu')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#FFF3CD] border border-[#FFE082] text-[#B8860B]">
                                            {{ $pembelian->status_pembelian }}
                                        </span>
                                        @break
                                    @case('Selesai')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#E3F2FD] border border-[#90CAF9] text-green-600">
                                            {{ $pembelian->status_pembelian }}
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#FFF3CD] border border-[#FFE082] text-[#B8860B]">
                                            {{ $pembelian->status_pembelian }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-[#16243A]">
                                {{ $pembelian->nama_barang }}
                            </td>
                            <td class="px-6 py-4 text-xs text-[#52627A] uppercase tracking-wide">
                                {{ $pembelian->jenis }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if ($pembelian->laporan)
                                    <a href="{{ route('detail_laporan', $pembelian->laporan_id) }}" class="font-semibold text-[#1E3A5F] hover:underline">{{ $pembelian->laporan->lokasi_fasilitas }}</a>
                                @else
                                    <span class="text-xs italic text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($pembelian->bukti_bayar)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 border border-green-200 text-green-700">Sudah Bayar</span>
                                @else
                                    {{-- Pembelian yang belum dibayar bisa dilanjutkan atau dibatalkan --}}
                                    <div class="flex flex-col items-start gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-50 border border-red-200 text-red-600">Belum Bayar</span>
                                        <div class="flex items-center gap-1.5">
                                            <a href="{{ route('isi_data_resi', $pembelian->pembelian_id) }}"
                                               class="inline-flex items-center gap-1 rounded-md border border-[#1E3A5F] px-2 py-1 text-[11px] font-bold text-[#1E3A5F] hover:bg-[#1E3A5F] hover:text-white transition">
                                                <i class='bx bx-credit-card'></i> Lanjutkan
                                            </a>
                                            <form action="{{ route('batal_pembelian', $pembelian->pembelian_id) }}" method="POST" onsubmit="return confirm('Batalkan pembelian ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="cursor-pointer inline-flex items-center gap-1 rounded-md border border-red-300 px-2 py-1 text-[11px] font-bold text-red-600 hover:bg-red-50 transition">
                                                    <i class='bx bx-x'></i> Batalkan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        @if(request('search'))
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#52627A] italic">
                                Tidak ada data pembelian yang anda cari.
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-[#52627A] italic">
                                Tidak ada data pembelian.
                            </td>
                        </tr>
                        @endif
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
                {{ $pembelian_all->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection