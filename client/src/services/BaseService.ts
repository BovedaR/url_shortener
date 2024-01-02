import axios, { type AxiosResponse } from "axios";
import type { ApiResponse } from '../types/ApiResponse';
import { ApiErrorHandler } from '../middleware/ApiErrorHandler';

export const instance = axios.create();

instance.interceptors.request.use((config) => {
  const token = localStorage.getItem('token');
  if (token) config.headers['Authorization'] = 'Bearer ' + token;
  return config;
}, error=>Promise.reject(error));

instance.interceptors.response.use((response)=>response, error=>{
  ApiErrorHandler(error);
  Promise.reject(error)
});

export interface AxiosApiResponse<T> extends AxiosResponse<ApiResponse<T>> {}

export class BaseService {
  protected apiBaseUrl: string;

  constructor(url: string) {
    this.apiBaseUrl = `${import.meta.env.VITE_API_BASE_URL}/${url}` || '';
    console.log(this.apiBaseUrl)
  }

  protected get<T>(url: string): Promise<AxiosApiResponse<T>> {
    return instance.get<ApiResponse<T>>(`${this.apiBaseUrl}/${url}`);
  }

  protected post<T>(url: string, data: any): Promise<AxiosApiResponse<T>> {
    return instance.post<ApiResponse<T>>(`${this.apiBaseUrl}/${url}`, data);
  }

  protected put<T>(url: string, data: any): Promise<AxiosApiResponse<T>> {
    return instance.put<ApiResponse<T>>(`${this.apiBaseUrl}/${url}`, data);
  }

  protected delete<T>(url: string): Promise<AxiosApiResponse<T>> {
    console.log(url)
    return instance.delete<ApiResponse<T>>(`${this.apiBaseUrl}/${url}`);
  }
}