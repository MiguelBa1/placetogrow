<template>
    <div :class="`w-full space-y-1 ${className}`">
        <Listbox v-model="selectedValue" :disabled="disabled">
            <template v-if="label">
                <ListboxLabel
                    :for="id"
                    class="block text-sm font-medium text-gray-700"
                >
                    {{ label }}
                </ListboxLabel>
            </template>
            <div class="relative">
                <ListboxButton
                    :id="id"
                    :aria-required="required"
                    :class="[
                        baseClasses,
                        error ? errorClasses : normalClasses,
                        disabled ? disabledClasses : '',
                    ]"
                >
                    <span
                        :class="`block truncate ${!selectedOption ? 'text-gray-400' : ''}`"
                    >
                        {{
                            selectedOption ? selectedOption.label : placeholder
                        }}
                    </span>
                    <span
                        class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                    >
                        <ChevronDownIcon
                            class="w-5 h-5 text-gray-400"
                            aria-hidden="true"
                        />
                    </span>
                </ListboxButton>
                <Transition
                    leave="transition ease-in duration-100"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <ListboxOptions
                        class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                    >
                        <ListboxOption
                            v-for="option in options"
                            :key="option.value"
                            :value="option.value"
                            class="relative cursor-default select-none py-2 pl-10 pr-4 hover:bg-gray-100"
                        >
                            <template #default="{ active, selected }">
                                <span
                                    :class="`block truncate ${selected ? 'font-medium' : 'font-normal'}`"
                                >
                                    {{ option.label }}
                                </span>
                                <span
                                    v-if="selected"
                                    :class="`absolute inset-y-0 left-0 flex items-center pl-3 ${
                                        active
                                            ? 'text-blue-600'
                                            : 'text-blue-600'
                                    }`"
                                >
                                    <CheckIcon
                                        class="w-5 h-5"
                                        aria-hidden="true"
                                    />
                                </span>
                            </template>
                        </ListboxOption>
                    </ListboxOptions>
                </Transition>
            </div>
        </Listbox>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { computed, withDefaults } from 'vue';
import {
    Listbox,
    ListboxButton,
    ListboxOptions,
    ListboxOption,
    ListboxLabel,
} from '@headlessui/vue';
import { ChevronDownIcon, CheckIcon } from '@heroicons/vue/24/solid';

type Option = {
    label: string;
    value: string | number;
};

const props = withDefaults(defineProps<{
    id: string;
    modelValue: string | number | null;
    options: Option[];
    label?: string;
    placeholder?: string;
    className?: string;
    error?: string;
    disabled?: boolean;
    required?: boolean;
}>(), {
    placeholder: 'Seleccionar',
    className: '',
    disabled: false,
    label: '',
    required: false,
    id: '',
});

const emit = defineEmits(['update:modelValue']);

const baseClasses =
    'relative w-full bg-white border rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 sm:text-sm';
const errorClasses = 'border-red-500 focus:ring-red-500';
const normalClasses = 'border-gray-300 focus:ring-blue-500';
const disabledClasses = 'disabled:bg-gray-200 disabled:cursor-not-allowed';

const selectedValue = computed({
    get() {
        return props.modelValue;
    },
    set(value) {
        emit('update:modelValue', value);
    },
});

const selectedOption = computed(() => {
    return props.options.find((option) => option.value === selectedValue.value);
});

</script>
