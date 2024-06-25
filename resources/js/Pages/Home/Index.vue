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

defineProps<{
    microsites: MicrositesPaginatedResponse;
    categories: Categories;
}>();

</script>

<template>
    <Head>
        <title>Home</title>
    </Head>
    <MainLayout>
        <template #header>
            <HomeHeader />
        </template>

        <div class="space-y-6">
            <CategoryList :categories="categories" />
            <MicrositeList :microsites="microsites.data" />
            <Pagination
                :links="microsites.links"
            />
        </div>
    </MainLayout>
</template>
