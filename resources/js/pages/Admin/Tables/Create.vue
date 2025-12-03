<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Create New Table</h1>
        <p class="text-white/80">Add a new table to your restaurant</p>
      </div>

      <GlassCard class="text-white max-w-2xl">
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <!-- Table Number -->
            <div>
              <label class="block text-sm font-medium mb-2">Table Number *</label>
              <input
                v-model="form.table_number"
                type="text"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="e.g., T-1, Table 5, A-12"
              />
              <p v-if="errors.table_number" class="text-red-300 text-sm mt-1">{{ errors.table_number }}</p>
            </div>

            <!-- Capacity -->
            <div>
              <label class="block text-sm font-medium mb-2">Capacity *</label>
              <input
                v-model.number="form.capacity"
                type="number"
                required
                min="1"
                max="20"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Number of guests"
              />
              <p class="text-white/60 text-xs mt-1">Maximum number of guests this table can accommodate</p>
            </div>

            <!-- Shape and Floor -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Shape *</label>
                <select
                  v-model="form.shape"
                  required
                  class="glass-input w-full px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50"
                >
                  <option value="round">Round ⭕</option>
                  <option value="square">Square ⬜</option>
                  <option value="rectangular">Rectangular ▭</option>
                  <option value="booth">Booth 🪑</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Floor *</label>
                <input
                  v-model.number="form.floor"
                  type="number"
                  required
                  min="1"
                  max="10"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  placeholder="Floor number"
                />
              </div>
            </div>

            <!-- Location and Zone -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Location</label>
                <input
                  v-model="form.location"
                  type="text"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  placeholder="e.g., Window, Patio, Main Hall"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Zone</label>
                <input
                  v-model="form.zone"
                  type="text"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  placeholder="e.g., A, B, VIP"
                />
              </div>
            </div>

            <!-- Status -->
            <div>
              <label class="block text-sm font-medium mb-2">Initial Status *</label>
              <select
                v-model="form.status"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50"
              >
                <option value="available">Available</option>
                <option value="occupied">Occupied</option>
                <option value="reserved">Reserved</option>
                <option value="cleaning">Cleaning</option>
              </select>
            </div>

            <!-- Position (for visual layout) -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Position X (0-100)</label>
                <input
                  v-model.number="form.position_x"
                  type="number"
                  min="0"
                  max="100"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  placeholder="Optional"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Position Y (0-100)</label>
                <input
                  v-model.number="form.position_y"
                  type="number"
                  min="0"
                  max="100"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  placeholder="Optional"
                />
              </div>
            </div>
            <p class="text-white/60 text-xs">Leave blank to set later in visual layout</p>

            <!-- Notes -->
            <div>
              <label class="block text-sm font-medium mb-2">Notes</label>
              <textarea
                v-model="form.notes"
                rows="3"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Any additional notes about this table..."
              ></textarea>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
              <Link
                :href="route('admin.tables.index')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Creating...' : 'Create Table' }}
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
  errors: Object,
})

const form = useForm({
  table_number: '',
  capacity: 4,
  location: '',
  zone: '',
  shape: 'round',
  floor: 1,
  status: 'available',
  position_x: null,
  position_y: null,
  notes: '',
})

const submit = () => {
  form.post(route('admin.tables.store'))
}

const processing = form.processing
</script>

