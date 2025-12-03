<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Menu Categories</h1>
          <p class="text-white/80">Organize your menu items into categories</p>
        </div>
        <Link
          v-if="hasPermission('menu-create') || isAdmin"
          :href="route('menu-categories.create')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          ➕ Create Category
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <GlassCard
          v-for="category in categories"
          :key="category.category_id"
          class="text-white hover:scale-105 transition-transform cursor-pointer"
        >
          <div class="flex items-start justify-between mb-4">
            <div class="flex items-center space-x-3">
              <div class="text-3xl">{{ category.icon || '📋' }}</div>
              <div>
                <h3 class="text-xl font-bold">{{ category.category_name }}</h3>
                <p class="text-sm text-white/60">{{ category.food_items_count }} items</p>
              </div>
            </div>
            <span
              :class="[
                'px-2 py-1 rounded text-xs font-semibold',
                category.is_active ? 'bg-green-500/30 text-green-300' : 'bg-gray-500/30 text-gray-300'
              ]"
            >
              {{ category.is_active ? 'Active' : 'Inactive' }}
            </span>
          </div>

          <p v-if="category.description" class="text-white/70 text-sm mb-4 line-clamp-2">
            {{ category.description }}
          </p>

          <div class="flex items-center justify-between text-xs text-white/60 mb-4">
            <span>Order: {{ category.display_order }}</span>
            <span v-if="category.image">📷 Has Image</span>
          </div>

          <div class="flex space-x-2 pt-4 border-t border-white/20">
            <Link
              :href="route('menu-categories.edit', category.category_id)"
              @click.stop
              v-if="hasPermission('menu-edit') || isAdmin"
              class="flex-1 glass-button text-center py-2 rounded text-sm"
            >
              ✏️ Edit
            </Link>
            <button
              @click.stop="deleteCategory(category.category_id)"
              v-if="hasPermission('menu-delete') || isAdmin"
              class="flex-1 glass-button bg-red-500/30 hover:bg-red-500/40 text-center py-2 rounded text-sm"
              :disabled="category.food_items_count > 0"
              :class="{ 'opacity-50 cursor-not-allowed': category.food_items_count > 0 }"
            >
              🗑️ Delete
            </button>
          </div>
        </GlassCard>
      </div>

      <div v-if="categories.length === 0" class="text-center py-12">
        <GlassCard class="text-white">
          <div class="text-4xl mb-4">📋</div>
          <h3 class="text-xl font-bold mb-2">No Categories Yet</h3>
          <p class="text-white/70 mb-4">Create your first category to organize menu items</p>
          <Link
            v-if="hasPermission('menu-create') || isAdmin"
            :href="route('menu-categories.create')"
            class="glass-button px-6 py-3 rounded-lg inline-block"
          >
            ➕ Create Category
          </Link>
        </GlassCard>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { usePermissions } from '@/composables/usePermissions'

const props = defineProps({
  categories: Array,
})

const { hasPermission, user } = usePermissions()
const isAdmin = computed(() => user.value?.is_admin || false)

const deleteCategory = (id) => {
  if (confirm('Are you sure you want to delete this category?')) {
    router.delete(route('menu-categories.destroy', id))
  }
}
</script>

