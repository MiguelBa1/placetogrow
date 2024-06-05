<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>
    <div class="max-w-7xl bg-white w-full m-auto rounded-xl p-10 shadow-md mt-10">
        <div class="p-5 border-b-2">
            <h1 class="text-gray-700 font-extrabold text-3xl">Sitios</h1>
        </div>

        <div class="flex flex-wrap mt-14">
            <Link class="max-w-xs w-full px-5 mb-8" href="/site1">
                <div class="bg-gray-50 flex items-center shadow-md border rounded-md px-6 py-4 hover:bg-gray-100">
                    <svg class="h-24 w-full text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"/>
                    </svg>
                </div>

                <h5 class="text-gray-700 text-xl text-center font-bold mt-2"> Sitio 1 </h5>
            </Link>
        </div>
    </div>
</template>
