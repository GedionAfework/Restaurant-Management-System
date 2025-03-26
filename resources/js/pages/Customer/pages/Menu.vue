<template>
  <div class="min-h-screen bg-black text-white">
    <Navbar />
    <div class="container mx-auto p-6">
      <h1 class="text-4xl font-extrabold mb-10 text-center text-teal-400 tracking-wide">Our Delicious Menu</h1>
      <div v-if="foods.data.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div v-for="food in foods.data" :key="food.id" class="food-item bg-gray-900 rounded-xl p-5 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
          <div class="relative overflow-hidden rounded-lg mb-4">
            <img v-if="food.picture" :src="'/storage/' + food.picture" :alt="food.name" class="w-full h-64 object-cover transition-transform duration-500 hover:scale-110" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
          </div>
          <h2 class="text-2xl font-bold text-gray-100 mb-2">{{ food.name }}</h2>
          <p class="text-gray-400 text-sm mb-2 italic">{{ food.type || 'N/A' }}</p>
          <p class="text-gray-300 mb-4 line-clamp-2">{{ food.description || 'No description available' }}</p>
          <p class="text-teal-400 font-bold text-xl mb-4">${{ food.price }}</p>
          <div class="flex items-center space-x-4">
            <input type="number" v-model.number="foodQuantities[food.id]" min="1" max="99" placeholder="1"
              class="w-16 p-2 bg-gray-800 border border-gray-600 rounded-lg text-gray-100 focus:ring-2 focus:ring-teal-400 focus:border-teal-400" />
            <button v-if="isAuthenticated" @click="orderFood(food.id)" class="bg-teal-500 text-gray-900 px-6 py-2 rounded-lg font-semibold hover:bg-teal-600 transition-colors">
              Order Now
            </button>
            <button v-else @click="redirectToLogin" class="bg-gray-500 text-white px-6 py-2 rounded-lg font-semibold hover:bg-gray-600 transition-colors">
              Login to Order
            </button>
          </div>
        </div>
      </div>
      <div v-else class="text-center text-gray-400">
        <p>No menu items available at the moment.</p>
      </div>

      <!-- Pagination -->
      <div v-if="foods.links.length" class="mt-10 flex justify-center space-x-3">
        <button v-for="link in foods.links" :key="link.label" @click="fetchMenu(link.url)" v-html="link.label"
          :class="{ 'font-bold text-teal-400 bg-gray-800': link.active, 'text-gray-400 hover:text-teal-500': !link.active }"
          class="px-4 py-2 rounded-lg transition-colors" :disabled="!link.url" />
      </div>
    </div>
    <Footer />
  </div>
</template>

<script setup>
import Navbar from '@/pages/Customer/Navbar.vue';
import Footer from '@/pages/Customer/Footer.vue';
import { ref, onMounted, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const foods = ref({
  data: [],
  links: [],
});

const foodQuantities = ref({});

const isAuthenticated = computed(() => !!page.props.auth?.customer);

const fetchMenu = async (url = '/api/food') => {
  try {
    const response = await fetch(url, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    });
    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
    const data = await response.json();
    foods.value = data;
    console.log('Fetched menu data:', data);
    data.data.forEach(food => {
      if (!foodQuantities.value[food.id]) {
        foodQuantities.value[food.id] = 1;
      }
    });
  } catch (error) {
    console.error('Error fetching menu data:', error);
  }
};

const orderFood = (foodId) => {
  const quantity = foodQuantities.value[foodId] || 1;
  const food = foods.value.data.find(f => f.id === foodId);
  const customerId = page.props.auth?.customer?.id;
  const totalAmount = (food.price * quantity).toFixed(2);

  console.log(`Ordering ${quantity} of ${food.name} (ID: ${foodId})`);

  router.post('/api/orders', {
    customer_id: customerId,
    food_id: foodId,
    quantity: quantity,
    total_amount: totalAmount,
  }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (page) => {
      console.log('Order response:', page.props.response || page.props);
      alert(`Added ${quantity} x ${food.name} to your order!`);
    },
    onError: (errors) => {
      console.error('Error placing order:', errors);
      alert('Failed to place order: ' + (errors.food_id || errors.customer_id || errors.quantity || errors.total_amount || 'Unknown error'));
    },
  });
};

const redirectToLogin = () => {
  router.visit('/login');
};

onMounted(() => {
  fetchMenu();
});
</script>

<style scoped>
.food-item {
  transition: all 0.3s ease;
}

.food-item:hover {
  box-shadow: 0 8px 25px rgba(0, 255, 209, 0.3);
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>