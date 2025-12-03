<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Create New Role</h1>
        <p class="text-white/80">Define a new role with specific permissions</p>
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
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="e.g., Manager, Waiter, Chef"
              />
              <p v-if="errors.name" class="text-red-300 text-sm mt-1">{{ errors.name }}</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium mb-2">Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Describe the role's responsibilities..."
              ></textarea>
            </div>

            <!-- Color -->
            <div>
              <label class="block text-sm font-medium mb-2">Color (for UI display)</label>
              <input
                v-model="form.color"
                type="color"
                class="h-12 w-32 rounded cursor-pointer"
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
                  >
                    <input
                      type="checkbox"
                      :value="permission.id"
                      v-model="form.permissions"
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
                :href="route('admin.roles.index')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Creating...' : 'Create Role' }}
              </button>
            </div>
          </div>
        </form>
      </GlassCard>
    </div>
  </AdminLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'

const props = defineProps({
  permissions: Object,
  errors: Object,
})

const form = useForm({
  name: '',
  description: '',
  color: '#3B82F6',
  permissions: [],
})

const submit = () => {
  form.post(route('admin.roles.store'))
}

const processing = form.processing
</script>

