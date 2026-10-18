@extends('template')

@section('content')
   {{-- FORM LAPORKAN KERUSAKAN --}}
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Laporkan Kerusakan Fasilitas</h1>
            <p class="text-sm text-[#52627A]">Isi formulir berikut untuk melaporkan kerusakan fasilitas kamar atau area hotel.</p>
        </div>

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

            <form action="{{ route('simpan_laporan') }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                @csrf

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-1">
                        <label for="tanggal_lapor" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Tanggal Lapor</label>
                        <input type="date" name="tanggal_lapor" id="tanggal_lapor" required max="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    </div>

                    <div class="md:col-span-1">
                        <label for="lokasi_fasilitas" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Lokasi Fasilitas</label>
                        <input type="text" name="lokasi_fasilitas" id="lokasi_fasilitas" required maxlength="150"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    </div>
                </div>

                <div>
                    <label for="deskripsi_kerusakan" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" rows="4" required
                        class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]"></textarea>
                </div>

                <input type="hidden" name="status_laporan" value="Menunggu" id="status_laporan" maxlength="255">

                <div>
                    <label for="foto_kerusakan" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Upload Foto Kerusakan</label>
                    <input type="file" name="foto_kerusakan" accept="image/*" required
                        class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    <p class="mt-1 text-[11px] text-[#52627A]">Format gambar (JPG, PNG) dengan ukuran wajar agar proses upload lebih cepat.</p>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                        Laporkan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@endsection