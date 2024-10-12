<script setup lang="ts">
import { MainLayout } from '@/Layouts';
import { Head } from '@inertiajs/vue3';
import {
    ChartData,
    ApprovedTransactionsByMicrositeTypeChart,
    InvoiceDistributionChart,
    SubscriptionDistributionChart,
    TopMicrositesChart,
    PaymentsOverTimeChart,
} from '@/Pages/Dashboard';
import { useI18n } from 'vue-i18n';

const { data, lastUpdated } = defineProps<{
    data: ChartData;
    lastUpdated: string;
}>();

const { t } = useI18n();
</script>

<template>
    <Head>
        <title>{{ t('dashboard.index.title') }}</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ t('dashboard.index.title') }}
            </h2>
        </template>
        <div class="col-span-3">
            <p class="mb-4 text-gray-600">
                {{ t('dashboard.index.general_information') }}
            </p>

            <p class="mb-4 text-gray-500 text-sm">
                {{ t('dashboard.index.last_updated', { date: lastUpdated }) }}
            </p>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-white shadow rounded p-4">
                <PaymentsOverTimeChart :data="data.paymentsOverTime" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <InvoiceDistributionChart :data="data.invoiceDistribution" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <TopMicrositesChart :data="data.topMicrositesByTransactions" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <SubscriptionDistributionChart :data="data.subscriptionDistribution" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <ApprovedTransactionsByMicrositeTypeChart :data="data.approvedTransactionsByMicrositeType" />
            </div>
        </div>
    </MainLayout>
</template>
