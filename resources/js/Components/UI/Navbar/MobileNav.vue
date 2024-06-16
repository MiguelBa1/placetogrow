<script setup lang="ts">
import { getNavigationLinks, getUserDropdownLinks } from '@/Data';
import { ResponsiveNavLink } from '@/Components';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const navigationLinks = getNavigationLinks(t);
const userDropdownLinks = getUserDropdownLinks(t);
</script>

<template>
    <div class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink
                v-for="link in navigationLinks"
                :key="link.name"
                :href="route(link.route)"
                :active="route().current(link.route)"
            >
                {{ link.label }}
            </ResponsiveNavLink>
        </div>
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $page.props.auth.user.name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <ResponsiveNavLink
                    v-for="link in userDropdownLinks"
                    :key="link.name"
                    :href="route(link.route)"
                    :method="link.method"
                    :as="link.as"
                >
                    {{ link.label }}
                </ResponsiveNavLink>
            </div>
        </div>
    </div>
</template>
