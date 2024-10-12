<script setup lang="ts">
import { defineProps } from 'vue';
import { Line } from 'vue-chartjs';
import { useI18n } from 'vue-i18n';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';
import dayjs from 'dayjs';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

const { t } = useI18n();

const props = defineProps<{
    data: {
        day: string;
        currency: string;
        total_amount: string;
    }[];
}>();

const formattedLabels = [...new Set(props.data.map(item => dayjs(item.day).format('MMM DD')))];

const chartData = {
    labels: formattedLabels,
    datasets: [
        {
            label: 'COP',
            borderColor: '#42A5F5',
            data: props.data
                .filter(item => item.currency === 'COP')
                .map(item => parseFloat(item.total_amount)),
            fill: false,
        },
        {
            label: 'USD',
            borderColor: '#66BB6A',
            data: props.data
                .filter(item => item.currency === 'USD')
                .map(item => parseFloat(item.total_amount)),
            fill: false,
        },
    ],
};

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: t('dashboard.index.payments_over_time'),
            font: {
                family: 'Poppins',
                size: 18,
            },
        },
    },
    scales: {
        x: {
            title: {
                display: false,
            },
        },
        y: {
            title: {
                display: false,
            },
            beginAtZero: true,
        },
    },
};
</script>

<template>
    <Line :data="chartData" :options="options" height="400" />
</template>
