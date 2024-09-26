import { SubscriptionStatus } from '@/types/enums';

export const getStatusClass = (status: SubscriptionStatus) => {
    switch (status) {
        case SubscriptionStatus.ACTIVE:
            return 'text-green-600';
        case SubscriptionStatus.INACTIVE:
            return 'text-red-600';
        case SubscriptionStatus.PENDING:
            return 'text-yellow-600';
        case SubscriptionStatus.CANCELED:
            return 'text-red-600';
        default:
            return '';
    }
};
