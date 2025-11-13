<footer class="py-8 border-t border-neutral-200">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-4">
        <div class="text-sm text-neutral-600">&copy; {{ date('Y') }} Sumsel Wedding Expo.</div>
        <div class="flex items-center gap-4 text-sm text-neutral-600">
            <a href="#" class="hover:text-neutral-900">Kebijakan Privasi</a>
            <a href="#" class="hover:text-neutral-900">Syarat & Ketentuan</a>
            @auth
                @if (auth()->user()->hasRole('super_admin'))
                    <a href="/admin" class="hover:text-neutral-900">Admin</a>
                @endif
            @endauth
        </div>
    </div>
</footer>
