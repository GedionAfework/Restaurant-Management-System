<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Create New Permission</h1>
        <p class="text-white/80">Add a new permission to the system</p>
      </div>

      <GlassCard class="text-white">
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <!-- Permission Name -->
            <div>
              <label class="block text-sm font-medium mb-2">Permission Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="e.g., View Orders, Create Menu Item"
              />
              <p v-if="errors.name" class="text-red-300 text-sm mt-1">{{ errors.name }}</p>
              <p class="text-white/60 text-xs mt-1">
                The name that will be displayed in the UI
              </p>
            </div>

            <!-- Module -->
            <div>
              <label class="block text-sm font-medium mb-2">Module *</label>
              <input
                v-model="form.module"
                type="text"
                required
                list="modules"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="e.g., menu, orders, employees, tables"
              />
              <datalist id="modules">
                <option
                  v-for="module in modules"
                  :key="module"
                  :value="module"
                ></option>
              </datalist>
              <p v-if="errors.module" class="text-red-300 text-sm mt-1">{{ errors.module }}</p>
              <p class="text-white/60 text-xs mt-1">
                Group permissions by module (menu, orders, employees, etc.)
              </p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium mb-2">Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Describe what this permission allows..."
              ></textarea>
              <p class="text-white/60 text-xs mt-1">
                Optional: Provide a detailed description of what this permission controls
              </p>
            </div>

            <!-- Preview -->
            <div class="p-4 glass rounded-lg">
              <p class="text-sm font-medium mb-2">Preview:</p>
              <div class="space-y-1 text-sm">
                <p><span class="text-white/60">Name:</span> {{ form.name || 'Permission Name' }}</p>
                <p><span class="text-white/60">Module:</span> {{ form.module || 'module' }}</p>
                <p>
                  <span class="text-white/60">Slug:</span>
                  <code class="px-2 py-1 glass rounded text-xs ml-2">
                    {{ form.module ? (form.module.toLowerCase().replace(/\s+/g, '-') + '-' + (form.name ? form.name.toLowerCase().replace(/\s+/g, '-') : 'permission-name')) : 'module-permission-name' }}
                  </code>
                </p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
              <Link
                :href="route('permissions.index')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Creating...' : 'Create Permission' }}
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
  modules: Array,
  errors: Object,
})

const form = useForm({
  name: '',
  description: '',
  module: '',
})

const submit = () => {
  form.post(route('permissions.store'))
}

const processing = form.processing
</script>

