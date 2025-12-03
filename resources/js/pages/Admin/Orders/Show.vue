<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <Link
          :href="route('admin.orders.index')"
          class="text-white/80 hover:text-white mb-4 inline-block"
        >
          ← Back to Orders
        </Link>
        <h1 class="text-4xl font-bold text-white mb-2">Order #{{ order.id }}</h1>
        <p class="text-white/80">Order details and management</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Order Details -->
        <div class="lg:col-span-2 space-y-6">
          <GlassCard class="text-white">
            <h2 class="text-2xl font-bold mb-4">Order Information</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-white/60">Status</p>
                <div
                  :class="[
                    'inline-block px-3 py-1 rounded-full text-sm font-semibold mt-1',
                    statusColors[order.status] || 'bg-gray-500/30'
                  ]"
                >
                  {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
                </div>
              </div>
              <div>
                <p class="text-sm text-white/60">Priority</p>
                <p class="font-semibold mt-1">
                  <span v-if="order.priority > 0" class="text-red-400">⚡ {{ order.priority }}</span>
                  <span v-else>Normal</span>
                </p>
              </div>
              <div>
                <p class="text-sm text-white/60">Placed At</p>
                <p class="font-semibold mt-1">{{ new Date(order.created_at).toLocaleString() }}</p>
              </div>
              <div>
                <p class="text-sm text-white/60">Total Amount</p>
                <p class="text-xl font-bold text-green-400 mt-1">${{ parseFloat(order.total_amount).toFixed(2) }}</p>
              </div>
            </div>
          </GlassCard>

          <GlassCard class="text-white">
            <h2 class="text-2xl font-bold mb-4">Item Details</h2>
            <div class="space-y-4">
              <div class="flex items-start space-x-4">
                <img
                  v-if="order.food?.picture"
                  :src="`/storage/${order.food.picture}`"
                  :alt="order.food.name"
                  class="w-24 h-24 object-cover rounded-lg"
                />
                <div class="flex-1">
                  <h3 class="text-xl font-bold">{{ order.food?.name }}</h3>
                  <p class="text-white/70">{{ order.food?.description }}</p>
                  <div class="mt-2 flex items-center space-x-4">
                    <span class="text-sm">Quantity: <strong>{{ order.quantity }}</strong></span>
                    <span class="text-sm">Price: <strong>${{ parseFloat(order.price).toFixed(2) }}</strong></span>
                  </div>
                </div>
              </div>
            </div>
          </GlassCard>

          <GlassCard class="text-white">
            <h2 class="text-2xl font-bold mb-4">Customer Information</h2>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-white/60">Customer</p>
                <p class="font-semibold mt-1">{{ order.customer?.name || 'Guest' }}</p>
              </div>
              <div>
                <p class="text-sm text-white/60">Table</p>
                <p class="font-semibold mt-1">{{ order.table?.table_number || 'Takeout' }}</p>
              </div>
            </div>
          </GlassCard>

          <GlassCard v-if="order.order_notes || order.kitchen_notes" class="text-white">
            <h2 class="text-2xl font-bold mb-4">Notes</h2>
            <div class="space-y-3">
              <div v-if="order.order_notes">
                <p class="text-sm font-semibold mb-1">Customer Notes:</p>
                <p class="p-3 bg-blue-500/20 rounded">{{ order.order_notes }}</p>
              </div>
              <div v-if="order.kitchen_notes">
                <p class="text-sm font-semibold mb-1">Kitchen Notes:</p>
                <p class="p-3 bg-yellow-500/20 rounded">{{ order.kitchen_notes }}</p>
              </div>
            </div>
          </GlassCard>
        </div>

        <!-- Actions Sidebar -->
        <div class="space-y-6">
          <GlassCard class="text-white">
            <h2 class="text-xl font-bold mb-4">Quick Actions</h2>
            <div class="space-y-3">
              <button
                v-if="order.status === 'pending' && (hasPermission('orders-update') || isAdmin)"
                @click="updateStatus('preparing')"
                class="w-full glass-button bg-blue-500/30 hover:bg-blue-500/40 px-4 py-3 rounded-lg"
              >
                🍳 Start Preparing
              </button>
              <button
                v-if="order.status === 'preparing' && (hasPermission('orders-update') || isAdmin)"
                @click="updateStatus('ready')"
                class="w-full glass-button bg-green-500/30 hover:bg-green-500/40 px-4 py-3 rounded-lg"
              >
                ✅ Mark Ready
              </button>
              <button
                v-if="order.status === 'ready' && (hasPermission('orders-update') || isAdmin)"
                @click="updateStatus('completed')"
                class="w-full glass-button bg-gray-500/30 hover:bg-gray-500/40 px-4 py-3 rounded-lg"
              >
                ✓ Complete Order
              </button>
              <button
                v-if="order.status !== 'cancelled' && (hasPermission('orders-update') || isAdmin)"
                @click="updateStatus('cancelled')"
                class="w-full glass-button bg-red-500/30 hover:bg-red-500/40 px-4 py-3 rounded-lg"
              >
                ✕ Cancel Order
              </button>
            </div>
          </GlassCard>

          <GlassCard class="text-white">
            <h2 class="text-xl font-bold mb-4">Timeline</h2>
            <div class="space-y-3 text-sm">
              <div>
                <p class="text-white/60">Placed</p>
                <p class="font-semibold">{{ new Date(order.created_at).toLocaleString() }}</p>
              </div>
              <div v-if="order.preparing_at">
                <p class="text-white/60">Started Preparing</p>
                <p class="font-semibold">{{ new Date(order.preparing_at).toLocaleString() }}</p>
              </div>
              <div v-if="order.estimated_completion_at">
                <p class="text-white/60">Estimated Completion</p>
                <p class="font-semibold">{{ new Date(order.estimated_completion_at).toLocaleString() }}</p>
              </div>
              <div v-if="order.ready_at">
                <p class="text-white/60">Ready</p>
                <p class="font-semibold">{{ new Date(order.ready_at).toLocaleString() }}</p>
              </div>
              <div v-if="order.completed_at">
                <p class="text-white/60">Completed</p>
                <p class="font-semibold">{{ new Date(order.completed_at).toLocaleString() }}</p>
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
  order: Object,
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

const updateStatus = (status) => {
  router.put(route('admin.orders.update', props.order.id), {
    status,
  })
}
</script>

