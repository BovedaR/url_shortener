import type { UrlCollection } from '../types/UrlCollection';
import { BaseService } from './BaseService';

export class UrlCollectionService extends BaseService {
  constructor() {
    super("urlcollections");
  }

  getUrlCollections() {
    return this.get<UrlCollection[]>(``);
  }

  getUrlCollection(id: number) {
    return this.get<UrlCollection>(`${id}`);
  }

  createUrlCollection(data: any) {
    return this.post<UrlCollection>(``, data);
  }

  updateUrlCollection(id: number, data: any) {
    return this.put<UrlCollection>(`${id}`, data);
  }

  deleteUrlCollection(id: number) {
    return this.delete<UrlCollection>(`${id}`);
  }
}