<script>
(() => {
    const key = 'swe.color-theme';
    const designKey = 'swe.design-theme';
    // Design identity is separate from Light/Dark/Auto preferences.
    const designs = { burgundy: 'Burgundy', 'navy-gold': 'Navy Gold' };
    let design = 'burgundy';
    try { const stored = localStorage.getItem(designKey); if (Object.hasOwn(designs, stored)) design = stored; } catch (_) {}
    const media = window.matchMedia('(prefers-color-scheme: dark)');
    const valid = value => ['light', 'dark', 'auto'].includes(value);
    let selected = 'light';
    try { const stored = localStorage.getItem(key); if (valid(stored)) selected = stored; } catch (_) {}
    const apply = () => {
        const resolved = selected === 'auto' ? (media.matches ? 'dark' : 'light') : selected;
        document.documentElement.dataset.designTheme = design;
        document.documentElement.dataset.colorTheme = resolved;
        document.documentElement.style.colorScheme = resolved;
        window.dispatchEvent(new CustomEvent('swe:theme-change', { detail: selected }));
    };
    window.__setDesignTheme = value => { if (!Object.hasOwn(designs, value)) return; design = value; apply(); };
    window.__getDesignTheme = () => design;
    window.__getDesignThemeName = () => designs[design];
    window.__getTheme = () => selected;
    window.__setTheme = value => { if (!valid(value)) return; selected = value; apply(); };
    window.__saveTheme = () => { try { localStorage.setItem(key, selected); localStorage.setItem(designKey, design); return true; } catch (_) { return false; } };
    media.addEventListener('change', () => { if (selected === 'auto') apply(); });
    window.addEventListener('storage', event => {
        if (event.key === designKey) { design = Object.hasOwn(designs, event.newValue) ? event.newValue : 'burgundy'; apply(); return; }
        if (event.key !== key && event.key !== null) return;
        selected = valid(event.newValue) ? event.newValue : 'light';
        if (event.key === null) design = 'burgundy';
        apply();
    });
    apply();
})();
</script>
