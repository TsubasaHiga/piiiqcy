'use strict'

import DEFINE from '../constant/define'
import EL from '../constant/elements'
import pd from './preventDefault'

/**
 * ページ遷移用のアニメーション（class toggle）処理を行います
 */
export default () => {
  window.addEventListener('popstate', function (e) {
    console.log('hey')
  })

  // ページ遷移後のクラスを付与
  setTimeout(() => {
    EL.HTML.classList.add('is-page-enter-animation')
  }, 10)

  for (let i = 0; i < EL.ALLLINKS.length; i = (i + 1) | 0) {
    const link = EL.ALLLINKS[i]

    // ハッシュ付き以外且つ、同じドメイン内のみ
    if (!link.hash && DEFINE.HOSTNAME === link.hostname) {
      link.addEventListener('click', e => {
        pd(e)

        EL.HTML.classList.add('is-page-leave-animation')
        setTimeout(() => {
          window.location = link.href
        }, 500)
      })
    }
  }
}
