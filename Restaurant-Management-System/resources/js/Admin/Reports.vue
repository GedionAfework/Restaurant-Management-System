<template>
    <div class="flex">
      <aside class="bg-white text-black h-screen w-1/5 p-4">
        <a href="/">
          <img class="w-24" src="../IMAGES/Logo-one.png" alt="Logo">
        </a>
        <nav class="mt-8">
          <router-link to="/dashboard" class="block py-2 px-4 text-sm">Dashboard</router-link>
          <router-link to="/order-list" class="block py-2 px-4 text-sm">Order List</router-link>
          <router-link to="/add-food" class="block py-2 px-4 text-sm">Add Food</router-link>
          <router-link to="/reports" class="block py-2 px-4 text-sm font-bold bg-orange-500 text-white">Reports</router-link>
        </nav>
      </aside>
      
      <div class="w-4/5 p-6 bg-gray-100">
        <h1 class="text-2xl font-bold mb-4">Sales Report</h1>
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
          <thead>
            <tr class="bg-gray-200">
              <th class="p-4 border">Date</th>
              <th class="p-4 border">Total Sales</th>
              <th class="p-4 border">Total Orders</th>
              <th class="p-4 border">Top Selling Item</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(report, index) in reports" :key="index" class="text-center">
              <td class="p-4 border">{{ report.date }}</td>
              <td class="p-4 border">${{ report.totalSales }}</td>
              <td class="p-4 border">{{ report.totalOrders }}</td>
              <td class="p-4 border">{{ report.topItem }}</td>
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
        reports: [
          { date: '2025-03-05', totalSales: 1200, totalOrders: 45, topItem: 'Burger' },
          { date: '2025-03-04', totalSales: 980, totalOrders: 38, topItem: 'Pizza' }
        ]
      };
    }
  };
  </script>
  
  <style>
  body {
    font-family: Arial, sans-serif;
  }
  </style>
  