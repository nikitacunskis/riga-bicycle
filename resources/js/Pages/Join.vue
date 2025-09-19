<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue'

const breadcrumbs = [{ text: 'Pievienojies', href: '/join' }]
</script>

<template>
    <FrontLayout :breadcrumbs="breadcrumbs">
        <!-- Hero -->
        <section class="relative bg-green-100 text-center py-16 px-4 sm:px-8">
            <img
                src="/img/strudel_solinsh.jpg"
                alt="Rīgas veloskaitīšanas brīvprātīgie"
                class="mx-auto rounded-lg shadow-lg mb-8 max-h-96 object-cover"
            />
            <h1 class="text-3xl sm:text-4xl font-bold text-green-800 mb-4">
                Pievienojies Rīgas veloskaitītāju komandai
            </h1>
            <p class="max-w-2xl mx-auto text-green-700 text-lg">
                Mēs skaitām velobraucējus, lai uzlabotu Rīgas infrastruktūru.
                Tavs brīvprātīgais darbs palīdz parādīt pilsētai, cik liela ir riteņbraucēju kopiena.
            </p>
            <div class="mt-6">
                <a
                    href="mailto:info@pilsetacilvekiem.lv
                     ?subject=Vēlos%20pievienoties%20veloskaitīšanai
                     &body=Labdien%2C%0A%0AVēlos%20pievienoties%20veloskaitītāju%20komandai.%20
                     Mani%20kontakti%3A%0A"
                    class="inline-block bg-green-600 text-white px-8 py-3 rounded-lg text-lg font-medium hover:bg-green-700"
                >
                    Pierakstīties tūlīt
                </a>
            </div>
        </section>

        <!-- Why Join -->
        <section class="max-w-5xl mx-auto py-12 px-4 sm:px-8">
            <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">
                Kāpēc tas ir svarīgi
            </h2>
            <p class="text-gray-700 mb-4">
                Dati par riteņbraucēju plūsmu palīdz pašvaldībai plānot drošākas
                ielas, veloceliņus un stāvvietas. Katrs saskaitītais ritenis ir
                arguments par cilvēkiem draudzīgu pilsētu.
            </p>
            <p class="text-gray-700">
                Mēs esam brīvprātīgo tīkls no dažādām apkaimēm un organizācijām.
                Nevajag biedrības biedra statusu – pietiek ar vēlmi piedalīties.
            </p>
        </section>

        <!-- How It Works -->
        <section class="bg-gray-50 py-12 px-4 sm:px-8">
            <h2 class="text-2xl font-bold text-green-800 mb-8 text-center">
                Kā notiek skaitīšana
            </h2>

            <div class="max-w-3xl mx-auto space-y-8 text-gray-700">
                <!-- Process -->
                <div>
                    <h3 class="text-xl font-semibold text-green-700 mb-3">
                        Kā notiek skaitīšana
                    </h3>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Skaitām katra mēneša 15.&nbsp;datumam tuvākajā piektdienā no 8:00–9:00.</li>
                        <li>Prioritāte – tilti un pārvadi, kur dati ir vissvarīgākie.</li>
                    </ul>
                </div>

                <!-- Expectations -->
                <div>
                    <h3 class="text-xl font-semibold text-green-700 mb-3">
                        Ko no tevis sagaidām
                    </h3>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Piedalīties regulāri, lai dati būtu nepārtraukti.</li>
                        <li>Pievienoties skaitītāju WhatsApp grupai.</li>
                        <li>Būt atbildīgam un komunikablam – ja nevari ierasties, dod ziņu.</li>
                    </ul>
                </div>

                <!-- What You Get -->
                <div>
                    <h3 class="text-xl font-semibold text-green-700 mb-3">
                        Ko tu saņem
                    </h3>
                    <ul class="list-disc list-inside space-y-2">
                        <li>Svaigu rīta gaisu un īstu Rīgas panorāmu, kamēr pilsēta mostas.</li>
                        <li>Jaunus draugus un sarunas ar citiem velomīļiem.</li>
                        <li>Sajūtu, ka tavs darbs tiešām maina pilsētu uz labu.</li>
                    </ul>
                </div>
            </div>
        </section>


        <!-- Call to Action -->
        <section class="max-w-4xl mx-auto text-center py-16 px-4 sm:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-green-800 mb-6">
                Gatavs pievienoties?
            </h2>
            <p class="text-gray-700 mb-8">
                Sūti e-pastu uz
                <a href="mailto:info@pilsetacilvekiem.lv" class="text-green-600 underline">info@pilsetacilvekiem.lv</a>
                vai zvani Andrejam <a href="tel:+37129140848" class="text-green-600 underline">2914&nbsp;0848</a>.
            </p>
            <a
                href="mailto:info@pilsetacilvekiem.lv
                     ?subject=Vēlos%20pievienoties%20veloskaitīšanai
                     &body=Labdien%2C%0A%0AVēlos%20pievienoties%20veloskaitītāju%20komandai.%20
                     Mani%20kontakti%3A%0A"
                class="inline-block bg-green-600 text-white px-10 py-4 rounded-lg text-lg font-semibold hover:bg-green-700"
            >
                Pieteikties šodien
            </a>
        </section>
    </FrontLayout>
</template>
