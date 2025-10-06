// File: resources/js/Components/DashboardTable.vue
<script setup>
import { ref, computed, watch, onMounted } from 'vue'

/**
 * Generic, headless-ish data table with built‑in pagination.
 * Parent passes ONLY data + column config; this component handles paging UI.
 *
 * Props
 *  - items: Array<object>
 *  - columns: Array<{ key: string, label?: string, class?: string, thClass?: string, format?: (value:any, row:any)=>any }>
 *  - pageSizeOptions: number[]
 *  - defaultPageSize: number
 *  - tableId: string (for scrollIntoView when page changes)
 *
 * Slots
 *  - actions: #actions="{ row }" → renders actions cell per row
 */

const props = defineProps({
    items: { type: Array, default: () => [] },
    columns: { type: Array, default: () => [] },
    pageSizeOptions: { type: Array, default: () => [5, 10, 20, 50] },
    defaultPageSize: { type: Number, default: 10 },
    tableId: { type: String, default: 'dashboard-table' },
    emptyText: { type: String, default: 'No records yet.' },
})

const pageSize = ref(props.defaultPageSize)
const currentPage = ref(1)

const totalItems = computed(() => props.items.length)
const totalPages = computed(() => Math.max(1, Math.ceil(totalItems.value / pageSize.value)))

const pagedRows = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value
    return props.items.slice(start, start + pageSize.value)
})

function goToPage(p) {
    const safe = Math.min(Math.max(1, p), totalPages.value)
    currentPage.value = safe
    // Smooth scroll to table top for better UX
    document?.getElementById(props.tableId)?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}
function changePageSize(size) {
    pageSize.value = size
    currentPage.value = 1
}

// If data shrinks (e.g., after delete), keep pagination sane
watch(() => props.items.length, () => {
    const maxStart = (totalPages.value - 1) * pageSize.value
    if ((currentPage.value - 1) * pageSize.value > maxStart) {
        goToPage(totalPages.value)
    }
})
</script>

<template>
    <div>
        <div class="overflow-x-auto shadow-sm">
            <table :id="tableId" class="min-w-full table-auto text-left">
                <thead class="sticky top-0 backdrop-blur z-10">
                    <tr>
                        <th v-for="col in columns" :key="col.key" :class="['px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-white', col.thClass]">
                            {{ col.label ?? col.key }}
                        </th>
                        <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500 text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:nth-child(odd)]:bg-gray-50/40">
                <template v-if="pagedRows.length">
                    <tr v-for="row in pagedRows" :key="row.id">
                        <td v-for="col in columns" :key="col.key" :class="['px-4 py-3 text-sm text-white', col.class]">
                            <template v-if="col.format">
                                {{ col.format(row[col.key], row) }}
                            </template>
                            <template v-else>
                                {{ row[col.key] ?? '—' }}
                            </template>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <slot name="actions" :row="row" />
                        </td>
                    </tr>
                </template>
                <tr v-else>
                    <td :colspan="columns.length + 1" class="px-4 py-8 text-center text-sm text-gray-500">{{ emptyText }}</td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination footer -->
        <div class="mt-4 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Page <span class="font-medium text-gray-700">{{ currentPage }}</span>
                of <span class="font-medium text-gray-700">{{ totalPages }}</span>
            </div>
            <div class="flex items-center gap-3">
                <label class="text-sm text-gray-500">Rows per page:</label>
                <select class="border border-gray-300 px-2 py-1 text-sm shadow-sm bg-black" :value="pageSize" @change="e => changePageSize(Number(e.target.value))">
                    <option v-for="opt in pageSizeOptions" :key="opt" :value="opt">{{ opt }}</option>
                </select>
                <nav class="inline-flex items-center gap-1" aria-label="Pagination">
                    <button class="border border-gray-300 px-3 py-2 text-sm shadow-sm" :disabled="currentPage===1" @click="goToPage(currentPage-1)">‹ Prev</button>
                    <button class="border border-gray-300 px-3 py-2 text-sm shadow-sm" :disabled="currentPage===totalPages" @click="goToPage(currentPage+1)">Next ›</button>
                </nav>
            </div>
        </div>
    </div>
</template>
