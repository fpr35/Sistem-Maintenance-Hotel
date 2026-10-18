<header class="border-b border-[#DBE3EE] bg-[#EAF0F7]/90 backdrop-blur lg:static sticky top-0 z-50">
        <nav class="mx-auto px-8 py-3 flex justify-between items-center text-[#16243A] text-sm">
            <div class="flex items-center gap-2">
                <span class="hidden lg:inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#F4F6FA] text-[#1E3A5F] border border-[#DBE3EE]">
                    <i class='bx bx-wrench text-lg'></i>
                </span>
                <button class="lg:hidden cursor-pointer text-[#c53030] hover:bg-[#d8716c] hover:text-white rounded-lg p-1" type="button" onclick="toggleMobileMenu()">
                    <i class='bx bx-menu text-2xl' id="menu-icon"></i>
                </button>
                <div>
                    <a href="{{ route('homepage') }}">
                        <p class="font-semibold leading-tight">Sistem Maintenance Hotel</p>
                        <p class="text-[11px] text-[#52627A] leading-tight">Facility Management</p>
                    </a>
                </div>
            </div>
            <ul class="flex items-center gap-4 text-xs md:text-sm">
                @if (Auth::check())
                    <li>
                        {{-- Logout memakai POST + CSRF agar tidak bisa dipicu dari link luar --}}
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                               class="cursor-pointer px-3 py-1.5 rounded-full border border-red-500 text-red-500 hover:border-[#D69E2E] hover:text-[#16243A] bg-white/70 hover:bg-white transition text-xs md:text-sm">
                                Logout
                            </button>
                        </form>
                    </li>
                @endif
            </ul>
        </nav>
</header>

<div class="hidden md:hidden w-full z-50 bg-[#F4F6FA] border-b border-[#DBE3EE] shadow-lg transition-all duration-300" id="mobile-menu">
            
    @if (auth()->user()->role == 'Admin')
        {{-- MOBILE MENU ADMIN --}}
        <div class="p-4">
            <div class="pb-3 mb-2 border-b border-[#DBE3EE]/50">
                <h2 class="text-sm font-bold tracking-tight text-[#16243A] flex items-center gap-2">
                    <i class='bx bxs-buildings text-[#1E3A5F]'></i>
                    <span>Maintenance Admin</span>
                </h2>
            </div>

            <nav class="space-y-1 text-sm font-medium">
                <a href="{{route('dashboard')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-grid-alt text-lg group-hover:scale-110 transition-transform'></i>
                    Dashboard
                </a>
                <a href="{{route('laporan_kerusakan_admin')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-wrench text-lg group-hover:scale-110 transition-transform'></i>
                    Data Kerusakan
                    @if ($notif['laporan'] > 0)<span class="ml-auto min-w-[20px] text-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $notif['laporan'] }}</span>@endif
                </a>
                <a href="{{route('list_barang_jasa')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-box text-lg group-hover:scale-110 transition-transform'></i>
                    Barang & Jasa
                </a>
                <a href="{{route('mitra_belum_terverifikasi')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-user-voice text-lg group-hover:scale-110 transition-transform'></i>
                    Verifikasi Mitra
                    @if ($notif['mitra'] > 0)<span class="ml-auto min-w-[20px] text-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $notif['mitra'] }}</span>@endif
                </a>
                <a href="{{route('riwayat_pembelian')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-history text-lg group-hover:scale-110 transition-transform'></i>
                    Riwayat Pembelian
                </a>
                <a href="{{route('kelola_user')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
                    <i class='bx bx-group text-lg group-hover:scale-110 transition-transform'></i>
                    Kelola User
                </a>
                <a href="{{route('profil')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
                    <i class='bx bx-user-circle text-lg group-hover:scale-110 transition-transform'></i>
                    Profil Saya
                </a>
            </nav>
        </div>

    @elseif (auth()->user()->role == 'Mitra')
        {{-- MOBILE MENU MITRA --}}
        <div class="p-4">
            <div class="pb-3 mb-2 border-b border-[#DBE3EE]/50">
                <h2 class="text-sm font-bold tracking-tight text-[#16243A] flex items-center gap-2">
                    <i class='bx bxs-briefcase-alt-2 text-[#D48806]'></i>
                    <span>Mitra Area</span>
                </h2>
            </div>

            <nav class="space-y-1 text-sm font-medium">
                <a href="{{route('dashboard')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-grid-alt text-lg group-hover:scale-110 transition-transform'></i>
                    Dashboard
                </a>
                <a href="{{route('barang_jasa')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-store-alt text-lg group-hover:scale-110 transition-transform'></i>
                    Barang & Jasa Saya
                </a>
                <a href="{{route('laporan_kerusakan_mitra')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-list-ul text-lg group-hover:scale-110 transition-transform'></i>
                    Laporan Kerusakan
                </a>
                <a href="{{route('orderan')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-cart-download text-lg group-hover:scale-110 transition-transform'></i>
                    Orderan Masuk
                </a>
                <a href="{{route('histori_orderan')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-time-five text-lg group-hover:scale-110 transition-transform'></i>
                    Riwayat Orderan
                </a>
                <a href="{{route('profil')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
                    <i class='bx bx-user-circle text-lg group-hover:scale-110 transition-transform'></i>
                    Profil Saya
                </a>
            </nav>
        </div>

    @else
        {{-- MOBILE MENU STAFF/USER --}}
        <div class="p-4">
            <div class="pb-3 mb-2 border-b border-[#DBE3EE]/50">
                <h2 class="text-sm font-bold tracking-tight text-[#16243A] flex items-center gap-2">
                    <i class='bx bxs-user-badge text-[#1E3A5F]'></i>
                    <span>Staff/Guest Area</span>
                </h2>
            </div>

            <nav class="space-y-1 text-sm font-medium">
                <a href="{{route('dashboard')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-grid-alt text-lg group-hover:scale-110 transition-transform'></i>
                    Dashboard
                </a>
                <a href="{{route('laporan_kerusakan')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all group">
                    <i class='bx bx-edit text-lg group-hover:scale-110 transition-transform'></i>
                    Laporan Kerusakan
                </a>
                <a href="{{route('profil')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
                    <i class='bx bx-user-circle text-lg group-hover:scale-110 transition-transform'></i>
                    Profil Saya
                </a>
            </nav>
        </div>
    @endif
</div>
<script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                menu.classList.add('sticky');
                menu.classList.add('top-15');
                icon.classList.remove('bx-menu');
                icon.classList.add('bx-x');
            } else {
                menu.classList.remove('sticky');
                menu.classList.remove('top-15');
                menu.classList.add('hidden');
                icon.classList.remove('bx-x');
                icon.classList.add('bx-menu');
            }
        }
</script>