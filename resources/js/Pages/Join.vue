<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue'

const breadcrumbs = [{ text: 'Pievienojies', href: '/join' }]
</script>

<template>
    <FrontLayout :breadcrumbs="breadcrumbs">
        <!-- Hero -->
        <section
            class="relative mx-auto max-w-7xl p-6 sm:p-10 bg-white/60 backdrop-blur-xl ring-1 ring-emerald-100 rounded-3xl shadow-2xl overflow-hidden"

        >
            <section class="grid grid-cols-12 gap-6">
                <div class="col-span-4">
                    <img
                        src="/img/strudel_solinsh.jpg"
                        alt="Rīgas veloskaitīšanas brīvprātīgie"
                        class="mx-auto mb-8 max-h-96 rounded-lg object-cover shadow-lg"
                    />
                </div>

                <div class="col-span-8 space-y-4">
                    <h1 class="text-3xl sm:text-4xl font-bold text-green-800 mb-4">
                        Pievienojies Rīgas veloskaitītāju komandai
                    </h1>
                    <p class="max-w-2xl text-green-700 text-lg">
                        Mēs skaitām velobraucējus, lai iegūtu sistemātisku statistiku, kas pamatotu Rīgas infrastruktūras
                        uzlabošanu. Tavs brīvprātīgais darbs palīdz parādīt pilsētai, cik liela ir riteņbraucēju kopiena.
                    </p>

                    <span class="block mt-3 text-lg font-semibold">
                        - Aizņem tikai dažas minūtes — pievienojies jau šodien
                    </span>
                    <span class="block mt-2 text-lg font-semibold">
                        - Skaitīšana notiek tikai 1 stundu mēnesī
                    </span>
                </div>
            </section>

            <div class=" text-center">
                <a
                    href="mailto:info@pilsetacilvekiem.lv
                     ?subject=Vēlos%20pievienoties%20veloskaitīšanai
                     &body=Labdien%2C%0A%0AVēlos%20pievienoties%20veloskaitītāju%20komandai.%20
                     Mani%20kontakti%3A%0A"
                    class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg text-4xl font-extrabold hover:bg-green-700"
                >
                    Pierakstīties tūlīt
                </a>
            </div>
        </section>

        <!-- Why Join -->
        <section class="max-w-5xl mx-auto py-12 px-4 sm:px-8">
            <h2 class="text-3xl font-bold text-green-800 mb-6 text-center">
                Kāpēc tas ir svarīgi
            </h2>
            <p class="mb-4">
                <b>Rīgas velosatiksmes skaitīšana</b> ir sistemātiska un zinātniska pieeja pilsētvides mērīšanai.
                Mēs strādājam pēc Atbilstoši dāņu urbānista Jāna Gēla principiem <b>“Cities for People”</b> mūsu
                mērķis ir padarīt Rīgu efektīvāku, dzīvīgāku un pievilcīgāku. Labi dati ir pamats gudrai
                pārvaldībai: tie ļauj pašvaldībai plānot veloinfrastruktūru un pamatot tās nepieciešamību.
                Mēs sadarbojamies ar Jana Gēla biroju, urbānistu Mikaelu Kolvilu-Andersenu un Danielu Hess,
                lai pierādītu, ka satiksme pilsētā var kļūt daudz efektīvāka un demokrātiskāka.
                <b>Velosipēds kā ilgtspējīgākais un ērtākais transporta veids</b> – ir viena no attīstības vektoru prioritātēm.
                Tāpēc dati par velosatiksmi interesē ne tikai pašvaldību, bet arī studentus, uzņēmējus un pētniekus.
                Šie dati ir pilsētas nākotnes atslēga. Tieši tāpēc mēs īpaši uzsveram <b>konsekvenci, regularitāti un uzticamību</b>
                – un sagaidām no brīvprātīgajiem <b>atbildību un godīgumu</b>.
            </p>

            <p class="mb-4">
                <b>Brīvprātīgie ir mūsu kustības sirds, kas tic, ka Rīga var kļūt par pilsētu, kas draudzīga cilvēkiem.</b>
                Lai piedalītos veloskaitīšanā, nav nepieciešams biedrības “Pilsētā cilvēkiem” biedra vai
                atbalstītāja statuss, taču visi brīvprātīgie ir laipni gaidīti mūsu pasākumos, diskusijās un
                aicināti kļūt par atbalstītājiem un biedriem, lai iegūtu vērtīgu kontaktu loku, vai pat palīdzētu šo
                pieredzi pārvērst karjerā.
            </p>

            <p class="mb-4">
                Tomēr pats svarīgākais ir ieguldījums <b>pilsētas attīstībā</b>. Veltot tikai <b>vienu stundu mēnesī</b>, tu
                kļūsti par Rīgas nākotnes līdzveidotāju – cilvēku, kas palīdz radīt <b>modernu, cilvēkiem draudzīgu un efektīvu galvaspilsētu</b>.
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
                vai raksti / zvani Andrejam <a href="tel:+37129140848" class="text-green-600 underline">+371 29140848.</a>.
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
