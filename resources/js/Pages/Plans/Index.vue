<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { MainLayout } from "@/Layouts";
import { SubscriptionsList, SubscriptionsTable } from "@/Pages/Plans";
import { useI18n } from "vue-i18n";
import { Button } from "@/Components";

const { t } = useI18n();

defineProps<{
    microsite: { id: string; slug: string; name: string };
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
                    {{ t('subscriptions.index.title', { microsite: microsite.name }) }}
                </h2>

                <div class="space-x-2">
                    <Button
                        variant="primary"
                        @click="router.visit(route('microsites.subscriptions.create', { microsite }))"
                    >
                        {{ t('subscriptions.index.create') }}
                    </Button>
                    <Button
                        variant="secondary"
                        color="gray"
                        @click="goBack"
                    >
                        {{ t('common.back') }}
                    </Button>
                </div>
            </div>
        </template>

        <SubscriptionsTable
            v-if="subscriptions.data.length > 0"
            :subscriptions="subscriptions"
            :microsite="microsite"
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
