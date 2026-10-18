                        @if (auth()->check() && (int) auth()->id() === (int) ($vendor->user_id ?? 0))
                            <div id="add-product-form" class="hidden mb-4">
                                <form method="POST" action="{{ route('vendors.products.store', $vendor) }}"
                                    enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @csrf
                                    <div>
                                        <label class="text-xs text-neutral-600">Nama Produk</label>
                                        <input name="nama_produk" type="text" required
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                            placeholder="Contoh: Paket Wedding Basic" value="{{ old('nama_produk') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-neutral-600">Harga</label>
                                        <input name="harga" type="number" min="0" required
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                            placeholder="120000000" value="{{ old('harga') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-neutral-600">DP (opsional)</label>
                                        <input name="dp_fixed" type="number" min="0"
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm"
                                            placeholder="10000000" value="{{ old('dp_fixed') }}">
                                    </div>
                                    <div>
                                        <label class="text-xs text-neutral-600">Foto Produk (opsional)</label>
                                        <input name="foto" type="file" accept="image/*"
                                            class="mt-1 w-full rounded-lg border border-neutral-300 px-3 py-2 text-sm">
                                        <p class="mt-1 text-[11px] text-neutral-500">Max 1MB</p>
                                        <img id="foto-preview-create" class="mt-2 h-20 w-20 object-cover rounded hidden"
                                            alt="Preview">
                                        <p id="foto-error-create" class="mt-1 text-[11px] text-rose-600 hidden"></p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="text-xs text-neutral-600">Deskripsi (opsional)</label>
                                        <div class="mt-1 rounded-lg border border-neutral-300">
                                            <div
                                                class="flex items-center gap-1 p-2 border-b border-neutral-200 bg-neutral-50">
                                                <button type="button" data-cmd="bold"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">B</button>
                                                <button type="button" data-cmd="italic"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300"><em>I</em></button>
                                                <button type="button" data-cmd="underline"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300"><u>U</u></button>
                                                <span class="mx-2 h-4 w-px bg-neutral-300"></span>
                                                <button type="button" data-cmd="insertUnorderedList"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">•
                                                    List</button>
                                                <button type="button" data-cmd="insertOrderedList"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">1.
                                                    List</button>
                                                <button type="button" id="btn-insert-link"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">Link</button>
                                                <span class="mx-2 h-4 w-px bg-neutral-300"></span>
                                                <button type="button" data-cmd="removeFormat"
                                                    class="px-2 py-1 text-xs rounded border border-neutral-300">Clear</button>
                                            </div>
                                            <div id="rich-desc" class="min-h-[120px] p-3 text-sm" contenteditable="true"
                                                placeholder="Detail paket, fasilitas, dll."></div>
                                            <textarea id="rich-desc-textarea" name="deskripsi" class="hidden">{{ old('deskripsi') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 sm:col-span-2">
                                        <label class="inline-flex items-center gap-2 text-xs text-neutral-700"><input
                                                type="checkbox" name="is_active" value="1" class="rounded" checked>
                                            Aktifkan produk</label>
                                        <button type="submit"
                                            class="ml-auto px-3 py-1.5 rounded-lg text-white text-xs swe-action-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        @endif
