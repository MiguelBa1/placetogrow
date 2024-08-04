import { PaymentStatus } from "@/types/enums";

export type TransactionInformation = {
    data: {
        id: number;
        reference: string;
        description: string;
        status: {
            value: PaymentStatus;
            label: string;
        };
        amount: number;
        payment_date: string;
        created_at: string;
        updated_at: string;
        currency: string;
        microsite: string;
        additional_data: {
            [key: string]: string;
        } | null;
        customer: {
            id: number;
            name: string;
            last_name: string;
            email: string;
            document_number: string;
            document_type: string;
        };
    }
}
