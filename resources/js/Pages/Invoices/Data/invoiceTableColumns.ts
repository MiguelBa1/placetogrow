export const getInvoiceTableColumns = (t: (key: string) => string) => [
    { key: 'reference', label: t('invoices.index.table.reference') },
    { key: 'name', label: t('invoices.index.table.name') },
    { key: 'document_number', label: t('invoices.index.table.document_number') },
    { key: 'amount', label: t('invoices.index.table.amount') },
    { key: 'expiration_date', label: t('invoices.index.table.expiration_date') },
];
