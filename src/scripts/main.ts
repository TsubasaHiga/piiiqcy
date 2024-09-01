import '@styles/tailwind-config.css'
import '@styles/main.scss'
import 'focus-visible'
import 'instant.page'

import AddAnimationClass from '@modules/AddAnimationClass'
import AddUaData from '@modules/AddUaData'
import SetOrientation from '@modules/SetOrientation'
import GetDocumentH from '@utils/getDocumentHeight'
import { debounce, throttle } from 'throttle-debounce'

const onDOMContentLoaded = () => {
  // AddUaData
  new AddUaData()
}

const onLoad = () => {
  // SetOrientation
  new SetOrientation()

  // addAnimationClass
  new AddAnimationClass({
    isDebug: true
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
  if (GetDocumentH() <= y) {
    document.documentElement.classList.add('is-footer')
  } else {
    document.documentElement.classList.remove('is-footer')
  }
}

let oldInnerWidth = window.innerWidth
const onResize = () => {
  // window幅が変わった時
  if (oldInnerWidth !== window.innerWidth) {
    oldInnerWidth = window.innerWidth
  }
}

// addEventListeners
window.addEventListener('DOMContentLoaded', onDOMContentLoaded)
window.addEventListener('load', () => {
  onLoad()
  window.addEventListener('scroll', throttle(100, onScroll), false)
  window.addEventListener('resize', debounce(100, onResize), false)
})
