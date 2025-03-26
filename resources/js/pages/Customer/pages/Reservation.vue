/* Reservation.vue */
<template>
  <div class="min-h-screen bg-black text-white">
    <Navbar />
    <div class="container mx-auto px-6 py-10 text-center">
      <h1 class="text-4xl font-extrabold mb-6">Make a Reservation</h1>
      <p class="mb-6 text-gray-300">Reserve a table and experience the best burgers in town!</p>
      <form class="bg-gray-800 p-6 rounded-lg w-full max-w-lg mx-auto">
        <input type="text" placeholder="Name" class="w-full p-3 mb-4 rounded-lg bg-gray-700 text-white" />
        <input type="phone" placeholder="Phone" class="w-full p-3 mb-4 rounded-lg bg-gray-700 text-white" />
        <input type="date" class="w-full p-3 mb-4 rounded-lg bg-gray-700 text-white" />
        <button class="bg-red-500 px-6 py-3 rounded-lg font-bold hover:bg-red-700">Book Now</button>
      </form>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import Navbar from "@/pages/Customer/Navbar.vue";
import Footer from "@/pages/Customer/Footer.vue";
</script>