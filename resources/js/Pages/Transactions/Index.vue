<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { MainLayout } from "@/Layouts";
import { TransactionsPaginatedResponse, TransactionsTable, IndexHeader } from "./index";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
    transactions: TransactionsPaginatedResponse;
    filters: { microsite: string, status: string, reference: string, document: number };
    paymentStatuses: { value: string; label: string }[];
}>();

</script>

<template>
    <Head>
        <title>
            {{ t('transactions.index.title') }}
        </title>
    </Head>

    <MainLayout>

        <template #header>
            <IndexHeader
                :filters="filters"
                :paymentStatuses="paymentStatuses"
            />
        </template>

        <TransactionsTable v-if="transactions.data.length > 0" :transactions="transactions" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">
                {{ t('transactions.index.table.no_transactions') }}
            </p>
        </div>

    </MainLayout>
</template>
