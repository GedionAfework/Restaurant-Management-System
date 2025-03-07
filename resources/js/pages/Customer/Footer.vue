<template>
    <footer class="bg-black text-white py-6">
      <div class="container mx-auto text-center">
        <div class="mb-4">
          <img src="../../../../assets/images/burger-logo.png" alt="Burger Haven" class="w-16 mx-auto">
        </div>
        <p class="mb-4">&copy; 2025 Burger Haven. All rights reserved.</p>
        <div class="flex justify-center space-x-6">
          <router-link to="/privacy" class="hover:text-gray-400">Privacy Policy</router-link>
          <router-link to="/terms" class="hover:text-gray-400">Terms of Service</router-link>
          <router-link to="/contact" class="hover:text-gray-400">Contact Us</router-link>
        </div>
      </div>
    </footer>
  </template>
  
  <script>
  export default {
    name: "Footer",
  };
  </script>
  
  <style scoped>
  /* Add your footer styles here */
  </style>
  