<script setup lang="ts">
import { DataTable } from '@/Components';
import { InvoiceList, getInvoiceTableColumns } from '../index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    invoiceList: InvoiceList;
}>();

const invoiceColumns = getInvoiceTableColumns(t);

</script>

<template>
    <DataTable :columns="invoiceColumns" :rows="invoiceList" class="rounded-lg">
        <template #cell-amount="{ row }">
            {{ `$ ${row.amount}` }}
        </template>
    </DataTable>
</template>
