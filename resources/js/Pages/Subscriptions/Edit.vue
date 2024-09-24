<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3"
import { MainLayout } from "@/Layouts";
import { reactive, ref } from "vue";
import { InputField, TextareaField, Listbox, Button } from "@/Components";
import { GlobeAmericasIcon } from "@heroicons/vue/16/solid";
import { useToast } from "vue-toastification";
import { useI18n } from "vue-i18n";

const toast = useToast();
const { t } = useI18n();

const { microsite, timeUnits, subscription } = defineProps<{
    microsite: { id: string; slug: string };
    timeUnits: { label: string; value: string }[];
    subscription: {
        id: number;
        billing_frequency: number;
        price: number;
        time_unit: string;
        total_duration: number;
        translations: {
            locale: string;
            name: string;
            description: string;
        }[];
    };
}>();

const isSubmitting = ref(false);

const form = reactive({
    price: subscription.price,
    total_duration: subscription.total_duration,
    billing_frequency: subscription.billing_frequency,
    time_unit: subscription.time_unit,
    translations: subscription.translations,
});

const errors = reactive({
    price: '',
    total_duration: '',
    billing_frequency: '',
    time_unit: '',
    translations: [
        {
            name: '',
            description: '',
        },
        {
            name: '',
            description: '',
        }
    ],
});

const submit = () => {
    isSubmitting.value = true;

    router.put(
        route('microsites.plans.update', { microsite, subscription }),
        form,
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success(t('subscriptions.edit.success'));
            },
            onError: (error) => {
                errors.price = error?.price;
                errors.total_duration = error?.total_duration;
                errors.billing_frequency = error?.billing_frequency;
                errors.time_unit = error?.time_unit;
                errors.translations = [
                    {
                        name: error['translations.0.name'],
                        description: error['translations.0.description'],
                    },
                    {
                        name: error['translations.1.name'],
                        description: error['translations.1.description'],
                    }
                ];

                toast.error(t('subscriptions.edit.error'));
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        }
    );
};

const goBack = () => {
    history.back();
};
</script>

<template>
    <Head>
        <title>
            {{ t('subscriptions.edit.title') }}
        </title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('subscriptions.edit.header') }}
                </h2>

                <div class="space-x-2">
                    <Button
                        variant="secondary"
                        color="gray"
                        @click="goBack"
                    >
                        {{ t('common.back') }}
                    </Button>
                </div>
            </div>
        </template>

        <form
            @submit.prevent="submit"
            class="w-full p-4 sm:p-8 bg-white shadow sm:rounded-lg grid grid-cols-2 gap-4"
        >
            <InputField
                id="price"
                v-model="form.price"
                :label="t('subscriptions.edit.form.price')"
                type="number"
                :error="errors.price"
            />
            <InputField
                id="total_duration"
                v-model="form.total_duration"
                :label="t('subscriptions.edit.form.totalDuration')"
                type="number"
                :error="errors.total_duration"
            />
            <Listbox
                id="time_unit"
                v-model="form.time_unit"
                :label="t('subscriptions.edit.form.timeUnit')"
                :options="timeUnits"
                :error="errors.time_unit"
            />
            <InputField
                id="billing_frequency"
                v-model="form.billing_frequency"
                :label="t('subscriptions.edit.form.billingFrequency')"
                type="number"
                :error="errors.billing_frequency"
            />

            <div v-for="(translation, index) in form.translations" :key="index" class="col-span-2 space-y-4">
                <h3 class="flex items-center gap-1 text-lg font-medium text-gray-900">
                    <GlobeAmericasIcon class="h-5 w-5 inline-block text-blue-500" />
                    <span>
                        {{ t('subscriptions.edit.form.content', { locale: translation.locale }) }}
                    </span>
                </h3>

                <InputField
                    id="name"
                    v-model="translation.name"
                    :label="t('subscriptions.edit.form.name')"
                    type="text"
                    :error="errors.translations[index].name"
                />
                <TextareaField
                    id="description"
                    v-model="translation.description"
                    :label="t('subscriptions.edit.form.description')"
                    :error="errors.translations[index]?.description"
                    :rows="6"
                />
            </div>

            <div class="col-span-2">
                <Button
                    variant="primary"
                    @click="submit"
                >
                    {{ isSubmitting ? t('common.loading') : t('common.form.save') }}
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
