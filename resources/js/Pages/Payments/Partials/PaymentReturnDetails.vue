<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from "@/Components";
import { formattedPaymentStatus, PaymentReturn } from "../index";
import { PaymentStatus } from "@/types/enums";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const { payment } = defineProps<{
    payment: PaymentReturn;
    customer: {
        name: string;
        last_name: string;
    };
    micrositeName: string;
}>();

const formattedPaymentDate = new Date(payment.payment_date).toLocaleString();
const formattedCreatedAt = new Date(payment.created_at).toLocaleString();
const formattedAmount = new Intl.NumberFormat().format(payment.amount);
const formattedStatus = formattedPaymentStatus(t, payment.status);

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

                <p class="text-lg">
                    {{ t('payments.result.payment_status', { micrositeName }) }}
                </p>
            </div>

            <div
                :class="{
                        'bg-red-200': payment.status === PaymentStatus.REJECTED,
                        'bg-orange-200': payment.status === PaymentStatus.PENDING,
                        'bg-green-200': payment.status === PaymentStatus.APPROVED,
                    }"
                class="text-xl font-bold text-gray-700 px-7 py-2 rounded-2xl"
            >
                {{ formattedStatus }}
            </div>
        </div>

        <dl class="grid grid-cols-2 gap-y-10 text-center">
            <div class="space-y-2">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.payment.reference') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ payment.reference }}
                </dd>
            </div>

            <div class="space-y-2">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.payment.total') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ formattedAmount }}
                    {{ payment.currency }}
                </dd>
            </div>

            <div class="space-y-2" v-if="payment.status === PaymentStatus.APPROVED">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.payment.method') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ payment.payment_method_name }}
                </dd>
            </div>

            <div class="space-y-2" v-if="payment.status === PaymentStatus.APPROVED">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.payment.authorization_code') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ payment.authorization }}
                </dd>
            </div>

            <div class="space-y-2" >
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.payment.created_at') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ formattedCreatedAt }}
                </dd>
            </div>

            <div class="space-y-2" v-if="payment.status === PaymentStatus.APPROVED">
                <dt class="text-md font-medium text-gray-500">
                    {{ t('payments.result.payment.date') }}
                </dt>
                <dd class="text-sm text-gray-900 font-semibold">
                    {{ formattedPaymentDate }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="flex justify-center">
        <Button>
            <Link
                :href="route('home')"
            >
                {{ t('payments.result.make_another_payment') }}
            </Link>
        </Button>
    </div>
</template>
