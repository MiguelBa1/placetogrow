<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/16/solid';
import { useI18n } from 'vue-i18n';
import { DataTable } from '@/Components';
import {
    SubscriptionListItem,
    SubscriptionsList,
    getSubscriptionTableColumns,
    getStatusClass,
    CancelSubscriptionModal,
    CustomerDetails,
} from '@/Pages/Subscriptions';
import { MainLayout } from '@/Layouts';

const { t } = useI18n();

const { subscriptions, customer } = defineProps<{
    subscriptions: SubscriptionsList;
    customer: CustomerDetails;
}>();
console.log(customer);

const subscriptionColumns = getSubscriptionTableColumns(t);

const isCancelSubscriptionModalOpen = ref(false);

const selectedSubscription = ref<SubscriptionListItem | null>(null);

const openCancelSubscriptionModal = (subscription: SubscriptionListItem) => {
    selectedSubscription.value = subscription;
    isCancelSubscriptionModalOpen.value = true;
};
</script>

<template>
    <Head>
        <title>
            {{ t('subscriptions.show.title') }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                {{ t('subscriptions.show.title') }}
            </h2>
        </template>

        <div class="justify-between flex gap-4 flex-col xl:flex-row">
            <div class="w-full xl:max-w-xs space-y-8 px-6 py-4 bg-white shadow-md sm:rounded-lg">
                <div class="font-bold text-xl text-gray-800">
                    {{ t('subscriptions.show.personalInformation') }}
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="text-gray-900 font-medium text-lg">
                            {{ customer.data.name }}
                        </div>
                        <div class="text-gray-500 text-sm">
                            {{ customer.data.email }}
                        </div>
                    </div>

                    <div>
                        <span class="block text-gray-600 font-semibold">
                            {{ t('subscriptions.show.documentType') }}
                        </span>
                        <span class="text-gray-900">
                            {{ customer.data.document_type }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-gray-600 font-semibold">
                            {{ t('subscriptions.show.documentNumber') }}
                        </span>
                        <span class="text-gray-900">
                            {{ customer.data.document_number }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-gray-600 font-semibold">
                            {{ t('subscriptions.show.phone') }}
                        </span>
                        <span class="text-gray-900">
                            {{ customer.data.phone }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="w-full space-y-8 px-6 py-4 bg-white shadow-md sm:rounded-lg">
                <div class="font-bold text-xl text-gray-800">
                    {{ t('subscriptions.show.activeSubscriptions') }}
                </div>

                <DataTable
                    v-if="subscriptions.data?.length > 0"
                    :columns="subscriptionColumns"
                    :rows="subscriptions.data"
                    class="rounded-lg"
                >
                    <template #cell-microsite="{ row }">
                        {{ row.microsite.name }}
                    </template>
                    <template #cell-status="{ row }">
                        <span :class="getStatusClass(row.status.value)">
                            {{ row.status.label }}
                        </span>
                    </template>
                    <template #cell-actions="{ row }">
                        <button
                            class="text-red-600 hover:text-red-800"
                            @click="openCancelSubscriptionModal(row as SubscriptionListItem)"
                        >
                            <TrashIcon class="w-5 h-5" />
                        </button>
                    </template>
                </DataTable>

                <div v-else class="flex justify-center items-center h-48">
                    <p class="text-gray-500">
                        {{ t('subscriptions.show.noSubscriptions') }}
                    </p>
                </div>
            </div>
        </div>
    </MainLayout>
    <CancelSubscriptionModal
        v-if="isCancelSubscriptionModalOpen"
        :isOpen="isCancelSubscriptionModalOpen"
        :subscription="selectedSubscription"
        :customer="customer.data"
        @closeModal="isCancelSubscriptionModalOpen = false"
    />
</template>
