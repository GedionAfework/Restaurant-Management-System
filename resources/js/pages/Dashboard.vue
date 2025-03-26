<template>
    <AdminLayout>
      <h1 class="text-2xl font-bold text-black">Admin Dashboard</h1>
      <p class="text-red-500">Welcome, admin!</p>
    </AdminLayout>
  </template>
  
  <script>
  import AdminLayout from './Layout.vue';
  
  export default {
    components: { AdminLayout },
  };
  </script>
  