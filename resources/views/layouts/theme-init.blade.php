<script>
(() => {
    const key = 'swe.color-theme';
    const media = window.matchMedia('(prefers-color-scheme: dark)');
    const valid = value => ['light', 'dark', 'auto'].includes(value);
    let selected = 'light';
    try { const stored = localStorage.getItem(key); if (valid(stored)) selected = stored; } catch (_) {}
    const apply = () => {
        const resolved = selected === 'auto' ? (media.matches ? 'dark' : 'light') : selected;
        document.documentElement.dataset.colorTheme = resolved;
        document.documentElement.style.colorScheme = resolved;
        window.dispatchEvent(new CustomEvent('swe:theme-change', { detail: selected }));
    };
    window.__getTheme = () => selected;
    window.__setTheme = value => { if (!valid(value)) return; selected = value; apply(); };
    window.__saveTheme = () => { try { localStorage.setItem(key, selected); return true; } catch (_) { return false; } };
    media.addEventListener('change', () => { if (selected === 'auto') apply(); });
    window.addEventListener('storage', event => {
        if (event.key !== key && event.key !== null) return;
        selected = valid(event.newValue) ? event.newValue : 'light'; apply();
    });
    apply();
})();
</script>
