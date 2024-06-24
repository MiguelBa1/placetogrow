<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';

const { props } = usePage();

console.log(props)

</script>

<template>
    <Head>
        <title>Welcome</title>
    </Head>
    <MainLayout>
        <div>
            <h1>Welcome Page</h1>
        </div>
    </MainLayout>
</template>
