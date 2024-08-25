<script setup lang="ts">
import dayjs from "dayjs";
import { Link } from "@inertiajs/vue3";
import { DataTable } from "@/Components";
import { PencilSquareIcon } from '@heroicons/vue/16/solid';
import { SubscriptionsList, getSubscriptionTableColumns } from "@/Pages/Subscriptions";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
    microsite: { id: string; slug: string; name: string };
    subscriptions: SubscriptionsList;
}>();

const columns = getSubscriptionTableColumns(t);

</script>

<template>
    <DataTable
        :rows="subscriptions.data"
        :columns="columns"
    >
        <template #cell-price="{ row }">
            {{ '$ ' + new Intl.NumberFormat().format(row.price) }}
        </template>
        <template #cell-created_at="{ row }">
            {{
                row.created_at ?
                    dayjs(row.created_at).format('DD/MM/YYYY HH:mm')
                    : '-'
            }}
        </template>
        <template #cell-actions="{ row }">
            <div class="flex justify-center gap-2">
                <Link
                    :href="route('microsites.subscriptions.edit', { microsite, subscription: row.id })"
                    class="text-blue-600 hover:text-blue-800"
                >
                    <PencilSquareIcon class="w-5 h-5" />
                </Link>
            </div>
        </template>
    </DataTable>
</template>
