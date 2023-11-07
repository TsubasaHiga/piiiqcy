import type { PageNameType } from '@type/PageDataListType'

import { pageDataList } from '@/pageDataList'

const GetPageDataFromPageDataList = (classNames: string[]) => {
  // pageDataの中のpageNameとclassNamesが一致するものを返す
  const pageDataFromPageData = pageDataList.find((pageData) => {
    const { pageName } = pageData
    return classNames.includes(pageName as PageNameType)
  })

  return pageDataFromPageData
}

export default GetPageDataFromPageDataList
