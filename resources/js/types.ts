export interface IPaginator<T> {
  currentPage: number
  data: Array<T>
  firstPageUrl: string
  from: number
  lastPage: number
  lastPageUrl: string
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
  nextPageUrl: string | null
  path: string
  perPage: number
  prevPageUrl: string | null
  to: number
  total: number
}

export interface IViewComponent {
  name: string
  props: Record<string, any>
}
