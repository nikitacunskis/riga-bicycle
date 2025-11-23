<template>
    <FrontLayout>
        <HeroCta />
        <ValueSection />

        <AlignedSection
            title="Kas mēs esam?"
            subtitle="Mēs esam brīvprātīgie un pilsētas mobilitātes aktīvisti, kurus vieno nevienaldzība pret Rīgas nākotni. Mūsu komanda regulāri skaita velo plūsmas Rīgas krustojumos, lai sniegtu datus, kas balstīti zinātnē un pētniecībā. Šie dati palīdz veidot drošu un patīkamu pilsētvidi, kur ikviens var pārvietoties brīvi un ērti. Mēs ticam Supermobilitātei – pilsētai, kurā velobraukšana, sabiedriskais transports un gājēji veido ilgtspējīgu pārvietošanās kultūru. Mēs esam brīvprātīgie un pilsētas aktīvisti, kurus vieno interese par Rīgas mobilitātes nākotni.
                        Mūsu komanda regulāri (vismaz reizi mēnesī) skaita velo plūsmas Rīgas krustojumos, lai sniegtu
                        sistemātiskus datus. Šie dati palīdz pamatot drošas un patīkamas pilsētvides veidošanu, kur
                        ikviens var pārvietoties brīvi un ērti. Mēs ticam pilsētai, kurā velobraukšana, sabiedriskais
                        transports un gājēji veido ilgtspējīgu pārvietošanās kultūru. Katrs mērījums ir solis tuvāk Rīgai,
                        kas domā par cilvēkiem un dzīvo nākotnē."
            :items="aboutItems"
        />
        <AlignedSection
            title="Kā mēs skaitām?"
            subtitle="Brīvprātīgie veic tiešo novērošanu uz vietas, iegūstot datus, kas nav savācami ar kameru,
                        piemēram, velobraucēju dzimumu. Skaitīšana notiek katra mēneša 2. vai 3. piektdienā 8:00 –
                        9:00 no rīta katru mēnesi – dažkārt arī citu piektdienu rītos, lai laika gaitā varētu salīdzināt
                        tendences un sezonālās izmaiņas. Mūsu pieeja nodrošina augstu reprezentativitāti, jo dati tiek
                        ievākti dažādos krustojumos un uz galvenajiem pilsētas tiltiem."
            :items="howItems"
        />
        <DataCta id="dati" />
        <FaqContact />
    </FrontLayout>
</template>

<script setup>
import ValueSection from "../Components/sections/ValueSection.vue"
import FrontLayout from "../Layouts/FrontLayout.vue"
import HeroCta from "../Components/HeroCta.vue"
import AlignedSection from "../Components/sections/AlignedSection.vue"
import DataCta from "../Components/cta/DataCta.vue"
import FaqContact from "../Components/sections/FaqContact.vue"

const aboutItems = [
    {
        image: "/img/pexels-marleneleppanen-1238456.jpg",
        imageAlt: 'Komanda krustojumā',
        heading: 'Kopiena ar misiju',
        body: 'Mēs veidojam atvērtu brīvprātīgo tīklu, kas ar precīziem datiem palīdz pierādīt, kur Rīgai\n' +
            'nepieciešamas droša veloinfrastruktūra. Visi rezultāti ir publiski pieejami un izmantojami gan\n' +
            'sabiedrībai, gan plānotājiem. Šī pieeja nodrošina caurspīdību un veicina līdzdalību pilsētas\n' +
            'procesos.',
        points: [
            { icon: '🚲', text: 'Mūsu komanda strādā brīvprātīgi un bez politiskas ietekmes, lai nodrošinātu uzticamus datus. Ikviens var pievienoties un piedalīties.' },
            { icon: '📊', text: 'Iegūtie dati ir publiski pieejami atvērtā formātā, lai tos varētu analizēt jebkurš interesents. Tas veicina sadarbību ar pētniekiem un pilsētplānotājiem.' }
        ]
    },
]


const howItems = [
    {
        image: "/img/pexels-mary-nikitina-53440096-7828573.jpg",
        imageAlt: 'Skaitīšanas lapa',
        heading: 'Vienota metodika',
        body: 'Mēs katru mēnesi skaitām velobraucējus vienā un tajā pašā vietā, lai iegūtu salīdzināmus datus\n' +
            'ilgākā laika posmā. Šāda regularitāte palīdz pamanīt sezonālās un ilgtermiņa izmaiņas. Visi\n' +
            'brīvprātīgie veic skaitīšanu pēc vienotiem kritērijiem, kas nodrošina uzticamus un pārbaudāmus\n' +
            'rezultātus.',
        points: [
            {
                icon: '🚲',
                text: 'Mēs vienmēr skaitam tajās pašās vietās un skaitām tos pašus rādītājus (dzimumu īpatsvars,\n' +
                    'bērnu krēsliņu skaits, elektroskrejriteņu un e-velo skaits u.tml.), lai dati būtu konsekventi. Šī\n' +
                    'metode ļauj saprast, kā mainās velobraucēju plūsmas dažādos gadalaikos.',
            },
            {
                icon: '🔍',
                text: 'Brīvprātīgie tiek rūpīgi apmācīti un ievēro vienotus standartus. Tas nodrošina, ka rezultāti ir\n' +
                    'ticami un iegūti pēc vienas metodikas.',
            },
        ]
    },
    {
        image: "/img/pexels-vlad-fonsark-2175898-4037900.jpg",
        imageAlt: 'Virzienu bultiņas',
        heading: 'Virzieni un laikapstākļi',
        body: 'Skaitīšanas laikā mēs uzskaitām velobraucēju kustības virzienu (uz centru/ no centra).\n' +
            'Precīza informācija par virzieniem un laikapstākļiem palīdz saprast, kā dažādi faktori maina\n' +
            'braucēju skaitu. Šie dati ļauj pilsētplānotājiem noteikt velobraucēju kustības tendences un \n' +
            'virzienus, kur nepieciešama jauna veloinfrastruktūra. Rūpīga piezīmju veikšana nodrošina, ka\n' +
            'katrs mērījums ir salīdzināms un izmantojams ilgtermiņa stratēģijās.',
        points: [
            { icon: '🧭', text: 'Fiksējam, kurā virzienā velobraucēji dodas – uz centru vai no centra. Šie dati atklāj populārākos\n' +
                    'maršrutus un palīdz plānot jaunus veloceļus.' },
            { icon: '🌦️', text: 'Pierakstām laikapstākļus un īpašus notikumus, kas var ietekmēt plūsmu. Šī informācija palīdz\n' +
                    'precīzāk salīdzināt datus dažādos gadalaikos un situācijās.' },
        ],
    }
]

const whatItems = [
    {
        imageAlt: 'Transportlīdzekļu ikonas',
        heading: 'Mobilitātes veids',
        body: 'Velosipēds, e-velosipēds, e-skūteris, kravas velo u.c.',
        pills: ['Velosipēds','E-velosipēds','E-skūteris','Kravas velo','Cits']
    },
    {
        imageAlt: 'Demogrāfija un virzieni',
        heading: 'Demogrāfija un plūsma',
        body: 'Dzimums, vecuma grupa, virziens, krustojums, laiks, laikapstākļi.',
        pills: ['Dzimums','Vecuma grupa','Virziens','Krustojums','Laiks','Laikapstākļi']
    }
]
</script>
