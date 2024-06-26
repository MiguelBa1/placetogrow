<script setup lang="ts">
import { computed, ref, watch } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import {
    Category,
    micrositeTypesTranslations,
    documentTypesTranslations,
    DocumentType,
    MicrositeType, CurrencyType,
} from './index';
import { InputField, Listbox, Button, FileInput } from '@/Components';
import dayjs from 'dayjs';
import { useToast } from 'vue-toastification';

const toast = useToast();

const { microsite, categories, documentTypes, micrositeTypes, currencyTypes } = defineProps<{
    microsite: any;
    categories: Category[];
    documentTypes: DocumentType[];
    micrositeTypes: MicrositeType[];
    currencyTypes: CurrencyType[];
}>();

const editForm = useForm({
    name: microsite.name,
    logo: null as File | null,
    category_id: microsite.category_id,
    payment_currency: microsite.payment_currency,
    payment_expiration: dayjs(microsite.payment_expiration).format('YYYY-MM-DD'),
    type: microsite.type,
    responsible_name: microsite.responsible_name,
    responsible_document_number: microsite.responsible_document_number,
    responsible_document_type: microsite.responsible_document_type,
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

const submit = () => {
    editForm.put(route('microsites.update', microsite.slug), {
        onSuccess: () => {
            toast.success('Microsite updated successfully.');
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
        <title>Edit Microsite</title>
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
                v-model="editForm.name"
                :error="editForm.errors.name"
                required
            />

            <Listbox
                id="category_id"
                label="Category"
                v-model="editForm.category_id"
                :options="categoryOptions"
                :error="editForm.errors.category_id"
                required
            />

            <Listbox
                id="payment_currency"
                label="Payment Currency"
                v-model="editForm.payment_currency"
                :options="currencyOptions"
                :error="editForm.errors.payment_currency"
                required
            />

            <InputField
                id="payment_expiration"
                type="date"
                label="Payment Expiration"
                v-model="editForm.payment_expiration"
                :error="editForm.errors.payment_expiration"
                required
            />

            <Listbox
                id="type"
                label="Microsite Type"
                v-model="editForm.type"
                :options="micrositeTypeOptions"
                :error="editForm.errors.type"
                required
            />

            <InputField
                id="responsible_name"
                type="text"
                label="Responsible Name"
                v-model="editForm.responsible_name"
                :error="editForm.errors.responsible_name"
                required
            />

            <InputField
                id="responsible_document_number"
                type="text"
                label="Responsible Document Number"
                v-model="editForm.responsible_document_number"
                :error="editForm.errors.responsible_document_number"
                required
            />

            <Listbox
                id="responsible_document_type"
                label="Responsible Document Type"
                v-model="editForm.responsible_document_type"
                :options="documentTypeOptions"
                :error="editForm.errors.responsible_document_type"
                required
            />

            <div class="col-span-2 grid grid-cols-2 gap-4">
                <FileInput
                    id="logo"
                    label="Logo (jpg, png, jpeg)"
                    v-model="editForm.logo"
                    :error="editForm.errors.logo"
                    accept="image/*"
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
                    :disabled="editForm.processing"
                >
                    Update Microsite
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
