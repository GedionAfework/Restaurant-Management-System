<!-- resources/js/Pages/Admin/Food.vue -->
<template>
  <AdminLayout>
    <div class="container mx-auto p-6 bg-gray-800 min-h-screen">
      <h1 class="text-2xl font-bold mb-6 text-gray-100">Food Items</h1>
      <Link :href="route('admin.food.create')" class="bg-teal-500 text-gray-900 px-6 py-2 rounded-lg hover:bg-teal-600 transition-colors mb-6 inline-block" id="add-food-btn">
        Add New Food
      </Link>

      <table class="w-full border-collapse bg-gray-700 rounded-lg overflow-hidden">
        <thead>
          <tr class="bg-gray-600">
            <th class="p-3 text-left text-gray-200 font-medium">Name</th>
            <th class="p-3 text-left text-gray-200 font-medium">Type</th>
            <th class="p-3 text-left text-gray-200 font-medium">Description</th>
            <th class="p-3 text-left text-gray-200 font-medium">Picture</th>
            <th class="p-3 text-left text-gray-200 font-medium">Price</th>
            <th class="p-3 text-left text-gray-200 font-medium">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="food in foods?.data || foods" :key="food.id" class="border-b border-gray-600 hover:bg-gray-600 transition-colors">
            <td class="p-3 text-gray-100">{{ food.name }}</td>
            <td class="p-3 text-gray-100">{{ food.type || 'N/A' }}</td>
            <td class="p-3 text-gray-100">{{ food.description || 'N/A' }}</td>
            <td class="p-3">
              <img v-if="food.picture" :src="'/storage/' + food.picture" class="h-16 w-16 object-cover rounded-md" alt="Food Picture" />
              <span v-else class="text-gray-400">N/A</span>
            </td>
            <td class="p-3 text-gray-100">${{ food.price }}</td>
            <td class="p-3 space-x-2">
              <Link :href="route('admin.food.edit', food.id)" class="bg-yellow-500 text-gray-900 px-3 py-1 rounded-lg hover:bg-yellow-600 transition-colors">
                Edit
              </Link>
              <button @click="deleteFood(food.id)" class="bg-red-500 text-gray-100 px-3 py-1 rounded-lg hover:bg-red-600 transition-colors">
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-if="foods?.links?.length" class="mt-6 flex space-x-2 justify-center">
        <Link v-for="link in foods.links" :key="link.label" :href="link.url" v-html="link.label"
          :class="{ 'font-bold text-teal-400': link.active, 'text-gray-400 hover:text-teal-500': !link.active }" class="px-3 py-1 rounded-lg transition-colors" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import AdminLayout from './Layout.vue'; // Adjust path if needed
import { Link, useForm } from '@inertiajs/vue3';

defineProps({
  foods: {
    type: Object,
    default: () => ({ data: [], links: [] }), // Default to avoid undefined errors
  },
});

const deleteFood = (id) => {
  if (confirm('Are you sure you want to delete this food item?')) {
    useForm().delete(route('admin.food.destroy', id), {
      onSuccess: () => alert('Food deleted successfully!'),
      preserveState: false,
    });
  }
};
</script>

<script>
export default {
  components: { AdminLayout },
};
</script>