<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Create Menu Category</h1>
        <p class="text-white/80">Add a new category to organize menu items</p>
      </div>

      <GlassCard class="text-white max-w-2xl">
        <form @submit.prevent="submit">
          <div class="space-y-6">
            <!-- Category Name -->
            <div>
              <label class="block text-sm font-medium mb-2">Category Name *</label>
              <input
                v-model="form.category_name"
                type="text"
                required
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="e.g., Burgers, Appetizers, Desserts"
              />
              <p v-if="errors.category_name" class="text-red-300 text-sm mt-1">{{ errors.category_name }}</p>
            </div>

            <!-- Icon -->
            <div>
              <label class="block text-sm font-medium mb-2">Icon (Emoji)</label>
              <input
                v-model="form.icon"
                type="text"
                maxlength="10"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="e.g., 🍔, 🍕, 🍰"
              />
              <p class="text-white/60 text-xs mt-1">Optional: Add an emoji icon for this category</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium mb-2">Description</label>
              <textarea
                v-model="form.description"
                rows="3"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="Describe this category..."
              ></textarea>
            </div>

            <!-- Image -->
            <div>
              <label class="block text-sm font-medium mb-2">Category Image</label>
              <input
                type="file"
                @change="handleImageChange"
                accept="image/*"
                class="glass-input w-full px-4 py-3 rounded-lg text-white"
              />
              <p class="text-white/60 text-xs mt-1">Optional: Upload an image for this category</p>
              <img
                v-if="imagePreview"
                :src="imagePreview"
                alt="Preview"
                class="mt-2 w-32 h-32 object-cover rounded-lg"
              />
            </div>

            <!-- Display Order -->
            <div>
              <label class="block text-sm font-medium mb-2">Display Order</label>
              <input
                v-model.number="form.display_order"
                type="number"
                min="0"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                placeholder="0"
              />
              <p class="text-white/60 text-xs mt-1">Lower numbers appear first</p>
            </div>

            <!-- Is Active -->
            <div class="flex items-center space-x-3">
              <input
                type="checkbox"
                v-model="form.is_active"
                id="is_active"
                class="w-5 h-5 rounded cursor-pointer"
              />
              <label for="is_active" class="text-sm font-medium cursor-pointer">
                Category is active (visible to customers)
              </label>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
              <Link
                :href="route('admin.menu-categories.index')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Creating...' : 'Create Category' }}
              </button>
            </div>
          </div>
        </form>
      </GlassCard>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../Layout.vue'
import GlassCard from '@/components/ui/GlassCard.vue'
import FlashMessage from '@/components/FlashMessage.vue'

const props = defineProps({
  errors: Object,
})

const imagePreview = ref(null)

const form = useForm({
  category_name: '',
  description: '',
  icon: '',
  image: null,
  display_order: 0,
  is_active: true,
})

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const submit = () => {
  form.post(route('admin.menu-categories.store'), {
    forceFormData: true,
  })
}

const processing = form.processing
</script>

