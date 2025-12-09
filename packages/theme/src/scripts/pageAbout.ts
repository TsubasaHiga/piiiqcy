import '@/styles/Pages/_about.scss'

import pageScript from './pages/about/index'

const onLoad = () => {
  console.log('[pageAbout] Page loaded')
  pageScript()
}

// addEventListeners
window.addEventListener('load', onLoad)
