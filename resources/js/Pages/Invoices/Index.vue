<script setup lang="ts">
import { ref } from 'vue'
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import { Head, usePage } from '@inertiajs/vue3';
import { InvoiceList, InvoicesTable, CreateInvoiceModal, ImportInvoiceModal } from './index';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const { props: { auth: { permissions }}} = usePage();

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
const isImportInvoiceModalOpen = ref(false);

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
                        v-if="permissions.includes('import_invoice')"
                        variant="primary"
                        @click="isImportInvoiceModalOpen = true"
                    >
                        {{ t('invoices.index.import') }}
                    </Button>

                    <Button
                        v-if="permissions.includes('create_invoice')"
                        variant="primary"
                        color="green"
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

        <InvoicesTable
            v-if="invoices.data.length > 0"
            :invoiceList="invoices"
        />
        <div
            v-else
            class="flex justify-center items-center h-96"
        >
            <p class="text-gray-500">
                {{ t('invoices.index.noInvoices') }}
            </p>
        </div>

        <CreateInvoiceModal
            :isOpen="isCreateInvoiceModalOpen"
            :micrositeSlug="microsite.slug"
            @closeModal="isCreateInvoiceModalOpen = false"
            :documentTypes="documentTypes"
        />

        <ImportInvoiceModal
            :isOpen="isImportInvoiceModalOpen"
            :micrositeSlug="microsite.slug"
            @closeModal="isImportInvoiceModalOpen = false"
        />
    </MainLayout>
</template>
