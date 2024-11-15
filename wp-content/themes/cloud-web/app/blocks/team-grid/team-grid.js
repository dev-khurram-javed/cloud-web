window.coreTheme.blocks('team-grid', (el) => {
    const sliderCarousel = el.querySelector('.slider-carousel');

    if (sliderCarousel) {
        new window.packages.swiper(sliderCarousel, {
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                prevEl: el.querySelector('.js-prev'),
                nextEl: el.querySelector('.js-next')
            },
            breakpoints: {
                580: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3
                }
            }
        });
    }
});