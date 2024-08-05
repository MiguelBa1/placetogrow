<script setup lang="ts">
import { DataTable } from '@/Components';
import { InvoiceList, getInvoiceTableColumns, getStatusClass } from '../index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    invoiceList: InvoiceList;
}>();

const invoiceColumns = getInvoiceTableColumns(t);

</script>

<template>
    <DataTable :columns="invoiceColumns" :rows="invoiceList.data" class="rounded-lg">
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
</template>
