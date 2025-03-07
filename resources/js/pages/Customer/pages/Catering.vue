<template>
    <div class="min-h-screen bg-black text-white">
      <Navbar />
      <div class="container mx-auto px-6 py-10 text-center">
        <h1 class="text-4xl font-extrabold mb-6">Catering Services</h1>
        <p class="mb-6 text-gray-300">Make your events special with our catering services.</p>
        <button class="bg-blue-500 px-6 py-3 rounded-lg font-bold hover:bg-blue-700">Request Catering</button>
      </div>
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Navbar from "@/pages/Customer/Navbar.vue";
  import Footer from "@/pages/Customer/Footer.vue";
  </script>
  