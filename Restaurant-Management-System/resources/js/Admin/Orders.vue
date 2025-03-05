<template>
    <div class="flex">
      <aside class="bg-white-800 text-black h-screen w-1/5">
        <a href="../main/main-starter.html">
          <img class="w-24" id="top_logo" src="../IMAGES/Logo-one.png" alt="Logo">
        </a>
        <div class="flex flex-col items-center justify-between h-full">
          <nav class="text-left mt-8">
            <router-link to="/dashboard" class="block py-2 px-4 text-sm">Dashboard</router-link>
            <router-link to="/order-list" class="block py-2 px-4 text-sm font-bold" id="customer">Order List</router-link>
            <router-link to="/add-food" class="block py-2 px-4 text-sm">Add Food</router-link>
          </nav>
        </div>
      </aside>
      <div class="w-4/5" id="main_page">
        <div class="h-screen flex items-center">
          <table class="w-full m-10 text-center">
            <thead>
              <tr class="text-2xl">
                <th class="bg-gray-200 border border-slate-600 p-4">Phone.No</th>
                <th class="bg-gray-200 border border-slate-600 p-4">Food</th>
                <th class="bg-gray-200 border border-slate-600 p-4">Total Price</th>
                <th class="bg-gray-200 border border-slate-600 p-4">Location</th>
                <th class="bg-gray-200 border border-slate-600 p-4">Description</th>
                <th class="bg-gray-200 border border-slate-600 p-4">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white text-xl text-black">
              <tr v-for="(order, index) in orders" :key="index">
                <td class="border border-slate-600 p-4">{{ order.phone }}</td>
                <td class="border border-slate-600 p-4">{{ order.food }}</td>
                <td class="border border-slate-600 p-4">{{ order.total_price }}</td>
                <td class="border border-slate-600 p-4">{{ order.location }}</td>
                <td class="border border-slate-600 p-4">{{ order.description }}</td>
                <td class="border border-slate-600 p-4">{{ order.status }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        orders: [] // Replace with actual data fetching logic
      };
    },
    mounted() {
      // Fetch order list data when the component is mounted
      fetch("./order-list.json") // Adjust URL as needed
        .then(response => response.json())
        .then(data => {
          this.orders = data;
        })
        .catch(error => console.error("Error fetching orders:", error));
    }
  };
  </script>
  
  <style scoped>
  #main_page {
    background-color: #E4D5D5;
  }
  #top_logo {
    position: absolute;
    top: 0;
    left: 0;
  }
  #customer {
    width: 200%;
    background-color: #F97350;
    color: white;
  }
  </style>