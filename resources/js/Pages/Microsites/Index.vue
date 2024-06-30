<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import {
    MicrositesPaginatedResponse,
    MicrositesTable,
    IndexHeader,
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
            <IndexHeader :microsites="microsites" />
        </template>

        <MicrositesTable v-if="microsites.data.length > 0" :microsites="microsites" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">
                {{ t('microsites.index.noMicrosites') }}
            </p>
        </div>
    </MainLayout>
</template>
