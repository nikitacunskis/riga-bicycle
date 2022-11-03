<script setup>
    import Breadcrumb from '@/Components/Breadcrumb.vue';
    const props = defineProps({
        title: {
            type: String,
            default: ""
        },
        breadcrumbs: {
            type: Array,
            default: []
        }
    });

</script>

<template>

<nav id="header" class="fixed w-full z-10 top-0">

    <div class="w-full md:max-w-4xl mx-auto flex flex-wrap items-center justify-between mt-0 py-3">

        <div class="pl-4">
            <a class="text-gray-900 text-base no-underline hover:no-underline font-extrabold text-xl" href="/">
                _____Velo skaitīšana_____<i class="fa-solid fa-person-biking"></i>
            </a>
        </div>

        <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto lg:block mt-2 lg:mt-0 bg-gray-100 md:bg-transparent z-20" id="nav-content">
            <ul class="list-reset lg:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    <a class="link-regular" href="/">Sākums</a>
                </li>
                <li class="mr-3">
                    <a class="link-active" href="/report">Atskaites</a>
                </li>
                <li class="mr-3">
                    <a class="link-regular" href="/join">Pievienojies</a>
                </li>
                <li class="mr-3">
                    <a class="link-regular" href="/raw">Neapstrādāti dati</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--Container-->
<div class="container w-full md:max-w-[55%] mx-auto pt-20">

    <div class="w-full px-4 md:px-6 text-xl text-gray-800 leading-normal" style="font-family:Georgia,serif;">

        {{ props.title }}<br>
    
        <Breadcrumb :elements="props.breadcrumbs" />

    </div>

    <!--Divider-->
    <hr class="border-b-2 border-gray-400 mb-8 mx-4">

        <div class="content-center">
            <slot />
        </div>

    <!--Divider-->
    <hr class="border-b-2 border-gray-400 mb-8 mx-4">

</div>
<!--/container-->

<footer class="bg-white border-t border-gray-400 shadow">
<div class="container max-w-4xl mx-auto flex py-8">
    <div class="w-full mx-auto flex flex-wrap">
        <div class="flex w-full md:w-1/3 ">
            <img class="w-20 h-20 rounded-full mr-4" src="img/nikitacunskis.png" alt="Nikita Cunskis profila bilde">
            <div class="flex-1 px-2">
                <h3 class="font-bold text-gray-900">Ņikita Cunskis</h3>
                <p class="text-gray-600 text-xs md:text-base">IT risinājuma izveide 
                <a class="text-green-500 no-underline hover:underline" href="https://github.com/nikitacunskis/riga-bicycle">GitHub</a><br>
                <a class="text-green-500 no-underline hover:underline" href="https://twitter.com/nikitacunskis">Twitter</a><br>
                <a class="text-green-500 no-underline hover:underline" href="mailto:nikita.cunskis@gmail.com">nikita.cunskis@gmail.com</a>
                </p>
            </div>
        </div>

        <div class="flex w-full md:w-1/3 ">
            <img class="w-20 h-20 rounded-full mr-4" src="img/pilsēta-cilvēkiem-logo.jpg" alt="Pilsēta Cilvēkiem logotips">
            <div class="flex-1 px-2">
                <h3 class="font-bold text-gray-900">Pilsēta Cilvēkiem</h3>
                <p class="text-gray-600 text-xs md:text-base">
                <a class="text-green-500 no-underline hover:underline" href="mailto:info@pilsetacilvekiem.lv">info@pilsetacilvekiem.lv</a><br>
                <a class="text-green-500 no-underline hover:underline" href="tel:++37129112372">+371 29112372</a><br>
                Juridiskā adrese:<br>
                Ropažu iela 14 k-1 - 105, Rīga, LV-1039<br>
                </p>
            </div>
        </div>

        <div class="flex w-full md:w-1/3">
            <div class="px-8">
                <h3 class="font-bold text-gray-900">Social</h3>
                <ul class="list-reset items-center text-sm pt-3">
                    <li>
                        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="https://www.facebook.com/PilsetaCilvekiem/">Facebook</a>
                    </li>
                    <li>
                        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="https://www.tiktok.com/@pilsetacilvekiem">Tik Tok</a>
                    </li>
                    <li>
                        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="https://www.youtube.com/channel/UCY3R63J7tbUg3qPMtbqmU6A">YouTube</a>
                    </li>
                    <li>
                        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="https://www.instagram.com/pilsetacilvekiem/">Instaram</a>
                    </li>
                    <li>
                        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="https://twitter.com/pilscilvekiem">Twitter</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
</footer>

</template>