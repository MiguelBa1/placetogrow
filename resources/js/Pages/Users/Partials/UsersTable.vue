<script setup lang="ts">
import dayjs from "dayjs";
import { ref } from "vue";
import { CogIcon } from '@heroicons/vue/16/solid';
import { DataTable, Pagination } from '@/Components';
import { UsersPaginatedResponse, UpdateUserRolesModal, getUsersTableColumns } from "../index";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    users: UsersPaginatedResponse;
    roles: { id: number; name: string; }[];
}>()

const usersColumns = getUsersTableColumns(t);

const updateUserRolesModal = ref(false);
const selectedUser = ref<UsersPaginatedResponse['data'][0] | null>(null);

const openUpdateUserRolesModal = (user: UsersPaginatedResponse['data'][0]) => {
    selectedUser.value = user;
    updateUserRolesModal.value = true;
}

</script>

<template>
    <div class="w-full space-y-4">
        <DataTable :columns="usersColumns" :rows="users.data" class="rounded-lg">
            <template #cell-created_at="{ row }">
                {{ dayjs(row.created_at).format('DD/MM/YYYY') }}
            </template>
            <template #cell-roles="{ row }">
                <div class="space-x-1">
                    <span
                        class="border border-gray-400 rounded-full px-2 py-1 text-xs"
                        v-for="(role) in row.roles" :key="role.id"
                    >
                        {{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }}
                    </span>
                </div>
            </template>
            <template #cell-actions="{ row }">
                <div class="flex justify-center">
                    <button
                            :title="t('users.index.table.modifyRoles')"
                            @click="openUpdateUserRolesModal(row as UsersPaginatedResponse['data'][0])"
                    >
                        <CogIcon class="h-5 w-5 text-gray-500 hover:text-gray-900 cursor-pointer"/>
                    </button>
                </div>
            </template>
        </DataTable>
        <Pagination :links="users.links"/>
    </div>
    <UpdateUserRolesModal
        v-if="selectedUser"
        :user="selectedUser"
        :availableRoles="roles"
        :isOpen="updateUserRolesModal"
        @closeModal="updateUserRolesModal = false"
    />
</template>
