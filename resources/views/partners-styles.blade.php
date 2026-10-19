<style>
.swe-partners-page .swe-schedule-hero h1{font-size:clamp(40px,4.8vw,66px)}
.swe-partners-total{display:block;font-size:12px;color:var(--swe-palette-hex-e6c7a2,#e6c7a2);margin-top:20px;letter-spacing:.04em}
.swe-partners-page .swe-section-top>p{max-width:330px;margin:0}
.swe-partners-filters{display:flex;align-items:flex-end;gap:20px;border-top:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding:25px 0}
.swe-partners-filters label{display:block;font-size:10px;color:var(--swe-palette-hex-796969,#796969);margin-bottom:9px}
.swe-partners-search{flex:1;min-width:0}
.swe-partners-search>div{position:relative}
.swe-partners-search svg{position:absolute;left:14px;top:14px;width:18px;height:18px;color:var(--swe-palette-hex-9e7f70,#9e7f70)}
.swe-partners-search input,.swe-partners-category select{font:inherit;color:var(--ink);font-size:12px;height:47px;width:100%;border:1px solid var(--swe-palette-hex-d6c3b4,#d6c3b4);border-radius:3px;background:#fff}
.swe-partners-search input{padding:12px 16px 12px 42px}
.swe-partners-search input::placeholder{color:var(--swe-palette-hex-9a8780,#9a8780)}
.swe-partners-search input:focus-visible,.swe-partners-category select:focus-visible,.swe-partners-reset:focus-visible{outline:2px solid var(--gold);outline-offset:3px}
.swe-partners-category{width:280px;max-width:35%}
.swe-partners-category select{padding:10px 30px 10px 12px}
.swe-partners-reset{border:0;background:transparent;color:var(--wine);font:inherit;font-size:11px;min-height:47px;padding:10px 5px;cursor:pointer;display:flex;align-items:center;gap:12px}
.swe-partners-results{display:flex;align-items:center;justify-content:space-between;gap:20px;padding-block:24px 30px}
.swe-partners-results>p{font-size:11px;color:var(--swe-palette-hex-796969,#796969)}
.swe-partners-results .swe-text-link{font-size:11px}
.swe-partners-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:28px}
.swe-partner-card{min-width:0;background:#fff;border:1px solid var(--swe-palette-hex-e5d9ce,#e5d9ce);transition:border-color .2s,box-shadow .2s}
.swe-partner-card:hover{border-color:var(--swe-palette-hex-bfa18a,#bfa18a);box-shadow:0 8px 30px var(--swe-palette-hex-4a25300a,#4a25300a)}
.swe-partner-art{aspect-ratio:1.7;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;background:var(--swe-palette-hex-e8dcd0,#e8dcd0)}
.swe-partner-card:nth-child(3n+2) .swe-partner-art{background:var(--swe-palette-hex-e2d3c7,#e2d3c7)}
.swe-partner-card:nth-child(3n) .swe-partner-art{background:var(--swe-palette-hex-eddfd8,#eddfd8)}
.swe-partner-art img{width:100%;height:100%;object-fit:contain;padding:30px;transition:transform .3s}
.swe-partner-card:hover .swe-partner-art img{transform:scale(1.04)}
.swe-partner-initial{font-family:'Playfair Display',Georgia,serif;font-size:78px;color:var(--swe-palette-hex-91606b,#91606b);line-height:1}
.swe-partner-category{position:absolute;left:14px;bottom:14px;background:var(--swe-palette-hex-faf7f2ee,#faf7f2ee);color:var(--wine);font-size:9px;max-width:calc(100% - 28px);padding:6px 9px;line-height:1.7}
.swe-partner-booth{position:absolute;right:12px;top:12px;font-size:9px;color:var(--swe-palette-hex-6e414a,#6e414a);border:1px solid var(--swe-palette-hex-bda291,#bda291);background:var(--swe-palette-hex-f8f1e5d9,#f8f1e5d9);padding:5px 9px}
.swe-partner-body{padding:23px}
.swe-partner-city{font-size:9px;color:var(--swe-palette-hex-987b69,#987b69);letter-spacing:.08em;text-transform:uppercase}
.swe-partner-body h3{font-family:'Playfair Display',Georgia,serif;font-weight:400;font-size:24px;line-height:1.3;margin:10px 0 23px;overflow-wrap:anywhere}
.swe-partner-body h3 a:hover{color:var(--wine)}
.swe-partner-meta{display:grid;grid-template-columns:1fr 1fr;gap:15px;border-top:1px solid var(--swe-palette-hex-e6dbd0,#e6dbd0);padding-top:17px;margin-bottom:24px}
.swe-partner-meta dt{font-size:9px;color:var(--swe-palette-hex-987e70,#987e70);margin-bottom:5px}
.swe-partner-meta dd{font-size:11px;color:var(--swe-palette-hex-624f4f,#624f4f);line-height:1.6;margin:0;overflow-wrap:anywhere}
.swe-partner-actions{display:flex;justify-content:space-between;align-items:center;gap:12px}
.swe-partner-actions .swe-text-link{font-size:11px;gap:20px}
.swe-partner-whatsapp{font-size:10px;color:var(--swe-palette-hex-725a50,#725a50);display:flex;align-items:center;gap:10px;padding-block:8px}
.swe-partner-whatsapp:hover{color:var(--wine)}
.swe-partners-no-results{margin-top:10px;text-align:center}
.swe-partners-noscript{font-size:12px;color:var(--swe-palette-hex-796969,#796969);margin-top:24px}
@media(max-width:1050px){.swe-partners-grid{gap:22px}.swe-partner-body{padding:19px}.swe-partner-body h3{font-size:22px}.swe-partners-filters{gap:15px}.swe-partners-category{width:250px}}
@media(max-width:767px){.swe-partners-page .swe-schedule-hero h1{font-size:41px}.swe-partners-page .swe-section-top>p{margin-top:20px}.swe-partners-filters{flex-wrap:wrap;gap:17px}.swe-partners-search{flex-basis:100%}.swe-partners-category{width:auto;max-width:none;flex:1;min-width:0}.swe-partners-reset{font-size:10px}.swe-partners-results{align-items:flex-start;gap:12px}.swe-partners-results .swe-text-link{font-size:10px;gap:10px}.swe-partners-results>p{font-size:10px;line-height:1.7}.swe-partners-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:20px 14px}.swe-partner-body{padding:15px}.swe-partner-body h3{font-size:20px;margin-bottom:17px}.swe-partner-art{aspect-ratio:1.1}.swe-partner-initial{font-size:60px}.swe-partner-category{font-size:8px;left:8px;bottom:8px;padding:4px 6px;max-width:calc(100% - 16px)}.swe-partner-booth{font-size:8px;top:8px;right:8px;padding:4px 7px}.swe-partner-art img{padding:18px}.swe-partner-actions{flex-wrap:wrap;gap:5px}.swe-partner-meta{gap:10px;margin-bottom:15px}.swe-partner-meta dt{font-size:8px}.swe-partner-meta dd{font-size:10px}.swe-partner-actions .swe-text-link{font-size:10px;gap:12px}.swe-partner-whatsapp{font-size:9px;gap:8px}.swe-partners-page .swe-section{padding-block:45px}}
@media(max-width:360px){.swe-partners-grid{grid-template-columns:1fr}.swe-partner-art{aspect-ratio:1.7}.swe-partner-body h3{font-size:24px}.swe-partner-actions{flex-wrap:nowrap}.swe-partner-meta dt{font-size:10px}.swe-partner-meta dd{font-size:11px}}
</style>
