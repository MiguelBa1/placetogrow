<script setup lang="ts">
import { MainLayout } from '@/Layouts';
import { Head, usePage } from '@inertiajs/vue3';

const props = usePage().props;

console.log(props);
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

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">You're logged in!</div>
        </div>
    </MainLayout>
</template>
