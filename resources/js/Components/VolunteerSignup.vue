<template>
    <section class="py-12 px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-green-800 text-center">
            Sign Up as a Volunteer
        </h2>
        <form
            @submit.prevent="submitForm"
            class="mt-6 max-w-md mx-auto grid gap-4"
        >
            <input
                v-model="name"
                type="text"
                placeholder="Name"
                required
                class="border border-gray-300 rounded-lg p-3 w-full focus:ring-green-500 focus:border-green-500"
            />
            <input
                v-model="email"
                type="email"
                placeholder="Email"
                required
                class="border border-gray-300 rounded-lg p-3 w-full focus:ring-green-500 focus:border-green-500"
            />
            <button
                type="submit"
                class="bg-green-600 text-white rounded-lg p-3 hover:bg-green-700"
            >
                Submit
            </button>
        </form>
    </section>
</template>

<script setup>
import { ref } from 'vue'
const name = ref('')
const email = ref('')

function submitForm() {
    // Replace with API call or emit event
    alert(`Thanks, ${name.value}! We'll contact you at ${email.value}.`)
    name.value = ''
    email.value = ''
}
</script>
