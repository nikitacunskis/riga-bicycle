<script setup lang="ts">
import { ref, computed } from 'vue'
import FrontLayout from '../../Layouts/FrontLayout.vue'
import { Form, Field, ErrorMessage } from 'vee-validate'
import { z } from 'zod'
import { toTypedSchema } from '@vee-validate/zod'

// Types

type ApplicantType = 'private' | 'org'

interface PrivatePayload {
    type: 'private'
    name: string
    phone: string
    email: string
    purpose: string
    acceptedTos: true
    acceptedPrivacy: true
}

interface OrgPayload {
    type: 'org'
    orgName: string
    regNumber: string
    contactName: string
    phone: string
    email: string
    purpose: string
    acceptedTos: true
    acceptedPrivacy: true
}

type Payload = PrivatePayload | OrgPayload

const emit = defineEmits<{ (e: 'submit', payload: Payload): void }>()
const applicantType = ref<ApplicantType>('private')
const isSubmitting = ref(false)
const success = ref<{ owner: string; key: string } | null>(null)
const errorMsg = ref<string | null>(null)


// Common validators
const phoneRegex = /^\+?\d[\d\s\-().]{6,}$/
const mustBeTrue = (msg: string) => z.boolean().refine(v => v === true, { message: msg })
const validationSchema = computed(() =>
    applicantType.value === 'private' ? toTypedSchema(privateSchemaZ) : toTypedSchema(orgSchemaZ)
)

const privateSchemaZ = z.object({
    name: z.string().trim().min(3, 'Ievadiet pilnu vārdu un uzvārdu'),
    phone: z.string().trim().regex(phoneRegex, 'Ievadiet derīgu tālruņa numuru'),
    email: z.email('Ievadiet derīgu e-pastu'),
    purpose: z.string().trim().min(20, 'Pastāstiet vairāk (vismaz 20 rakstzīmes)'),
    acceptedTos: mustBeTrue('Jums jāpiekrīt Lietošanas noteikumiem'),
    acceptedPrivacy: mustBeTrue('Jums jāpiekrīt Privātuma politikai'),
})

const orgSchemaZ = z.object({
    orgName: z.string().trim().min(2, 'Ievadiet organizācijas nosaukumu'),
    regNumber: z.string().trim().min(2, 'Ievadiet reģistrācijas numuru'),
    contactName: z.string().trim().min(3, 'Ievadiet kontaktpersonas pilnu vārdu'),
    phone: z.string().trim().regex(phoneRegex, 'Ievadiet derīgu tālruņa numuru'),
    email: z.email('Ievadiet derīgu e-pastu'),
    purpose: z.string().trim().min(20, 'Pastāstiet vairāk (vismaz 20 rakstzīmes)'),
    acceptedTos: mustBeTrue('Jums jāpiekrīt Lietošanas noteikumiem'),
    acceptedPrivacy: mustBeTrue('Jums jāpiekrīt Privātuma politikai'),
})

async function onSubmit(values: Record<string, unknown>) {
    errorMsg.value = null
    success.value = null
    isSubmitting.value = true

    try {
        // Normalize payload
        const payload: Payload =
            applicantType.value === 'private'
                ? {
                    type: 'private',
                    name: String(values.name ?? '').trim(),
                    phone: String(values.phone ?? '').trim(),
                    email: String(values.email ?? '').trim(),
                    purpose: String(values.purpose ?? '').trim(),
                    acceptedTos: true,
                    acceptedPrivacy: true,
                }
                : {
                    type: 'org',
                    orgName: String(values.orgName ?? '').trim(),
                    regNumber: String(values.regNumber ?? '').trim(),
                    contactName: String(values.contactName ?? '').trim(),
                    phone: String(values.phone ?? '').trim(),
                    email: String(values.email ?? '').trim(),
                    purpose: String(values.purpose ?? '').trim(),
                    acceptedTos: true,
                    acceptedPrivacy: true,
                }

        const csrf = (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || ''

        const res = await fetch('/apis/request', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify(payload),
        })

        if (!res.ok) {
            const text = await res.text().catch(() => '')
            throw new Error(text || `Request failed (${res.status})`)
        }

        const json = await res.json()
        success.value = { owner: json.api.owner, email: json.api.email }

        // Optional: auto-copy for convenience
        try {
            await navigator.clipboard.writeText(json.api.key)
        } catch { /* no-op */ }

        // Emit for parent listeners if any
        emit('submit', payload)

        // Soft reset textareas/inputs (keep applicant type)
        reset(applicantType.value)
    } catch (err: any) {
        errorMsg.value = err?.message || 'Unexpected error'
    } finally {
        isSubmitting.value = false
    }
}

function reset(typeToKeep: ApplicantType) {
    const current = applicantType.value
    applicantType.value = typeToKeep === 'private' ? 'org' : 'private'
    requestAnimationFrame(() => (applicantType.value = current))
}
</script>

<template>
    <FrontLayout>
        <div class="px-6 py-6">
            <a class="bg-green-600 border rounded px-4 py-2 text-white" href="/apis/documentation">Dokumentācija</a>
            <header class="mb-6 mt-6">
                <h1 class="text-2xl font-semibold">API piekļuves pieprasījums</h1>
                <p class="text-sm text-gray-500">Aizpildiet formu, lai pieprasītu piekļuvi.</p>
            </header>

            <!-- Applicant type selector -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Pieteikuma veids</label>
                <div class="inline-flex gap-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="radio" class="h-4 w-4" name="applicantType" value="private" v-model="applicantType" />
                        <span>Privātpersona</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="radio" class="h-4 w-4" name="applicantType" value="org" v-model="applicantType" />
                        <span>Organizācija</span>
                    </label>
                </div>
            </div>

            <!-- Form -->
            <Form :validation-schema="validationSchema" validate-on-input @submit="onSubmit" v-slot="{ errors, meta }">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- PRIVATE PERSON FIELDS -->
                    <template v-if="applicantType === 'private'">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium mb-1" for="name">Vārds Uzvārds</label>
                            <Field id="name" name="name" as="input" type="text" class="w-full rounded border px-3 py-2" autocomplete="name" />
                            <ErrorMessage name="name" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="phone">Tālruņa numurs</label>
                            <Field id="phone" name="phone" as="input" type="tel" class="w-full rounded border px-3 py-2" placeholder="+371..." autocomplete="tel" />
                            <ErrorMessage name="phone" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="email">E-pasts</label>
                            <Field id="email" name="email" as="input" type="email" class="w-full rounded border px-3 py-2" autocomplete="email" />
                            <ErrorMessage name="email" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="purpose">Izmantošanas mērķis</label>
                            <Field id="purpose" name="purpose" as="textarea" rows="4" class="w-full rounded border px-3 py-2" placeholder="Aprakstiet, kā izmantosiet API…" />
                            <p class="text-xs text-gray-500 mt-1">Minimāli 20 rakstzīmes.</p>
                            <ErrorMessage name="purpose" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <div class="grid grid-rows-2 gap-y-2">
                                <div class="py-2">
                                    <label class="inline-flex items-start gap-3 cursor-pointer">
                                        <Field name="acceptedTos" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                        <span>
                                            Es piekrītu <a href="/tos" target="_blank" rel="noopener" class="underline">Lietošanas noteikumiem</a>.
                                        </span>
                                    </label>
                                    <ErrorMessage name="acceptedTos" class="block text-sm text-red-600" />
                                </div>
                                <div class="py-2">
                                    <label class="inline-flex items-start gap-3 cursor-pointer">
                                        <Field name="acceptedPrivacy" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                        <span>
                                            Es piekrītu <a href="/privacy" target="_blank" rel="noopener" class="underline">Privātuma politikai</a>
                                        </span>
                                    </label>
                                    <ErrorMessage name="acceptedPrivacy" class="block text-sm text-red-600" />
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- ORGANISATION FIELDS -->
                    <template v-else>
                        <div>
                            <label class="block text-sm font-medium mb-1" for="orgName">Organizācijas nosaukums</label>
                            <Field id="orgName" name="orgName" as="input" type="text" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="orgName" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="regNumber">Reģistrācijas numurs</label>
                            <Field id="regNumber" name="regNumber" as="input" type="text" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="regNumber" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="contactName">Kontaktpersona (Vārds Uzvārds)</label>
                            <Field id="contactName" name="contactName" as="input" type="text" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="contactName" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="phone">Tālruņa numurs</label>
                            <Field id="phone" name="phone" as="input" type="tel" class="w-full rounded border px-3 py-2" placeholder="+371..." />
                            <ErrorMessage name="phone" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="email">E-pasts</label>
                            <Field id="email" name="email" as="input" type="email" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="email" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="purpose">Izmantošanas mērķis</label>
                            <Field id="purpose" name="purpose" as="textarea" rows="4" class="w-full rounded border px-3 py-2" />
                            <p class="text-xs text-gray-500 mt-1">Minimāli 20 rakstzīmes.</p>
                            <ErrorMessage name="purpose" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="inline-flex items-start gap-3 cursor-pointer">
                                <Field name="acceptedTos" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                <span>
                                    Mēs piekrītam <a href="/tos" target="_blank" rel="noopener" class="underline">Lietošanas noteikumiem</a>.
                                </span>
                            </label>
                            <ErrorMessage name="acceptedTos" class="block text-sm text-red-600" />

                            <label class="inline-flex items-start gap-3 cursor-pointer">
                                <Field name="acceptedPrivacy" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                <span>
                                    Mēs piekrītam <a href="/privacy" target="_blank" rel="noopener" class="underline">Privātuma politikai</a>
                                    (<span class="italic">Datenschutz</span>).
                                </span>
                            </label>
                            <ErrorMessage name="acceptedPrivacy" class="block text-sm text-red-600" />
                        </div>
                    </template>
                </div>

                <!-- Sticky submit bar -->
                <div class="sticky bottom-0 border-t mt-8 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-sm text-gray-500">
                            {{ applicantType === 'private' ? 'Privātpersona' : 'Organizācija' }} •
                            {{ meta.valid ? 'Forma ir derīga' : 'Dati nav derīgi' }}
                        </p>
                        <div class="flex gap-3">
                            <button type="button" class="rounded border px-4 py-2" @click="reset(applicantType)">Atiestatīt</button>
                            <button type="submit" class="rounded bg-black text-white px-4 py-2 disabled:opacity-50" :disabled="!meta.valid">
                                Iesniegt pieprasījumu
                            </button>
                        </div>
                    </div>
                </div>
            </Form>

            <!-- Success banner -->
            <div
                v-if="success"
                id="request-success"
                class="rounded-lg border border-green-200 bg-green-50 px-4 py-3"
                role="status"
                aria-live="polite"
            >
                API atslēga izsniegta <b>{{ success.owner }}</b>. Mēs sazināsimies ar jums pa e-pastu <b>{{ success.email }}</b> pēc apstiprināšanas.
            </div>

            <!-- Error banner -->
            <div
                v-if="errorMsg"
                id="request-error"
                class="rounded-lg border border-red-200 bg-red-50 px-4 py-3"
                role="alert"
                aria-live="assertive"
            >
                <p class="font-medium">Iesniegšana neizdevās</p>
                <p class="mt-1 text-sm text-red-700">{{ errorMsg }}</p>
            </div>
        </div>
    </FrontLayout>
</template>

<style scoped>
/* Minimal, neutral styling. Assumes Tailwind for utilities; these are just safety fallbacks. */
.border { border: 1px solid #e5e7eb; }
.rounded { border-radius: 0.5rem; }
</style>
