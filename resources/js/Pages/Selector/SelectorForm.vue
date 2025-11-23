<script setup lang="ts">
import { ref, computed } from 'vue'
import FrontLayout from '../../Layouts/FrontLayout.vue'

type Item = { id: string; label: string }
type Group = { label: string; options: Item[] }
type Fields = {
    options: {
        years: Group
        objects: Group
        places: Group
        method: Group
    }
}

const props = defineProps<{ fields: Fields }>()

/** State */
const selectedIds = ref<Set<string>>(new Set())
const methodId = ref<string | null>(null)
const methodError = ref<string | null>(null)

/** Helpers */
const years = computed(() => props.fields?.options?.years?.options ?? [])
const objects = computed(() => props.fields?.options?.objects?.options ?? [])
const places = computed(() => props.fields?.options?.places?.options ?? [])
const methods = computed(() => props.fields?.options?.method?.options ?? [])

function isChecked(id: string) { return selectedIds.value.has(id) }
function toggleOption(id: string) {
    selectedIds.value.has(id) ? selectedIds.value.delete(id) : selectedIds.value.add(id)
    if (selectedIds.value.size > 0 && methodId.value) methodError.value = null
}
function toggleGroup(ids: string[]) {
    const allSelected = ids.every(id => selectedIds.value.has(id))
    if (allSelected) ids.forEach(id => selectedIds.value.delete(id))
    else ids.forEach(id => selectedIds.value.add(id))
    if (selectedIds.value.size > 0 && methodId.value) methodError.value = null
}
const countSelected = (ids: string[]) => ids.reduce((n, id) => n + (selectedIds.value.has(id) ? 1 : 0), 0)

const canSubmit = computed(() => selectedIds.value.size > 0 && !!methodId.value)

/** ACTION: DIRECT GET REDIRECT */
function submit() {
    if (!canSubmit.value) {
        methodError.value = 'Lūdzu, izvēlies vismaz vienu filtru un metodi.'
        return
    }

    const params = new URLSearchParams()
    ;[...selectedIds.value].forEach(v => params.append('selected[]', v))
    params.append('method', methodId.value!)

    window.location.href = `/report-result?${params.toString()}`
}
</script>

<template>
    <FrontLayout title="Atskaites" :breadcrumbs="[{ text: 'Atskaites', href: '/report' }]">

        <section
            class="relative mx-auto max-w-7xl p-6 sm:p-10 bg-white/60 backdrop-blur-xl ring-1 ring-emerald-100 rounded-3xl shadow-2xl overflow-hidden"
        >
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -inset-x-40 -top-32 h-72 bg-gradient-to-r from-emerald-400/30 via-emerald-200/20 to-emerald-400/30 blur-3xl rotate-6"></div>
            </div>

            <div class="relative">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-900 text-center tracking-tight">
                    Veloskaitīšanas Atskaites
                </h1>
                <p class="mt-3 text-center text-emerald-800/80 max-w-2xl mx-auto">
                    Izvēlies gadus, punktus un metodi, lai redzētu detalizētus grafikus par Rīgas velosatiksmi.
                </p>

                <div class="mt-10 overflow-x-auto">
                    <table class="w-full border-separate border-spacing-0 text-left text-sm">
                        <thead>
                        <tr>
                            <th class="bg-emerald-600/90 text-white font-semibold px-4 py-3 text-center sticky top-0 shadow-md rounded-tl-2xl">
                                <div class="flex items-center justify-center gap-2">
                                    <span>{{ props.fields.options.years.label }}</span>
                                    <span class="inline-block rounded-full bg-emerald-500/30 px-2 py-0.5 text-xs">
                                      {{ countSelected(years.map(o=>o.id)) }}
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    class="mt-1 text-xs underline decoration-emerald-200 hover:text-emerald-100"
                                    @click="toggleGroup(years.map(o=>o.id))"
                                >
                                    Select all
                                </button>
                            </th>

                            <th class="bg-emerald-600/90 text-white font-semibold px-4 py-3 text-center sticky top-0 shadow-md">
                                <div class="flex items-center justify-center gap-2">
                                    <span>{{ props.fields.options.objects.label }}</span>
                                    <span class="inline-block rounded-full bg-emerald-500/30 px-2 py-0.5 text-xs">
                                      {{ countSelected(objects.map(o=>o.id)) }}
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    class="mt-1 text-xs underline decoration-emerald-200 hover:text-emerald-100"
                                    @click="toggleGroup(objects.map(o=>o.id))"
                                >
                                    Select all
                                </button>
                            </th>

                            <th class="bg-emerald-600/90 text-white font-semibold px-4 py-3 text-center sticky top-0 shadow-md">
                                <div class="flex items-center justify-center gap-2">
                                    <span>{{ props.fields.options.places.label }}</span>
                                    <span class="inline-block rounded-full bg-emerald-500/30 px-2 py-0.5 text-xs">
                                      {{ countSelected(places.map(o=>o.id)) }}
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    class="mt-1 text-xs underline decoration-emerald-200 hover:text-emerald-100"
                                    @click="toggleGroup(places.map(o=>o.id))"
                                >
                                    Select all
                                </button>
                            </th>

                            <th class="bg-emerald-600/90 text-white font-semibold px-4 py-3 text-center sticky top-0 shadow-md rounded-tr-2xl">
                                <div class="flex items-center justify-center gap-2">
                                    <span>{{ props.fields.options.method.label }}</span>
                                    <span class="inline-block rounded-full bg-emerald-500/30 px-2 py-0.5 text-xs">
                                      {{ methodId ? 1 : 0 }}
                                    </span>
                                </div>
                            </th>
                        </tr>
                        </thead>

                        <tbody class="bg-white/70 backdrop-blur">
                        <tr class="[&>td]:align-top [&>td]:p-5 [&>td:not(:last-child)]:border-r [&>td:not(:last-child)]:border-emerald-100">

                            <!-- Years -->
                            <td>
                                <ul class="space-y-2">
                                    <li v-for="o in years" :key="o.id">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input
                                                type="checkbox"
                                                :checked="isChecked(o.id)"
                                                @change="toggleOption(o.id)"
                                                class="accent-emerald-600 h-5 w-5 rounded-md border-emerald-300"
                                            />
                                            <span class="group-hover:text-emerald-700">{{ o.label }}</span>
                                        </label>
                                    </li>
                                </ul>
                            </td>

                            <!-- Objects -->
                            <td>
                                <ul class="space-y-2">
                                    <li v-for="o in objects" :key="o.id">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input
                                                type="checkbox"
                                                :checked="isChecked(o.id)"
                                                @change="toggleOption(o.id)"
                                                class="accent-emerald-600 h-5 w-5 rounded-md border-emerald-300"
                                            />
                                            <span class="group-hover:text-emerald-700">{{ o.label }}</span>
                                        </label>
                                    </li>
                                </ul>
                            </td>

                            <!-- Places -->
                            <td>
                                <ul class="space-y-2">
                                    <li v-for="o in places" :key="o.id">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input
                                                type="checkbox"
                                                :checked="isChecked(o.id)"
                                                @change="toggleOption(o.id)"
                                                class="accent-emerald-600 h-5 w-5 rounded-md border-emerald-300"
                                            />
                                            <span class="group-hover:text-emerald-700">{{ o.label }}</span>
                                        </label>
                                    </li>
                                </ul>
                            </td>

                            <!-- Method (radios) -->
                            <td>
                                <ul class="space-y-2">
                                    <li v-for="o in methods" :key="o.id">
                                        <label class="flex items-center gap-2 cursor-pointer group">
                                            <input
                                                type="radio"
                                                name="method"
                                                v-model="methodId"
                                                :value="o.id"
                                                class="accent-emerald-600 h-4 w-4 border-emerald-300"
                                                @change="methodError = null"
                                            />
                                            <span class="group-hover:text-emerald-700">{{ o.label }}</span>
                                        </label>
                                    </li>
                                </ul>
                                <p v-if="methodError" class="mt-3 text-sm text-rose-600">{{ methodError }}</p>
                            </td>

                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-10 flex justify-center">
                    <button
                        @click="submit"
                        class="px-8 py-3 text-lg font-semibold rounded-2xl bg-emerald-600 text-white shadow-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300"
                    >
                        Rādīt grafiku
                    </button>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>
