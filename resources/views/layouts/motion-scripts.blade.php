<script>
(() => {
    const preference = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (preference.matches || !('IntersectionObserver' in window) || !Element.prototype.animate) return;
    const running = new Set();
    const reveal = (element, delay = 0) => {
        if (preference.matches) return;
        element.classList.add('swe-motion-target');
        const animation = element.animate([
            { opacity: 0, transform: 'translateY(14px)' },
            { opacity: 1, transform: 'translateY(0)' }
        ], { duration: 440, delay, easing: 'cubic-bezier(.2,.65,.3,1)', fill: 'backwards' });
        running.add(animation);
        animation.finished.then(() => running.delete(animation)).catch(() => running.delete(animation));
    };
    const main = document.querySelector('body > main');
    if (!main) return;
    const shapeObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => entry.target.classList.toggle('swe-shapes-active', entry.isIntersecting));
    });
    // Only one decorative layer in the first banner; keep cards and forms clear.
    const banner = main.querySelector('.swe-hero,.swe-schedule-hero,.swe-page-hero');
    if (banner && !banner.querySelector('.swe-motion-shapes')) {
        const shapes = document.createElement('span');
        shapes.className = 'swe-motion-shapes';
        shapes.setAttribute('aria-hidden', 'true');
        banner.classList.add('swe-shape-host');
        banner.appendChild(shapes);
        shapeObserver.observe(banner);
    }
    const heading = main.querySelector('h1');
    if (heading) {
        const container = heading.parentElement;
        Array.from(container.children).filter(element => element.matches('h1,p,.swe-eyebrow,.swe-page-eyebrow,.swe-actions')).forEach((element, index) => reveal(element, Math.min(index, 3) * 65));
    }
    const selector = '.swe-vendor,.swe-gallery-item,.swe-travel-card,.swe-product-card,.swe-product-vendor-card,.swe-benefits > div,article,.swe-participant-card,.swe-partner-card,.swe-visit-card,.swe-direction-card,.swe-event-poster,.grid > .rounded-xl,.grid > .rounded-2xl,[data-motion-reveal]';
    const candidates = Array.from(main.querySelectorAll(selector)).filter(element =>
        !element.closest('.swe-floorplan,dialog,form,.swe-page-hero,.swe-hero,.swe-schedule-hero') &&
        !element.parentElement.closest(selector) && !element.querySelector('form,iframe')
    );
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            observer.unobserve(entry.target);
            if (!entry.target.getClientRects().length) return;
            const siblings = Array.from(entry.target.parentElement.children);
            reveal(entry.target, Math.min(siblings.indexOf(entry.target) % 3, 2) * 55);
        });
    }, { threshold: 0, rootMargin: '0px 0px -24px 0px' });
    candidates.forEach(element => observer.observe(element));
    const stop = () => { shapeObserver.disconnect(); if (banner) banner.classList.remove('swe-shapes-active'); observer.disconnect(); running.forEach(animation => animation.cancel()); running.clear(); };
    preference.addEventListener('change', event => { if (event.matches) stop(); });
    window.addEventListener('beforeprint', stop);
    window.addEventListener('pagehide', stop, { once: true });
})();
</script>
