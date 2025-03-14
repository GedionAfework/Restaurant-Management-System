<template>
  <AdminLayout>
    <h1 class="text-2xl font-bold">Manage Food</h1>
    <ul>
      <li v-for="food in foods.data" :key="food.id">
        {{ food.name }} - ${{ food.price }}
      </li>
    </ul>

    <div v-if="foods.last_page > 1">
      <button @click="loadPage(foods.current_page - 1)" :disabled="foods.current_page === 1">Previous</button>
      <button @click="loadPage(foods.current_page + 1)" :disabled="foods.current_page === foods.last_page">Next</button>
    </div>
  </AdminLayout>
</template>

<script>
export default {
  props: {
    foods: Object,  // Paginated food data passed from the controller
  },
  methods: {
    loadPage(page) {
      this.$inertia.get('/admin/food', { page });
    },
  },
};
</script>
