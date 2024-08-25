<script setup lang="ts">
import { computed } from 'vue';

interface TextareaProps {
    id: string;
    name?: string;
    label?: string;
    placeholder?: string;
    className?: string;
    modelValue?: string;
    disabled?: boolean;
    error?: string;
    autoComplete?: 'on' | 'off';
    required?: boolean;
    rows?: number;
}

const props = defineProps<TextareaProps>();
const emit = defineEmits(['update:modelValue']);

const baseClasses = 'relative w-full bg-white border rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 sm:text-sm';
const errorClasses = 'border-red-500 focus:ring-red-500';
const normalClasses = 'border-gray-300 focus:ring-blue-500';
const disabledClasses = 'disabled:bg-gray-200 disabled:cursor-not-allowed';

const textareaClasses = computed(() => {
    return `${baseClasses} ${props.error ? errorClasses : normalClasses} ${disabledClasses}`;
});

const textareaAttrs = computed(() => {
    return {
        id: props.id,
        name: props.name,
        placeholder: props.placeholder,
        disabled: props.disabled,
        autocomplete: props.autoComplete,
        rows: props.rows || 3, // Default to 3 rows if not provided
    };
});

const updateValue = (event: Event) => {
    const target = event.target as HTMLTextAreaElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div :class="`w-full ${className ?? ''}`">
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <textarea
            v-bind="textareaAttrs"
            :id="id"
            :value="modelValue"
            @input="updateValue"
            :class="textareaClasses"
            :required="required"
        ></textarea>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
