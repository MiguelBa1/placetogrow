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

const props = defineProps<ChartData>();

</script>

<template>
    <Head>
        <title>Dashboard</title>
    </Head>

    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="md:col-span-2 bg-white shadow rounded p-4">
                <PaymentsOverTimeChart :data="props.paymentsOverTime" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <InvoiceDistributionChart :data="props.invoiceDistribution" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <TopMicrositesChart :data="props.topMicrositesByTransactions" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <SubscriptionDistributionChart :data="props.subscriptionDistribution" />
            </div>
            <div class="bg-white shadow rounded p-4">
                <ApprovedTransactionsByMicrositeTypeChart :data="props.approvedTransactionsByMicrositeType" />
            </div>
        </div>
    </MainLayout>
</template>
