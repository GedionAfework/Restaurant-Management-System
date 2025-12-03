<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-4xl font-bold text-white mb-2">Roles Management</h1>
          <p class="text-white/80">Manage user roles and permissions</p>
        </div>
        <Link
          :href="route('roles.create')"
          class="glass-button px-6 py-3 rounded-lg text-white font-semibold hover:scale-105 transition"
        >
          ➕ Create Role
        </Link>
      </div>

      <GlassCard class="text-white">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="border-b border-white/20">
                <th class="text-left py-3 px-4">Name</th>
                <th class="text-left py-3 px-4">Description</th>
                <th class="text-left py-3 px-4">Users</th>
                <th class="text-left py-3 px-4">Permissions</th>
                <th class="text-left py-3 px-4">Type</th>
                <th class="text-right py-3 px-4">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="role in roles.data"
                :key="role.id"
                class="border-b border-white/10 hover:bg-white/5 transition"
              >
                <td class="py-4 px-4">
                  <div class="flex items-center space-x-3">
                    <div
                      v-if="role.color"
                      class="w-4 h-4 rounded-full"
                      :style="{ backgroundColor: role.color }"
                    ></div>
                    <span class="font-semibold">{{ role.name }}</span>
                  </div>
                </td>
                <td class="py-4 px-4 text-white/70">{{ role.description || 'N/A' }}</td>
                <td class="py-4 px-4">
                  <span class="px-3 py-1 glass rounded-full text-sm">
                    {{ role.users_count }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span class="px-3 py-1 glass rounded-full text-sm">
                    {{ role.permissions?.length || 0 }}
                  </span>
                </td>
                <td class="py-4 px-4">
                  <span
                    v-if="role.is_system"
                    class="px-3 py-1 bg-red-500/30 text-red-300 rounded-full text-xs"
                  >
                    System
                  </span>
                  <span
                    v-else
                    class="px-3 py-1 bg-blue-500/30 text-blue-300 rounded-full text-xs"
                  >
                    Custom
                  </span>
                </td>
                <td class="py-4 px-4 text-right">
                  <div class="flex justify-end space-x-2">
                    <Link
                      :href="route('roles.edit', role.id)"
                      class="glass-button px-4 py-2 text-sm rounded"
                      :class="{ 'opacity-50 cursor-not-allowed': role.is_system }"
                      :disabled="role.is_system"
                    >
                      ✏️ Edit
                    </Link>
                    <button
                      @click="deleteRole(role.id)"
                      class="glass-button bg-red-500/30 hover:bg-red-500/40 px-4 py-2 text-sm rounded"
                      :disabled="role.is_system || role.users_count > 0"
                      :class="{
                        'opacity-50 cursor-not-allowed': role.is_system || role.users_count > 0,
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

        <!-- Pagination -->
        <div v-if="roles.links" class="mt-6 flex justify-center space-x-2">
          <Link
            v-for="link in roles.links"
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
      </GlassCard>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'

const props = defineProps({
  roles: Object,
})

const deleteRole = (id) => {
  if (confirm('Are you sure you want to delete this role?')) {
    router.delete(route('roles.destroy', id))
  }
}
</script>

