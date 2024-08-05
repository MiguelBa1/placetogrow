export type MicrositeInformation = {
    id: string;
    name: string;
    slug: string;
    logo: string;
    type: string;
    category: {
        id: string;
        name: string;
    };
    responsible_name: string;
    responsible_document_number: string;
    responsible_document_type: string;
    payment_currency: string;
    payment_expiration?: string;
    created_at: string;
    updated_at: string;

}
