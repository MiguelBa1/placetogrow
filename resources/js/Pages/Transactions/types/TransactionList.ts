import { ApiResourceLink, ApiResourceMeta } from '@/types';

export type TransactionListItem = {
    id: number;
    reference: string;
    microsite: string;
    status: {
        value: string;
        label: string;
    };
    amount: number;
    payment_date: string | null;
}

export type TransactionsPaginatedResponse = {
    data: TransactionListItem[];
    links: ApiResourceLink;
    meta: ApiResourceMeta;
};
