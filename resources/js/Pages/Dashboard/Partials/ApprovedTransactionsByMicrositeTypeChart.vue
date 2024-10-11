<script setup lang="ts">
import { defineProps } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps<{
    data: {
        microsite_type: string;
        approved_transactions: number;
    }[];
}>();

const maxApprovedTransactions = Math.max(...props.data.map(item => item.approved_transactions));

const chartData = {
    labels: props.data.map(item => item.microsite_type),
    datasets: [
        {
            label: 'Approved Transactions',
            backgroundColor: ['#42A5F5', '#66BB6A', '#FFCE56'],
            data: props.data.map(item => item.approved_transactions),
        },
    ],
};

const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            display: false,
        },
        title: {
            display: true,
            text: 'Approved Transactions by Microsite Type',
            font: {
                family: 'Poppins',
                size: 16,
            },
        },
    },
    scales: {
        y: {
            beginAtZero: true,
            suggestedMax: maxApprovedTransactions + 4,
        }
    }
};
</script>

<template>
    <div class="min-h-[300px]">
        <Bar :data="chartData" :options="options" />
    </div>
</template>
