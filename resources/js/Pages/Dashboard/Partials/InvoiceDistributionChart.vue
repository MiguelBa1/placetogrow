<script setup lang="ts">
import { defineProps } from 'vue';
import { Doughnut } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

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

const options = {
    responsive: true,
    plugins: {
        legend: {
            position: 'top',
        },
        title: {
            display: true,
            text: 'Invoice Distribution',
            font: {
                family: 'Poppins',
                size: 18,
            },
        },
    },
};
</script>

<template>
    <Doughnut :data="chartData" :options="options" />
</template>

