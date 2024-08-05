<script setup lang="ts">
import {ref} from 'vue';
import {Modal, Button, InputField, Listbox} from '@/Components';
import {useForm} from '@inertiajs/vue3';
import {useToast} from 'vue-toastification';
import {useI18n} from 'vue-i18n';

const {t} = useI18n();

const toast = useToast();

const {micrositeSlug} = defineProps<{
    isOpen: boolean;
    micrositeSlug: string;
    documentTypes: { label: string; value: string }[];
}>();

const emit = defineEmits(['closeModal']);

const createForm = useForm({
    reference: '',
    document_type: '',
    document_number: '',
    name: '',
    last_name: '',
    email: '',
    phone: '',
    amount: '',
});

const formRef = ref<HTMLFormElement | null>(null);

const submitForm = () => {
    if (formRef.value) {
        formRef.value.requestSubmit();
    }
};

const createInvoice = () => {
    createForm.post(route('microsites.invoices.store', micrositeSlug), {
        onSuccess: () => {
            toast.success(t('invoices.index.creationModal.success'));
            createForm.reset();
            emit('closeModal');
        },
        onError: () => {
            toast.error(t('invoices.index.creationModal.error'));
        },
    });
};


</script>

<template>
    <Modal :isOpen="isOpen" @close="emit('closeModal')">
        <form
            ref="formRef"
            @submit.prevent="submitForm"
            class="grid grid-cols-2 gap-6"
        >
            <InputField
                id="reference"
                type="text"
                required
                v-model="createForm.reference"
                :label="t('invoices.index.creationModal.reference')"
                :error="createForm.errors.reference"
            />
            <InputField
                id="name"
                type="text"
                required
                v-model="createForm.name"
                :label="t('invoices.index.creationModal.name')"
                :error="createForm.errors.name"
            />
            <InputField
                id="last_name"
                type="text"
                required
                v-model="createForm.last_name"
                :label="t('invoices.index.creationModal.lastName')"
                :error="createForm.errors.last_name"
            />
            <Listbox
                id="document_type"
                v-model="createForm.document_type"
                :options="documentTypes"
                :label="t('invoices.index.creationModal.documentType')"
                :error="createForm.errors.document_type"
            />
            <InputField
                id="document_number"
                type="text"
                required
                v-model="createForm.document_number"
                :label="t('invoices.index.creationModal.documentNumber')"
                :error="createForm.errors.document_number"
            />

            <InputField
                id="email"
                type="email"
                required
                v-model="createForm.email"
                :label="t('invoices.index.creationModal.email')"
                :error="createForm.errors.email"
            />
            <InputField
                id="phone"
                type="text"
                required
                v-model="createForm.phone"
                :label="t('invoices.index.creationModal.phone')"
                :error="createForm.errors.phone"
            />
            <InputField
                id="amount"
                type="number"
                required
                v-model="createForm.amount"
                :label="t('invoices.index.creationModal.amount')"
                :error="createForm.errors.amount"
            />
        </form>
        <template #footerButtons>
            <Button
                @click="emit('closeModal')"
                variant="secondary"
            >
                {{ t('common.cancel') }}
            </Button>
            <Button
                @click="createInvoice"
                color="green"
            >
                {{ t('common.create') }}
            </Button>
        </template>
    </Modal>
</template>
