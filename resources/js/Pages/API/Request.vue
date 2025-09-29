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
    name: z.string().trim().min(3, 'Enter your full name'),
    phone: z.string().trim().regex(phoneRegex, 'Enter a valid phone number'),
    email: z.email('Enter a valid email'), // Zod >=3.23
    purpose: z.string().trim().min(20, 'Tell us more (min 20 chars)'),
    acceptedTos: mustBeTrue('You must accept the Terms of Service'),
    acceptedPrivacy: mustBeTrue('You must accept the Privacy Policy'),
})

const orgSchemaZ = z.object({
    orgName: z.string().trim().min(2, 'Enter organisation name'),
    regNumber: z.string().trim().min(2, 'Enter registration number'),
    contactName: z.string().trim().min(3, 'Enter contact person full name'),
    phone: z.string().trim().regex(phoneRegex, 'Enter a valid phone number'),
    email: z.email('Enter a valid email'),
    purpose: z.string().trim().min(20, 'Tell us more (min 20 chars)'),
    acceptedTos: mustBeTrue('You must accept the Terms of Service'),
    acceptedPrivacy: mustBeTrue('You must accept the Privacy Policy'),
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
                <h1 class="text-2xl font-semibold">API Access Request</h1>
                <p class="text-sm text-gray-500">Fill out the form to request access.</p>
            </header>

            <!-- Applicant type selector -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Applicant type</label>
                <div class="inline-flex gap-3">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="radio" class="h-4 w-4" name="applicantType" value="private" v-model="applicantType" />
                        <span>Private person</span>
                    </label>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="radio" class="h-4 w-4" name="applicantType" value="org" v-model="applicantType" />
                        <span>Organisation</span>
                    </label>
                </div>
            </div>

            <!-- Form -->
            <Form :validation-schema="validationSchema" validate-on-input @submit="onSubmit" v-slot="{ errors, meta }">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- PRIVATE PERSON FIELDS -->
                    <template v-if="applicantType === 'private'">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-medium mb-1" for="name">Name Surname</label>
                            <Field id="name" name="name" as="input" type="text" class="w-full rounded border px-3 py-2" autocomplete="name" />
                            <ErrorMessage name="name" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="phone">Phone number</label>
                            <Field id="phone" name="phone" as="input" type="tel" class="w-full rounded border px-3 py-2" placeholder="+371..." autocomplete="tel" />
                            <ErrorMessage name="phone" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="email">E-mail</label>
                            <Field id="email" name="email" as="input" type="email" class="w-full rounded border px-3 py-2" autocomplete="email" />
                            <ErrorMessage name="email" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="purpose">Purpose of usage</label>
                            <Field id="purpose" name="purpose" as="textarea" rows="4" class="w-full rounded border px-3 py-2" placeholder="Describe how you'll use the API…" />
                            <p class="text-xs text-gray-500 mt-1">Min 20 characters.</p>
                            <ErrorMessage name="purpose" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <div class="grid grid-rows-2 gap-y-2">
                                <div class="py-2">
                                    <label class="inline-flex items-start gap-3 cursor-pointer">
                                        <Field name="acceptedTos" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                        <span>
                                            I accept the <a href="/tos" target="_blank" rel="noopener" class="underline">Terms of Service</a>.
                                        </span>
                                    </label>
                                    <ErrorMessage name="acceptedTos" class="block text-sm text-red-600" />
                                </div>
                                <div class="py-2">
                                    <label class="inline-flex items-start gap-3 cursor-pointer">
                                        <Field name="acceptedPrivacy" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                        <span>
                                            I accept the <a href="/privacy" target="_blank" rel="noopener" class="underline">Privacy Policy</a>
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
                            <label class="block text-sm font-medium mb-1" for="orgName">Organisation name</label>
                            <Field id="orgName" name="orgName" as="input" type="text" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="orgName" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="regNumber">Registration number</label>
                            <Field id="regNumber" name="regNumber" as="input" type="text" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="regNumber" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="contactName">Contact person (Name Surname)</label>
                            <Field id="contactName" name="contactName" as="input" type="text" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="contactName" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="phone">Phone number</label>
                            <Field id="phone" name="phone" as="input" type="tel" class="w-full rounded border px-3 py-2" placeholder="+371..." />
                            <ErrorMessage name="phone" class="text-sm text-red-600" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1" for="email">E-mail</label>
                            <Field id="email" name="email" as="input" type="email" class="w-full rounded border px-3 py-2" />
                            <ErrorMessage name="email" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1" for="purpose">Purpose of usage</label>
                            <Field id="purpose" name="purpose" as="textarea" rows="4" class="w-full rounded border px-3 py-2" />
                            <p class="text-xs text-gray-500 mt-1">Min 20 characters.</p>
                            <ErrorMessage name="purpose" class="text-sm text-red-600" />
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label class="inline-flex items-start gap-3 cursor-pointer">
                                <Field name="acceptedTos" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                <span>
                  We accept the
                  <a href="/tos" target="_blank" rel="noopener" class="underline">Terms of Service</a>.
                </span>
                            </label>
                            <ErrorMessage name="acceptedTos" class="block text-sm text-red-600" />

                            <label class="inline-flex items-start gap-3 cursor-pointer">
                                <Field name="acceptedPrivacy" type="checkbox" :value="true" :unchecked-value="false" class="mt-1 h-4 w-4" />
                                <span>
                  We accept the
                  <a href="/privacy" target="_blank" rel="noopener" class="underline">Privacy Policy</a>
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
                                {{ applicantType === 'private' ? 'Private person' : 'Organisation' }} •
                                {{ meta.valid ? 'Form is valid' : 'Provided data is not valid' }}
                        </p>
                        <div class="flex gap-3">
                            <button type="button" class="rounded border px-4 py-2" @click="reset(applicantType)">Reset</button>
                            <button type="submit" class="rounded bg-black text-white px-4 py-2 disabled:opacity-50" :disabled="!meta.valid">
                                Submit request
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
                API key issued for <b>{{ success.owner }}</b>. We will contact you via your email <b>{{ success.email }}</b> after approval.
            </div>

            <!-- Error banner -->
            <div
                v-if="errorMsg"
                id="request-error"
                class="rounded-lg border border-red-200 bg-red-50 px-4 py-3"
                role="alert"
                aria-live="assertive"
            >
                <p class="font-medium">Submission failed</p>
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
