<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/16/solid';
import { useI18n } from 'vue-i18n';
import { DataTable } from '@/Components';
import { SubscriptionsList, getSubscriptionTableColumns, getStatusClass } from '@/Pages/CustomerSubscriptions';
import { MainLayout } from '@/Layouts';
import { SubscriptionStatus } from '@/types/enums';

const { t } = useI18n();

const { subscriptions } = defineProps<{
    subscriptions: SubscriptionsList;
    customer: {
        document_number: string;
        email: string;
    };
}>();

const subscriptionColumns = getSubscriptionTableColumns(t);

</script>

<template>
    <Head>
        <title>
            {{ t('customerSubscriptions.show.title', { email: customer.email }) }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800">
                {{ t('customerSubscriptions.show.title', { email: customer.email }) }}
            </h2>
        </template>

        <DataTable
            v-if="subscriptions.data?.length > 0"
            :columns="subscriptionColumns" :rows="subscriptions.data" class="rounded-lg"
        >
            <template #cell-microsite="{ row }">
                {{ row.microsite.name }}
            </template>
            <template #cell-status="{ row }">
                <span
                    :class="getStatusClass(row.status.value)"
                >
                    {{ row.status.label }}
                </span>
            </template>
            <template #cell-actions="{ row }">
                <button
                    v-if="SubscriptionStatus.ACTIVE === row.status.value"
                    class="text-red-600 hover:text-red-800"
                    @click="() => console.log('Delete subscription', row)"
                >
                    <TrashIcon class="w-5 h-5" />
                </button>
            </template>
        </DataTable>
        <div
            v-else
            class="flex justify-center items-center h-96"
        >
            <p class="text-gray-500">
                {{ t('customerSubscriptions.show.noSubscriptions') }}
            </p>
        </div>
    </MainLayout>

</template>
