@extends('template')

@section('content')
<div class="flex-1 min-h-screen px-4 py-8 bg-[#F4F6FA] flex items-center justify-center">

    <div class="w-full max-w-2xl">
        
        {{-- Header Section --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-2">Konfirmasi Pembayaran</h1>
            <p class="text-sm text-[#52627A]">Lengkapi data pengiriman dan unggah bukti transfer.</p>
        </div>

        {{-- Form Container --}}
        <div class="bg-white rounded-2xl border border-[#C9D4E3] shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden p-6 md:p-8">
            
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
            
            <form action="{{ route('simpan_resi') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="mitra_id" value="{{ $pembelian->mitra_id }}">
                <input type="hidden" name="pembelian_id" value="{{ $pembelian->pembelian_id }}">
                
                {{-- Input: Atas Nama --}}
                <div>
                    <label for="nama" class="block text-[10px] font-bold text-[#52627A] uppercase tracking-wider mb-2">Atas Nama Penerima</label>
                    <input type="text" name="nama" id="nama" required maxlength="150"
                           class="w-full border border-[#C9D4E3] rounded-lg px-4 py-3 text-sm text-[#16243A] bg-[#F8FAFC] focus:outline-none focus:ring-1 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition-all"
                           placeholder="Masukkan nama lengkap">
                </div>

                {{-- Input: WhatsApp --}}
                <div>
                    <label for="kontak" class="block text-[10px] font-bold text-[#52627A] uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                    <input type="number" name="kontak" id="kontak" required maxlength="150"
                           class="w-full border border-[#C9D4E3] rounded-lg px-4 py-3 text-sm text-[#16243A] bg-[#F8FAFC] focus:outline-none focus:ring-1 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition-all"
                           placeholder="08xxxxxxxxxx">
                </div>

                {{-- Input: Alamat --}}
                <div>
                    <label for="alamat" class="block text-[10px] font-bold text-[#52627A] uppercase tracking-wider mb-2">Alamat Pengiriman</label>
                    <textarea name="alamat" id="alamat" required maxlength="255" rows="3"
                           class="w-full border border-[#C9D4E3] rounded-lg px-4 py-3 text-sm text-[#16243A] bg-[#F8FAFC] focus:outline-none focus:ring-1 focus:ring-[#1E3A5F] focus:border-[#1E3A5F] transition-all"
                           placeholder="Jalan, RT/RW, Kelurahan, Kecamatan..."></textarea>
                </div>
                
                {{-- Informasi Pembayaran (Info Box) --}}
                <div class="bg-[#EAF0F7] border border-[#C9D4E3] rounded-xl p-4 flex gap-4 items-start">
                    <div class="p-2 bg-white rounded-full border border-[#C9D4E3]/50 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#1E3A5F]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="text-sm text-[#16243A]/80 leading-relaxed">
                        <p class="mb-1">Silakan transfer sebesar <strong class="text-[#1E3A5F] text-lg">Rp. {{ number_format($pembelian->total_harga, 0, ',', '.') }}</strong></p>
                        <p>Ke Nomor Rekening: <b>{{ $pembelian->mitra_bisnis->no_rekening }} ({{ $pembelian->mitra_bisnis->nama_bank}})</b></p>
                    </div>
                </div>

                {{-- Input: Upload Bukti --}}
                <div>
                    <label for="bukti_bayar" class="block text-[10px] font-bold text-[#52627A] uppercase tracking-wider mb-2">Upload Bukti Bayar</label>
                    <input type="file" name="bukti_bayar" accept="image/*" required
                           class="block w-full text-sm text-[#52627A]
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-xs file:font-bold file:uppercase file:tracking-wide
                                  file:bg-[#1E3A5F] file:text-white
                                  hover:file:bg-[#152A46]
                                  border border-[#C9D4E3] rounded-lg cursor-pointer bg-[#F8FAFC] p-2">
                    <p class="mt-1 text-xs text-[#52627A]/70 italic">*Format: JPG, JPEG, atau PNG.</p>
                </div>

                {{-- Tombol Submit --}}
                <button type="submit"
                        class="cursor-pointer w-full bg-[#1E3A5F] hover:bg-[#152A46] text-white font-bold py-3 px-4 rounded-xl uppercase tracking-widest shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 mt-4">
                    Kirim Data & Bukti Bayar
                </button>

                {{-- Tombol Batal/Kembali (Opsional) --}}
                <div class="text-center mt-4">
                     <p class="text-xs font-bold text-red-400 uppercase tracking-wider ">Silahkan isi data diri dan upload bukti transfer, karena jika tidak maka pembelian tidak akan terkonfirmasi.</p>
                </div>

            </form>

            {{-- Tombol Batal: hapus pembelian yang belum dibayar agar tidak menggantung --}}
            <form action="{{ route('batal_pembelian', $pembelian->pembelian_id) }}" method="POST" class="mt-4 text-center"
                  onsubmit="return confirm('Batalkan pembelian ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="cursor-pointer text-xs font-bold text-red-500 uppercase tracking-wider hover:underline">
                    Batalkan Pembelian
                </button>
            </form>
        </div>
    </div>
</div>
@endsection