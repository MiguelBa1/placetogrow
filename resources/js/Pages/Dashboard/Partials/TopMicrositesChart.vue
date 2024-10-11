<script setup lang="ts">
import { defineProps } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps<{
    data: {
        microsite_name: string;
        transaction_count: number;
    }[];
}>();

const maxTransactions = Math.max(...props.data.map(item => item.transaction_count));

const chartData = {
    labels: props.data.map(item => item.microsite_name),
    datasets: [
        {
            label: 'Transactions',
            backgroundColor: ['#42A5F5', '#66BB6A', '#FFCE56', '#FF6384', '#36A2EB'],
            data: props.data.map(item => item.transaction_count),
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
            text: 'Top 5 Microsites by Transactions',
            font: {
                family: 'Poppins',
                size: 16,
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            suggestedMax: maxTransactions + 2,
        }
    }
};
</script>

<template>
    <div class="min-h-[300px]">
        <Bar :data="chartData" :options="options" />
    </div>
</template>
