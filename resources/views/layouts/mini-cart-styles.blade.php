<style>
.swe-mini-cart-shell #cart-badge{background:var(--swe-palette-hex-641b2a,#641b2a)}
.swe-mini-cart-shell>a:hover,.swe-mini-cart-shell:focus-within>a{background:var(--swe-palette-hex-f1e8de,#f1e8de);color:var(--swe-palette-hex-641b2a,#641b2a)}
.swe-mini-cart{width:360px;max-width:calc(100vw - 32px);background:var(--swe-palette-hex-fffdf9,#fffdf9);border:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);border-radius:8px;box-shadow:0 14px 40px var(--swe-palette-hex-35252a24,#35252a24);color:var(--swe-palette-hex-35252a,#35252a);font-family:'DM Sans',sans-serif;overflow:hidden}
.swe-mini-cart:before{content:'';position:absolute;bottom:100%;height:12px;left:0;right:0}
.swe-mini-cart-shell:focus-within .swe-mini-cart{opacity:1;visibility:visible}
.swe-mini-cart-heading{padding:17px 18px 13px;border-bottom:1px solid var(--swe-palette-hex-eadfd4,#eadfd4);display:flex;flex-direction:column;gap:5px}
.swe-mini-cart-heading>span:first-child{font-family:'Playfair Display',Georgia,serif;font-size:22px;color:var(--swe-palette-hex-641b2a,#641b2a)}
.swe-mini-cart-caption{font-size:8px;letter-spacing:.16em;color:var(--swe-palette-hex-997957,#997957)}
.swe-mini-cart #mini-cart-items{max-height:min(280px,42vh);overscroll-behavior:contain;scrollbar-width:thin;scrollbar-color:var(--swe-palette-hex-b78a54,#b78a54) var(--swe-palette-hex-f1e8de,#f1e8de)}
.swe-mini-cart .swe-mini-cart-item{padding:16px 18px;align-items:flex-start;gap:12px;border-bottom:1px solid var(--swe-palette-hex-f1e8de,#f1e8de)}
.swe-mini-cart .swe-mini-cart-item:last-child{border-bottom:0}
.swe-mini-cart-item .flex-1{min-width:0}
.swe-mini-cart-item p:first-child{font-size:12px;line-height:1.6;font-weight:500;overflow-wrap:anywhere}
.swe-mini-cart-item p+p{font-size:10px;color:var(--swe-palette-hex-806b60,#806b60);margin-top:5px;line-height:1.7}
.swe-mini-cart-placeholder,.swe-mini-cart-image{width:46px;height:52px;flex-shrink:0;border-radius:5px;border:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}
.swe-mini-cart-placeholder{background:linear-gradient(135deg,var(--swe-palette-hex-eee1d2,#eee1d2),var(--swe-palette-hex-d8bfa7,#d8bfa7));color:var(--swe-palette-hex-641b2a,#641b2a);font-family:'Playfair Display',Georgia,serif;font-size:22px}
.swe-mini-cart .remove-mini-item{color:var(--swe-palette-hex-875c4c,#875c4c);font-size:10px;flex-shrink:0;padding:4px 0;cursor:pointer}
.swe-mini-cart .remove-mini-item:hover{color:var(--swe-palette-hex-641b2a,#641b2a);text-decoration:underline}
.swe-mini-cart-footer{padding:15px 18px 18px;background:var(--swe-palette-hex-f8f1e8,#f8f1e8);border-top:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);display:flex;flex-direction:column;gap:14px}
.swe-mini-cart-footer>div:first-child{display:flex;align-items:center;justify-content:space-between;gap:12px}
.swe-mini-cart-footer>div:first-child>p:first-child{font-size:11px;color:var(--swe-palette-hex-806b60,#806b60)}
.swe-mini-cart #mini-cart-total{font-size:16px;font-weight:600;color:var(--swe-palette-hex-641b2a,#641b2a)}
.swe-mini-cart-footer>div:last-child{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.swe-mini-cart-secondary,.swe-mini-cart-primary{display:flex;align-items:center;justify-content:center;min-height:40px;border-radius:4px;font-size:11px;font-weight:500;transition:background .2s}
.swe-mini-cart-secondary{border:1px solid var(--swe-palette-hex-cbb5a0,#cbb5a0);background:var(--swe-palette-hex-fffdf9,#fffdf9);color:var(--swe-palette-hex-641b2a,#641b2a)}
.swe-mini-cart-secondary:hover{background:var(--swe-palette-hex-ede0d2,#ede0d2)}
.swe-mini-cart-primary{background:var(--swe-palette-hex-641b2a,#641b2a);border:1px solid var(--swe-palette-hex-641b2a,#641b2a);color:#fff}
.swe-mini-cart-primary:hover{background:var(--swe-palette-hex-4d1421,#4d1421)}
.swe-mini-cart-empty{padding:28px 18px;text-align:center;font-size:13px;color:var(--swe-palette-hex-641b2a,#641b2a);line-height:1.9}
.swe-mini-cart-empty span{font-size:11px;color:var(--swe-palette-hex-806b60,#806b60)}
.swe-mini-cart :is(a,button):focus-visible{outline:2px solid var(--swe-palette-hex-b78a54,#b78a54);outline-offset:2px}
@media(max-width:767px){.swe-mini-cart{position:fixed;top:64px;right:16px;margin-top:8px;width:360px}}
</style>
