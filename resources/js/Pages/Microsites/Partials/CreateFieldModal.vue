<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Modal, Button, InputField, Listbox } from '@/Components';
import { useFieldTypesQuery } from '../index';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const { isOpen, micrositeSlug } = defineProps<{
    isOpen: boolean;
    micrositeSlug: string | null;
}>();

const {
    data: fieldTypes,
    isLoading: fieldTypesLoading,
} = useFieldTypesQuery({
    enabled: isOpen,
});

const emit = defineEmits(['closeModal']);
const toast = useToast();

const createForm = useForm({
    name: '',
    type: '',
    validation_rules: '',
    translation_es: '',
    translation_en: '',
});

const createField = () => {
    if (!micrositeSlug) {
        console.log('Microsite slug is required');
        return;
    }

    createForm.post(route('microsites.fields.store', micrositeSlug), {
        onSuccess: () => {
            toast.success(t('microsites.show.fields.creationModal.success'));
            emit('closeModal');
        },
        onError: () => {
            toast.error(t('microsites.show.fields.creationModal.error'));
        },
        onFinish: () => {
            createForm.reset();
        },
        preserveScroll: true,
    });
};
</script>

<template>
    <Modal
        :title="t('microsites.show.fields.creationModal.title')"
        :isOpen="isOpen" @close="emit('closeModal')"
    >
        <form
            @submit.prevent="createField"
            class="space-y-2"
        >
            <InputField
                id="field-name"
                type="text"
                v-model="createForm.name"
                :label="t('microsites.show.fields.creationModal.name')"
                :error="createForm.errors.name"
            />
            <InputField
                id="field-translations-es"
                type="text"
                v-model="createForm.translation_es"
                :label="t('microsites.show.fields.creationModal.translations.es')"
                :error="createForm.errors.translation_es"
            />
            <InputField
                id="field-translations-en"
                type="text"
                v-model="createForm.translation_en"
                :label="t('microsites.show.fields.creationModal.translations.en')"
                :error="createForm.errors.translation_en"
            />
            <Listbox
                id="field-type"
                v-model="createForm.type"
                :label="t('microsites.show.fields.creationModal.type')"
                :error="createForm.errors.type"
                :options="fieldTypes ?? []"
                :placeholder="fieldTypesLoading ? t('common.loading') : t('common.select')"
            />
            <InputField
                id="field-validation-rules"
                type="text"
                v-model="createForm.validation_rules"
                :label="t('microsites.show.fields.creationModal.validationRules')"
                :error="createForm.errors.validation_rules"
            />

        </form>

        <template #footerButtons>
            <Button type="button" variant="secondary" @click="emit('closeModal')">
                {{ t('microsites.show.fields.creationModal.cancel') }}
            </Button>
            <Button
                type="button"
                color="green"
                @click="createField"
                :disabled="createForm.processing"
            >
                {{ t('microsites.show.fields.creationModal.save') }}
            </Button>
        </template>
    </Modal>
</template>
