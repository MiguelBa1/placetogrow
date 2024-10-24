<script setup lang="ts">
import { Link, router } from "@inertiajs/vue3";
import { DataTable } from "@/Components";
import { PencilSquareIcon, TrashIcon, ArrowUturnLeftIcon } from '@heroicons/vue/16/solid';
import { PlansList, getPlanTableColumns } from "@/Pages/Plans";
import { useI18n } from "vue-i18n";
import { useToast } from "vue-toastification";

const { t } = useI18n();
const toast = useToast();

const { microsite, plans } = defineProps<{
    microsite: { id: string; slug: string; name: string };
    plans: PlansList;
}>();

const columns = getPlanTableColumns(t);

const deletePlan = (planId: number) => {

    router.delete(route('microsites.plans.destroy', { microsite, plan: planId }), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(t('plans.index.delete.success'));
        },
        onError: () => {
            toast.error(t('plans.index.delete.error'));
        },
    });
};

const restorePlan = (planId: number) => {

    router.put(route('microsites.plans.restore', { microsite, plan: planId }), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(t('plans.index.restore.success'));
        },
        onError: () => {
            toast.error(t('plans.index.restore.error'));
        },
    });
};

</script>

<template>
    <DataTable
        :rows="plans.data"
        :columns="columns"
    >
        <template #cell-actions="{ row }">
            <div class="flex justify-center">
                <div
                    v-if="!row.deleted_at"
                    class="flex justify-center gap-2"
                >
                    <Link
                        :href="route('microsites.plans.edit', { microsite, plan: row.id })"
                        class="text-blue-600 hover:text-blue-800"
                    >
                        <PencilSquareIcon class="w-5 h-5" />
                    </Link>
                    <button
                        class="text-red-600 hover:text-red-800"
                        @click="deletePlan(row.id)"
                    >
                        <TrashIcon class="w-5 h-5" />
                    </button>
                </div>
                <button
                    v-else
                    class="text-green-600 hover:text-green-800"
                    @click="restorePlan(row.id)"
                >
                    <ArrowUturnLeftIcon class="w-5 h-5" />
                </button>
            </div>
        </template>
    </DataTable>
</template>
