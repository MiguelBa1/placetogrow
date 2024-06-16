<script setup lang="ts">
import { getNavigationLinks } from '@/Data';
import { NavLink } from '@/Components';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const navigationLinks = getNavigationLinks(t);
</script>

<template>
    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
        <NavLink
            v-for="link in navigationLinks"
            :key="link.name"
            :href="route(link.route)"
            :active="route().current(link.route)"
        >
            {{ link.label }}
        </NavLink>
    </div>
</template>
