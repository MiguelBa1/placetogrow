<script setup lang="ts">

defineProps<{
    subscription: {
        reference: string;
        amount: number;
        currency: string;
        status_message: string;
        payment_method_name: string;
        created_at: string;
        start_date: string;
        end_date: string;
    };
}
>();

</script>

<template>
    {{ JSON.stringify(subscription) }}
</template>
