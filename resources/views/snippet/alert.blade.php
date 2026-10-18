{{-- Pesan sukses / error / validasi (dipakai ulang di banyak halaman) --}}
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
        <ul class="text-sm list-disc ml-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
