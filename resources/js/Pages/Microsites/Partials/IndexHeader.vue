<script setup lang="ts">
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { router, useForm } from '@inertiajs/vue3';
import { Button, InputField, Listbox } from '@/Components';
import { MagnifyingGlassIcon, AdjustmentsVerticalIcon, XMarkIcon } from '@heroicons/vue/16/solid';
import { MicrositesPaginatedResponse } from '../index';

const { t } = useI18n();

const { categories, filters: initialFilters } = defineProps<{
    microsites: MicrositesPaginatedResponse;
    categories: { id: number; name: string }[];
    filters: { search: string; category: string };
}>();

const filtersForm = useForm({
    search: initialFilters.search ?? '',
    category: Number(initialFilters.category),
});

const handleSearch = () => {
    router.visit(route('microsites.index', {
        search: filtersForm.search,
        category: filtersForm.category,
    }));
};

const showFilters = ref(filtersForm.search !== '' || filtersForm.category !== 0);

const categoryOptions = computed(() => {
    return categories.map((category) => ({
        label: category.name,
        value: category.id,
    }));
});

</script>

<template>
    <div class="flex justify-between">
        <div class="flex gap-4"
        >
            <div class="flex items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ t('microsites.index.header') }}
                </h2>
                <button
                    @click="showFilters = !showFilters"
                    class="p-2 rounded-md bg-gray-200 hover:bg-gray-300"
                >
                    <AdjustmentsVerticalIcon class="h-5 w-5" />
                </button>
            </div>

            <form
                v-if="showFilters"
                class="flex items-center gap-2 w-96"
                @submit.prevent="handleSearch"
            >
                <InputField
                    id="search"
                    type="text"
                    :placeholder="t('microsites.index.filters.name')"
                    v-model="filtersForm.search"
                />
                <Listbox
                    id="category"
                    :options="categoryOptions"
                    :placeholder="t('microsites.index.filters.category')"
                    v-model="filtersForm.category"
                />
                <Button
                    type="submit"
                    variant="secondary"
                >
                    <MagnifyingGlassIcon class="h-5 w-5" />
                </Button>
                <Button
                    variant="secondary"
                    color="gray"
                    @click="router.visit(route('microsites.index'))"
                >
                    <XMarkIcon class="h-5 w-5" />
                </Button>
            </form>
        </div>
        <Button @click="router.visit(route('microsites.create', {
                    page: microsites.meta.current_page || 1,
                }))">
            {{ t('microsites.index.createMicrosite') }}
        </Button>
    </div>
</template>

