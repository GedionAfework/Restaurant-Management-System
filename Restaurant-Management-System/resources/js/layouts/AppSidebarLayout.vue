<template>
    <div class="flex">
      <!-- Sidebar -->
      <aside class="w-64 bg-gray-900 text-white h-screen p-4">
        <h2 class="text-xl font-bold">🍽️ Restaurant</h2>
        <!-- Sidebar Links -->
        <ul class="mt-4 space-y-3">
          <li><Link href="/" class="hover:text-gray-400">Home</Link></li>
          <li><Link href="/menu" class="hover:text-gray-400">Menu</Link></li>
          <li><Link href="/orders" class="hover:text-gray-400">Orders</Link></li>
          <li><Link href="/dashboard" class="hover:text-gray-400">Dashboard</Link></li>
        </ul>
      </aside>
  
      <!-- Main Content -->
      <div class="flex-1 flex flex-col">
        <!-- Navbar -->
        <nav class="bg-red-600 text-white p-4 flex justify-between items-center">
          <div class="text-lg font-bold">🍽️ Restaurant</div>
          <ul class="flex gap-6">
            <li><Link href="/" class="hover:underline">Home</Link></li>
            <li><Link href="/menu" class="hover:underline">Menu</Link></li>
            <li><Link href="/orders" class="hover:underline">Orders</Link></li>
            <li><Link href="/dashboard" class="hover:underline">Dashboard</Link></li>
          </ul>

          <!-- Add NavUser Here -->
          <NavUser :auth="page.props.auth" />
        </nav>
  
        <!-- Page Content -->
        <main class="p-6">
          <slot />
        </main>
      </div>
    </div>
</template>
  
<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import NavUser from '@/components/NavUser.vue';

const page = usePage();
</script>
