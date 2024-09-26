<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { useI18n } from 'vue-i18n';
import { Button } from "@/Components";
import { DynamicForm, Field, MicrositeInformation, SubscriptionList, SubscriptionPlans } from "@/Pages/Payments";

const { t } = useI18n();

const { microsite, fields } = defineProps<{
    microsite: MicrositeInformation;
    fields: {
        data: Field[];
    };
    subscriptions?: SubscriptionList;
}>();

const goBack = () => {
    window.history.back();
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
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ microsite.name }}
                    </h2>
                </div>
                <Button
                    @click="goBack"
                    type="button"
                    variant="secondary"
                    color="gray"
                >
                    {{ t('common.back') }}
                </Button>
            </div>
        </template>

        <DynamicForm
            v-if="['basic', 'invoice'].includes(microsite.type)"
            :fields="fields.data"
            :microsite="microsite"
        />
        <SubscriptionPlans
            v-if="microsite.type === 'subscription' && subscriptions"
            :subscriptions="subscriptions"
            :microsite="microsite"
            :fields="fields.data"
        />
    </MainLayout>
</template>
