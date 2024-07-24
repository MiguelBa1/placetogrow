<script setup lang="ts">
import { computed } from 'vue';

interface FileInputProps {
    id: string;
    label?: string;
    name?: string;
    className?: string;
    error?: string;
    disabled?: boolean;
    required?: boolean;
    accept?: string;
}

const props = defineProps<FileInputProps>();
const emit = defineEmits(['update:modelValue']);

const baseClasses = 'relative w-full bg-white border rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 sm:text-sm';
const errorClasses = 'border-red-500 focus:ring-red-500';
const normalClasses = 'border-gray-300 focus:ring-blue-500';
const disabledClasses = 'disabled:bg-gray-200 disabled:cursor-not-allowed';

const inputClasses = computed(() => {
    return `${baseClasses} ${props.error ? errorClasses : normalClasses} ${disabledClasses}`;
});

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const files = target.files ? target.files[0] : null;
    emit('update:modelValue', files);
};
</script>

<template>
    <div :class="`w-full ${className ?? ''}`">
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <div class="relative">
            <input
                :id="id"
                :name="name"
                type="file"
                @change="handleFileChange"
                :disabled="disabled"
                :class="inputClasses"
                :required="required"
                :accept="accept"
                class="file-input-custom"
            />
        </div>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<style scoped>
.file-input-custom::file-selector-button {
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    background-color: #ffffff;
    color: #1f2937;
    font-size: 0.875rem;
    line-height: 1.25rem;
    cursor: pointer;
    transition: background-color 0.2s, border-color 0.2s, color 0.2s;
}

.file-input-custom::file-selector-button:hover {
    background-color: #f9fafb;
    border-color: #cbd5e0;
}

.file-input-custom::file-selector-button:focus {
    outline: 2px solid transparent;
    outline-offset: 2px;
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px #3b82f6;
}

.file-input-custom::file-selector-button:disabled {
    background-color: #f9fafb;
    cursor: not-allowed;
}

.file-input-custom {
    display: flex;
    align-items: center;
    padding: 0.5rem 0.75rem;
    height: calc(2.25rem + 2px);
}
</style>
