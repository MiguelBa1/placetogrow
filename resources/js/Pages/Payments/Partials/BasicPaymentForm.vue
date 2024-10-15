<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { InputField, Listbox, Button } from "@/Components";
import { Field, MicrositeInformation } from '../index';
import { useI18n } from 'vue-i18n';
import { useToast } from "vue-toastification";

const toast = useToast();

const {t} = useI18n();
const formData = ref<
    Record<string, string | number>
>({});

const formErrors = ref<
    Record<string, string>
>({});

const {microsite, fields} = defineProps<{
    fields: Field[];
    microsite: MicrositeInformation;
}>();

const isSubmitting = ref(false);

const getComponent = (type: string) => {
    switch (type) {
        case 'text':
        case 'email':
        case 'password':
        case 'number':
            return InputField;
        case 'select':
            return Listbox;
        default:
            return InputField;
    }
};

const handleSubmit = () => {
    isSubmitting.value = true;

    router.post(route('basic-payments.store', {
        microsite: microsite.slug
    }), formData.value, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(t('common.form.success'));
            formErrors.value = {};
        },
        onError: (error) => {
            toast.error(error?.payment ?? t('common.form.error'));
            formErrors.value = error ?? {};
        },
        onFinish: () => {
            isSubmitting.value = false;
        }
    });
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-4 p-10 bg-white rounded-xl shadow-sm">
        <div
            class="grid grid-cols-1 md:grid-cols-2 gap-4"
        >
            <div
                v-for="field in fields"
                :key="field.id"
            >
                <component
                    :is="getComponent(field.type)"
                    :id="field.name"
                    :type="field.type"
                    :name="field.name"
                    :label="field.label"
                    v-model="formData[field.name]"
                    :options="field.options"
                    :error="formErrors[field.name]"
                />
            </div>
        </div>
        <div class="space-y-3">
            <p class="text-start text-gray-700">
                {{ t('payments.show.paymentMessage', { payment_currency: microsite.payment_currency }) }}
            </p>
            <Button
                type="submit"
                :disabled="isSubmitting"
            >
                {{ t('payments.show.form.pay') }}
            </Button>
        </div>
    </form>
</template>
