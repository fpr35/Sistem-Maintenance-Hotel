@extends('template')

@section('content')

<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA]">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Header --}}
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-1">Verifikasi Mitra</h1>
            <p class="text-sm text-[#52627A]">Daftar pendaftaran mitra baru yang menunggu persetujuan.</p>
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
            <form action="{{ route('pencarian_mitra_belum_verifikasi') }}" method="GET" class="flex items-center">
                <input type="text" name="search" id="search" placeholder="Cari akun mitra..." class="w-full rounded-lg border border-[#C9D4E3] px-3 py-2 placeholder:text-[#52627A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] transition">
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
                        <tr class="bg-[#EAF0F7] border-b border-[#C9D4E3] ">
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Nama Mitra</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase">Email</th>
                            <th class="px-6 py-4 text-[10px] font-bold tracking-[0.2em] text-[#52627A] uppercase text-left lg:text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-[#C9D4E3]/30">
                        @forelse ($mitra_belum_verifikasi_all as $mitra)
                        <tr class="hover:bg-[#F7F9FC] transition-colors ">
                            <td class="px-6 py-4 text-sm font-bold text-[#16243A]">
                                {{ $mitra->nama }}
                            </td>
                            <td class="px-6 py-4 text-sm text-[#16243A]/80">
                                {{ $mitra->email }}
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex gap-3 justify-center">
                                    
                                    {{-- Tombol Verifikasi --}}
                                    <form action="{{ url('verifikasi_akun', $mitra->id) }}" method="POST">
                                        @csrf
                                        {{-- Data mitra diambil langsung dari database di controller --}}

                                        <button class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-[#1E3A5F] text-white text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-[#152A46] transition-colors shadow-sm"
                                            onclick="return confirm('Apakah anda yakin ingin memverifikasi akun ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            Verifikasi
                                        </button>
                                    </form>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ url('hapus_unverified_mitra', $mitra->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="cursor-pointer inline-flex items-center px-3 py-1.5 bg-white border border-red-200 text-red-600 text-xs font-bold uppercase tracking-wider rounded-lg hover:bg-red-50 hover:border-red-400 transition-colors"
                                            onclick="return confirm('Tolak dan hapus data mitra ini?')">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            Tolak
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @empty
                        @if(request('search'))
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-[#52627A] italic">
                                Tidak ada mitra yang anda cari.
                            </td>
                        </tr>
                        @else
                        <tr>
                            <td colspan="3" class="px-6 py-8 text-center text-[#52627A] italic">
                                Tidak ada mitra baru yang perlu diverifikasi.
                            </td>
                        </tr>
                        @endif
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-[#C9D4E3]/30 bg-[#EAF0F7]">
                {{ $mitra_belum_verifikasi_all->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>

@endsection