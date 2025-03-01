<template>
    <nav class="bg-red-600 text-white p-4 flex justify-between items-center">
      <div class="text-lg font-bold">🍽️ Restaurant</div>
      <ul class="flex gap-6">
        <li><Link href="/" class="hover:underline">Home</Link></li>
        <li><Link href="/menu" class="hover:underline">Menu</Link></li>
        <li><Link href="/orders" class="hover:underline">Orders</Link></li>
        <li><Link href="/dashboard" class="hover:underline">Dashboard</Link></li>
      </ul>
    </nav>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  </script>
  