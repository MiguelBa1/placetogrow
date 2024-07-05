<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Modal, Button } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps<{
    isOpen: boolean;
    micrositeSlug: string | null;
}>();

const emit = defineEmits(['closeModal']);
const toast = useToast();

const closeModal = () => {
    emit('closeModal');
};

const deleteForm = useForm({});

const deleteMicrosite = () => {
    if (!props?.micrositeSlug) {
        console.log('Microsite ID is required');
        return;
    }

    deleteForm.delete(route('microsites.destroy', props.micrositeSlug), {
        onSuccess: () => {
            toast.success(t('microsites.index.delete.success'));
        },
        onError: () => {
            toast.error(t('microsites.index.delete.error'));
        },
        onFinish: () => {
            closeModal();
        },
    });
};
</script>

<template>
    <Modal
        :title="t('microsites.index.delete.title')"
        :isOpen="isOpen" @close="closeModal">
        <p>
            {{ t('microsites.index.delete.message')}}
        </p>

        <template #footerButtons>
            <Button type="button" variant="secondary" @click="closeModal">
                {{ t('microsites.index.delete.cancel') }}
            </Button>
            <Button
                type="button"
                color="red"
                @click="deleteMicrosite"
                :disabled="deleteForm.processing"
            >
                {{
                    deleteForm.processing ?
                        t('microsites.index.delete.deleting') :
                        t('microsites.index.delete.delete')
                }}
            </Button>
        </template>
    </Modal>
</template>
