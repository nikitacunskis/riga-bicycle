<template>
    <nav
        class="flex items-center justify-between py-4 px-4 sm:px-6 lg:px-8 bg-white text-black"
    >
        <!-- Logo / Brand -->
        <div class="text-3xl font-bold">
            <a href="/">
                <img src="/img/logo.png" class="max-h-24" />
            </a>
        </div>

        <!-- Desktop links -->
        <div class="hidden md:flex space-x-6">
            <a href="/join" class="hover:none text-xl font-bold">Kļūt par brīvprātīgo</a>
            <a href="/report" class="hover:none text-xl font-bold">Iegūt datus</a>
            <a href="/apis/request" class="hover:none text-xl font-bold">API</a>
        </div>

        <!-- Mobile menu button -->
        <button
            class="md:hidden p-2 focus:outline-none focus:ring-2 focus:ring-white"
            @click="mobileOpen = !mobileOpen"
            aria-label="Toggle menu"
        >
            ☰
        </button>

        <!-- Mobile panel -->
        <div
            v-if="mobileOpen"
            class="absolute top-full left-0 w-full bg-green-700 md:hidden"
        >
            <a
                href="#"
                class="block px-4 py-2 border-b border-green-800 hover:bg-green-800"
            >Become a Counter</a>
            <a
                href="#"
                class="block px-4 py-2 hover:bg-green-800"
            >View Data</a>
        </div>
    </nav>
</template>

<script setup>
import { ref } from 'vue'
const mobileOpen = ref(false)
</script>
