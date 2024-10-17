<script setup lang="ts">
import MarkdownIt from 'markdown-it';
import { ref } from 'vue';
import { Button } from "@/Components";
import { SubscriptionList, MicrositeInformation, Field, SubscriptionFormModal } from '@/Pages/Payments';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const markdown = new MarkdownIt();

const { subscriptions, microsite } = defineProps<{
    fields: Field[];
    subscriptions: SubscriptionList;
    microsite: MicrositeInformation;
}>();

const renderMarkdown = (description: string) => {
    return markdown.render(description);
};

const showModal = ref(false);
const selectedPlan = ref<number | null>(null);

const openModal = (subscriptionId: number) => {
    selectedPlan.value = subscriptionId;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedPlan.value = null;
};
</script>

<template>
    <div class="flex flex-wrap justify-center gap-3">
        <div
            v-for="subscription in subscriptions.data"
            :key="subscription.id"
            class="flex flex-col justify-between gap-6 bg-white shadow-lg rounded-lg p-6 text-center w-full sm:w-1/2 lg:w-1/3 max-w-xs"
        >
            <div class="space-y-2">
                <h3 class="text-xl font-medium">{{ subscription.name }}</h3>
                <p class="text-2xl font-semibold text-gray-800">
                    {{ subscription.price }} / {{ subscription.billing_frequency }}
                </p>
                <p class="font-semibold text-gray-600 my-4">
                    {{ t('payments.show.subscription.duration') }}: {{ subscription.total_duration }}
                </p>
                <p class="text-gray-600 my-4" v-html="renderMarkdown(subscription.description)"></p>
            </div>
            <Button @click="openModal(subscription.id)" class="w-full">
                {{ t('payments.show.subscription.button') }}
            </Button>
        </div>
    </div>
    <SubscriptionFormModal
        v-if="showModal"
        :isOpen="showModal"
        :fields="fields"
        :planId="selectedPlan"
        :micrositeSlug="microsite.slug"
        @closeModal="closeModal"
    />
</template>

