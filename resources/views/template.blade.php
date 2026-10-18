<!DOCTYPE html>
<html lang="id">
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
    
    @include('snippet.header')

    <main class="block lg:flex ">
        <div class="w-1/5 hidden lg:block">
            @if (Auth::check())
                @include('snippet.sidebar')
            @endif
        </div>
        <div class="lg:w-4/5 w-full px-2">
            @yield('content')
        </div>
    </main>

    <footer class="border-t border-[#DBE3EE] bg-[#EAF0F7]">
        <div class="mx-auto px-8 py-4 text-xs md:text-sm text-[#52627A] flex flex-col md:flex-row items-center justify-between gap-2">
            <p>&copy; 2025 Sistem Maintenance Hotel</p>
            <p class="flex items-center gap-1 text-[#6B7A90]">
                <i class='bx bx-shield-quarter text-lg'></i>
                <span>Internal Maintenance Portal</span>
            </p>
        </div>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>