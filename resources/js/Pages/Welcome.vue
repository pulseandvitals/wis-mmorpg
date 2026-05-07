<script setup>
import { Head, Link } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Welcome" />

    <body class="bg-[#0b1220] text-white overflow-x-hidden">
        <!-- Background -->
        <div class="fixed inset-0">
            <div
                class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1501785888041-af3ef285b470')] bg-cover bg-center opacity-40"
            ></div>
            <div
                class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/40 to-[#0b1220]"
            ></div>
        </div>

        <!-- Floating particles -->
        <div class="pointer-events-none fixed inset-0">
            <div
                class="w-2 h-2 bg-yellow-300 rounded-full absolute top-24 left-16 animate-pulse"
            ></div>
            <div
                class="w-1 h-1 bg-blue-300 rounded-full absolute top-40 right-24 animate-ping"
            ></div>
            <div
                class="w-2 h-2 bg-green-300 rounded-full absolute bottom-40 left-1/2 animate-pulse"
            ></div>
        </div>

        <!-- Navbar -->
        <header
            class="relative z-10 flex justify-between items-center px-10 py-6"
        >
            <h1 class="text-2xl font-bold tracking-widest text-yellow-300">
                ELFARIA
            </h1>

            <nav class="flex gap-6 text-sm text-gray-200">
                <a href="#" class="hover:text-yellow-300 transition">Home</a>
                <a href="#" class="hover:text-yellow-300 transition">Classes</a>
                <a href="#" class="hover:text-yellow-300 transition">Ranking</a>
                <a href="#" class="hover:text-yellow-300 transition"
                    >Download</a
                >
            </nav>
        </header>

        <!-- Hero -->
        <section class="relative z-10 text-center mt-24 px-6">
            <h2
                class="text-5xl md:text-7xl font-extrabold text-yellow-300 drop-shadow-lg"
            >
                Enter Elfaria
            </h2>

            <p class="mt-4 text-gray-200 max-w-xl mx-auto">
                A 2D fantasy MMORPG where legends are forged, dungeons are
                endless, and heroes are born from chaos.
            </p>

            <!-- Buttons -->
            <div class="mt-8 flex justify-center gap-4">
                <button
                    class="px-6 py-3 bg-yellow-400 text-black font-bold rounded-xl hover:scale-105 transition"
                >
                    Start Adventure
                </button>

                <button
                    class="px-6 py-3 border border-white rounded-xl hover:bg-white hover:text-black transition"
                >
                    Watch Trailer
                </button>
            </div>

            <!-- Server info -->
            <div
                class="mt-10 flex justify-center gap-4 flex-wrap text-sm text-gray-300"
            >
                <div class="bg-black/40 px-4 py-2 rounded-lg">
                    🌍 Server: Online
                </div>
                <div class="bg-black/40 px-4 py-2 rounded-lg">
                    👥 Players: 1,284
                </div>
                <div class="bg-black/40 px-4 py-2 rounded-lg">
                    ⚔️ PvP Enabled
                </div>
            </div>
        </section>

        <!-- Classes -->
        <section class="relative z-10 mt-28 px-10">
            <h3 class="text-3xl font-bold text-center mb-10">
                Choose Your Class
            </h3>

            <div class="grid md:grid-cols-3 gap-6 max-w-6xl mx-auto">
                <div
                    class="bg-black/40 border border-white/10 p-6 rounded-2xl hover:scale-105 transition"
                >
                    <h4 class="text-xl font-bold text-yellow-300">Knight</h4>
                    <p class="text-sm text-gray-300 mt-2">
                        Master melee combat and become the frontline protector
                        of Elfaria.
                    </p>
                </div>

                <div
                    class="bg-black/40 border border-white/10 p-6 rounded-2xl hover:scale-105 transition"
                >
                    <h4 class="text-xl font-bold text-yellow-300">Mage</h4>
                    <p class="text-sm text-gray-300 mt-2">
                        Wield arcane powers and unleash devastating elemental
                        spells.
                    </p>
                </div>

                <div
                    class="bg-black/40 border border-white/10 p-6 rounded-2xl hover:scale-105 transition"
                >
                    <h4 class="text-xl font-bold text-yellow-300">Archer</h4>
                    <p class="text-sm text-gray-300 mt-2">
                        Strike from distance with precision and deadly accuracy.
                    </p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer
            class="relative z-10 mt-28 text-center text-gray-400 text-sm pb-10"
        >
            © 2026 Elfaria MMORPG. All rights reserved.
        </footer>
    </body>
</template>
