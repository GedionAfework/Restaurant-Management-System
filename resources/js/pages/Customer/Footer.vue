<template>
  <footer class="bg-black text-white py-6">
    <div class="container mx-auto text-center">
      <div class="mb-4">
        <img src="../../../../assets/images/burger-logo.png" alt="Burger Haven" class="w-16 mx-auto">
      </div>
      <p class="mb-4">&copy; 2025 Burger Haven. All rights reserved.</p>
      <div class="flex justify-center space-x-6">
        <Link href="/privacy" class="hover:text-gray-400">Privacy Policy</Link>
        <Link href="/terms" class="hover:text-gray-400">Terms of Service</Link>
        <Link href="/contact" class="hover:text-gray-400">Contact Us</Link>
      </div>
    </div>
  </footer>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
  name: "Footer",
};
</script>

<style scoped>
/* Add your footer styles here */
</style>
