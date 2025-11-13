<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'WeddingExpo')</title>

    <script>
        (function() {
            try {
                var STORAGE_KEY = 'theme';
                var stored = localStorage.getItem(STORAGE_KEY);
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)');

                function apply(theme) {
                    var html = document.documentElement;
                    if (theme === 'dark' || (theme === 'auto' && prefersDark.matches)) {
                        html.classList.add('dark');
                    } else {
                        html.classList.remove('dark');
                    }
                }

                // Initial apply
                var theme = stored || 'light';
                apply(theme);

                // Listen to system changes when auto
                if (prefersDark && theme === 'auto') {
                    prefersDark.addEventListener('change', function() {
                        apply('auto');
                    });
                }

                // Expose a small API for pages to update theme
                window.__setTheme = function(next) {
                    localStorage.setItem(STORAGE_KEY, next);
                    apply(next);
                };

                window.__getTheme = function() {
                    return localStorage.getItem(STORAGE_KEY) || 'light';
                };
            } catch (e) {
                // Fail silently to avoid blocking page render
            }
        })();
    </script>

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
