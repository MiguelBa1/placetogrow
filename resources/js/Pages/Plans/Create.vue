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

const { microsite, timeUnits } = defineProps<{
    microsite: { id: string; slug: string };
    timeUnits: { label: string; value: string }[];
}>();

const isSubmitting = ref(false);

const form = reactive({
    price: undefined,
    total_duration: undefined,
    billing_frequency: undefined,
    time_unit: undefined,
    translations: [
        {
            locale: 'en',
            name: '',
            description: '',
        },
        {
            locale: 'es',
            name: '',
            description: '',
        }
    ],
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

    router.post(
        route('microsites.plans.store', microsite),
        form,
        {
            preserveScroll: true,
            onSuccess: () => {
                toast.success(t('subscriptions.create.success'));
                isSubmitting.value = false;
            },
            onError: (error) => {
                toast.error(t('subscriptions.create.error'));
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
            {{ t('subscriptions.create.header') }}
        </title>
    </Head>
    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ t('subscriptions.create.header') }}
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
                type="number"
                :label="t('subscriptions.create.form.price')"
                v-model="form.price"
                :error="errors.price"
                required
            />

            <InputField
                id="total_duration"
                type="number"
                :label="t('subscriptions.create.form.totalDuration')"
                v-model="form.total_duration"
                :error="errors.total_duration"
                required
            />

            <InputField
                id="billing_frequency"
                type="number"
                :label="t('subscriptions.create.form.billingFrequency')"
                v-model="form.billing_frequency"
                :error="errors.billing_frequency"
                required
            />

            <Listbox
                id="time_unit"
                :label="t('subscriptions.create.form.timeUnit')"
                v-model="form.time_unit"
                :options="timeUnits"
                :error="errors.time_unit"
                required
            />

            <div v-for="(translation, index) in form.translations" :key="index" class="col-span-2 space-y-4">
                <h3 class="flex items-center gap-1 text-lg font-medium text-gray-900">
                    <GlobeAmericasIcon class="h-5 w-5 inline-block text-blue-500" />
                    <span>
                        {{ t('subscriptions.create.form.content', { locale: translation.locale }) }}
                    </span>
                </h3>

                <InputField
                    :id="`translation-name-${index}`"
                    type="text"
                    :label="t('subscriptions.create.form.name')"
                    v-model="translation.name"
                    :error="errors.translations[index]?.name"
                />
                <TextareaField
                    :id="`translation-description-${index}`"
                    :label="t('subscriptions.create.form.description')"
                    v-model="translation.description"
                    :error="errors.translations[index]?.description"
                />
            </div>
            <div>
                <Button type="submit" class="col-span-2">
                    {{ isSubmitting ? t('common.loading') : t('common.create') }}
                </Button>
            </div>
        </form>
    </MainLayout>
</template>
