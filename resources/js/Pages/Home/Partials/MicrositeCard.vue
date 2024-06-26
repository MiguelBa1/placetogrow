<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { type Microsite } from '../index';

defineProps<{
    microsite: Microsite;
}>();

const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/images/placeholder.png';
};

</script>

<template>
    <Link
        :href="route('microsites.show', {
            microsite: microsite.id
        })"
        class="grid gap-2 p-4 max-w-sm rounded overflow-hidden border bg-white
               shadow-sm hover:shadow-lg
               cursor-pointer transition duration-300 ease-in-out"
    >
        <div class="flex justify-start items-center">
            <img
                class="h-24 w-24"
                :src="microsite.logo"
                :alt="microsite.name"
                @error="handleImageError"
            />
        </div>
        <div class="font-bold text-xl">{{ microsite.name }}</div>
    </Link>
</template>
