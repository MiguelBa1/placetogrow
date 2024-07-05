<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Category, MicrositeType } from './index';
import { InputField, Listbox, Button, FileInput } from '@/Components';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const toast = useToast();
const { t } = useI18n();

const { categories, documentTypes, micrositeTypes, currencyTypes } = defineProps<{
    categories: Category[];
    documentTypes: { label: string; value: string }[];
    micrositeTypes: { label: string; value: string }[];
    currencyTypes: { label: string; value: string }[];
}>();

const createForm = useForm({
    name: '',
    logo: null as File | null,
    category_id: '',
    payment_currency: '',
    payment_expiration: '',
    type: '',
    responsible_name: '',
    responsible_document_number: '',
    responsible_document_type: '',
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

const previewUrl = ref<string | null>(null);

watch(() => createForm.logo, (newFile) => {
    if (newFile) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewUrl.value = e.target?.result as string;
        };
        reader.readAsDataURL(newFile);
    } else {
        previewUrl.value = null;
    }
});

watch(() => createForm.type, (newType) => {
    if (newType === MicrositeType.BASIC) {
        createForm.payment_expiration = '';
    }
});

const submit = () => {
    createForm.post(route('microsites.store'), {
        onSuccess: () => {
            createForm.reset();
            toast.success(t('microsites.create.form.success'));
            const currentPage = route().params.page || 1;
            router.visit(route('microsites.index', { page: currentPage }), {
                only: ['microsites'],
            });
        },
        onError: () => {
            toast.error(t('microsites.create.form.error'));
        },
    });
};

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>{{ t('microsites.create.title')}}</title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('microsites.create.header') }}
                </h2>
                <Button
                    variant="secondary"
                    color="gray"
                    @click="goBack"
                >
                    {{ t('microsites.create.back') }}
                </Button>
            </div>
        </template>

        <form
            @submit.prevent="submit"
            class="w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4"
        >
            <InputField
                id="name"
                type="text"
                :label="t('microsites.create.form.name')"
                v-model="createForm.name"
                :error="createForm.errors.name"
                required
            />

            <Listbox
                id="category_id"
                :label="t('microsites.create.form.category')"
                v-model="createForm.category_id"
                :options="categoryOptions"
                :error="createForm.errors.category_id"
                required
            />

            <InputField
                id="responsible_name"
                type="text"
                :label="t('microsites.create.form.responsibleName')"
                v-model="createForm.responsible_name"
                :error="createForm.errors.responsible_name"
                required
            />

            <Listbox
                id="responsible_document_type"
                :label="t('microsites.create.form.responsibleDocumentType')"
                v-model="createForm.responsible_document_type"
                :options="documentTypeOptions"
                :error="createForm.errors.responsible_document_type"
                required
            />

            <InputField
                id="responsible_document_number"
                type="text"
                :label="t('microsites.create.form.responsibleDocumentNumber')"
                v-model="createForm.responsible_document_number"
                :error="createForm.errors.responsible_document_number"
                required
            />

            <Listbox
                id="payment_currency"
                :label="t('microsites.create.form.paymentCurrency')"
                v-model="createForm.payment_currency"
                :options="currencyOptions"
                :error="createForm.errors.payment_currency"
                required
            />

            <Listbox
                id="type"
                :label="t('microsites.create.form.type')"
                v-model="createForm.type"
                :options="micrositeTypeOptions"
                :error="createForm.errors.type"
                required
            />

            <InputField
                id="payment_expiration"
                type="number"
                :min="1"
                :max="365"
                :label="t('microsites.create.form.paymentExpiration')"
                v-model="createForm.payment_expiration"
                :error="createForm.errors.payment_expiration"
                :disabled="![MicrositeType.SUBSCRIPTION, MicrositeType.INVOICE].includes(createForm.type as MicrositeType)"
            />

            <div class="col-span-2 grid grid-cols-2 gap-4">
                <FileInput
                    id="logo"
                    :label="t('microsites.create.form.logo')"
                    v-model="createForm.logo"
                    :error="createForm.errors.logo"
                    accept="image/*"
                    required
                />
                <div v-if="previewUrl" class="mt-1">
                    <img :src="previewUrl" alt="Preview" class="w-48 h-48 object-cover rounded-md border" />
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
                    :disabled="createForm.processing"
                >
                    {{ t('microsites.create.form.save') }}
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
