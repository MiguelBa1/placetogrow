<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Modal, Button, InputField, Listbox } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';
import { MicrositeField, useFieldTypesQuery } from '../index';
import { ref } from 'vue';

const { t } = useI18n();
const toast = useToast();

const { isOpen, micrositeSlug, field } = defineProps<{
    isOpen: boolean;
    micrositeSlug: string | null;
    field: MicrositeField | null;
}>();

const emit = defineEmits(['closeModal']);

const {
    data: fieldTypes,
    isLoading: fieldTypesLoading,
} = useFieldTypesQuery({
    enabled: isOpen,
});

const editForm = useForm({
    name: field?.name,
    type: field?.type,
    validation_rules: field?.validation_rules,
    translation_es: field?.translation_es,
    translation_en: field?.translation_en,
    options: field?.options ?? '',
});

const editFormRef = ref<HTMLFormElement | null>(null);

const submitForm = () => {
    if (editFormRef.value) {
        editFormRef.value.requestSubmit();
    }
};

const editField = () => {
    if (!micrositeSlug) {
        console.log('Microsite slug is required');
        return;
    }

    editForm.transform((data) => ({
        ...data,
        options: data.type === 'select' ? data.options.split(',').map(option => option.trim()) : null,
    })).put(route('microsites.fields.update', [micrositeSlug, field?.id]), {
        onSuccess: () => {
            toast.success(t('microsites.show.fields.editModal.success'));
            emit('closeModal');
            editForm.reset();
        },
        onError: (error) => {
            toast.error(t('microsites.show.fields.editModal.error'));
        },
        preserveScroll: true,
    });

};
</script>

<template>
    <Modal
        :title="t('microsites.show.fields.editModal.title')"
        :isOpen="isOpen" @close="emit('closeModal')"
    >
        <form ref="editFormRef" @submit.prevent="editField" class="space-y-2">
            <InputField
                id="field-name"
                type="text"
                required
                v-model="editForm.name"
                :label="t('microsites.show.fields.editModal.name')"
                :error="editForm.errors.name"
            />
            <InputField
                id="field-translation_es"
                type="text"
                required
                v-model="editForm.translation_es"
                :label="t('microsites.show.fields.editModal.translations.es')"
                :error="editForm.errors.translation_es"
            />
            <InputField
                id="field-translation_en"
                type="text"
                required
                v-model="editForm.translation_en"
                :label="t('microsites.show.fields.editModal.translations.en')"
                :error="editForm.errors.translation_en"
            />
            <Listbox
                id="field-type"
                v-model="editForm.type"
                :label="t('microsites.show.fields.editModal.type')"
                :error="editForm.errors.type"
                :options="fieldTypes ?? []"
                :isLoading="fieldTypesLoading"
                :placeholder="fieldTypesLoading ? t('common.loading') : t('common.select')"
            />
            <InputField
                v-if="editForm.type === 'select'"
                id="field-options"
                type="text"
                v-model="editForm.options"
                required
                :label="t('microsites.show.fields.editModal.options')"
                :placeholder="t('microsites.show.fields.editModal.optionsPlaceholder')"
                :error="editForm.errors.options"
            />
            <InputField
                id="field-validation_rules"
                type="text"
                v-model="editForm.validation_rules"
                :label="t('microsites.show.fields.editModal.validationRules')"
                :error="editForm.errors.validation_rules"
            />
        </form>

        <template #footerButtons>
            <Button variant="secondary" @click="emit('closeModal')">
                {{ t('microsites.show.fields.editModal.cancel') }}
            </Button>
            <Button
                color="green"
                type="submit"
                @click="submitForm">
                {{ t('microsites.show.fields.editModal.save') }}
            </Button>
        </template>
    </Modal>
</template>
