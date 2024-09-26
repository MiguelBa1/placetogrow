import { SubscriptionStatus } from '@/types/enums';

export const formattedSubscriptionStatus = (t: (key: string) => string, status: SubscriptionStatus) => {
    switch (status) {
        case SubscriptionStatus.ACTIVE:
            return t('payments.result.status.active');
        case SubscriptionStatus.INACTIVE:
            return t('payments.result.status.inactive');
        case SubscriptionStatus.CANCELED:
            return t('payments.result.status.canceled');
        case SubscriptionStatus.PENDING:
            return t('payments.result.status.pending');
        default:
            return t('payments.result.status.unknown');
    }
}
