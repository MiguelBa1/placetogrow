<script setup lang="ts">
import { MainLayout } from '@/Layouts';
import { usePage } from '@inertiajs/vue3';

type Microsites = {
    id: number;
    name: string;
    logo: string;
    category: {
        id: number;
        name: string;
    };
    type: string;
    payment_currency: string;
    payment_expiration: string;
    created_at: string;
    updated_at: string;
};

const { props } = usePage();

const microsites = props.microsites as Microsites[];

</script>

<template>
    <MainLayout>
        <h1>Microsites</h1>
        <span
            v-for="microsite in microsites"
            :key="microsite.id"
        >
            {{ microsite.name }}
        </span>
    </MainLayout>
</template>
