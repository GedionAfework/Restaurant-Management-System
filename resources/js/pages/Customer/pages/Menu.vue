<template>
  <div class="menu">
    <h1>Menu</h1>
    <ul>
      <!-- Loop through the food items and display them -->
      <li v-for="food in foods.data" :key="food.id">
        <div class="food-item">
          <h2>{{ food.name }}</h2>
          <p>{{ food.description }}</p>
          <p>${{ food.price }}</p>
        </div>
      </li>
    </ul>

    <!-- Pagination controls -->
    <div v-if="foods.links">
      <button 
        v-for="(link, index) in foods.links" 
        :key="index" 
        @click="fetchMenu(link.url)"
        :disabled="!link.url"
      >
        {{ link.label }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      foods: {
        data: [],  // Food items
        links: []  // Pagination links
      },
    };
  },
  mounted() {
    // Fetch the menu when the component is mounted
    this.fetchMenu();
  },
  methods: {
    async fetchMenu(url = '/api/menu') {
      try {
        const response = await fetch(url);  // Use fetch to get data
        const data = await response.json();  // Parse the JSON response
        this.foods = data;  // Store the food items and pagination links
      } catch (error) {
        console.error('Error fetching menu data:', error);
      }
    },
  },
};
</script>

<style scoped>
/* Add styling for the menu page */
.menu {
  padding: 20px;
}
.food-item {
  margin-bottom: 20px;
}
</style>
