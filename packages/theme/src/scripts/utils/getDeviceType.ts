import { values } from '@/scripts/const/values'

/**
 * breakpointとウインドウサイズを比較してlgかsmか取得します
 * @return string 'lg' or 'sm'
 */
export const getDeviceType = (): 'lg' | 'sm' => (window.innerWidth > values.BREAKPOINT ? 'lg' : 'sm')
