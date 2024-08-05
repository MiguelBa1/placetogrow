<script setup lang="ts">
import dayjs from "dayjs";
import { useI18n } from "vue-i18n";
import { MicrositeInformation } from "../index";

const { t } = useI18n();

const { microsite } = defineProps<{
    microsite: MicrositeInformation;
}>();

</script>

<template>
    <div class="grid md:grid-cols-3 gap-8">
        <div>
            <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                {{ t('microsites.show.details') }}
            </h3>
            <dl class="mt-4">
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.category') }}
                    </dt>
                    <dd class="mb-2">
                        {{ microsite.category.name }}
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.type') }}
                    </dt>
                    <dd class="mb-2">
                        {{ microsite.type }}
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.paymentExpiration') }}
                    </dt>
                    <dd class="mb-2">
                        {{ microsite.payment_expiration }}
                    </dd>
                </div>
            </dl>
        </div>

        <div>
            <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                {{ t('microsites.show.responsiblePerson.title') }}
            </h3>
            <dl class="mt-4">
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.responsiblePerson.name') }}
                    </dt>
                    <dd class="mb-2">
                        {{ microsite.responsible_name }}
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.responsiblePerson.documentNumber') }}
                    </dt>
                    <dd class="mb-2">
                        {{ microsite.responsible_document_number }}
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.responsiblePerson.documentType') }}
                    </dt>
                    <dd class="mb-2">
                        {{ microsite.responsible_document_type }}
                    </dd>
                </div>
            </dl>
        </div>

        <div>
            <h3 class="font-semibold text-lg text-gray-800 leading-tight">
                {{ t('microsites.show.timestamps') }}
            </h3>
            <dl class="mt-4">
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.createdAt') }}
                    </dt>
                    <dd class="mb-2">
                        {{ dayjs(microsite.created_at).format('DD/MM/YYYY HH:mm') }}
                    </dd>
                </div>
                <div class="flex flex-col">
                    <dt class="font-semibold text-gray-600">
                        {{ t('microsites.show.updatedAt') }}
                    </dt>
                    <dd class="mb-2">
                        {{ dayjs(microsite.updated_at).format('DD/MM/YYYY HH:mm') }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</template>
