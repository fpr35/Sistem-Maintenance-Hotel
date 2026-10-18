@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-2xl mx-auto">
        {{-- HEADER --}}
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Tambah Barang/Jasa</h1>
            <p class="text-sm text-[#52627A]">Isi formulir untuk menambahkan inventaris barang atau layanan jasa baru.</p>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 md:p-8 shadow-[0_8px_20px_rgba(22,36,58,0.35)]">

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

            <form action="{{ route('simpan_barangjasa') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                @csrf

                {{-- NAMA BARANG & JENIS (2 Column) --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-1">
                        <label for="nama_barang" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang" required maxlength="150"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    </div>

                    <div class="md:col-span-1">
                        <label for="jenis" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Jenis</label>
                        <select name="jenis" id="jenis" required 
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                            <option value="" disabled selected>-- Pilih Jenis --</option>
                            <option value="Barang">Barang</option>
                            <option value="Jasa">Jasa</option>
                        </select>
                    </div>
                </div>

                {{-- STOK & HARGA (2 Column) --}}
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-1">
                        <label for="stok" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Stok</label>
                        <input type="number" name="stok" id="stok" required min="0"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    </div>

                    <div class="md:col-span-1">
                        <label for="harga_satuan" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Harga Satuan</label>
                        <input type="number" name="harga_satuan" id="harga_satuan" required min="0"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    </div>
                </div>

                {{-- DESKRIPSI --}}
                <div>
                    <label for="deskripsi" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" required
                        class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]"></textarea>
                </div>

                {{-- UPLOAD FOTO --}}
                <div>
                    <label for="foto_produk" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Upload Foto Produk</label>
                    <input type="file" name="foto_produk" accept="image/*" required
                        class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    <p class="mt-1 text-[11px] text-[#52627A]">Format gambar (JPG, PNG) disarankan rasio 1:1.</p>
                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="pt-4">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                        Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection