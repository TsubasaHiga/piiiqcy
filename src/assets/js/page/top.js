'use strict'

import 'swiper/swiper-bundle.min.css'
import Swiper from 'swiper/bundle'

export default () => {
  console.log('top')

  const mySwiper = new Swiper('.swiper-container', {
    loop: true,
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev'
    }
  })
}
