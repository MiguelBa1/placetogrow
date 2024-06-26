<script setup lang="ts">
import { ref } from 'vue';
import dayjs from 'dayjs';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/16/solid';
import { Link } from '@inertiajs/vue3';
import { Button, DataTable, Pagination } from '@/Components';
import { DeleteMicrositeModal, micrositesColumns, MicrositesPaginatedResponse, micrositeTypesTranslations, MicrositeType } from '../index';

const { microsites } = defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();

const currentPage = microsites.current_page;

const selectedMicrositeSlug = ref<number | null>(null);

const isDeleteModalOpen = ref(false);

const openDeleteModal = (micrositeSlug: number) => {
    selectedMicrositeSlug.value = micrositeSlug;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    selectedMicrositeSlug.value = null;
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
                {{ dayjs(row.payment_expiration as Date).format('DD/MM/YYYY') }}
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
