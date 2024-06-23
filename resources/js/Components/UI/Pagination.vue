<template>
    <div class="flex items-center justify-center space-x-2 mt-4">
        <button
            v-for="(link, index) in links"
            :key="link.label"
            @click="changePage(link.url)"
            :class="{
                'bg-blue-500 text-white': link.active,
                'bg-gray-200 text-black': !link.active,
                'cursor-not-allowed': !link.url,
                'whitespace-nowrap': true,
                'w-10 h-10': index !== 0 && index !== links.length - 1,
                'px-4 py-2': index === 0 || index === links.length - 1,
            }"
            :disabled="!link.url || link.active"
            class="rounded-full hover:bg-gray-300 flex items-center justify-center disabled:cursor-not-allowed"
        >
            <span v-html="link.label"></span>
        </button>
    </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { type PaginationLink } from '@/Components';

defineProps<{
    links: PaginationLink[];
}>();

const changePage = (url: string | null) => {
    if (url) {
        router.visit(url, { preserveState: true, preserveScroll: true });
    }
};
</script>
