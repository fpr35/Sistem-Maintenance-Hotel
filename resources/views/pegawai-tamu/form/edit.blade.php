@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">

    {{-- FORM LAPORKAN KERUSAKAN --}}
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Edit Laporan Kerusakan</h1>
            <p class="text-sm text-[#52627A]">Perbarui informasi laporan kerusakan fasilitas Anda.</p>
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

            <form action="{{ url('update_laporan', $laporan->laporan_id) }}" method="POST" class="space-y-5" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-1">
                        <label for="tanggal_lapor" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Tanggal Lapor</label>
                        <input type="date" name="tanggal_lapor" id="tanggal_lapor" required max="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]"
                            value="{{ $laporan->tanggal_lapor }}">
                    </div>

                    <div class="md:col-span-1">
                        <label for="lokasi_fasilitas" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Lokasi Fasilitas</label>
                        <input type="text" name="lokasi_fasilitas" id="lokasi_fasilitas" required maxlength="150"
                            class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]"
                            value="{{ $laporan->lokasi_fasilitas }}">
                    </div>
                </div>

                <div>
                    <label for="deskripsi_kerusakan" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Deskripsi Kerusakan</label>
                    <textarea name="deskripsi_kerusakan" id="deskripsi_kerusakan" rows="4" required
                        class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">{{ $laporan->deskripsi_kerusakan }}</textarea>
                </div>

                <input type="hidden" name="status_laporan" id="status_laporan" value="{{ $laporan->status_laporan }}" maxlength="255">

                <div class="space-y-2">
                    <label for="foto_kerusakan" class="block text-xs font-semibold tracking-[0.16em] text-[#52627A] uppercase mb-1">Ubah Foto Kerusakan</label>
                    <input type="file" name="foto_kerusakan" accept="image/*"
                        class="w-full rounded-xl border border-[#C9D4E3] bg-white px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E]">
                    @if ($laporan->foto_kerusakan)
                        <p class="text-xs text-[#52627A] mt-1">Foto saat ini:</p>
                        <div class="mt-1 inline-block overflow-hidden rounded-xl border border-[#C9D4E3] bg-[#F4F6FA]">
                            <img src="{{ asset('storage/' . $laporan->foto_kerusakan) }}" alt="Foto Kerusakan" class="w-24 h-24 object-cover">
                        </div>
                    @endif
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center whitespace-nowrap rounded-full bg-[#1E3A5F] px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-[#152A46] focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-[#1E3A5F] transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection