import '@styles/Pages/_top.scss'

import pageScript from './pages/top/index'

const onLoad = () => {
  console.log('[pageTop] Page loaded')
  pageScript()
}

// addEventListeners
window.addEventListener('load', onLoad)
