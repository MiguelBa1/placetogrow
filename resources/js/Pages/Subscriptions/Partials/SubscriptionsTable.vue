<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import { DataTable } from "@/Components";
import { PencilSquareIcon, TrashIcon, ArrowUturnLeftIcon } from '@heroicons/vue/16/solid';
import { SubscriptionsList, getSubscriptionTableColumns } from "@/Pages/Subscriptions";
import { useI18n } from "vue-i18n";
import { useToast } from "vue-toastification";

const { t } = useI18n();
const toast = useToast();

const { microsite, subscriptions } = defineProps<{
    microsite: { id: string; slug: string; name: string };
    subscriptions: SubscriptionsList;
}>();

const columns = getSubscriptionTableColumns(t);

const deleteSubscription = (subscriptionId: number) => {

    router.delete(route('microsites.plans.destroy', { microsite, subscription: subscriptionId }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(t('subscriptions.index.delete.success'));
        },
        onError: () => {
            toast.error(t('subscriptions.index.delete.error'));
        },
    });
};

const restoreSubscription = (subscriptionId: number) => {

    router.put(route('microsites.plans.restore', { microsite, subscription: subscriptionId }), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(t('subscriptions.index.restore.success'));
        },
        onError: () => {
            toast.error(t('subscriptions.index.restore.error'));
        },
    });
};

</script>

<template>
    <DataTable
        :rows="subscriptions.data"
        :columns="columns"
    >
        <template #cell-actions="{ row }">
            <div class="flex justify-center">
                <div
                    v-if="!row.deleted_at"
                    class="flex justify-center gap-2"
                >
                    <Link
                        :href="route('microsites.plans.edit', { microsite, subscription: row.id })"
                        class="text-blue-600 hover:text-blue-800"
                    >
                        <PencilSquareIcon class="w-5 h-5" />
                    </Link>
                    <button
                        class="text-red-600 hover:text-red-800"
                        @click="deleteSubscription(row.id)"
                    >
                        <TrashIcon class="w-5 h-5" />
                    </button>
                </div>
                <button
                    v-else
                    class="text-green-600 hover:text-green-800"
                    @click="restoreSubscription(row.id)"
                >
                    <ArrowUturnLeftIcon class="w-5 h-5" />
                </button>
            </div>
        </template>
    </DataTable>
</template>
