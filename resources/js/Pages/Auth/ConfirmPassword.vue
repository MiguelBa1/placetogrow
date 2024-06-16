<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { InputError, InputLabel, PrimaryButton, TextInput } from '@/Components';
import { MainLayout } from '@/Layouts';
import { Head, useForm } from '@inertiajs/vue3';

const { t } = useI18n();

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <MainLayout>
        <Head>
            <title>{{ t('auth.confirmPassword.title') }}</title>
        </Head>

        <form @submit.prevent="submit"
              class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <div class="mb-4 text-sm text-gray-600">
                {{ t('auth.confirmPassword.description') }}
            </div>

            <div>
                <InputLabel forId="password" :value="t('auth.confirmPassword.passwordLabel')" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex justify-end mt-4">
                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ t('auth.confirmPassword.confirmButton') }}
                </PrimaryButton>
            </div>
        </form>
    </MainLayout>
</template>
