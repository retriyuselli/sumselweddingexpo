<style>
#appearance input[name=theme]:checked+label{border-color:#b78a54!important;background:#f1e8de!important;color:#641b2a}
#appearance input[name=theme]:focus-visible+label{outline:2px solid #b78a54;outline-offset:3px}
html[data-color-theme=dark] body{background:#1d1719;color:#efdfd6}
html[data-color-theme=dark] :is(.swe-home,.swe-unified){--cream:#1d1719;--ink:#efdfd6;--swe-cream:#1d1719;--swe-ink:#efdfd6;--swe-line:#514047;background:#1d1719;color:#efdfd6}
html[data-color-theme=dark] header{background:#241c20f5;border-color:#514047}
html[data-color-theme=dark] header :is(nav a,nav button,.text-neutral-700,.text-neutral-900){color:#efdfd6}
html[data-color-theme=dark] header :is(a[aria-current=page],[data-nav-active=true]){color:#e8bf8f}
html[data-color-theme=dark] header>a,html[data-color-theme=dark] header a>span{color:inherit}
html[data-color-theme=dark] header a[href="/"] span{color:#e8bf8f!important}
html[data-color-theme=dark] :is(header .dropdown-menu,#mobile-menu,#mobile-profile-menu){background:#30252a;border-color:#514047;color:#efdfd6}
html[data-color-theme=dark] header :is(a,button,summary):hover{color:#e8bf8f}
html[data-color-theme=dark] header :is(.hover\:bg-neutral-50,.hover\:bg-rose-50):hover{background:#44313a}
html[data-color-theme=dark] footer{background:#241c20;border-color:#514047;color:#cfbcb2}
html[data-color-theme=dark] footer a,html[data-color-theme=dark] footer .text-neutral-600{color:#cfbcb2}
html[data-color-theme=dark] main :is(.bg-white,.bg-gray-50,.bg-neutral-50,.bg-rose-50,.bg-purple-50,.bg-blue-50,.bg-indigo-50):not(.swe-floorplan *):not(.swe-page-hero){background:#2b2226;color:#efdfd6}
html[data-color-theme=dark] main :is(.text-neutral-900,.text-neutral-800,.text-neutral-700,.text-gray-900,.text-gray-800):not(.swe-floorplan *){color:#efdfd6}
html[data-color-theme=dark] main :is(.text-neutral-600,.text-neutral-500,.text-gray-600,.text-gray-500):not(.swe-floorplan *){color:#c4afa6}
html[data-color-theme=dark] main :is(.border-neutral-200,.border-neutral-300,.border-gray-200){border-color:#514047}
html[data-color-theme=dark] main :is(input:not([type=checkbox]):not([type=radio]),select,textarea){background:#211a1e;color:#efdfd6;border-color:#76605c}
html[data-color-theme=dark] main :is(input,textarea)::placeholder{color:#ac9690}
html[data-color-theme=dark] main :is(.swe-vendors,.swe-location,.swe-travel-card,.swe-partner-card,.swe-product-card,.swe-product-vendor-card,.swe-purchase-card,.swe-vendor-contact-card,.swe-visit-card,.swe-direction-card,.swe-memories,.swe-empty,.swe-gallery-unavailable){background:#2b2226;border-color:#514047;color:#efdfd6}
html[data-color-theme=dark] main :is(.swe-vendor-image,.swe-monogram,.swe-partner-monogram,.swe-product-placeholder){background:#44313a;color:#e8bf8f}
html[data-color-theme=dark] main :is(.swe-intro-copy,.swe-benefits p,.swe-section-top p,.swe-vendor-info p,.swe-text-link,.swe-partner-card p,.swe-travel-card p,.swe-product-card p){color:#cfbcb2}
html[data-color-theme=dark] main :is(.swe-vendor-info h3,.swe-section h2,.swe-section h3,.swe-partner-card h3,.swe-product-card h3){color:#efdfd6}
html[data-color-theme=dark] main :is(.swe-quick,.swe-quick a,.swe-benefits>div,.swe-vendor-info){border-color:#514047;color:#efdfd6}
html[data-color-theme=dark] .swe-unified .swe-danger-zone{border-color:#98615e}
html[data-color-theme=dark] .swe-unified .swe-danger-panel{background:#3d292e;border-color:#704b4c}
html[data-color-theme=dark] .swe-unified .swe-danger-zone h2{color:#e2aaa0}
html[data-color-theme=dark] body :is(a,button).swe-action-danger{background:#38262c;border-color:#bd8581;color:#f0c0b4}
html[data-color-theme=dark] body :is(a,button).swe-action-danger:hover{background:#782d32;color:#fff}
html[data-color-theme=dark] #appearance input[name=theme]:checked+label{background:#4c3039!important;color:#f4d7ba}
html[data-color-theme=dark] :is(.swe-mini-cart,.swe-mini-cart-heading){background:#30252a;border-color:#514047;color:#efdfd6}
html[data-color-theme=dark] .swe-mini-cart :is(.swe-mini-cart-heading>span:first-child,#mini-cart-total,.text-neutral-900){color:#f4d7ba}
html[data-color-theme=dark] .swe-mini-cart-footer{background:#241c20;border-color:#514047}
html[data-color-theme=dark] .swe-mini-cart :is(p+p,.swe-mini-cart-caption,.remove-mini-item){color:#cfbcb2}
html[data-color-theme=dark] .swe-unified table thead{background:#44313a}
html[data-color-theme=dark] .swe-unified :is(.bg-linear-to-r,.bg-linear-to-br,.bg-gradient-to-r,.bg-gradient-to-br):not(.swe-page-hero):not(.swe-floorplan *):not(.swe-page-hero *){background:#44313a;color:#efdfd6}
html[data-color-theme=dark] main :is(.swe-eyebrow,h2 em,.swe-monogram,.swe-partners-reset,.swe-btn-outline){color:#e8bf8f}
html[data-color-theme=dark] main :is(.swe-intro-copy>p,.swe-section-top p,.swe-benefits p,.swe-gallery-item figcaption>span,.swe-timeline p,.swe-org-copy p){color:#cfbcb2}
html[data-color-theme=dark] main :is(.swe-product-steps,.swe-venue-grid,.swe-venue-details,.swe-venue-map,.swe-map-caption,.swe-travel,.swe-org-documentation,.swe-org-photo-empty,.swe-team-portrait,.swe-team-empty,.swe-org-contact,.swe-vendor-identity,.swe-expo-note,.swe-dp-block,.swe-product-visual,.swe-partner-art,.swe-product-photo,.swe-gallery-photo){background:#30252a;border-color:#514047;color:#efdfd6}
html[data-color-theme=dark] main :is(.swe-stage,.swe-appointment-status,.swe-detail-error){background:#44313a;color:#f4d7ba}
html[data-color-theme=dark] .swe-unified .swe-profile-link,html[data-color-theme=dark] .swe-unified .swe-participant-card:hover h3{color:#e8bf8f}
@media print{html[data-color-theme=dark] body,html[data-color-theme=dark] main{background:#fff!important;color:#000!important}}
</style>
