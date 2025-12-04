<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Add Employee</h1>
        <p class="text-white/80">Create a new employee account</p>
      </div>

      <GlassCard class="text-white max-w-2xl">
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <!-- Name -->
            <div>
              <label class="block text-sm font-medium mb-2">Name *</label>
              <input
                v-model="form.name"
                type="text"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Employee full name"
              />
              <p v-if="errors.name" class="text-red-300 text-sm mt-1">{{ errors.name }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-sm font-medium mb-2">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="employee@restaurant.com"
              />
              <p v-if="errors.email" class="text-red-300 text-sm mt-1">{{ errors.email }}</p>
            </div>

            <!-- Password -->
            <div>
              <label class="block text-sm font-medium mb-2">Password *</label>
              <input
                v-model="form.password"
                type="password"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Minimum 8 characters"
              />
              <p v-if="errors.password" class="text-red-300 text-sm mt-1">{{ errors.password }}</p>
            </div>

            <!-- Password Confirmation -->
            <div>
              <label class="block text-sm font-medium mb-2">Confirm Password *</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Re-enter password"
              />
              <p v-if="errors.password_confirmation" class="text-red-300 text-sm mt-1">{{ errors.password_confirmation }}</p>
            </div>

            <!-- Role -->
            <div>
              <label class="block text-sm font-medium mb-2">Role *</label>
              <select
                v-model="form.role_id"
                required
                class="glass-input w-full px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50"
              >
                <option value="" disabled>Select a role</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">
                  {{ role.name }}
                </option>
              </select>
              <p v-if="errors.role_id" class="text-red-300 text-sm mt-1">{{ errors.role_id }}</p>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
              <Link
                :href="route('admin.employees')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Creating...' : 'Create Employee' }}
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
import FlashMessage from '@/components/FlashMessage.vue'

const props = defineProps({
  roles: Array,
  errors: Object,
})

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role_id: '',
})

const submit = () => {
  form.post(route('admin.employees.store'))
}

const processing = form.processing
</script>
