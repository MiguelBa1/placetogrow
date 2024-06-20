<script setup lang="ts">
import { PropType, ref } from 'vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/16/solid';
import { Head, Link } from '@inertiajs/vue3';
import { DataTable } from '@/Components';
import { MainLayout } from '@/Layouts';
import {
    PaginatedMicrosites,
    micrositesColumns,
    DeleteMicrositeModal,
} from './index';

defineProps({
    microsites: {
        type: Object as PropType<PaginatedMicrosites>,
        required: true,
    }
});

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
    <Head>
        <title>Microsites</title>
    </Head>
    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">Microsites</h2>
        </template>

        <div class="w-full">
            <DataTable :columns="micrositesColumns" :rows="microsites.data">
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
            </DataTable>
        </div>
    </MainLayout>
    <DeleteMicrositeModal
        :isOpen="isDeleteModalOpen"
        :micrositeId="selectedMicrositeId"
        @closeModal="closeDeleteModal"
    />
</template>
