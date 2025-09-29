<template>
    <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white/60 rounded-3xl mx-4 sm:mx-6 lg:mx-8 ring-1 ring-white/60">
        <div class="mx-auto max-w-6xl grid lg:grid-cols-12 gap-10 items-center">
            <!-- Text left -->
            <div class="lg:col-span-7 order-2 lg:order-1">
                <h2 class="text-3xl font-bold text-green-800">Kā mēs skaitām?</h2>
                <p class="mt-4 text-gray-700">
                    Katra skaitīšana notiek <strong>konkrētā krustojumā</strong> ar vienādu metodiku:
                </p>
                <div class="mt-6 grid sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-100 ring-1 ring-white/60 flex items-center justify-center">🗺️</div>
                        <span>Lokācija + virzieni (N/E/S/W)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-100 ring-1 ring-white/60 flex items-center justify-center">✅</div>
                        <span>Vienota lapa skaitīšanai (papīrs/app)</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-100 ring-1 ring-white/60 flex items-center justify-center">👤</div>
                        <span>Dzimums & vecuma grupa</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-100 ring-1 ring-white/60 flex items-center justify-center">🚦</div>
                        <span>Laika logs (minūtes) & apstākļi</span>
                    </div>
                </div>
            </div>

            <!-- Image right -->
            <div class="lg:col-span-5 order-1 lg:order-2">
                <div class="aspect-video w-full rounded-3xl bg-gray-200 ring-1 ring-white/60 shadow-inner flex items-center justify-center text-gray-500">
                    <span>FOTO: metodika / tāfele (placeholder)</span>
                </div>
            </div>
        </div>
    </section>
</template>
