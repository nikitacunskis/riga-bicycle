<template>
    <FrontLayout>
        <HeroCta />
        <ValueSection />

        <AlignedSection
            title="Kas mēs esam?"
            subtitle="Mēs esam brīvprātīgie un pilsētas mobilitātes aktīvisti, kurus vieno nevienaldzība pret Rīgas nākotni. Mūsu komanda regulāri skaita velo plūsmas Rīgas krustojumos, lai sniegtu datus, kas balstīti zinātnē un pētniecībā. Šie dati palīdz veidot drošu un patīkamu pilsētvidi, kur ikviens var pārvietoties brīvi un ērti. Mēs ticam Supermobilitātei – pilsētai, kurā velobraukšana, sabiedriskais transports un gājēji veido ilgtspējīgu pārvietošanās kultūru. Katrs mērījums ir solis tuvāk Rīgai, kas domā par cilvēkiem un dzīvo nākotnē, nevis pagātnē."
            :items="aboutItems"
        />
        <AlignedSection
            title="Kā mēs skaitām?"
            subtitle="Mēs strādājam ar zinātnisku metodi, lai nodrošinātu precizitāti un ticamus rezultātus. Brīvprātīgie veic tiešo novērošanu uz vietas, iegūstot datus, kas nav savācami ar kameru, piemēram, velobraucēju vecumu un dzimumu. Skaitīšana notiek ar stingru regularitāti, lai laika gaitā varētu salīdzināt tendences un sezonālās izmaiņas. Mūsu pieeja nodrošina augstu reprezentativitāti, jo dati tiek ievākti dažādos krustojumos un dažādos diennakts laikos. Šādi mēs iegūstam faktus, kas palīdz plānot drošāku, velobraucējiem draudzīgāku pilsētvidi."
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
        body: 'Mēs veidojam atvērtu brīvprātīgo tīklu, kas ar precīziem datiem palīdz pierādīt, kur Rīgai nepieciešamas drošas velojoslas un gudri risinājumi. Visi rezultāti ir publiski pieejami un izmantojami gan sabiedrībai, gan plānotājiem. Šī pieeja nodrošina caurspīdību un veicina līdzdalību pilsētas attīstībā.',
        points: [
            { icon: '🚲', text: 'Mūsu komanda strādā brīvprātīgi un bez politiskas ietekmes, lai nodrošinātu uzticamus datus. Ikviens var pievienoties un piedalīties.' },
            { icon: '📊', text: 'Iegūtie dati ir publiski pieejami atvērtā formātā, lai tos varētu analizēt jebkurš interesents. Tas veicina sadarbību ar pētniekiem un pilsētplānotājiem.' }
        ]
    },
    {
        image: "/img/pexels-hngstrm-3073037.jpg",
        imageAlt: 'Rīgas karte ar punktiem',
        heading: 'Vairāki krustojumi',
        body: 'Skaitīšana notiek vairākās vietās vienlaicīgi, lai atspoguļotu patieso velobraucēju plūsmu visā pilsētā. Šāda plaša pārklājuma pieeja ļauj pamanīt atšķirības starp rajoniem un saprast, kur vajadzīga papildu infrastruktūra.',
        points: [
            { icon: '🗺️', text: 'Katrs mērījums tiek veikts pēc iepriekš noteiktām koordinātām, kas nodrošina salīdzināmus datus. Tas palīdz identificēt konkrētas plūsmas un maršrutus.' },
            { icon: '🕒', text: 'Dati tiek ievākti dažādos diennakts laikos un gadalaikos, lai fiksētu satiksmes maksimumus. Šādi iespējams prognozēt pieprasījuma svārstības un pielāgot risinājumus.' }
        ]
    }
]


const howItems = [
    {
        image: "/img/pexels-mary-nikitina-53440096-7828573.jpg",
        imageAlt: 'Skaitīšanas lapa',
        heading: 'Vienota metodika',
        body: 'Mēs katru mēnesi skaitām velobraucējus vienā un tajā pašā vietā, lai iegūtu salīdzināmus datus ilgākā laika posmā. Šāda regularitāte palīdz pamanīt sezonālās un ilgtermiņa izmaiņas. Visi brīvprātīgie strādā pēc vienotiem kritērijiem, kas nodrošina uzticamus un pārbaudāmus rezultātus. Dati ir izmantojami gan zinātniskai pētniecībai, gan Rīgas pilsētplānošanas vajadzībām.',
        points: [
            {
                icon: '🚲',
                text: 'Mēs vienmēr strādājam tajā pašā vietā un skaitām tos pašus rādītājus, lai dati būtu konsekventi. Šī metode ļauj saprast, kā mainās velobraucēju plūsmas dažādos gadalaikos.',
            },
            {
                icon: '🔍',
                text: 'Brīvprātīgie tiek rūpīgi apmācīti un ievēro vienotus standartus. Tas nodrošina, ka rezultāti ir ticami un izmantojami pilsētplānošanas lēmumos.',
            },
        ]
    },
    {
        image: "/img/pexels-vlad-fonsark-2175898-4037900.jpg",
        imageAlt: 'Virzienu bultiņas',
        heading: 'Virzieni un apstākļi',
        body: 'Skaitīšanas laikā mēs uzskaitām velobraucējus un pierakstām apstākļus, kas ietekmē satiksmes plūsmu. Precīza informācija par virzieniem un laikapstākļiem palīdz saprast, kā dažādi faktori maina braucēju skaitu. Šie dati ļauj pilsētplānotājiem noteikt vietas, kur nepieciešami jauni veloceļi un drošības uzlabojumi. Rūpīga piezīmju veikšana nodrošina, ka katrs mērījums ir salīdzināms un izmantojams ilgtermiņa stratēģijās.',
        points: [
            { icon: '🧭', text: 'Fiksējam, no kura virziena velobraucēji ierodas: ziemeļi, austrumi, dienvidi, rietumi. Šie dati atklāj populārākos maršrutus un palīdz plānot jaunus veloceļus.' },
            { icon: '🌦️', text: 'Pierakstām laikapstākļus, redzamību un īpašus notikumus, kas ietekmē plūsmu. Šī informācija palīdz precīzāk salīdzināt datus dažādos gadalaikos un situācijās.' },
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
