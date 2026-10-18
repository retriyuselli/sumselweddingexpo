@push('scripts')
<script>
(() => {
    const list = document.getElementById('home-video-list');
    const controls = document.querySelector('.swe-video-controls');
    if (!list || !controls) return;
    const cards = Array.from(list.children);
    const previous = controls.querySelector('[data-video-prev]');
    const next = controls.querySelector('[data-video-next]');
    const label = controls.querySelector('[data-video-page]');
    const mobile = window.matchMedia('(max-width: 767px)');
    let page = 0;
    const pages = Math.ceil(cards.length / 2);
    cards.forEach(card => { const frame = card.querySelector('iframe'); if (frame) frame.dataset.videoSource = frame.getAttribute('src'); });
    const render = () => {
        controls.hidden = !mobile.matches || pages <= 1;
        cards.forEach((card, index) => {
            const visible = !mobile.matches || Math.floor(index / 2) === page;
            card.hidden = !visible;
            const frame = card.querySelector('iframe');
            // Unload hidden players so audio stops when moving to another page.
            if (frame && !visible) frame.removeAttribute('src');
            else if (frame && !frame.hasAttribute('src')) frame.setAttribute('src', frame.dataset.videoSource);
        });
        previous.disabled = page === 0;
        next.disabled = page >= pages - 1;
        label.textContent = `Video ${page * 2 + 1}–${Math.min(page * 2 + 2, cards.length)} dari ${cards.length}`;
    };
    previous.addEventListener('click', () => { if (page > 0) { page--; render(); } });
    next.addEventListener('click', () => { if (page < pages - 1) { page++; render(); } });
    mobile.addEventListener('change', () => { page = 0; render(); });
    render();
})();
</script>
@endpush
