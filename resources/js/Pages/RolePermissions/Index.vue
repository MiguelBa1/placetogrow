<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import { Role, RolesTable, CreateRoleModal } from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    roles: {
        data: Role[];
    };
}>();

const isCreateRoleModalOpen = ref(false);

</script>

<template>
    <Head>
        <title>
            {{ t('rolePermissions.index.header') }}
        </title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ t('rolePermissions.index.title') }}
                </h2>
                <Button
                    type="button"
                    @click="isCreateRoleModalOpen = true"
                >
                    {{ t('rolePermissions.index.createButton') }}
                </Button>
            </div>
        </template>

        <RolesTable
            v-if="roles.data.length > 0"
            :roles="roles.data" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">
                {{ t('rolePermissions.index.noRoles') }}
            </p>
        </div>

    </MainLayout>
    <CreateRoleModal
        :isOpen="isCreateRoleModalOpen"
        @closeModal="isCreateRoleModalOpen = false"
    />
</template>
