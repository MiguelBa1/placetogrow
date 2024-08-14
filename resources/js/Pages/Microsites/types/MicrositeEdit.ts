export type MicrositeEditData = {
    id: number;
    name: string;
    slug: string;
    category_id: number;
    type: string;
    payment_currency: string;
    payment_expiration: string | null;
    responsible_name: string;
    responsible_document_number: string;
    responsible_document_type: string;
    logo: string;
}
