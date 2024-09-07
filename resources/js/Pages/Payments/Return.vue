<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { PaymentReturn, PaymentReturnDetails } from "./index";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

const { payment } = defineProps<{
    payment?: PaymentReturn;
}>();

</script>

<template>
    <MainLayout>
        <Head>
            <title>
                {{ t('payments.result.title') }}
            </title>
        </Head>

        <div class="max-w-xl bg-white w-full m-auto rounded-xl p-10 shadow-md space-y-8">
            <PaymentReturnDetails
                v-if="payment"
                :payment="payment"
            />

        </div>
    </MainLayout>
</template>
