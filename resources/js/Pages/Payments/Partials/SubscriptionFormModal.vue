<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { InputField, Listbox, Button, Modal } from "@/Components";
import { Field, SubscriptionItem } from '@/Pages/Payments';
import { useI18n } from 'vue-i18n';
import { useToast } from "vue-toastification";

const { subscription, micrositeSlug } = defineProps<{
    isOpen: boolean;
    micrositeSlug: string;
    subscription: SubscriptionItem | null;
    fields: Field[];
}>();

const { t } = useI18n();
const toast = useToast();
const formData = ref<
    Record<string, string | number>
>({});

const formErrors = ref<
    Record<string, string>
>({
    subscription_id: subscription?.id ?? '',
});

const isSubmitting = ref(false);
const formRef = ref<HTMLFormElement | null>(null);

const emit = defineEmits(['closeModal']);

const closeModal = () => {
    emit('closeModal');
};

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

const handleSubmit = (subscription: SubscriptionItem) => {
    isSubmitting.value = true;

    router.post(route('payments.store', {
        microsite: micrositeSlug,
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
    <Modal
        :isOpen="isOpen"
        @close="closeModal"
    >
        <h2 class="text-2xl font-semibold text-gray-800">
            {{ t('payments.show.subscription.form.title') }}
        </h2>
        <p class="text-gray-600 my-4">
            {{ t('payments.show.subscription.form.subTitle') }}
        </p>

        <form
            ref="formRef"
            @submit.prevent="handleSubmit"
            class="grid grid-cols-2 gap-6"
        >
            <div v-for="field in fields">
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
        </form>
        <template #footerButtons>
            <Button
                type="button"
                variant="secondary"
                color="gray"
                @click="closeModal"
            >
                {{ t('common.cancel') }}
            </Button>

            <Button
                class="col-span-2"
                :loading="isSubmitting"
                @click="() => {
                    handleSubmit();
                }"
            >
                {{ t('payments.show.subscription.form.submit') }}
            </Button>
        </template>
    </Modal>
</template>
