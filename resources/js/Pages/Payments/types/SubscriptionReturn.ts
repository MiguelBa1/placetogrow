import { SubscriptionStatus } from '@/types/enums';

export type SubscriptionReturn = {
    reference: string;
    status: SubscriptionStatus;
    status_message: string;
    payment_method_name: string;
    created_at: string;
    start_date: string;
    end_date: string;
}
