<script setup lang="ts">
import MarkdownIt from 'markdown-it';
import { ref } from 'vue';
import { Button } from "@/Components";
import { SubscriptionList, SubscriptionItem, MicrositeInformation, Field, SubscriptionFormModal } from '@/Pages/Payments';
import { useI18n } from 'vue-i18n';
import { formatCurrency} from '@/Utils';

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

const formattedPrice = (price: number) => {
    return formatCurrency(price, microsite.payment_currency);
};

const selectedSubscription = ref<SubscriptionItem | null>(null);
const showModal = ref(false);

const openModal = (subscription: SubscriptionItem) => {
    selectedSubscription.value = subscription;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    selectedSubscription.value = null;
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
                <h3 class="text-lg font-semibold">{{ subscription.name }}</h3>
                <p class="text-2xl font-bold text-gray-800 my-4">
                    {{ formattedPrice(subscription.price) }} / {{ t('payments.show.subscription.month') }}
                </p>

                <p class="text-gray-600 my-4" v-html="renderMarkdown(subscription.description)"></p>

            </div>
            <Button @click="openModal(subscription)" class="w-full">
                {{ t('payments.show.subscription.button') }}
            </Button>
        </div>
    </div>
    <SubscriptionFormModal
        :isOpen="showModal"
        :fields="fields"
        :subscription="selectedSubscription"
        :micrositeSlug="microsite.slug"
        @closeModal="closeModal"
    />
</template>
