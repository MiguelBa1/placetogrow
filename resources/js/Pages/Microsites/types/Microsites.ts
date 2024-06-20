import { type PaginatedResponse } from '@/Components';

export type Microsite = {
    id: number;
    name: string;
    responsible_name: string;
    category: {
        id: number;
        name: string;
    };
    type: string;
    payment_currency: string;
    payment_expiration: string;
};

export type MicrositesPaginatedResponse = PaginatedResponse & {
    data: Microsite[];
};
