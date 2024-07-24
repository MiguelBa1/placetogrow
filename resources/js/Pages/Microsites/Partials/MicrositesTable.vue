<script setup lang="ts">
import { ref } from 'vue';
import { PencilSquareIcon, TrashIcon, EyeIcon, ArrowUturnLeftIcon } from '@heroicons/vue/16/solid';
import { Link, usePage } from '@inertiajs/vue3';
import { DataTable, Pagination } from '@/Components';
import {
    DeleteMicrositeModal,
    RestoreMicrositeModal,
    getMicrositeTableColumns,
    MicrositesPaginatedResponse,
} from '../index';
import { useI18n } from 'vue-i18n';

const { props: { auth: { permissions }}} = usePage();

const { t } = useI18n();

const { microsites } = defineProps<{
    microsites: MicrositesPaginatedResponse;
}>();

const micrositesColumns = getMicrositeTableColumns(t);

const selectedMicrositeSlug = ref<string | null>(null);

const isDeleteModalOpen = ref(false);
const isRestoreModalOpen = ref(false);

const openDeleteModal = (micrositeSlug: string) => {
    selectedMicrositeSlug.value = micrositeSlug;
    isDeleteModalOpen.value = true;
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    selectedMicrositeSlug.value = null;
};

const openRestoreModal = (micrositeSlug: string) => {
    selectedMicrositeSlug.value = micrositeSlug;
    isRestoreModalOpen.value = true;
};

const closeRestoreModal = () => {
    isRestoreModalOpen.value = false;
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
                <div class="flex justify-center">
                    <div
                        v-if="!row.deleted_at"
                        class="flex justify-center gap-2"
                    >
                        <Link
                            v-if="permissions.includes('view_microsite')"
                            :href="route('microsites.show', { microsite: row.slug, page: microsites.meta.current_page })"
                            class="text-gray-600 hover:text-gray-900"
                        >
                            <EyeIcon class="w-5 h-5" />
                        </Link>
                        <Link
                            v-if="permissions.includes('update_microsite')"
                            :href="route('microsites.edit', { microsite: row.slug, page: microsites.meta.current_page })"
                            class="text-blue-600 hover:text-blue-900"
                        >
                            <PencilSquareIcon class="w-5 h-5" />
                        </Link>
                        <button
                            v-if="permissions.includes('delete_microsite')"
                            class="text-red-600 hover:text-red-900"
                            @click="openDeleteModal(row.slug)"
                        >
                            <TrashIcon class="w-5 h-5" />
                        </button>
                    </div>
                    <button
                        v-else
                        v-if="permissions.includes('restore_microsite')"
                        class="text-green-600 hover:text-green-800"
                        @click="openRestoreModal(row.slug)"
                    >
                        <ArrowUturnLeftIcon class="w-5 h-5" />
                    </button>
                </div>
            </template>
        </DataTable>
        <Pagination :links="microsites.meta.links" />
    </div>
    <DeleteMicrositeModal
        :isOpen="isDeleteModalOpen"
        :micrositeSlug="selectedMicrositeSlug"
        @closeModal="closeDeleteModal"
    />
    <RestoreMicrositeModal
        :isOpen="isRestoreModalOpen"
        :micrositeSlug="selectedMicrositeSlug"
        @closeModal="closeRestoreModal"
    />
</template>
