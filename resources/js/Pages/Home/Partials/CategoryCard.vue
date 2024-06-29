<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { type Category } from '../index';

defineProps<{
    category: Category;
}>();

</script>

<template>
    <Link
        :href="route('home', {
            category: category.id
        })"
        class="flex md:flex-col gap-6 md:gap-2 p-4 w-full max-w-xs rounded
               overflow-hidden border bg-white shadow-sm hover:text-blue-500
               cursor-pointer"
    >
        <div class="flex justify-center items-center">
            <img class="h-12 w-12" :src="category.logo" :alt="category.name" />
        </div>
        <div class="flex justify-center items-center font-bold text-xl">{{ category.name }}</div>
    </Link>
</template>
