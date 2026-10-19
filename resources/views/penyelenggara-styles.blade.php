<style>
.swe-organizer-page .swe-schedule-hero{padding-bottom:65px}
.swe-organizer-page .swe-schedule-hero h1{font-size:clamp(40px,4.7vw,64px)}
.swe-organizer-signature{border-left:1px solid #ffffff30;padding-left:38px;min-width:0}
.swe-organizer-page .swe-organizer-signature>p{font-family:'Playfair Display',Georgia,serif;font-size:32px;line-height:1.4;margin:17px 0 12px;color:var(--swe-palette-hex-f8ecdc,#f8ecdc);overflow-wrap:anywhere}
.swe-organizer-signature>span:last-child{font-size:11px;color:var(--swe-palette-hex-e0beb0,#e0beb0);letter-spacing:.12em}
.swe-org-logo{background:var(--swe-palette-hex-faf7f2,#faf7f2);padding:18px;margin-top:20px;width:170px;height:100px;display:flex;align-items:center;justify-content:center;border-radius:3px}
.swe-org-logo img{max-width:100%;max-height:100%;object-fit:contain}
.swe-org-shortcuts{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));padding-block:29px;border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);gap:22px}
.swe-org-shortcuts>a{display:flex;align-items:center;gap:12px;font-size:11px;border-right:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);padding-right:22px}
.swe-org-shortcuts>a:last-child{border:0}
.swe-org-shortcuts>a>span:first-child{color:var(--swe-palette-hex-9e7d65,#9e7d65);font-size:9px}
.swe-org-shortcuts>a>span:last-child{margin-left:auto;font-size:17px;color:var(--wine)}
.swe-org-shortcuts>a:hover{color:var(--wine)}
.swe-org-profile{display:grid;grid-template-columns:1fr 1.15fr;gap:85px}
.swe-org-name{font-size:13px;color:var(--wine);line-height:1.8;border-left:2px solid var(--swe-palette-hex-b78a54,#b78a54);padding-left:18px;margin-top:30px}
.swe-org-story>p{font-size:14px;line-height:2;color:var(--swe-palette-hex-796969,#796969);white-space:pre-line;overflow-wrap:anywhere}
.swe-org-principles{margin-top:30px}
.swe-org-principles>div{display:flex;gap:22px;padding:21px 0;border-top:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}
.swe-org-principles>div>span{font-family:Georgia,serif;font-size:23px;color:var(--swe-palette-hex-b18d70,#b18d70)}
.swe-org-principles h3{font-size:14px;font-weight:500;margin-bottom:6px}
.swe-org-principles p{font-size:12px;line-height:1.9;color:var(--swe-palette-hex-796969,#796969)}
.swe-org-documentation{background:var(--swe-palette-hex-f0e8e0,#f0e8e0)}
.swe-org-gallery{display:grid;grid-template-columns:repeat(auto-fit,minmax(min(100%,300px),1fr));gap:28px}
.swe-org-photo{margin:0;min-width:0}
.swe-org-photo>img{display:block;width:100%;height:310px;object-fit:cover}
.swe-org-photo:only-child{max-width:850px;width:100%}
.swe-org-photo:only-child>img{height:430px}
.swe-org-photo-empty{display:flex;flex-direction:column;justify-content:center;align-items:center;min-height:250px;background:var(--swe-palette-hex-e4d5c8,#e4d5c8);color:var(--swe-palette-hex-956d65,#956d65)}
.swe-org-photo-empty>span{font-family:'Playfair Display',Georgia,serif;font-size:60px;letter-spacing:.1em}
.swe-org-photo-empty p{font-size:10px;letter-spacing:.1em;margin-top:12px}
.swe-org-photo figcaption{padding-top:17px}
.swe-org-photo h3{font-size:15px;font-weight:500;line-height:1.6}
.swe-org-photo figcaption p{font-size:12px;color:var(--swe-palette-hex-796969,#796969);line-height:1.9;margin-top:8px;white-space:pre-line;overflow-wrap:anywhere}
.swe-org-photo figcaption>span{display:block;font-size:10px;color:var(--swe-palette-hex-967c6c,#967c6c);margin-top:12px}
.swe-organizer-page .swe-section-top>p{max-width:325px;margin:0}
.swe-org-team{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:25px}
.swe-team-portrait{aspect-ratio:1;background:var(--swe-palette-hex-eee0d4,#eee0d4);display:flex;align-items:center;justify-content:center;overflow:hidden}
.swe-team-portrait img{width:100%;height:100%;object-fit:cover}
.swe-team-portrait>span{font-family:'Playfair Display',Georgia,serif;font-size:80px;color:var(--swe-palette-hex-9e6f71,#9e6f71)}
.swe-team-info{padding:17px 0}
.swe-team-info h3{font-size:15px;font-weight:500;overflow-wrap:anywhere}
.swe-team-info>p{font-size:11px;color:var(--swe-palette-hex-796969,#796969);margin-top:7px;line-height:1.7}
.swe-team-socials{display:flex;flex-wrap:wrap;gap:15px;margin-top:12px}
.swe-team-socials a{font-size:10px;color:var(--wine)}
.swe-team-empty{grid-column:1/-1;max-width:none;width:100%;padding:38px;border:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1);background:var(--swe-palette-hex-f4eee7,#f4eee7)}
.swe-team-empty h3{font-family:'Playfair Display',Georgia,serif;font-size:30px;line-height:1.4;margin:12px 0}
.swe-team-empty p{font-size:13px;line-height:1.9;color:var(--swe-palette-hex-796969,#796969);max-width:650px;margin-bottom:23px}
.swe-org-contact{background:var(--swe-palette-hex-eee4da,#eee4da)}
.swe-org-contact-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:100px}
.swe-org-contact-grid>div>p{font-size:13px;color:var(--swe-palette-hex-796969,#796969);line-height:1.9;max-width:430px}
.swe-org-socials{display:flex;flex-wrap:wrap;gap:24px;margin-top:30px}
.swe-contact-details{min-width:0}
.swe-contact-details>div{padding:22px 0;border-top:1px solid var(--swe-palette-hex-d0bbab,#d0bbab)}
.swe-contact-value{display:flex;align-items:center;justify-content:space-between;gap:15px;font-size:20px;margin-top:12px;color:var(--wine);overflow-wrap:anywhere;line-height:1.6}
.swe-contact-value>span{font-size:18px;flex-shrink:0}
.swe-contact-details p{font-size:13px;color:var(--swe-palette-hex-6f5c59,#6f5c59);line-height:1.9;margin-top:12px;overflow-wrap:anywhere}
.swe-contact-details .swe-text-link{font-size:11px;margin-top:18px}
@media(max-width:1050px){.swe-org-profile,.swe-org-contact-grid{gap:45px}.swe-org-shortcuts{gap:15px}.swe-org-shortcuts>a{padding-right:15px;gap:8px}.swe-org-team{grid-template-columns:repeat(3,minmax(0,1fr))}.swe-contact-value{font-size:17px}}
@media(max-width:767px){.swe-organizer-page .swe-schedule-hero{padding-bottom:35px}.swe-organizer-page .swe-schedule-hero h1{font-size:40px}.swe-organizer-signature{border-left:0;border-top:1px solid #ffffff30;padding:25px 0 0}.swe-organizer-page .swe-organizer-signature>p{font-size:26px}.swe-org-shortcuts{grid-template-columns:1fr 1fr;gap:0;padding-block:12px}.swe-org-shortcuts>a{padding:16px 10px;font-size:10px}.swe-org-shortcuts>a:nth-child(2){border-right:0}.swe-org-shortcuts>a:nth-child(-n+2){border-bottom:1px solid var(--swe-palette-hex-ddcfc1,#ddcfc1)}.swe-org-profile,.swe-org-contact-grid{grid-template-columns:1fr;gap:30px}.swe-org-story>p{font-size:13px}.swe-organizer-page .swe-section-top>p{margin-top:20px}.swe-org-team{grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.swe-org-gallery{grid-template-columns:1fr}.swe-org-photo>img,.swe-org-photo:only-child>img{height:280px}.swe-team-empty{padding:26px}.swe-team-empty h3{font-size:26px}.swe-contact-value{font-size:17px}.swe-organizer-page .swe-section{padding-block:45px}.swe-org-name{margin-top:23px}.swe-team-info h3{font-size:13px}}
</style>
