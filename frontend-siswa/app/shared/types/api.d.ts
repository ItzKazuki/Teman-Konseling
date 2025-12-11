interface ApiMeta {
  status_code: number;
  timestamp: string;
}

interface ApiSuccess<T> {
  status: true;
  message: string;
  data: T;
  pagination?: Pagination;
  meta: ApiMeta;
}

interface ApiError {
  status: false;
  message: string;
  errors?: any;
  meta: ApiMeta;
}

interface Pagination {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
  has_more_pages: boolean;
}

type ApiResponse<T = any> = ApiSuccess<T> | ApiError;

type ApiStreamResponse = Blob;
