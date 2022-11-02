<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';

const props = defineProps({
    title: {
        type: String,
        default: ""
    },
});
</script>
<template>
    <FrontLayout>
        <video autoplay muted loop>
            <source src="img/pexels-kindel-media-6774055.mp4" type="video/mp4">
            <img src="img/strudel_solinsh.jpg" />
        </video>

        <p>Lai pievienotos velo skaitīšanas komandai nav obligāti jāiestājās biedrībā "Pilsētā Cilvekiem". Datu vākšana ir vairāku aktīvistu no vairākām apkaimēm un vairākām organizācijām brīvprātīgs ieguldījums Rīgas pilsētā. Katrs, kam ir brīvs laiks un var aprīkot sevi ar lapiņu un pildspalvu, vai mobīlo telefonu tiek aicināts velo skaitītāju komandā.</p>
        <p>Galvenie nosacījumi:</p>
        <ul>
            <li> - mēs skaitam 15. datumam tuvākajā piektdienā 8:00 - 9:00</li>
            <li> - vēlams skaitīt regulāri. Ja punktā neizdodas atrast skaitītāju, tad punkts netiks saskaitīts un datos veidosies nepatīkami robi.</li>
            <li> - iekšējai komunikācijai, jūs tiksiet pievienots WhatsApp čatiņam</li>
            <li> - pirmās prioritātes punkti = TILTI un PĀRVADI!</li>
            <li> - nevari vairs skaitīt savu ierasto punktu? Nav problēma - vari paņemt citu brīvu! Nav laika? Neliela problēma - meklēsim, kas tevi aizvietos.</li>
        </ul>
        <p>Lai tiktu komandā, rakstat uz e-pastu info@pilsetacilvekiem.lv vai rakstat/zvanat pa tiešo Andrejam 29140848.</p>
    </FrontLayout>
</template>