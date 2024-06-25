<script setup>
import { useForm } from '@inertiajs/vue3';

defineProps({
    messageError: String,
});

const form = useForm({
    name: '',
    lastName: '',
    email: '',
    documentType: 'CC',
    documentNumber: '',
    phone: '',
    currency: 'COP',
    amount: 0,
});
</script>

<template>
    <div
        class="max-w-7xl bg-white w-full m-auto rounded-xl p-10 shadow-md mt-10"
    >
        <form
            class="grid sm:grid-cols-2 gap-y-5 gap-x-20"
            @submit.prevent="form.post('/site1')"
        >
            <div>
                <label class="font-medium text-md text-gray-700" for="name">
                    <span>Nombre</span>
                </label>

                <input
                    id="name"
                    v-model="form.name"
                    class="rounded-md shadow-sm p-1.5 border-2 border-gray-300 focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="name"
                    required
                />

                <p v-show="form.errors.name" class="text-sm text-red-600 mt-2">
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <label class="font-medium text-md text-gray-700" for="lastName">
                    <span>Apellido</span>
                </label>

                <input
                    id="lastName"
                    v-model="form.lastName"
                    class="rounded-md shadow-sm p-1.5 border-2 border-gray-300 focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="lastName"
                    required
                />

                <p
                    v-show="form.errors.lastName"
                    class="text-sm text-red-600 mt-2"
                >
                    {{ form.errors.lastName }}
                </p>
            </div>

            <div>
                <label class="font-medium text-md text-gray-700" for="email">
                    <span>Email</span>
                </label>

                <input
                    id="email"
                    v-model="form.email"
                    class="rounded-md shadow-sm p-1.5 border-2 border-gray-300 focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="email"
                    required
                />

                <p v-show="form.errors.email" class="text-sm text-red-600 mt-2">
                    {{ form.errors.email }}
                </p>
            </div>

            <div>
                <label
                    class="font-medium text-md text-gray-700"
                    for="documentType"
                >
                    <span>Tipo de documento</span>
                </label>

                <select
                    id="documentType"
                    v-model="form.documentType"
                    class="rounded-md shadow-sm p-2 border-2 border-gray-300 bg-white focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="documentType"
                    required
                >
                    <option value="CC">Cédula de ciudadanía</option>
                    <option value="PPN">Pasaporte</option>
                </select>

                <p
                    v-show="form.errors.documentType"
                    class="text-sm text-red-600 mt-2"
                >
                    {{ form.errors.documentType }}
                </p>
            </div>

            <div>
                <label
                    class="font-medium text-md text-gray-700"
                    for="documentNumber"
                >
                    <span>Número de documento</span>
                </label>

                <input
                    id="documentNumber"
                    v-model="form.documentNumber"
                    class="rounded-md shadow-sm p-1.5 border-2 border-gray-300 focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="documentNumber"
                    required
                />

                <p
                    v-show="form.errors.documentNumber"
                    class="text-sm text-red-600 mt-2"
                >
                    {{ form.errors.documentNumber }}
                </p>
            </div>

            <div>
                <label class="font-medium text-md text-gray-700" for="phone">
                    <span>Teléfono</span>
                </label>

                <input
                    id="phone"
                    v-model="form.phone"
                    class="rounded-md shadow-sm p-1.5 border-2 border-gray-300 focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="phone"
                    required
                />

                <p v-show="form.errors.phone" class="text-sm text-red-600 mt-2">
                    {{ form.errors.phone }}
                </p>
            </div>

            <div>
                <label class="font-medium text-md text-gray-700" for="currency">
                    <span>Moneda</span>
                </label>

                <select
                    id="currency"
                    v-model="form.currency"
                    class="rounded-md shadow-sm p-2 border-2 border-gray-300 bg-white focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="currency"
                    required
                >
                    <option value="COP">COP</option>
                    <option value="USD">USD</option>
                </select>

                <p
                    v-show="form.errors.currency"
                    class="text-sm text-red-600 mt-2"
                >
                    {{ form.errors.currency }}
                </p>
            </div>

            <div>
                <label class="font-medium text-md text-gray-700" for="amount">
                    <span>Valor</span>
                </label>

                <input
                    id="amount"
                    v-model="form.amount"
                    class="rounded-md shadow-sm p-1.5 border-2 border-gray-300 focus:outline-none focus:border-gray-800 focus:ring-1 w-full"
                    name="amount"
                    required
                    type="number"
                />

                <p
                    v-show="form.errors.amount"
                    class="text-sm text-red-600 mt-2"
                >
                    {{ form.errors.amount }}
                </p>
            </div>

            <div class="sm:col-span-2 mt-5">
                <button
                    class="w-full sm:w-auto items-center p-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Pagar
                </button>

                <span
                    v-if="$page.props.errors[0]"
                    class="mt-4 block text-sm text-red-600"
                >
                    {{ $page.props.errors[0] }}
                </span>
            </div>
        </form>
    </div>
</template>
