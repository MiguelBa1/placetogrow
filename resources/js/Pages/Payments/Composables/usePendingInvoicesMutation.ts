import axios from 'axios';
import { useMutation } from '@tanstack/vue-query';
import { PendingInvoice } from '@/Pages/Payments';

type UsePendingInvoicesMutationProps = {
    document_number: string;
    reference: string;
    micrositeSlug: string;
}

export function usePendingInvoicesMutation() {
    return useMutation<{ data: PendingInvoice[] }, Error, UsePendingInvoicesMutationProps>({
        mutationFn: async ({ document_number, reference, micrositeSlug }) => {
            const { data } = await axios.get(route('invoice-payments.pending-invoices', micrositeSlug), {
                params: {
                    document_number,
                    reference
                }
            });
            return data;
        }
    });
}
