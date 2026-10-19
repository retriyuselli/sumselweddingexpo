<style>
.swe-design-theme{display:flex;justify-content:space-between;align-items:center;gap:18px;padding:20px;border:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);border-radius:5px;margin-bottom:25px}
.swe-design-label{font-size:8px;letter-spacing:.14em;color:var(--swe-palette-hex-997957,#997957)}.swe-design-theme h3{font-family:'Playfair Display',Georgia,serif;font-size:25px;font-weight:400;margin:5px 0;color:var(--swe-palette-hex-641b2a,#641b2a)}.swe-design-theme p{font-size:11px;color:var(--swe-palette-hex-796969,#796969);margin:0}.swe-design-swatches{display:flex;flex-shrink:0;gap:6px}.swe-design-swatches span{width:22px;height:32px;border:1px solid var(--swe-palette-hex-ad8e7740,#ad8e7740);border-radius:4px}
html[data-color-theme=dark] .swe-design-theme{border-color:var(--swe-palette-hex-514047,#514047)}html[data-color-theme=dark] .swe-design-theme h3{color:var(--swe-palette-hex-e8bf8f,#e8bf8f)}html[data-color-theme=dark] .swe-design-theme p{color:var(--swe-palette-hex-cfbcb2,#cfbcb2)}

#appearance input[name=theme]:checked+label{border-color:var(--swe-palette-hex-b78a54,#b78a54)!important;background:var(--swe-palette-hex-f1e8de,#f1e8de)!important;color:var(--swe-palette-hex-641b2a,#641b2a)}
#appearance input[name=theme]:focus-visible+label{outline:2px solid var(--swe-palette-hex-b78a54,#b78a54);outline-offset:3px}
html[data-color-theme=dark] body{background:var(--swe-palette-hex-1d1719,#1d1719);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] :is(.swe-home,.swe-unified){--cream:var(--swe-palette-hex-1d1719,#1d1719);--ink:var(--swe-palette-hex-efdfd6,#efdfd6);--swe-cream:var(--swe-palette-hex-1d1719,#1d1719);--swe-ink:var(--swe-palette-hex-efdfd6,#efdfd6);--swe-line:var(--swe-palette-hex-514047,#514047);background:var(--swe-palette-hex-1d1719,#1d1719);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] header{background:var(--swe-palette-hex-241c20f5,#241c20f5);border-color:var(--swe-palette-hex-514047,#514047)}
html[data-color-theme=dark] header :is(nav a,nav button,.text-neutral-700,.text-neutral-900){color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] header :is(a[aria-current=page],[data-nav-active=true]){color:var(--swe-palette-hex-e8bf8f,#e8bf8f)}
html[data-color-theme=dark] header>a,html[data-color-theme=dark] header a>span{color:inherit}
html[data-color-theme=dark] header a[href="/"] span{color:var(--swe-palette-hex-e8bf8f,#e8bf8f)!important}
html[data-color-theme=dark] :is(header .dropdown-menu,#mobile-menu,#mobile-profile-menu){background:var(--swe-palette-hex-30252a,#30252a);border-color:var(--swe-palette-hex-514047,#514047);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] header :is(a,button,summary):hover{color:var(--swe-palette-hex-e8bf8f,#e8bf8f)}
html[data-color-theme=dark] header :is(.hover\:bg-neutral-50,.hover\:bg-rose-50):hover{background:var(--swe-palette-hex-44313a,#44313a)}
html[data-color-theme=dark] footer{background:var(--swe-palette-hex-241c20,#241c20);border-color:var(--swe-palette-hex-514047,#514047);color:var(--swe-palette-hex-cfbcb2,#cfbcb2)}
html[data-color-theme=dark] footer a,html[data-color-theme=dark] footer .text-neutral-600{color:var(--swe-palette-hex-cfbcb2,#cfbcb2)}
html[data-color-theme=dark] main :is(.bg-white,.bg-gray-50,.bg-neutral-50,.bg-rose-50,.bg-purple-50,.bg-blue-50,.bg-indigo-50):not(.swe-floorplan *):not(.swe-page-hero){background:var(--swe-palette-hex-2b2226,#2b2226);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] main :is(.text-neutral-900,.text-neutral-800,.text-neutral-700,.text-gray-900,.text-gray-800):not(.swe-floorplan *){color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] main :is(.text-neutral-600,.text-neutral-500,.text-gray-600,.text-gray-500):not(.swe-floorplan *){color:var(--swe-palette-hex-c4afa6,#c4afa6)}
html[data-color-theme=dark] main :is(.border-neutral-200,.border-neutral-300,.border-gray-200){border-color:var(--swe-palette-hex-514047,#514047)}
html[data-color-theme=dark] main :is(input:not([type=checkbox]):not([type=radio]),select,textarea){background:var(--swe-palette-hex-211a1e,#211a1e);color:var(--swe-palette-hex-efdfd6,#efdfd6);border-color:var(--swe-palette-hex-76605c,#76605c)}
html[data-color-theme=dark] main :is(input,textarea)::placeholder{color:var(--swe-palette-hex-ac9690,#ac9690)}
html[data-color-theme=dark] main :is(.swe-vendors,.swe-location,.swe-travel-card,.swe-partner-card,.swe-product-card,.swe-product-vendor-card,.swe-purchase-card,.swe-vendor-contact-card,.swe-visit-card,.swe-direction-card,.swe-memories,.swe-empty,.swe-gallery-unavailable){background:var(--swe-palette-hex-2b2226,#2b2226);border-color:var(--swe-palette-hex-514047,#514047);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] main :is(.swe-vendor-image,.swe-monogram,.swe-partner-monogram,.swe-product-placeholder){background:var(--swe-palette-hex-44313a,#44313a);color:var(--swe-palette-hex-e8bf8f,#e8bf8f)}
html[data-color-theme=dark] main :is(.swe-intro-copy,.swe-benefits p,.swe-section-top p,.swe-vendor-info p,.swe-text-link,.swe-partner-card p,.swe-travel-card p,.swe-product-card p){color:var(--swe-palette-hex-cfbcb2,#cfbcb2)}
html[data-color-theme=dark] main :is(.swe-vendor-info h3,.swe-section h2,.swe-section h3,.swe-partner-card h3,.swe-product-card h3){color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] main :is(.swe-quick,.swe-quick a,.swe-benefits>div,.swe-vendor-info){border-color:var(--swe-palette-hex-514047,#514047);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] .swe-unified .swe-danger-zone{border-color:var(--swe-palette-hex-98615e,#98615e)}
html[data-color-theme=dark] .swe-unified .swe-danger-panel{background:var(--swe-palette-hex-3d292e,#3d292e);border-color:var(--swe-palette-hex-704b4c,#704b4c)}
html[data-color-theme=dark] .swe-unified .swe-danger-zone h2{color:var(--swe-palette-hex-e2aaa0,#e2aaa0)}
html[data-color-theme=dark] body :is(a,button).swe-action-danger{background:var(--swe-palette-hex-38262c,#38262c);border-color:var(--swe-palette-hex-bd8581,#bd8581);color:var(--swe-palette-hex-f0c0b4,#f0c0b4)}
html[data-color-theme=dark] body :is(a,button).swe-action-danger:hover{background:var(--swe-palette-hex-782d32,#782d32);color:#fff}
html[data-color-theme=dark] #appearance input[name=theme]:checked+label{background:var(--swe-palette-hex-4c3039,#4c3039)!important;color:var(--swe-palette-hex-f4d7ba,#f4d7ba)}
html[data-color-theme=dark] :is(.swe-mini-cart,.swe-mini-cart-heading){background:var(--swe-palette-hex-30252a,#30252a);border-color:var(--swe-palette-hex-514047,#514047);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] .swe-mini-cart :is(.swe-mini-cart-heading>span:first-child,#mini-cart-total,.text-neutral-900){color:var(--swe-palette-hex-f4d7ba,#f4d7ba)}
html[data-color-theme=dark] .swe-mini-cart-footer{background:var(--swe-palette-hex-241c20,#241c20);border-color:var(--swe-palette-hex-514047,#514047)}
html[data-color-theme=dark] .swe-mini-cart :is(p+p,.swe-mini-cart-caption,.remove-mini-item){color:var(--swe-palette-hex-cfbcb2,#cfbcb2)}
html[data-color-theme=dark] .swe-unified table thead{background:var(--swe-palette-hex-44313a,#44313a)}
html[data-color-theme=dark] .swe-unified :is(.bg-linear-to-r,.bg-linear-to-br,.bg-gradient-to-r,.bg-gradient-to-br):not(.swe-page-hero):not(.swe-floorplan *):not(.swe-page-hero *){background:var(--swe-palette-hex-44313a,#44313a);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] main :is(.swe-eyebrow,h2 em,.swe-monogram,.swe-partners-reset,.swe-btn-outline){color:var(--swe-palette-hex-e8bf8f,#e8bf8f)}
html[data-color-theme=dark] main :is(.swe-intro-copy>p,.swe-section-top p,.swe-benefits p,.swe-gallery-item figcaption>span,.swe-timeline p,.swe-org-copy p){color:var(--swe-palette-hex-cfbcb2,#cfbcb2)}
html[data-color-theme=dark] main :is(.swe-product-steps,.swe-venue-grid,.swe-venue-details,.swe-venue-map,.swe-map-caption,.swe-travel,.swe-org-documentation,.swe-org-photo-empty,.swe-team-portrait,.swe-team-empty,.swe-org-contact,.swe-vendor-identity,.swe-expo-note,.swe-dp-block,.swe-product-visual,.swe-partner-art,.swe-product-photo,.swe-gallery-photo){background:var(--swe-palette-hex-30252a,#30252a);border-color:var(--swe-palette-hex-514047,#514047);color:var(--swe-palette-hex-efdfd6,#efdfd6)}
html[data-color-theme=dark] main :is(.swe-stage,.swe-appointment-status,.swe-detail-error){background:var(--swe-palette-hex-44313a,#44313a);color:var(--swe-palette-hex-f4d7ba,#f4d7ba)}
html[data-color-theme=dark] .swe-unified .swe-profile-link,html[data-color-theme=dark] .swe-unified .swe-participant-card:hover h3{color:var(--swe-palette-hex-e8bf8f,#e8bf8f)}
@media print{html[data-color-theme=dark] body,html[data-color-theme=dark] main{background:#fff!important;color:#000!important}}

.swe-design-picker{border:0;padding:0;margin:0 0 22px;min-width:0}.swe-design-options{display:grid;gap:12px}.swe-design-picker .swe-design-theme{margin:0;cursor:pointer}.swe-design-picker input:checked+label{border-color:#b78a54;box-shadow:inset 0 0 0 1px #b78a54}.swe-design-picker input:focus-visible+label{outline:2px solid #b78a54;outline-offset:3px}.swe-design-picker h3{font-size:21px}
</style>
