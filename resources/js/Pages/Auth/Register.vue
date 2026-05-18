<script setup>
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
    class: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const classes = [
    {
        name: "Knight",
        icon: "⚔️",
        desc: "Frontline tank with high defense",
    },
    {
        name: "Archer",
        icon: "🏹",
        desc: "Long range physical damage",
    },
    {
        name: "Wizard",
        icon: "🔮",
        desc: "Master of elemental magic",
    },
    {
        name: "Assassin",
        icon: "🗡️",
        desc: "Fast burst damage killer",
    },
    {
        name: "Crusader",
        icon: "✨",
        desc: "Holy warrior with support power",
    },
];
</script>

<template>
    <Head title="Create Adventurer" />

    <div
        class="min-h-screen flex items-center justify-center bg-[#0b1220] relative overflow-hidden py-10"
    >
        <!-- BACKGROUND -->
        <div
            class="absolute inset-0 bg-[url('/images/login-bg.jpg')] bg-cover bg-center opacity-30"
        ></div>

        <!-- DARK OVERLAY -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-[2px]"></div>

        <!-- GLOW -->
        <div
            class="absolute w-[800px] h-[800px] bg-cyan-500/10 blur-3xl rounded-full"
        ></div>

        <!-- CARD -->
        <div
            class="relative z-10 w-full max-w-2xl border border-white/10 bg-black/50 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/70 overflow-hidden"
        >
            <!-- HEADER -->
            <div
                class="px-8 py-7 border-b border-white/10 bg-gradient-to-r from-blue-500/10 to-cyan-500/10"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-3xl shadow-lg"
                    >
                        ⚔️
                    </div>

                    <div>
                        <h1
                            class="text-3xl font-black text-white tracking-wide"
                        >
                            WIS ONLINE
                        </h1>

                        <p class="text-sm text-blue-200/70">
                            Create your legendary adventurer
                        </p>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="p-8">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- CHARACTER NAME -->
                    <div>
                        <label
                            class="block text-xs uppercase tracking-widest text-gray-400 mb-2"
                        >
                            Character Name
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            required
                            autofocus
                            autocomplete="name"
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder:text-gray-500 focus:border-blue-400/50 focus:ring-0 outline-none transition"
                            placeholder="Enter character name"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- CLASS SELECTION -->
                    <div>
                        <label
                            class="block text-xs uppercase tracking-widest text-gray-400 mb-3"
                        >
                            Choose Your Class
                        </label>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <button
                                v-for="c in classes"
                                :key="c.name"
                                type="button"
                                @click="form.class = c.name"
                                class="rounded-2xl border p-4 text-left transition"
                                :class="
                                    form.class === c.name
                                        ? 'border-cyan-400 bg-cyan-500/10 shadow-lg shadow-cyan-500/10'
                                        : 'border-white/10 bg-black/30 hover:bg-white/5'
                                "
                            >
                                <div class="text-3xl mb-2">
                                    {{ c.icon }}
                                </div>

                                <p class="text-white font-bold">
                                    {{ c.name }}
                                </p>

                                <p class="text-xs text-gray-400 mt-1">
                                    {{ c.desc }}
                                </p>
                            </button>
                        </div>

                        <InputError class="mt-2" :message="form.errors.class" />
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label
                            class="block text-xs uppercase tracking-widest text-gray-400 mb-2"
                        >
                            Adventurer Email
                        </label>

                        <input
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="username"
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder:text-gray-500 focus:border-blue-400/50 focus:ring-0 outline-none transition"
                            placeholder="Enter your email"
                        />

                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- PASSWORD -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label
                                class="block text-xs uppercase tracking-widest text-gray-400 mb-2"
                            >
                                Password
                            </label>

                            <input
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder:text-gray-500 focus:border-blue-400/50 focus:ring-0 outline-none transition"
                                placeholder="Create password"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.password"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs uppercase tracking-widest text-gray-400 mb-2"
                            >
                                Confirm Password
                            </label>

                            <input
                                v-model="form.password_confirmation"
                                type="password"
                                required
                                autocomplete="new-password"
                                class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder:text-gray-500 focus:border-blue-400/50 focus:ring-0 outline-none transition"
                                placeholder="Confirm password"
                            />

                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>
                    </div>

                    <!-- ACTIONS -->
                    <div
                        class="flex flex-col md:flex-row items-center justify-between gap-4 pt-2"
                    >
                        <Link
                            :href="route('login')"
                            class="text-sm text-blue-300 hover:text-blue-200 transition"
                        >
                            Already have an account?
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold tracking-wide shadow-lg shadow-blue-500/20 hover:scale-[1.02] hover:shadow-blue-500/40 active:scale-[0.99] transition disabled:opacity-50"
                        >
                            <span v-if="form.processing">
                                Creating Adventurer...
                            </span>

                            <span v-else> Enter The World </span>
                        </button>
                    </div>
                </form>

                <!-- FOOTER -->
                <div
                    class="mt-8 pt-5 border-t border-white/10 text-center text-xs text-gray-500"
                >
                    Begin your journey through dungeons, guild wars, legendary
                    raids, and mythical treasures.
                </div>
            </div>
        </div>
    </div>
</template>
