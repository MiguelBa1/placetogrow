<script setup lang="ts">
defineProps<{
    forId: string;
    value?: string;
}>();

</script>

<template>
    <label :for="forId" class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
