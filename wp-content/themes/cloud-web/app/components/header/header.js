window.coreTheme.components('header', (el) => {
    // Make the header sticky.
    window.addEventListener('scroll', () => {
        el.classList[window.scrollY > 100 ? 'add' : 'remove']('sticky');
    });
});