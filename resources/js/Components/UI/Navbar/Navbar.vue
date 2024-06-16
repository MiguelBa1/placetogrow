<script setup lang="ts">
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Link, usePage } from '@inertiajs/vue3';
import {
    ApplicationLogo,
    DesktopNav,
    MobileNav,
    UserDropdown,
} from '@/Components';
import { getNavigationLinks, getGuestLinks } from '@/Data';

const { t } = useI18n();
const showingNavigationDropdown = ref(false);
const page = usePage();
const isAuthenticated = page.props.auth && page.props.auth.user;

const navigationLinks = getNavigationLinks(t);
const guestLinks = getGuestLinks(t);
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="isAuthenticated ? route('dashboard') : '/'">
                            <ApplicationLogo
                                class="block h-9 w-auto fill-current text-gray-800"
                            />
                        </Link>
                    </div>
                    <!-- Desktop Navigation Links -->
                    <DesktopNav
                        v-if="isAuthenticated"
                        :links="navigationLinks"
                    />
                </div>
                <!-- Auth Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <template v-if="isAuthenticated">
                        <UserDropdown />
                    </template>
                    <template v-else>
                        <Link
                            v-for="link in guestLinks"
                            :key="link.name"
                            :href="route(link.route)"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                        >
                            {{ link.label }}
                        </Link>
                    </template>
                </div>
                <!-- Hamburger for mobile -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500
                         hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile Navigation Menu -->
        <MobileNav
            :links="isAuthenticated ? navigationLinks : guestLinks"
            v-if="showingNavigationDropdown"
        />
    </nav>
</template>
