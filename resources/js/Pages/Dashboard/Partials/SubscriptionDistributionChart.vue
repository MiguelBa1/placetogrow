<script setup lang="ts">
import { Bar } from 'vue-chartjs';
import { useI18n } from 'vue-i18n';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const { t } = useI18n();

const props = defineProps<{
    data: {
        status: string;
        subscription_count: number;
    }[];
}>();

const maxSubscriptions = Math.max(...props.data.map(item => item.subscription_count));

const chartData = {
    labels: props.data.map(item => item.status),
    datasets: [
        {
            label: 'Subscriptions',
            backgroundColor: ['#42A5F5', '#66BB6A', '#FFCE56', '#FF6384', '#36A2EB'],
            data: props.data.map(item => item.subscription_count),
        },
    ],
};

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false,
        },
        title: {
            display: true,
            text: t('dashboard.index.subscription_distribution'),
            font: {
                family: 'Poppins',
                size: 16,
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            suggestedMax: maxSubscriptions + 2,
        }
    }
};
</script>

<template>
    <Bar :data="chartData" :options="options" height="300" />
</template>
