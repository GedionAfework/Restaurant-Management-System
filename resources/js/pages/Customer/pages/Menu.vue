<template>
    <div class="menu-page min-h-screen bg-black text-white">
      <div class="container mx-auto px-6 py-10">
        <h1 class="text-4xl font-extrabold text-center mb-6">Our Menu</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="menu-item bg-gray-800 p-6 rounded-lg">
            <img src="../../../../../assets/images/Adobe Express - file (1).png" alt="Burger 1" class="w-full h-48 object-cover rounded-lg mb-4">
            <h2 class="text-xl font-semibold mb-2">Cheese Burger</h2>
            <p class="text-gray-400 mb-4">A classic cheese burger with all the fixings.</p>
            <p class="text-xl font-bold">$8.99</p>
          </div>
  
          <div class="menu-item bg-gray-800 p-6 rounded-lg">
            <img src="../../../../../assets/images/burg-hero-removebg-preview.png" alt="Burger 2" class="w-full h-48 object-cover rounded-lg mb-4">
            <h2 class="text-xl font-semibold mb-2">Chicken Burger</h2>
            <p class="text-gray-400 mb-4">A crispy fried chicken patty with fresh vegetables.</p>
            <p class="text-xl font-bold">$9.99</p>
          </div>
  
          <!-- Add more items as necessary -->
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "Menu",
  };
  </script>
  
  <style scoped>
  .menu-item {
    transition: transform 0.3s ease;
  }
  
  .menu-item:hover {
    transform: scale(1.05);
  }
  </style>
  