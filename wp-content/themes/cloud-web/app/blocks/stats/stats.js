window.coreTheme.blocks('stats', (el) => {
    const counters = el.querySelectorAll('.num');
    let animate = true;

    const animateValue = (obj, start, duration) => {
        let startTimestamp = null;
        const step = timestamp => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            obj.innerHTML = Math.floor(progress * (0 + start));
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    };

    const startCounters = () => {
        const { scrollY } = window;
        const offsetTop = el.getBoundingClientRect().top + document.documentElement.scrollTop;

        if ((scrollY >= offsetTop) & animate) {
            counters.forEach(counter => {
                const target = counter.dataset.number;
                animateValue(counter, target, 800);
            });

            animate = false;
        }
    };

    if (el.classList.contains('animate-numbers')) {
        window.addEventListener('scroll', startCounters);
    }

    startCounters();
});