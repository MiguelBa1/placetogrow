<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { LanguageIcon } from '@heroicons/vue/16/solid';
import { Dropdown } from '@/Components';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const availableLanguages = [
    {
        code: 'es',
        name: t('components.ui.languageSwitcher.es')
    },
    {
        code: 'en',
        name: t('components.ui.languageSwitcher.en')
    },
];

const changeLanguage = (code: string) => {
    router.post(route('language.update'), {
        locale: code,
    }, {
        onSuccess: () => {
            location.reload();
        },
    });
};

</script>

<template>
    <Dropdown align="right" width="48" content-classes="py-1 bg-white">
        <template #trigger>
            <button
                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
            >
                <LanguageIcon
                    class="h-4 w-4 mr-1"
                />
                    {{ t('components.ui.languageSwitcher.language') }}
                <svg
                    class="ms-2 -me-0.5 h-4 w-4"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                        clip-rule="evenodd"
                    />
                </svg>
            </button>
        </template>
        <template #content>
            <ul class="text-gray-700">
                <li
                    v-for="lang in availableLanguages"
                    :key="lang.code"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                    @click="changeLanguage(lang.code)"
                >
                    {{ lang.name }}
                </li>
            </ul>
        </template>
    </Dropdown>
</template>
