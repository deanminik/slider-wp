document.addEventListener('DOMContentLoaded', function () {
    var splideElements = document.querySelectorAll('.ccc-home-slider');

    splideElements.forEach(function (slider) {
        new Splide(slider, {
            type: 'loop',
            perPage: 1,
            autoplay: true,
            interval: 10000,
            pauseOnHover: true,
            pauseOnFocus: true,
            arrows: true,
            pagination: true,
            speed: 900,
            easing: 'cubic-bezier(0.25, 1, 0.5, 1)',
        }).mount();
    });
});
