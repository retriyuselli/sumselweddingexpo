<style>
.swe-mini-cart-shell #cart-badge{background:#641b2a}
.swe-mini-cart-shell>a:hover,.swe-mini-cart-shell:focus-within>a{background:#f1e8de;color:#641b2a}
.swe-mini-cart{width:360px;max-width:calc(100vw - 32px);background:#fffdf9;border:1px solid #ddcfc1;border-radius:8px;box-shadow:0 14px 40px #35252a24;color:#35252a;font-family:'DM Sans',sans-serif;overflow:hidden}
.swe-mini-cart:before{content:'';position:absolute;bottom:100%;height:12px;left:0;right:0}
.swe-mini-cart-shell:focus-within .swe-mini-cart{opacity:1;visibility:visible}
.swe-mini-cart-heading{padding:17px 18px 13px;border-bottom:1px solid #eadfd4;display:flex;flex-direction:column;gap:5px}
.swe-mini-cart-heading>span:first-child{font-family:'Playfair Display',Georgia,serif;font-size:22px;color:#641b2a}
.swe-mini-cart-caption{font-size:8px;letter-spacing:.16em;color:#997957}
.swe-mini-cart #mini-cart-items{max-height:min(280px,42vh);overscroll-behavior:contain;scrollbar-width:thin;scrollbar-color:#b78a54 #f1e8de}
.swe-mini-cart .swe-mini-cart-item{padding:16px 18px;align-items:flex-start;gap:12px;border-bottom:1px solid #f1e8de}
.swe-mini-cart .swe-mini-cart-item:last-child{border-bottom:0}
.swe-mini-cart-item .flex-1{min-width:0}
.swe-mini-cart-item p:first-child{font-size:12px;line-height:1.6;font-weight:500;overflow-wrap:anywhere}
.swe-mini-cart-item p+p{font-size:10px;color:#806b60;margin-top:5px;line-height:1.7}
.swe-mini-cart-placeholder,.swe-mini-cart-image{width:46px;height:52px;flex-shrink:0;border-radius:5px;border:1px solid #ddcfc1}
.swe-mini-cart-placeholder{background:linear-gradient(135deg,#eee1d2,#d8bfa7);color:#641b2a;font-family:'Playfair Display',Georgia,serif;font-size:22px}
.swe-mini-cart .remove-mini-item{color:#875c4c;font-size:10px;flex-shrink:0;padding:4px 0;cursor:pointer}
.swe-mini-cart .remove-mini-item:hover{color:#641b2a;text-decoration:underline}
.swe-mini-cart-footer{padding:15px 18px 18px;background:#f8f1e8;border-top:1px solid #ddcfc1;display:flex;flex-direction:column;gap:14px}
.swe-mini-cart-footer>div:first-child{display:flex;align-items:center;justify-content:space-between;gap:12px}
.swe-mini-cart-footer>div:first-child>p:first-child{font-size:11px;color:#806b60}
.swe-mini-cart #mini-cart-total{font-size:16px;font-weight:600;color:#641b2a}
.swe-mini-cart-footer>div:last-child{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.swe-mini-cart-secondary,.swe-mini-cart-primary{display:flex;align-items:center;justify-content:center;min-height:40px;border-radius:4px;font-size:11px;font-weight:500;transition:background .2s}
.swe-mini-cart-secondary{border:1px solid #cbb5a0;background:#fffdf9;color:#641b2a}
.swe-mini-cart-secondary:hover{background:#ede0d2}
.swe-mini-cart-primary{background:#641b2a;border:1px solid #641b2a;color:#fff}
.swe-mini-cart-primary:hover{background:#4d1421}
.swe-mini-cart-empty{padding:28px 18px;text-align:center;font-size:13px;color:#641b2a;line-height:1.9}
.swe-mini-cart-empty span{font-size:11px;color:#806b60}
.swe-mini-cart :is(a,button):focus-visible{outline:2px solid #b78a54;outline-offset:2px}
@media(max-width:767px){.swe-mini-cart{position:fixed;top:64px;right:16px;margin-top:8px;width:360px}}
</style>
