<style>
main .swe-shape-host{position:relative;isolation:isolate;overflow:hidden}
main .swe-shape-host>.swe-wrap,main .swe-shape-host>.max-w-7xl{position:relative;z-index:1}
.swe-motion-shapes{position:absolute;inset:0;z-index:0;pointer-events:none;overflow:hidden;user-select:none}
.swe-motion-shapes::before,.swe-motion-shapes::after{content:'';position:absolute;box-sizing:border-box;will-change:transform;animation-play-state:paused!important}
.swe-motion-shapes::before{width:260px;height:300px;border:1px solid var(--swe-palette-hex-edcda92e,#edcda92e);border-radius:48% 48% 42% 42%;right:-85px;top:-125px;rotate:-22deg}
.swe-motion-shapes::after{width:135px;height:135px;border:1px solid var(--swe-palette-hex-edcda924,#edcda924);border-radius:50%;left:-70px;bottom:-60px;background:var(--swe-palette-hex-edcda904,#edcda904)}
.swe-shapes-active>.swe-motion-shapes::before,.swe-shapes-active>.swe-motion-shapes::after{animation-play-state:running!important}
@keyframes swe-shape-drift{0%,100%{transform:translate(0,0) rotate(0deg)}50%{transform:translate(-14px,15px) rotate(7deg)}}
@keyframes swe-shape-float{0%,100%{transform:translate(0,0)}50%{transform:translate(12px,-12px)}}
@media(prefers-reduced-motion:no-preference){.swe-motion-shapes::before{animation:swe-shape-drift 18s ease-in-out infinite}.swe-motion-shapes::after{animation:swe-shape-float 14s ease-in-out infinite}}
@media(max-width:767px){.swe-motion-shapes::before{width:170px;height:200px;right:-80px;top:-95px}.swe-motion-shapes::after{display:none}}
@media(prefers-reduced-motion:reduce){.swe-motion-shapes{display:none}}
@media print{.swe-motion-shapes{display:none}}

@media(prefers-reduced-motion:no-preference){
header .dropdown-menu{transition:opacity .22s ease,visibility .22s ease,translate .22s ease}
header .dropdown-menu.invisible{translate:0 6px}
header .dropdown-menu.visible,header .swe-mini-cart-shell:focus-within .dropdown-menu{translate:0 0}
main :is(.swe-btn,button[type="submit"],a.bg-rose-600,a.bg-blue-600,.swe-profile-link),header :is(.swe-mini-cart-primary,.swe-mini-cart-secondary){transition:background-color .2s ease,color .2s ease,box-shadow .2s ease,translate .2s ease}
main :is(.swe-btn,button[type="submit"],a.bg-rose-600,a.bg-blue-600):not(:disabled):hover,header :is(.swe-mini-cart-primary,.swe-mini-cart-secondary):hover{translate:0 -2px}
main :is(.swe-btn,button[type="submit"],a.bg-rose-600,a.bg-blue-600):not(:disabled):active{translate:0 0}
main :is(.swe-participant-card,.swe-partner-card){transition:border-color .25s ease,box-shadow .25s ease,translate .25s ease}
main :is(.swe-participant-card,.swe-partner-card):hover{translate:0 -3px}
}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto!important}header .dropdown-menu,main :is(a,button),main :is(.swe-participant-card,.swe-partner-card){transition:none!important;translate:none!important}}
@media print{.swe-motion-target{opacity:1!important;transform:none!important;translate:none!important}}
</style>
