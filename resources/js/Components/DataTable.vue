<script setup>
const props = defineProps({
    columns: { type: Array, required: true }, // [{key:'name',label:'Name'}]
    rows: { type: Array, default: () => [] },
})
const emit = defineEmits(['row-click'])
</script>

<template>
    <div class="rounded-2xl border bg-white overflow-hidden dark:bg-zinc-900 dark:border-zinc-800">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-800">
            <thead class="bg-gray-50 dark:bg-zinc-800/60">
            <tr>
                <th v-for="c in columns" :key="c.key" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider dark:text-gray-400">
                    {{ c.label }}
                </th>
            </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-zinc-800">
            <tr v-for="r in rows" :key="r.id" class="hover:bg-gray-50 dark:hover:bg-zinc-800/60 cursor-pointer" @click="$emit('row-click', r)">
                <td v-for="c in columns" :key="c.key" class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">
                    <slot :name="`cell:${c.key}`" :row="r">{{ r[c.key] }}</slot>
                </td>
            </tr>
            <tr v-if="!rows.length">
                <td :colspan="columns.length" class="px-4 py-6 text-sm text-gray-500 dark:text-gray-400">No data.</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
