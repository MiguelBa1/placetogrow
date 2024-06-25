<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import {
    Category,
    micrositeTypesTranslations,
    documentTypesTranslations,
    DocumentType,
    MicrositeType, CurrencyType,
} from './index';
import { InputField, Listbox, Button, FileInput } from '@/Components';
import { useToast } from 'vue-toastification';

const toast = useToast();

const { categories, documentTypes, micrositeTypes, currencyTypes } = defineProps<{
    categories: Category[];
    documentTypes: DocumentType[];
    micrositeTypes: MicrositeType[];
    currencyTypes: CurrencyType[];
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

const micrositeTypeOptions = computed(() => {
    return micrositeTypes.map((type) => ({
        label: micrositeTypesTranslations[type],
        value: type,
    }));
});

const documentTypeOptions = computed(() => {
    return documentTypes.map((type) => ({
        label: documentTypesTranslations[type],
        value: type,
    }));
});

const categoryOptions = computed(() => {
    return categories.map((category) => ({
        label: category.name,
        value: category.id,
    }));
});

const currencyOptions = computed(() => {
    return currencyTypes.map((currency) => ({
        label: currency,
        value: currency,
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

const submit = () => {
    createForm.post(route('microsites.store'), {
        onSuccess: () => {
            createForm.reset();
            toast.success('Microsite created successfully.');
            const currentPage = route().params.page || 1;
            router.visit(route('microsites.index', { page: currentPage }), {
                only: ['microsites'],
            });
        },
        onError: () => {
            toast.error('Please check the form for errors.');
        },
    });
};

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>Create Microsite</title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Microsite
                </h2>
                <Button
                    variant="secondary"
                    color="gray"
                    @click="goBack"
                >
                    Back
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
                label="Name"
                v-model="createForm.name"
                :error="createForm.errors.name"
                required
            />

            <Listbox
                id="category_id"
                label="Category"
                v-model="createForm.category_id"
                :options="categoryOptions"
                :error="createForm.errors.category_id"
                required
            />

            <Listbox
                id="payment_currency"
                label="Payment Currency"
                v-model="createForm.payment_currency"
                :options="currencyOptions"
                :error="createForm.errors.payment_currency"
                required
            />

            <InputField
                id="payment_expiration"
                type="date"
                label="Payment Expiration"
                v-model="createForm.payment_expiration"
                :error="createForm.errors.payment_expiration"
                required
            />

            <Listbox
                id="type"
                label="Microsite Type"
                v-model="createForm.type"
                :options="micrositeTypeOptions"
                :error="createForm.errors.type"
                required
            />

            <InputField
                id="responsible_name"
                type="text"
                label="Responsible Name"
                v-model="createForm.responsible_name"
                :error="createForm.errors.responsible_name"
                required
            />

            <InputField
                id="responsible_document_number"
                type="text"
                label="Responsible Document Number"
                v-model="createForm.responsible_document_number"
                :error="createForm.errors.responsible_document_number"
                required
            />

            <Listbox
                id="responsible_document_type"
                label="Responsible Document Type"
                v-model="createForm.responsible_document_type"
                :options="documentTypeOptions"
                :error="createForm.errors.responsible_document_type"
                required
            />

            <div class="col-span-2 grid grid-cols-2 gap-4">
                <FileInput
                    id="logo"
                    label="Logo (jpg, png, jpeg)"
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
                    Create Microsite
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
