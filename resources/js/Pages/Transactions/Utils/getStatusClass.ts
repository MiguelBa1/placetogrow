import { PaymentStatus } from "@/types/enums";

export function getStatusClass(status: PaymentStatus): string {
    switch (status) {
        case PaymentStatus.APPROVED:
            return 'text-green-600';
        case PaymentStatus.REJECTED:
            return 'text-red-600';
        case PaymentStatus.PENDING:
            return 'text-yellow-500';
        default:
            return '';
    }
}
