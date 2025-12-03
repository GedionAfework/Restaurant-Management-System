<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Edit Role</h1>
        <p class="text-white/80">Update role details and permissions</p>
      </div>

      <GlassCard class="text-white">
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <!-- Role Name -->
            <div>
              <label class="block text-sm font-medium mb-2">Role Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                :disabled="role.is_system"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 disabled:opacity-50"
                placeholder="e.g., Manager, Waiter, Chef"
              />
              <p v-if="errors.name" class="text-red-300 text-sm mt-1">{{ errors.name }}</p>
              <p v-if="role.is_system" class="text-yellow-300 text-sm mt-1">
                ⚠️ System roles cannot be edited
              </p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium mb-2">Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                :disabled="role.is_system"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50 disabled:opacity-50"
                placeholder="Describe the role's responsibilities..."
              ></textarea>
            </div>

            <!-- Color -->
            <div>
              <label class="block text-sm font-medium mb-2">Color (for UI display)</label>
              <input
                v-model="form.color"
                type="color"
                :disabled="role.is_system"
                class="h-12 w-32 rounded cursor-pointer disabled:opacity-50"
              />
            </div>

            <!-- Permissions -->
            <div>
              <label class="block text-sm font-medium mb-4">Permissions</label>
              <div
                v-for="(modulePermissions, module) in permissions"
                :key="module"
                class="mb-6"
              >
                <h4 class="text-lg font-semibold mb-3 capitalize">{{ module }}</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                  <label
                    v-for="permission in modulePermissions"
                    :key="permission.id"
                    class="flex items-center space-x-3 p-3 glass rounded-lg cursor-pointer hover:bg-white/10 transition"
                    :class="{ 'opacity-50': role.is_system }"
                  >
                    <input
                      type="checkbox"
                      :value="permission.id"
                      v-model="form.permissions"
                      :disabled="role.is_system"
                      class="w-5 h-5 rounded cursor-pointer"
                    />
                    <div>
                      <p class="font-medium">{{ permission.name }}</p>
                      <p v-if="permission.description" class="text-xs text-white/60">
                        {{ permission.description }}
                      </p>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
              <Link
                :href="route('roles.index')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing || role.is_system"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Updating...' : 'Update Role' }}
              </button>
            </div>
          </div>
        </form>
      </GlassCard>
    </div>
  </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'

const props = defineProps({
  role: Object,
  permissions: Object,
  errors: Object,
})

const form = useForm({
  name: props.role.name,
  description: props.role.description || '',
  color: props.role.color || '#3B82F6',
  permissions: props.role.permissions?.map(p => p.id) || [],
})

const submit = () => {
  form.put(route('roles.update', props.role.id))
}

const processing = form.processing
</script>

