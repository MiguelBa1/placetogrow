<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { Button } from "@/Components";
import { MainLayout } from '@/Layouts';
import { Category, EditForm, MicrositeEditData } from './index';
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const { microsite, categories, documentTypes, micrositeTypes, currencyTypes } = defineProps<{
    microsite: MicrositeEditData;
    categories: Category[];
    documentTypes: { label: string; value: string }[];
    micrositeTypes: { label: string; value: string }[];
    currencyTypes: { label: string; value: string }[];
}>();

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

        <EditForm
            :microsite="microsite"
            :categories="categories"
            :documentTypes="documentTypes"
            :currencyTypes="currencyTypes"
            :micrositeTypes="micrositeTypes"
        />
    </MainLayout>
</template>
