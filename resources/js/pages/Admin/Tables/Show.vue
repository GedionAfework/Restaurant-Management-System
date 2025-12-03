<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <Link
          :href="route('admin.tables.index')"
          class="text-white/70 hover:text-white mb-4 inline-block"
        >
          ← Back to Tables
        </Link>
        <h1 class="text-4xl font-bold text-white mb-2">Table {{ table.table_number }}</h1>
        <p class="text-white/80">View table details and activity</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Table Details -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Basic Information -->
          <GlassCard class="text-white">
            <h3 class="text-xl font-bold mb-4">Table Information</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-white/70 mb-1">Table Number</p>
                <p class="text-lg font-semibold">{{ table.table_number }}</p>
              </div>
              <div>
                <p class="text-sm text-white/70 mb-1">Capacity</p>
                <p class="text-lg font-semibold">{{ table.capacity }} guests</p>
              </div>
              <div>
                <p class="text-sm text-white/70 mb-1">Shape</p>
                <p class="text-lg font-semibold">
                  <span class="text-2xl">{{ getShapeIcon(table.shape) }}</span>
                  {{ capitalize(table.shape) }}
                </p>
              </div>
              <div>
                <p class="text-sm text-white/70 mb-1">Status</p>
                <span
                  :class="[
                    'px-3 py-1 rounded text-sm font-semibold',
                    getStatusClass(table.status)
                  ]"
                >
                  {{ table.status }}
                </span>
              </div>
              <div v-if="table.location">
                <p class="text-sm text-white/70 mb-1">Location</p>
                <p class="text-lg font-semibold">{{ table.location }}</p>
              </div>
              <div v-if="table.zone">
                <p class="text-sm text-white/70 mb-1">Zone</p>
                <p class="text-lg font-semibold">{{ table.zone }}</p>
              </div>
              <div>
                <p class="text-sm text-white/70 mb-1">Floor</p>
                <p class="text-lg font-semibold">Floor {{ table.floor }}</p>
              </div>
            </div>
            <div v-if="table.notes" class="mt-4 pt-4 border-t border-white/20">
              <p class="text-sm text-white/70 mb-1">Notes</p>
              <p class="text-white/90">{{ table.notes }}</p>
            </div>
          </GlassCard>

          <!-- Active Order -->
          <GlassCard v-if="table.active_order" class="text-white">
            <h3 class="text-xl font-bold mb-4">Active Order</h3>
            <div class="space-y-3">
              <div class="p-3 glass rounded-lg">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="font-semibold">{{ table.active_order.food?.name || 'Unknown' }}</p>
                    <p class="text-sm text-white/60">
                      Qty: {{ table.active_order.quantity }} · 
                      Status: {{ table.active_order.status }}
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold">${{ parseFloat(table.active_order.total_amount).toFixed(2) }}</p>
                  </div>
                </div>
              </div>
              <Link
                :href="route('admin.orders.show', table.active_order.id)"
                class="glass-button text-center py-2 rounded-lg block"
              >
                View Order Details →
              </Link>
            </div>
          </GlassCard>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
          <GlassCard class="text-white">
            <h3 class="text-xl font-bold mb-4">Actions</h3>
            <div class="space-y-3">
              <Link
                :href="route('admin.tables.edit', table.table_id)"
                v-if="hasPermission('tables-manage') || isAdmin"
                class="glass-button w-full text-center py-3 rounded-lg block"
              >
                ✏️ Edit Table
              </Link>
              <button
                v-if="table.status === 'occupied' && (hasPermission('tables-assign') || isAdmin)"
                @click="releaseTable"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 w-full text-center py-3 rounded-lg block"
              >
                ✅ Release Table
              </button>
              <button
                v-if="table.status !== 'cleaning' && (hasPermission('tables-manage') || isAdmin)"
                @click="markCleaning"
                class="glass-button bg-blue-500/30 hover:bg-blue-500/40 w-full text-center py-3 rounded-lg block"
              >
                🧹 Mark as Cleaning
              </button>
            </div>
          </GlassCard>

          <!-- Statistics -->
          <GlassCard class="text-white">
            <h3 class="text-xl font-bold mb-4">Statistics</h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-white/70">Total Orders</span>
                <span class="font-semibold">{{ table.orders?.length || 0 }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-white/70">Reservations</span>
                <span class="font-semibold">{{ table.reservations?.length || 0 }}</span>
              </div>
            </div>
          </GlassCard>
        </div>
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
  table: Object,
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

const capitalize = (str) => {
  return str.charAt(0).toUpperCase() + str.slice(1)
}

const releaseTable = () => {
  if (confirm('Release this table and mark it as available?')) {
    router.post(route('admin.tables.release', props.table.table_id), {}, {
      preserveScroll: true,
    })
  }
}

const markCleaning = () => {
  router.put(route('admin.tables.update', props.table.table_id), {
    ...props.table,
    status: 'cleaning',
  }, {
    preserveScroll: true,
  })
}
</script>

