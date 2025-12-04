<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Orders Management</h1>
          <p class="text-white/80">View and manage all restaurant orders</p>
        </div>
        <Link
          :href="route('admin.kitchen.index')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          🍳 Kitchen Display
        </Link>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <GlassCard class="text-white">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.today.total }}</div>
            <div class="text-sm text-white/60">Total Today</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-yellow-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.today.pending }}</div>
            <div class="text-sm text-white/60">Pending</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-blue-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.today.preparing }}</div>
            <div class="text-sm text-white/60">Preparing</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-green-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.today.ready }}</div>
            <div class="text-sm text-white/60">Ready</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-gray-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">${{ parseFloat(stats.today.revenue).toFixed(2) }}</div>
            <div class="text-sm text-white/60">Revenue</div>
          </div>
        </GlassCard>
      </div>

      <!-- Filters -->
      <GlassCard class="text-white mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium mb-2">Status</label>
            <select
              v-model="filters.status"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg bg-white/95 text-gray-900"
            >
              <option value="all">All Statuses</option>
              <option value="pending">Pending</option>
              <option value="preparing">Preparing</option>
              <option value="ready">Ready</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Date</label>
            <input
              v-model="filters.date"
              type="date"
              @change="applyFilters"
              class="glass-input w-full px-4 py-2 rounded-lg text-white"
            />
          </div>
          <div>
            <label class="block text-sm font-medium mb-2">Search</label>
            <input
              v-model="filters.search"
              @input="applySearch"
              type="text"
              placeholder="Order ID, customer, food..."
              class="glass-input w-full px-4 py-2 rounded-lg text-white placeholder-white/50"
            />
          </div>
        </div>
      </GlassCard>

      <!-- Orders List -->
      <div class="space-y-4">
        <GlassCard
          v-for="order in orders.data"
          :key="order.id"
          class="text-white hover:scale-[1.02] transition-transform cursor-pointer"
          @click="$inertia.visit(route('admin.orders.show', order.id))"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1">
              <div class="flex items-center space-x-4 mb-3">
                <div>
                  <h3 class="text-xl font-bold">Order #{{ order.id }}</h3>
                  <p class="text-sm text-white/60">
                    {{ new Date(order.created_at).toLocaleString() }}
                  </p>
                </div>
                <div
                  :class="[
                    'px-3 py-1 rounded-full text-sm font-semibold',
                    statusColors[order.status] || 'bg-gray-500/30'
                  ]"
                >
                  {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                </div>
                <div v-if="order.priority > 0" class="px-3 py-1 bg-red-500/30 text-red-300 rounded-full text-sm">
                  ⚡ Priority: {{ order.priority }}
                </div>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                <div>
                  <p class="text-sm text-white/60">Item</p>
                  <p class="font-semibold">{{ order.food?.name }}</p>
                  <p class="text-sm text-white/70">Qty: {{ order.quantity }} × ${{ parseFloat(order.price).toFixed(2) }}</p>
                </div>
                <div>
                  <p class="text-sm text-white/60">Customer</p>
                  <p class="font-semibold">{{ order.customer?.name || 'Guest' }}</p>
                  <p v-if="order.table" class="text-sm text-white/70">Table: {{ order.table.table_number }}</p>
                </div>
                <div>
                  <p class="text-sm text-white/60">Total Amount</p>
                  <p class="text-xl font-bold text-green-400">${{ parseFloat(order.total_amount).toFixed(2) }}</p>
                </div>
              </div>

              <div v-if="order.order_notes" class="mt-2 p-2 bg-blue-500/20 rounded text-sm">
                <strong>Notes:</strong> {{ order.order_notes }}
              </div>
              <div v-if="order.kitchen_notes" class="mt-2 p-2 bg-yellow-500/20 rounded text-sm">
                <strong>Kitchen Notes:</strong> {{ order.kitchen_notes }}
              </div>

              <div v-if="order.preparing_at || order.estimated_completion_at" class="mt-2 text-sm text-white/60">
                <span v-if="order.preparing_at">
                  Started: {{ new Date(order.preparing_at).toLocaleTimeString() }}
                </span>
                <span v-if="order.estimated_completion_at" class="ml-4">
                  ETA: {{ new Date(order.estimated_completion_at).toLocaleTimeString() }}
                </span>
              </div>
            </div>

            <div class="ml-4 flex flex-col space-y-2">
              <Link
                :href="route('admin.orders.show', order.id)"
                @click.stop
                class="glass-button px-4 py-2 rounded text-sm text-center"
              >
                View Details
              </Link>
              <button
                v-if="order.status === 'pending' && (hasPermission('orders-update') || isAdmin)"
                @click.stop="updateStatus(order.id, 'preparing')"
                class="glass-button bg-blue-500/30 hover:bg-blue-500/40 px-4 py-2 rounded text-sm"
              >
                Start Preparing
              </button>
              <button
                v-if="order.status === 'preparing' && (hasPermission('orders-update') || isAdmin)"
                @click.stop="updateStatus(order.id, 'ready')"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-4 py-2 rounded text-sm"
              >
                Mark Ready
              </button>
              <button
                v-if="order.status === 'ready' && (hasPermission('orders-update') || isAdmin)"
                @click.stop="updateStatus(order.id, 'completed')"
                class="glass-button bg-gray-500/30 hover:bg-gray-500/40 px-4 py-2 rounded text-sm"
              >
                Complete
              </button>
            </div>
          </div>
        </GlassCard>
      </div>

      <!-- Empty State -->
      <div v-if="orders.data.length === 0" class="text-center py-12">
        <GlassCard class="text-white">
          <div class="text-4xl mb-4">📋</div>
          <h3 class="text-xl font-bold mb-2">No Orders Found</h3>
          <p class="text-white/70">No orders match your filters</p>
        </GlassCard>
      </div>

      <!-- Pagination -->
      <div v-if="orders.links && orders.links.length > 1" class="mt-6 flex justify-center space-x-2">
        <Link
          v-for="link in orders.links"
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
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { usePermissions } from '@/composables/usePermissions'

const props = defineProps({
  orders: Object,
  stats: Object,
  filters: Object,
})

const { hasPermission, user } = usePermissions()
const isAdmin = computed(() => user.value?.is_admin || false)

const statusColors = {
  pending: 'bg-yellow-500/30 text-yellow-300',
  preparing: 'bg-blue-500/30 text-blue-300',
  ready: 'bg-green-500/30 text-green-300',
  completed: 'bg-gray-500/30 text-gray-300',
  cancelled: 'bg-red-500/30 text-red-300',
}

const filters = ref(props.filters)
let searchTimeout = null

const applyFilters = () => {
  router.get(route('admin.orders.index'), filters.value, {
    preserveState: true,
    preserveScroll: true,
  })
}

const applySearch = () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    applyFilters()
  }, 500)
}

const updateStatus = (id, status) => {
  router.put(route('admin.orders.update', id), {
    status,
  }, {
    preserveScroll: true,
  })
}
</script>

