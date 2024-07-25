<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { MicrositeField, getFieldsTableColumns, MicrositeEditData, CreateFieldModal, EditFieldModal } from "../index";
import { DataTable, Button } from "@/Components";
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/16/solid';
import { useI18n } from 'vue-i18n';
import { useToast } from "vue-toastification";

const { t } = useI18n();
const toast = useToast();

const { micrositeSlug, fields } = defineProps<{
    micrositeSlug: MicrositeEditData['slug'];
    fields: {
        data: MicrositeField[];
    };
}>();

const fieldsColumns = getFieldsTableColumns(t);

const isCreateFieldModalOpen = ref(false);

const isEditFieldModalOpen = ref(false);
const editFieldData = ref<MicrositeField | null>(null);

const editField = (field: MicrositeField) => {
    editFieldData.value = field;
    isEditFieldModalOpen.value = true;
};

const deleteForm = useForm({});

const deleteField = (field: MicrositeField) => {
    deleteForm.delete(route('microsites.fields.destroy', [micrositeSlug, field.id]), {
        onSuccess: () => {
            toast.success(t('microsites.show.fields.deletion.success'));
        },
        onError: (errors) => {
            toast.error(errors[0]);
        },
    });
};

</script>

<template>
    <div class="space-y-4">
        <div class="flex justify-start">
            <Button @click="isCreateFieldModalOpen = true">
                {{ t('microsites.show.fields.create')}}
            </Button>
        </div>

        <DataTable :columns="fieldsColumns" :rows="fields.data" class="rounded-lg">
            <template #cell-name="{ row }">
                {{ row.modifiable ? row.name : row.name + ' (system)' }}
            </template>
            <template #cell-actions="{ row }">
                <fieldset
                    :disabled="deleteForm.processing"
                    class="flex justify-center gap-1">
                    <button
                        :disabled="row.modifiable === false"
                        class="text-blue-600 hover:text-blue-900 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="editField(row as MicrositeField)"
                    >
                        <PencilSquareIcon class="w-5 h-5" />
                    </button>
                    <button
                        :disabled="row.modifiable === false"
                        class="text-red-600 hover:text-red-900 disabled:opacity-50 disabled:cursor-not-allowed"
                        @click="deleteField(row as MicrositeField)"
                    >
                        <TrashIcon class="w-5 h-5" />
                    </button>
                </fieldset>
            </template>
        </DataTable>
    </div>
    <CreateFieldModal
        v-if="isCreateFieldModalOpen"
        :isOpen="isCreateFieldModalOpen"
        :micrositeSlug="micrositeSlug"
        @closeModal="isCreateFieldModalOpen = false"
    />
    <EditFieldModal
        v-if="isEditFieldModalOpen"
        :isOpen="isEditFieldModalOpen"
        :field="editFieldData"
        :micrositeSlug="micrositeSlug"
        @closeModal="isEditFieldModalOpen = false"
    />
</template>
