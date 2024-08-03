<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { MainLayout } from "@/Layouts";
import { TransactionsPaginatedResponse, TransactionsTable } from "./index";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
    transactions: TransactionsPaginatedResponse;
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
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ t('transactions.index.title') }}
                </h2>
            </div>
        </template>

        <TransactionsTable v-if="transactions.data.length > 0" :transactions="transactions" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">
                No transactions found.
            </p>
        </div>

    </MainLayout>
</template>
