<template>
    <div class="overflow-x-auto max-w-full">
        <table class="min-w-full bg-white">
            <thead>
                <tr>
                    <th
                        v-for="column in columns"
                        :key="column.key"
                        class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-center text-sm font-medium uppercase tracking-wider"
                    >
                        {{ column.label }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows" :key="row.id" class="bg-white text-center">
                    <td
                        v-for="column in columns"
                        :key="column.key"
                        class="px-6 py-4 whitespace-nowrap text-sm"
                    >
                        <slot :name="`cell-${column.key}`" :row="row">
                            {{ row[column.key] }}
                        </slot>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
interface Column {
    key: string;
    label: string;
}

interface Row {
    id: number;
    [key: string]: any;
}

defineProps<{
    columns: Column[];
    rows: Row[];
}>();
</script>
