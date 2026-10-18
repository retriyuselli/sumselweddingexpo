    <script>
        (function() {
            const btnOpen = document.getElementById('btn-open-add-product');
            const formWrap = document.getElementById('add-product-form');
            if (btnOpen && formWrap) {
                btnOpen.addEventListener('click', () => {
                    formWrap.classList.toggle('hidden');
                });
            }

            const rte = document.getElementById('rich-desc');
            const rteHidden = document.getElementById('rich-desc-textarea');
            const rteBtns = document.querySelectorAll('#add-product-form [data-cmd]');
            const linkBtn = document.getElementById('btn-insert-link');
            const formEl = document.querySelector('#add-product-form form');
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

            const createFotoInput = document.querySelector('#add-product-form input[name="foto"]');
            const createFotoPreview = document.getElementById('foto-preview-create');
            const createFotoErr = document.getElementById('foto-error-create');
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
            if (createFotoInput) {
                createFotoInput.addEventListener('change', () => {
                    const file = createFotoInput.files && createFotoInput.files[0];
                    previewImage(file, createFotoPreview, createFotoErr);
                });
                const addForm = document.querySelector('#add-product-form form');
                if (addForm) {
                    addForm.addEventListener('submit', (e) => {
                        const file = createFotoInput.files && createFotoInput.files[0];
                        if (file && file.size > MAX_SIZE) {
                            e.preventDefault();
                            previewImage(file, createFotoPreview, createFotoErr);
                        }
                    });
                }
            }

            // Edit dipindahkan ke halaman terpisah

            function readCart() {
                try {
                    return JSON.parse(localStorage.getItem('cartItems') || '[]');
                } catch (e) {
                    return [];
                }
            }

            function writeCart(items) {
                localStorage.setItem('cartItems', JSON.stringify(items));
                window.dispatchEvent(new Event('storage'));
            }

            function addItem(data) {
                const items = readCart();
                const idx = items.findIndex(i => i.product_vendor_id === data.product_vendor_id);
                if (idx >= 0) {
                    items[idx].qty = (items[idx].qty || 1) + 1;
                } else {
                    items.push(data);
                }
                writeCart(items);
            }

            const modal = document.getElementById('cart-modal');
            const modalImg = document.getElementById('cart-modal-img');
            const modalName = document.getElementById('cart-modal-name');
            const modalVendor = document.getElementById('cart-modal-vendor');
            const modalClose = document.getElementById('cart-modal-close');

            function openCartModal(data) {
                if (data.img) {
                    modalImg.src = data.img;
                    modalImg.classList.remove('hidden');
                } else {
                    modalImg.src = '';
                    modalImg.classList.add('hidden');
                }
                modalName.textContent = data.nama_produk || '';
                modalVendor.textContent = data.vendor_nama || '';
                modal.classList.remove('hidden');
            }

            function closeCartModal() {
                modal.classList.add('hidden');
            }

            if (modalClose) {
                modalClose.addEventListener('click', closeCartModal);
            }
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeCartModal();
                    }
                });
            }

            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', () => {
                    const data = {
                        product_vendor_id: parseInt(btn.dataset.id, 10),
                        qty: 1,
                        vendor_id: parseInt(btn.dataset.vendor, 10),
                        nama_produk: btn.dataset.nama || '',
                        harga: parseInt(btn.dataset.harga || '0', 10),
                        img: btn.dataset.img || null,
                        slug: btn.dataset.slug || '',
                        vendor_nama: @json($vendor->nama_vendor ?? ''),
                        dp_fixed: parseInt(btn.dataset.dpFixed || '0', 10)
                    };
                    addItem(data);
                    openCartModal(data);
                });
            });
        })();
    </script>