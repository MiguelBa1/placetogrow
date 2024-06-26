<script setup lang="ts">
import { Navbar } from '@/Components';
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <Navbar />

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="w-full max-w-7xl mx-auto mt-6 py-6 sm:pt-0 px-4 sm:px-6 lg:px-8">
                <slot />
            </main>
        </div>
    </div>
</template>
