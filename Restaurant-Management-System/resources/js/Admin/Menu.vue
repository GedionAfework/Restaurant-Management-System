<template>
    <div class="flex">
      <!-- Sidebar -->
      <aside class="bg-white text-black h-screen w-1/5 p-4 shadow-md">
        <a href="../main/main-starter.html">
          <img class="w-24" src="../IMAGES/Logo-one.png" alt="Logo" />
        </a>
        <nav class="mt-8">
          <router-link to="/dashboard" class="block py-2 px-4 text-sm">Dashboard</router-link>
          <router-link to="/order-list" class="block py-2 px-4 text-sm">Order List</router-link>
          <router-link to="/menus" class="block py-2 px-4 text-sm font-bold bg-orange-500 text-white rounded">Menus</router-link>
        </nav>
      </aside>
      
      <!-- Main Content -->
      <div class="w-4/5 p-6 bg-gray-100 min-h-screen">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-bold">Menus</h1>
          <button class="bg-orange-500 text-white px-4 py-2 rounded">Add Menu</button>
        </div>
        
        <!-- Menu Table -->
        <table class="w-full border-collapse bg-white shadow-md rounded-lg overflow-hidden">
          <thead>
            <tr class="bg-gray-200 text-left">
              <th class="p-4 border">Image</th>
              <th class="p-4 border">Name</th>
              <th class="p-4 border">Price</th>
              <th class="p-4 border">Category</th>
              <th class="p-4 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="menu in menus" :key="menu.id" class="text-center">
              <td class="p-4 border">
                <img :src="menu.image" alt="menu" class="w-16 h-16 object-cover rounded" />
              </td>
              <td class="p-4 border">{{ menu.name }}</td>
              <td class="p-4 border">${{ menu.price }}</td>
              <td class="p-4 border">{{ menu.category }}</td>
              <td class="p-4 border">
                <button class="bg-red-500 text-white px-3 py-1 rounded">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        menus: [
          { id: 1, image: 'https://via.placeholder.com/100', name: 'Burger', price: 10, category: 'Fast Food' },
          { id: 2, image: 'https://via.placeholder.com/100', name: 'Pizza', price: 15, category: 'Italian' },
          { id: 3, image: 'https://via.placeholder.com/100', name: 'Salad', price: 8, category: 'Healthy' }
        ]
      };
    }
  };
  </script>
  
  <style scoped>
  button:hover {
    opacity: 0.8;
  }
  </style>
  