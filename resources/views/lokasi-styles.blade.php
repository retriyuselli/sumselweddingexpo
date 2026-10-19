<style>
.swe-location-shortcuts{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));padding-block:27px;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);gap:25px}
.swe-location-shortcuts>a{display:flex;align-items:center;gap:16px;font-size:12px;font-weight:500;border-right:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding-right:25px}
.swe-location-shortcuts>a:last-child{border:0}
.swe-location-shortcuts>a>span:first-child{color:var(--swe-palette-hex-a3846d,#a3846d);font-size:9px}
.swe-location-shortcuts>a>span:last-child{margin-left:auto;font-size:18px;color:var(--wine)}
.swe-location-shortcuts>a:hover{color:var(--wine)}
.swe-location-page .swe-section-top>p{max-width:355px;margin:0;line-height:1.9}
.swe-venue-grid{display:grid;grid-template-columns:1fr 1.2fr;border:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);background:#fff}
.swe-venue-details{padding:38px;background:var(--swe-palette-hex-f1e9e0,#f1e9e0);min-width:0}
.swe-venue-number{font-family:'Playfair Display',Georgia,serif;font-size:42px;letter-spacing:.08em;color:var(--swe-palette-hex-a4836a,#a4836a);border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding-bottom:25px;margin-bottom:28px}
.swe-venue-number>span{display:block;font-family:'DM Sans',sans-serif;font-size:8px;letter-spacing:.25em;margin-top:5px}
.swe-venue-details h3{font-family:'Playfair Display',Georgia,serif;font-size:29px;line-height:1.35;letter-spacing:-.02em;margin:15px 0;overflow-wrap:anywhere}
.swe-address{color:var(--swe-palette-hex-796969,#796969);font-size:12px;line-height:1.9;overflow-wrap:anywhere}
.swe-venue-meta{margin:25px 0 5px;border-top:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}
.swe-venue-meta>div{padding:16px 0;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);display:grid;grid-template-columns:95px minmax(0,1fr);gap:16px}
.swe-venue-meta dt{font-size:11px;color:var(--swe-palette-hex-796969,#796969)}
.swe-venue-meta dd{font-size:12px;line-height:1.7;margin:0}
.swe-venue-details .swe-actions{align-items:center;gap:20px}
.swe-venue-details .swe-btn{gap:20px}
.swe-venue-map{display:flex;flex-direction:column;min-width:0;scroll-margin-top:90px;background:var(--swe-palette-hex-e9e2da,#e9e2da)}
.swe-venue-map iframe{width:100%;min-height:400px;flex:1;border:0;display:block}
.swe-map-caption{background:#fff;padding:23px 25px;display:flex;justify-content:space-between;align-items:center;gap:18px;font-size:10px;line-height:1.8;color:var(--swe-palette-hex-796969,#796969)}
.swe-map-caption a{color:var(--wine);font-weight:600;flex-shrink:0}
.swe-map-caption a>span{margin-left:12px}
.swe-map-empty{padding:50px;display:flex;align-items:flex-start;justify-content:center;flex-direction:column;min-height:460px}
.swe-map-empty>svg{width:42px;height:42px;color:var(--swe-palette-hex-9b7a65,#9b7a65);margin-bottom:25px}
.swe-map-empty h3{font-family:'Playfair Display',Georgia,serif;font-size:30px;line-height:1.3;margin-bottom:15px}
.swe-map-empty p{font-size:13px;color:var(--swe-palette-hex-796969,#796969);line-height:1.9;margin-bottom:25px}
.swe-travel{background:var(--swe-palette-hex-f0e8e0,#f0e8e0)}
.swe-travel-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:30px}
.swe-travel-card{border-top:1px solid var(--swe-palette-hex-bea590,#bea590);padding-top:25px}
.swe-travel-card-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:25px;color:var(--wine)}
.swe-travel-card-top svg{width:27px;height:27px;stroke-linecap:round;stroke-linejoin:round}
.swe-travel-card-top>span{font-size:10px;color:var(--swe-palette-hex-9d7a64,#9d7a64)}
.swe-travel-card h3{font-family:'Playfair Display',Georgia,serif;font-size:25px;line-height:1.4;margin-bottom:15px}
.swe-travel-card p{font-size:12px;line-height:2;color:var(--swe-palette-hex-796969,#796969)}
.swe-visit-preparation{display:grid;grid-template-columns:1fr 1.2fr;gap:90px}
.swe-visit-preparation>div>p{font-size:13px;line-height:1.9;color:var(--swe-palette-hex-796969,#796969);max-width:340px}
.swe-visit-checklist{list-style:none;padding:0;margin:0}
.swe-visit-checklist>li{display:flex;gap:25px;padding:25px 0;border-top:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}
.swe-visit-checklist>li>span{font-family:Georgia,serif;font-size:24px;color:var(--swe-palette-hex-ac886a,#ac886a)}
.swe-visit-checklist h3{font-size:15px;font-weight:500;margin-bottom:10px;line-height:1.6}
.swe-visit-checklist p{font-size:12px;line-height:1.9;color:var(--swe-palette-hex-796969,#796969);margin-bottom:15px}
@media(max-width:1000px){.swe-venue-details{padding:28px}.swe-venue-grid{grid-template-columns:1fr 1fr}.swe-map-caption{flex-direction:column;align-items:flex-start}.swe-visit-preparation{gap:45px}.swe-travel-grid{gap:22px}}
@media(max-width:767px){.swe-location-shortcuts{grid-template-columns:1fr;gap:0;padding-block:8px}.swe-location-shortcuts>a{padding:17px 0;border:0;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}.swe-location-shortcuts>a:last-child{border:0}.swe-location-page .swe-section-top>p{margin-top:20px}.swe-venue-grid{grid-template-columns:1fr}.swe-venue-details{padding:25px}.swe-venue-details h3{font-size:25px}.swe-venue-number{font-size:35px;padding-bottom:20px;margin-bottom:22px}.swe-venue-meta>div{grid-template-columns:85px minmax(0,1fr)}.swe-venue-map iframe{height:340px;min-height:340px;flex:none}.swe-map-caption{padding:20px}.swe-map-empty{min-height:320px;padding:30px}.swe-travel-grid,.swe-visit-preparation{grid-template-columns:1fr;gap:30px}.swe-travel-card h3{font-size:24px}.swe-travel-card-top{margin-bottom:15px}.swe-visit-checklist>li{gap:20px}.swe-visit-preparation{gap:25px}.swe-location-page .swe-section{padding-block:45px}}
</style>
