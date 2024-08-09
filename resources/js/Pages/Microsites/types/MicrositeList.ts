import { ApiResourceLink, ApiResourceMeta } from '@/types';

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

export type MicrositesPaginatedResponse = {
    data: MicrositeListItem[];
    links: ApiResourceLink;
    meta: ApiResourceMeta;
};
