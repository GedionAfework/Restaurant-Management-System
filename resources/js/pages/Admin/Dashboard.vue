<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <!-- Flash Messages -->
      <FlashMessage />

      <!-- Welcome Header -->
      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">
          {{ greeting }}, {{ userName || 'Admin' }}!
        </h1>
        <p class="text-white/80">{{ currentDate }}</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="flex items-center justify-center h-64">
        <div class="text-white text-xl">Loading dashboard data...</div>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="glass-card p-6 text-white text-center">
        <div class="text-4xl mb-4">⚠️</div>
        <h3 class="text-xl font-bold mb-2">Error Loading Dashboard</h3>
        <p class="text-white/70">{{ error }}</p>
        <button
          @click="refresh"
          class="glass-button mt-4 px-6 py-2 rounded-lg"
        >
          Retry
        </button>
      </div>

      <!-- Dashboard Content -->
      <div v-else>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
          <!-- Revenue Today -->
          <GlassCard class="text-white hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-white/70 mb-1">Revenue Today</p>
                <p class="text-3xl font-bold">${{ formatCurrency(stats.revenue.today) }}</p>
                <p class="text-xs text-green-400 mt-1">
                  Week: ${{ formatCurrency(stats.revenue.week) }}
                </p>
              </div>
              <div class="text-4xl animate-pulse">💰</div>
            </div>
          </GlassCard>

          <!-- Orders Today -->
          <GlassCard class="text-white hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-white/70 mb-1">Orders Today</p>
                <p class="text-3xl font-bold">{{ stats.orders.today }}</p>
                <p class="text-xs text-yellow-400 mt-1">
                  Pending: {{ stats.orders.pending }}
                </p>
              </div>
              <div class="text-4xl">📦</div>
            </div>
          </GlassCard>

          <!-- Active Tables -->
          <GlassCard class="text-white hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-white/70 mb-1">Tables</p>
                <p class="text-3xl font-bold">{{ stats.tables.active }}/{{ stats.tables.total }}</p>
                <p class="text-xs text-blue-400 mt-1">
                  Available: {{ stats.tables.available }}
                </p>
              </div>
              <div class="text-4xl">🪑</div>
            </div>
          </GlassCard>

          <!-- Total Customers -->
          <GlassCard class="text-white hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-sm text-white/70 mb-1">Total Customers</p>
                <p class="text-3xl font-bold">{{ stats.customers.total }}</p>
                <p class="text-xs text-purple-400 mt-1">
                  Menu Items: {{ stats.menu.total_items }}
                </p>
              </div>
              <div class="text-4xl">👥</div>
            </div>
          </GlassCard>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <!-- Revenue Trend Chart -->
          <GlassCard class="text-white">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold">Revenue Trend (Last 7 Days)</h3>
              <button
                @click="refreshCharts"
                class="text-white/70 hover:text-white text-sm"
                :disabled="refreshing"
              >
                {{ refreshing ? '🔄' : '↻' }} Refresh
              </button>
            </div>
            <div class="h-64">
              <canvas v-if="charts.revenueTrend?.length > 0" ref="revenueChartRef"></canvas>
              <div v-else class="flex items-center justify-center h-full text-white/50">
                No revenue data available
              </div>
            </div>
          </GlassCard>

          <!-- Order Status Distribution -->
          <GlassCard class="text-white">
            <h3 class="text-xl font-bold mb-4">Order Status Distribution</h3>
            <div class="h-64">
              <canvas v-if="charts.orderStatusDistribution?.length > 0" ref="statusChartRef"></canvas>
              <div v-else class="flex items-center justify-center h-full text-white/50">
                No order data available
              </div>
            </div>
          </GlassCard>
        </div>

        <!-- Popular Items and Recent Orders -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Popular Menu Items -->
          <GlassCard class="text-white">
            <h3 class="text-xl font-bold mb-4">Popular Menu Items</h3>
            <div v-if="charts.popularItems?.length > 0" class="space-y-3">
              <div
                v-for="(item, index) in charts.popularItems"
                :key="item.food_id"
                class="flex items-center justify-between p-3 glass rounded-lg hover:bg-white/10 transition cursor-pointer"
              >
                <div class="flex items-center space-x-3">
                  <div
                    class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold"
                    :class="{
                      'bg-yellow-500/40': index === 0,
                      'bg-gray-400/40': index === 1,
                      'bg-orange-700/40': index === 2,
                    }"
                  >
                    {{ index + 1 }}
                  </div>
                  <div>
                    <p class="font-semibold">{{ item.food?.name || 'Unknown Item' }}</p>
                    <p class="text-xs text-white/60">{{ item.total_ordered }} orders</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="font-bold text-green-400">${{ formatCurrency(item.total_revenue) }}</p>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-white/50">
              No popular items yet
            </div>
          </GlassCard>

          <!-- Recent Orders -->
          <GlassCard class="text-white">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-xl font-bold">Recent Orders</h3>
              <Link
                :href="route('admin.orders')"
                class="text-sm text-white/70 hover:text-white"
              >
                View All →
              </Link>
            </div>
            <div v-if="recentOrders?.length > 0" class="space-y-2 max-h-96 overflow-y-auto">
              <div
                v-for="order in recentOrders"
                :key="order.id"
                class="p-3 glass rounded-lg hover:bg-white/10 transition cursor-pointer"
              >
                <div class="flex items-center justify-between">
                  <div>
                    <p class="font-semibold">{{ order.food?.name || 'Unknown Item' }}</p>
                    <p class="text-xs text-white/60">
                      {{ order.customer?.first_name || 'Guest' }} · Qty: {{ order.quantity }} · 
                      {{ formatTime(order.created_at) }}
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="font-bold">${{ formatCurrency(order.total_amount) }}</p>
                    <span
                      :class="[
                        'text-xs px-2 py-1 rounded mt-1 inline-block',
                        getStatusClass(order.status)
                      ]"
                    >
                      {{ order.status }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div v-else class="text-center py-8 text-white/50">
              No recent orders
            </div>
          </GlassCard>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6">
          <GlassCard class="text-white">
            <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <Link
                v-if="hasPermission('menu-create') || isAdmin"
                :href="route('admin.food.create')"
                class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
              >
                ➕ Add Menu Item
              </Link>
              <Link
                v-if="hasPermission('orders-view') || isAdmin"
                :href="route('admin.orders')"
                class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
              >
                📋 View Orders
              </Link>
              <Link
                v-if="hasPermission('employees-create') || isAdmin"
                :href="route('admin.employees.create')"
                class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
              >
                👤 Add Employee
              </Link>
              <Link
                v-if="hasPermission('roles-view') || isAdmin"
                :href="route('admin.roles.index')"
                class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
              >
                🔐 Manage Roles
              </Link>
            </div>
          </GlassCard>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import AdminLayout from './Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { usePermissions } from '@/composables/usePermissions'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  stats: Object,
  recentOrders: Array,
  charts: Object,
})

const page = usePage()
const { hasPermission, user } = usePermissions()
const isAdmin = computed(() => user.value?.is_admin || false)

const revenueChartRef = ref(null)
const statusChartRef = ref(null)
const loading = ref(false)
const error = ref(null)
const refreshing = ref(false)
let revenueChart = null
let statusChart = null
let refreshInterval = null

const userName = computed(() => {
  return page.props.auth?.user?.name || 'Admin'
})

const greeting = computed(() => {
  const hour = new Date().getHours()
  if (hour < 12) return 'Good Morning'
  if (hour < 18) return 'Good Afternoon'
  return 'Good Evening'
})

const currentDate = computed(() => {
  return new Date().toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

const formatCurrency = (value) => {
  return parseFloat(value || 0).toFixed(2)
}

const formatTime = (dateString) => {
  if (!dateString) return ''
  const date = new Date(dateString)
  const now = new Date()
  const diffMs = now - date
  const diffMins = Math.floor(diffMs / 60000)
  
  if (diffMins < 1) return 'Just now'
  if (diffMins < 60) return `${diffMins}m ago`
  const diffHours = Math.floor(diffMins / 60)
  if (diffHours < 24) return `${diffHours}h ago`
  return date.toLocaleDateString()
}

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-500/30 text-yellow-300',
    completed: 'bg-green-500/30 text-green-300',
    cancelled: 'bg-red-500/30 text-red-300',
    preparing: 'bg-blue-500/30 text-blue-300',
    confirmed: 'bg-blue-500/30 text-blue-300',
    serving: 'bg-purple-500/30 text-purple-300',
  }
  return classes[status] || 'bg-gray-500/30 text-gray-300'
}

const refresh = () => {
  loading.value = true
  error.value = null
  router.reload({
    only: ['stats', 'recentOrders', 'charts'],
    preserveScroll: true,
    onFinish: () => {
      loading.value = false
      refreshCharts()
    },
    onError: (errors) => {
      loading.value = false
      error.value = 'Failed to load dashboard data'
    }
  })
}

const refreshCharts = () => {
  refreshing.value = true
  // Destroy existing charts
  if (revenueChart) {
    revenueChart.destroy()
    revenueChart = null
  }
  if (statusChart) {
    statusChart.destroy()
    statusChart = null
  }
  
  // Re-render charts
  setTimeout(() => {
    renderCharts()
    refreshing.value = false
  }, 100)
}

const renderCharts = () => {
  // Revenue Trend Chart
  if (revenueChartRef.value && props.charts.revenueTrend?.length > 0) {
    // Fill missing dates
    const last7Days = []
    const today = new Date()
    for (let i = 6; i >= 0; i--) {
      const date = new Date(today)
      date.setDate(date.getDate() - i)
      last7Days.push(date.toISOString().split('T')[0])
    }

    const revenueData = last7Days.map(date => {
      const found = props.charts.revenueTrend.find(r => r.date === date)
      return found ? parseFloat(found.revenue) : 0
    })

    revenueChart = new Chart(revenueChartRef.value, {
      type: 'line',
      data: {
        labels: last7Days.map(date => {
          const d = new Date(date)
          return d.toLocaleDateString('en-US', { weekday: 'short' })
        }),
        datasets: [
          {
            label: 'Revenue ($)',
            data: revenueData,
            borderColor: 'rgba(255, 255, 255, 0.8)',
            backgroundColor: 'rgba(255, 255, 255, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(255, 255, 255, 0.9)',
            pointBorderColor: 'rgba(255, 255, 255, 1)',
            pointRadius: 4,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: 'white', font: { size: 12 } },
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: 'white',
            bodyColor: 'white',
            borderColor: 'rgba(255, 255, 255, 0.2)',
            borderWidth: 1,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { color: 'white', font: { size: 10 } },
            grid: { color: 'rgba(255, 255, 255, 0.1)' },
          },
          x: {
            ticks: { color: 'white', font: { size: 10 } },
            grid: { color: 'rgba(255, 255, 255, 0.1)' },
          },
        },
      },
    })
  }

  // Order Status Distribution Chart
  if (statusChartRef.value && props.charts.orderStatusDistribution?.length > 0) {
    statusChart = new Chart(statusChartRef.value, {
      type: 'pie',
      data: {
        labels: props.charts.orderStatusDistribution.map((item) => 
          item.status.charAt(0).toUpperCase() + item.status.slice(1)
        ),
        datasets: [
          {
            data: props.charts.orderStatusDistribution.map((item) => item.count),
            backgroundColor: [
              'rgba(251, 191, 36, 0.7)', // pending - yellow
              'rgba(34, 197, 94, 0.7)', // completed - green
              'rgba(239, 68, 68, 0.7)', // cancelled - red
              'rgba(59, 130, 246, 0.7)', // preparing - blue
              'rgba(147, 51, 234, 0.7)', // serving - purple
            ],
            borderColor: 'rgba(255, 255, 255, 0.8)',
            borderWidth: 2,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: { color: 'white', padding: 15, font: { size: 11 } },
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            titleColor: 'white',
            bodyColor: 'white',
            borderColor: 'rgba(255, 255, 255, 0.2)',
            borderWidth: 1,
          },
        },
      },
    })
  }
}

onMounted(() => {
  renderCharts()
  
  // Auto-refresh every 30 seconds
  refreshInterval = setInterval(() => {
    refresh()
  }, 30000)
})

onUnmounted(() => {
  if (revenueChart) revenueChart.destroy()
  if (statusChart) statusChart.destroy()
  if (refreshInterval) clearInterval(refreshInterval)
})
</script>
