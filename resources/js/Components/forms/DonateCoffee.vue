<template>
    <section class="py-12 px-4 sm:px-6 lg:px-8" aria-labelledby="donate-heading">
        <div class="max-w-3xl mx-auto text-center">
            <h2 id="donate-heading" class="text-2xl font-bold text-green-800">
                Atbalsti ar kafiju
            </h2>
            <p class="mt-3 text-gray-700">
                Katra skaitīšanas diena sākas ar karstu kafiju brīvprātīgajiem.
                Ja vari, uzsauc krūzi — tas tiešām palīdz! ☕
            </p>

            <!-- BMaC primary button -->
            <a :href="`https://www.buymeacoffee.com/${handle}`"
               rel="noopener noreferrer"
               target="_blank"
               class="mt-6 inline-flex items-center justify-center rounded-xl bg-yellow-500 px-5 py-3 font-semibold text-black shadow hover:bg-yellow-400">
                Buy Me a Coffee
            </a>

            <!-- Quick amount suggestions as secondary links -->
            <div class="mt-4 flex flex-wrap justify-center gap-2 text-sm">
                <a v-for="a in [3,5,10,15]" :key="a"
                   :href="`https://www.buymeacoffee.com/${handle}`"
                   target="_blank" rel="noopener noreferrer"
                   class="px-3 py-1 rounded-lg bg-white ring-1 ring-emerald-200 text-emerald-800 hover:bg-emerald-50">
                    €{{ a }}
                </a>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    handle: { type: String, required: true }
})
</script>
