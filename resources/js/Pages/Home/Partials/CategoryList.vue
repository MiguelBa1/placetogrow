<script setup lang="ts">
import { CategoryCard } from './index';
import { type Categories } from '../index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    categories: Categories;
}>();

</script>

<template>
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-left">
            {{ t('home.index.categories') }}
        </h2>
        <div class="flex gap-4 flex-wrap justify-center md:flex-nowrap">
            <template v-for="category in categories" :key="category.id">
                <CategoryCard :category="category" />
            </template>
        </div>
    </div>
</template>
