<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { InputField, Listbox, Button } from '@/Components';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const { microsite, documentTypes } = defineProps<{
    microsite: {
        id: string;
        name: string;
        logo: string;
        slug: string;
        payment_currency: string;
    };
    documentTypes: { label: string; value: string }[];
}>();

const paymentForm = useForm({
    name: '',
    last_name: '',
    email: '',
    document_type: '',
    document_number: '',
    phone: '',
    currency: 'COP',
    amount: '',
});

const documentTypeOptions = computed(() => documentTypes);

const currencyOption = [
    { label: microsite.payment_currency, value: 'COP'},
];

const onSubmit = () => {
    paymentForm.post(route('payments.store', {
        microsite: microsite.slug,
    }));
};
</script>

<template>
    <Head>
        <title>
            {{ microsite.name }}
        </title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex gap-4 items-center">
                <img
                    class="h-20 w-auto"
                    :src="microsite.logo"
                    :alt="microsite.name"
                />
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ microsite.name }}
                </h2>
            </div>
        </template>

        <form
            class="bg-white p-10 rounded-xl shadow-sm grid sm:grid-cols-2 gap-4"
            @submit.prevent="onSubmit"
        >
            <InputField
                id="name"
                type="text"
                :label="t('payments.show.form.name')"
                v-model="paymentForm.name"
                required
                :error="paymentForm.errors.name"
            />

            <InputField
                id="lastName"
                type="text"
                :label="t('payments.show.form.lastName')"
                v-model="paymentForm.last_name"
                required
                :error="paymentForm.errors.last_name"
            />

            <InputField
                id="email"
                type="text"
                :label="t('payments.show.form.email')"
                v-model="paymentForm.email"
                :error="paymentForm.errors.email"
                required
            />

            <Listbox
                id="document-type"
                :label="t('payments.show.form.documentType')"
                :options="documentTypeOptions"
                v-model="paymentForm.document_type"
                required
                :error="paymentForm.errors.document_type"
            />

            <InputField
                id="document-number"
                type="text"
                :label="t('payments.show.form.documentNumber')"
                v-model="paymentForm.document_number"
                required
                :error="paymentForm.errors.document_number"
            />

            <InputField
                id="phone"
                type="text"
                :label="t('payments.show.form.phone')"
                v-model="paymentForm.phone"
                required
                :error="paymentForm.errors.phone"
            />

            <Listbox
                id="currency"
                :label="t('payments.show.form.currency')"
                :options="currencyOption"
                v-model="paymentForm.currency"
                required
                :error="paymentForm.errors.currency"
                disabled
            />

            <InputField
                id="amount"
                type="number"
                :label="t('payments.show.form.amount')"
                v-model="paymentForm.amount"
                required
                :error="paymentForm.errors.amount"
            />

            <div>
                <Button
                    class="sm:col-span-2 mt-5"
                    type="submit"
                    :disabled="paymentForm.processing"
                >
                    {{ t('payments.show.form.submit') }}
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
