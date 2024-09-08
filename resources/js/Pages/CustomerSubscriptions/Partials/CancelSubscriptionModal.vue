<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { Modal, Button } from '@/Components';
import { useToast } from 'vue-toastification';
import { SubscriptionListItem } from '@/Pages/CustomerSubscriptions';

const { t } = useI18n();
const toast = useToast();

const { customer, customerSubscription }  = defineProps<{
    isOpen: boolean;
    customerSubscription: SubscriptionListItem;
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

    router.post(route('subscriptions.cancel', {
        subscriptionId: customerSubscription.id,
    }), customer, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success(t('customerSubscriptions.show.cancel.success'));
        },
        onError: () => {
            toast.error(t('customerSubscriptions.show.cancel.error'));
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
        :title="t('customerSubscriptions.show.cancel.title', { email: customer.email })"
    >
        <p>
            {{ t('customerSubscriptions.show.cancel.message', {
                subscriptionName: customerSubscription.subscription_name,
                micrositeName: customerSubscription.microsite_name,
            }) }}
        </p>
        <template #footerButtons>
            <Button
                @click="closeModal"
                variant="secondary"
            >
                {{ t('customerSubscriptions.show.cancel.close') }}
            </Button>
            <Button
                @click="submit"
                variant="primary"
                color="red"
            >
                {{ t('customerSubscriptions.show.cancel.button') }}
            </Button>
        </template>
    </Modal>
</template>
