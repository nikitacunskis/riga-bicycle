<script setup>
import Header from '@/components/Header.vue';
import HeroSection from '@/components/HeroSection.vue';
import ParagraphSection from '@/components/ParagraphSection.vue';
import FullWidthImage from '@/components/FullWidthImage.vue';
import PieChart from '@/components/charts/PieChart.vue';
import BarChart from '@/components/charts/BarChart.vue';
import MultiLineChart from '@/components/charts/MultiLineChart.vue';

const menuItems = [
  { label: 'Visualizer', link: '/visualizer', bgColor: 'transparent', textColor: '#16a34a' },
  { label: 'API', link: '/api', bgColor: 'transparent', textColor: '#16a34a' }
];

const heroButtons = [
  { label: 'See Data Visualizer', link: '/visualizer', bgColor: 'green', textColor: 'white' },
  { label: 'Get API for Developers', link: '/api', bgColor: 'blue', textColor: 'white' }
];

const introText1 = 'Veloskaitīšana ir nozīmīgs pētniecības instruments, kas parāda velosatiksmes plūsmas izmaiņas gadu griezumā galvenajos Rīgas punktos. Tostarp veloskaitīšana arī parāda velosatiksmes plūsmas izmaiņas, izbūvējot jaunu infrastruktūru. Papildus tiek ievākti arī dati par mikromobilitātes līdzekļu un sieviešu īpatsvaru velosatiksmē. Sieviešu īpatsvars spēj parādīt to, cik droša ir veloinfrastruktūra. Jo tas tuvāks 50%, jo drošāk.';
const introText2 = 'Veloskaitīšana notiek regulāri dažādos Rīgas punktos katra mēneša 2. vai 3. piektdienā starp 8:00 un 9:00 no rīta jau kopš 2018.gada rudens (tiek pieņemts, ka rīta sastrēgumstundā ir apmēram 10% no visas diennakts plūsmas). Metodika pārņemta no Latvijas Riteņbraucēju biedrības. Skaitīšanu veic un turpinās veikt brīvprātīgie, kamēr Rīgas pilsēta neuzstādīs automātiskos skaitītājus.';
const introText3 = 'Katru mēnesi ievāktie dati vairāku gadu garumā spēj labi parādīt veloplūsmas tendenci Rīgas galvenajos punktos, ko iespējams izmantot, piemēram, Rīgas domei attīstot veloinfrastruktūru, tai skaitā arī pētot lietotāju skaita pieaugumu, izbūvējot vai uzlabojot veloinfrastruktūru. Datus var izmantot ikviens interesents satiksmes un pilsētvides jomā.';
const introText4 = 'Mājas lapa izveidota 2022.gadā, uzlabojot datu lietošanas funkcionalitāti un padarot datus pieejamus ikvienam lietotājam.';
</script>

<template>
  <div>
    <Header :menu-items="menuItems" logo-src="/img/places.png" />
    <HeroSection background-src="/img/events.jpeg" hero-text="Bicycle Traffic Data" hero-sub-text="Explore counts across Riga" hero-text-color="white" hero-height="50vh" :hero-buttons="heroButtons" />
    <ParagraphSection :text-content="introText1" />
    <ParagraphSection :text-content="introText2" />
    <ParagraphSection :text-content="introText3" />
    <ParagraphSection :text-content="introText4" button-label="Join Volunteers" button-link="/join" />
    <FullWidthImage image-src="/img/reports.jpeg" image-height="200px" />
    <div class="p-4 grid gap-4 md:grid-cols-3">
      <PieChart :chart-data="[40,60]" :chart-labels="['Cyclists','Others']" class="h-64" />
      <BarChart :chart-data="[10,20,30]" :chart-labels="['2019','2020','2021']" class="h-64" />
      <MultiLineChart :chart-data="[[1,2,3],[3,2,1]]" :chart-labels="['2019','2020','2021']" :colors="['#16a34a','#6ee7b7']" class="h-64" />
    </div>
  </div>
</template>
