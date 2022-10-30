<script setup>
    import { Link } from '@inertiajs/inertia-vue3';
</script>

<template>
    <table>
        <tr>
            <td>
                <div class="flex justify-center">
                    <div class="rounded-lg shadow-lg bg-white max-w-sm">
                        <a href="#!">
                            <img class="rounded-t-lg" src="/img/places.png" alt=""/>
                        </a>
                        <div class="p-6">
                            <h5 class="text-gray-900 text-xl font-medium mb-2">
                                <Link href="/dashboard/places" method="get" as="button" type="button">
                                    Places<i class="fa-solid fa-map-location-dot"></i>
                                </Link>
                            </h5>
                            <p class="text-gray-700 text-base mb-4">
                                Vietu admin panelis. Visi skaitīšanas punkti ar norādēm kartē. Nepieciešams izveidot punktu šeit, pirms piesaistīt reportu punktam.
                            </p>
                            <Link href="/dashboard/places" method="get" as="button" type="button">Move -></Link>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="flex justify-center">
                    <div class="rounded-lg shadow-lg bg-white max-w-sm">
                        <a href="#!">
                            <img class="rounded-t-lg" src="/img/events.jpeg" alt=""/>
                        </a>
                        <div class="p-6">
                            <h5 class="text-gray-900 text-xl font-medium mb-2">
                                <Link href="/dashboard/events" method="get" as="button" type="button">
                                    Events<i class="fa-regular fa-calendar-check"></i>
                                </Link>
                            </h5>
                            <p class="text-gray-700 text-base mb-4">
                                Skaitīšanas pasākumu admin panelis. Ir nepieciešams izveidot pašu psaākumu, lai piesaistīt tām reportus.
                            </p>
                            <Link href="/dashboard/events" method="get" as="button" type="button">Move -></Link>
                        </div>
                    </div>
                </div>
            </td>

            <td>
                <div class="flex justify-center">
                    <div class="rounded-lg shadow-lg bg-white max-w-sm">
                        <a href="#!">
                            <img class="rounded-t-lg" src="/img/reports.jpeg" alt=""/>
                        </a>
                        <div class="p-6">
                            <h5 class="text-gray-900 text-xl font-medium mb-2">
                                <Link href="/dashboard/reports" method="get" as="button" type="button">
                                    Reports<i class="fa-solid fa-list"></i>
                                </Link>
                            </h5>
                            <p class="text-gray-700 text-base mb-4">
                                Admin panelis ar atskaitēm par veiktām skaitīšanām. Šeit glabājās RAW dati par skaitīšanām.   
                            </p>
                            <Link href="/dashboard/reports" method="get" as="button" type="button">Move -></Link>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</template>