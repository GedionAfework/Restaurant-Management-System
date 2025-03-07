<template>
  <div class="min-h-screen text-black">
    <!-- Top Navbar -->
    <Navbar />

    <!-- Hero Section -->
    <div class="flex flex-col lg:flex-row justify-between items-center p-10 bg-black text-white">
      <!-- Left Side Text -->
      <div class="w-full lg:w-1/2 mb-4 lg:mb-0">
        <h1 class="text-4xl font-extrabold mb-4">
          Bite into <span class="text-red-500">Happiness.</span> Savor the <span class="text-red-500">Flavor.</span>
        </h1>
        <p class="text-lg mb-4">
          At <span class="font-bold italic underline text-yellow-500">Burger Haven</span>, we don’t just serve burgers — we craft unforgettable moments. Juicy, perfectly grilled, and packed with fresh, premium ingredients, every bite is a celebration of flavor. Ready for a taste you’ll crave? Come on in and let your taste buds experience the burger revolution!
        </p>
        <Link :href="menuRoute" class="bg-black border-2 border-white text-white px-6 py-3 rounded-lg text-lg font-bold hover:bg-gray-800 transition-all">
          Menu
        </Link>
      </div>

      <!-- Right Side Image -->
      <div class="w-full lg:w-1/2">
        <img src="../../../assets/images/Adobe Express - file (1).png" alt="Burger" class="w-full h-auto object-cover transform lg:scale-90">
      </div>
    </div>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js'; // ✅ Import route function
import Navbar from "@/pages/Customer/Navbar.vue";
import Footer from "@/pages/Customer/Footer.vue";

// Define the menu route
const menuRoute = route('menu'); // ✅ Fix: Make sure this matches your Laravel route name
</script>

<style scoped>
/* Button hover effect */
button:hover {
  opacity: 0.8;
}
</style>
