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
    <title>Register Akun - Sistem Maintenance Hotel</title>
</head>
<body class="min-h-screen bg-[#F4F6FA] text-[#16243A] font-['Plus_Jakarta_Sans'] flex items-center justify-center">
    <div class="relative w-full max-w-xl px-4 py-6">
        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-[#16243A]">Buat Akun Baru</h1>
            <p class="mt-2 text-xs md:text-sm text-[#52627A] max-w-md mx-auto">Daftar sebagai Pegawai atau Tamu untuk mengakses fitur pelaporan dan monitoring maintenance hotel.</p>
        </div>

        <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl shadow-[0_12px_30px_rgba(22,36,58,0.35)] px-6 py-7 md:px-8 md:py-8">
        @if(session('error'))
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
            <form action="{{ route('registerProcess') }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-1.5">
                    <label for="nama" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Nama Lengkap</label>
                    <input type="text" name="nama" id="nama" required
                           class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                           placeholder="Nama lengkap sesuai identitas">
                </div>

                <div class="space-y-1.5">
                    <label for="username" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Username</label>
                    <input type="text" name="username" id="username" required
                           class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                           placeholder="Username untuk login">
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                           placeholder="Minimal 6 karakter">
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                           placeholder="Ulangi password">
                </div>

                <div class="space-y-1.5">
                    <label for="role" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Role</label>
                    <select name="role" id="role" required
                            class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition">
                        <option value="Pegawai" class="bg-white text-[#16243A]">Pegawai</option>
                        <option value="Tamu" class="bg-white text-[#16243A]">Tamu</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label for="kontak" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Kontak</label>
                    <input type="tel" inputmode="numeric" name="kontak" id="kontak" required pattern="\+?[0-9]{8,15}" title="8-15 digit angka"
                           class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                           placeholder="No. HP / WhatsApp aktif">
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold tracking-[0.18em] text-[#52627A] uppercase">Email</label>
                    <input type="email" name="email" id="email" required
                           class="w-full bg-white border border-[#C9D4E3] rounded-xl px-3 py-2.5 text-sm text-[#16243A] placeholder:text-[#94A3B8] focus:outline-none focus:ring-2 focus:ring-[#D69E2E] focus:border-[#D69E2E] transition"
                           placeholder="Email aktif">
                </div>

                <button type="submit"
                        class="w-full mt-2 cursor-pointer inline-flex items-center justify-center gap-2 bg-[#1E3A5F] hover:bg-[#152A46] text-white font-semibold py-2.5 px-4 rounded-full text-sm shadow-lg shadow-[rgba(30,58,95,0.45)] transition">
                    <i class='bx bx-user-plus'></i>
                    <span>Registrasi Akun</span>
                </button>
            </form>

            <div class="pt-3 border-[#C9D4E3] space-y-1.5 text-center text-xs md:text-base">
                <p class="text-[#52627A]">
                    Bermitra dengan kami?
                    <a href="{{ route('form_register_mitra') }}" class="text-[#1E3A5F] hover:text-[#152A46] font-medium hover:underline">Daftar Akun Mitra</a>
                </p>
                <p class="text-[#52627A]">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-[#1E3A5F] hover:text-[#152A46] font-medium hover:underline">Login</a>
                </p>
            </div>
        </div>

        <p class="mt-6 text-[11px] md:text-xs text-center text-[#52627A]">
            &copy; 2025 Sistem Maintenance Hotel. Registrasi akun pengguna internal.</p>
    </div>
</body>
</html>