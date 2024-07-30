<script setup lang="ts">
import { ref } from 'vue'
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import { Head } from '@inertiajs/vue3';
import { InvoiceList, InvoicesTable, CreateInvoiceModal } from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
defineProps<{
    invoices: InvoiceList;
    microsite: {
        name: string;
        slug: string;
    };
    documentTypes: { label: string; value: string }[];
}>();

const goBack = () => {
    history.back();
};

const isCreateInvoiceModalOpen = ref(false);

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
                    {{ t('invoices.index.title', { name: microsite.name }) }}
                </h2>
                <div class="space-x-2">
                    <Button
                        variant="primary"
                        @click="isCreateInvoiceModalOpen = true"
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

        <CreateInvoiceModal
            :isOpen="isCreateInvoiceModalOpen"
            :micrositeSlug="microsite.slug"
            @closeModal="isCreateInvoiceModalOpen = false"
            :documentTypes="documentTypes"
        />

    </MainLayout>
</template>
