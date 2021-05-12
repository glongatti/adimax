var mySwiper = new Swiper('#banner-slider .elementor-widget-container', {
    slidesPerView: 1,
    spaceBetween: 10,
    slideClass: 'elementor-column',
    wrapperClass: 'elementor-row',
    navigation: {
        nextEl: '#our_team_arrows .swiper-button-next',
        prevEl: '#our_team_arrows .swiper-button-prev',
    },
    breakpoints: {
        640: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 1,
          spaceBetween: 40,
        },
        1024: {
          slidesPerView: 5,
          spaceBetween: 0,
        },
    }
});