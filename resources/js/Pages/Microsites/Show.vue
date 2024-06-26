<script setup lang="ts">
import { useForm, Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { InputField, Listbox, Button } from '@/Components';
import { computed } from 'vue';

const { microsite, documentTypes } = defineProps<{
    microsite: {
        id: string;
        name: string;
        logo: string;
        payment_currency: string;
    };
    documentTypes: string[];
}>();

const paymentForm = useForm({
    name: '',
    last_name: '',
    email: '',
    document_type: '',
    document_number: '',
    phone: '',
    currency: microsite.payment_currency,
    amount: '',
});

const documentTypeOptions = computed(() => {
    return documentTypes.map((type) => ({
        label: type,
        value: type,
    }));
});

const currencyOption = [
    { label: microsite.payment_currency, value: microsite.payment_currency },
]

const onSubmit = () => {
    paymentForm.post(route('microsites.payment.store', {
        microsite: microsite.id,
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
                label="Nombre"
                v-model="paymentForm.name"
                required
                :error="paymentForm.errors.name"
            />

            <InputField
                id="lastName"
                type="text"
                label="Apellido"
                v-model="paymentForm.last_name"
                required
                :error="paymentForm.errors.last_name"
            />

            <InputField
                id="email"
                type="text"
                label="Email"
                v-model="paymentForm.email"
                :error="paymentForm.errors.email"
                required
            />

            <Listbox
                id="document-type"
                label="Tipo de documento"
                :options="documentTypeOptions"
                v-model="paymentForm.document_type"
                required
                :error="paymentForm.errors.document_type"
            />

            <InputField
                id="document-number"
                type="text"
                label="Número de documento"
                v-model="paymentForm.document_number"
                required
                :error="paymentForm.errors.document_number"
            />

            <InputField
                id="phone"
                type="text"
                label="Teléfono"
                v-model="paymentForm.phone"
                required
                :error="paymentForm.errors.phone"
            />

            <Listbox
                id="currency"
                label="Moneda"
                :options="currencyOption"
                v-model="paymentForm.currency"
                required
                :error="paymentForm.errors.currency"
                disabled
            />

            <InputField
                id="amount"
                type="number"
                label="Valor"
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
                    Pagar
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
