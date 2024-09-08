<script setup lang="ts">
import { DataTable } from '@/Components';
import { MainLayout } from '@/Layouts';
import { InvoiceList, getInvoiceTableColumns, getStatusClass } from './index';
import { useI18n } from 'vue-i18n';
import { Head } from '@inertiajs/vue3';

const { t } = useI18n();

defineProps<{
    invoices: InvoiceList;
    customer: {
        document_number: string;
        email: string;
    };
}>();

const invoiceColumns = getInvoiceTableColumns(t);

</script>

<template>
    <Head>
        <title>
            {{ t('customerInvoices.show.title', { email: customer.email }) }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                {{ t('customerInvoices.show.title', { email: customer.email }) }}
            </h2>
        </template>

        <DataTable
            v-if="invoices.data?.length > 0"
            :columns="invoiceColumns" :rows="invoices.data" class="rounded-lg"
        >
            <template #cell-microsite="{ row }">
                {{ row.microsite.name }}
            </template>
            <template #cell-status="{ row }">
                <span
                    :class="getStatusClass(row.status.value)"
                >
                    {{ row.status.label }}
                </span>
            </template>
            <template #cell-amount="{ row }">
                {{ `$ ${row.amount}` }}
            </template>
        </DataTable>
        <div
            v-else
            class="flex justify-center items-center h-96"
        >
            <p class="text-gray-500">
                {{ t('customerInvoices.show.noInvoices') }}
            </p>
        </div>

    </MainLayout>
</template>
