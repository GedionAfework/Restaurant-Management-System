<template>
  <div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white p-5">
      <h2 class="text-xl font-bold">Admin Panel</h2>
      <nav class="mt-5">
        <ul>
          <li class="mb-2">
            <inertia-link href="/admin/dashboard" class="hover:text-gray-400">Dashboard</inertia-link>
          </li>
          <li class="mb-2">
            <inertia-link href="/admin/employees" class="hover:text-gray-400">Employees</inertia-link>
          </li>
          <li class="mb-2">
            <inertia-link href="/admin/food" class="hover:text-gray-400">Food</inertia-link>
          </li>
          <li class="mb-2">
            <inertia-link href="/admin/orders" class="hover:text-gray-400">Orders</inertia-link>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 p-5 bg-gray-100">
      <slot />
    </div>
  </div>
</template>
