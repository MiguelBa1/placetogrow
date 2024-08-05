<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { router, useForm } from '@inertiajs/vue3';
import { Button, InputField, Listbox } from '@/Components';
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/16/solid';

const { t } = useI18n();

const { filters: initialFilters, paymentStatuses } = defineProps<{
    filters: {
        microsite: string;
        status: string;
        reference: string;
        document: number;
    };
    paymentStatuses: { value: string; label: string }[];
}>();

const filtersForm = useForm({
    microsite: initialFilters.microsite ?? '',
    status: initialFilters.status ?? '',
    reference: initialFilters.reference ?? '',
    document: initialFilters.document ?? '',
});

const handleSearch = () => {
    router.visit(
        route('transactions.index', {
            microsite: filtersForm.microsite,
            status: filtersForm.status,
            reference: filtersForm.reference,
            document: filtersForm.document,
        })
    );
};
</script>

<template>
    <div class="flex flex-col md:flex-row gap-4">
        <div class="flex gap-4 items-center">
            <h2 class="font-semibold text-xl text-gray-800">
                {{ t('transactions.index.title') }}
            </h2>
        </div>
        <form
            class="flex flex-col md:flex-row items-end gap-2 w-full"
            @submit.prevent="handleSearch"
        >
            <InputField
                id="microsite"
                type="text"
                v-model="filtersForm.microsite"
                :placeholder="t('transactions.index.filters.microsite')"
            />
            <InputField
                id="reference"
                type="text"
                v-model="filtersForm.reference"
                :placeholder="t('transactions.index.filters.reference')"
            />
            <InputField
                id="document"
                type="text"
                v-model="filtersForm.document"
                :placeholder="t('transactions.index.filters.document')"
            />
            <Listbox
                id="status"
                v-model="filtersForm.status"
                :options="paymentStatuses"
                :placeholder="t('transactions.index.filters.status')"
            />
            <div class="flex gap-2">
                <Button type="submit" variant="secondary">
                    <MagnifyingGlassIcon class="h-5 w-5" />
                </Button>
                <Button variant="tertiary" @click="router.visit(route('transactions.index'))">
                    <XMarkIcon class="h-5 w-5" />
                </Button>
            </div>
        </form>
    </div>
</template>
