<script setup lang="ts">
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { Link, usePage } from '@inertiajs/vue3';
import {
    ApplicationLogo,
    DesktopNav,
    MobileNav,
    UserDropdown,
    HamburgerIcon,
    LanguageSwitcher,
} from '@/Components';
import { getNavigationLinks, getGuestDropdownLinks } from '@/Data';

const { t } = useI18n();
const showingNavigationDropdown = ref(false);
const page = usePage();
const isAuthenticated = page.props.auth && page.props.auth.user;

const navigationLinks = getNavigationLinks(t, page.props.auth.permissions, !!isAuthenticated);
const guestLinks = getGuestDropdownLinks(t);
</script>

<template>
    <nav class="bg-white border-b border-gray-100">
        <div class="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('home')">
                            <ApplicationLogo
                                class="block h-9 w-auto text-gray-800"
                            />
                        </Link>
                    </div>
                    <!-- Desktop Navigation Links -->
                    <DesktopNav :links="navigationLinks" />
                </div>
                <!-- Auth Links -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <LanguageSwitcher />
                    <UserDropdown
                        v-if="isAuthenticated"
                    />
                    <Link
                        v-else
                        v-for="link in guestLinks"
                        :key="link.name"
                        :href="route(link.route)"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent
                         transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        {{ link.label }}
                    </Link>
                </div>
                <!-- Hamburger for mobile -->
                <div class="-me-2 flex items-center sm:hidden">
                    <button
                        @click="showingNavigationDropdown = !showingNavigationDropdown"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500
                         hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                    >
                        <HamburgerIcon
                            class="h-6 w-6"
                            :aria-expanded="showingNavigationDropdown"
                        />
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
