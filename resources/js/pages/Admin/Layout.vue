<template>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white p-5">
      <Link :href="homeRoute" class="border-b-2 border-transparent px-4 cursor-pointer">
        <img src="../../../../assets/images/burger-logo.png" alt="Burger" class="w-12 object-cover transform">
      </Link>
      <h2 class="text-xl font-bold">Admin Panel</h2>

      <nav class="mt-5">
        <ul>
          <li class="mb-2">
            <Link :href="dashboardRoute" class="hover:text-gray-400">Dashboard</Link>
          </li>
          <li class="mb-2">
            <Link :href="employeesRoute" class="hover:text-gray-400">Employees</Link>
          </li>
          <li class="mb-2">
            <Link :href="foodRoute" class="hover:text-gray-400">Food</Link>
          </li>
          <li class="mb-2">
            <Link :href="ordersRoute" class="hover:text-gray-400">Orders</Link>
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
// import { route } from "ziggy-js";
const homeRoute = route("home");
const dashboardRoute = route('admin.dashboard');
const employeesRoute = route('admin.employees');
const foodRoute = route('admin.food');
const ordersRoute = route('admin.orders');
</script>