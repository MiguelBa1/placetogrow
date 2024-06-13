<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Link } from '@inertiajs/vue3';

const { t } = useI18n();
</script>

<template>
    <div class="min-h-screen bg-gray-100 space-y-6">
        <header class="shadow bg-white border-b border-gray-100">
            <nav class="flex justify-end items-center max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                >
                    {{ t('layouts.guestLayout.dashboard') }}
                </Link>

                <template v-else>
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        {{ t('layouts.guestLayout.login') }}
                    </Link>

                    <Link
                        :href="route('register')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20]"
                    >
                        {{ t('layouts.guestLayout.register') }}
                    </Link>
                </template>
            </nav>
        </header>
        <div class="w-full flex flex-col items-center pt-6 sm:pt-0">
            <slot />
        </div>
    </div>
</template>
