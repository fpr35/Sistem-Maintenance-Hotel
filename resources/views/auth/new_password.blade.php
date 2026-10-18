<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="{{ asset('img/logo.svg') }}" rel="icon" type="image/svg+xml">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Buat Password Baru - Sistem Maintenance Hotel</title>
</head>
<body class="min-h-screen bg-[#F4F6FA] text-[#16243A] font-['Plus_Jakarta_Sans'] flex items-center justify-center">
    <div class="py-4">
        <div class="relative z-10 w-full max-w-md px-4">
            <div class="mb-8 text-center">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-[#16243A]">Buat Password Baru</h1>
                <p class="mt-2 text-xs md:text-sm text-[#52627A]">Masukkan email Anda untuk membuat password baru.</p>
            </div>

            <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl shadow-[0_12px_30px_rgba(22,36,58,0.35)] px-6 py-7 md:px-8 md:py-8">
            
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

                <form action="{{ route('new_password_process', $user->user_id) }}" method="POST" class="space-y-5">
                    @csrf

                    <input type="hidden" name="token" value="{{$token}}">
                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Password baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-[#94A3B8] text-lg">
                                <i class='bx bx-lock-alt'></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                class="w-full bg-white border border-[#C9D4E3] rounded-xl pl-10 pr-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                                placeholder="Masukkan password baru Anda">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Konfirmasi password baru</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-[#94A3B8] text-lg">
                                <i class='bx bx-lock-alt'></i>
                            </span>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="w-full bg-white border border-[#C9D4E3] rounded-xl pl-10 pr-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full mt-2 cursor-pointer inline-flex items-center justify-center gap-2 bg-[#1E3A5F] hover:bg-[#152A46] text-white font-semibold py-2.5 px-4 rounded-full text-sm shadow-lg shadow-[rgba(30,58,95,0.45)] transition">
                        <i class='bx bx-log-in'></i>
                        <span>Buat Password Baru</span>
                    </button>

                </form>
            </div>

            <p class="mt-6 text-[11px] md:text-xs text-center text-[#52627A]">
                &copy; 2025 Sistem Maintenance Hotel. Internal use only.
            </p>
        </div>
    </div>
</body>
</html>