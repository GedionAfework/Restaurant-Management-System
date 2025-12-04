<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Employees</h1>
          <p class="text-white/80">Manage restaurant employees</p>
        </div>
        <Link
          v-if="hasPermission('employees-create') || isAdmin"
          :href="route('admin.employees.create')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          ➕ Add Employee
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <GlassCard
          v-for="employee in employees"
          :key="employee.id"
          class="text-white hover:scale-105 transition-transform"
        >
          <div class="space-y-3">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-bold">{{ employee.name }}</h3>
              <span class="px-3 py-1 glass rounded-full text-sm">
                {{ employee.role?.name || 'No Role' }}
              </span>
            </div>
            <p class="text-white/70">{{ employee.email }}</p>
            <div class="flex space-x-2 pt-4 border-t border-white/20">
              <Link
                :href="route('admin.employees.edit', employee.id)"
                @click.stop
                v-if="hasPermission('employees-edit') || isAdmin"
                class="flex-1 glass-button text-center py-2 rounded text-sm"
              >
                ✏️ Edit
              </Link>
              <button
                @click.stop="deleteEmployee(employee.id)"
                v-if="hasPermission('employees-delete') || isAdmin"
                class="flex-1 glass-button bg-red-500/30 hover:bg-red-500/40 text-center py-2 rounded text-sm"
              >
                🗑️ Delete
              </button>
            </div>
          </div>
        </GlassCard>
      </div>

      <div v-if="employees.length === 0" class="text-center py-12">
        <GlassCard class="text-white">
          <div class="text-4xl mb-4">👥</div>
          <h3 class="text-xl font-bold mb-2">No Employees Yet</h3>
          <p class="text-white/70 mb-4">Add your first employee to get started</p>
          <Link
            v-if="hasPermission('employees-create') || isAdmin"
            :href="route('admin.employees.create')"
            class="glass-button px-6 py-3 rounded-lg inline-block"
          >
            ➕ Add Employee
          </Link>
        </GlassCard>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from './Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'
import { usePermissions } from '@/composables/usePermissions'

const props = defineProps({
  employees: Array,
})

const { hasPermission, user, isAdmin } = usePermissions()

const deleteEmployee = (id) => {
  if (confirm('Are you sure you want to delete this employee?')) {
    router.delete(route('admin.employees.destroy', id))
  }
}
</script>
