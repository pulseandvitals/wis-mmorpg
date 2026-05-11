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
PAGE
========================================= */
.mmorpg-register {
    min-height: 100vh;

    display: flex;
    align-items: center;
    justify-content: center;

    position: relative;

    overflow: hidden;

    background:
        radial-gradient(
            circle at top,
            rgba(251, 191, 36, 0.08),
            transparent 30%
        ),
        url("/images/mmorpg-bg.jpg") center center / cover no-repeat;
}

/* FLOATING PARTICLES */
.mmorpg-register::before,
.mmorpg-register::after {
    content: "";

    position: absolute;

    width: 500px;
    height: 500px;

    border-radius: 999px;

    background: rgba(251, 191, 36, 0.05);

    filter: blur(120px);

    animation: floatGlow 8s ease-in-out infinite;
}

.mmorpg-register::before {
    top: -150px;
    left: -100px;
}

.mmorpg-register::after {
    bottom: -150px;
    right: -100px;

    animation-delay: 4s;
}

/* =========================================
OVERLAY
========================================= */
.register-overlay {
    position: absolute;
    inset: 0;

    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.45),
        rgba(0, 0, 0, 0.82)
    );

    backdrop-filter: blur(4px);
}

/* =========================================
CARD
========================================= */
.register-card {
    width: 460px;

    position: relative;
    z-index: 10;

    padding: 38px;

    border-radius: 28px;

    background: linear-gradient(
        to bottom,
        rgba(20, 20, 25, 0.82),
        rgba(10, 10, 15, 0.88)
    );

    border: 1px solid rgba(251, 191, 36, 0.15);

    backdrop-filter: blur(16px);

    box-shadow:
        0 0 60px rgba(0, 0, 0, 0.65),
        0 0 25px rgba(251, 191, 36, 0.08),
        inset 0 0 18px rgba(255, 255, 255, 0.02);

    overflow: hidden;
}

/* GOLDEN BORDER EFFECT */
.register-card::before {
    content: "";

    position: absolute;
    inset: 0;

    border-radius: inherit;

    padding: 1px;

    background: linear-gradient(
        135deg,
        rgba(251, 191, 36, 0.5),
        transparent,
        rgba(251, 191, 36, 0.25)
    );

    -webkit-mask:
        linear-gradient(#fff 0 0) content-box,
        linear-gradient(#fff 0 0);

    -webkit-mask-composite: xor;
    mask-composite: exclude;

    pointer-events: none;
}

/* =========================================
TOP
========================================= */
.register-top {
    display: flex;
    flex-direction: column;
    align-items: center;

    margin-bottom: 30px;
}

.game-logo {
    width: 95px;
    height: 95px;

    object-fit: contain;

    margin-bottom: 16px;

    filter: drop-shadow(0 0 12px rgba(251, 191, 36, 0.35));
}

.game-title {
    font-size: 34px;
    font-weight: 900;

    letter-spacing: 3px;

    background: linear-gradient(to bottom, #fde68a, #fbbf24);

    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;

    text-shadow: 0 0 14px rgba(251, 191, 36, 0.25);

    margin-bottom: 6px;
}

.game-subtitle {
    color: rgba(255, 255, 255, 0.62);

    font-size: 14px;

    letter-spacing: 1px;
}

/* =========================================
FORM
========================================= */
.register-form {
    display: flex;
    flex-direction: column;

    gap: 18px;
}

.input-group {
    display: flex;
    flex-direction: column;
}

.input-label {
    color: rgba(255, 255, 255, 0.78);

    margin-bottom: 8px;

    font-size: 12px;

    letter-spacing: 1px;

    text-transform: uppercase;
}

/* =========================================
INPUT
========================================= */
.mmorpg-input {
    width: 100%;
    height: 54px;

    padding: 0 18px;

    border-radius: 16px;

    border: 1px solid rgba(255, 255, 255, 0.08);

    background: linear-gradient(
        to bottom,
        rgba(255, 255, 255, 0.04),
        rgba(255, 255, 255, 0.02)
    );

    color: white;

    font-size: 14px;

    transition: 0.25s;

    backdrop-filter: blur(8px);
}

/* SELECT STYLE */
select.mmorpg-input {
    appearance: none;

    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='gold' viewBox='0 0 20 20'%3E%3Cpath d='M5.25 7.5L10 12.25 14.75 7.5'/%3E%3C/svg%3E");

    background-repeat: no-repeat;

    background-position: right 16px center;

    background-size: 18px;

    padding-right: 50px;
}

.mmorpg-input:focus {
    border-color: rgba(251, 191, 36, 0.45);

    background: linear-gradient(
        to bottom,
        rgba(255, 255, 255, 0.06),
        rgba(255, 255, 255, 0.03)
    );

    box-shadow:
        0 0 0 4px rgba(251, 191, 36, 0.08),
        0 0 20px rgba(251, 191, 36, 0.08);
}

/* =========================================
ACTIONS
========================================= */
.register-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;

    margin-top: 14px;
}

.login-link {
    color: rgba(255, 255, 255, 0.72);

    font-size: 13px;

    transition: 0.2s;
}

.login-link:hover {
    color: #fde047;
}

/* =========================================
BUTTON
========================================= */
.register-btn {
    position: relative;

    height: 52px;

    padding: 0 26px;

    border: none;
    border-radius: 16px;

    background: linear-gradient(to bottom, #fde047, #f59e0b);

    color: #111827;

    font-weight: 800;

    letter-spacing: 1px;

    cursor: pointer;

    overflow: hidden;

    transition: 0.25s;
}

.register-btn::before {
    content: "";

    position: absolute;
    inset: 0;

    background: linear-gradient(
        120deg,
        transparent,
        rgba(255, 255, 255, 0.35),
        transparent
    );

    transform: translateX(-100%);
}

.register-btn:hover::before {
    animation: shine 1s linear;
}

.register-btn:hover {
    transform: translateY(-2px);

    box-shadow:
        0 10px 24px rgba(251, 191, 36, 0.35),
        0 0 18px rgba(251, 191, 36, 0.2);
}

.register-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* =========================================
ANIMATIONS
========================================= */
@keyframes shine {
    100% {
        transform: translateX(100%);
    }
}

@keyframes floatGlow {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(30px);
    }

    100% {
        transform: translateY(0px);
    }
}

/* =========================================
MOBILE
========================================= */
@media (max-width: 640px) {
    .register-card {
        width: 92%;
        padding: 24px;
    }

    .game-title {
        font-size: 26px;
    }

    .register-actions {
        flex-direction: column;
        gap: 16px;
    }

    .register-btn {
        width: 100%;
    }
}
</style>
