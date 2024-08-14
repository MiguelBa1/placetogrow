<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { useI18n } from 'vue-i18n';
import { Button } from "@/Components";
import { DynamicForm, Field, MicrositeInformation } from "./index";

const { t } = useI18n();

const { microsite, fields } = defineProps<{
    microsite: MicrositeInformation;
    fields: {
        data: Field[];
    };
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
const goBack = () => {
    window.history.back();
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
            <div class="flex justify-between items-center">
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
                <Button
                    @click="goBack"
                    type="button"
                    variant="secondary"
                    color="gray"
                >
                    {{ t('common.back') }}
                </Button>
            </div>
        </template>

        <div class="p-10 bg-white rounded-xl shadow-sm">
            <DynamicForm
                v-if="fields.data.length > 0"
                :fields="fields.data"
                :microsite="microsite"
            />
            <div v-else class="text-center">
                <p>
                    {{ t('common.no_data') }}
                </p>
            </div>
        </div>
    </MainLayout>
</template>
