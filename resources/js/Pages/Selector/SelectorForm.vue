<script setup>
import { ref, computed } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import PrimaryButton from '../../Components/PrimaryButton.vue'
import FrontLayout from '../../Layouts/FrontLayout.vue'

const props = defineProps({
    fields: Object, // { fields: [...], options: { years:{options:[]}, ... } }
})

/* ---- reactive form state ---- */
const selected = ref([]) // holds selected ids
const donateBoxOpen = ref(false)

const form = useForm({
    selected: [],
})

function toggleOption(id) {
    if (selected.value.includes(id)) {
        selected.value = selected.value.filter(x => x !== id)
    } else {
        selected.value.push(id)
    }
}

function toggleGroup(ids) {
    const allSelected = ids.every(id => selected.value.includes(id))
    if (allSelected) {
        selected.value = selected.value.filter(id => !ids.includes(id))
    } else {
        selected.value = [...new Set([...selected.value, ...ids])]
    }
}

function submit() {
    donateBoxOpen.value = true
}

function realSubmit() {
    form.selected = selected.value
    form.post(route('page.report.post'))
}

function donate() {
    window.open('https://www.pilsetacilvekiem.lv/ziedot/', '_blank')
    realSubmit()
}

const breadcrumbs = [{ text: 'Atskaites', href: '/report' }]
</script>

<template>
    <FrontLayout title="Atskaites" :breadcrumbs="breadcrumbs">
        <!-- Selection Table -->
        <section v-if="!donateBoxOpen" class="overflow-x-auto bg-white">
            <table class="w-full text-sm text-left border border-gray-200">
                <thead class="bg-green-50 text-green-800">
                <tr>
                    <th
                        v-for="(group, key) in fields.options"
                        :key="key"
                        class="border border-gray-200 px-4 py-3 text-center"
                    >
                        <span class="font-semibold">{{ group.label }}</span>
                        <br />
                        <button
                            type="button"
                            class="text-green-600 hover:underline text-xs"
                            @click="toggleGroup(group.options.map(o => o.id))"
                        >
                            Select all
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <!-- Years -->
                    <td class="align-top border p-4">
                        <ul class="space-y-1">
                            <li v-for="o in fields.options.years.options" :key="o.id">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        :value="o.id"
                                        :checked="selected.includes(o.id)"
                                        @change="toggleOption(o.id)"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    />
                                    {{ o.label }}
                                </label>
                            </li>
                        </ul>
                    </td>

                    <!-- Objects -->
                    <td class="align-top border p-4">
                        <ul class="space-y-1">
                            <li v-for="o in fields.options.objects.options" :key="o.id">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        :value="o.id"
                                        :checked="selected.includes(o.id)"
                                        @change="toggleOption(o.id)"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    />
                                    {{ o.label }}
                                </label>
                            </li>
                        </ul>
                    </td>

                    <!-- Places -->
                    <td class="align-top border p-4">
                        <ul class="space-y-1">
                            <li v-for="o in fields.options.places.options" :key="o.id">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        :value="o.id"
                                        :checked="selected.includes(o.id)"
                                        @change="toggleOption(o.id)"
                                        class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                                    />
                                    {{ o.label }}
                                </label>
                            </li>
                        </ul>
                    </td>

                    <!-- Method (radio) -->
                    <td class="align-top border p-4">
                        <ul class="space-y-1">
                            <li v-for="o in fields.options.method.options" :key="o.id">
                                <label class="flex items-center gap-2">
                                    <input
                                        type="radio"
                                        name="method"
                                        :value="o.id"
                                        v-model="selected"
                                        class="border-gray-300 text-green-600 focus:ring-green-500"
                                    />
                                    {{ o.label }}
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="mt-6 flex justify-center">
                <PrimaryButton :disabled="form.processing" @click="submit">
                    Rādīt grafiku
                </PrimaryButton>
            </div>
        </section>

        <!-- Donation Dialog -->
        <section
            v-else
            class="mt-10 max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6"
        >
            <div class="flex flex-col md:flex-row gap-6 items-center">
                <i class="fa-solid fa-heart text-red-500 text-8xl"></i>
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">
                        “Pilsēta cilvēkiem” piedāvā datus <b>bez maksas</b>. Atbalstiet projektu ar ziedojumu?
                    </h3>
                    <p class="text-gray-600 text-sm mb-4">
                        Pieprasītie dati paliks tepat. Mēs atvērsim ziedojumu lapu jaunā cilnē.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <button
                            type="button"
                            @click="donate"
                            class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700"
                        >
                            Ziedot un saņemt datus
                        </button>
                        <button
                            type="button"
                            @click="realSubmit"
                            class="border border-gray-300 px-5 py-2 rounded-lg hover:bg-gray-100"
                        >
                            Nē, tikai saņemt datus
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </FrontLayout>
</template>
