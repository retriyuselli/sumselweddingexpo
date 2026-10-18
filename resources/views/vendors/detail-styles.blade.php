<style>
.swe-vendor-hero-grid{display:grid;grid-template-columns:minmax(0,1fr) 250px;gap:70px;align-items:center}
.swe-vendor-detail .swe-schedule-hero h1{font-size:clamp(38px,4.8vw,66px);overflow-wrap:anywhere;max-width:850px}
.swe-vendor-hero-grid>div>p{max-width:620px;font-size:13px;line-height:1.9;color:#ead6d2}
.swe-vendor-identity{text-align:center;background:#f6eddf;padding:28px 22px;color:var(--wine);border-radius:4px}
.swe-vendor-identity img{width:100%;height:120px;object-fit:contain}
.swe-vendor-identity>span{font-family:'Playfair Display',Georgia,serif;font-size:80px;line-height:1.4}
.swe-vendor-identity>p{color:#805c60!important;font-size:11px!important;margin-top:10px}
.swe-vendor-identity>strong{display:inline-block;font-size:10px;border-top:1px solid #d7bfa9;margin-top:15px;padding-top:13px;font-weight:500}
.swe-detail-nav{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:25px;padding-block:28px;border-bottom:1px solid #ddcfc1}
.swe-detail-nav a{display:flex;gap:20px;color:#9e7d65;font-size:10px;border-right:1px solid #ddcfc1;padding-right:25px}
.swe-detail-nav a:last-child{border:0}.swe-detail-nav a>span{flex:1;color:var(--ink);font-size:12px}
.swe-detail-layout{display:grid;grid-template-columns:minmax(0,1fr) 290px;gap:50px;align-items:start}
.swe-detail-main{min-width:0}.swe-detail-main .swe-section-top{gap:25px}.swe-detail-main .swe-section-top>p{max-width:250px;margin:0;font-size:12px}
.swe-product-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:23px}
.swe-product-card{background:#fff;border:1px solid #e0d1c4;min-width:0;display:flex;flex-direction:column}
.swe-product-photo{aspect-ratio:1.2;display:flex;align-items:center;justify-content:center;background:#e7d9cc;overflow:hidden}
.swe-product-photo img{width:100%;height:100%;object-fit:cover;transition:transform .3s}.swe-product-photo:hover img{transform:scale(1.04)}
.swe-product-photo>span{font-family:'Playfair Display',Georgia,serif;color:#9c6b70;font-size:65px}
.swe-product-body{padding:19px;display:flex;flex-direction:column;flex:1}
.swe-product-body h3{font-family:'Playfair Display',Georgia,serif;font-size:21px;font-weight:400;line-height:1.4;overflow-wrap:anywhere}
.swe-product-price{font-size:14px;color:var(--wine);font-weight:600;margin-top:16px}
.swe-product-dp{font-size:10px;color:#8a7164;margin-top:5px}
.swe-product-actions{display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px;margin-top:auto;padding-top:23px}
.swe-product-actions .swe-text-link{font-size:10px;gap:12px;padding-bottom:5px}
.swe-cart-button{font:inherit;font-size:10px;color:#fff;background:var(--wine);padding:10px 12px;border:0;border-radius:3px;cursor:pointer}
.swe-cart-button:hover{background:#491322}.swe-cart-button:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
.swe-product-edit{font-size:10px;color:var(--wine)}
.swe-vendor-information,.swe-owner-appointments{padding-top:60px;margin-top:55px;border-top:1px solid #ddcfc1}
.swe-vendor-facts{display:grid;grid-template-columns:1fr 1fr;gap:25px 35px;margin-top:30px}
.swe-vendor-facts>div{border-top:1px solid #e1d4c7;padding-top:16px;min-width:0}
.swe-vendor-facts dt{font-size:10px;color:#947663;margin-bottom:8px}
.swe-vendor-facts dd{font-size:13px;line-height:1.8;margin:0;overflow-wrap:anywhere}
.swe-expo-note{font-size:11px;line-height:1.9;color:#796969;background:#eee5da;padding:18px 20px;margin-top:30px}
.swe-vendor-contact-card{padding:27px;background:#eee3d8}
.swe-vendor-contact-card h3{font-family:'Playfair Display',Georgia,serif;font-size:30px;line-height:1.3;letter-spacing:-.025em;margin:17px 0}
.swe-vendor-contact-card>p,.swe-vendor-guide p{font-size:12px;line-height:1.9;color:#796969}
.swe-vendor-contact-card dl{margin-top:22px}.swe-vendor-contact-card dl>div{padding:15px 0;border-top:1px solid #d6bfac}
.swe-vendor-contact-card dt{font-size:9px;color:#947663;margin-bottom:7px}.swe-vendor-contact-card dd{font-size:12px;line-height:1.8;margin:0;overflow-wrap:anywhere}
.swe-vendor-contact-actions{margin-top:22px;display:flex;flex-direction:column;align-items:flex-start;gap:20px}
.swe-vendor-contact-actions .swe-btn{width:100%;font-size:11px;padding-inline:15px;gap:20px}
.swe-vendor-contact-actions .swe-text-link{font-size:11px}
.swe-vendor-guide{padding:25px;border:1px solid #ddcfc1;margin-top:20px}.swe-vendor-guide p{margin-block:15px 20px}.swe-vendor-guide .swe-text-link{font-size:10px;gap:15px}
.swe-owner-appointments article{padding:20px 0;border-top:1px solid #ddcfc1;display:flex;justify-content:space-between;gap:20px;align-items:flex-start}
.swe-owner-appointments h3{font-size:14px;font-weight:500}.swe-owner-appointments article p{font-size:12px;line-height:1.8;margin-top:7px}.swe-owner-appointments article div>span{display:block;color:#8c6c5e;font-size:10px;margin-top:9px}
.swe-appointment-status{font-size:10px;color:var(--wine);padding:6px 9px;background:#e9d8c8;flex-shrink:0}.swe-owner-appointments>.swe-text-link{margin-top:25px}.swe-owner-appointments>p{font-size:13px;color:#796969}.swe-owner-pagination{margin-block:25px}
.swe-product-create #add-product-form{background:#eee4da;padding:24px;margin-bottom:30px;border:1px solid #d6bfac}.swe-product-create button[type=submit]{background:var(--wine)}
.swe-detail-error{font-size:12px;color:var(--wine);padding:20px;background:#efe1d6;margin-bottom:25px}.swe-detail-error ul{list-style:disc;padding-left:20px;margin-top:10px}
#cart-modal button,#cart-modal a{font-family:'DM Sans',sans-serif}#cart-modal a[href$='/cart']{background:#641b2a}
@media(max-width:1100px){.swe-detail-layout{grid-template-columns:minmax(0,1fr) 260px;gap:30px}.swe-product-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.swe-detail-main .swe-section-top{display:block}.swe-detail-main .swe-section-top>p,.swe-detail-main .swe-section-top>button{margin-top:20px;max-width:none}.swe-vendor-hero-grid{gap:40px}.swe-vendor-contact-card{padding:23px}}
@media(max-width:767px){.swe-vendor-hero-grid{grid-template-columns:1fr;gap:28px}.swe-vendor-detail .swe-schedule-hero h1{font-size:41px}.swe-vendor-identity{max-width:210px;width:100%;padding:20px}.swe-vendor-identity>span{font-size:55px}.swe-vendor-identity img{height:90px}.swe-detail-nav{gap:12px;padding-block:23px}.swe-detail-nav a{gap:8px;padding-right:10px;font-size:9px;flex-wrap:wrap}.swe-detail-nav a>span{font-size:10px}.swe-detail-layout{grid-template-columns:1fr;gap:40px}.swe-detail-contact{grid-row:2}.swe-product-grid{gap:18px 14px}.swe-product-body{padding:15px}.swe-product-body h3{font-size:19px}.swe-product-price{font-size:12px}.swe-cart-button{font-size:9px;padding:9px 10px}.swe-vendor-facts{gap:20px}.swe-vendor-information,.swe-owner-appointments{padding-top:35px;margin-top:35px}.swe-vendor-detail .swe-section{padding-block:45px}.swe-vendor-contact-card{padding:28px}.swe-product-create #add-product-form{padding:18px}.swe-owner-appointments article{flex-direction:column;gap:10px}}
@media(max-width:360px){.swe-product-grid{grid-template-columns:1fr}.swe-product-photo{aspect-ratio:1.5}.swe-product-body h3{font-size:23px}.swe-product-price{font-size:14px}}
</style>
