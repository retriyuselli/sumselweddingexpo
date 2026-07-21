@if(session('success'))
    <div x-data="{ open: true }" x-show="open" x-cloak x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/40" @click="open = false" aria-hidden="true"></div>

        <div role="dialog" aria-modal="true" aria-labelledby="successModalTitle"
             class="relative z-10 w-full max-w-md bg-white rounded-2xl shadow-xl border border-neutral-200"
             @click.stop>
            <div class="p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-2.59a.75.75 0 10-1.22-.88l-3.847 5.34-1.69-1.69a.75.75 0 10-1.06 1.06l2.25 2.25a.75.75 0 001.164-.094l4.463-6.986Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h2 id="successModalTitle" class="text-lg font-semibold text-neutral-900">Berhasil</h2>
                        <p class="mt-1 text-sm text-neutral-700">{{ session('success') }}</p>
                    </div>
                    <button type="button" @click="open = false" class="text-neutral-500 hover:text-neutral-700" aria-label="Tutup">
                        &times;
                    </button>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="/dashboard" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                        Dashboard
                    </a>
                    <button type="button" @click="open = false" class="px-4 py-2 rounded-lg border border-neutral-200 text-neutral-700 hover:bg-neutral-50">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
