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
    <title>Login - Sistem Maintenance Hotel</title>
</head>
<body class="min-h-screen bg-[#F4F6FA] text-[#16243A] font-['Plus_Jakarta_Sans'] flex items-center justify-center">
    <div class="py-4">
        <div class="relative z-10 w-full max-w-md px-4">
            <div class="mb-8 text-center">
                <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-[#16243A]">Masuk ke Dashboard</h1>
                <p class="mt-2 text-xs md:text-sm text-[#52627A]">Gunakan akun Anda untuk mengelola laporan kerusakan dan maintenance fasilitas hotel.</p>
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

                <form action="{{ route('loginProcess') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="space-y-1.5">
                        <label for="username" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-[#94A3B8] text-lg">
                                <i class='bx bx-user'></i>
                            </span>
                            <input type="text" name="username" id="username" required
                                class="w-full bg-white border border-[#C9D4E3] rounded-xl pl-10 pr-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                                placeholder="Masukkan username Anda">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label for="password" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-[#94A3B8] text-lg">
                                <i class='bx bx-lock-alt'></i>
                            </span>
                            <input type="password" name="password" id="password" required
                                class="w-full bg-white border border-[#C9D4E3] rounded-xl pl-10 pr-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                                placeholder="Masukkan password Anda">
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full mt-2 cursor-pointer inline-flex items-center justify-center gap-2 bg-[#1E3A5F] hover:bg-[#152A46] text-white font-semibold py-2.5 px-4 rounded-full text-sm shadow-lg shadow-[rgba(30,58,95,0.45)] transition">
                        <i class='bx bx-log-in'></i>
                        <span>Masuk</span>
                    </button>

                    <p class="text-[#52627A] text-center text-xs md:text-sm">
                        Lupa Password?
                        <a href="{{ route('reset_password') }}" class="text-[#1E3A5F] hover:text-[#152A46] font-medium hover:underline">Reset Password</a>
                    </p>

                    <div class="pt-3 border-t border-[#C9D4E3] space-y-1.5 text-center text-xs md:text-sm">
                        <p class="text-[#52627A]">
                            Belum punya akun internal?
                            <a href="{{ route('register') }}" class="text-[#1E3A5F] hover:text-[#152A46] font-medium hover:underline">Daftar Akun Staff/Tamu</a>
                        </p>
                        <p class="text-[#52627A]">
                            Bermitra dengan kami?
                            <a href="{{ route('form_register_mitra') }}" class="text-[#1E3A5F] hover:text-[#152A46] font-medium hover:underline">Daftar Akun Mitra</a>
                        </p>
                    </div>
                </form>
            </div>

            <p class="mt-6 text-[11px] md:text-xs text-center text-[#52627A]">
                &copy; 2025 Sistem Maintenance Hotel. Internal use only.
            </p>
        </div>
    </div>
</body>
</html>