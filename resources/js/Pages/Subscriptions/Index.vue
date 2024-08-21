<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from "@/Layouts";
import { SubscriptionsList, SubscriptionsTable } from "@/Pages/Subscriptions";
import { useI18n } from "vue-i18n";
import { Button } from "@/Components";

const { t } = useI18n();

defineProps<{
    microsite: { id: string; slug: string };
    subscriptions: SubscriptionsList;
}>();

const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>Subscriptions</title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('subscriptions.index.title') }}
                </h2>

                <Button
                    variant="secondary"
                    color="gray"
                    @click="goBack"
                >
                    {{ t('common.back') }}
                </Button>
            </div>
        </template>

        <SubscriptionsTable
            v-if="subscriptions.data.length > 0"
            :subscriptions="subscriptions"
        />
        <div
            v-else
            class="flex items-center justify-center h-96"
        >
            <p class="text-gray-500 text-lg">
                {{ t('subscriptions.index.table.no_subscriptions') }}
            </p>
        </div>
    </MainLayout>
</template>
