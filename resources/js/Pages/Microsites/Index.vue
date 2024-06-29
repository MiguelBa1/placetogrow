<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import {
    MicrositesPaginatedResponse,
    MicrositesTable,

} from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();

</script>

<template>
    <Head>
        <title>
            {{ t('microsites.index.title') }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{  t('microsites.index.header') }}
                </h2>
                <Button @click="router.visit(route('microsites.create', {
                    page: microsites.meta.current_page || 1,
                }))">
                    {{ t('microsites.index.createMicrosite') }}
                </Button>
            </div>
        </template>

        <MicrositesTable v-if="microsites.data.length > 0" :microsites="microsites" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">
                {{ t('microsites.index.noMicrosites') }}
            </p>
        </div>
    </MainLayout>
</template>
