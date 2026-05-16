<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
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
</script>
<template>
    <Head title="Register" />

    <div class="mmorpg-register">
        <!-- BACKGROUND -->
        <div class="register-overlay"></div>

        <!-- CARD -->
        <div class="register-card">
            <!-- LOGO -->
            <div class="register-top">
                <img src="/logo.png" class="game-logo" />

                <h1 class="game-title">ETERNAL REALMS</h1>

                <p class="game-subtitle">Create your adventurer</p>
            </div>

            <!-- FORM -->
            <form @submit.prevent="submit" class="register-form">
                <!-- NAME -->
                <div class="input-group">
                    <InputLabel
                        for="name"
                        value="Character Name"
                        class="input-label"
                    />

                    <TextInput
                        id="name"
                        type="text"
                        class="mmorpg-input"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="input-group">
                    <InputLabel
                        for="name"
                        value="Character Class"
                        class="input-label"
                    />

                    <select
                        id="class"
                        class="mmorpg-input"
                        v-model="form.class"
                        required
                    >
                        <option disabled value="">Select Class</option>

                        <option value="Knight">⚔️ Knight</option>

                        <option value="Archer">🏹 Archer</option>

                        <option value="Wizard">🔮 Wizard</option>

                        <option value="Assassin">🗡️ Assassin</option>

                        <option value="Crusader">✨ Crusader</option>
                    </select>

                    <InputError class="mt-2" :message="form.errors.class" />
                </div>

                <!-- EMAIL -->
                <div class="input-group">
                    <InputLabel for="email" value="Email" class="input-label" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mmorpg-input"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- PASSWORD -->
                <div class="input-group">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="input-label"
                    />

                    <TextInput
                        id="password"
                        type="password"
                        class="mmorpg-input"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- CONFIRM -->
                <div class="input-group">
                    <InputLabel
                        for="password_confirmation"
                        value="Confirm Password"
                        class="input-label"
                    />

                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mmorpg-input"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError
                        class="mt-2"
                        :message="form.errors.password_confirmation"
                    />
                </div>

                <!-- ACTIONS -->
                <div class="register-actions">
                    <Link :href="route('login')" class="login-link">
                        Already registered?
                    </Link>

                    <button
                        type="submit"
                        class="register-btn"
                        :disabled="form.processing"
                    >
                        Enter World
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* =========================================
PAGE (OLD MMORPG STYLE)
========================================= */
.mmorpg-register {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;

    background:
        radial-gradient(circle at top, rgba(0, 0, 0, 0.2), transparent 40%),
        url("/images/mmorpg-bg.jpg") center center / cover no-repeat;
}

/* DARK OVERLAY (STRONGER RPG FEEL) */
.register-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.78);
}

/* =========================================
CARD (STONE / METAL PANEL STYLE)
========================================= */
.register-card {
    width: 440px;
    position: relative;
    z-index: 10;

    padding: 32px;

    border-radius: 10px;

    background: linear-gradient(to bottom, #1a1a1f, #0d0d10);

    border: 2px solid #3a3a44;

    box-shadow:
        0 0 0 1px rgba(0, 0, 0, 0.8),
        inset 0 0 10px rgba(255, 255, 255, 0.03);

    overflow: hidden;
}

/* FRAME EDGE (RPG PANEL BORDER) */
.register-card::before {
    content: "";
    position: absolute;
    inset: 0;
    border: 1px solid rgba(255, 255, 255, 0.05);
    pointer-events: none;
}

/* =========================================
TOP
========================================= */
.register-top {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 26px;
}

.game-logo {
    width: 80px;
    height: 80px;
    margin-bottom: 10px;
    filter: drop-shadow(0 0 6px rgba(0, 0, 0, 0.6));
}

.game-title {
    font-size: 28px;
    font-weight: 900;
    letter-spacing: 2px;
    color: #e5e5e5;
    text-shadow: 0 1px 0 #000;
}

.game-subtitle {
    color: rgba(255, 255, 255, 0.55);
    font-size: 12px;
}

/* =========================================
INPUTS (GAME UI STYLE)
========================================= */
.mmorpg-input {
    width: 100%;
    height: 46px;

    padding: 0 14px;

    border-radius: 6px;

    border: 1px solid #2f2f38;

    background: #0f0f14;

    color: #e5e5e5;

    font-size: 13px;

    outline: none;

    transition: 0.2s;
}

.mmorpg-input:focus {
    border-color: #6b7280;
    box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.05);
}

/* SELECT */
select.mmorpg-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23aaa' viewBox='0 0 20 20'%3E%3Cpath d='M5.25 7.5L10 12.25 14.75 7.5'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 14px;
}

/* LABEL */
.input-label {
    color: #bdbdbd;
    font-size: 11px;
    letter-spacing: 1px;
    margin-bottom: 6px;
}

/* =========================================
BUTTON (MMORPG START BUTTON)
========================================= */
.register-btn {
    height: 44px;
    padding: 0 18px;

    border-radius: 6px;

    border: 1px solid #3a3a44;

    background: linear-gradient(to bottom, #2b2b33, #14141a);

    color: #e5e5e5;

    font-weight: bold;

    cursor: pointer;

    transition: 0.2s;
}

.register-btn:hover {
    border-color: #6b7280;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.4);
}

.register-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* LINK */
.login-link {
    color: #9ca3af;
    font-size: 12px;
}

.login-link:hover {
    color: #ffffff;
}

/* =========================================
LAYOUT
========================================= */
.register-form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.register-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
}

/* MOBILE */
@media (max-width: 640px) {
    .register-card {
        width: 92%;
        padding: 22px;
    }

    .register-actions {
        flex-direction: column;
        gap: 10px;
    }

    .register-btn {
        width: 100%;
    }
}
</style>
