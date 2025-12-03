<template>
  <div class="min-h-screen bg-black text-white flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
      <h2 class="text-3xl font-extrabold text-center mb-6">Create Your Account</h2>

      <!-- Display global errors -->
      <div v-if="form.errors" class="bg-red-600 p-4 rounded-md mb-4">
        <ul>
          <li v-for="(error, index) in form.errors" :key="index" class="text-white">{{ error }}</li>
        </ul>
      </div>

      <form @submit.prevent="register" class="space-y-4">
        <!-- Name Field -->
        <div>
          <input
            v-model="form.name"
            type="text"
            placeholder="Full Name"
            class="w-full p-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
          />
          <div v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</div>
        </div>

        <!-- Email Field -->
        <div>
          <input
            v-model="form.email"
            type="email"
            placeholder="Email Address"
            class="w-full p-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
          />
          <div v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</div>
        </div>

        <!-- Password Field -->
        <div>
          <input
            v-model="form.password"
            type="password"
            placeholder="Password"
            class="w-full p-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
          />
          <div v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</div>
        </div>

        <!-- Confirm Password Field -->
        <div>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full p-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
          />
          <div v-if="form.errors.password_confirmation" class="text-sm text-red-500">{{ form.errors.password_confirmation }}</div>
        </div>

        <!-- Submit Button -->
        <button
          type="submit"
          class="w-full py-3 rounded-lg bg-red-500 text-white text-lg font-bold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
        >
          Sign Up
        </button>
        <div class="text-center mt-4">
          <p class="text-sm text-gray-300">Already have an account?</p>
          <Link :href="loginRoute" class="text-red-500 hover:underline">Login</Link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { route } from 'ziggy-js';
import { Link } from '@inertiajs/vue3';

const loginRoute = route("login");

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const register = () => {
  form.post(route('register'), {
    onSuccess: () => {
      // Redirect to login page after successful registration
      router.visit(route('login'));
    },
    onError: (errors) => {
      console.log("Registration failed:", errors);
    },
  });
};
</script>