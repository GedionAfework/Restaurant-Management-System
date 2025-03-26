<!-- resources/js/Pages/Admin/Orders.vue -->
<template>
  <AdminLayout>
    <div class="container mx-auto p-6 bg-gray-800 min-h-screen">
      <h1 class="text-2xl font-bold mb-6 text-gray-100">Orders</h1>
      <div v-if="orders.data.length" class="space-y-6">
        <div v-for="order in orders.data" :key="order.id" class="bg-gray-900 p-5 rounded-xl shadow-xl">
          <h2 class="text-xl font-bold text-gray-100">Order #{{ order.id }}</h2>
          <p class="text-gray-400">Customer ID: {{ order.customer_id || 'Guest' }}</p>
          <p class="text-gray-400">Total Amount: ${{ order.total_amount }}</p>
          <p class="text-gray-400">Status: {{ order.status }}</p>
          <p class="text-gray-400">Placed: {{ new Date(order.created_at).toLocaleString() }}</p>
          <div class="mt-2">
            <h3 class="text-lg font-semibold text-gray-200">Items:</h3>
            <ul class="list-disc pl-5 text-gray-400">
              <li v-for="item in order.items" :key="item.order_item_id">
                {{ item.menu_item.name }} - Quantity: {{ item.quantity }} - Price: ${{ item.price }}
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div v-else class="text-center text-gray-400">
        <p>No orders available at the moment.</p>
      </div>

      <div v-if="orders.links.length" class="mt-10 flex justify-center space-x-3">
        <button v-for="link in orders.links" :key="link.label" @click="fetchOrders(link.url)" v-html="link.label"
          :class="{ 'font-bold text-teal-400 bg-gray-800': link.active, 'text-gray-400 hover:text-teal-500': !link.active }"
          class="px-4 py-2 rounded-lg transition-colors" :disabled="!link.url" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from './Layout.vue';
import { ref, onMounted } from 'vue';
import { route } from 'ziggy-js';

const orders = ref({
  data: [],
  links: [],
});

const fetchOrders = async (url = route('admin.orders')) => {
  try {
    const response = await fetch(url, {
      headers: {
        'Accept': 'application/json',
        'X-Inertia': 'true',
      },
    });
    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
    const data = await response.json();
    orders.value = data.props.orders;
    console.log('Fetched orders:', orders.value);
  } catch (error) {
    console.error('Error fetching orders:', error);
  }
};

onMounted(() => {
  fetchOrders();
});
</script>