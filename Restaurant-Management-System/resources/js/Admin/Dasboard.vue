<template>
    <div class="flex bg-gray-100">
      <!-- Sidebar -->
      <aside class="bg-white-800 text-black h-screen w-1/5">
        <a href="../main/main-starter.html">
          <img
            class="w-24"
            id="top_logo"
            src="../IMAGES/Logo-one.png"
            alt="Logo"
          />
        </a>
        <div class="flex flex-col items-center justify-between h-full">
          <nav class="text-left mt-8">
            <router-link
              to="/dashboard"
              class="block w-full py-2 px-4 text-sm font-bold"
              id="dashboard"
            >
              Dashboard
            </router-link>
            <router-link to="/order-list" class="block py-2 px-4 text-sm">
              Order List
            </router-link>
            <router-link to="/add-food" class="block py-2 px-4 text-sm">
              Add Foods
            </router-link>
          </nav>
        </div>
      </aside>
  
      <!-- Main Content -->
      <div class="flex flex-wrap w-4/5" id="main_page"></div>
  
      <!-- Modal -->
      <div v-if="showModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="modal-content bg-white p-8 rounded shadow-md w-96 mx-auto mt-20">
          <span @click="closeModal" class="close absolute top-0 right-0 text-xl cursor-pointer">&times;</span>
          <h2 class="text-2xl font-semibold mb-6 text-center">ADD Edited FOOD</h2>
  
          <!-- Form -->
          <form @submit.prevent="submitForm" id="foodForm">
            <div class="mb-4">
              <label for="picture" class="block text-gray-600 font-medium">Picture <span class="text-red-800">*</span></label>
              <input
                type="text"
                id="picture"
                v-model="food.picture"
                class="w-full px-4 py-2 border rounded-md"
                required
              />
              <img v-if="food.picture" id="preview" class="mt-2 max-w-full" :src="food.picture" />
            </div>
  
            <div class="mb-4">
              <label for="title" class="block text-gray-600 font-medium">Name <span class="text-red-800">*</span></label>
              <input
                type="text"
                id="title"
                v-model="food.title"
                placeholder="Enter title"
                class="w-full px-4 py-2 border rounded-md"
                required
              />
            </div>
  
            <div class="mb-4">
              <label for="price" class="block text-gray-600 font-medium">Price <span class="text-red-800">*</span></label>
              <input
                type="text"
                id="price"
                v-model="food.price"
                placeholder="Enter price"
                class="w-full px-4 py-2 border rounded-md"
                required
              />
            </div>
  
            <div class="mb-6">
              <div class="flex justify-between">
                <div>
                  <label class="block text-gray-600">Origin:</label>
                  <select
                    v-model="food.origin"
                    class="w-full p-2 border rounded-md mt-1"
                    id="orginSelect"
                  >
                    <option value="ethiopia">Ethiopian</option>
                    <option value="french">French</option>
                    <option value="asian">Asian</option>
                  </select>
                </div>
              </div>
            </div>
  
            <div class="mb-6">
              <label for="description" class="block text-gray-600 font-medium">Description <span class="text-red-800">*</span></label>
              <textarea
                id="description"
                v-model="food.description"
                rows="4"
                placeholder="Enter description"
                class="w-full px-4 py-2 border rounded-md"
                required
              ></textarea>
            </div>
  
            <div id="editData">
              <button
                type="submit"
                class="w-full text-black font-semibold p-2 rounded-3xl mb-10"
              >
                Add edited food
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        showModal: false,
        food: {
          picture: '',
          title: '',
          price: '',
          origin: 'ethiopia',
          description: ''
        }
      };
    },
    methods: {
      openModal() {
        this.showModal = true;
      },
      closeModal() {
        this.showModal = false;
      },
      submitForm() {
        // Handle form submission logic
        console.log(this.food);
        this.closeModal();
      }
    }
  };
  </script>
  
  <style scoped>
  .modal {
    display: block;
  }
  .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
  }
  .close:hover,
  .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
  }
  </style>
  