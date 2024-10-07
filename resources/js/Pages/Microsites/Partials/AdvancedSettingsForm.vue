<script setup lang="ts">
import { InputField, Listbox, Button } from '@/Components';
import { MicrositeEditData, MicrositeType } from '../index';
import { useForm, router } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const toast = useToast();
const { t } = useI18n();

const { microsite } = defineProps<{
    microsite: MicrositeEditData;
    lateFeeTypes: { label: string; value: string }[];
}>();

const form = useForm<{
    max_retries?: number;
    retry_backoff?: number;
    late_fee_type?: string;
    late_fee_value?: number;
}>({
    max_retries: microsite.settings.retry?.max_retries,
    retry_backoff: microsite.settings.retry?.retry_backoff,
    late_fee_type: microsite.settings.late_fee?.type,
    late_fee_value: microsite.settings.late_fee?.value,
});

const saveSettings = async () => {
    const settings : {
        retry: {
            max_retries: number;
            retry_backoff: number;
        } | null;
        late_fee: {
            type: string;
            value: number;
        } | null;
    } = {
        retry: null,
        late_fee: null,
    };
    if (microsite.type === MicrositeType.INVOICE) {
        settings.late_fee = {
            type: form.late_fee_type!,
            value: form.late_fee_value!,
        };
    } else if (microsite.type === MicrositeType.SUBSCRIPTION) {
        settings.retry = {
            max_retries: form.max_retries!,
            retry_backoff: form.retry_backoff!,
        };
    }
    const payload = {
        settings,
    };

    router.post(route('microsites.update-settings', microsite), payload, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(t('microsites.edit.settings.saved'));
        },
        onError: (errors) => {
            const errorMessages = Object.values(errors).flat();
            errorMessages.forEach((message: string) => {
                toast.error(message, {
                    timeout: 5000,
                });
            });
        },
    });
};


</script>

<template>
    <form
        class="grid grid-cols-1 gap-6 md:grid-cols-2"
        @submit.prevent="saveSettings"
    >
        <template
            v-if="microsite.type === MicrositeType.INVOICE"
        >
            <Listbox
                required
                :options="lateFeeTypes"
                v-model="form.late_fee_type"
                :label="t('microsites.edit.settings.lateFeeType')"
                :errors="form.errors.late_fee_type"
            />
            <InputField
                required
                id="late_fee_value"
                type="number"
                :label="t('microsites.edit.settings.lateFeeValue')"
                :min="0"
                v-model="form.late_fee_value"
                :errors="form.errors.late_fee_value"
            />
        </template>
        <template
            v-else-if="microsite.type === MicrositeType.SUBSCRIPTION"
        >
            <InputField
                required
                id="max_retries"
                type="number"
                :label="t('microsites.edit.settings.maxRetries')"
                :min="1"
                v-model="form.max_retries"
                :errors="form.errors.max_retries"
            />
            <InputField
                required
                id="retry_backoff"
                type="number"
                :label="t('microsites.edit.settings.retryBackoff')"
                :min="1"
                v-model="form.retry_backoff"
                :errors="form.errors.retry_backoff"
            />
        </template>

        <div class="col-span-2">
            <Button type="submit" variant="primary">Guardar</Button>
        </div>
    </form>
</template>
