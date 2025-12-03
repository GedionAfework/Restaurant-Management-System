<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Permissions Management</h1>
          <p class="text-white/80">Manage system permissions by module</p>
        </div>
        <Link
          :href="route('admin.permissions.create')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          ➕ Create Permission
        </Link>
      </div>

      <div class="space-y-6">
        <GlassCard
          v-for="(modulePermissions, module) in permissions"
          :key="module"
          class="text-white"
        >
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold capitalize">{{ module }}</h2>
            <span class="px-3 py-1 glass rounded-full text-sm">
              {{ modulePermissions.length }} permissions
            </span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-white/20">
                  <th class="text-left py-3 px-4">Name</th>
                  <th class="text-left py-3 px-4">Slug</th>
                  <th class="text-left py-3 px-4">Description</th>
                  <th class="text-left py-3 px-4">Roles</th>
                  <th class="text-right py-3 px-4">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="permission in modulePermissions"
                  :key="permission.id"
                  class="border-b border-white/10 hover:bg-white/5 transition"
                >
                  <td class="py-4 px-4 font-semibold">{{ permission.name }}</td>
                  <td class="py-4 px-4">
                    <code class="px-2 py-1 glass rounded text-xs">{{ permission.slug }}</code>
                  </td>
                  <td class="py-4 px-4 text-white/70">
                    {{ permission.description || 'N/A' }}
                  </td>
                  <td class="py-4 px-4">
                    <span class="px-3 py-1 glass rounded-full text-sm">
                      {{ permission.roles?.length || 0 }} roles
                    </span>
                  </td>
                  <td class="py-4 px-4 text-right">
                    <div class="flex justify-end space-x-2">
                      <Link
                        :href="route('admin.permissions.edit', permission.id)"
                        class="glass-button px-4 py-2 text-sm rounded"
                      >
                        ✏️ Edit
                      </Link>
                      <button
                        @click="deletePermission(permission.id)"
                        class="glass-button bg-red-500/30 hover:bg-red-500/40 px-4 py-2 text-sm rounded"
                        :disabled="permission.roles && permission.roles.length > 0"
                        :class="{
                          'opacity-50 cursor-not-allowed': permission.roles && permission.roles.length > 0,
                        }"
                      >
                        🗑️ Delete
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </GlassCard>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'

const props = defineProps({
  permissions: Object,
})

const deletePermission = (id) => {
  if (confirm('Are you sure you want to delete this permission?')) {
    router.delete(route('admin.permissions.destroy', id))
  }
}
</script>

