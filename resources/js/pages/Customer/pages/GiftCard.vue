<template>
    <div class="min-h-screen bg-black text-white">
      <Navbar />
      <div class="container mx-auto px-6 py-10 text-center">
        <h1 class="text-4xl font-extrabold mb-6">Gift Cards</h1>
        <p class="mb-6 text-gray-300">Give the gift of delicious burgers!</p>
        <button class="bg-yellow-500 px-6 py-3 rounded-lg font-bold hover:bg-yellow-700">Buy Gift Card</button>
      </div>
      <Footer />
    </div>
  </template>
  
  <script setup>
  import Navbar from "@/pages/Customer/Navbar.vue";
  import Footer from "@/pages/Customer/Footer.vue";
  </script>