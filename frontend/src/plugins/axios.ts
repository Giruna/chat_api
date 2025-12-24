import axios, { AxiosError } from 'axios'
import type { InternalAxiosRequestConfig } from 'axios'

const api = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
  },
})

api.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    const token = localStorage.getItem('token')

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }

    return config
  }
)
api.interceptors.response.use(
  response => response,
  (error: AxiosError<any>) => {
    let message = 'An unexpected error occurred.'

    if (error.response) {
      const { status, statusText, data } = error.response

      if (data && data.success === false) {
        message = data.message || 'Request failed.'
      } else if (status) {
        message = `Server error (${status}): ${statusText || 'Unknown error'}`
      }
    } else if (error.request) {
      message = 'Network error. Please check your connection.'
    } else if (error.message) {
      message = error.message
    }

    return Promise.reject(new Error(message))
  }
)

export default api
