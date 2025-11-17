@extends('layouts.app')

@section('title', 'Edit Produk — WeddingExpo')

@section('content')
    <main class="min-h-screen bg-gray-50">
        <section class="pt-24 md:pt-28 pb-6 bg-linear-to-r from-blue-50 to-indigo-50">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Edit Produk</h1>
                <p class="text-sm text-neutral-600 mt-1">{{ $vendor->nama_vendor }}</p>
                <div class="mt-3">
                    <a href="{{ route('vendors.show', $vendor->slug) }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-700">&larr; Kembali ke halaman vendor</a>
                </div>
            </div>
        </section>

        <section class="py-8 sm:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl border border-neutral-200 p-4 sm:p-6">
                    @if (session('success'))
                        <div class="mb-3 text-xs px-3 py-2 rounded-lg border border-green-300 text-green-700 bg-green-50">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-3 text-xs px-3 py-2 rounded-lg border border-rose-300 text-rose-700 bg-rose-50">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('vendors.products.update', [$vendor, $product]) }}" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="text-xs text-neutral-600">Nama Produk</label>
                            <input name="nama_produk" type="text" required class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm" value="{{ old('nama_produk', $product->nama_produk) }}">
                        </div>
                        <div>
                            <label class="text-xs text-neutral-600">Harga</label>
                            <input name="harga" type="number" min="0" required class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm" value="{{ old('harga', (int) ($product->harga ?? 0)) }}">
                        </div>
                        <div>
                            <label class="text-xs text-neutral-600">DP (opsional)</label>
                            <input name="dp_fixed" type="number" min="0" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm" value="{{ old('dp_fixed', (int) ($product->dp_fixed ?? 0)) }}">
                        </div>
                        <div>
                            <label class="text-xs text-neutral-600">Foto Produk (opsional)</label>
                            <input name="foto" type="file" accept="image/*" class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                            <p class="mt-1 text-[11px] text-neutral-500">Max 1MB</p>
                            @php
                                $currentImg = !empty($product->foto_url)
                                    ? (\Illuminate\Support\Str::startsWith($product->foto_url, ['http://','https://'])
                                        ? $product->foto_url
                                        : \Illuminate\Support\Facades\Storage::url($product->foto_url))
                                    : null;
                            @endphp
                            @if ($currentImg)
                                <img src="{{ $currentImg }}" alt="Current" class="mt-2 h-20 w-20 object-cover rounded" />
                            @endif
                            <img id="foto-preview-edit" class="mt-2 h-20 w-20 object-cover rounded hidden" alt="Preview">
                            <p id="foto-error-edit" class="mt-1 text-[11px] text-rose-600 hidden"></p>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-xs text-neutral-600">Deskripsi (opsional)</label>
                            <div class="mt-1 rounded-lg border border-neutral-300">
                                <div class="flex items-center gap-1 p-2 border-b border-neutral-200 bg-neutral-50">
                                    <button type="button" data-cmd="bold" class="px-2 py-1 text-xs rounded border border-neutral-300">B</button>
                                    <button type="button" data-cmd="italic" class="px-2 py-1 text-xs rounded border border-neutral-300"><em>I</em></button>
                                    <button type="button" data-cmd="underline" class="px-2 py-1 text-xs rounded border border-neutral-300"><u>U</u></button>
                                    <span class="mx-2 h-4 w-px bg-neutral-300"></span>
                                    <button type="button" data-cmd="insertUnorderedList" class="px-2 py-1 text-xs rounded border border-neutral-300">• List</button>
                                    <button type="button" data-cmd="insertOrderedList" class="px-2 py-1 text-xs rounded border border-neutral-300">1. List</button>
                                    <button type="button" id="btn-insert-link" class="px-2 py-1 text-xs rounded border border-neutral-300">Link</button>
                                    <span class="mx-2 h-4 w-px bg-neutral-300"></span>
                                    <button type="button" data-cmd="removeFormat" class="px-2 py-1 text-xs rounded border border-neutral-300">Clear</button>
                                </div>
                                <div id="rich-desc" class="min-h-[120px] p-3 text-sm" contenteditable="true" placeholder="Detail paket, fasilitas, dll."></div>
                                <textarea id="rich-desc-textarea" name="deskripsi" class="hidden">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 sm:col-span-2">
                            <label class="inline-flex items-center gap-2 text-xs text-neutral-700"><input type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>Aktifkan produk</label>
                            <button type="submit" class="ml-auto px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-xs hover:bg-indigo-700">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <script>
        (function() {
            const rte = document.getElementById('rich-desc');
            const rteHidden = document.getElementById('rich-desc-textarea');
            const rteBtns = document.querySelectorAll('[data-cmd]');
            const linkBtn = document.getElementById('btn-insert-link');
            const formEl = document.querySelector('form');
            if (rte && rteHidden && formEl) {
                if (rteHidden.value) {
                    rte.innerHTML = rteHidden.value;
                }
                rteBtns.forEach(b => {
                    b.addEventListener('click', () => {
                        document.execCommand(b.dataset.cmd, false, null);
                        rte.focus();
                    });
                });
                if (linkBtn) {
                    linkBtn.addEventListener('click', () => {
                        const url = prompt('Masukkan URL');
                        if (url) {
                            document.execCommand('createLink', false, url);
                        }
                        rte.focus();
                    });
                }
                formEl.addEventListener('submit', () => {
                    rteHidden.value = rte.innerHTML.trim();
                });
            }

            const editFotoInput = document.querySelector('input[name="foto"]');
            const editFotoPreview = document.getElementById('foto-preview-edit');
            const editFotoErr = document.getElementById('foto-error-edit');
            const MAX_SIZE = 1024 * 1024;
            function previewImage(file, imgEl, errEl) {
                errEl.classList.add('hidden');
                errEl.textContent = '';
                if (!file) {
                    if (imgEl) imgEl.classList.add('hidden');
                    return;
                }
                if (file.size > MAX_SIZE) {
                    errEl.textContent = 'Ukuran file melebihi 1MB';
                    errEl.classList.remove('hidden');
                    if (imgEl) imgEl.classList.add('hidden');
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    if (imgEl) {
                        imgEl.src = e.target.result;
                        imgEl.classList.remove('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
            if (editFotoInput) {
                editFotoInput.addEventListener('change', () => {
                    const file = editFotoInput.files && editFotoInput.files[0];
                    previewImage(file, editFotoPreview, editFotoErr);
                });
                const editForm = document.querySelector('form');
                if (editForm) {
                    editForm.addEventListener('submit', (e) => {
                        const file = editFotoInput.files && editFotoInput.files[0];
                        if (file && file.size > MAX_SIZE) {
                            e.preventDefault();
                            previewImage(file, editFotoPreview, editFotoErr);
                        }
                    });
                }
            }
        })();
    </script>
@endsection