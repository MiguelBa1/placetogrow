<script setup lang="ts">
import { MicrositeField, getFieldsTableColumns } from "../index";
import { DataTable, Button } from "@/Components";
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/16/solid';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    fields: {
        data: MicrositeField[];
    };
}>();

const fieldsColumns = getFieldsTableColumns(t);

const editField = (field: MicrositeField) => {
    console.log('Edit field', field);
};

const deleteField = (field: MicrositeField) => {
    console.log('Delete field', field);
};

</script>

<template>
    <div class="space-y-4">
        <div class="flex justify-start">
            <Button @click="console.log('Create field')">
                {{ t('microsites.edit.fields.create')}}
            </Button>
        </div>

        <DataTable :columns="fieldsColumns" :rows="fields.data" class="rounded-lg">
            <template #cell-name="{ row }">
                {{ row.modifiable ? row.name : row.name + ' (system)' }}
            </template>
            <template #cell-actions="{ row }">
                <div class="flex justify-center gap-1">
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
                </div>
            </template>
        </DataTable>
    </div>
</template>
