<script setup>
const props = defineProps({
    label: String,
    value: [String, Number],
    delta: Number, // +/- vs previous period
    series: { type: Array, default: () => [] }, // for sparkline
})
</script>

<template>
    <div class="rounded-2xl shadow-sm border bg-white p-4 sm:p-6 hover:shadow transition dark:bg-zinc-900 dark:border-zinc-800">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ label }}</div>
                <div class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ value }}</div>
            </div>
            <div
                :class="[
          'px-2 py-1 rounded text-xs font-medium',
          delta >= 0 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-900/20 dark:text-rose-300'
        ]"
                aria-label="Delta"
            >
                {{ delta >= 0 ? '▲' : '▼' }} {{ Math.abs(delta) }}%
            </div>
        </div>

        <!-- Sparkline (lightweight inline SVG) -->
        <svg v-if="series && series.length" class="w-full mt-4" :viewBox="`0 0 ${series.length-1} 10`" preserveAspectRatio="none" aria-hidden="true">
            <polyline :points="series.map((v,i)=>`${i},${10 - (v - Math.min(...series)) / (Math.max(...series)-Math.min(...series) || 1) * 10}`).join(' ')"
                      fill="none" stroke="currentColor" stroke-width="0.6" class="text-gray-400 dark:text-gray-600" />
        </svg>
    </div>
</template>
