<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const { t } = useI18n();

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent'
);
</script>

<template>
    <AuthenticatedLayout>
        <Head>
            <title>{{ t('auth.verifyEmail.title') }}</title>
        </Head>

        <div class="flex justify-center">
            <form @submit.prevent="submit"
                  class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
            >
                <div class="mb-4 text-sm text-gray-600">
                    {{ t('auth.verifyEmail.description') }}
                </div>

                <div
                    class="mb-4 font-medium text-sm text-green-600"
                    v-if="verificationLinkSent"
                >
                    {{ t('auth.verifyEmail.verificationLinkSent') }}
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ t('auth.verifyEmail.resendButton') }}
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        {{ t('auth.verifyEmail.logoutButton') }}
                    </Link>
                </div>

            </form>
        </div>
    </AuthenticatedLayout>
</template>
