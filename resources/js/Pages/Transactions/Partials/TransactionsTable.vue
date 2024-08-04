<script setup lang="ts">
import dayjs from "dayjs";
import { Link } from "@inertiajs/vue3";
import { DataTable, Pagination } from "@/Components";
import { TransactionsPaginatedResponse, getTransactionTableColumns, getStatusClass } from "../index";
import { EyeIcon } from '@heroicons/vue/16/solid';
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
    transactions: TransactionsPaginatedResponse;
}>();

const columns = getTransactionTableColumns(t);

</script>

<template>
    <DataTable
        :rows="transactions.data"
        :columns="columns"
    >
        <template #cell-status="{ row }">
            <span
                class="font-bold"
                :class="getStatusClass(row.status.value)"
            >
                {{ row.status.label }}
            </span>
        </template>
        <template #cell-amount="{ row }">
            {{ '$ ' + new Intl.NumberFormat().format(row.amount) }}
        </template>
        <template #cell-payment_date="{ row }">
            {{
                row.payment_date ?
                    dayjs(row.payment_date).format('DD/MM/YYYY HH:mm')
                    : '-'
            }}
        </template>
        <template #cell-actions="{ row }">
            <div class="flex justify-center">
                <Link
                    href="#"
                    class="text-gray-700 hover:text-black"
                >
                    <EyeIcon class="h-5 w-5" />
                </Link>
            </div>
        </template>
    </DataTable>
    <Pagination
        :links="transactions.meta.links"
    />
</template>

