export type InvoiceItem = {
    id: number;
    reference: string;
    name: string;
    document_number: string;
    amount: number;
    expiration_date: string;
    status: string;
}

export type InvoiceList = {
    data: InvoiceItem[];
};
