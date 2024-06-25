import { type PaginatedResponse } from '@/Components';

export type Microsite = {
    id: number;
    name: string;
    logo: string;
};

export type MicrositesPaginatedResponse = PaginatedResponse<Microsite>;
