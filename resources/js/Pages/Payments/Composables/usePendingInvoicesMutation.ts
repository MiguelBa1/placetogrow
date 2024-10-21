import axios, { AxiosError } from 'axios';
import { useMutation } from '@tanstack/vue-query';
import { PendingInvoice } from '@/Pages/Payments';

type UsePendingInvoicesMutationProps = {
    document_number: string | number;
    reference: string | number;
    micrositeSlug: string;
}

export function usePendingInvoicesMutation() {
    return useMutation<{ data: PendingInvoice[] }, AxiosError<{ errors: Record<string, string> }>, UsePendingInvoicesMutationProps>({
        mutationFn: async ({ document_number, reference, micrositeSlug }) => {
            const { data } = await axios.get<{ data: PendingInvoice[] }>(route('invoice-payments.pending-invoices', micrositeSlug), {
                params: {
                    document_number,
                    reference
                }
            });
            return data;
        }
    });
}
