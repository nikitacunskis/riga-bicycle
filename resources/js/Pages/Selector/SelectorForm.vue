<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/vue3'
/** #### CHANGED (EXPLANATION): useForm must come from @inertiajs/vue3 to match your app bootstrap.
 *  Mixing packages (@inertiajs/vue3 vs @inertiajs/vue3) causes the “data arrives but UI doesn’t change” symptom. */

import FrontLayout from '../../Layouts/FrontLayout.vue'

/** #### DELETED (EXPLANATION): you mentioned you removed Ziggy; these imports are unused and can be removed to avoid confusion. */
// import { route } from 'ziggy-js'
// import { Ziggy } from '@/ziggy'

/** Types */
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
const donateBoxOpen = ref(false)
const methodError = ref<string | null>(null)

const form = useForm<{ selected: string[]; method: string }>({
    selected: [],
    method: '',
})

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
const summaryText = computed(() => {
    const methodLabel = methods.value.find(m => m.id === methodId.value)?.label ?? '—'
    return `Izvēlēti filtri: ${selectedIds.value.size}. Metode: ${methodLabel}.`
})

/** Actions */
function submit() {
    if (!canSubmit.value) {
        methodError.value = 'Lūdzu, izvēlies vismaz vienu filtru un metodi.'
        return
    }
    donateBoxOpen.value = true
}
function realSubmit() {
    form.selected = Array.from(selectedIds.value)
    form.method = methodId.value as string

    form.post('/report', {
        preserveState: false,
        replace: true
    })
}
function donate() {
    window.open('https://www.pilsetacilvekiem.lv/ziedot/', '_blank', 'noopener,noreferrer')
    realSubmit()
}

function redirectApiRequest() {
    window.open('/apis/request', '_blank', 'noopener,noreferrer')
}
</script>
<template>
    <FrontLayout title="Atskaites" :breadcrumbs="breadcrumbs">
        <!-- STEP 1 -->
        <section
            v-if="!donateBoxOpen"
            class="relative mx-auto max-w-7xl p-6 sm:p-10 bg-white/60 backdrop-blur-xl ring-1 ring-emerald-100 rounded-3xl shadow-2xl overflow-hidden"
        >
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -inset-x-40 -top-32 h-72 bg-gradient-to-r from-emerald-400/30 via-emerald-200/20 to-emerald-400/30 blur-3xl rotate-6"></div>
            </div>

            <div class="relative">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-emerald-900 text-center tracking-tight">Veloskaitīšanas Atskaites</h1>
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
                                    :aria-pressed="countSelected(years.map(o=>o.id)) === years.length"
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
                                    :aria-pressed="countSelected(objects.map(o=>o.id)) === objects.length"
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
                                    :aria-pressed="countSelected(places.map(o=>o.id)) === places.length"
                                >
                                    Select all
                                </button>
                            </th>

                            <th class="bg-emerald-600/90 text-white font-semibold px-4 py-3 text-center sticky top-0 shadow-md rounded-tr-2xl">
                                <div class="flex items-center justify-center gap-2">
                                    <span>{{ props.fields.options.method.label }}</span>
                                    <span class="inline-block rounded-full bg-emerald-500/30 px-2 py-0.5 text-xs">{{ methodId ? 1 : 0 }}</span>
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
                                                :id="`y-${o.id}`"
                                                :value="o.id"
                                                :checked="isChecked(o.id)"
                                                @change="toggleOption(o.id)"
                                                class="accent-emerald-600 h-5 w-5 rounded-md border-emerald-300 focus:ring-2 focus:ring-emerald-500"
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
                                                :id="`o-${o.id}`"
                                                :value="o.id"
                                                :checked="isChecked(o.id)"
                                                @change="toggleOption(o.id)"
                                                class="accent-emerald-600 h-5 w-5 rounded-md border-emerald-300 focus:ring-2 focus:ring-emerald-500"
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
                                                :id="`p-${o.id}`"
                                                :value="o.id"
                                                :checked="isChecked(o.id)"
                                                @change="toggleOption(o.id)"
                                                class="accent-emerald-600 h-5 w-5 rounded-md border-emerald-300 focus:ring-2 focus:ring-emerald-500"
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
                                                :id="`m-${o.id}`"
                                                :value="o.id"
                                                v-model="methodId"
                                                class="accent-emerald-600 h-4 w-4 border-emerald-300 focus:ring-2 focus:ring-emerald-500"
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
                        :disabled="form.processing"
                        @click="submit"
                        class="px-8 py-3 text-lg font-semibold rounded-2xl bg-emerald-600 text-white shadow-lg hover:bg-emerald-700
                   focus:outline-none focus:ring-4 focus:ring-emerald-300 transition transform hover:-translate-y-0.5 active:translate-y-0
                   disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Rādīt grafiku
                    </button>
                    <button
                        @click="redirectApiRequest"
                        class="mx-2 px-8 py-3 text-lg font-semibold rounded-2xl bg-emerald-600 text-white shadow-lg hover:bg-emerald-700
                   focus:outline-none focus:ring-4 focus:ring-emerald-300 transition transform hover:-translate-y-0.5 active:translate-y-0
                   disabled:opacity-50 disabled:cursor-not-allowed">
                        Vēlies integrēt datus?
                    </button>
                </div>
            </div>
        </section>

        <!-- STEP 2 -->
        <section
            v-else
            class="mx-auto mt-16 max-w-3xl rounded-3xl bg-white/70 backdrop-blur-xl p-8 text-center ring-1 ring-emerald-100 shadow-2xl"
        >
            <div class="flex flex-col items-center gap-6">
                <i class="fa-solid fa-heart text-emerald-500 text-8xl drop-shadow-md" aria-hidden="true"></i>
                <h3 class="text-2xl font-bold text-emerald-900">“Pilsēta cilvēkiem” piedāvā datus <b>bez maksas</b>.</h3>
                <p class="text-emerald-800 max-w-md">
                    Atbalsti projektu ar ziedojumu! Datu lejupielāde turpināsies, pat ja izlasi bez ziedojuma.
                </p>

                <p class="text-sm text-emerald-700/80" aria-live="polite">{{ summaryText }}</p>

                <div class="flex flex-wrap justify-center gap-4 mt-4">
                    <button
                        type="button"
                        @click="donate"
                        class="px-6 py-3 rounded-2xl bg-emerald-600 text-white font-medium shadow-md hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-300"
                    >
                        Ziedot un saņemt datus
                    </button>
                    <button
                        type="button"
                        :disabled="form.processing"
                        @click="realSubmit"
                        class="px-6 py-3 rounded-2xl border border-emerald-300 bg-white text-emerald-700 font-medium hover:bg-emerald-50 focus:ring-4 focus:ring-emerald-200 disabled:opacity-50"
                    >
                        Nē, tikai saņemt datus
                    </button>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>
