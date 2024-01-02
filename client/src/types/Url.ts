import type { UrlCollection } from "./UrlCollection";

export type Url = {
  id: number;
  name: string;
  originalUrl: string;
  shortUrl: string;
  collectionId: number | null;
  collection?: UrlCollection;
  type?: string;
  createdAt: string;
  updatedAt: string;
};