<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { Category, MicrositeType } from '../index';
import { InputField, Listbox, Button, FileInput } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const toast = useToast();
const { t } = useI18n();

const { microsite, categories, documentTypes, micrositeTypes, currencyTypes } = defineProps<{
    microsite: any;
    categories: Category[];
    documentTypes: { label: string; value: string }[];
    micrositeTypes: { label: string; value: string }[];
    currencyTypes: { label: string; value: string }[];
}>();

const editForm = useForm({
    name: microsite.name,
    logo: null as File | null,
    category_id: microsite.category_id,
    payment_currency: microsite.payment_currency,
    payment_expiration: microsite.payment_expiration ?? undefined,
    type: microsite.type,
    responsible_name: microsite.responsible_name,
    responsible_document_number: microsite.responsible_document_number,
    responsible_document_type: microsite.responsible_document_type,
});

const micrositeTypeOptions = computed(() => micrositeTypes);

const documentTypeOptions = computed(() => documentTypes);

const currencyOptions = computed(() => currencyTypes);

const categoryOptions = computed(() => {
    return categories.map((category) => ({
        label: category.name,
        value: category.id,
    }));
});

const previewUrl = ref<string | null>(microsite.logo);

watch(() => editForm.logo, (newFile) => {
    if (newFile) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target?.result as string;
        };
        reader.readAsDataURL(newFile);
    } else {
        previewUrl.value = microsite.logo;
    }
});

watch(() => editForm.type, (newType) => {
    if (newType === MicrositeType.BASIC) {
        editForm.payment_expiration = undefined;
    }
});


const submit = () => {
    editForm.post(route('microsites.update', microsite), {
        onSuccess: () => {
            toast.success(t('microsites.edit.form.success'));
            const currentPage = route().params.page || 1;
            router.visit(route('microsites.index', { page: currentPage }), {
                only: ['microsites'],
            });
        },
        onError: () => {
            toast.error(t('microsites.edit.form.error'));
        },
    });
};


</script>

<template>
    <form
        @submit.prevent="submit"
        class="w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4"
    >
        <InputField
            id="name"
            type="text"
            :label="t('microsites.edit.form.name')"
            v-model="editForm.name"
            :error="editForm.errors.name"
            required
        />

        <Listbox
            id="category_id"
            :label="t('microsites.edit.form.category')"
            v-model="editForm.category_id"
            :options="categoryOptions"
            :error="editForm.errors.category_id"
            required
        />

        <InputField
            id="responsible_name"
            type="text"
            :label="t('microsites.edit.form.responsibleName')"
            v-model="editForm.responsible_name"
            :error="editForm.errors.responsible_name"
            required
        />

        <Listbox
            id="responsible_document_type"
            :label="t('microsites.edit.form.responsibleDocumentType')"
            v-model="editForm.responsible_document_type"
            :options="documentTypeOptions"
            :error="editForm.errors.responsible_document_type"
            required
        />

        <InputField
            id="responsible_document_number"
            type="text"
            :label="t('microsites.edit.form.responsibleDocumentNumber')"
            v-model="editForm.responsible_document_number"
            :error="editForm.errors.responsible_document_number"
            required
        />

        <Listbox
            id="payment_currency"
            :label="t('microsites.edit.form.paymentCurrency')"
            v-model="editForm.payment_currency"
            :options="currencyOptions"
            :error="editForm.errors.payment_currency"
            required
        />

        <Listbox
            id="type"
            :label="t('microsites.edit.form.type')"
            v-model="editForm.type"
            :options="micrositeTypeOptions"
            :error="editForm.errors.type"
            required
        />

        <InputField
            id="payment_expiration"
            type="number"
            :label="t('microsites.edit.form.paymentExpiration')"
            v-model="editForm.payment_expiration"
            :error="editForm.errors.payment_expiration"
            :disabled="![MicrositeType.SUBSCRIPTION, MicrositeType.INVOICE].includes(editForm.type as MicrositeType)"
        />

        <div class="flex flex-col md:col-span-2 md:grid md:grid-cols-2 gap-4">
            <FileInput
                id="logo"
                :label="t('microsites.edit.form.logo')"
                v-model="editForm.logo"
                :error="editForm.errors.logo"
                accept="image/*"
            />
            <div v-if="previewUrl" class="mt-1">
                <img :src="previewUrl"
                     :alt="editForm.name + ' Logo'"
                     class="w-48 h-48 object-cover rounded-md border" />
            </div>
            <div v-else class="mt-1">
                <img src="/images/placeholder.png" alt="Placeholder"
                     class="w-48 h-48 object-cover rounded-md border" />
            </div>
        </div>

        <div class="col-span-2">
            <Button
                type="submit"
                variant="primary"
                color="blue"
                :disabled="editForm.processing"
            >
                {{ t('microsites.edit.form.save') }}
            </Button>
        </div>
    </form>

</template>
