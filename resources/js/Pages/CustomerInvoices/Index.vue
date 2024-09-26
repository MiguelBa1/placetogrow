<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Button, InputField } from '@/Components';
import { useI18n } from 'vue-i18n';
import { useToast } from 'vue-toastification';

const { t } = useI18n();
const toast = useToast();

const form = useForm({
    document_number: '',
    email: '',
})

const submit = () => {
    form.post(route('invoices.send-link'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(t('customerInvoices.index.linkSent'));
        },
        onError: (errors) => {
            toast.error(t('customerInvoices.index.linkError'));
        },
        onFinish: () => {
            form.reset();
        },
    });
}
</script>

<template>
    <Head>
        <title>
            {{ t('customerInvoices.index.title') }}
        </title>
    </Head>

    <MainLayout>
        <form @submit.prevent="submit"
              class="space-y-4 w-full mx-auto sm:max-w-md px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="space-y-2">
                <h1 class="font-semibold text-xl text-gray-800">
                    {{ t('customerInvoices.index.accessInvoices') }}
                </h1>
                <p class="text-sm text-gray-600">
                    {{ t('customerInvoices.index.enterDetails') }}
                </p>
            </div>
            <InputField
                id="document_number"
                type="text"
                :label="t('customerInvoices.index.documentNumberLabel')"
                v-model="form.document_number"
                required
                :errors="form.errors?.document_number"
            />
            <InputField
                id="email"
                type="email"
                :label="t('customerInvoices.index.emailLabel')"
                v-model="form.email"
                required
                :errors="form.errors?.email"
            />
            <div class="w-full">
                <Button
                    type="submit"
                    variant="primary"
                    color="blue"
                    class="w-full"
                    :disabled="form.processing"
                >
                    {{ t('customerInvoices.index.sendLinkButton') }}
                </Button>
            </div>
            <p class="text-sm text-gray-600">
                {{ t('customerInvoices.index.afterSend') }}
            </p>
        </form>
    </MainLayout>
</template>
