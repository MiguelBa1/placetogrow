<script setup lang="ts">
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import { Head } from '@inertiajs/vue3';
import { InvoiceList, InvoicesTable } from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
defineProps<{
    invoices: InvoiceList;
    micrositeName: string;
}>();

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>
            {{ t('invoices.index.header') }}
        </title>
    </Head>
    <MainLayout>
        <template #header>

            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    {{ t('invoices.index.title', { name: micrositeName }) }}
                </h2>
                <div class="space-x-2">
                    <Button
                        variant="primary"
                        @click="route('invoices.create')"
                    >
                        {{ t('invoices.index.create') }}
                    </Button>

                    <Button
                        variant="secondary"
                        color="gray"
                        @click="goBack"
                    >
                        {{ t('common.back') }}
                    </Button>

                </div>
            </div>
        </template>

        <InvoicesTable :invoiceList="invoices" />

    </MainLayout>
</template>
