<script setup lang="ts">
import { computed, withDefaults } from 'vue';

type ButtonProps = {
    id?: string;
    type?: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    variant?: 'primary' | 'secondary' | 'tertiary';
    color?: 'blue' | 'green' | 'red' | 'gray';
};

const props = withDefaults(defineProps<ButtonProps>(), {
    id: '',
    type: 'button',
    disabled: false,
    variant: 'primary',
    color: 'blue',
});

const emit = defineEmits(['click']);

const onClick = () => {
    if (!props.disabled) {
        emit('click');
    }
};

const baseClasses = 'px-4 py-2 font-semibold rounded-md transition-colors';
const disabledClasses = 'disabled:bg-gray-200 disabled:cursor-not-allowed';

const colorClasses = {
    blue: {
        primary: 'bg-blue-600 hover:bg-blue-800 text-white',
        secondary: 'border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white',
    },
    green: {
        primary: 'bg-green-600 hover:bg-green-800 text-white',
        secondary: 'border border-green-500 text-green-500 hover:bg-green-500 hover:text-white',
    },
    red: {
        primary: 'bg-red-600 hover:bg-red-800 text-white',
        secondary: 'border border-red-500 text-red-500 hover:bg-red-500 hover:text-white',
    },
    gray: {
        primary: 'bg-gray-800 hover:bg-gray-700 text-white',
        secondary: 'border border-gray-500 text-gray-500 hover:bg-gray-500 hover:text-white',
    },
};

const variantClasses = {
    primary: computed(() => colorClasses[props.color].primary),
    secondary: computed(() => colorClasses[props.color].secondary),
    tertiary: 'border border-gray-300 text-black hover:bg-gray-100',
};

const resolvedVariantClass = computed(() => {
    const variantClass = variantClasses[props.variant];
    return typeof variantClass === 'string' ? variantClass : variantClass.value;
});

const buttonClasses = computed(() => {
    return [
        baseClasses,
        props.disabled ? '' : resolvedVariantClass.value,
        disabledClasses,
    ].join(' ');
});
</script>

<template>
    <button
        :id="props.id"
        :type="props.type"
        @click="onClick"
        :disabled="props.disabled"
        :class="buttonClasses"
    >
        <slot />
    </button>
</template>
