<script setup lang="ts">
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { router } from '@inertiajs/vue3';
import { Button, InputField } from '@/Components';
import { MagnifyingGlassIcon } from '@heroicons/vue/16/solid';
import { MicrositesPaginatedResponse } from '../index';

const { t } = useI18n();

defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();
const search = ref('');

const handleSearch = () => {
    if (search.value) {
        router.visit(route('microsites.index', { search: search.value }));
    }
};

</script>

<template>
    <div class="flex justify-between">
        <form class="flex gap-4"
              @submit.prevent="handleSearch"
        >
            <div class="flex items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{  t('microsites.index.header') }}
                </h2>
            </div>
            <div class="flex items-center gap-2">
                <InputField
                    id="search"
                    type="text"
                    class="w-64"
                    :placeholder="t('home.index.searchPlaceholder')"
                    v-model="search"
                />
                <Button
                    type="submit"
                    variant="secondary"
                >
                    <MagnifyingGlassIcon class="h-5 w-5" />
                </Button>
            </div>
        </form>
        <Button @click="router.visit(route('microsites.create', {
                    page: microsites.meta.current_page || 1,
                }))">
            {{ t('microsites.index.createMicrosite') }}
        </Button>
    </div>
</template>

