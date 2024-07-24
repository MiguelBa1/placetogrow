<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Modal, Button, InputField } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';
import { MicrositeField } from '../index'

const { t } = useI18n();
const toast = useToast();

const { isOpen, micrositeSlug, field } = defineProps<{
    isOpen: boolean;
    micrositeSlug: string | null;
    field: MicrositeField | null;
}>();

const emit = defineEmits(['closeModal']);

const editForm = useForm({
    name: field?.name,
    type: field?.type,
    validation_rules: field?.validation_rules,
    translation_es: field?.translation_es,
    translation_en: field?.translation_en,
});

const editField = () => {
    if (!micrositeSlug) {
        console.log('Microsite slug is required');
        return;
    }

    editForm.put(route('microsites.fields.update', [micrositeSlug, field?.id]), {
        onSuccess: () => {
            toast.success(t('microsites.edit.fields.editModal.success'));
            emit('closeModal');
        },
        onError: () => {
            toast.error(t('microsites.edit.fields.editModal.error'));
        },
        onFinish: () => {
            editForm.reset();
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Modal
        :title="t('microsites.edit.fields.editModal.title')"
        :isOpen="isOpen" @close="emit('closeModal')"
    >
        <form
            @submit.prevent="editField"
            class="space-y-2"
        >
            <InputField
                id="field-name"
                type="text"
                v-model="editForm.name"
                :label="t('microsites.edit.fields.editModal.name')"
            />
            <InputField
                id="field-translation_es"
                type="text"
                v-model="editForm.translation_es"
                :label="t('microsites.edit.fields.editModal.translations.es')"
            />
            <InputField
                id="field-translation_en"
                type="text"
                v-model="editForm.translation_en"
                :label="t('microsites.edit.fields.editModal.translations.en')"
            />
            <InputField
                id="field-type"
                type="text"
                v-model="editForm.type"
                :label="t('microsites.edit.fields.editModal.type')"
            />
            <InputField
                id="field-validation_rules"
                type="text"
                v-model="editForm.validation_rules"
                :label="t('microsites.edit.fields.editModal.validationRules')"
            />

        </form>

        <template #footerButtons>
            <Button variant="secondary" @click="emit('closeModal')">
                {{ t('microsites.edit.fields.editModal.cancel') }}
            </Button>
            <Button
                color="green"
                type="submit"
                @click="editField">
                {{ t('microsites.edit.fields.editModal.save') }}
            </Button>
        </template>
    </Modal>
</template>
