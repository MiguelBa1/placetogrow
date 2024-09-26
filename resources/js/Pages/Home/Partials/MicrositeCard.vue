<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { type Microsite } from '../index';

const { microsite } = defineProps<{
    microsite: Microsite;
}>();

const handleImageError = (event: Event) => {
    const target = event.target as HTMLImageElement;
    target.src = '/images/placeholder.png';
};

const getMicrositeRoute = () => {
    if (['invoice', 'basic'].includes(microsite.type)) {
        return route('payments.show', {
            microsite: microsite.slug
        });
    }

    if (microsite.type === 'subscription') {
        return route('subscription-payments.show', {
            microsite: microsite.slug
        });
    }

    console.error('Unknown microsite type:', microsite.type);
    return '#';
};

</script>

<template>
    <Link
        :href="getMicrositeRoute()"
        class="grid gap-2 p-4 max-w-sm rounded overflow-hidden border bg-white
               shadow-sm hover:text-blue-500
               cursor-pointer"
    >
        <div class="flex justify-center items-center">
            <img
                class="h-24 w-24"
                :src="microsite.logo"
                :alt="microsite.name"
                @error="handleImageError"
                loading="lazy"
            />
        </div>
        <div class="text-xl text-center">{{ microsite.name }}</div>
    </Link>
</template>
