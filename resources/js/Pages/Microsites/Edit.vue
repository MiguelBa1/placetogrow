<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Button, Accordion } from "@/Components";
import { MainLayout } from '@/Layouts';
import { Category, EditForm, MicrositeField, FieldsTable } from './index';
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const { microsite, categories, documentTypes, micrositeTypes, currencyTypes, fields } = defineProps<{
    microsite: any;
    categories: Category[];
    documentTypes: { label: string; value: string }[];
    micrositeTypes: { label: string; value: string }[];
    currencyTypes: { label: string; value: string }[];
    fields: {
        data: MicrositeField[];
    };
}>();

console.log(fields)

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>{{ t('microsites.edit.title') }}</title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('microsites.edit.header') }}
                </h2>
                <Button
                    variant="secondary"
                    color="gray"
                    @click="goBack"
                >
                    {{ t('microsites.edit.back') }}
                </Button>
            </div>
        </template>

        <div class="space-y-6">
            <Accordion :title="t('microsites.edit.generalInformation')" :default-open="false">
                <EditForm
                    :microsite="microsite"
                    :categories="categories"
                    :documentTypes="documentTypes"
                    :currencyTypes="currencyTypes"
                    :micrositeTypes="micrositeTypes"
                />
            </Accordion>

            <Accordion :title="t('microsites.edit.fieldsTable')" :default-open="true">
                <FieldsTable
                    v-if="fields.data.length > 0"
                    :fields="fields" />
                <div
                    v-else
                    class="flex justify-center items-center h-40"
                >
                    <p class="text-gray-500">{{ t('microsites.edit.noFields') }}</p>
                </div>
            </Accordion>
        </div>
    </MainLayout>
</template>
