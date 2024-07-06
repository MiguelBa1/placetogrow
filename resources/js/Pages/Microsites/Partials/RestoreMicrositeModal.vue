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

const restoreForm = useForm({});

const restoreMicrosite = () => {
    if (!props.micrositeSlug) {
        console.log('Microsite slug is required');
        return;
    }

    restoreForm.put(route('microsites.restore', props.micrositeSlug), {
        onSuccess: () => {
            toast.success(t('microsites.index.restore.success'));
        },
        onError: () => {
            toast.error(t('microsites.index.restore.error'));
        },
        onFinish: () => {
            closeModal();
        },
    });
};
</script>

<template>
    <Modal
        :title="t('microsites.index.restore.title')"
        :isOpen="isOpen" @close="closeModal">
        <p>
            {{ t('microsites.index.restore.message') }}
        </p>

        <template #footerButtons>
            <Button type="button" variant="secondary" @click="closeModal">
                {{ t('microsites.index.restore.cancel') }}
            </Button>
            <Button
                type="button"
                color="green"
                @click="restoreMicrosite"
                :disabled="restoreForm.processing"
            >
                {{
                    restoreForm.processing ?
                        t('microsites.index.restore.restoring') :
                        t('microsites.index.restore.restore')
                }}
            </Button>
        </template>
    </Modal>
</template>
