import type { Url } from '../types/Url';
import { BaseService } from './BaseService';

export class UrlService extends BaseService {
  constructor() {
    super("urls");
  }

  getUrls() {
    return this.get<Url[]>(``);
  }

  getUrl(id: number) {
    return this.get<Url>(`${id}`);
  }

  createUrl(data: any) {
    return this.post<Url>(``, data);
  }

  updateUrl(id: number, data: any) {
    return this.put<Url>(`${id}`, data);
  }

  deleteUrl(id: number) {
    return this.delete<Url>(`${id}`);
  }
}