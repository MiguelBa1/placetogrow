<template>
    <Disclosure :default-open="defaultOpen" v-slot="{ open }">
        <div class="bg-white border border-gray-200 rounded-lg">
            <DisclosureButton
                class="flex w-full p-4 text-left text-sm font-medium shadow text-gray-900 rounded-lg hover:bg-gray-500 hover:text-white
                 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75
                 transition-all duration-50 ease-in-out"
            >
                <span>{{ title }}</span>
                <span class="ml-auto">
                  <ChevronUpIcon v-if="open" class="w-5 h-5"/>
                  <ChevronDownIcon v-else class="w-5 h-5"/>
                </span>
            </DisclosureButton>
            <DisclosurePanel class="p-4 sm:p-6 text-sm text-gray-500">
                <slot/>
            </DisclosurePanel>
        </div>
    </Disclosure>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue'
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/16/solid'

interface Props {
    title: string
    defaultOpen?: boolean
}

const props = defineProps<Props>()

const defaultOpen = computed(() => props.defaultOpen ?? true)
</script>
