<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { InputError, InputLabel, PrimaryButton, TextInput } from '@/Components';
import { MainLayout } from '@/Layouts';

import { Head, useForm } from '@inertiajs/vue3';

defineProps<{
    status?: string;
}>();

const { t } = useI18n();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <MainLayout>
        <Head>
            <title>{{ t('auth.forgotPassword.title') }}</title>
        </Head>

        <form @submit.prevent="submit"
              class="w-full mx-auto sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="mb-4 text-sm text-gray-600">
                {{ t('auth.forgotPassword.description') }}
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <div>
                <InputLabel forId="email" :value="t('auth.forgotPassword.emailLabel')" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ t('auth.forgotPassword.emailButton') }}
                </PrimaryButton>
            </div>
        </form>
    </MainLayout>
</template>
