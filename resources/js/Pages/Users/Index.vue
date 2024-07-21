<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { UsersPaginatedResponse, UsersTable } from "./index";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps<{
    users: UsersPaginatedResponse;
    roles: { id: number; name: string; }[];
}>();

</script>

<template>
    <Head>
        <title>
            {{ t('users.index.title') }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ t('users.index.title') }}
            </h2>
        </template>
        <UsersTable v-if="users.data.length > 0" :users="users" :roles="roles" />
        <div v-else class="flex justify-center items-center h-96">
            <p class="text-gray-500">
                {{ t('users.index.noUsers') }}
            </p>
        </div>
    </MainLayout>
</template>
