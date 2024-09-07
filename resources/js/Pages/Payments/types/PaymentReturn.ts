import { PaymentStatus } from '@/types/enums';

export type PaymentReturn = {
    reference: string;
    amount: number;
    currency: string;
    status: PaymentStatus;
    status_message: string;
    payment_method_name: string;
    authorization: string;
    created_at: string;
    payment_date: string;
}
