<style>
.swe-schedule-hero{background:var(--swe-palette-hex-3d1822,#3d1822);color:#fff;position:relative;isolation:isolate;padding:30px 0 64px;overflow:hidden}
.swe-schedule-hero:before{content:'';position:absolute;inset:0;z-index:-1;background:linear-gradient(90deg,var(--swe-palette-hex-32121cf5,#32121cf5),var(--swe-palette-hex-3d1822dd,#3d1822dd) 58%,var(--swe-palette-hex-3d182295,#3d182295)),url('/images/swe-home-hero.jpg') center 42%/cover}
.swe-breadcrumb{display:flex;align-items:center;gap:15px;font-size:11px;color:var(--swe-palette-hex-e2c7bf,#e2c7bf);margin-bottom:55px}
.swe-breadcrumb a:hover{color:white}
.swe-schedule-hero-grid{display:grid;grid-template-columns:1.4fr 1fr;gap:90px;align-items:center}
.swe-schedule-hero h1{font-size:clamp(43px,5vw,67px);line-height:1.1;margin:17px 0 22px}
.swe-schedule-hero h1 em{color:var(--swe-palette-hex-e6c7a2,#e6c7a2)}
.swe-schedule-hero-grid>div>p{max-width:540px;font-size:13px;line-height:1.9;color:var(--swe-palette-hex-ead6d2,#ead6d2)}
.swe-edition{border-left:1px solid #ffffff30;padding:10px 0 10px 35px}
.swe-status{display:inline-flex;align-items:center;gap:9px;border:1px solid var(--swe-palette-hex-dcb5a360,#dcb5a360);border-radius:30px;padding:7px 12px;font-size:10px;color:var(--swe-palette-hex-f0d6bd,#f0d6bd)}
.swe-status>span{width:5px;height:5px;border-radius:50%;background:var(--swe-palette-hex-d6ad81,#d6ad81)}
.swe-status-live>span{background:#a8d6a0}
.swe-edition>p{font-family:'Playfair Display',Georgia,serif;font-size:27px!important;margin-top:20px;line-height:1.4!important;color:#fff!important}
.swe-edition>strong{display:block;font-size:12px;font-weight:400;color:var(--swe-palette-hex-e2c7bf,#e2c7bf);margin-top:12px;line-height:1.8}
.swe-archive-note{display:block;font-size:10px;color:var(--swe-palette-hex-dac0b7,#dac0b7);margin-top:18px;line-height:1.8}
.swe-schedule-facts{display:grid;grid-template-columns:1fr 1fr 1.1fr;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding-block:32px;gap:30px}
.swe-schedule-facts>div{padding-right:25px;border-right:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);min-width:0}
.swe-schedule-facts>div:last-child{border:0;padding-right:0}
.swe-schedule-facts strong{display:block;font-size:14px;font-weight:500;margin:10px 0 7px;line-height:1.7;overflow-wrap:anywhere}
.swe-schedule-facts>div>span:last-child{font-size:11px;color:var(--swe-palette-hex-796969,#796969);line-height:1.8;display:block}
.swe-rundown-layout{display:grid;grid-template-columns:minmax(0,1fr) 310px;gap:65px;align-items:start}
.swe-rundown-main{min-width:0}
.swe-rundown-main .swe-section-top{gap:25px}
.swe-rundown-main .swe-section-top>p{max-width:230px;margin-bottom:0;font-size:12px}
.swe-day-tabs{display:flex;gap:10px;overflow-x:auto;padding:0 3px 15px;scrollbar-width:thin;scroll-margin-top:90px}
.swe-day-tab{min-width:138px;flex:1;display:flex;flex-direction:column;gap:7px;border:1px solid var(--swe-palette-hex-d9c9bc,#d9c9bc);border-radius:4px;padding:17px 19px;transition:background .2s,color .2s}
.swe-day-tab>span:first-child{font-size:8px;letter-spacing:.15em;color:var(--swe-palette-hex-956d65,#956d65)}
.swe-day-tab strong{font-family:'Playfair Display',Georgia,serif;font-size:21px;font-weight:400}
.swe-day-tab>span:last-child{font-size:10px;color:var(--swe-palette-hex-796969,#796969)}
.swe-day-tab:hover{background:var(--swe-palette-hex-eee1d5,#eee1d5)}
.swe-day-tab[aria-selected=true]{background:var(--wine);border-color:var(--wine);color:#fff}
.swe-day-tab[aria-selected=true]>span{color:var(--swe-palette-hex-ead4c2,#ead4c2)}
.swe-day-heading{display:flex;align-items:center;justify-content:space-between;gap:20px;padding:27px 0;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}
.swe-day-heading h3{font-family:'Playfair Display',Georgia,serif;font-size:25px;margin-top:5px;line-height:1.4}
.swe-day-heading>span{font-size:10px;white-space:nowrap;border:1px solid var(--swe-palette-hex-d9c9bc,#d9c9bc);padding:7px 10px;border-radius:20px;color:var(--swe-palette-hex-796969,#796969)}
.swe-timeline{list-style:none;margin:0;padding:10px 0}
.swe-timeline li{display:grid;grid-template-columns:140px minmax(0,1fr);gap:25px;position:relative}
.swe-timeline-time{position:relative;padding:30px 10px 30px 20px;border-left:1px solid var(--swe-palette-hex-d9c9bc,#d9c9bc);margin-left:5px}
.swe-timeline-time strong{font-size:11px;font-weight:600;color:var(--wine);line-height:1.8;display:block}
.swe-timeline-dot{position:absolute;top:34px;left:-5px;width:9px;height:9px;background:var(--wine);border:2px solid var(--cream);border-radius:50%;outline:1px solid var(--swe-palette-hex-d9c9bc,#d9c9bc)}
.swe-timeline li:last-child .swe-timeline-time{border-color:transparent}
.swe-timeline-content{padding:27px 0;border-bottom:1px solid var(--swe-palette-hex-e5d9cf,#e5d9cf);min-width:0}
.swe-timeline li:last-child .swe-timeline-content{border:0}
.swe-timeline-content h4{font-size:16px;font-weight:500;line-height:1.6;overflow-wrap:anywhere}
.swe-timeline-content p{font-size:12px;color:var(--swe-palette-hex-796969,#796969);line-height:1.9;margin-top:10px;white-space:pre-line;overflow-wrap:anywhere}
.swe-stage{display:inline-flex;gap:6px;align-items:center;font-size:10px;margin-top:13px;color:var(--swe-palette-hex-8b5b4d,#8b5b4d);background:var(--swe-palette-hex-efe4da,#efe4da);padding:5px 9px;border-radius:3px}
.swe-stage svg{width:13px;height:13px;flex-shrink:0}
.swe-day-panel{scroll-margin-top:90px}
.swe-schedule-aside{padding-top:8px}
.swe-visit-card{background:var(--swe-palette-hex-eee4da,#eee4da);padding:30px}
.swe-visit-card h3{font-family:'Playfair Display',Georgia,serif;font-size:29px;line-height:1.3;margin:18px 0 22px;letter-spacing:-.03em}
.swe-visit-card ul{padding-left:16px;list-style:disc;color:var(--swe-palette-hex-796969,#796969);font-size:12px;line-height:1.9;margin:0 0 28px}
.swe-visit-card li+li{margin-top:14px}
.swe-visit-card .swe-btn{width:100%;font-size:11px;gap:12px;padding-inline:12px}
.swe-direction-card{border:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding:27px;margin-top:20px}
.swe-direction-card h3{font-size:15px;font-weight:500;line-height:1.7;margin:15px 0;overflow-wrap:anywhere}
.swe-direction-card p{font-size:11px;line-height:1.9;color:var(--swe-palette-hex-796969,#796969);margin-bottom:20px}
.swe-schedule-social{font-size:11px;margin:25px 0 0}
.swe-day-panel .swe-empty{margin-top:25px}
@media(max-width:1050px){.swe-rundown-layout{grid-template-columns:minmax(0,1fr) 270px;gap:30px}.swe-schedule-hero-grid{gap:40px}.swe-timeline li{grid-template-columns:110px minmax(0,1fr);gap:15px}.swe-visit-card{padding:24px}.swe-rundown-main .swe-section-top{display:block}.swe-rundown-main .swe-section-top>p{max-width:none;margin-top:18px}}
@media(max-width:767px){.swe-schedule-hero{padding:25px 0 35px}.swe-breadcrumb{margin-bottom:32px}.swe-schedule-hero-grid{grid-template-columns:1fr;gap:28px}.swe-schedule-hero h1{font-size:43px}.swe-schedule-hero-grid>div>p{font-size:12px}.swe-edition{border-left:0;border-top:1px solid #ffffff30;padding:25px 0 0}.swe-edition>p{font-size:24px!important;margin-top:14px}.swe-schedule-facts{grid-template-columns:1fr;gap:20px;padding-block:25px}.swe-schedule-facts>div{border:0;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding:0 0 18px}.swe-schedule-facts>div:last-child{padding-bottom:0}.swe-schedule-facts strong{font-size:13px;margin-top:6px}.swe-rundown-layout{grid-template-columns:1fr;gap:40px}.swe-day-tab{min-width:116px;padding:15px}.swe-day-tab strong{font-size:20px}.swe-day-heading h3{font-size:22px}.swe-timeline li{grid-template-columns:90px minmax(0,1fr);gap:12px}.swe-timeline-time{padding-left:12px;padding-right:0}.swe-timeline-time strong{font-size:10px}.swe-timeline-content h4{font-size:14px}.swe-timeline-content p{font-size:11px}.swe-visit-card{padding:27px}.swe-schedule-page .swe-section{padding-block:40px}}
</style>
