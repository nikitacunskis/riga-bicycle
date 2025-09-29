<template>
    <section class="py-12 px-4 sm:px-6 lg:px-8" aria-labelledby="data-heading">
        <div class="mx-auto max-w-5xl rounded-3xl bg-white/70 backdrop-blur ring-1 ring-white/60 p-8 text-center">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="mx-auto">
                    <h2 id="data-heading" class="text-2xl font-bold text-green-800">Dati visiem</h2>
                    <p class="mt-2 text-gray-700">
                        Atvērts panelis un lejupielāde pētniecībai, žurnālistikai, aktīvistiem.
                    </p>
                </div>
            </div>

            <!-- Centered action buttons -->
            <div class="mt-8 flex flex-wrap justify-center gap-4">
                <a href="/report"
                   class="inline-flex items-center justify-center rounded-xl bg-green-600 px-6 py-3 text-white shadow hover:bg-green-700">
                    Skatīt paneli
                </a>
                <a href="/apis/request"
                   class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3 text-green-700 ring-1 ring-green-200 shadow hover:bg-green-50">
                    Pieprasīt API piekļūvi
                </a>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
</script>
