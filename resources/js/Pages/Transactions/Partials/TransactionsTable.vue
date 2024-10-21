<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { DataTable, Pagination } from "@/Components";
import { TransactionsPaginatedResponse, getTransactionTableColumns, getStatusClass } from "../index";
import { EyeIcon } from '@heroicons/vue/16/solid';
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
    transactions: TransactionsPaginatedResponse;
}>();

const columns = getTransactionTableColumns(t);

const { auth: { permissions } } = usePage().props;

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
        <template #cell-actions="{ row }">
            <div class="flex justify-center">
                <Link
                    v-if="permissions.includes('view_transaction')"
                    :href="route('transactions.show', row.reference)"
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

