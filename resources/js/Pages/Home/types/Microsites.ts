import { type PaginatedResponse } from '@/Components';

export type Microsite = {
    id: number;
    name: string;
    logo: string;
    slug: string;
    type: string;
};

export type MicrositesPaginatedResponse = PaginatedResponse<Microsite>;
