export type TableItem = {
    id: number;
    name: string;
    originalUrl: string;
    shortUrl: string;
    type: string;
    expanded?: boolean;
    urls?: any[];
};