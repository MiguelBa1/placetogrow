<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { InputField, Button } from '@/Components';
import { MagnifyingGlassIcon } from '@heroicons/vue/16/solid';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const search = ref('');

const handleSearch = () => {
    if (search.value) {
        router.visit(route('home', { search: search.value }),
            {
                only: ['microsites']
            }
        );
    }
};

</script>

<template>
    <form class="flex gap-4"
          @submit.prevent="handleSearch"
    >
        <div class="flex items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ t('home.index.header') }}
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
</template>
