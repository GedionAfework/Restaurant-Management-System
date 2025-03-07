<template>
    <div class="min-h-screen bg-black text-white">
      <Navbar />
      <div class="container mx-auto px-6 py-10 text-center">
        <h1 class="text-4xl font-extrabold mb-6">Our Location</h1>
        <p class="mb-6 text-gray-300">Find us at the heart of the city!</p>
        <iframe class="w-full h-96 rounded-lg" src="https://www.google.com/maps/embed" frameborder="0" allowfullscreen></iframe>
      </div>
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Navbar from "@/pages/Customer/Navbar.vue";
  import Footer from "@/pages/Customer/Footer.vue";
  </script>