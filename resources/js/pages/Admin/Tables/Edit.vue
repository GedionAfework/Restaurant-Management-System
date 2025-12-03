<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Edit Table</h1>
        <p class="text-white/80">Update table details</p>
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
              />
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
                  class="glass-input w-full px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50"
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
                  placeholder="e.g., Window, Patio"
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
              <label class="block text-sm font-medium mb-2">Status *</label>
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

            <!-- Position -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium mb-2">Position X (0-100)</label>
                <input
                  v-model.number="form.position_x"
                  type="number"
                  min="0"
                  max="100"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50"
                />
              </div>
              <div>
                <label class="block text-sm font-medium mb-2">Position Y (0-100)</label>
                <input
                  v-model.number="form.position_y"
                  type="number"
                  min="0"
                  max="100"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50"
                />
              </div>
            </div>

            <!-- Notes -->
            <div>
              <label class="block text-sm font-medium mb-2">Notes</label>
              <textarea
                v-model="form.notes"
                rows="3"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
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
                {{ processing ? 'Updating...' : 'Update Table' }}
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
  table: Object,
  errors: Object,
})

const form = useForm({
  table_number: props.table.table_number,
  capacity: props.table.capacity,
  location: props.table.location || '',
  zone: props.table.zone || '',
  shape: props.table.shape,
  floor: props.table.floor,
  status: props.table.status,
  position_x: props.table.position_x,
  position_y: props.table.position_y,
  notes: props.table.notes || '',
})

const submit = () => {
  form.put(route('admin.tables.update', props.table.table_id))
}

const processing = form.processing
</script>

