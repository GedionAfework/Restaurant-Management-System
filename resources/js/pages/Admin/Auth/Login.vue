<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
      <div class="bg-white p-6 rounded shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Admin Login</h1>
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input
              v-model="form.email"
              type="email"
              class="w-full p-2 border rounded"
              required
            />
            <span v-if="errors.email" class="text-red-500 text-sm">{{
              errors.email
            }}</span>
          </div>
          <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input
              v-model="form.password"
              type="password"
              class="w-full p-2 border rounded"
              required
            />
          </div>
          <button
            type="submit"
            class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
          >
            Login
          </button>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { useForm } from "@inertiajs/vue3";
  
  export default {
    name: "AdminLogin",
    data() {
      return {
        form: useForm({
          email: "",
          password: "",
        }),
      };
    },
    props: {
      errors: Object,
    },
    methods: {
      submit() {
        this.form.post("/admin/login", {
          onSuccess: () => this.form.reset("password"),
        });
      },
    },
  };
  </script>