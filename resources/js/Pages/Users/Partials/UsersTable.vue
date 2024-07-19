<script setup lang="ts">
import dayjs from "dayjs";
import { AdjustmentsVerticalIcon, CogIcon } from '@heroicons/vue/16/solid';
import { DataTable, Pagination } from '@/Components';
import { UsersPaginatedResponse, getUsersTableColumns } from "../index";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    users: UsersPaginatedResponse;
}>()

const usersColumns = getUsersTableColumns(t);

</script>

<template>
    <div class="w-full space-y-4">
        <DataTable :columns="usersColumns" :rows="users.data" class="rounded-lg">
            <template #cell-created_at="{ row }">
                {{ dayjs(row.created_at).format('DD/MM/YYYY') }}
            </template>
            <template #cell-roles="{ row }">
                <span
                    class="border border-gray-400 rounded-full px-2 py-1 text-xs"
                    v-for="(role, index) in row.roles" :key="role.id">
                    {{ role.name }}{{ index !== row.roles.length - 1 ? ', ' : '' }}
                </span>
            </template>
            <template #cell-actions>
                <div class="flex justify-center"
                     :title="t('users.index.table.modifyRoles')"
                >
                    <CogIcon class="h-5 w-5 text-gray-500 hover:text-gray-900 cursor-pointer" />
                </div>
            </template>
        </DataTable>
        <Pagination :links="users.links" />
    </div>
</template>
