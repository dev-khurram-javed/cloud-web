window.coreTheme.blocks('media-gallery', (el) => {
    const mediaSlider = el.querySelector('.js-slider-images');

    if (mediaSlider) {
        new window.packages.swiper(mediaSlider, {
            autoHeight: true,
            pagination: {
                el: el.querySelector('.js-indicator'),
                type: 'fraction',
                formatFractionCurrent: function (number) {
                    return ('0' + number).slice(-2);
                },
                formatFractionTotal: function (number) {
                    return ('0' + number).slice(-2);
                },
                renderFraction: function (currentClass, totalClass) {
                    return '<span class="' + currentClass + '"></span>' +
                        '/' +
                        '<span class="' + totalClass + '"></span>';
                }
            },
            navigation: {
                prevEl: el.querySelector('.js-prev'),
                nextEl: el.querySelector('.js-next')
            }
        });
    }
});