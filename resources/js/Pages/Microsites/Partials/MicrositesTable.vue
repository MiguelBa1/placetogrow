<script setup lang="ts">
import { ref } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/16/solid';
import { Link } from '@inertiajs/vue3';
import { Button, DataTable, Pagination } from '@/Components';
import {
    DeleteMicrositeModal,
    getMicrositeTableColumns,
    MicrositesPaginatedResponse,
    Microsite,
    micrositeTypesTranslations,
    MicrositeType,
} from '../index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const { microsites } = defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();

const micrositesColumns = getMicrositeTableColumns(t);

const currentPage = microsites.current_page;

const selectedMicrositeSlug = ref<string | null>(null);

const isDeleteModalOpen = ref(false);

const openDeleteModal = (micrositeSlug: string) => {
    selectedMicrositeSlug.value = micrositeSlug;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    selectedMicrositeSlug.value = null;
};

const getFormattedPaymentExpiration = (row: Microsite) => {
    switch (row.type) {
        case MicrositeType.BASIC:
            return `${t('microsites.index.noExpire')}`;
        case MicrositeType.SUBSCRIPTION:
            return `${t('microsites.index.frequency')}: ${row.payment_expiration}`;
        case MicrositeType.INVOICE:
            return `${row.payment_expiration}`;
    }
};

</script>

<template>
    <div class="w-full space-y-4">
        <DataTable :columns="micrositesColumns" :rows="microsites.data" class="rounded-lg">
            <template #cell-category="{ row }">
                {{ row.category.name }}
            </template>
            <template #cell-actions="{ row }">
                <div class="flex justify-center gap-2">
                    <Link
                        :href="route('microsites.edit', { microsite: row.slug, page: currentPage })"
                        class="text-blue-600 hover:text-blue-900"
                    >
                        <PencilSquareIcon class="w-5 h-5" />
                    </Link>
                    <button
                        class="text-red-600 hover:text-red-900"
                        @click="openDeleteModal(row.slug)"
                    >
                        <TrashIcon class="w-5 h-5" />
                    </button>
                </div>
            </template>
            <template #cell-type="{ row }">
                {{ micrositeTypesTranslations[row.type as MicrositeType] }}
            </template>
            <template #cell-payment_expiration="{ row }">
                {{ getFormattedPaymentExpiration(row as Microsite) }}
            </template>
        </DataTable>
        <Pagination :links="microsites.links" />
    </div>
    <DeleteMicrositeModal
        :isOpen="isDeleteModalOpen"
        :micrositeSlug="selectedMicrositeSlug"
        @closeModal="closeDeleteModal"
    />
</template>
