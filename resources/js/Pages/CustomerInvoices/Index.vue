<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { MainLayout } from '@/Layouts';
import { Button } from '@/Components';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const goBack = () => {
    history.back();
};

</script>

<template>
    <Head>
        <title>
            Mis Facturas
        </title>
    </Head>

    <MainLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800">
                    Mis Facturas
                </h2>
                <Button
                    variant="secondary"
                    color="gray"
                    @click="goBack"
                >
                    {{ t('common.back') }}
                </Button>
            </div>
        </template>
    </MainLayout>
</template>
