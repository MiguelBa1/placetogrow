import { InvoiceStatus } from "../index";

export const getStatusClass = (status: InvoiceStatus) => {
    switch (status) {
        case InvoiceStatus.PAID:
            return 'text-green-600';
        case InvoiceStatus.EXPIRED:
            return 'text-red-600';
        case InvoiceStatus.PENDING:
            return 'text-yellow-600';
        default:
            return '';
    }
};
