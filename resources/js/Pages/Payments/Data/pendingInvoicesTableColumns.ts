export const getPendingInvoicesTableColumns = (t: (key: string) => string) => [
    { key: 'reference', label: t('invoicePayments.show.pendingInvoices.table.reference') },
    { key: 'status', label: t('invoicePayments.show.pendingInvoices.table.status') },
    { key: 'name', label: t('invoicePayments.show.pendingInvoices.table.name') },
    { key: 'amount', label: t('invoicePayments.show.pendingInvoices.table.amount') },
    { key: 'late_fee', label: t('invoicePayments.show.pendingInvoices.table.late_fee') },
    { key: 'total_amount', label: t('invoicePayments.show.pendingInvoices.table.total_amount') },
    { key: 'currency', label: t('invoicePayments.show.pendingInvoices.table.currency') },
    { key: 'expiration_date', label: t('invoicePayments.show.pendingInvoices.table.expiration_date') },
    { key: 'actions', label: t('invoicePayments.show.pendingInvoices.table.actions') },
];
