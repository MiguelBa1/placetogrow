<script setup lang="ts">
import { type Microsite } from '../index';
import { MicrositeCard } from './index';

defineProps<{
    microsites: Microsite[];
}>();

</script>

<template>
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-left">Microsites</h2>
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
            <template v-for="microsite in microsites" :key="microsite.id">
                <MicrositeCard :microsite="microsite" />
            </template>
        </div>
    </div>
</template>
