<script setup lang="ts">
import { ref, computed } from 'vue';
import { Modal, Button } from '@/Components';
import { useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toastification';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const toast = useToast();

const { micrositeSlug } = defineProps<{
    isOpen: boolean;
    micrositeSlug: string;
}>();

const emit = defineEmits(['closeModal']);

const form = useForm({
    invoices: null as File | null,
});

const fileInput = ref<HTMLInputElement | null>(null);

const handleButtonClick = () => {
    fileInput.value?.click();
};

const handleFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const file = target.files?.[0];

    if (file) {
        form.invoices = file;
    }
};

const submitForm = () => {
    if (!form.invoices) {
        toast.error(t('invoices.index.importModal.noFileError'));
        return;
    }

    form.post(route('microsites.invoices.import', micrositeSlug), {
        onSuccess: () => {
            toast.success(t('invoices.index.importModal.success'));
            emit('closeModal');
        },
        onError: (error) => {
            console.log(error)
            toast.error(t('invoices.index.importModal.error'));
        },
    });
};

const fileName = computed(() => form.invoices ? form.invoices.name : t('invoices.index.importModal.noFileSelected'));

</script>

<template>
    <Modal
        :isOpen="isOpen"
        @close="emit('closeModal')"
    >
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <p>{{ t('invoices.index.importModal.description') }}</p>
                <p>
                    <a
                        :href="route('microsites.invoices.download-template', micrositeSlug)"
                        class="text-blue-500 underline">
                        {{ t('invoices.index.importModal.downloadSample') }}
                    </a>
                </p>
            </div>
            <div class="mb-4">
                <Button type="button" variant="secondary" @click="handleButtonClick">
                    {{ t('invoices.index.importModal.chooseFile') }}
                </Button>
                <span class="ml-2">{{ fileName }}</span>
            </div>
            <input
                ref="fileInput"
                type="file"
                accept=".csv"
                class="hidden"
                @change="handleFileChange"
                required
            >
            <p class="text-red-500" v-if="form.errors.invoices">{{ form.errors.invoices }}</p>
        </form>
        <template #footerButtons>
            <Button
                variant="tertiary"
                @click="emit('closeModal')"
            >
                {{ t('common.cancel') }}
            </Button>

            <Button
                variant="primary"
                color="green"
                @click="submitForm"
                :disabled="form.processing"
            >
                {{
                    form.processing
                        ? t('common.loading')
                        : t('invoices.index.importModal.import')
                }}
            </Button>
        </template>
    </Modal>
</template>
