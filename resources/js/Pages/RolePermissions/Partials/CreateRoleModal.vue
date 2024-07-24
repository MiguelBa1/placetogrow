<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Modal, Button, InputField } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const { isOpen } = defineProps<{
    isOpen: boolean;
}>();

const emit = defineEmits(['closeModal']);
const toast = useToast();

const closeModal = () => {
    emit('closeModal');
};

const createForm = useForm({
    name: '',
});

const createRole = () => {
    createForm.post(route('roles-permissions.store'), {
        onSuccess: () => {
            toast.success(t('rolePermissions.index.create.success'));
            closeModal();
        },
        onError: () => {
            toast.error(t('rolePermissions.index.create.error'));
        },
    });
};

</script>

<template>
    <Modal
        :title="t('rolePermissions.index.create.title')"
        :isOpen="isOpen"
        @close="closeModal"
    >
        <InputField
            id="name"
            type="text"
            :label="t('rolePermissions.index.create.name')"
            v-model="createForm.name"
            :error="createForm.errors.name"
        />

        <template #footerButtons>
            <Button
                type="button"
                variant="secondary"
                @click="closeModal"
            >
                {{ t('rolePermissions.index.create.cancel') }}
            </Button>
            <Button
                type="button"
                color="green"
                @click="createRole"
                :disabled="createForm.processing"
            >
                {{ t('rolePermissions.index.create.save') }}
            </Button>
        </template>
    </Modal>
</template>
