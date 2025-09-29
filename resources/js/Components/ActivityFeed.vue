<script setup>
import dayjs from 'dayjs'
const props = defineProps({ items: { type: Array, default: () => [] } })
</script>

<template>
    <div class="rounded-2xl border bg-white dark:bg-zinc-900 dark:border-zinc-800">
        <div class="px-5 py-4 border-b dark:border-zinc-800">
            <h3 class="font-semibold text-gray-900 dark:text-white">Live Activity</h3>
        </div>
        <ul class="divide-y dark:divide-zinc-800">
            <li v-for="(i,idx) in items" :key="idx" class="px-5 py-3 hover:bg-gray-50 dark:hover:bg-zinc-800/60 transition">
                <div class="text-sm text-gray-800 dark:text-gray-200">{{ i.text }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ dayjs(i.ts).fromNow?.() || i.ts }}</div>
            </li>
            <li v-if="!items.length" class="px-5 py-6 text-sm text-gray-500 dark:text-gray-400">No activity yet.</li>
        </ul>
    </div>
</template>
