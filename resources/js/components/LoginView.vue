<template>
    <div
        class="flex min-h-screen items-center justify-center bg-surface-100 px-4 py-8"
    >
        <div class="w-full max-w-md">
            <div class="rounded-lg bg-white p-6 shadow-lg dark:bg-surface-800">
                <div class="mb-6 text-center">
                    <img src="" alt="logo" class="mx-auto mb-4 h-12" />
                    <h2
                        class="text-2xl font-semibold text-gray-800 dark:text-white"
                    >
                        Selamat Datang
                    </h2>
                    <p class="text-gray-500 dark:text-gray-300">
                        Silakan login untuk melanjutkan
                    </p>
                </div>

                <form @submit.prevent="handleLogin">
                    <div class="mb-4">
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Email</label
                        >
                        <InputText
                            id="email"
                            v-model="email"
                            type="email"
                            class="w-full mt-1"
                            required
                            autofocus
                        />
                    </div>

                    <div class="mb-6">
                        <label
                            for="password"
                            class="block text-sm font-medium text-gray-700 dark:text-white"
                            >Password</label
                        >
                        <InputText
                            id="password"
                            v-model="password"
                            type="password"
                            class="w-full mt-1"
                            required
                        />
                    </div>

                    <Button label="Login" type="submit" class="w-full" />

                    <p
                        v-if="error"
                        class="mt-4 text-sm text-red-600 text-center"
                    >
                        {{ error }}
                    </p>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";

const email = ref("");
const password = ref("");
const error = ref("");

const handleLogin = async () => {
    error.value = "";
    try {
        const res = await axios.post("/api/login", {
            email: email.value,
            password: password.value,
        });

        localStorage.setItem("token", res.data.token);
        window.location.href = "/dashboard";
    } catch (err) {
        error.value =
            err.response?.data?.message ||
            "Login gagal. Cek kembali data Anda.";
    }
};
</script>
