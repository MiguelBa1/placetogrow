<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { useI18n } from 'vue-i18n';
import { Accordion, Button } from '@/Components';
import { FieldsTable, MicrositeField, MicrositeInformation, MicrositeDetails } from "./index";

const { t } = useI18n();

const { microsite } = defineProps<{
    microsite: MicrositeInformation;
    fields: {
        data: MicrositeField[];
    };
}>();

const goBack = () => {
    history.back();
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
                    <div>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                            {{ microsite.name }}
                        </h2>
                        <p class="text-gray-600">
                            {{ microsite.slug }}
                        </p>
                    </div>
                </div>
                <div>
                    <Button
                        variant="secondary"
                        color="gray"
                        @click="goBack"
                    >
                        {{ t('microsites.edit.back') }}
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Accordion :title="t('microsites.show.generalInformation')" :default-open="true">
                <MicrositeDetails :microsite="microsite" />
            </Accordion>

            <Accordion :title="t('microsites.show.fieldsTable')" :default-open="true">
                <FieldsTable
                    v-if="fields.data.length > 0"
                    :fields="fields"
                    :micrositeSlug="microsite.slug"
                />
                <div
                    v-else
                    class="flex justify-center items-center h-40"
                >
                    <p class="text-gray-500">{{ t('microsites.show.noFields') }}</p>
                </div>
            </Accordion>
        </div>
    </MainLayout>
</template>
