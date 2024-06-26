<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Modal, Button } from '@/Components';
import { useToast } from 'vue-toastification';

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
            toast.success('Microsite deleted successfully.');
        },
        onError: () => {
            toast.error('Failed to delete microsite.');
        },
        onFinish: () => {
            closeModal();
        },
    });
};
</script>

<template>
    <Modal title="Delete Microsite" :isOpen="isOpen" @close="closeModal">
        <p>Are you sure you want to delete this microsite?</p>

        <template #footerButtons>
            <Button type="button" variant="secondary" @click="closeModal">
                Cancel
            </Button>
            <Button
                type="button"
                color="red"
                @click="deleteMicrosite"
                :disabled="deleteForm.processing"
            >
                {{ deleteForm.processing ? 'Deleting...' : 'Delete' }}
            </Button>
        </template>
    </Modal>
</template>
