<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    payment: Object,
});
</script>

<template>
    <div
        class="max-w-xl bg-white w-full m-auto rounded-xl p-10 shadow-md mt-10"
    >
        <div>
            <div
                class="flex justify-between items-center p-5 border-b-2 text-center"
            >
                <dl>
                    <dt class="text-xl font-semibold text-gray-500">
                        Detalles del Pago
                    </dt>
                    <dd class="text-xl font-semibold text-gray-700">
                        {{ payment.status_message }}
                    </dd>
                </dl>
                <div
                    :class="{
                        'bg-red-200': payment.status === 'REJECTED',
                        'bg-orange-200':
                            payment.status === 'PENDING' ||
                            payment.status === 'OK',
                        'bg-green-200': payment.status === 'APPROVED',
                    }"
                    class="ml-10 text-xl font-bold text-gray-700 px-7 py-2 rounded-2xl"
                >
                    {{
                        payment.status === 'REJECTED'
                            ? 'RECHAZADO'
                            : payment.status === 'PENDING' ||
                                payment.status === 'OK'
                              ? 'PENDIENTE'
                              : 'APROBADO'
                    }}
                </div>
            </div>

            <dl class="mt-14 grid grid-cols-2 gap-y-10 text-center">
                <div>
                    <dt class="text-md font-medium text-gray-500">
                        Referencia de pago
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ payment.payment_reference }}
                    </dd>
                </div>

                <div>
                    <dt class="text-md font-medium text-gray-500">Total</dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ new Intl.NumberFormat().format(payment.amount) }}
                        {{ payment.currency }}
                    </dd>
                </div>

                <div v-if="payment.status === 'APPROVED'">
                    <dt class="text-md font-medium text-gray-500">
                        Método de pago
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ payment.payment_method_name }}
                    </dd>
                </div>

                <div v-if="payment.status === 'APPROVED'">
                    <dt class="text-md font-medium text-gray-500">
                        Autorización
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ payment.authorization }}
                    </dd>
                </div>

                <div>
                    <dt class="text-md font-medium text-gray-500">
                        Fecha de creación
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ new Date(payment.created_at).toLocaleString() }}
                    </dd>
                </div>

                <div v-if="payment.status === 'APPROVED'">
                    <dt class="text-md font-medium text-gray-500">
                        Fecha de pago
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 font-semibold">
                        {{ new Date(payment.payment_date).toLocaleString() }}
                    </dd>
                </div>
            </dl>
        </div>

        <div class="mt-32 text-center">
            <Link
                class="w-full sm:w-auto items-center p-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                href="/"
            >
                Realizar otro pago
            </Link>
        </div>
    </div>
</template>
