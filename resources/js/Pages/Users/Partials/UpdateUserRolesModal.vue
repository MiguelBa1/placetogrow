<script setup lang="ts">
import { Modal, Button } from "@/Components";
import { useForm } from "@inertiajs/vue3";
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const toast = useToast();

const { user } = defineProps<{
    isOpen: boolean;
    user: {
        id: number;
        name: string;
        roles: {
            name: string;
        }[];
    } | null;
    availableRoles: {
        id: number;
        name: string;
    }[];
}>();

const emit = defineEmits(['closeModal']);

const form = useForm({
    roles: user ? user.roles.map(role => role.name) : [],
});

const updateRoles = () => {
    if (!user) {
        return;
    }

    form.put(route('users.updateRoles', user.id), {
        onSuccess: () => {
            toast.success(t('users.index.updateRoles.success'));
        },
        onError: () => {
            toast.error(t('users.index.updateRoles.error'));
        },
        onFinish: () => {
            emit('closeModal');
        },
    });
};

const isRoleAssigned = (roleName: string) => {
    return form.roles.includes(roleName);
};

</script>

<template>
    <Modal
        :title="t('users.index.updateRoles.title')"
        :isOpen="isOpen"
        @close="emit('closeModal')"
    >
        <form @submit.prevent="updateRoles" class="space-y-4">
            <div>
                {{ t('users.index.updateRoles.subTitle', { name: user?.name }) }}
            </div>
            <div class="space-y-2">
                <strong>
                    {{ t('users.index.updateRoles.roles') }}
                </strong>
                <div v-for="role in availableRoles" :key="role.id">
                    <label :for="`role-${role.id}`" class="flex items-center">
                        <input
                            type="checkbox"
                            :id="`role-${role.id}`"
                            :value="role.name"
                            v-model="form.roles"
                            :checked="isRoleAssigned(role.name)"
                        />
                        <span class="ml-2">{{ role.name.charAt(0).toUpperCase() + role.name.slice(1) }}</span>
                    </label>
                </div>
            </div>
        </form>
        <template #footerButtons>
            <Button type="button" variant="secondary" @click="emit('closeModal')">
                {{ t('users.index.updateRoles.cancel') }}
            </Button>
            <Button color="blue" @click="updateRoles">
                {{ t('users.index.updateRoles.save') }}
            </Button>
        </template>
    </Modal>
</template>
