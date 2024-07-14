<script setup lang="ts">
import { DataTable } from '@/Components';
import { Link } from '@inertiajs/vue3';
import { PencilSquareIcon } from '@heroicons/vue/16/solid';
import { Role, getRoleTableColumns } from '../index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    roles: Role[];
}>();

const rolesColumns = getRoleTableColumns(t);


</script>

<template>
    <DataTable :columns="rolesColumns" :rows="roles" class="rounded-lg">
        <template #cell-actions="{ row }">
            <div class="flex justify-center">
                <Link
                    :href="route('roles-permissions.edit', row.id)"
                      class="text-blue-600 hover:text-blue-900">
                    <PencilSquareIcon class="h-5 w-5" />
                </Link>
            </div>
        </template>
    </DataTable>
</template>
