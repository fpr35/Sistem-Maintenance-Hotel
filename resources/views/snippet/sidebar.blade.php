@if (auth()->user()->role == 'Admin')
   {{-- SIDEBAR ADMIN --}}
   <aside class="w-64 min-h-screen bg-[#F4F6FA] border-r border-[#DBE3EE] text-[#16243A] flex flex-col shadow-[0_1px_10px_rgba(22,36,58,0.15)] sticky top-0 z-40">
       <div class="p-6 border-b border-[#DBE3EE]/50">
           <h2 class="text-xl font-bold tracking-tight text-[#16243A] flex items-center gap-2">
               <i class='bx bxs-buildings text-[#1E3A5F]'></i>
               <span>Maintenance</span>
           </h2>
           <p class="text-xs text-[#52627A] mt-1 ml-7">Administrator Panel</p>
       </div>

       <nav class="flex-1 px-4 py-6 space-y-1.5 text-sm font-medium">
           <a href="{{route('dashboard')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-grid-alt text-lg group-hover:scale-110 transition-transform'></i>
               Dashboard
           </a>

           <a href="{{route('laporan_kerusakan_admin')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-wrench text-lg group-hover:scale-110 transition-transform'></i>
               Data Kerusakan
               @if ($notif['laporan'] > 0)<span class="ml-auto min-w-[20px] text-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $notif['laporan'] }}</span>@endif
           </a>

           <a href="{{route('list_barang_jasa')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-box text-lg group-hover:scale-110 transition-transform'></i>
               Barang & Jasa
           </a>

           <a href="{{route('mitra_belum_terverifikasi')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-user-voice text-lg group-hover:scale-110 transition-transform'></i>
               Verifikasi Mitra
               @if ($notif['mitra'] > 0)<span class="ml-auto min-w-[20px] text-center rounded-full bg-red-500 px-1.5 py-0.5 text-[10px] font-bold text-white">{{ $notif['mitra'] }}</span>@endif
           </a>

           <a href="{{route('riwayat_pembelian')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
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
   </aside>

@elseif (auth()->user()->role == 'Mitra')
   {{-- SIDEBAR MITRA --}}
   <aside class="w-64 min-h-screen bg-[#F4F6FA] border-r border-[#DBE3EE] text-[#16243A] flex flex-col shadow-[0_1px_10px_rgba(22,36,58,0.15)] sticky top-0">
       <div class="p-6 border-b border-[#DBE3EE]/50">
           <h2 class="text-xl font-bold tracking-tight text-[#16243A] flex items-center gap-2">
               <i class='bx bxs-briefcase-alt-2 text-[#D48806]'></i>
               <span>Mitra Area</span>
           </h2>
           <p class="text-xs text-[#52627A] mt-1 ml-7">Partner Panel</p>
       </div>

       <nav class="flex-1 px-4 py-6 space-y-1.5 text-sm font-medium">
           <a href="{{route('dashboard')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-grid-alt text-lg group-hover:scale-110 transition-transform'></i>
               Dashboard
           </a>

           <a href="{{route('barang_jasa')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-store-alt text-lg group-hover:scale-110 transition-transform'></i>
               Barang & Jasa Saya
           </a>

           <a href="{{route('laporan_kerusakan_mitra')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-list-ul text-lg group-hover:scale-110 transition-transform'></i>
               Laporan Kerusakan
           </a>

           <a href="{{route('orderan')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-cart-download text-lg group-hover:scale-110 transition-transform'></i>
               Orderan Masuk
           </a>

           <a href="{{route('histori_orderan')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-time-five text-lg group-hover:scale-110 transition-transform'></i>
               Riwayat Orderan
           </a>
           <a href="{{route('profil')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-user-circle text-lg group-hover:scale-110 transition-transform'></i>
               Profil Saya
           </a>
       </nav>
   </aside>

@else
   {{-- SIDEBAR STAFF/USER --}}
   <aside class="w-64 min-h-screen bg-[#F4F6FA] border-r border-[#DBE3EE] text-[#16243A] flex flex-col shadow-[0_1px_10px_rgba(22,36,58,0.15)] sticky top-0">
       <div class="p-6 border-b border-[#DBE3EE]/50">
           <h2 class="text-xl font-bold tracking-tight text-[#16243A] flex items-center gap-2">
               <i class='bx bxs-user-badge text-[#1E3A5F]'></i>
               <span>Staff/Guest Area</span>
           </h2>
           <p class="text-xs text-[#52627A] mt-1 ml-7">Employee/Guest Panel</p>
       </div>

       <nav class="flex-1 px-4 py-6 space-y-1.5 text-sm font-medium">
           <a href="{{route('dashboard')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-grid-alt text-lg group-hover:scale-110 transition-transform'></i>
               Dashboard
           </a>

           <a href="{{route('laporan_kerusakan')}}" 
              class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-edit text-lg group-hover:scale-110 transition-transform'></i>
               Laporan Kerusakan
           </a>
           <a href="{{route('profil')}}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-[#52627A] hover:bg-[#E3EBF5] hover:text-[#1E3A5F] transition-all duration-200 group">
               <i class='bx bx-user-circle text-lg group-hover:scale-110 transition-transform'></i>
               Profil Saya
           </a>
       </nav>
   </aside>
@endif