<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Kitchen Display</h1>
          <p class="text-white/80">Monitor and manage orders in real-time</p>
        </div>
        <div class="flex space-x-4">
          <button
            @click="refreshOrders"
            class="glass-button px-4 py-2 rounded-lg text-white"
            :disabled="refreshing"
          >
            {{ refreshing ? 'Refreshing...' : '🔄 Refresh' }}
          </button>
          <Link
            :href="route('admin.orders.index')"
            class="glass-button px-4 py-2 rounded-lg text-white"
          >
            📋 All Orders
          </Link>
        </div>
      </div>

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <GlassCard class="text-white bg-yellow-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.pending }}</div>
            <div class="text-sm text-white/60">Pending</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-blue-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.preparing }}</div>
            <div class="text-sm text-white/60">Preparing</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-green-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.ready }}</div>
            <div class="text-sm text-white/60">Ready</div>
          </div>
        </GlassCard>
        <GlassCard class="text-white bg-gray-500/20">
          <div class="text-center">
            <div class="text-3xl font-bold">{{ stats.completed_today }}</div>
            <div class="text-sm text-white/60">Completed Today</div>
          </div>
        </GlassCard>
      </div>

      <!-- Orders by Status -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Pending Orders -->
        <div class="space-y-4">
          <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
            <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2 animate-pulse"></span>
            Pending ({{ groupedOrders.pending.length }})
          </h2>
          <div class="space-y-4">
            <GlassCard
              v-for="order in groupedOrders.pending"
              :key="order.id"
              class="text-white hover:scale-105 transition-transform"
              :class="{ 'border-2 border-red-500': order.priority > 5 }"
            >
              <div class="space-y-3">
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="text-lg font-bold">#{{ order.id }}</h3>
                    <p class="text-sm text-white/60">{{ order.table?.table_number || 'Takeout' }}</p>
                  </div>
                  <div
                    v-if="order.priority > 0"
                    class="px-2 py-1 bg-red-500/30 text-red-300 rounded text-xs font-bold"
                  >
                    Priority {{ order.priority }}
                  </div>
                </div>

                <div>
                  <p class="font-semibold">{{ order.food?.name }}</p>
                  <p class="text-sm text-white/70">Qty: {{ order.quantity }}</p>
                </div>

                <div v-if="order.order_notes" class="p-2 bg-blue-500/20 rounded text-xs">
                  {{ order.order_notes }}
                </div>

                <div class="text-xs text-white/60">
                  {{ new Date(order.created_at).toLocaleTimeString() }}
                </div>

                <div class="flex space-x-2 pt-2 border-t border-white/20">
                  <button
                    @click="updateOrderStatus(order.id, 'preparing')"
                    class="flex-1 glass-button bg-blue-500/30 hover:bg-blue-500/40 px-3 py-2 rounded text-sm"
                  >
                    Start
                  </button>
                  <button
                    @click="showNotesModal(order)"
                    class="glass-button px-3 py-2 rounded text-sm"
                  >
                    📝
                  </button>
                </div>
              </div>
            </GlassCard>
          </div>
        </div>

        <!-- Preparing Orders -->
        <div class="space-y-4">
          <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
            <span class="w-3 h-3 bg-blue-400 rounded-full mr-2"></span>
            Preparing ({{ groupedOrders.preparing.length }})
          </h2>
          <div class="space-y-4">
            <GlassCard
              v-for="order in groupedOrders.preparing"
              :key="order.id"
              class="text-white hover:scale-105 transition-transform"
              :class="{ 'border-2 border-red-500': order.is_overdue }"
            >
              <div class="space-y-3">
                <div class="flex items-start justify-between">
                  <div>
                    <h3 class="text-lg font-bold">#{{ order.id }}</h3>
                    <p class="text-sm text-white/60">{{ order.table?.table_number || 'Takeout' }}</p>
                  </div>
                  <div
                    v-if="order.is_overdue"
                    class="px-2 py-1 bg-red-500/30 text-red-300 rounded text-xs font-bold animate-pulse"
                  >
                    OVERDUE
                  </div>
                </div>

                <div>
                  <p class="font-semibold">{{ order.food?.name }}</p>
                  <p class="text-sm text-white/70">Qty: {{ order.quantity }}</p>
                </div>

                <div v-if="order.estimated_completion_at" class="text-xs">
                  <div class="text-white/60">ETA:</div>
                  <div
                    :class="[
                      'font-bold',
                      order.is_overdue ? 'text-red-400' : 'text-green-400'
                    ]"
                  >
                    {{ formatTime(order.estimated_completion_at) }}
                  </div>
                  <div v-if="order.estimated_time_remaining" class="text-white/60 mt-1">
                    {{ order.estimated_time_remaining }} min remaining
                  </div>
                </div>

                <div v-if="order.kitchen_notes" class="p-2 bg-yellow-500/20 rounded text-xs">
                  {{ order.kitchen_notes }}
                </div>

                <div class="text-xs text-white/60">
                  Started: {{ new Date(order.preparing_at).toLocaleTimeString() }}
                </div>

                <div class="flex space-x-2 pt-2 border-t border-white/20">
                  <button
                    @click="updateOrderStatus(order.id, 'ready')"
                    class="flex-1 glass-button bg-green-500/30 hover:bg-green-500/40 px-3 py-2 rounded text-sm"
                  >
                    Ready
                  </button>
                  <button
                    @click="showNotesModal(order)"
                    class="glass-button px-3 py-2 rounded text-sm"
                  >
                    📝
                  </button>
                </div>
              </div>
            </GlassCard>
          </div>
        </div>

        <!-- Ready Orders -->
        <div class="space-y-4">
          <h2 class="text-2xl font-bold text-white mb-4 flex items-center">
            <span class="w-3 h-3 bg-green-400 rounded-full mr-2"></span>
            Ready ({{ groupedOrders.ready.length }})
          </h2>
          <div class="space-y-4">
            <GlassCard
              v-for="order in groupedOrders.ready"
              :key="order.id"
              class="text-white hover:scale-105 transition-transform border-2 border-green-500/50"
            >
              <div class="space-y-3">
                <div>
                  <h3 class="text-lg font-bold">#{{ order.id }}</h3>
                  <p class="text-sm text-white/60">{{ order.table?.table_number || 'Takeout' }}</p>
                </div>

                <div>
                  <p class="font-semibold">{{ order.food?.name }}</p>
                  <p class="text-sm text-white/70">Qty: {{ order.quantity }}</p>
                </div>

                <div class="text-xs text-white/60">
                  Ready at: {{ new Date(order.ready_at).toLocaleTimeString() }}
                </div>

                <div class="flex space-x-2 pt-2 border-t border-white/20">
                  <button
                    @click="updateOrderStatus(order.id, 'completed')"
                    class="flex-1 glass-button bg-gray-500/30 hover:bg-gray-500/40 px-3 py-2 rounded text-sm"
                  >
                    Complete
                  </button>
                </div>
              </div>
            </GlassCard>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="orders.length === 0" class="text-center py-12">
        <GlassCard class="text-white">
          <div class="text-4xl mb-4">🍳</div>
          <h3 class="text-xl font-bold mb-2">No Active Orders</h3>
          <p class="text-white/70">All caught up!</p>
        </GlassCard>
      </div>

      <!-- Notes Modal -->
      <div
        v-if="selectedOrder"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        @click.self="closeNotesModal"
      >
        <GlassCard class="text-white max-w-md w-full mx-4">
          <h3 class="text-xl font-bold mb-4">Order #{{ selectedOrder.id }} - Kitchen Notes</h3>
          <textarea
            v-model="kitchenNotes"
            rows="4"
            class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 mb-4"
            placeholder="Add kitchen notes..."
          ></textarea>
          <div class="flex justify-end space-x-2">
            <button
              @click="closeNotesModal"
              class="glass-button px-4 py-2 rounded"
            >
              Cancel
            </button>
            <button
              @click="saveKitchenNotes"
              class="glass-button bg-green-500/30 hover:bg-green-500/40 px-4 py-2 rounded"
            >
              Save Notes
            </button>
          </div>
        </GlassCard>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'

const props = defineProps({
  orders: Array,
  groupedOrders: Object,
  stats: Object,
})

const refreshing = ref(false)
const selectedOrder = ref(null)
const kitchenNotes = ref('')
let refreshInterval = null

const formatTime = (dateString) => {
  return new Date(dateString).toLocaleTimeString()
}

const refreshOrders = async () => {
  refreshing.value = true
  router.reload({ only: ['orders', 'groupedOrders', 'stats'] })
  setTimeout(() => {
    refreshing.value = false
  }, 500)
}

const updateOrderStatus = (id, status) => {
  router.post(route('admin.kitchen.update-status', id), {
    status,
  }, {
    preserveScroll: true,
    preserveState: true,
  })
}

const showNotesModal = (order) => {
  selectedOrder.value = order
  kitchenNotes.value = order.kitchen_notes || ''
}

const closeNotesModal = () => {
  selectedOrder.value = null
  kitchenNotes.value = ''
}

const saveKitchenNotes = () => {
  if (!selectedOrder.value) return
  
  router.post(route('admin.kitchen.update-status', selectedOrder.value.id), {
    status: selectedOrder.value.status,
    kitchen_notes: kitchenNotes.value,
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      closeNotesModal()
    },
  })
}

onMounted(() => {
  // Auto-refresh every 30 seconds
  refreshInterval = setInterval(() => {
    refreshOrders()
  }, 30000)
})

onUnmounted(() => {
  if (refreshInterval) {
    clearInterval(refreshInterval)
  }
})
</script>

