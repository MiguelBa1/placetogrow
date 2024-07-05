<script setup lang="ts">
import dayjs from 'dayjs';
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { useI18n } from 'vue-i18n';
import { Button } from '@/Components';

const { t } = useI18n();

const { microsite } = defineProps<{
    microsite: {
        id: string;
        name: string;
        slug: string;
        logo: string;
        type: string;
        category: {
            id: string;
            name: string;
        };
        responsible_name: string;
        responsible_document_number: string;
        responsible_document_type: string;
        payment_currency: string;
        payment_expiration?: string;
        created_at: string;
        updated_at: string;
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

        <div class="bg-white p-10 rounded-xl shadow-sm">
            <div class="grid md:grid-cols-3 gap-8">
                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('microsites.show.details') }}
                    </h3>
                    <dl class="mt-4">
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.category') }}
                            </dt>
                            <dd class="mb-2">
                                {{ microsite.category.name }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.type') }}
                            </dt>
                            <dd class="mb-2">
                                {{ microsite.type }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.paymentExpiration') }}
                            </dt>
                            <dd class="mb-2">
                                {{ microsite.payment_expiration }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('microsites.show.responsiblePerson.title') }}
                    </h3>
                    <dl class="mt-4">
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.responsiblePerson.name') }}
                            </dt>
                            <dd class="mb-2">
                                {{ microsite.responsible_name }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.responsiblePerson.documentNumber') }}
                            </dt>
                            <dd class="mb-2">
                                {{ microsite.responsible_document_number }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.responsiblePerson.documentType') }}
                            </dt>
                            <dd class="mb-2">
                                {{ microsite.responsible_document_type }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('microsites.show.timestamps') }}
                    </h3>
                    <dl class="mt-4">
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.createdAt') }}
                            </dt>
                            <dd class="mb-2">
                                {{ dayjs(microsite.created_at).format('DD/MM/YYYY HH:mm') }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('microsites.show.updatedAt') }}
                            </dt>
                            <dd class="mb-2">
                                {{ dayjs(microsite.updated_at).format('DD/MM/YYYY HH:mm') }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
