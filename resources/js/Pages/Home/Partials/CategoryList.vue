<script setup lang="ts">
import { CategoryCard } from './index';
import { type Categories } from '../index';

defineProps<{
    categories: Categories;
}>();

</script>

<template>
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-left">Categories</h2>
        <div class="flex gap-4 flex-wrap justify-center md:flex-nowrap">
            <template v-for="category in categories" :key="category.id">
                <CategoryCard :category="category" />
            </template>
        </div>
    </div>
</template>
