<script setup lang="ts">
import { Doughnut } from 'vue-chartjs';
import { useI18n } from 'vue-i18n';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, ChartOptions } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const { t } = useI18n();

const props = defineProps<{
    data: {
        status: string;
        invoice_count: number;
    }[];
}>();

const chartData = {
    labels: props.data.map(item => item.status),
    datasets: [
        {
            label: 'Invoices',
            backgroundColor: ['#42A5F5', '#66BB6A', '#FFCE56', '#FF6384', '#36A2EB'],
            data: props.data.map(item => item.invoice_count),
        },
    ],
};

const options: ChartOptions<'doughnut'> = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: t('dashboard.index.invoice_distribution'),
            font: {
                family: 'Poppins',
                size: 18,
            },
        },
    },
};
</script>

<template>
    <Doughnut :data="chartData" :options="options" height="300" />
</template>
