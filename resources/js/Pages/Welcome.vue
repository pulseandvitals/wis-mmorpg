<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";

const hoveredClass = ref(null);
const isMobile = ref(false);

onMounted(() => {
    isMobile.value = window.innerWidth < 768;

    // Detect mobile for responsive adjustments
    window.addEventListener("resize", () => {
        isMobile.value = window.innerWidth < 768;
    });
});
const jsonLd = ref({
    "@context": "https://schema.org",
    "@type": "VideoGame",
    name: "Wisteria Online",
    description:
        "Free MMORPG browser game with fantasy world, PvP battles, quests, and dungeons. Play on mobile or desktop.",
    genre: ["MMORPG", "Fantasy", "Adventure"],
    gamePlatform: ["Web Browser", "Mobile", "Desktop"],
    applicationCategory: "Game",
    offers: {
        "@type": "Offer",
        price: "0",
        priceCurrency: "USD",
    },
    aggregateRating: {
        "@type": "AggregateRating",
        ratingValue: "4.8",
        ratingCount: "15420",
    },
});
</script>

<template>
    <Head>
        <title>
            Wisteria Online - Free MMORPG Browser Game | Mobile & Desktop Ready
        </title>

        <!-- Primary Meta -->
        <meta
            name="description"
            content="Wisteria Online - Free MMORPG browser game. Play on mobile or desktop. Explore a fantasy world, battle monsters, complete quests, join PvP fights, and level up your hero online."
        />
        <meta
            name="keywords"
            content="wisteria online, mmorpg browser game, free mmorpg, online rpg, fantasy browser game, 2d mmorpg, multiplayer rpg, free online rpg game, mobile mmorpg, desktop mmorpg"
        />
        <meta name="robots" content="index, follow" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes"
        />
        <meta name="theme-color" content="#0a0e27" />

        <link rel="canonical" href="https://wisteria-online.com" />

        <!-- Open Graph / Social -->
        <meta
            property="og:title"
            content="Wisteria Online - Free MMORPG | Play on Mobile & Desktop"
        />
        <meta
            property="og:description"
            content="Join Wisteria Online, a free MMORPG browser game with dungeons, PvP battles, quests, and endless progression. Play on any device."
        />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://wisteria-online.com" />
        <meta
            property="og:image"
            content="https://wisteria-online.com/og-image.jpg"
        />
        <meta property="og:image:width" content="1200" />
        <meta property="og:image:height" content="630" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="Wisteria Online - Free MMORPG" />
        <meta
            name="twitter:description"
            content="Play Wisteria Online on mobile or desktop. Free MMORPG browser game."
        />
    </Head>
    <div class="fixed inset-0 z-0">
        <img
            src="/loading_screens/Wisteria7.png"
            class="w-full h-full object-cover opacity-30"
        />
    </div>

    <body
        class="bg-gradient-to-b from-[#0a0e27] via-[#1a1f3a] to-[#0a0e27] text-white overflow-x-hidden font-sans"
    >
        <!-- Animated Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_20%_50%,rgba(59,130,246,0.15),transparent_50%)]"
            ></div>
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_80%_80%,rgba(168,85,247,0.15),transparent_50%)]"
            ></div>

            <!-- Floating Orbs -->
            <div
                class="absolute top-20 left-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"
            ></div>
            <div
                class="absolute top-40 right-10 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"
            ></div>
        </div>

        <!-- Navbar -->
        <header
            class="relative z-50 backdrop-blur-lg bg-black/20 border-b border-white/5 top-0"
        >
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6 flex justify-between items-center"
            >
                <div class="flex items-center gap-2 sm:gap-3">
                    <!-- LOGO -->
                    <img
                        src="/logos/wisteriaonline.png"
                        alt="Wisteria Online Logo"
                        class="w-7 h-7 sm:w-9 sm:h-9 object-contain"
                    />

                    <!-- GAME TITLE -->
                    <h1
                        class="text-sm sm:text-lg font-black tracking-widest bg-gradient-to-r from-purple-300 via-pink-300 to-purple-400 bg-clip-text text-transparent"
                    >
                        WISTERIA
                    </h1>
                </div>

                <nav
                    class="hidden md:flex gap-6 lg:gap-8 text-sm font-semibold"
                >
                    <a
                        href="#features"
                        class="text-gray-300 hover:text-purple-300 transition duration-300"
                        >Features</a
                    >
                    <a
                        href="#classes"
                        class="text-gray-300 hover:text-purple-300 transition duration-300"
                        >Classes</a
                    >
                    <a
                        href="#stats"
                        class="text-gray-300 hover:text-purple-300 transition duration-300"
                        >Stats</a
                    >
                    <a
                        href="#community"
                        class="text-gray-300 hover:text-purple-300 transition duration-300"
                        >Community</a
                    >
                </nav>

                <!-- Play Now Button in Navbar -->
                <button
                    class="px-4 sm:px-6 py-1.5 sm:py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold rounded-lg hover:from-purple-400 hover:to-pink-400 transition duration-300 transform hover:scale-105 shadow-lg shadow-purple-500/30 text-xs sm:text-sm"
                >
                    Play Now
                </button>
            </div>
        </header>

        <!-- Hero Section -->
        <section
            class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 text-center pt-16 sm:pt-20"
        >
            <div
                class="max-w-4xl mx-auto space-y-6 sm:space-y-8 animate-fade-in"
            >
                <!-- Mobile/Desktop Badge -->
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 sm:px-4 sm:py-2 rounded-full bg-purple-500/20 border border-purple-500/30 text-xs sm:text-sm text-purple-300 font-medium"
                >
                    <span
                        class="inline-block w-2 h-2 rounded-full bg-green-400 animate-pulse"
                    ></span>
                    Available on Mobile & Desktop
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <p
                        class="text-pink-400 font-bold text-xs sm:text-sm tracking-widest uppercase"
                    >
                        Free MMORPG - No Download Required
                    </p>
                    <h2
                        class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black leading-tight"
                    >
                        <span
                            class="bg-gradient-to-r from-purple-300 via-pink-300 to-purple-400 bg-clip-text text-transparent drop-shadow-lg"
                        >
                            Wisteria Online
                        </span>
                    </h2>
                    <p
                        class="text-sm sm:text-base text-gray-300 mt-4 sm:mt-6 max-w-3xl mx-auto leading-relaxed"
                    >
                        A legendary 2D MMORPG where thousands of players battle,
                        quest, and grind together.
                        <span class="text-purple-400 font-semibold"
                            >Play on your phone or computer</span
                        >
                        — your adventure follows you everywhere.
                    </p>
                </div>

                <!-- Hero Buttons - Big and Clickable -->
                <div
                    class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-6 pt-4 sm:pt-8"
                >
                    <!-- PLAY NOW - Primary CTA -->
                    <button
                        class="group relative px-8 sm:px-12 py-4 sm:py-5 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold text-base sm:text-lg rounded-xl overflow-hidden transform hover:scale-110 transition duration-300 shadow-2xl shadow-purple-500/50 hover:shadow-pink-500/50"
                    >
                        <span class="relative z-10 flex items-center gap-2">
                            ▶ PLAY NOW - FREE
                            <span class="text-xs opacity-70 hidden sm:inline"
                                >(No Download)</span
                            >
                        </span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 opacity-0 group-hover:opacity-100 transition duration-300"
                        ></div>
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl blur-xl opacity-0 group-hover:opacity-30 transition duration-300"
                        ></div>
                    </button>

                    <button
                        class="px-8 sm:px-10 py-4 sm:py-5 border-2 border-purple-400 text-purple-400 font-bold text-base sm:text-lg rounded-xl hover:bg-purple-400 hover:text-black transition duration-300 transform hover:scale-105"
                    >
                        Watch Trailer
                    </button>
                </div>

                <!-- Mobile-Friendly Notice -->
                <div
                    class="pt-4 sm:pt-6 text-xs text-gray-400 flex flex-wrap justify-center gap-4"
                >
                    <span class="flex items-center gap-1"
                        >📱 Mobile Optimized</span
                    >
                    <span class="flex items-center gap-1"
                        >🖥️ Desktop Ready</span
                    >
                    <span class="flex items-center gap-1"
                        >🌐 Browser Based</span
                    >
                    <span class="flex items-center gap-1">⚡ No Download</span>
                </div>

                <!-- Server Status Grid -->
                <div
                    class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-4 pt-6 sm:pt-12 max-w-2xl mx-auto"
                >
                    <div
                        class="bg-gradient-to-br from-purple-600/20 to-pink-800/20 backdrop-blur border border-purple-500/30 p-3 sm:p-4 rounded-lg hover:border-purple-400 transition"
                    >
                        <p class="text-purple-400 text-xs font-bold uppercase">
                            Status
                        </p>
                        <p
                            class="text-base sm:text-lg font-black text-green-300"
                        >
                            ONLINE
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-600/20 to-purple-800/20 backdrop-blur border border-blue-500/30 p-3 sm:p-4 rounded-lg hover:border-blue-400 transition"
                    >
                        <p class="text-blue-400 text-xs font-bold uppercase">
                            Players
                        </p>
                        <p
                            class="text-base sm:text-lg font-black text-blue-300"
                        >
                            12.4K
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-red-600/20 to-orange-800/20 backdrop-blur border border-red-500/30 p-3 sm:p-4 rounded-lg hover:border-red-400 transition"
                    >
                        <p class="text-red-400 text-xs font-bold uppercase">
                            PvP
                        </p>
                        <p class="text-base sm:text-lg font-black text-red-300">
                            ACTIVE
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-600/20 to-teal-800/20 backdrop-blur border border-green-500/30 p-3 sm:p-4 rounded-lg hover:border-green-400 transition"
                    >
                        <p class="text-green-400 text-xs font-bold uppercase">
                            Dungeons
                        </p>
                        <p
                            class="text-base sm:text-lg font-black text-green-300"
                        >
                            42
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section
            id="features"
            class="relative z-10 py-24 sm:py-32 px-4 sm:px-6 bg-gradient-to-b from-transparent via-black/20 to-transparent"
        >
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12 sm:mb-20">
                    <p
                        class="text-pink-400 font-bold text-sm sm:text-lg uppercase tracking-widest mb-3 sm:mb-4"
                    >
                        Why Play Wisteria Online?
                    </p>
                    <h3
                        class="text-2xl sm:text-3xl font-black mb-4 sm:mb-6 bg-gradient-to-r from-purple-300 to-pink-300 bg-clip-text text-transparent"
                    >
                        Epic Features for Every Device
                    </h3>
                    <p class="text-sm text-gray-400 max-w-2xl mx-auto px-4">
                        Whether you're on your phone or computer, enjoy a
                        seamless MMORPG experience with cross-platform
                        progression.
                    </p>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Feature 1 -->
                    <div
                        class="group relative bg-gradient-to-br from-purple-900/40 to-slate-900/40 backdrop-blur border border-purple-500/30 p-6 sm:p-8 rounded-2xl hover:border-purple-400 transition duration-300 hover:transform hover:scale-105"
                    >
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">📱</div>
                        <h4
                            class="text-base sm:text-lg font-bold text-purple-300 mb-2"
                        >
                            Mobile First
                        </h4>
                        <p
                            class="text-xs sm:text-sm text-gray-300 leading-relaxed"
                        >
                            Fully optimized for touch controls. Play on your
                            phone with ease.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="group relative bg-gradient-to-br from-blue-900/40 to-slate-900/40 backdrop-blur border border-blue-500/30 p-6 sm:p-8 rounded-2xl hover:border-blue-400 transition duration-300 hover:transform hover:scale-105"
                    >
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">🖥️</div>
                        <h4
                            class="text-base sm:text-lg font-bold text-blue-300 mb-2"
                        >
                            Desktop Ready
                        </h4>
                        <p
                            class="text-xs sm:text-sm text-gray-300 leading-relaxed"
                        >
                            Keyboard and mouse support for PC players. Full
                            screen experience.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="group relative bg-gradient-to-br from-green-900/40 to-slate-900/40 backdrop-blur border border-green-500/30 p-6 sm:p-8 rounded-2xl hover:border-green-400 transition duration-300 hover:transform hover:scale-105"
                    >
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">🌐</div>
                        <h4
                            class="text-base sm:text-lg font-bold text-green-300 mb-2"
                        >
                            Cross-Platform
                        </h4>
                        <p
                            class="text-xs sm:text-sm text-gray-300 leading-relaxed"
                        >
                            Switch between devices. Your progress syncs
                            automatically.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div
                        class="group relative bg-gradient-to-br from-yellow-900/40 to-slate-900/40 backdrop-blur border border-yellow-500/30 p-6 sm:p-8 rounded-2xl hover:border-yellow-400 transition duration-300 hover:transform hover:scale-105"
                    >
                        <div class="text-3xl sm:text-4xl mb-3 sm:mb-4">⚡</div>
                        <h4
                            class="text-base sm:text-lg font-bold text-yellow-300 mb-2"
                        >
                            No Download
                        </h4>
                        <p
                            class="text-xs sm:text-sm text-gray-300 leading-relaxed"
                        >
                            Play instantly in your browser. No installation
                            required.
                        </p>
                    </div>
                </div>

                <!-- Mobile/Desktop Toggle Visual -->
                <div
                    class="mt-12 sm:mt-16 p-4 sm:p-8 bg-gradient-to-br from-purple-900/30 to-pink-900/30 backdrop-blur border border-purple-500/30 rounded-2xl max-w-3xl mx-auto"
                >
                    <div
                        class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-8"
                    >
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">📱</span>
                            <span class="text-sm font-medium text-gray-300"
                                >Mobile</span
                            >
                        </div>
                        <div
                            class="w-16 sm:w-24 h-1 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full"
                        ></div>
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">🖥️</span>
                            <span class="text-sm font-medium text-gray-300"
                                >Desktop</span
                            >
                        </div>
                        <div class="text-xs text-gray-400 mt-2 sm:mt-0">
                            ✓ Syncs automatically
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Classes Section -->
        <section id="classes" class="relative z-10 py-24 sm:py-32 px-4 sm:px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12 sm:mb-20">
                    <p
                        class="text-pink-400 font-bold text-sm sm:text-lg uppercase tracking-widest mb-3 sm:mb-4"
                    >
                        Choose Your Path
                    </p>
                    <h3
                        class="text-2xl sm:text-3xl font-black mb-4 sm:mb-6 bg-gradient-to-r from-purple-300 to-pink-300 bg-clip-text text-transparent"
                    >
                        Five Legendary Classes
                    </h3>
                    <p class="text-sm text-gray-400 max-w-2xl mx-auto px-4">
                        Master unique abilities in combat. Each class offers
                        distinct gameplay and progression.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6"
                >
                    <!-- Knight -->
                    <div
                        @mouseenter="hoveredClass = 'knight'"
                        @mouseleave="hoveredClass = null"
                        class="group relative"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 blur"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-blue-950/60 to-blue-900/40 backdrop-blur border border-blue-500/30 p-6 sm:p-8 rounded-xl hover:border-blue-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-2xl sm:text-3xl mb-3 sm:mb-4">🛡️</p>
                            <h4
                                class="text-base sm:text-lg font-black text-blue-300 mb-2 sm:mb-3"
                            >
                                Knight
                            </h4>
                            <ul class="space-y-1 text-xs text-gray-400">
                                <li>✓ High HP & Defense</li>
                                <li>✓ Melee Weapons</li>
                                <li>✓ Aggro Control</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Crusader -->
                    <div
                        @mouseenter="hoveredClass = 'crusader'"
                        @mouseleave="hoveredClass = null"
                        class="group relative"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 blur"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-yellow-950/60 to-orange-900/40 backdrop-blur border border-yellow-500/30 p-6 sm:p-8 rounded-xl hover:border-yellow-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-2xl sm:text-3xl mb-3 sm:mb-4">⚡</p>
                            <h4
                                class="text-base sm:text-lg font-black text-yellow-300 mb-2 sm:mb-3"
                            >
                                Crusader
                            </h4>
                            <ul class="space-y-1 text-xs text-gray-400">
                                <li>✓ Balanced Stats</li>
                                <li>✓ Holy Magic</li>
                                <li>✓ Healing</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Archer -->
                    <div
                        @mouseenter="hoveredClass = 'archer'"
                        @mouseleave="hoveredClass = null"
                        class="group relative"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 blur"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-green-950/60 to-emerald-900/40 backdrop-blur border border-green-500/30 p-6 sm:p-8 rounded-xl hover:border-green-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-2xl sm:text-3xl mb-3 sm:mb-4">🏹</p>
                            <h4
                                class="text-base sm:text-lg font-black text-green-300 mb-2 sm:mb-3"
                            >
                                Archer
                            </h4>
                            <ul class="space-y-1 text-xs text-gray-400">
                                <li>✓ High Critical Rate</li>
                                <li>✓ Range Attacks</li>
                                <li>✓ Evasion</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Wizard -->
                    <div
                        @mouseenter="hoveredClass = 'wizard'"
                        @mouseleave="hoveredClass = null"
                        class="group relative"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-600 to-violet-600 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 blur"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-purple-950/60 to-violet-900/40 backdrop-blur border border-purple-500/30 p-6 sm:p-8 rounded-xl hover:border-purple-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-2xl sm:text-3xl mb-3 sm:mb-4">🔮</p>
                            <h4
                                class="text-base sm:text-lg font-black text-purple-300 mb-2 sm:mb-3"
                            >
                                Wizard
                            </h4>
                            <ul class="space-y-1 text-xs text-gray-400">
                                <li>✓ AoE Damage</li>
                                <li>✓ Elemental Magic</li>
                                <li>✓ Crowd Control</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Assassin -->
                    <div
                        @mouseenter="hoveredClass = 'assassin'"
                        @mouseleave="hoveredClass = null"
                        class="group relative"
                    >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-red-600 to-pink-600 rounded-xl opacity-0 group-hover:opacity-100 transition duration-300 blur"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-red-950/60 to-pink-900/40 backdrop-blur border border-red-500/30 p-6 sm:p-8 rounded-xl hover:border-red-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-2xl sm:text-3xl mb-3 sm:mb-4">🗡️</p>
                            <h4
                                class="text-base sm:text-lg font-black text-red-300 mb-2 sm:mb-3"
                            >
                                Assassin
                            </h4>
                            <ul class="space-y-1 text-xs text-gray-400">
                                <li>✓ Stealth Mechanic</li>
                                <li>✓ Burst Damage</li>
                                <li>✓ High Mobility</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section - Big "Play Now" -->
        <section class="relative z-10 py-24 sm:py-32 px-4 sm:px-6">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="bg-gradient-to-r from-purple-600/30 to-pink-600/30 backdrop-blur border border-purple-500/50 rounded-3xl p-8 sm:p-16"
                >
                    <h2
                        class="text-2xl sm:text-3xl font-black mb-4 sm:mb-6 bg-gradient-to-r from-purple-300 to-pink-300 bg-clip-text text-transparent"
                    >
                        Ready to Begin Your Adventure?
                    </h2>
                    <p
                        class="text-sm text-gray-300 mb-6 sm:mb-10 max-w-2xl mx-auto leading-relaxed px-4"
                    >
                        Join thousands of players in the most immersive 2D
                        MMORPG experience.
                        <span class="text-purple-400 font-semibold"
                            >Play on any device</span
                        >
                        — mobile, tablet, or desktop.
                    </p>
                    <button
                        class="group relative px-10 sm:px-14 py-5 sm:py-6 bg-gradient-to-r from-purple-500 to-pink-500 text-white font-bold text-base sm:text-lg rounded-xl overflow-hidden transform hover:scale-110 transition duration-300 shadow-2xl shadow-purple-500/50 hover:shadow-pink-500/50"
                    >
                        <span class="relative z-10 text-xl sm:text-2xl"
                            >▶ PLAY NOW</span
                        >
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-400 to-pink-400 opacity-0 group-hover:opacity-100 transition duration-300"
                        ></div>
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl blur-xl opacity-0 group-hover:opacity-30 transition duration-300"
                        ></div>
                    </button>
                    <p class="mt-4 text-xs text-gray-400">
                        Free to play · No download · Mobile & desktop
                    </p>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section id="stats" class="relative z-10 py-24 sm:py-32 px-4 sm:px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12 sm:mb-20">
                    <h3
                        class="text-2xl sm:text-3xl font-black mb-4 sm:mb-6 bg-gradient-to-r from-purple-300 to-pink-300 bg-clip-text text-transparent"
                    >
                        Wisteria by the Numbers
                    </h3>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-6">
                    <div
                        class="bg-gradient-to-br from-purple-600/20 to-pink-800/20 backdrop-blur border border-purple-500/30 p-6 sm:p-10 rounded-2xl text-center hover:border-purple-400 transition"
                    >
                        <p
                            class="text-2xl sm:text-3xl font-black text-purple-300 mb-2 sm:mb-3"
                        >
                            12.4K
                        </p>
                        <p
                            class="text-xs sm:text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Active Players
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-600/20 to-purple-800/20 backdrop-blur border border-blue-500/30 p-6 sm:p-10 rounded-2xl text-center hover:border-blue-400 transition"
                    >
                        <p
                            class="text-2xl sm:text-3xl font-black text-blue-300 mb-2 sm:mb-3"
                        >
                            500+
                        </p>
                        <p
                            class="text-xs sm:text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Quests
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-red-600/20 to-orange-800/20 backdrop-blur border border-red-500/30 p-6 sm:p-10 rounded-2xl text-center hover:border-red-400 transition"
                    >
                        <p
                            class="text-2xl sm:text-3xl font-black text-red-300 mb-2 sm:mb-3"
                        >
                            42
                        </p>
                        <p
                            class="text-xs sm:text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Dungeons
                        </p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-600/20 to-teal-800/20 backdrop-blur border border-green-500/30 p-6 sm:p-10 rounded-2xl text-center hover:border-green-400 transition"
                    >
                        <p
                            class="text-2xl sm:text-3xl font-black text-green-300 mb-2 sm:mb-3"
                        >
                            300+
                        </p>
                        <p
                            class="text-xs sm:text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Levels
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer
            class="relative z-10 border-t border-white/5 bg-black/30 backdrop-blur py-12 sm:py-16 px-4 sm:px-6"
        >
            <div class="max-w-7xl mx-auto">
                <div
                    class="grid grid-cols-2 sm:grid-cols-4 gap-8 sm:gap-12 mb-8 sm:mb-12"
                >
                    <div>
                        <h3
                            class="text-base sm:text-lg font-black text-purple-300 mb-4 sm:mb-6"
                        >
                            WISTERIA
                        </h3>
                        <p
                            class="text-xs sm:text-sm text-gray-400 leading-relaxed"
                        >
                            The most immersive 2D MMORPG experience. Play on
                            mobile or desktop.
                        </p>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm mb-3 sm:mb-4">
                            GAME
                        </p>
                        <ul
                            class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-gray-400"
                        >
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Classes</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Features</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Play Now</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm mb-3 sm:mb-4">
                            COMMUNITY
                        </p>
                        <ul
                            class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-gray-400"
                        >
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Forum</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Discord</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Support</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold text-white text-sm mb-3 sm:mb-4">
                            SOCIAL
                        </p>
                        <ul
                            class="space-y-1 sm:space-y-2 text-xs sm:text-sm text-gray-400"
                        >
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Twitter</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >YouTube</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-purple-300 transition"
                                    >Discord</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-white/5 pt-6 sm:pt-8 text-center text-gray-500 text-xs sm:text-sm"
                >
                    <p>
                        © 2026 Wisteria Online. All rights reserved. | Play on
                        Mobile & Desktop
                    </p>
                </div>
            </div>
        </footer>
    </body>
</template>

<style scoped>
@keyframes blob {
    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

/* Mobile-specific adjustments */
@media (max-width: 640px) {
    .animate-fade-in {
        animation-duration: 0.6s;
    }
}
</style>
