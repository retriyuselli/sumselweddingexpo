@php
    $hasError = session('error');
    $hasStatus = session('status');
    $hasErrorsBag = $errors->any();
@endphp

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    @if($hasError)
        <div class="mt-4 mb-2 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <div class="flex items-start gap-3 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mt-0.5">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 5.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V8.25A.75.75 0 0112 7.5Zm0 9a.75.75 0 100-1.5.75.75 0 000 1.5Z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <div class="font-medium">Terjadi Kesalahan</div>
                    <div class="text-sm">{{ $hasError }}</div>
                </div>
                <button type="button" class="text-red-700 hover:text-red-900" onclick="this.closest('div').remove()">&times;</button>
            </div>
        </div>
    @endif

    @if($hasStatus)
        <div class="mt-4 mb-2 rounded-lg bg-blue-50 border border-blue-200 text-blue-800">
            <div class="flex items-start gap-3 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mt-0.5">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 5.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V8.25A.75.75 0 0112 7.5Zm0 9a.75.75 0 100-1.5.75.75 0 000 1.5Z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <div class="font-medium">Informasi</div>
                    <div class="text-sm">{{ $hasStatus }}</div>
                </div>
                <button type="button" class="text-blue-700 hover:text-blue-900" onclick="this.closest('div').remove()">&times;</button>
            </div>
        </div>
    @endif

    @if($hasErrorsBag)
        <div class="mt-4 mb-2 rounded-lg bg-red-50 border border-red-200 text-red-800">
            <div class="flex items-start gap-3 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 mt-0.5">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 5.25a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0V8.25A.75.75 0 0112 7.5Zm0 9a.75.75 0 100-1.5.75.75 0 000 1.5Z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <div class="font-medium">Validasi Gagal</div>
                    <ul class="mt-1 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="text-red-700 hover:text-red-900" onclick="this.closest('div').remove()">&times;</button>
            </div>
        </div>
    @endif
</div>