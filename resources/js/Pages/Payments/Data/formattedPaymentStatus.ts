import { PaymentStatus } from "@/types/enums";

export const formattedPaymentStatus = (t: (key: string) => string, status: PaymentStatus) => {
    switch (status) {
        case PaymentStatus.PENDING:
            return t('payments.result.status.pending');
        case PaymentStatus.APPROVED:
            return t('payments.result.status.approved');
        case PaymentStatus.REJECTED:
            return t('payments.result.status.rejected');
        default:
            return t('payments.result.status.unknown');
    }
}
