<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import BodySection from '@/Components/BodySection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DashboardTable from '@/Components/DashboardTable.vue'

const props = defineProps({ apis: { type: Array, default: () => [] } })

// Local list so we can append without full reload
const items = ref([...props.apis])

// --- Create form state & logic (unchanged) ---
const owner = ref('')
const keyInput = ref('')
const validUntil = ref('')
const phone = ref('')
const email = ref('')
const type = ref('personal')
const regNumber = ref('')
const tosAccepted = ref(false)
const privacyAccepted = ref(false)
const isSubmitting = ref(false)
const justCreatedKey = ref(null)
const showAdvanced = ref(false)

function emailLooksValid(v) { if (!v) return true; return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) }

async function submitCreate() {
    if (!owner.value.trim()) return alert('Owner is required.')
    if (!tosAccepted.value || !privacyAccepted.value) return alert('You must accept Terms of Service and Privacy Policy.')
    if (!emailLooksValid(email.value.trim())) return alert('Invalid email format.')
    if (type.value === 'organisation' && !regNumber.value.trim()) return alert('Registration number is required for organisation type.')

    isSubmitting.value = true
    try {
        const payload = {
            owner: owner.value.trim(),
            type: type.value,
            tos_accepted: true,
            privacy_accepted: true,
        }
        if (keyInput.value.trim()) payload.key = keyInput.value.trim()
        if (validUntil.value) payload.valid_until = validUntil.value
        if (phone.value.trim()) payload.phone = phone.value.trim()
        if (email.value.trim()) payload.email = email.value.trim()
        if (type.value === 'organisation' && regNumber.value.trim()) payload.reg_number = regNumber.value.trim()

        const { data } = await window.axios.post('http://localhost:8080/dashboard/apis/store', payload, { headers: { Accept: 'application/json' } })
        items.value.unshift(data.api)
        justCreatedKey.value = data.api.key

        owner.value = ''
        keyInput.value = ''
        validUntil.value = ''
        phone.value = ''
        email.value = ''
        type.value = 'personal'
        regNumber.value = ''
        tosAccepted.value = false
        privacyAccepted.value = false
        showAdvanced.value = false
    } catch (e) {
        console.error(e)
        alert('Failed to create API key.')
    } finally {
        isSubmitting.value = false
    }
}

function copyKeyOnce() {
    if (!justCreatedKey.value) return
    navigator.clipboard?.writeText(justCreatedKey.value)
    justCreatedKey.value = null
}

// Delete keeps data in sync; the table adjusts pagination internally
async function deleteApi(id) {
    if (!confirm('Delete this API key?')) return
    try {
        await window.axios.delete(`/dashboard/apis/${id}`, { headers: { Accept: 'application/json' } })
        items.value = items.value.filter(a => a.id !== id)
    } catch (e) {
        console.error(e)
        alert('Delete failed.')
    }
}

// Column config for DashboardTable
const columns = [
    { key: 'id', label: 'ID' },
    { key: 'key', label: 'Key' },
    { key: 'owner', label: 'Owner' },
    { key: 'type', label: 'Type', format: v => v || '—' },
    { key: 'reg_number', label: 'Reg #' , format: v => v || '—' },
    { key: 'email', label: 'Email', format: v => v || '—' },
    { key: 'phone', label: 'Phone', format: v => v || '—' },
    { key: 'valid_until', label: 'Valid Until', format: v => v ? new Date(v).toLocaleDateString() : '—' },
]
</script>

<template>
    <AdminLayout title="API keys">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">API keys</h2>
        </template>

        <BodySection>
            <!-- Creation form (unchanged UI) -->
            <div class="mb-4 border border-gray-200 p-4 bg-white/60 dark:bg-gray-900/40">
                <form @submit.prevent="submitCreate" class="flex flex-col gap-4">
                    <!-- Row 1: Owner, Type, Reg # -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Owner<span class="text-red-500">*</span></label>
                            <input v-model="owner" required placeholder="Person or organization" class="border-gray-300 text-sm shadow-sm focus:border-black p-2 focus:ring-black bg-black" />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Type</label>
                            <select v-model="type" class="border-gray-300 text-sm shadow-sm focus:border-black p-2 focus:ring-black bg-black">
                                <option value="personal">personal</option>
                                <option value="organisation">organisation</option>
                            </select>
                        </div>
                        <div v-if="type==='organisation'" class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Registration Number</label>
                            <input v-model="regNumber" placeholder="e.g. LV12345678901" class="border-gray-300 text-sm shadow-sm focus:border-black p-2 focus:ring-black bg-black" />
                        </div>
                    </div>

                    <!-- Row 2: Email, Phone, Advanced toggle -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Email</label>
                            <input type="email" v-model="email" placeholder="name@example.com" class="border-gray-300 text-sm shadow-sm focus:border-black p-2 focus:ring-black bg-black" />
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Phone</label>
                            <input type="tel" v-model="phone" placeholder="+371 20000000" class="border-gray-300 text-sm shadow-sm focus:border-black p-2 focus:ring-black bg-black" />
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="text-sm underline text-gray-600 hover:text-gray-900" @click="showAdvanced = !showAdvanced" :aria-expanded="showAdvanced.toString()">
                                {{ showAdvanced ? 'Hide advanced' : 'Advanced' }}
                            </button>
                        </div>
                    </div>

                    <!-- Advanced -->
                    <div v-if="showAdvanced" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Custom Key (optional)</label>
                            <input v-model="keyInput" placeholder="Leave empty to auto-generate" class="border-gray-300 text-sm shadow-sm focus:border-black p-2 focus:ring-black bg-black font-mono" />
                            <p class="text-[11px] text-gray-500 mt-1">If empty, the server will generate a secure key.</p>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-xs text-gray-500 mb-1">Valid Until (optional)</label>
                            <input type="date" v-model="validUntil" :min="new Date().toISOString().slice(0,10)" class="border-gray-300 text-sm shadow-sm focus:border-black focus:ring-black bg-black p-2" />
                            <p class="text-[11px] text-gray-500 mt-1">Default: today + 1 year.</p>
                        </div>
                    </div>

                    <!-- Consents -->
                    <div class="flex flex-col md:flex-row md:items-center gap-3">
                        <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" v-model="tosAccepted" /><span>Accept Terms of Service<span class="text-red-500">*</span></span></label>
                        <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" v-model="privacyAccepted" /><span>Accept Privacy Policy<span class="text-red-500">*</span></span></label>
                        <div class="ml-auto">
                            <PrimaryButton type="submit" :disabled="isSubmitting" class="h-10">{{ isSubmitting ? 'Creating…' : 'Create' }}</PrimaryButton>
                        </div>
                    </div>
                </form>

                <!-- One-time key reveal -->
                <div v-if="justCreatedKey" class="mt-3 border border-emerald-300 bg-emerald-50 p-3 text-sm text-emerald-900">
                    <div class="font-medium mb-2">API key created (shown once)</div>
                    <div class="flex items-center gap-2">
                        <input :value="justCreatedKey" readonly class="flex-1 border bg-white font-mono text-[13px] px-2 py-1" />
                        <button class="border px-2 py-1 text-xs hover:bg-emerald-100" @click="copyKeyOnce">Copy & hide</button>
                    </div>
                </div>
            </div>

            <!-- Data Table: parent ONLY passes data; table handles pagination -->
            <DashboardTable
                table-id="apis-table"
                :items="items"
                :columns="columns"
                :default-page-size="10"
                :page-size-options="[5,10,20,50]"
                empty-text="No API keys yet."
            >
                <template #actions="{ row }">
                    <button class="border border-red-300 px-3 py-2 text-sm shadow-sm hover:bg-red-50 text-red-700" @click="deleteApi(row.id)">Delete</button>
                </template>
            </DashboardTable>
        </BodySection>
    </AdminLayout>
</template>
