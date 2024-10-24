import { MicrositeType } from '@/Pages/Microsites';

export type MicrositeEditData = {
    id: number;
    name: string;
    slug: string;
    category_id: number;
    type: MicrositeType;
    payment_currency: string;
    payment_expiration: string | null;
    responsible_name: string;
    responsible_document_number: string;
    responsible_document_type: string;
    settings: {
        retry?: {
            max_retries: number;
            retry_backoff: number;
        };
        late_fee?: {
            type: string;
            value: number;
        };
    };
    logo: string;
}
