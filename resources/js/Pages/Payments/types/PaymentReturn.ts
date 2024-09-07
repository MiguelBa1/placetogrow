import { PaymentStatus } from '@/types/enums';

export type PaymentReturn = {
    payment: {
        reference: string;
        amount: number;
        currency: string;
        status: PaymentStatus;
        status_message: string;
        payment_method_name: string;
        authorization: string;
        created_at: string;
        payment_date: string;
    };
    customerName: string;
    micrositeName: string;
}
