<style>
.swe-gallery-page .swe-schedule-hero .swe-btn{margin-top:26px}
.swe-gallery-hero-note{padding-left:40px;border-left:1px solid #ffffff30}
.swe-gallery-page .swe-gallery-hero-note>p{font-family:'Playfair Display',Georgia,serif;font-size:42px;line-height:1.25;margin:20px 0;color:#fff}
.swe-gallery-hero-note em{color:var(--swe-palette-hex-e6c7a2,#e6c7a2)}
.swe-gallery-hero-note>span:last-child{font-size:10px;color:var(--swe-palette-hex-e0c0b2,#e0c0b2);letter-spacing:.06em}
.swe-gallery-page .swe-section-top>p{max-width:330px;margin-bottom:0}
.swe-gallery-toolbar{display:flex;align-items:center;justify-content:space-between;gap:30px;padding:25px 0;margin-bottom:30px;border-top:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}
.swe-gallery-toolbar>div{min-width:0}
.swe-gallery-toolbar>div>p{font-size:14px;margin-top:7px;line-height:1.8}
.swe-gallery-toolbar>div>p>span{display:inline-block;font-size:10px;padding:3px 9px;margin-left:8px;border:1px solid var(--swe-palette-hex-d6c3b4,#d6c3b4);border-radius:20px;color:var(--swe-palette-hex-796969,#796969);white-space:nowrap}
.swe-gallery-filter{flex-shrink:0;max-width:50%}
.swe-gallery-filter label{display:block;font-size:10px;color:var(--swe-palette-hex-796969,#796969);margin-bottom:8px}
.swe-gallery-filter>div{display:flex;gap:10px}
.swe-gallery-filter select{font:inherit;font-size:11px;color:var(--ink);min-height:45px;width:270px;max-width:100%;border:1px solid var(--swe-palette-hex-d6c3b4,#d6c3b4);border-radius:3px;background:#fff;padding:10px 30px 10px 12px}
.swe-gallery-filter select:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
.swe-gallery-filter .swe-btn{font-size:11px;padding:11px 16px;min-height:45px;gap:15px}
.swe-gallery-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:34px 25px}
.swe-gallery-item{margin:0;min-width:0}
.swe-gallery-photo{display:block;position:relative;aspect-ratio:4/3;background:var(--swe-palette-hex-e6d8cb,#e6d8cb);overflow:hidden}
.swe-gallery-photo img{width:100%;height:100%;object-fit:cover;transition:transform .35s}
.swe-gallery-photo:hover img{transform:scale(1.04)}
.swe-photo-expand{position:absolute;right:14px;bottom:14px;background:var(--swe-palette-hex-faf7f2ef,#faf7f2ef);color:var(--wine);width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:20px}
.swe-gallery-item figcaption{padding-top:14px}
.swe-gallery-item figcaption>span{display:block;color:var(--swe-palette-hex-94745f,#94745f);font-size:9px;line-height:1.8;letter-spacing:.03em}
.swe-gallery-item h3{font-size:15px;font-weight:500;line-height:1.6;margin-top:6px;overflow-wrap:anywhere}
.swe-gallery-item figcaption p{color:var(--swe-palette-hex-796969,#796969);font-size:10px;margin-top:6px}
.swe-gallery-unavailable{aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;flex-direction:column;background:var(--swe-palette-hex-eaded2,#eaded2);color:var(--swe-palette-hex-a17b69,#a17b69)}
.swe-gallery-unavailable>span{font-family:'Playfair Display',Georgia,serif;font-size:48px;letter-spacing:.12em}
.swe-gallery-unavailable p{font-size:10px;letter-spacing:.04em;margin-top:12px}
.swe-gallery-pagination{margin-top:45px}
.swe-gallery-load-failed img{visibility:hidden}
.swe-photo-failure{position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:12px;color:var(--wine)}
.swe-lightbox{font-family:'DM Sans',sans-serif;width:min(1180px,calc(100% - 40px));max-width:none;max-height:calc(100dvh - 40px);padding:22px;background:var(--swe-palette-hex-29161f,#29161f);color:#fff;border:1px solid var(--swe-palette-hex-76545c,#76545c);border-radius:6px;margin:auto;overflow:auto}
.swe-lightbox::backdrop{background:var(--swe-palette-hex-160a12e8,#160a12e8)}
.swe-lightbox button:focus-visible,.swe-lightbox a:focus-visible{outline:2px solid var(--swe-palette-hex-e6c7a2,#e6c7a2);outline-offset:4px}
.swe-lightbox [hidden]{display:none!important}
.swe-lightbox-top{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:18px;font-size:11px;color:var(--swe-palette-hex-e0c0b2,#e0c0b2)}
.swe-lightbox button{font:inherit;background:transparent;color:var(--swe-palette-hex-f6eadb,#f6eadb);border:1px solid var(--swe-palette-hex-94727c,#94727c);border-radius:3px;cursor:pointer;min-height:42px;padding:8px 14px}
.swe-lightbox button:hover{background:#ffffff15}
.swe-lightbox-top button{display:flex;gap:18px;align-items:center;font-size:11px}
.swe-lightbox-top button span{font-size:23px}
.swe-lightbox-stage{display:flex;align-items:center;justify-content:center;gap:16px}
.swe-lightbox-stage>button{font-size:20px;flex-shrink:0}
.swe-lightbox-image-wrap{display:flex;align-items:center;justify-content:center;min-width:0;flex:1;min-height:180px}
.swe-lightbox-image-wrap img{max-width:100%;max-height:65dvh;object-fit:contain}
.swe-lightbox-image-wrap p{font-size:13px;line-height:1.9;padding:40px;text-align:center;color:var(--swe-palette-hex-e0c0b2,#e0c0b2)}
.swe-lightbox-bottom{display:flex;align-items:flex-end;justify-content:space-between;gap:25px;padding-top:20px}
.swe-lightbox-bottom p{font-size:10px;line-height:1.8;color:var(--swe-palette-hex-cfafa3,#cfafa3)}
.swe-lightbox-bottom h2{font-family:'Playfair Display',Georgia,serif;font-size:25px;font-weight:400;line-height:1.4;margin-top:7px}
.swe-lightbox-bottom>a{color:var(--swe-palette-hex-e6c7a2,#e6c7a2);font-size:11px;white-space:nowrap}
@media(max-width:1050px){.swe-gallery-toolbar{align-items:flex-start}.swe-gallery-filter select{width:210px}.swe-gallery-grid{gap:28px 20px}.swe-gallery-filter>div{flex-wrap:wrap}}
@media(max-width:767px){.swe-gallery-hero-note{border-left:0;border-top:1px solid #ffffff30;padding:25px 0 0}.swe-gallery-page .swe-gallery-hero-note>p{font-size:32px}.swe-gallery-page .swe-section-top>p{margin-top:20px}.swe-gallery-toolbar{display:block;padding-block:23px}.swe-gallery-filter{margin-top:20px;max-width:none}.swe-gallery-filter>div{flex-wrap:nowrap}.swe-gallery-filter select{flex:1;min-width:0;width:100%}.swe-gallery-filter .swe-btn{flex-shrink:0;padding-inline:12px;gap:10px}.swe-gallery-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:25px 15px}.swe-gallery-item h3{font-size:12px}.swe-gallery-item figcaption>span{font-size:8px}.swe-gallery-unavailable>span{font-size:32px}.swe-gallery-unavailable p{font-size:9px}.swe-photo-expand{right:8px;bottom:8px;width:26px;height:26px;font-size:17px}.swe-lightbox{width:calc(100% - 20px);padding:14px;max-height:calc(100dvh - 20px)}.swe-lightbox-stage{gap:7px}.swe-lightbox-stage>button{padding:5px 9px;min-height:40px;font-size:17px}.swe-lightbox-bottom{display:block}.swe-lightbox-bottom>a{display:inline-block;margin-top:17px}.swe-lightbox-bottom h2{font-size:21px}.swe-lightbox-image-wrap img{max-height:55dvh}.swe-lightbox-image-wrap p{padding:15px;font-size:11px}.swe-gallery-page .swe-section{padding-block:45px}}
</style>
