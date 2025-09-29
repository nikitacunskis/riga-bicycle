<template>
    <section class="py-12 px-4 sm:px-6 lg:px-8" aria-labelledby="volunteer-heading">
        <h2 id="volunteer-heading" class="text-2xl font-bold text-green-800 text-center">
            Piesakies kā brīvprātīgais
        </h2>

        <form @submit.prevent="submitForm"
              class="mt-6 max-w-md mx-auto grid gap-4"
              novalidate>
            <label class="sr-only" for="name">Vārds</label>
            <input id="name" v-model="name" type="text" placeholder="Vārds"
                   required
                   class="border border-gray-300 rounded-lg p-3 w-full focus:ring-green-500 focus:border-green-500"/>

            <label class="sr-only" for="email">E-pasts</label>
            <input id="email" v-model="email" type="email" placeholder="E-pasts"
                   required
                   class="border border-gray-300 rounded-lg p-3 w-full focus:ring-green-500 focus:border-green-500"/>

            <button type="submit"
                    class="bg-green-600 text-white rounded-lg p-3 hover:bg-green-700 disabled:opacity-60"
                    :disabled="sending">
                {{ sending ? 'Sūtām…' : 'Pieteikties' }}
            </button>

            <p v-if="success" class="text-sm text-green-700" role="status" aria-live="polite">
                Paldies, {{ lastName }}! Sazināsimies ar tevi pa e-pastu {{ lastEmail }}.
            </p>
        </form>
    </section>
</template>

<script setup>
import { ref } from 'vue'
const name = ref('')
const email = ref('')
const sending = ref(false)
const success = ref(false)
const lastName = ref('')
const lastEmail = ref('')

async function submitForm () {
    sending.value = true
    success.value = false
    // TODO: replace with real API call: await $fetch('/api/volunteers', { method:'POST', body:{ name:name.value, email:email.value } })
    await new Promise(r => setTimeout(r, 600))
    lastName.value = name.value
    lastEmail.value = email.value
    name.value = ''
    email.value = ''
    sending.value = false
    success.value = true
}
</script>
