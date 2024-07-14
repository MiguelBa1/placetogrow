<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const toast = useToast();

const { role } = defineProps<{
    role: {
        data: {
            id: string;
            name: string;
            permissions: {
                [key: string]: {
                    id: string;
                    name: string;
                    checked: boolean;
                }[];
            };
        };
    };
}>();

const selectedPermissions = ref<string[]>([]);

onMounted(() => {
    selectedPermissions.value = Object.values(role.data.permissions)
        .flat()
        .filter(permission => permission.checked)
        .map(permission => permission.id);
});

const handleSubmit = () => {
    router.put(route('roles-permissions.update', role.data.id), {
        permissions: selectedPermissions.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(t('rolePermissions.edit.success'));
        },
        onError: () => {
            toast.error(t('rolePermissions.edit.error'));
        },
    });
};

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>{{ t('rolePermissions.edit.title', { name: role.data.name }) }}</title>
    </Head>
    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('rolePermissions.edit.header', { name: role.data.name }) }}
                </h2>
                <Button variant="secondary" color="gray" @click="goBack">
                    Volver
                </Button>
            </div>
        </template>

        <form @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <template v-for="(permissions, group) in role.data.permissions">
                    <div class="bg-white space-y-6 px-6 py-4 shadow overflow-hidden sm:rounded-lg">
                        <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ group }}</h3>
                        <fieldset class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div v-for="permission in permissions" :key="permission.id">
                                <div class="flex items center justify-between">
                                    <div class="flex items center">
                                        <input type="checkbox"
                                               :id="permission.id"
                                               :value="permission.id"
                                               v-model="selectedPermissions">
                                        <label :for="permission.id"
                                               class="ml-2 text-sm font-semibold text-gray-700">{{ permission.name }}</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </template>
            </div>

            <div class="flex justify-end mt-4">
                <Button type="submit">
                    {{ t('rolePermissions.edit.saveButton') }}
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
