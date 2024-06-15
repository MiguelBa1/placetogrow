<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { computed } from 'vue';
import {
    Dialog,
    DialogTitle,
    DialogOverlay,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import { Button } from './index';

const { t } = useI18n();

const props = withDefaults(
    defineProps<{
        isOpen: boolean;
        title?: string;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
        closeable?: boolean;
    }>(),
    {
        maxWidth: '2xl',
        closeable: true,
    }
);

const emit = defineEmits(['close']);

const onClose = () => {
    if (props.closeable) {
        emit('close');
    }
};

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[props.maxWidth];
});
</script>

<template>
    <Teleport to="body">
        <TransitionRoot :show="isOpen" as="template">
            <Dialog
                as="div"
                class="fixed inset-0 z-50 overflow-y-auto"
                @close="onClose"
            >
                <div class="min-h-screen px-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enterFrom="opacity-0"
                        enterTo="opacity-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100"
                        leaveTo="opacity-0"
                    >
                        <DialogOverlay
                            class="fixed inset-0 bg-black opacity-30"
                        />
                    </TransitionChild>

                    <span
                        class="inline-block h-screen align-middle"
                        aria-hidden="true"
                    >
                        &#8203;
                    </span>
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enterFrom="opacity-0 scale-95"
                        enterTo="opacity-100 scale-100"
                        leave="ease-in duration-200"
                        leaveFrom="opacity-100 scale-100"
                        leaveTo="opacity-0 scale-95"
                    >
                        <div
                            :class="[
                                'inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg',
                                maxWidthClass,
                            ]"
                        >
                            <DialogTitle
                                v-if="title"
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                {{ title }}
                            </DialogTitle>
                            <div class="mt-2">
                                <slot />
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <slot name="footerButtons">
                                    <Button
                                        @click="onClose"
                                        class="bg-gray-600 hover:bg-gray-700 focus-visible:ring-gray-500"
                                    >
                                        {{ t('components.ui.modal.close') }}
                                    </Button>
                                </slot>
                            </div>
                        </div>
                    </TransitionChild>
                </div>
            </Dialog>
        </TransitionRoot>
    </Teleport>
</template>

