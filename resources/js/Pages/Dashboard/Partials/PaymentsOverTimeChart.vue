<script setup lang="ts">
import { defineProps } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js';
import dayjs from 'dayjs';  // Importar Day.js

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement);

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
            font: {
                family: 'Poppins',
                size: 14,
            },
        },
        title: {
            display: true,
            text: 'Payments Over Time (Last Month)',
            font: {
                family: 'Poppins',
                size: 18,
            },
        },
    },
    scales: {
        x: {
            title: {
                display: true,
                text: 'Day',
            },
        },
        y: {
            title: {
                display: true,
                text: 'Amount',
            },
            beginAtZero: true,
        },
    },
};
</script>

<template>
    <div class="min-h-[400px]">
        <Line :data="chartData" :options="options" />
    </div>
</template>
