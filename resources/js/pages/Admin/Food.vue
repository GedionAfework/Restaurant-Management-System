<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Menu Items</h1>
          <p class="text-white/80">Manage your restaurant menu</p>
        </div>
        <Link
          v-if="hasPermission('menu-create') || isAdmin"
          :href="route('admin.food.create')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          ➕ Create Menu Item
        </Link>
      </div>

      <!-- Filters -->
      <GlassCard class="text-white mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Category</label>
            <select
              v-model="filters.category_id"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg bg-white/95 text-gray-900"
            >
              <option value="all">All Categories</option>
              <option
                v-for="category in categories"
                :key="category.category_id"
                :value="category.category_id"
              >
                {{ category.category_name }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Availability</label>
            <select
              v-model="filters.is_available"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg"
            >
              <option value="all">All Items</option>
              <option value="true">Available</option>
              <option value="false">Unavailable</option>
            </select>
          </div>
          <div class="flex items-end">
            <input
              v-model="searchQuery"
              @input="applySearch"
              type="text"
              placeholder="Search items..."
              class="glass-input w-full px-4 py-2 rounded-lg text-white placeholder-white/50"
            />
          </div>
        </div>
      </GlassCard>

      <!-- Menu Items Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <GlassCard
          v-for="food in foods.data"
          :key="food.id"
          class="text-white hover:scale-105 transition-transform cursor-pointer"
          @click="$inertia.visit(route('admin.food.edit', food.id))"
        >
          <div class="relative">
            <img
              v-if="food.picture"
              :src="`/storage/${food.picture}`"
              :alt="food.name"
              class="w-full h-48 object-cover rounded-lg mb-4"
            />
            <div
              v-else
              class="w-full h-48 bg-white/10 rounded-lg mb-4 flex items-center justify-center text-4xl"
            >
              🍽️
            </div>
            <div
              v-if="food.is_featured"
              class="absolute top-2 right-2 px-2 py-1 bg-yellow-500/30 text-yellow-300 rounded text-xs font-semibold"
            >
              ⭐ Featured
            </div>
            <div
              v-if="!food.is_available"
              class="absolute top-2 left-2 px-2 py-1 bg-red-500/30 text-red-300 rounded text-xs font-semibold"
            >
              Unavailable
            </div>
          </div>

          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-bold">{{ food.name }}</h3>
              <span class="text-lg font-semibold text-green-400">${{ parseFloat(food.price).toFixed(2) }}</span>
            </div>

            <p v-if="food.category" class="text-sm text-white/60">
              📋 {{ food.category.category_name }}
            </p>

            <p v-if="food.description" class="text-sm text-white/70 line-clamp-2">
              {{ food.description }}
            </p>

            <div v-if="food.tags && food.tags.length > 0" class="flex flex-wrap gap-2 mt-2">
              <span
                v-for="tag in food.tags"
                :key="tag"
                class="px-2 py-1 glass rounded-full text-xs"
              >
                {{ tag }}
              </span>
            </div>

            <div v-if="food.variants && food.variants.length > 0" class="text-xs text-white/60 mt-2">
              {{ food.variants.length }} variant(s) available
            </div>

            <div v-if="food.add_ons && food.add_ons.length > 0" class="text-xs text-white/60">
              {{ food.add_ons.length }} add-on(s) available
            </div>
          </div>

          <div class="mt-4 pt-4 border-t border-white/20 flex space-x-2">
            <Link
              :href="route('admin.food.edit', food.id)"
              @click.stop
              v-if="hasPermission('menu-edit') || isAdmin"
              class="flex-1 glass-button text-center py-2 rounded text-sm"
            >
              ✏️ Edit
            </Link>
            <button
              @click.stop="deleteFood(food.id)"
              v-if="hasPermission('menu-delete') || isAdmin"
              class="flex-1 glass-button bg-red-500/30 hover:bg-red-500/40 text-center py-2 rounded text-sm"
            >
              🗑️ Delete
            </button>
          </div>
        </GlassCard>
      </div>

      <!-- Empty State -->
      <div v-if="foods.data.length === 0" class="text-center py-12">
        <GlassCard class="text-white">
          <div class="text-4xl mb-4">🍽️</div>
          <h3 class="text-xl font-bold mb-2">No Menu Items Found</h3>
          <p class="text-white/70 mb-4">
            {{ hasPermission('menu-create') || isAdmin ? 'Create your first menu item!' : 'No items match your filters.' }}
          </p>
          <Link
            v-if="hasPermission('menu-create') || isAdmin"
            :href="route('admin.food.create')"
            class="glass-button px-6 py-3 rounded-lg inline-block"
          >
            ➕ Create Menu Item
          </Link>
        </GlassCard>
      </div>

      <!-- Pagination -->
      <div v-if="foods.links && foods.links.length > 1" class="mt-6 flex justify-center space-x-2">
        <Link
          v-for="link in foods.links"
          :key="link.label"
          :href="link.url || '#'"
          :class="[
            'px-4 py-2 glass rounded',
            link.active ? 'bg-white/30 font-bold' : '',
            !link.url ? 'opacity-50 cursor-not-allowed' : '',
          ]"
          v-html="link.label"
        ></Link>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AdminLayout from './Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { usePermissions } from '@/composables/usePermissions'

const props = defineProps({
  foods: Object,
  categories: Array,
  filters: Object,
})

const { hasPermission, user } = usePermissions()
const isAdmin = computed(() => user.value?.is_admin || false)

const searchQuery = ref('')
let searchTimeout = null

const applyFilters = () => {
  router.get(route('admin.food'), props.filters, {
    preserveState: true,
    preserveScroll: true,
  })
}

const applySearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    router.get(route('admin.food'), {
      ...props.filters,
      search: searchQuery.value,
    }, {
      preserveState: true,
      preserveScroll: true,
    })
  }, 500)
}

const deleteFood = (id) => {
  if (confirm('Are you sure you want to delete this menu item?')) {
    router.delete(route('admin.food.destroy', id))
  }
}
</script>
