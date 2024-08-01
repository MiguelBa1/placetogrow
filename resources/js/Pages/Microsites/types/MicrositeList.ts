import { type PaginationLink } from '@/Components';

export type MicrositeListItem = {
    id: number;
    name: string;
    responsible_name: string;
    category: {
        id: number;
        name: string;
    };
    type: {
        value: string;
        label: string;
    };
    slug: string;
    payment_currency: string;
    payment_expiration?: string;
    deleted_at?: string;
};

export type ApiResourceLink = {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
};

export type ApiResourceMeta = {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
    links: PaginationLink[];
};

export type MicrositesPaginatedResponse = {
    data: MicrositeListItem[];
    links: ApiResourceLink;
    meta: ApiResourceMeta;
};
