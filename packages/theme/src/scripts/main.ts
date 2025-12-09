import '@/styles/main.scss'
import 'focus-visible'
import 'instant.page'

import { debounce, getDocumentHeight, throttle } from 'umaki'

import AddUaData from '@/scripts/modules/AddUaData'
import InView from '@/scripts/modules/InView'
import SetOrientation from '@/scripts/modules/SetOrientation'

const onDOMContentLoaded = () => {
  // AddUaData
  new AddUaData()
}

const onLoad = () => {
  // SetOrientation
  new SetOrientation()

  // 'u-inview'クラスが付与された要素を監視して、画面内に入った時に'is-inview'クラスを付与する
  const inViewElements = document.querySelectorAll('.u-inview') as NodeListOf<HTMLElement>
  inViewElements.forEach((element) => {
    new InView(
      element,
      () => element.classList.add('is-inview'),
      () => element.classList.remove('is-inview'),
      () => {},
      {
        isOnce: true
      }
    )
  })

  // onScroll
  onScroll()

  document.documentElement.classList.add('is-loaded')
}

const onScroll = () => {
  const y = Math.round(window.scrollY)

  // add className is-scroll
  if (y > 0) {
    document.documentElement.classList.add('is-scroll')
  } else {
    document.documentElement.classList.remove('is-scroll')
  }

  // add className is-footer
  if (getDocumentHeight() <= y) {
    document.documentElement.classList.add('is-footer')
  } else {
    document.documentElement.classList.remove('is-footer')
  }
}

// addEventListeners
window.addEventListener('DOMContentLoaded', onDOMContentLoaded)
window.addEventListener('load', onLoad)
window.addEventListener('scroll', throttle(onScroll, 30), false)
window.addEventListener('scroll', debounce(onScroll, 100), false)
