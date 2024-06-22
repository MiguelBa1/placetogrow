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
                <Button @click="router.visit(route('microsites.create', {
                    page: microsites.current_page || 1,
                }))">Create Microsite</Button>
            </div>
        </template>

        <MicrositesTable v-if="microsites.data.length > 0" :microsites="microsites" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">No microsites found</p>
        </div>
    </MainLayout>
</template>
