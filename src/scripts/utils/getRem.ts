/**
 * pxをremに変換する
 */
const GetRem = (px: number): string => {
  const baseFontSize = 16
  return `${px / baseFontSize}rem`
}

export default GetRem
