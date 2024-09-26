<script setup lang="ts">
import dayjs from 'dayjs';
import { useI18n } from "vue-i18n";
import { Link } from '@inertiajs/vue3';
import { Button } from "@/Components";
import { SubscriptionReturn, formattedSubscriptionStatus } from '@/Pages/Payments';
import { SubscriptionStatus } from '@/types/enums';

const { t } = useI18n();

const { subscription } = defineProps<{
    subscription: SubscriptionReturn;
    customer: {
        name: string;
        last_name: string;
    };
    micrositeName?: string;
}>();

const formattedCreatedAt = dayjs(subscription.created_at).format('DD/MM/YYYY HH:mm');
const formattedStartDate = dayjs(subscription.start_date).format('DD/MM/YYYY');
const formattedEndDate = dayjs(subscription.end_date).format('DD/MM/YYYY');
const formattedStatus = formattedSubscriptionStatus(t, subscription.status);

</script>

<template>
    <div class="space-y-6">
        <div
            class="flex gap-3 flex-col justify-between items-center p-5 border-b-2"
        >
            <div class="text-center">
                <p class="text-xl font-semibold">
                    {{ t('payments.result.greeting') }}
                </p>
                <p class="text-2xl font-bold">
                    {{ customer.name }} {{ customer.last_name }}
                </p>

                <p class="text-xl">
                    {{ t('payments.result.subscription_status') }}
                </p>

            </div>

            <div
                :class="{
                    'bg-red-200': subscription.status === SubscriptionStatus.INACTIVE,
                    'bg-orange-200': subscription.status === SubscriptionStatus.PENDING,
                    'bg-green-200': subscription.status === SubscriptionStatus.ACTIVE,
                }"
                class="text-xl font-bold text-gray-700 px-7 py-2 rounded-2xl"
            >
                {{ formattedStatus }}
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-y-10 text-center">
            <div class="space-y-2">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.subscription.reference') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ subscription.reference }}
                </dd>
            </div>

            <div class="space-y-2">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.subscription.created_at') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ formattedCreatedAt }}
                </dd>
            </div>

            <div class="space-y-2" v-if="subscription.status === SubscriptionStatus.ACTIVE">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.subscription.start_date') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ formattedStartDate }}
                </dd>
            </div>

            <div class="space-y-2" v-if="subscription.status === SubscriptionStatus.ACTIVE">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.subscription.end_date') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ formattedEndDate }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="flex justify-center">
        <Link
            :href="route('home')"
        >
            <Button>
                    {{ t('payments.result.go_to_home') }}
            </Button>
        </Link>
    </div>
</template>
