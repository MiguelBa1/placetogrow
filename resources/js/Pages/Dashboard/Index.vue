<script setup lang="ts">
import { MainLayout } from '@/Layouts';
import { Head, router } from '@inertiajs/vue3';
import {
    ChartData,
    ApprovedTransactionsByMicrositeTypeChart,
    InvoiceDistributionChart,
    SubscriptionDistributionChart,
    TopMicrositesChart,
    PaymentsOverTimeChart,
} from '@/Pages/Dashboard';
import { useI18n } from 'vue-i18n';
import { ref } from 'vue';
import { InputField, Button } from '@/Components';

const {
    data,
    lastUpdated,
    startDate: initialStartDate,
    endDate: initialEndDate,
} = defineProps<{
    data: ChartData;
    lastUpdated: string;
    startDate: string;
    endDate: string;
}>();

const { t } = useI18n();

const today = new Date().toISOString().split('T')[0];

const startDate = ref(initialStartDate);
const endDate = ref(initialEndDate);

const handleDateFilter = () => {
    if (startDate.value && endDate.value) {
        router.visit('/dashboard', {
            method: 'get',
            data: {
                start_date: startDate.value,
                end_date: endDate.value,
            },
        });
    }
};
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

        <div
            class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
        >
            <div class="flex justify-between items-center col-span-3">
                <div>
                    <p class="mb-4 text-gray-600">
                        {{ t('dashboard.index.general_information') }}
                    </p>
                    <p class="mb-4 text-gray-500 text-sm">
                        {{
                            t('dashboard.index.last_updated', {
                                date: lastUpdated,
                            })
                        }}
                    </p>
                </div>

                <form
                    class="flex gap-4 items-end mb-4"
                    @submit.prevent="handleDateFilter"
                >
                    <InputField
                        id="start_date"
                        type="date"
                        label="Fecha de Inicio"
                        v-model="startDate"
                    />

                    <InputField
                        id="end_date"
                        type="date"
                        label="Fecha de Fin"
                        :max="today"
                        v-model="endDate"
                    />
                    <Button type="submit" variant="secondary" color="blue">
                        Filtrar
                    </Button>
                </form>
            </div>
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
