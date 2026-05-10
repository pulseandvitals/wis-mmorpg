<script setup>
import { Head, Link } from "@inertiajs/vue3";
import { ref } from "vue";

const hoveredClass = ref(null);
</script>

<template>
    <Head title="Elfaria - Epic MMORPG Adventure" />

    <body
        class="bg-gradient-to-b from-[#0a0e27] via-[#1a1f3a] to-[#0a0e27] text-white overflow-x-hidden"
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
            class="relative z-50 backdrop-blur-lg bg-black/20 border-b border-white/5 sticky top-0"
        >
            <div
                class="max-w-7xl mx-auto px-6 py-6 flex justify-between items-center"
            >
                <div class="flex items-center gap-3">
                    <div class="text-xl">⚔️</div>
                    <h1
                        class="text-sm font-black tracking-widest bg-gradient-to-r from-yellow-300 via-yellow-400 to-yellow-500 bg-clip-text text-transparent"
                    >
                        ELFARIA
                    </h1>
                </div>

                <nav class="hidden md:flex gap-8 text-sm font-semibold">
                    <a
                        href="#classes"
                        class="text-gray-300 hover:text-yellow-300 transition duration-300"
                        >Classes</a
                    >
                    <a
                        href="#features"
                        class="text-gray-300 hover:text-yellow-300 transition duration-300"
                        >Features</a
                    >
                    <a
                        href="#stats"
                        class="text-gray-300 hover:text-yellow-300 transition duration-300"
                        >Statistics</a
                    >
                    <a
                        href="#"
                        class="text-gray-300 hover:text-yellow-300 transition duration-300"
                        >Community</a
                    >
                </nav>

                <button
                    class="px-6 py-2 bg-yellow-500 text-black font-bold rounded-lg hover:bg-yellow-400 transition duration-300 transform hover:scale-105"
                >
                    Play Now
                </button>
            </div>
        </header>

        <!-- Hero Section -->
        <section
            class="relative z-10 min-h-screen flex flex-col justify-center items-center px-6 text-center pt-20"
        >
            <div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
                <div class="space-y-4">
                    <p
                        class="text-yellow-400 font-bold text-base tracking-widest uppercase"
                    >
                        Experience the Ultimate Fantasy
                    </p>
                    <h2
                        class="text-3xl md:text-4xl lg:text-5xl font-black leading-tight"
                    >
                        <span
                            class="bg-gradient-to-r from-yellow-300 via-yellow-400 to-orange-400 bg-clip-text text-transparent drop-shadow-lg"
                        >
                            Enter Elfaria
                        </span>
                    </h2>
                    <p
                        class="text-sm md:text-base text-gray-300 mt-6 max-w-3xl mx-auto leading-relaxed"
                    >
                        A legendary 2D MMORPG where thousands of players battle,
                        quest, and grind together in a world of endless
                        adventure. Become a legend. Become immortal.
                    </p>
                </div>

                <!-- Hero Buttons -->
                <div
                    class="flex flex-col md:flex-row justify-center gap-6 pt-8"
                >
                    <button
                        class="group relative px-10 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-black font-bold text-base rounded-lg overflow-hidden transform hover:scale-110 transition duration-300 shadow-lg shadow-yellow-500/50"
                    >
                        <span class="relative z-10">START YOUR JOURNEY</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-yellow-300 to-yellow-600 opacity-0 group-hover:opacity-100 transition duration-300"
                        ></div>
                    </button>

                    <button
                        class="px-10 py-4 border-2 border-yellow-400 text-yellow-400 font-bold text-base rounded-lg hover:bg-yellow-400 hover:text-black transition duration-300 transform hover:scale-105"
                    >
                        WATCH CINEMATIC
                    </button>
                </div>

                <!-- Server Status -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-12 max-w-2xl mx-auto"
                >
                    <div
                        class="bg-gradient-to-br from-blue-600/20 to-blue-800/20 backdrop-blur border border-blue-500/30 p-4 rounded-lg hover:border-blue-400 transition"
                    >
                        <p class="text-blue-400 text-sm font-bold uppercase">
                            Status
                        </p>
                        <p class="text-lg font-black text-blue-300">ONLINE</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-purple-600/20 to-purple-800/20 backdrop-blur border border-purple-500/30 p-4 rounded-lg hover:border-purple-400 transition"
                    >
                        <p class="text-purple-400 text-sm font-bold uppercase">
                            Players
                        </p>
                        <p class="text-lg font-black text-purple-300">12.4K</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-red-600/20 to-red-800/20 backdrop-blur border border-red-500/30 p-4 rounded-lg hover:border-red-400 transition"
                    >
                        <p class="text-red-400 text-sm font-bold uppercase">
                            PvP
                        </p>
                        <p class="text-lg font-black text-red-300">ACTIVE</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-600/20 to-green-800/20 backdrop-blur border border-green-500/30 p-4 rounded-lg hover:border-green-400 transition"
                    >
                        <p class="text-green-400 text-sm font-bold uppercase">
                            Dungeons
                        </p>
                        <p class="text-lg font-black text-green-300">42</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Classes Section -->
        <section id="classes" class="relative z-10 py-32 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <p
                        class="text-yellow-400 font-bold text-lg uppercase tracking-widest mb-4"
                    >
                        CHOOSE YOUR DESTINY
                    </p>
                    <h3
                        class="text-2xl md:text-3xl font-black mb-6 bg-gradient-to-r from-yellow-300 to-yellow-500 bg-clip-text text-transparent"
                    >
                        Five Legendary Classes
                    </h3>
                    <p class="text-sm text-gray-400 max-w-2xl mx-auto">
                        Select your class and master its unique abilities in
                        combat. Each path offers distinct gameplay and
                        progression.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-6">
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
                            class="relative bg-gradient-to-br from-blue-950/60 to-blue-900/40 backdrop-blur border border-blue-500/30 p-8 rounded-xl hover:border-blue-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-3xl mb-4">🛡️</p>
                            <h4 class="text-lg font-black text-blue-300 mb-3">
                                Knight
                            </h4>
                            <p
                                class="text-gray-300 text-sm mb-6 leading-relaxed"
                            >
                                Master melee combat and heavy armor. Become the
                                unbreakable wall of your party with devastating
                                shield bash abilities.
                            </p>
                            <ul class="space-y-2 text-xs text-gray-400">
                                <li>✓ High HP & Defense</li>
                                <li>✓ Melee Weapons</li>
                                <li>✓ Aggro Control</li>
                                <li>✓ Shield Mastery</li>
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
                            class="relative bg-gradient-to-br from-yellow-950/60 to-orange-900/40 backdrop-blur border border-yellow-500/30 p-8 rounded-xl hover:border-yellow-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-3xl mb-4">⚡</p>
                            <h4 class="text-lg font-black text-yellow-300 mb-3">
                                Crusader
                            </h4>
                            <p
                                class="text-gray-300 text-sm mb-6 leading-relaxed"
                            >
                                Holy warrior with balanced offense and defense.
                                Combine divine magic with sword skills for
                                ultimate versatility.
                            </p>
                            <ul class="space-y-2 text-xs text-gray-400">
                                <li>✓ Balanced Stats</li>
                                <li>✓ Holy Magic</li>
                                <li>✓ Healing</li>
                                <li>✓ Heavy Armor</li>
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
                            class="relative bg-gradient-to-br from-green-950/60 to-emerald-900/40 backdrop-blur border border-green-500/30 p-8 rounded-xl hover:border-green-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-3xl mb-4">🏹</p>
                            <h4 class="text-lg font-black text-green-300 mb-3">
                                Archer
                            </h4>
                            <p
                                class="text-gray-300 text-sm mb-6 leading-relaxed"
                            >
                                Swift and deadly ranged combatant. Rain arrows
                                upon enemies from afar with critical strike
                                precision.
                            </p>
                            <ul class="space-y-2 text-xs text-gray-400">
                                <li>✓ High Critical Rate</li>
                                <li>✓ Range Attacks</li>
                                <li>✓ Evasion</li>
                                <li>✓ Poison Arrows</li>
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
                            class="relative bg-gradient-to-br from-purple-950/60 to-violet-900/40 backdrop-blur border border-purple-500/30 p-8 rounded-xl hover:border-purple-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-3xl mb-4">🔮</p>
                            <h4 class="text-lg font-black text-purple-300 mb-3">
                                Wizard
                            </h4>
                            <p
                                class="text-gray-300 text-sm mb-6 leading-relaxed"
                            >
                                Master of arcane magic. Unleash devastating
                                spells and area-of-effect attacks to dominate
                                the battlefield.
                            </p>
                            <ul class="space-y-2 text-xs text-gray-400">
                                <li>✓ AoE Damage</li>
                                <li>✓ Elemental Magic</li>
                                <li>✓ Crowd Control</li>
                                <li>✓ High Mana Pool</li>
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
                            class="relative bg-gradient-to-br from-red-950/60 to-pink-900/40 backdrop-blur border border-red-500/30 p-8 rounded-xl hover:border-red-400 transition duration-300 transform group-hover:scale-105"
                        >
                            <p class="text-3xl mb-4">🗡️</p>
                            <h4 class="text-lg font-black text-red-300 mb-3">
                                Assassin
                            </h4>
                            <p
                                class="text-gray-300 text-sm mb-6 leading-relaxed"
                            >
                                Silent killer with extreme burst damage. Strike
                                from shadows and eliminate enemies before they
                                see you.
                            </p>
                            <ul class="space-y-2 text-xs text-gray-400">
                                <li>✓ Stealth Mechanic</li>
                                <li>✓ Burst Damage</li>
                                <li>✓ High Mobility</li>
                                <li>✓ Backstab</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section
            id="features"
            class="relative z-10 py-32 px-6 bg-gradient-to-b from-transparent via-black/20 to-transparent"
        >
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <p
                        class="text-yellow-400 font-bold text-lg uppercase tracking-widest mb-4"
                    >
                        ENDLESS POSSIBILITIES
                    </p>
                    <h3
                        class="text-2xl md:text-3xl font-black mb-6 bg-gradient-to-r from-yellow-300 to-yellow-500 bg-clip-text text-transparent"
                    >
                        Epic Gameplay Features
                    </h3>
                    <p class="text-sm text-gray-400 max-w-2xl mx-auto">
                        Explore a rich world filled with combat, challenges, and
                        legendary rewards.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- PvE -->
                    <div class="group relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl opacity-0 group-hover:opacity-20 transition duration-300 blur-xl"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-slate-900/80 to-slate-800/80 backdrop-blur border border-blue-500/30 p-10 rounded-2xl hover:border-blue-400 transition duration-300"
                        >
                            <div
                                class="text-4xl mb-6 transform group-hover:scale-125 transition duration-300"
                            >
                                🐉
                            </div>
                            <h4 class="text-lg font-black text-blue-300 mb-4">
                                PvE Dungeons
                            </h4>
                            <p class="text-gray-300 mb-6 leading-relaxed">
                                Conquer treacherous dungeons filled with
                                fearsome monsters and bosses. Form parties,
                                coordinate strategies, and claim legendary loot.
                                Over 42 unique dungeons await.
                            </p>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>✦ Boss Raids with Epic Rewards</li>
                                <li>✦ Cooperative Gameplay</li>
                                <li>✦ 4-8 Player Instances</li>
                                <li>✦ Weekly Challenges</li>
                            </ul>
                        </div>
                    </div>

                    <!-- PvP -->
                    <div class="group relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-red-600 to-red-800 rounded-2xl opacity-0 group-hover:opacity-20 transition duration-300 blur-xl"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-slate-900/80 to-slate-800/80 backdrop-blur border border-red-500/30 p-10 rounded-2xl hover:border-red-400 transition duration-300"
                        >
                            <div
                                class="text-4xl mb-6 transform group-hover:scale-125 transition duration-300"
                            >
                                ⚔️
                            </div>
                            <h4 class="text-lg font-black text-red-300 mb-4">
                                PvP Battles
                            </h4>
                            <p class="text-gray-300 mb-6 leading-relaxed">
                                Test your skills against real players in intense
                                1v1 duels, massive faction wars, and ranked
                                arenas. Climb the rankings and dominate the
                                leaderboards.
                            </p>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>✦ Ranked Matchmaking System</li>
                                <li>✦ Territory Wars</li>
                                <li>✦ Guild Battles</li>
                                <li>✦ Tournament Events</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Questing -->
                    <div class="group relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-yellow-600 to-yellow-800 rounded-2xl opacity-0 group-hover:opacity-20 transition duration-300 blur-xl"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-slate-900/80 to-slate-800/80 backdrop-blur border border-yellow-500/30 p-10 rounded-2xl hover:border-yellow-400 transition duration-300"
                        >
                            <div
                                class="text-4xl mb-6 transform group-hover:scale-125 transition duration-300"
                            >
                                📜
                            </div>
                            <h4 class="text-lg font-black text-yellow-300 mb-4">
                                Epic Quests
                            </h4>
                            <p class="text-gray-300 mb-6 leading-relaxed">
                                Embark on hundreds of quests across a sprawling
                                world. Complete legendary story arcs, daily
                                challenges, and seasonal events for incredible
                                rewards.
                            </p>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>✦ 500+ Unique Quests</li>
                                <li>✦ Dynamic Quest System</li>
                                <li>✦ Seasonal Events</li>
                                <li>✦ Story Progression</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Grinding -->
                    <div class="group relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl opacity-0 group-hover:opacity-20 transition duration-300 blur-xl"
                        ></div>
                        <div
                            class="relative bg-gradient-to-br from-slate-900/80 to-slate-800/80 backdrop-blur border border-purple-500/30 p-10 rounded-2xl hover:border-purple-400 transition duration-300"
                        >
                            <div
                                class="text-4xl mb-6 transform group-hover:scale-125 transition duration-300"
                            >
                                💎
                            </div>
                            <h4 class="text-lg font-black text-purple-300 mb-4">
                                Endless Grinding
                            </h4>
                            <p class="text-gray-300 mb-6 leading-relaxed">
                                Continuously improve your character through
                                farming and leveling. Unlock new abilities,
                                enhance gear, and progress through multiple
                                prestige tiers.
                            </p>
                            <ul class="space-y-2 text-sm text-gray-400">
                                <li>✦ 300+ Levels to Master</li>
                                <li>✦ Prestige System</li>
                                <li>✦ Skill Enhancement Trees</li>
                                <li>✦ Infinite Progression</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section id="stats" class="relative z-10 py-32 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-20">
                    <p
                        class="text-yellow-400 font-bold text-lg uppercase tracking-widest mb-4"
                    >
                        BY THE NUMBERS
                    </p>
                    <h3
                        class="text-2xl md:text-3xl font-black mb-6 bg-gradient-to-r from-yellow-300 to-yellow-500 bg-clip-text text-transparent"
                    >
                        Elfaria in Numbers
                    </h3>
                </div>

                <div class="grid md:grid-cols-4 gap-6">
                    <div
                        class="bg-gradient-to-br from-blue-600/20 to-blue-800/20 backdrop-blur border border-blue-500/30 p-10 rounded-2xl text-center hover:border-blue-400 transition"
                    >
                        <p class="text-3xl font-black text-blue-300 mb-3">
                            12.4K
                        </p>
                        <p
                            class="text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Active Players
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-purple-600/20 to-purple-800/20 backdrop-blur border border-purple-500/30 p-10 rounded-2xl text-center hover:border-purple-400 transition"
                    >
                        <p class="text-3xl font-black text-purple-300 mb-3">
                            500+
                        </p>
                        <p
                            class="text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Quests
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-red-600/20 to-red-800/20 backdrop-blur border border-red-500/30 p-10 rounded-2xl text-center hover:border-red-400 transition"
                    >
                        <p class="text-3xl font-black text-red-300 mb-3">42</p>
                        <p
                            class="text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Dungeons
                        </p>
                    </div>

                    <div
                        class="bg-gradient-to-br from-green-600/20 to-green-800/20 backdrop-blur border border-green-500/30 p-10 rounded-2xl text-center hover:border-green-400 transition"
                    >
                        <p class="text-3xl font-black text-green-300 mb-3">
                            300+
                        </p>
                        <p
                            class="text-sm text-gray-300 font-bold uppercase tracking-widest"
                        >
                            Levels
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="relative z-10 py-32 px-6">
            <div class="max-w-4xl mx-auto text-center">
                <div
                    class="bg-gradient-to-r from-yellow-600/30 to-orange-600/30 backdrop-blur border border-yellow-500/50 rounded-3xl p-16"
                >
                    <h2
                        class="text-2xl md:text-3xl font-black mb-6 bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent"
                    >
                        Ready for Adventure?
                    </h2>
                    <p
                        class="text-sm text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed"
                    >
                        Join thousands of players in the most immersive 2D
                        MMORPG experience. Become a legend today.
                    </p>
                    <button
                        class="group relative px-12 py-5 bg-gradient-to-r from-yellow-400 to-orange-400 text-black font-bold text-base rounded-xl overflow-hidden transform hover:scale-110 transition duration-300 shadow-2xl shadow-yellow-500/50"
                    >
                        <span class="relative z-10">PLAY NOW - FREE</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-yellow-300 to-orange-500 opacity-0 group-hover:opacity-100 transition duration-300"
                        ></div>
                    </button>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer
            class="relative z-10 border-t border-white/5 bg-black/30 backdrop-blur py-16 px-6"
        >
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-4 gap-12 mb-12">
                    <div>
                        <h3 class="text-lg font-black text-yellow-300 mb-6">
                            ELFARIA
                        </h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            The most immersive 2D MMORPG experience. Battle,
                            quest, and grind your way to legends.
                        </p>
                    </div>
                    <div>
                        <p class="font-bold text-white mb-4">GAME</p>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Classes</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Features</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Download</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold text-white mb-4">COMMUNITY</p>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Forum</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Discord</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Support</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <p class="font-bold text-white mb-4">SOCIAL</p>
                        <ul class="space-y-2 text-sm text-gray-400">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Twitter</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >YouTube</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-yellow-300 transition"
                                    >Instagram</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>

                <div
                    class="border-t border-white/5 pt-8 text-center text-gray-500 text-sm"
                >
                    <p>
                        © 2026 Elfaria MMORPG. All rights reserved. | Terms of
                        Service | Privacy Policy
                    </p>
                </div>
            </div>
        </footer>
    </body>
</template>

<style scoped>
body {
    font-family: "Press Start 2P", cursive;
    letter-spacing: 2px;
    font-size: 2px;
}
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

@keyframes float {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes glow-pulse {
    0%,
    100% {
        text-shadow: 0 0 10px rgba(250, 204, 21, 0.5);
    }
    50% {
        text-shadow:
            0 0 20px rgba(250, 204, 21, 0.8),
            0 0 30px rgba(250, 204, 21, 0.5);
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

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.glow-text {
    animation: glow-pulse 3s ease-in-out infinite;
}
</style>
