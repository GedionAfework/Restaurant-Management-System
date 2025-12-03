<template>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white p-5">
      <Link :href="homeRoute" class="border-b-2 border-transparent px-4 cursor-pointer">
        <img src="../../../../assets/images/burger-logo.png" alt="Burger" class="w-12 object-cover transform">
      </Link>
      <h2 class="text-xl font-bold">Admin Panel</h2>

      <nav class="mt-5">
        <ul class="space-y-2">
          <li v-if="hasPermission('dashboard-view') || isAdmin">
            <Link :href="dashboardRoute" class="block px-4 py-2 rounded hover:bg-gray-800 transition">📊 Dashboard</Link>
          </li>
          <li v-if="hasPermission('employees-view') || isAdmin">
            <Link :href="employeesRoute" class="block px-4 py-2 rounded hover:bg-gray-800 transition">👥 Employees</Link>
          </li>
          <li v-if="hasPermission('menu-view') || isAdmin">
            <Link :href="foodRoute" class="block px-4 py-2 rounded hover:bg-gray-800 transition">🍔 Food/Menu</Link>
          </li>
          <li v-if="hasPermission('menu-view') || isAdmin">
            <Link :href="route('admin.menu-categories.index')" class="block px-4 py-2 rounded hover:bg-gray-800 transition">📋 Menu Categories</Link>
          </li>
          <li v-if="hasPermission('orders-view') || isAdmin">
            <Link :href="route('admin.orders.index')" class="block px-4 py-2 rounded hover:bg-gray-800 transition">📦 Orders</Link>
          </li>
          <li v-if="hasPermission('orders-view') || isAdmin">
            <Link :href="route('admin.kitchen.index')" class="block px-4 py-2 rounded hover:bg-gray-800 transition">🍳 Kitchen Display</Link>
          </li>
          <li v-if="hasPermission('tables-view') || isAdmin">
            <Link :href="route('admin.tables.index')" class="block px-4 py-2 rounded hover:bg-gray-800 transition">🪑 Tables</Link>
          </li>
          <li v-if="hasPermission('roles-view') || isAdmin">
            <Link :href="route('admin.roles.index')" class="block px-4 py-2 rounded hover:bg-gray-800 transition">🔐 Roles & Permissions</Link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 bg-gray-100">
      <slot />
    </div>
  </div>
</template>
<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { usePermissions } from '@/composables/usePermissions';
import { computed } from 'vue';

const { hasPermission, user } = usePermissions();

const isAdmin = computed(() => user.value?.is_admin || false);

const homeRoute = route("home");
const dashboardRoute = route('admin.dashboard');
const employeesRoute = route('admin.employees');
const foodRoute = route('admin.food');
</script>