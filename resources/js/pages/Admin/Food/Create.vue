<!-- resources/js/Pages/Admin/Food/Create.vue -->
<template>
  <AdminLayout>
    <div class="container mx-auto p-6 bg-gray-800 min-h-screen">
      <h1 class="text-2xl font-bold mb-6 text-gray-100">Add New Food</h1>
      <form @submit.prevent="submit" class="space-y-6" enctype="multipart/form-data">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-200">Name</label>
          <input v-model="form.name" id="name" class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-gray-100 focus:ring-2 focus:ring-teal-400 focus:border-teal-400" required />
          <div v-if="form.errors.name" class="text-rose-400 text-sm mt-1">{{ form.errors.name }}</div>
        </div>
        <div>
          <label for="type" class="block text-sm font-medium text-gray-200">Type</label>
          <select v-model="form.type" id="type" class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-gray-100 focus:ring-2 focus:ring-teal-400 focus:border-teal-400">
            <option value="" disabled selected>Select a type</option>
            <option value="Appetizer">Appetizer</option>
            <option value="Main Course">Main Course</option>
            <option value="Dessert">Dessert</option>
            <option value="Drinks">Drinks</option>
            <option value="Alcohol">Alcohol</option>
            <option value="Side Dish">Side Dish</option>
            <option value="Snack">Snack</option>
            <option value="Breakfast">Breakfast</option>
            <option value="Special">Special</option>
          </select>
          <div v-if="form.errors.type" class="text-rose-400 text-sm mt-1">{{ form.errors.type }}</div>
        </div>
        <div>
          <label for="description" class="block text-sm font-medium text-gray-200">Description</label>
          <textarea v-model="form.description" id="description" class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-gray-100 focus:ring-2 focus:ring-teal-400 focus:border-teal-400" rows="4"></textarea>
          <div v-if="form.errors.description" class="text-rose-400 text-sm mt-1">{{ form.errors.description }}</div>
        </div>
        <div>
          <label for="picture" class="block text-sm font-medium text-gray-200">Picture</label>
          <input type="file" id="picture" @change="updatePicture" class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-gray-200 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-teal-900 file:text-teal-200 hover:file:bg-teal-800" />
          <div v-if="form.errors.picture" class="text-rose-400 text-sm mt-1">{{ form.errors.picture }}</div>
        </div>
        <div>
          <label for="price" class="block text-sm font-medium text-gray-200">Price</label>
          <input v-model="form.price" id="price" type="number" step="0.01" class="w-full border border-gray-600 rounded-lg p-2 bg-gray-700 text-gray-100 focus:ring-2 focus:ring-teal-400 focus:border-teal-400" required />
          <div v-if="form.errors.price" class="text-rose-400 text-sm mt-1">{{ form.errors.price }}</div>
        </div>
        <div class="space-x-4">
          <button type="submit" :disabled="form.processing" class="bg-teal-500 text-gray-900 px-6 py-2 rounded-lg hover:bg-teal-600 transition-colors disabled:bg-teal-700 disabled:cursor-not-allowed">
            {{ form.processing ? 'Saving...' : 'Save' }}
          </button>
          <Link :href="route('admin.food')" class="bg-gray-500 text-gray-100 px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">Cancel</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from '../Layout.vue'; // Adjust path if needed
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  type: '',
  description: '',
  picture: null,
  price: '',
});

const updatePicture = (event) => {
  form.picture = event.target.files[0];
};

const submit = () => {
  console.log('Form data before submission:', form.data());
  form.post(route('admin.food.store'), {
    forceFormData: true,
    preserveState: false,
    onSuccess: () => {
      console.log('Save successful');
      form.reset();
      window.location.href = route('admin.food');
    },
    onError: (errors) => {
      console.log('Save failed:', errors);
    },
    onFinish: () => {
      console.log('Request finished');
    },
  });
};
</script>