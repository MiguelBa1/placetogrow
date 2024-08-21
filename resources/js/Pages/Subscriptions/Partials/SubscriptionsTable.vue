<script setup lang="ts">
import dayjs from "dayjs";
import { DataTable } from "@/Components";
import { SubscriptionsList, getSubscriptionTableColumns } from "@/Pages/Subscriptions";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

defineProps<{
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
    </DataTable>
</template>
