<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <!-- Welcome Header -->
      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Dashboard</h1>
        <p class="text-white/80">Welcome back! Here's what's happening today.</p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Revenue Today -->
        <GlassCard class="text-white">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-white/70 mb-1">Revenue Today</p>
              <p class="text-3xl font-bold">${{ stats.revenue.today.toFixed(2) }}</p>
              <p class="text-xs text-green-400 mt-1">
                Week: ${{ stats.revenue.week.toFixed(2) }}
              </p>
            </div>
            <div class="text-4xl">💰</div>
          </div>
        </GlassCard>

        <!-- Orders Today -->
        <GlassCard class="text-white">
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
        <GlassCard class="text-white">
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
        <GlassCard class="text-white">
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
          <h3 class="text-xl font-bold mb-4">Revenue Trend (Last 7 Days)</h3>
          <div class="h-64">
            <canvas ref="revenueChartRef"></canvas>
          </div>
        </GlassCard>

        <!-- Order Status Distribution -->
        <GlassCard class="text-white">
          <h3 class="text-xl font-bold mb-4">Order Status Distribution</h3>
          <div class="h-64">
            <canvas ref="statusChartRef"></canvas>
          </div>
        </GlassCard>
      </div>

      <!-- Popular Items and Recent Orders -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Popular Menu Items -->
        <GlassCard class="text-white">
          <h3 class="text-xl font-bold mb-4">Popular Menu Items</h3>
          <div class="space-y-3">
            <div
              v-for="(item, index) in charts.popularItems"
              :key="item.food_id"
              class="flex items-center justify-between p-3 glass rounded-lg"
            >
              <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center font-bold">
                  {{ index + 1 }}
                </div>
                <div>
                  <p class="font-semibold">{{ item.food?.name || 'Unknown' }}</p>
                  <p class="text-xs text-white/60">{{ item.total_ordered }} orders</p>
                </div>
              </div>
              <div class="text-right">
                <p class="font-bold text-green-400">${{ parseFloat(item.total_revenue).toFixed(2) }}</p>
              </div>
            </div>
          </div>
        </GlassCard>

        <!-- Recent Orders -->
        <GlassCard class="text-white">
          <h3 class="text-xl font-bold mb-4">Recent Orders</h3>
          <div class="space-y-2 max-h-96 overflow-y-auto">
            <div
              v-for="order in recentOrders"
              :key="order.id"
              class="p-3 glass rounded-lg hover:bg-white/10 transition"
            >
              <div class="flex items-center justify-between">
                <div>
                  <p class="font-semibold">{{ order.food?.name || 'Unknown' }}</p>
                  <p class="text-xs text-white/60">
                    {{ order.customer?.first_name || 'Guest' }} · Qty: {{ order.quantity }}
                  </p>
                </div>
                <div class="text-right">
                  <p class="font-bold">${{ parseFloat(order.total_amount).toFixed(2) }}</p>
                  <span
                    :class="[
                      'text-xs px-2 py-1 rounded',
                      getStatusClass(order.status)
                    ]"
                  >
                    {{ order.status }}
                  </span>
                </div>
              </div>
            </div>
          </div>
        </GlassCard>
      </div>

      <!-- Quick Actions -->
      <div class="mt-6">
        <GlassCard class="text-white">
          <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <Link
              :href="route('admin.food.create')"
              class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
            >
              ➕ Add Menu Item
            </Link>
            <Link
              :href="route('admin.orders')"
              class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
            >
              📋 View Orders
            </Link>
            <Link
              :href="route('admin.employees.create')"
              class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
            >
              👤 Add Employee
            </Link>
            <Link
              :href="route('admin.roles.index')"
              class="glass-button text-center py-4 rounded-lg hover:scale-105 transition"
            >
              🔐 Manage Roles
            </Link>
          </div>
        </GlassCard>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from './Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import { Chart, registerables } from 'chart.js'

Chart.register(...registerables)

const props = defineProps({
  stats: Object,
  recentOrders: Array,
  charts: Object,
})

const revenueChartRef = ref(null)
const statusChartRef = ref(null)

const getStatusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-500/30 text-yellow-300',
    completed: 'bg-green-500/30 text-green-300',
    cancelled: 'bg-red-500/30 text-red-300',
    preparing: 'bg-blue-500/30 text-blue-300',
  }
  return classes[status] || 'bg-gray-500/30 text-gray-300'
}

onMounted(() => {
  // Revenue Trend Chart
  if (revenueChartRef.value && props.charts.revenueTrend) {
    new Chart(revenueChartRef.value, {
      type: 'line',
      data: {
        labels: props.charts.revenueTrend.map((item) => {
          const date = new Date(item.date)
          return date.toLocaleDateString('en-US', { weekday: 'short' })
        }),
        datasets: [
          {
            label: 'Revenue ($)',
            data: props.charts.revenueTrend.map((item) => parseFloat(item.revenue)),
            borderColor: 'rgba(255, 255, 255, 0.8)',
            backgroundColor: 'rgba(255, 255, 255, 0.1)',
            tension: 0.4,
            fill: true,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: 'white' },
          },
        },
        scales: {
          y: {
            ticks: { color: 'white' },
            grid: { color: 'rgba(255, 255, 255, 0.1)' },
          },
          x: {
            ticks: { color: 'white' },
            grid: { color: 'rgba(255, 255, 255, 0.1)' },
          },
        },
      },
    })
  }

  // Order Status Distribution Chart
  if (statusChartRef.value && props.charts.orderStatusDistribution) {
    new Chart(statusChartRef.value, {
      type: 'pie',
      data: {
        labels: props.charts.orderStatusDistribution.map((item) => item.status),
        datasets: [
          {
            data: props.charts.orderStatusDistribution.map((item) => item.count),
            backgroundColor: [
              'rgba(251, 191, 36, 0.6)', // pending - yellow
              'rgba(34, 197, 94, 0.6)', // completed - green
              'rgba(239, 68, 68, 0.6)', // cancelled - red
              'rgba(59, 130, 246, 0.6)', // preparing - blue
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
            labels: { color: 'white', padding: 15 },
          },
        },
      },
    })
  }
})
</script>
