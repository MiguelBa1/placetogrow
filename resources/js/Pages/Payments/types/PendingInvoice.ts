export type PendingInvoice = {
    id: number;
    reference: string;
    document_number: string;
    status: {
        label: string;
        value: string;
    };
    name: string;
    amount: number;
    expiration_date: string;
}
