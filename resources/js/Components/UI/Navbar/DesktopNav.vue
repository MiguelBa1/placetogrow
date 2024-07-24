<script setup lang="ts">
import { NavLink } from '@/Components';

const { links } = defineProps<{
    links: {
        name: string;
        label: string;
        route: string;
        method?: string;
        as?: string;
    }[]
}>();

</script>

<template>
    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <NavLink
            v-for="link in links"
            :key="link.name"
            :href="route(link.route)"
            :active="route().current(link.route)"
        >
            {{ link.label }}
        </NavLink>
    </div>
</template>
