<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'WeddingExpo')</title>

    <!-- Font: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>

<body class="font-[Poppins] text-neutral-800 bg-white">
    @include('layouts.navbar')

    @include('layouts.success-modal')
    @include('layouts.flash')

    <main>
        @yield('content')
    </main>

    @hasSection('footer')
        @yield('footer')
    @else
        @include('layouts.footer')
    @endif

    @stack('scripts')
</body>

</html>
