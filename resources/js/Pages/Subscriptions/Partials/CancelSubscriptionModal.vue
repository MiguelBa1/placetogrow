<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Modal, Button } from '@/Components';
import { useToast } from 'vue-toastification';
import { SubscriptionListItem } from '@/Pages/Subscriptions';

const { t } = useI18n();
const toast = useToast();

const { customer, subscription }  = defineProps<{
    isOpen: boolean;
    subscription: SubscriptionListItem | null;
    customer: {
        document_number: string;
        email: string;
    };
}>();

const emit = defineEmits(['closeModal']);

const closeModal = () => {
    emit('closeModal');
};

const submit = () => {
    if (!subscription) {
        return;
    }

    router.post(route('subscriptions.cancel', {
        subscriptionId: subscription.id,
    }), customer, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(t('subscriptions.show.cancel.success'));
        },
        onError: () => {
            toast.error(t('subscriptions.show.cancel.error'));
        },
        onFinish: () => {
            closeModal();
        },
    });
};

</script>

<template>
    <Modal
        :isOpen="isOpen"
        @close="closeModal"
        :title="t('subscriptions.show.cancel.title', { email: customer.email })"
    >
        <p>
            {{ t('subscriptions.show.cancel.message', {
            subscriptionName: subscription?.subscription_name,
            micrositeName: subscription?.microsite_name,
        }) }}
        </p>
        <template #footerButtons>
            <Button
                @click="closeModal"
                variant="secondary"
            >
                {{ t('subscriptions.show.cancel.close') }}
            </Button>
            <Button
                @click="submit"
                variant="primary"
                color="red"
            >
                {{ t('subscriptions.show.cancel.button') }}
            </Button>
        </template>
    </Modal>
</template>
