<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import {
    MicrositesPaginatedResponse,
    MicrositesTable,

} from './index';

defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();

</script>

<template>
    <Head>
        <title>Microsites</title>
    </Head>
    <MainLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800">Microsites</h2>
                <Button @click="router.visit(route('microsites.create'))">Create Microsite</Button>
            </div>
        </template>

        <MicrositesTable :microsites="microsites" />
    </MainLayout>
</template>
