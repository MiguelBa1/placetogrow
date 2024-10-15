<script setup lang="ts">
import { ref } from 'vue';
import { InputField, Listbox, Button, DataTable } from "@/Components";
import { Field, MicrositeInformation, usePendingInvoicesMutation, PendingInvoicesTable } from '../index';
import { useI18n } from 'vue-i18n';
import { useToast } from "vue-toastification";

const toast = useToast();
const { t } = useI18n();

const formData = ref<Record<string, string | number>>({
    reference: '',
    document_number: ''
});

const formErrors = ref<Record<string, string>>({});

const { microsite, fields } = defineProps<{
    fields: Field[];
    microsite: MicrositeInformation;
}>();

const isSubmitting = ref(false);

const getComponent = (type: string) => {
    switch (type) {
        case 'text':
        case 'email':
        case 'password':
        case 'number':
            return InputField;
        case 'select':
            return Listbox;
        default:
            return InputField;
    }
};

const {
    mutateAsync: fetchPendingInvoices,
    isPending: isFetchingPendingInvoices,
    isSuccess: isFetchedPendingInvoices
} = usePendingInvoicesMutation();

const pendingInvoices = ref([]);

const handleSearch = async () => {
    isSubmitting.value = true;

    await fetchPendingInvoices({
        document_number: formData.value.document_number,
        reference: formData.value.reference,
        micrositeSlug: microsite.slug
    }, {
        onError: (error) => {
            toast.error(error?.payment ?? t('common.form.error'));
            formErrors.value = error.response.data.errors ?? {};
            isSubmitting.value = false;
        },
        onSuccess: ({ data }) => {
            pendingInvoices.value = data;
        }
    });
    isSubmitting.value = false;
};


</script>

<template>
    <div class="space-y-4 p-10 bg-white rounded-xl shadow-sm">
        <h1 class="text-2xl font-bold">{{ t('invoicePayments.show.form.title') }}</h1>
        <p class="text-gray-600">{{ t('invoicePayments.show.form.instructions') }}</p>

        <form @submit.prevent="handleSearch" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div v-for="field in fields" :key="field.id">
                    <component
                        :is="getComponent(field.type)"
                        :id="field.name"
                        :type="field.type"
                        :name="field.name"
                        :label="field.label"
                        v-model="formData[field.name]"
                        :options="field.options"
                        :error="formErrors[field.name] ? formErrors[field.name][0] : null"
                    />
                </div>
            </div>
            <div class="space-y-3">
                <Button
                    type="submit"
                    :disabled="isSubmitting"
                    color="green"
                >
                    {{ t('invoicePayments.show.form.search') }}
                </Button>
            </div>
        </form>

        <div>
            <div v-if="isFetchingPendingInvoices" class="text-center">
                {{ t('common.loading') }}
            </div>
            <PendingInvoicesTable
                v-else-if="pendingInvoices.length > 0"
                :pendingInvoices="pendingInvoices"
                :micrositeSlug="microsite.slug"
                :formData="formData"
            />
            <div
                v-else-if="isFetchedPendingInvoices && pendingInvoices.length === 0"
            >
                <p class="text-center text-gray-500">{{ t('invoicePayments.show.form.noResults') }}</p>
            </div>
        </div>
    </div>
</template>
