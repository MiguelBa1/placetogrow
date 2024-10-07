<script setup lang="ts">
import { computed } from 'vue';

interface InputProps {
    id: string;
    type: 'text' | 'email' | 'password' | 'number' | 'date';
    name?: string;
    label?: string;
    placeholder?: string;
    className?: string;
    modelValue?: string | number;
    disabled?: boolean;
    error?: string;
    autoComplete?: 'on' | 'off';
    required?: boolean;
    min?: number;
    max?: number;
}

const props = defineProps<InputProps>();
const emit = defineEmits(['update:modelValue']);

const baseClasses = 'relative w-full bg-white border rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 sm:text-sm';
const errorClasses = 'border-red-500 focus:ring-red-500';
const normalClasses = 'border-gray-300 focus:ring-blue-500';
const disabledClasses = 'disabled:bg-gray-200 disabled:cursor-not-allowed';

const inputClasses = computed(() => {
    return `${baseClasses} ${props.error ? errorClasses : normalClasses} ${disabledClasses}`;
});

const inputAttrs = computed(() => {
    return {
        id: props.id,
        name: props.name,
        type: props.type,
        placeholder: props.placeholder,
        disabled: props.disabled,
        autocomplete: props.autoComplete,
    };
});

const updateValue = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div :class="`w-full ${className ?? ''}`">
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700 mb-1">{{ label }}</label>
        <input
            v-bind="inputAttrs"
            :id="id"
            :value="modelValue"
            @input="updateValue"
            :class="inputClasses"
            :required="required"
            :min="min"
            :max="max"
        />
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>
