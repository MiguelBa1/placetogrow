export const getInvoiceTableColumns = (t: (key: string) => string) => [
    { key: 'reference', label: t('customerInvoices.show.table.reference') },
    { key: 'microsite', label: t('customerInvoices.show.table.microsite') },
    { key: 'name', label: t('customerInvoices.show.table.name') },
    { key: 'document_number', label: t('customerInvoices.show.table.document_number') },
    { key: 'amount', label: t('customerInvoices.show.table.amount') },
    { key: 'status', label: t('customerInvoices.show.table.status') },
    { key: 'expiration_date', label: t('customerInvoices.show.table.expiration_date') },
];
