<script setup lang="ts">
import { ref } from 'vue';
import dayjs from 'dayjs';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/16/solid';
import { Link } from '@inertiajs/vue3';
import { Button, DataTable, Pagination } from '@/Components';
import { DeleteMicrositeModal, micrositesColumns, MicrositesPaginatedResponse, micrositeTypesTranslations, MicrositeType } from '../index';

defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();

const selectedMicrositeId = ref<number | null>(null);

const isDeleteModalOpen = ref(false);

const openDeleteModal = (micrositeId: number) => {
    selectedMicrositeId.value = micrositeId;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    selectedMicrositeId.value = null;
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
                        :href="route('microsites.edit', row.id)"
                        class="text-blue-600 hover:text-blue-900"
                    >
                        <PencilSquareIcon class="w-5 h-5" />
                    </Link>
                    <button
                        class="text-red-600 hover:text-red-900"
                        @click="openDeleteModal(row.id)"
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
        <Pagination
            :links="microsites.links"
        />
    </div>
    <DeleteMicrositeModal
        :isOpen="isDeleteModalOpen"
        :micrositeId="selectedMicrositeId"
        @closeModal="closeDeleteModal"
    />
</template>
