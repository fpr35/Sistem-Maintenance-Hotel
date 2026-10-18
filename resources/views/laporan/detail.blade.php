@extends('template')

@section('content')
@php
    // Halaman asal sesuai role, untuk tombol "Kembali"
    $kembali = match ($user->role) {
        'Admin' => route('laporan_kerusakan_admin'),
        'Mitra' => route('laporan_kerusakan_mitra'),
        default => route('laporan_kerusakan'),
    };

    $warnaStatus = match ($laporan->status_laporan) {
        'Diverifikasi' => 'bg-blue-50 border-blue-200 text-blue-700',
        'Diperbaiki' => 'bg-orange-50 border-orange-200 text-orange-700',
        'Selesai' => 'bg-green-50 border-green-200 text-green-700',
        default => 'bg-gray-50 border-gray-200 text-gray-600',
    };
@endphp

<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Detail Laporan Kerusakan</h1>
                <p class="text-sm text-[#52627A]">Laporan #{{ $laporan->laporan_id }}</p>
            </div>
            <a href="{{ $kembali }}" class="inline-flex items-center gap-1 rounded-full border border-[#C9D4E3] bg-white px-4 py-2 text-sm font-semibold text-[#52627A] hover:text-[#1E3A5F] hover:border-[#1E3A5F] transition">
                <i class='bx bx-arrow-back'></i> Kembali
            </a>
        </div>

        @include('snippet.alert')

        <div class="bg-white border border-[#C9D4E3] rounded-2xl overflow-hidden shadow-[0_8px_20px_rgba(22,36,58,0.2)]">
            <div class="grid md:grid-cols-2">

                {{-- FOTO --}}
                <a href="{{ asset('storage/' . $laporan->foto_kerusakan) }}" target="_blank" class="block bg-gray-100">
                    <img src="{{ asset('storage/' . $laporan->foto_kerusakan) }}" alt="Foto kerusakan" class="w-full h-72 md:h-full object-cover">
                </a>

                {{-- INFORMASI --}}
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#52627A]">Lokasi / Fasilitas</p>
                        <p class="text-xl font-bold text-[#1E3A5F]">{{ $laporan->lokasi_fasilitas }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#52627A]">Tanggal Lapor</p>
                            <p class="text-sm text-[#16243A]">{{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#52627A]">Status</p>
                            <span class="inline-block mt-0.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold {{ $warnaStatus }}">{{ $laporan->status_laporan }}</span>
                        </div>
                    </div>

                    @if ($user->role === 'Admin')
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-[#52627A]">Pelapor</p>
                            <p class="text-sm text-[#16243A]">
                                {{ $laporan->pelapor->nama ?? 'User tidak ditemukan' }}
                                @if ($laporan->pelapor)
                                    <span class="text-xs text-[#52627A]">({{ $laporan->pelapor->role }} &middot; {{ $laporan->pelapor->kontak ?: '-' }})</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-[#52627A]">Deskripsi Kerusakan</p>
                        <p class="text-sm text-[#16243A] whitespace-pre-line leading-relaxed">{{ $laporan->deskripsi_kerusakan }}</p>
                    </div>

                    @if ($user->role === 'Admin')
                        {{-- Ubah status langsung dari halaman detail --}}
                        <form action="{{ route('ubah_status_laporan', $laporan->laporan_id) }}" method="POST" class="pt-4 border-t border-[#E3E9F2] flex gap-2">
                            @csrf
                            @method('PUT')
                            <select name="status_laporan" class="flex-1 rounded-lg border border-[#C9D4E3] bg-[#F4F6FA] px-3 py-2 text-sm">
                                @foreach (['Menunggu', 'Diverifikasi', 'Diperbaiki', 'Selesai'] as $status)
                                    <option value="{{ $status }}" {{ $laporan->status_laporan == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="cursor-pointer rounded-lg bg-[#1E3A5F] px-4 py-2 text-sm font-bold text-white hover:bg-[#152A46] transition">Ubah Status</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        {{-- PEMBELIAN TERKAIT (khusus admin) --}}
        @if ($user->role === 'Admin')
            <div class="bg-white border border-[#C9D4E3] rounded-2xl p-6 shadow-[0_8px_20px_rgba(22,36,58,0.2)]">
                <h2 class="text-lg font-bold text-[#1E3A5F] mb-4 flex items-center gap-2"><i class='bx bx-cart'></i> Pembelian untuk Laporan Ini</h2>

                @if ($laporan->pembelian->isEmpty())
                    <p class="text-sm italic text-[#52627A]">Belum ada pembelian barang/jasa untuk laporan ini.
                        <a href="{{ route('list_barang_jasa') }}" class="font-semibold text-[#1E3A5F] hover:underline not-italic">Beli sekarang</a>
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="text-[10px] uppercase tracking-wider text-[#52627A] border-b border-[#E3E9F2]">
                                    <th class="py-2 pr-4">Tanggal</th>
                                    <th class="py-2 pr-4">Barang/Jasa</th>
                                    <th class="py-2 pr-4">Mitra</th>
                                    <th class="py-2 pr-4">Jumlah</th>
                                    <th class="py-2 pr-4">Total</th>
                                    <th class="py-2 pr-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E3E9F2]">
                                @foreach ($laporan->pembelian as $pembelian)
                                    <tr>
                                        <td class="py-2 pr-4">{{ $pembelian->created_at?->format('d M Y') }}</td>
                                        <td class="py-2 pr-4 font-semibold">{{ $pembelian->nama_barang }}</td>
                                        <td class="py-2 pr-4">{{ $pembelian->mitra_bisnis->nama_mitra ?? '-' }}</td>
                                        <td class="py-2 pr-4">{{ $pembelian->jumlah_beli }}</td>
                                        <td class="py-2 pr-4">Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                                        <td class="py-2 pr-4">{{ $pembelian->status_pembelian }} &middot; {{ $pembelian->bukti_bayar ? 'Sudah Bayar' : 'Belum Bayar' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection
