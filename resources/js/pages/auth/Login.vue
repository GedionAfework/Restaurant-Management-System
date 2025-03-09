<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
  email: "",
  password: "",
});

const login = () => {
  form.post("/login", {
    onSuccess: () => {
      window.location.href = "/dashboard"; // Redirect after login
    },
  });
};
</script>

<template>
  <div class="flex flex-col items-center">
    <h1 class="text-xl font-bold">Login</h1>
    <input v-model="form.email" type="email" placeholder="Email" class="border p-2" />
    <input v-model="form.password" type="password" placeholder="Password" class="border p-2 mt-2" />
    <button @click="login" class="bg-blue-500 text-white p-2 mt-2">Login</button>
  </div>
</template>
