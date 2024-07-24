<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Pagination } from '@/Components';
import { MainLayout } from '@/Layouts';
import {
    HomeHeader,
    CategoryList,
    MicrositeList,
    type MicrositesPaginatedResponse,
    type Categories
} from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    microsites: MicrositesPaginatedResponse;
    categories: Categories;
}>();

</script>

<template>
    <Head>
        <title>
            {{ t('home.index.title') }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <HomeHeader />
        </template>

        <div class="space-y-6">
            <CategoryList :categories="categories" />
            <div
                v-if="microsites.data.length > 0"
            >
                <MicrositeList :microsites="microsites.data" />
                <Pagination
                    :links="microsites.links"
                />
            </div>
            <div
                v-else
                class="text-center text-gray-500"
            >
                <p>
                    {{ t('home.index.noMicrosites') }}
                </p>
            </div>
        </div>
    </MainLayout>
</template>
