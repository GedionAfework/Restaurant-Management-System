<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Table Management</h1>
          <p class="text-white/80">Manage restaurant tables and their status</p>
        </div>
        <Link
          v-if="hasPermission('tables-manage') || isAdmin"
          :href="route('admin.tables.create')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          ➕ Create Table
        </Link>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <GlassCard class="text-white text-center">
          <p class="text-sm text-white/70 mb-1">Total</p>
          <p class="text-2xl font-bold">{{ stats.total }}</p>
        </GlassCard>
        <GlassCard class="text-white text-center">
          <p class="text-sm text-white/70 mb-1">Available</p>
          <p class="text-2xl font-bold text-green-400">{{ stats.available }}</p>
        </GlassCard>
        <GlassCard class="text-white text-center">
          <p class="text-sm text-white/70 mb-1">Occupied</p>
          <p class="text-2xl font-bold text-red-400">{{ stats.occupied }}</p>
        </GlassCard>
        <GlassCard class="text-white text-center">
          <p class="text-sm text-white/70 mb-1">Reserved</p>
          <p class="text-2xl font-bold text-yellow-400">{{ stats.reserved }}</p>
        </GlassCard>
        <GlassCard class="text-white text-center">
          <p class="text-sm text-white/70 mb-1">Cleaning</p>
          <p class="text-2xl font-bold text-blue-400">{{ stats.cleaning }}</p>
        </GlassCard>
      </div>

      <!-- Filters -->
      <GlassCard class="text-white mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg"
            >
              <option value="all">All Statuses</option>
              <option value="available">Available</option>
              <option value="occupied">Occupied</option>
              <option value="reserved">Reserved</option>
              <option value="cleaning">Cleaning</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Zone</label>
            <select
              v-model="filters.zone"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg"
            >
              <option value="all">All Zones</option>
              <option v-for="zone in zones" :key="zone" :value="zone">{{ zone }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Floor</label>
            <select
              v-model="filters.floor"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg"
            >
              <option value="all">All Floors</option>
              <option v-for="floor in floors" :key="floor" :value="floor">Floor {{ floor }}</option>
            </select>
          </div>
          <div class="flex items-end">
            <Link
              :href="route('admin.tables.layout')"
              class="glass-button w-full text-center py-2 rounded-lg"
            >
              📐 Visual Layout
            </Link>
          </div>
        </div>
      </GlassCard>

      <!-- Tables Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <GlassCard
          v-for="table in tables"
          :key="table.table_id"
          class="text-white hover:scale-105 transition-transform cursor-pointer"
          @click="$inertia.visit(route('admin.tables.show', table.table_id))"
        >
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center space-x-2">
              <span class="text-2xl">{{ getShapeIcon(table.shape) }}</span>
              <div>
                <h3 class="text-xl font-bold">Table {{ table.table_number }}</h3>
                <p class="text-xs text-white/60">{{ table.location || 'No location' }}</p>
              </div>
            </div>
            <span
              :class="[
                'px-2 py-1 rounded text-xs font-semibold',
                getStatusClass(table.status)
              ]"
            >
              {{ table.status }}
            </span>
          </div>

          <div class="space-y-2 text-sm">
            <div class="flex justify-between">
              <span class="text-white/70">Capacity:</span>
              <span class="font-semibold">{{ table.capacity }} guests</span>
            </div>
            <div v-if="table.zone" class="flex justify-between">
              <span class="text-white/70">Zone:</span>
              <span class="font-semibold">{{ table.zone }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-white/70">Floor:</span>
              <span class="font-semibold">{{ table.floor }}</span>
            </div>
          </div>

          <div class="mt-4 pt-4 border-t border-white/20 flex space-x-2">
            <Link
              :href="route('admin.tables.edit', table.table_id)"
              @click.stop
              v-if="hasPermission('tables-manage') || isAdmin"
              class="flex-1 glass-button text-center py-2 rounded text-sm"
            >
              ✏️ Edit
            </Link>
            <button
              v-if="table.status === 'occupied' && (hasPermission('tables-assign') || isAdmin)"
              @click.stop="releaseTable(table.table_id)"
              class="flex-1 glass-button bg-green-500/30 hover:bg-green-500/40 text-center py-2 rounded text-sm"
            >
              ✅ Release
            </button>
          </div>
        </GlassCard>
      </div>

      <!-- Empty State -->
      <div v-if="tables.length === 0" class="text-center py-12">
        <GlassCard class="text-white">
          <div class="text-4xl mb-4">🪑</div>
          <h3 class="text-xl font-bold mb-2">No Tables Found</h3>
          <p class="text-white/70 mb-4">
            {{ hasPermission('tables-manage') || isAdmin ? 'Create your first table to get started!' : 'No tables match your filters.' }}
          </p>
          <Link
            v-if="hasPermission('tables-manage') || isAdmin"
            :href="route('admin.tables.create')"
            class="glass-button px-6 py-3 rounded-lg inline-block"
          >
            ➕ Create Table
          </Link>
        </GlassCard>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { usePermissions } from '@/composables/usePermissions'

const props = defineProps({
  tables: Array,
  stats: Object,
  filters: Object,
  zones: Array,
  floors: Array,
})

const { hasPermission, user } = usePermissions()
const isAdmin = computed(() => user.value?.is_admin || false)

const getStatusClass = (status) => {
  const classes = {
    available: 'bg-green-500/30 text-green-300',
    occupied: 'bg-red-500/30 text-red-300',
    reserved: 'bg-yellow-500/30 text-yellow-300',
    cleaning: 'bg-blue-500/30 text-blue-300',
  }
  return classes[status] || 'bg-gray-500/30 text-gray-300'
}

const getShapeIcon = (shape) => {
  const icons = {
    round: '⭕',
    square: '⬜',
    rectangular: '▭',
    booth: '🪑',
  }
  return icons[shape] || '⭕'
}

const applyFilters = () => {
  router.get(route('admin.tables.index'), props.filters, {
    preserveState: true,
    preserveScroll: true,
  })
}

const releaseTable = (id) => {
  if (confirm('Release this table and mark it as available?')) {
    router.post(route('admin.tables.release', id), {}, {
      preserveScroll: true,
      onSuccess: () => {
        // Table will be refreshed automatically
      },
    })
  }
}
</script>

