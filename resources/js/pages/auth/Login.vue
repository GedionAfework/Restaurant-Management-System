<template>
    <div class="min-h-screen bg-black text-white flex items-center justify-center">
      <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-3xl font-extrabold text-center mb-6">Login to Your Account</h2>
        <form @submit.prevent="login" class="space-y-4">
          <!-- Email Field -->
          <div>
            <input
              v-model="form.email"
              type="email"
              placeholder="Email Address"
              class="w-full p-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
            />
          </div>
          <!-- Password Field -->
          <div>
            <input
              v-model="form.password"
              type="password"
              placeholder="Password"
              class="w-full p-4 rounded-lg bg-gray-700 text-white focus:outline-none focus:ring-2 focus:ring-red-500"
            />
          </div>
          <!-- Remember Me -->
          <div class="flex items-center justify-between">
            <label class="flex items-center text-sm">
              <input type="checkbox" v-model="form.remember" class="text-red-500" />
              <span class="ml-2">Remember Me</span>
            </label>
            <a href="#" class="text-sm text-red-500 hover:underline">Forgot Password?</a>
          </div>
          <!-- Submit Button -->
          <button
            type="submit"
            class="w-full py-3 rounded-lg bg-red-500 text-white text-lg font-bold hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500"
          >
            Login
          </button>
          <div class="text-center mt-4">
            <p class="text-sm text-gray-300">Don't have an account?</p>
            <Link :href="route('register')" class="text-red-500 hover:underline">Sign Up</Link>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useForm } from "@inertiajs/vue3";
  import { route } from 'ziggy-js';
  import { Link } from '@inertiajs/vue3';
  
  const form = useForm({
    email: "",
    password: "",
    remember: false,
  });
  
  const login = () => {
    form.post("/login", {
      onSuccess: () => {
        window.location.href = "/"; // Redirect after login
      },
    });
  };
  </script>
  