<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('img/logo.svg') }}" rel="icon" type="image/svg+xml">
    <title>Sistem Maintenance Hotel</title>
</head>
<body class="bg-[#F4F6FA] text-[#16243A] font-['Plus_Jakarta_Sans']">

    <nav class="bg-[#EAF0F7] w-full fixed top-0 z-50 shadow-lg border-b border-[#DBE3EE] transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <div class="flex-shrink-0 flex items-center gap-2">
                    <a href="/" class="text-[#16243A] font-bold text-lg md:text-xl tracking-tight hover:text-[#1E3A5F] transition">
                        Sistem Maintenance Hotel
                    </a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="#laporan" class="text-[#52627A] hover:text-[#1E3A5F] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Laporan</a>
                        <a href="#tentang" class="text-[#52627A] hover:text-[#1E3A5F] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Tentang</a>
                        <a href="#status" class="text-[#52627A] hover:text-[#1E3A5F] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Status</a>
                        <a href="#area-penting" class="text-[#52627A] hover:text-[#1E3A5F] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Area</a>
                        <a href="#kontak" class="text-[#52627A] hover:text-[#1E3A5F] px-3 py-2 rounded-md text-sm font-medium transition duration-300">Kontak</a>
                        
                        <div class="ml-4 flex items-center gap-2">
                            <a href="{{ route('login') }}" class="text-[#52627A] hover:text-[#1E3A5F] hover:bg-[#DBE3EE]/50 px-4 py-2 rounded-full text-sm font-medium transition duration-300">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="bg-[#1E3A5F] text-white hover:bg-[#152A46] px-5 py-2 rounded-full text-sm font-medium shadow-md hover:shadow-lg transition duration-300">
                                Register
                            </a>
                        </div>
                    </div>
                </div>

                <div class="-mr-2 flex md:hidden">
                    <button type="button" onclick="toggleMobileMenu()" 
                            class="cursor-pointer inline-flex items-center justify-center p-2 rounded-md text-[#52627A] hover:text-[#16243A] hover:bg-[#DBE3EE] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E3A5F]" 
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class='bx bx-menu text-3xl' id="menu-icon"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="hidden md:hidden bg-[#EAF0F7] border-t border-[#DBE3EE] shadow-inner" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#laporan" class="text-[#52627A] hover:text-[#1E3A5F] hover:bg-[#F4F6FA] block px-3 py-2 rounded-md text-base font-medium transition">Laporan Kerusakan</a>
                <a href="#tentang" class="text-[#52627A] hover:text-[#1E3A5F] hover:bg-[#F4F6FA] block px-3 py-2 rounded-md text-base font-medium transition">Tentang Sistem</a>
                <a href="#status" class="text-[#52627A] hover:text-[#1E3A5F] hover:bg-[#F4F6FA] block px-3 py-2 rounded-md text-base font-medium transition">Status Maintenance</a>
                <a href="#area-penting" class="text-[#52627A] hover:text-[#1E3A5F] hover:bg-[#F4F6FA] block px-3 py-2 rounded-md text-base font-medium transition">Area Penting</a>
                <a href="#kontak" class="text-[#52627A] hover:text-[#1E3A5F] hover:bg-[#F4F6FA] block px-3 py-2 rounded-md text-base font-medium transition">Kontak Engineering</a>
            </div>
            
            <div class="pt-4 pb-4 border-t border-[#DBE3EE]">
                <div class="flex items-center px-5 gap-3">
                    <a href="{{ route('login') }}" class="block w-full text-center px-4 py-2 border border-[#DBE3EE] rounded-full text-base font-medium text-[#52627A] hover:bg-[#F4F6FA] hover:text-[#1E3A5F] transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block w-full text-center px-4 py-2 border border-transparent rounded-full text-base font-medium text-white bg-[#1E3A5F] hover:bg-[#152A46] transition">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </nav>


    <div class="relative w-full overflow-hidden bg-gradient-to-b from-[#DDE6F2] via-[#E9EFF7] to-[#F4F6FA] mt-20">
        <div class="swiper mySwiper h-[75vh] md:h-[85vh] lg:h-[100vh]">
            <div class="swiper-wrapper h-full">
                <div class="swiper-slide relative h-full">
                    <img src="{{ asset('img/landing/hero-hotel.jpg') }}" alt="Hotel"
                         class="w-full h-full object-cover brightness-[0.70] object-center">
                    
                    <div class="absolute inset-0 bg-gradient-to-b from-black/20 via-black/20 to-black/40 flex items-center justify-center px-4 md:px-8">
                        <div class="max-w-4xl mx-auto text-center animate-fade-in-up">

                            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight text-white drop-shadow-lg leading-tight mb-4 md:mb-6">
                                Monitoring & Maintenance <br class="hidden md:block"> Fasilitas Hotel Real-Time
                            </h1>
                            
                            <p class="text-sm sm:text-base md:text-lg text-gray-100 max-w-xl md:max-w-2xl mx-auto mb-8 leading-relaxed">
                                Kelola laporan kerusakan, jadwal perawatan, dan koordinasi tim engineering dalam satu dashboard yang elegan dan mudah digunakan.
                            </p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 md:gap-4 w-full px-4">
                                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-[#D69E2E] hover:bg-[#B7791F] text-[#16243A] font-bold text-sm md:text-base shadow-lg shadow-black/20 transition transform hover:-translate-y-1">
                                    + Buat Laporan
                                </a>
                                <a href="#laporan" class="w-full sm:w-auto px-8 py-3.5 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/40 text-white font-semibold text-sm md:text-base flex items-center justify-center gap-2 transition hover:border-white">
                                    <i class='bx bx-list-check text-xl'></i>
                                    Lihat Aktivitas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="border-y border-[#DBE3EE] bg-[#EAF0F7]/90 backdrop-blur sticky top-20 z-40">
        <div class="max-w-6xl mx-auto px-4 py-6 md:py-8 flex flex-col md:flex-row items-center justify-between gap-4 md:gap-8">
            <div class="text-center md:text-left">
                <p class="text-[10px] md:text-xs uppercase tracking-[0.25em] text-[#D69E2E] font-bold mb-1">Quick Action</p>
                <h2 class="text-lg md:text-2xl font-bold text-[#16243A]">Ada kerusakan baru?</h2>
                <p class="text-sm text-[#46566D] mt-1 hidden md:block">Laporkan segera agar ditindaklanjuti engineering.</p>
            </div>
            <a href="{{ route('dashboard') }}"
               class="w-full md:w-auto px-6 py-3 bg-[#1E3A5F] text-[#F4F6FA] text-center rounded-full text-sm font-bold hover:bg-[#152A46] transition shadow-md whitespace-nowrap">
                + Lapor Kerusakan
            </a>
        </div>
    </div>


    <section class="max-w-6xl mx-auto px-4 py-12 md:py-16" id="tentang">
        <div class="text-center max-w-3xl mx-auto">
            <h2 class="text-2xl md:text-4xl font-bold text-[#16243A] mb-4">Tentang Sistem Maintenance</h2>
            <p class="text-[#3B4A61] leading-relaxed text-sm md:text-base">
                Sistem Maintenance Hotel ini dirancang untuk mempermudah proses pelaporan kerusakan,
                koordinasi teknisi, monitoring fasilitas, dan penjadwalan preventive maintenance.
                Dengan tampilan modern dan mudah digunakan, staff engineering & housekeeping dapat bekerja
                lebih cepat dan efisien.
            </p>
        </div>
    </section>


    <section class="py-12 md:py-16 bg-[#E3EBF5] border-y border-[#DBE3EE]" id="status">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center text-[#16243A] mb-3">
                Status Maintenance
            </h2>
            <p class="text-center text-[#52627A] text-sm mb-8 md:mb-10">Ringkasan progres hari ini.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                
                <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 text-center shadow-[0_8px_20px_rgba(22,36,58,0.1)] hover:shadow-[0_12px_30px_rgba(22,36,58,0.2)] transition">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B7791F] mb-2 font-bold">Terlapor</p>
                    <h3 class="text-base font-bold text-[#16243A] mb-1">Laporan Masuk</h3>
                    <p class="text-3xl md:text-4xl text-[#1E3A5F] font-extrabold">{{ $laporan_masuk }}</p>
                </div>

                <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 text-center shadow-[0_8px_20px_rgba(22,36,58,0.1)] hover:shadow-[0_12px_30px_rgba(22,36,58,0.2)] transition">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B7791F] mb-2 font-bold">Dipastikan</p>
                    <h3 class="text-base font-bold text-[#16243A] mb-1">Terverifikasi</h3>
                    <p class="text-3xl md:text-4xl text-[#1D7BB8] font-extrabold">{{ $laporan_terverifikasi }}</p>
                </div>

                <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 text-center shadow-[0_8px_20px_rgba(22,36,58,0.1)] hover:shadow-[0_12px_30px_rgba(22,36,58,0.2)] transition">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B7791F] mb-2 font-bold">On Progress</p>
                    <h3 class="text-base font-bold text-[#16243A] mb-1">Dikerjakan</h3>
                    <p class="text-3xl md:text-4xl text-[#D48806] font-extrabold">{{ $laporan_dikerjakan }}</p>
                </div>

                <div class="bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl p-6 text-center shadow-[0_8px_20px_rgba(22,36,58,0.1)] hover:shadow-[0_12px_30px_rgba(22,36,58,0.2)] transition">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-[#B7791F] mb-2 font-bold">Selesai</p>
                    <h3 class="text-base font-bold text-[#16243A] mb-1">Bulan Ini</h3>
                    <p class="text-3xl md:text-4xl text-[#2E7D32] font-extrabold">{{ $laporan_selesai }}</p>
                </div>

            </div>
        </div>
    </section>


    <section class="max-w-6xl mx-auto px-4 py-12 md:py-16" id="area-penting">
        <h2 class="text-2xl md:text-3xl font-bold text-center text-[#16243A] mb-3">Area Penting Hotel</h2>
        <p class="text-center text-[#52627A] text-sm mb-10">Fasilitas krusial yang dipantau berkala.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
            <div class="group bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('img/landing/area-genset.jpg') }}" alt="Ruang Genset"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-5 text-center md:text-left">
                    <h3 class="font-bold text-lg text-[#16243A]">Ruang Genset</h3>
                    <p class="text-[#52627A] mt-2 text-sm leading-relaxed">
                        Monitoring kelistrikan & backup daya utama hotel untuk mencegah pemadaman.
                    </p>
                </div>
            </div>

            <div class="group bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('img/landing/area-ac.jpg') }}" alt="Sistem AC"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-5 text-center md:text-left">
                    <h3 class="font-bold text-lg text-[#16243A]">Sistem AC & HVAC</h3>
                    <p class="text-[#52627A] mt-2 text-sm leading-relaxed">
                        Perawatan chiller, AHU, dan AC kamar tamu untuk kenyamanan maksimal.
                    </p>
                </div>
            </div>

            <div class="group bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition">
                <div class="h-48 overflow-hidden">
                    <img src="{{ asset('img/landing/area-lift.jpg') }}" alt="Lift"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-5 text-center md:text-left">
                    <h3 class="font-bold text-lg text-[#16243A]">Lift Tamu & Service</h3>
                    <p class="text-[#52627A] mt-2 text-sm leading-relaxed">
                        Pemeriksaan keamanan harian transportasi vertikal untuk keselamatan tamu.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <section class="py-12 md:py-16 bg-[#E3EBF5] border-y border-[#DBE3EE]" id="laporan">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8 md:mb-10">
                <h2 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-3">
                    Laporan Terbaru
                </h2>
                <p class="text-[#52627A] text-sm">Geser ke samping untuk melihat aktivitas maintenance terkini.</p>
            </div>

            <div class="flex overflow-x-auto pb-8 gap-4 md:gap-6 px-2 snap-x snap-mandatory scroll-smooth" style="scrollbar-width: thin; scrollbar-color: #C9D4E3 transparent;">
                
                @foreach ($laporan_semua as $laporan)
                    <div class="min-w-[280px] md:min-w-[320px] bg-[#F4F6FA] border border-[#C9D4E3] rounded-2xl shadow-[0_8px_20px_rgba(22,36,58,0.15)] overflow-hidden flex flex-col snap-center group hover:border-[#D69E2E] transition-all duration-300 hover:shadow-lg">
                        
                        <div class="relative h-48 w-full bg-[#DDE6F2] overflow-hidden">
                            @if ($laporan->foto_kerusakan)
                                <a href="{{ asset('storage/' . $laporan->foto_kerusakan) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $laporan->foto_kerusakan) }}" 
                                         alt="Lokasi Kerusakan" 
                                         class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                </a>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#D69E2E] flex-col gap-2">
                                    <i class='bx bx-image-alt text-4xl opacity-50'></i>
                                    <span class="text-xs italic opacity-70">Tidak ada foto</span>
                                </div>
                            @endif
                            
                            <div class="absolute top-3 right-3 bg-[#EAF0F7]/90 backdrop-blur-sm px-3 py-1 rounded-full border border-[#C9D4E3] shadow-sm">
                                <span class="text-[10px] font-bold text-[#52627A] uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($laporan->tanggal_lapor)->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5 flex flex-col flex-1 justify-between">
                            <div>
                                <h3 class="font-bold text-lg text-[#16243A] line-clamp-1 mb-1" title="{{ $laporan->lokasi_fasilitas }}">
                                    {{ $laporan->lokasi_fasilitas }}
                                </h3>
                                <p class="text-xs text-[#52627A] mb-4 flex items-center gap-1">
                                    <i class='bx bx-map'></i> Lokasi Area Hotel
                                </p>
                            </div>

                            <div class="pt-4 border-t border-[#DBE3EE] flex justify-between items-center">
                                <span class="text-xs font-semibold text-[#52627A]">Status:</span>
                                
                                @switch($laporan->status_laporan)
                                    @case('Menunggu')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#DBE4F0] text-[#46566D] border border-[#C9D4E3] text-xs font-semibold shadow-sm">
                                            Menunggu
                                        </span>
                                        @break
                                    @case('Diverifikasi')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#E3F2FD] text-[#1565C0] border border-[#90CAF9] text-xs font-semibold shadow-sm">
                                            Diverifikasi
                                        </span>
                                        @break
                                    @case('Diperbaiki')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#FFF3CD] text-[#B8860B] border border-[#FFE082] text-xs font-semibold shadow-sm">
                                            Diperbaiki
                                        </span>
                                        @break
                                    @case('Selesai')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-[#E1F3E6] text-[#2E7D32] border border-[#A5D6A7] text-xs font-semibold shadow-sm">
                                            Selesai
                                        </span>
                                        @break
                                    @default
                                        <span class="text-xs text-[#16243A]">{{ $laporan->status_laporan }}</span>
                                @endswitch
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="flex justify-center mt-4 text-[#52627A]/50 text-xs md:text-sm animate-pulse md:hidden">
                <span>&larr; Geser untuk melihat lainnya &rarr;</span>
            </div>
        </div>
    </section>


    <section class="max-w-4xl mx-auto px-4 py-12 md:py-16 text-center" id="kontak">
        <h2 class="text-2xl md:text-3xl font-bold text-[#16243A] mb-4">Kontak Engineering</h2>
        <div class="inline-block p-6 rounded-2xl bg-white border border-[#DBE3EE] shadow-sm">
            <p class="text-[#3B4A61] text-sm md:text-base mb-2 font-bold">
                Engineering Office – Departemen Maintenance
            </p>
            <p class="text-[#3B4A61] text-sm">
                <i class='bx bx-phone'></i> +62 812 0000 0000 <span class="mx-2">|</span> <i class='bx bx-envelope'></i> engineering@hotel.com
            </p>
        </div>
    </section>

    <footer class="border-t border-[#DBE3EE] bg-[#16243A] text-white">
        <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row items-center justify-between gap-4 text-xs md:text-sm opacity-90">
            <p class="text-center md:text-left">&copy; 2025 Sistem Maintenance Hotel. All rights reserved.</p>
            <p class="flex items-center gap-1">
                <i class='bx bx-shield-quarter text-lg'></i>
                <span>Internal System</span>
            </p>
        </div>
    </footer>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('menu-icon');
            
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.classList.remove('bx-menu');
                icon.classList.add('bx-x');
            } else {
                menu.classList.add('hidden');
                icon.classList.remove('bx-x');
                icon.classList.add('bx-menu');
            }
        }
        
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
                document.getElementById('menu-icon').classList.remove('bx-x');
                document.getElementById('menu-icon').classList.add('bx-menu');
            });
        });
    </script>
</body>
</html>