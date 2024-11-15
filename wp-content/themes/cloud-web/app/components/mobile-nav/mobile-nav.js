window.coreTheme.components('mobile-nav', (el) => {
    const mobileMenuToggler = document.querySelector('.js-mobile-menu-toggler');
    const iconToggler = el.querySelectorAll('.js-icon-toggle');
    const backBtn = el.querySelectorAll('.js-back');

    function isOpen(menuitem) {
        if (!menuitem || !menuitem.getAttribute('aria-expanded')) return false;

        return menuitem.getAttribute('aria-expanded') === 'true';
    }

    const scrollLock = {
        isLocked: () => {
            return document.body.classList.contains('scroll-lock');
        },

        lock: () => {
            document.body.classList.add('scroll-lock');
        },

        unlock: () => {
            document.body.classList.remove('scroll-lock');
        }
    };

    // Open Mobile Menu when its toggler is clicked.
    mobileMenuToggler.addEventListener('click', () => {
        el.classList.toggle('mobile-menu-active');
        mobileMenuToggler.setAttribute('aria-expanded', !isOpen(mobileMenuToggler));
        mobileMenuToggler.classList.toggle('active');
        el.classList.toggle('active');
        scrollLock[mobileMenuToggler.classList.contains('active') ? 'lock' : 'unlock']();

        // Close All Dropdowns
        el.querySelectorAll('.dropdown').forEach(dropdown => {
            closeItem(dropdown);
        });
    });

    // Open Item
    function openItem(item) {
        item.querySelector('.dropdown').classList.add('active');
    }

    // Close Item
    function closeItem(dropdown) {
        dropdown.classList.remove('active');
    }

    // Detect clicks on Toggler.
    iconToggler.forEach(toggler => {
        toggler.addEventListener('click', () => {
            const item = toggler.closest('.js-item');

            openItem(item);
        });
    });

    // Back Button Click.
    backBtn.forEach(btn => {
        btn.addEventListener('click', () => {
            const dropdown = btn.closest('.dropdown');

            closeItem(dropdown);
        });
    });
});