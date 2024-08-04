<script setup lang="ts">
import dayjs from "dayjs";
import { Head } from "@inertiajs/vue3";
import { MainLayout } from "@/Layouts";
import { Button } from "@/Components";
import { TransactionInformation, getStatusClass } from "./index";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
    transaction: TransactionInformation;
}>();

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>
            {{ t('transactions.show.title', { reference: transaction.data.reference }) }}
        </title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('transactions.show.title', { reference: transaction.data.reference }) }}
                </h2>
                <div>
                    <Button
                        variant="secondary"
                        color="gray"
                        @click="goBack"
                    >
                        {{ t('transactions.show.back') }}
                    </Button>
                </div>
            </div>
        </template>

        <div class="w-full p-6 sm:p-8 bg-white shadow rounded-lg space-y-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('transactions.show.details.title') }}
                    </h3>
                    <dl class="mt-4">
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.details.microsite') }}
                            </dt>
                            <dd class="mb-2">
                                {{ transaction.data.microsite }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.details.status') }}
                            </dt>
                            <dd class="mb-2">
                                <span
                                    class="font-bold"
                                    :class="getStatusClass(transaction.data.status.value)"
                                >
                                    {{ transaction.data.status.label }}
                                </span>
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.details.currency') }}
                            </dt>
                            <dd class="mb-2">
                                {{ transaction.data.currency }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.details.amount') }}
                            </dt>
                            <dd class="mb-2">
                                {{ '$ ' + new Intl.NumberFormat().format(transaction.data.amount) }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('transactions.show.customer.title') }}
                    </h3>
                    <dl class="mt-4">
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.customer.name') }}
                            </dt>
                            <dd class="mb-2">
                                {{ transaction.data.customer.name + ' ' + transaction.data.customer.last_name }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.customer.documentNumber') }}
                            </dt>
                            <dd class="mb-2">
                                {{ transaction.data.customer.document_number }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.customer.email') }}
                            </dt>
                            <dd class="mb-2 break-words">
                                {{ transaction.data.customer.email }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('transactions.show.details.additionalData') }}
                    </h3>
                    <dl class="mt-4">
                        <div v-for="(value, key) in transaction.data.additional_data" :key="key" class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ key }}
                            </dt>
                            <dd class="mb-2">
                                {{ value }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div>
                    <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                        {{ t('transactions.show.timestamps.title') }}
                    </h3>
                    <dl class="mt-4">
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.timestamps.paymentDate') }}
                            </dt>
                            <dd class="mb-2">
                                {{ dayjs(transaction.data.payment_date).format('DD/MM/YYYY HH:mm') }}
                            </dd>
                        </div>
                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.timestamps.createdAt') }}
                            </dt>
                            <dd class="mb-2">
                                {{ dayjs(transaction.data.created_at).format('DD/MM/YYYY HH:mm') }}
                            </dd>
                        </div>

                        <div class="flex flex-col">
                            <dt class="font-semibold text-gray-600">
                                {{ t('transactions.show.timestamps.updatedAt') }}
                            </dt>
                            <dd class="mb-2">
                                {{ dayjs(transaction.data.updated_at).format('DD/MM/YYYY HH:mm') }}
                            </dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>
    </MainLayout>
</template>
