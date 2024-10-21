<script setup lang="ts">
import { ref } from 'vue';
import { Button, DataTable } from '@/Components';
import { ExclamationCircleIcon } from '@heroicons/vue/24/outline';
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { getPendingInvoicesTableColumns, PendingInvoice } from '../index';

const { t } = useI18n();
const toast = useToast();

const { micrositeSlug, formData } = defineProps<{
    pendingInvoices: PendingInvoice[];
    micrositeSlug: string;
    formData: Record<string, string | number>;
}>();

const isSubmitting = ref(false);

const pendingInvoicesColumns = getPendingInvoicesTableColumns(t);

const handlePay = (invoiceId: number) => {
    isSubmitting.value = true;

    router.post(route('invoice-payments.store', {
        microsite: micrositeSlug,
        invoice: invoiceId
    }), formData, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(t('common.form.success'));
        },
        onError: (error) => {
            toast.error(error?.payment ?? t('common.form.error'));
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
}
</script>

<template>
    <div class="space-y-4">
        <DataTable
            :columns="pendingInvoicesColumns"
            :rows="pendingInvoices"
        >
            <template #cell-actions="{ row }">
                <Button
                    @click="handlePay(row.id)"
                    :disabled="isSubmitting"
                >
                    {{ t('invoicePayments.show.form.pay') }}
                </Button>
            </template>
        </DataTable>

        <p class="flex gap-2 text-sm text-gray-500">
            <ExclamationCircleIcon
                class="h-5 w-5 text-yellow-500"
                title="Recargo por mora si no se paga antes de la fecha de vencimiento"
            />
            {{ t('invoicePayments.show.form.lateFeeWarning') }}
        </p>
    </div>
</template>
