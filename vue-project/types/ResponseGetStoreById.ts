export type ResponseGetStoreById = {
  data: ResponseData | ErrorData
}

type ErrorData = {
  errorMessage: string
}

type ResponseData = {
  successMessage: string
  store: StoreData
}

type StoreData = {
  id: number
  name: string
  client_id: number
  created_at: Date
  updated_at: Date
}
