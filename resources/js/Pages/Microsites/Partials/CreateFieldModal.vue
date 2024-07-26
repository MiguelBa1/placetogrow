<script setup lang="ts">
import { ref } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Modal, Button, InputField, Listbox } from '@/Components';
import { useFieldTypesQuery } from '../index';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';
import {create} from "muggle-string";

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
    options: '',
});

const createFormRef = ref<HTMLFormElement | null>(null);

const submitForm = () => {
    if (createFormRef.value) {
        createFormRef.value.requestSubmit();
    }
};

const createField = () => {
    if (!micrositeSlug) {
        console.log('Microsite slug is required');
        return;
    }

    createForm.transform((data) => ({
        ...data,
        options: data.type === 'select' ? data.options.split(',').map(option => option.trim()) : null,
        validation_rules: data.validation_rules?.split(',').map(rule => rule.trim()).join('|'),
    })).post(route('microsites.fields.store', micrositeSlug), {
        onSuccess: () => {
            toast.success(t('microsites.show.fields.creationModal.success'));
            createForm.reset();
            emit('closeModal');
        },
        onError: () => {
            toast.error(t('microsites.show.fields.creationModal.error'));
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
            ref="createFormRef"
            @submit.prevent="createField"
            class="space-y-2"
        >
            <InputField
                id="field-name"
                type="text"
                v-model="createForm.name"
                required
                :label="t('microsites.show.fields.creationModal.name')"
                :error="createForm.errors.name"
            />
            <Listbox
                id="field-type"
                v-model="createForm.type"
                required
                :label="t('microsites.show.fields.creationModal.type')"
                :error="createForm.errors.type"
                :options="fieldTypes ?? []"
                :placeholder="fieldTypesLoading ? t('common.loading') : t('common.select')"
            />
            <InputField
                v-if="createForm.type === 'select'"
                id="field-options"
                type="text"
                v-model="createForm.options"
                required
                :label="t('microsites.show.fields.creationModal.options')"
                :error="createForm.errors.options"
                :placeholder="t('microsites.show.fields.creationModal.optionsPlaceholder')"
            />
            <InputField
                id="field-validation-rules"
                type="text"
                required
                v-model="createForm.validation_rules"
                :label="t('microsites.show.fields.creationModal.validationRules')"
                :error="createForm.errors.validation_rules"
                :placeholder="t('microsites.show.fields.validationRulesHelp')"
            />
            <InputField
                id="field-translations-es"
                type="text"
                required
                v-model="createForm.translation_es"
                :label="t('microsites.show.fields.creationModal.translations.es')"
                :error="createForm.errors.translation_es"
            />
            <InputField
                id="field-translations-en"
                type="text"
                required
                v-model="createForm.translation_en"
                :label="t('microsites.show.fields.creationModal.translations.en')"
                :error="createForm.errors.translation_en"
            />
        </form>

        <template #footerButtons>
            <Button type="button" variant="secondary" @click="emit('closeModal')">
                {{ t('microsites.show.fields.creationModal.cancel') }}
            </Button>
            <Button
                type="button"
                color="green"
                @click="submitForm"
                :disabled="createForm.processing"
            >
                {{ t('microsites.show.fields.creationModal.save') }}
            </Button>
        </template>
    </Modal>
</template>
