<template>
    <div class="flex bg-gray-100">
      <aside class="bg-white-800 text-black h-screen w-1/5">
        <router-link to="/main/main-starter">
          <img class="w-24" id="top_logo" src="../../IMAGES/Logo-one.png" alt="Logo" />
        </router-link>
        <nav class="text-left mt-8 flex flex-col items-center h-full">
          <router-link to="/dashboard" class="block py-2 px-4 text-sm">Dashboard</router-link>
          <router-link to="/order-list" class="block py-2 px-4 text-sm">Order List</router-link>
          <router-link to="/add-food" class="block w-full py-2 px-4 text-sm font-bold bg-orange-500 text-white">Add Foods</router-link>
        </nav>
      </aside>
      <div class="w-4/5 bg-[#E4D5D5] flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded shadow-md w-96">
          <h2 class="text-2xl font-semibold mb-6 text-center">ADD A NEW FOOD</h2>
          <form @submit.prevent="addFood">
            <div class="mb-4">
              <label class="block text-gray-600 font-medium">Picture <span class="text-red-800">*</span></label>
              <input type="file" @change="handleImageUpload" class="w-full px-4 py-2 border rounded-md" required />
              <img v-if="imagePreview" :src="imagePreview" class="mt-2 max-w-full" />
            </div>
            <div class="mb-4">
              <label class="block text-gray-600 font-medium">Name <span class="text-red-800">*</span></label>
              <input v-model="food.name" type="text" placeholder="Enter name" class="w-full px-4 py-2 border rounded-md" required />
            </div>
            <div class="mb-4">
              <label class="block text-gray-600 font-medium">Price <span class="text-red-800">*</span></label>
              <input v-model="food.price" type="number" placeholder="Enter price" class="w-full px-4 py-2 border rounded-md" required />
            </div>
            <fieldset class="mb-4">
              <legend>Kind:</legend>
              <div v-for="(kind, index) in kinds" :key="index">
                <input type="checkbox" :id="kind" :value="kind" v-model="food.kinds" />
                <label :for="kind">{{ kind }}</label>
              </div>
            </fieldset>
            <div class="flex justify-between mt-4">
              <div>
                <label class="block text-gray-600">Origin:</label>
                <select v-model="food.origin" class="w-full p-2 border rounded-md mt-1">
                  <option v-for="origin in origins" :key="origin" :value="origin">{{ origin }}</option>
                </select>
              </div>
              <div>
                <label class="block text-gray-600">Fasting:</label>
                <select v-model="food.fasting" class="w-full p-2 border rounded-md mt-1">
                  <option :value="true">Fasting</option>
                  <option :value="false">Non-Fasting</option>
                </select>
              </div>
            </div>
            <div class="mb-6 mt-4">
              <label class="block text-gray-600 font-medium">Description <span class="text-red-800">*</span></label>
              <textarea v-model="food.description" rows="4" placeholder="Enter description" class="w-full px-4 py-2 border rounded-md" required></textarea>
            </div>
            <button type="submit" class="w-full bg-orange-500 text-white font-semibold p-2 rounded-3xl mb-10">Add Food</button>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        food: {
          name: "",
          price: "",
          kinds: [],
          origin: "Ethiopia",
          fasting: true,
          description: "",
        },
        kinds: ["Starter", "Breakfast", "Lunch", "Dinner", "Dessert"],
        origins: ["Ethiopia", "French", "Indian", "China", "Turkey"],
        imagePreview: null,
      };
    },
    methods: {
      handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) {
          this.imagePreview = URL.createObjectURL(file);
        }
      },
      addFood() {
        console.log("Food added:", this.food);
        alert("Food successfully added!");
      },
    },
  };
  </script>
  
  <style>
  #top_logo {
    position: absolute;
    top: 0;
    left: 0;
  }
  </style>