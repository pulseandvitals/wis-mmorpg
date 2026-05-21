<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};
</script>

<template>
    <Head title="Enter Wis Online" />

    <div
        class="min-h-screen flex items-center justify-center bg-[#0b1220] relative overflow-hidden"
    >
        <!-- BACKGROUND -->
        <div
            class="absolute inset-0 bg-[url('/images/login-bg.jpg')] bg-cover bg-center opacity-30"
        ></div>

        <!-- DARK OVERLAY -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-[2px]"></div>

        <!-- GLOW -->
        <div
            class="absolute w-[700px] h-[700px] bg-blue-500/10 blur-3xl rounded-full"
        ></div>

        <!-- LOGIN CARD -->
        <div
            class="relative z-10 w-full max-w-md border border-white/10 bg-black/50 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/70 overflow-hidden"
        >
            <!-- HEADER -->
            <div
                class="px-8 py-7 border-b border-white/10 bg-gradient-to-r from-blue-500/10 to-cyan-500/10"
            >
                <div class="flex items-center gap-4">
                    <!-- LOGO -->
                    <div
                        class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center shadow-lg overflow-hidden"
                    >
                        <img
                            src="/logos/wisteriaonline.png"
                            class="w-full h-full object-cover"
                            alt="Wisteria Online"
                        />
                    </div>

                    <div>
                        <h1
                            class="text-3xl font-black text-white tracking-wide"
                        >
                            Wisteria Online
                        </h1>

                        <p class="text-sm text-blue-200/70">
                            Classic Fantasy MMORPG
                        </p>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="p-8">
                <div
                    v-if="status"
                    class="mb-5 rounded-xl border border-green-500/20 bg-green-500/10 px-4 py-3 text-sm text-green-300"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
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
                    <div>
                        <label
                            class="block text-xs uppercase tracking-widest text-gray-400 mb-2"
                        >
                            Secret Password
                        </label>

                        <input
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-xl border border-white/10 bg-black/40 px-4 py-3 text-white placeholder:text-gray-500 focus:border-blue-400/50 focus:ring-0 outline-none transition"
                            placeholder="Enter your password"
                        />

                        <InputError
                            class="mt-2"
                            :message="form.errors.password"
                        />
                    </div>

                    <!-- OPTIONS -->
                    <div class="flex items-center justify-between pt-1">
                        <label
                            class="flex items-center gap-2 text-sm text-gray-300"
                        >
                            <Checkbox v-model:checked="form.remember" />

                            Remember Me
                        </label>

                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-300 hover:text-blue-200 transition"
                        >
                            Forgot Password?
                        </Link>
                    </div>

                    <!-- LOGIN BUTTON -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full mt-2 py-3 rounded-xl bg-gradient-to-r from-blue-500 to-cyan-500 text-white font-bold tracking-wide shadow-lg shadow-blue-500/20 hover:scale-[1.02] hover:shadow-blue-500/40 active:scale-[0.99] transition disabled:opacity-50"
                    >
                        <span v-if="form.processing"> Entering World... </span>

                        <span v-else> Enter Valdora </span>
                    </button>
                </form>

                <!-- FOOTER -->
                <div
                    class="mt-8 pt-5 border-t border-white/10 text-center text-xs text-gray-500"
                >
                    Journey through dungeons, monsters, and legendary loot.
                </div>
            </div>
        </div>
    </div>
</template>
