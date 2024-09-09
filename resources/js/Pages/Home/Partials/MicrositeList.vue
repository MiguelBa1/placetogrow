<script setup lang="ts">
import { type Microsite } from '../index';
import { MicrositeCard } from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    microsites: Microsite[];
}>();

</script>

<template>
    <div class="space-y-4">
        <h2 class="text-xl font-semibold text-left">
            {{ t('home.index.microsites') }}
        </h2>
        <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
            <template v-for="microsite in microsites" :key="microsite.id">
                <MicrositeCard :microsite="microsite" />
            </template>
        </div>
    </div>
</template>
