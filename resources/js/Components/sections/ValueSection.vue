<template>
    <section class=" px-4 sm:px-6 lg:px-8 bg-white/70 backdrop-blur ring-1 ring-white/60">
        <div class="mx-auto max-w-6xl">
            <header class="text-center">
                <h2 class="text-4xl font-extrabold text-green-800">
                    Kāpēc skaitām velobraucējus?
                </h2>
                <p class="mt-4 text-lg text-gray-700 max-w-3xl mx-auto">
                    Dati par riteņbraukšanu ir pierādījums pilsētai, ka veloinfrastruktūra ir vajadzīga
                    un jāplāno gudri. Skaitīšana nav hobijs – tā ir pilsētas attīstības pamatne.
                </p>
            </header>

            <!-- 3-column value grid -->
            <div class="mt-16 grid md:grid-cols-3 gap-10">
                <div class="rounded-3xl bg-emerald-50 p-6 ring-1 ring-emerald-200 shadow">
                    <h3 class="text-xl font-bold text-emerald-800">Svārstību monitorings</h3>
                    <p class="mt-3 text-gray-700">
                        Laika posmā starp 2014. un 2018. gadu velobraucēju skaits vidēji pieauga par <strong>40%</strong>, savukārt,
                        pēdējo gadu laikā situācija ir stabilizējusies, skaits ir atkarīgs arī no laikapstākļiem.
                        Pēc velojoslu ieviešanas uz Zemitāna tilta, velobraucēju skaits pieauga vidēji <strong>1.5 reizes</strong>, bet
                        nākotnē varēs novērtēt, vai tas būs mainījies pēc veloceļa izbūves.
                    </p>
                </div>

                <div class="rounded-3xl bg-emerald-50 p-6 ring-1 ring-emerald-200 shadow">
                    <h3 class="text-xl font-bold text-emerald-800">Plānošana & sistemātiski dati</h3>
                    <p class="mt-3 text-gray-700">
                        Rīgas vadība sola, bet neveic sistemātisku automatizētu uzskaiti. Mūsu dati rāda, kur ir lielāka
                        velosatiksme un kur nepieciešama labāka veloinfrastruktūra.
                    </p>
                </div>

                <div class="rounded-3xl bg-emerald-50 p-6 ring-1 ring-emerald-200 shadow">
                    <h3 class="text-xl font-bold text-emerald-800">Vienlīdzība</h3>
                    <p class="mt-3 text-gray-700">
                        Sieviešu īpatsvars pieaug, bet joprojām atpaliek no optimālā 50 %.
                        Drošāka infrastruktūra nozīmē, ka braukt var visi – arī bērni un vecāki ar kravas velosipēdiem.
                    </p>
                </div>
            </div>

            <!-- Story + CTA -->
            <div class="mt-16 grid lg:grid-cols-2 gap-10 items-center">
                <div class="aspect-video w-full rounded-3xl overflow-hidden">
                    <img src="/img/pexels-vlad-fonsark-2175898-4993448.jpg" />
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-green-800">Mūsu misija</h3>
                    <p class="mt-4 text-gray-700">
                        Kad pilsēta nerūpējas par datiem, mēs – brīvprātīgie – to darām. Skaitām nozīmīgākajos
                        krustojumos un uz tiltiem, lai publiski parādītu, ka veloinfrastruktūra ir pieprasīta un
                        nepieciešama
                    </p>
                    <div class="mt-6 flex flex-wrap gap-4">
                        <a href="/join"
                           class="inline-flex items-center justify-center rounded-xl bg-green-600 px-5 py-3 text-white shadow hover:bg-green-700">
                            Pievienoties skaitītājiem
                        </a>
                        <a href="/report"
                           class="inline-flex items-center justify-center rounded-xl bg-white px-5 py-3 text-green-700 ring-1 ring-green-200 shadow hover:bg-green-50">
                            Skatīt visus datus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script setup lang="ts">
</script>
