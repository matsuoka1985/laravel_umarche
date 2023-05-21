import Swiper from 'swiper';

import 'swiper/swiper-bundle.css';

import SwiperCore, { Navigation, Pagination } from 'swiper/core';

SwiperCore.use([Navigation,Pagination]);

// const swiper = new Swiper(...);

const swiper = new Swiper('.swiper-container', {
    // direction: 'vertical',
    loop: true,

    pagination: {
        el:'.swiper-pagination'
    },

    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
    },

    scrollbar: {
        el:'.swiper-scrollbar',
    },
});
