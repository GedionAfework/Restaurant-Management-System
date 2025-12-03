<template>
  <AdminLayout>
    <div class="min-h-screen bg-animated-gradient p-6">
      <FlashMessage />

      <div class="mb-6">
        <h1 class="text-4xl font-bold text-white mb-2">Edit Menu Item</h1>
        <p class="text-white/80">Update menu item details</p>
      </div>

      <GlassCard class="text-white max-w-4xl">
        <form @submit.prevent="submit">
          <div class="space-y-8">
            <!-- Basic Information -->
            <div class="space-y-6">
              <h3 class="text-xl font-bold border-b border-white/20 pb-2">Basic Information</h3>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium mb-2">Name *</label>
                  <input
                    v-model="form.name"
                    type="text"
                    required
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                  <p v-if="errors.name" class="text-red-300 text-sm mt-1">{{ errors.name }}</p>
                </div>

                <div>
                  <label class="block text-sm font-medium mb-2">Category</label>
                  <select
                    v-model="form.category_id"
                    class="glass-input w-full px-4 py-3 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-white/50"
                  >
                    <option :value="null">No Category</option>
                    <option
                      v-for="category in categories"
                      :key="category.category_id"
                      :value="category.category_id"
                    >
                      {{ category.category_name }}
                    </option>
                  </select>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Type</label>
                <input
                  v-model="form.type"
                  type="text"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                />
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea
                  v-model="form.description"
                  rows="3"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                ></textarea>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label class="block text-sm font-medium mb-2">Price *</label>
                  <input
                    v-model.number="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                </div>

                <div>
                  <label class="block text-sm font-medium mb-2">Preparation Time (minutes)</label>
                  <input
                    v-model.number="form.preparation_time"
                    type="number"
                    min="0"
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-2">Picture</label>
                <div v-if="food.picture" class="mb-2">
                  <img
                    :src="`/storage/${food.picture}`"
                    alt="Current"
                    class="w-32 h-32 object-cover rounded-lg"
                  />
                  <p class="text-xs text-white/60 mt-1">Current image</p>
                </div>
                <input
                  type="file"
                  @change="handleImageChange"
                  accept="image/*"
                  class="glass-input w-full px-4 py-3 rounded-lg text-white"
                />
                <img
                  v-if="imagePreview"
                  :src="imagePreview"
                  alt="Preview"
                  class="mt-2 w-32 h-32 object-cover rounded-lg"
                />
              </div>
            </div>

            <!-- Availability & Tags -->
            <div class="space-y-6">
              <h3 class="text-xl font-bold border-b border-white/20 pb-2">Availability & Tags</h3>

              <div>
                <label class="block text-sm font-medium mb-3">Available During</label>
                <div class="flex flex-wrap gap-4">
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      type="checkbox"
                      value="breakfast"
                      v-model="form.availability_times"
                      class="w-5 h-5 rounded cursor-pointer"
                    />
                    <span>Breakfast</span>
                  </label>
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      type="checkbox"
                      value="lunch"
                      v-model="form.availability_times"
                      class="w-5 h-5 rounded cursor-pointer"
                    />
                    <span>Lunch</span>
                  </label>
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input
                      type="checkbox"
                      value="dinner"
                      v-model="form.availability_times"
                      class="w-5 h-5 rounded cursor-pointer"
                    />
                    <span>Dinner</span>
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-3">Tags</label>
                <div class="flex flex-wrap gap-4">
                  <label
                    v-for="tag in availableTags"
                    :key="tag"
                    class="flex items-center space-x-2 cursor-pointer"
                  >
                    <input
                      type="checkbox"
                      :value="tag"
                      v-model="form.tags"
                      class="w-5 h-5 rounded cursor-pointer"
                    />
                    <span>{{ tag }}</span>
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium mb-3">Allergens</label>
                <div class="flex flex-wrap gap-4">
                  <label
                    v-for="allergen in availableAllergens"
                    :key="allergen"
                    class="flex items-center space-x-2 cursor-pointer"
                  >
                    <input
                      type="checkbox"
                      :value="allergen"
                      v-model="form.allergens"
                      class="w-5 h-5 rounded cursor-pointer"
                    />
                    <span>{{ allergen }}</span>
                  </label>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                  <input
                    type="checkbox"
                    v-model="form.is_available"
                    id="is_available"
                    class="w-5 h-5 rounded cursor-pointer"
                  />
                  <label for="is_available" class="cursor-pointer">Item is available</label>
                </div>
                <div class="flex items-center space-x-3">
                  <input
                    type="checkbox"
                    v-model="form.is_featured"
                    id="is_featured"
                    class="w-5 h-5 rounded cursor-pointer"
                  />
                  <label for="is_featured" class="cursor-pointer">Feature this item</label>
                </div>
              </div>
            </div>

            <!-- Nutritional Information -->
            <div class="space-y-6">
              <h3 class="text-xl font-bold border-b border-white/20 pb-2">Nutritional Information</h3>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                  <label class="block text-sm font-medium mb-2">Calories</label>
                  <input
                    v-model.number="form.calories"
                    type="number"
                    min="0"
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-2">Protein (g)</label>
                  <input
                    v-model.number="form.protein"
                    type="number"
                    min="0"
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-2">Carbs (g)</label>
                  <input
                    v-model.number="form.carbs"
                    type="number"
                    min="0"
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                </div>
                <div>
                  <label class="block text-sm font-medium mb-2">Fat (g)</label>
                  <input
                    v-model.number="form.fat"
                    type="number"
                    min="0"
                    class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
                  />
                </div>
              </div>
            </div>

            <!-- Variants -->
            <div class="space-y-6">
              <div class="flex justify-between items-center border-b border-white/20 pb-2">
                <h3 class="text-xl font-bold">Size Variants</h3>
                <button
                  type="button"
                  @click="addVariant"
                  class="glass-button px-4 py-2 rounded-lg text-sm"
                >
                  ➕ Add Variant
                </button>
              </div>

              <div v-if="form.variants.length === 0" class="text-center py-4 text-white/50">
                No variants added
              </div>

              <div
                v-for="(variant, index) in form.variants"
                :key="variant.id || index"
                class="p-4 glass rounded-lg space-y-4"
              >
                <div class="flex justify-between items-start">
                  <h4 class="font-semibold">Variant {{ index + 1 }}</h4>
                  <button
                    type="button"
                    @click="removeVariant(index)"
                    class="text-red-400 hover:text-red-300"
                  >
                    ✕ Remove
                  </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-2">Name *</label>
                    <input
                      v-model="variant.name"
                      type="text"
                      required
                      class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Price Modifier *</label>
                    <input
                      v-model.number="variant.price_modifier"
                      type="number"
                      step="0.01"
                      required
                      class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Size</label>
                    <input
                      v-model="variant.size"
                      type="text"
                      class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50"
                    />
                  </div>
                </div>
                <div class="flex items-center space-x-3">
                  <input
                    type="checkbox"
                    :id="`variant-default-${index}`"
                    v-model="variant.is_default"
                    class="w-5 h-5 rounded cursor-pointer"
                  />
                  <label :for="`variant-default-${index}`" class="cursor-pointer">
                    Set as default variant
                  </label>
                </div>
              </div>
            </div>

            <!-- Add-ons -->
            <div class="space-y-6">
              <div class="flex justify-between items-center border-b border-white/20 pb-2">
                <h3 class="text-xl font-bold">Add-ons</h3>
                <button
                  type="button"
                  @click="addAddOn"
                  class="glass-button px-4 py-2 rounded-lg text-sm"
                >
                  ➕ Add Add-on
                </button>
              </div>

              <div v-if="form.add_ons.length === 0" class="text-center py-4 text-white/50">
                No add-ons added
              </div>

              <div
                v-for="(addOn, index) in form.add_ons"
                :key="addOn.id || index"
                class="p-4 glass rounded-lg flex items-center gap-4"
              >
                <div class="flex-1 grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium mb-2">Name *</label>
                    <input
                      v-model="addOn.name"
                      type="text"
                      required
                      class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50"
                    />
                  </div>
                  <div>
                    <label class="block text-sm font-medium mb-2">Price *</label>
                    <input
                      v-model.number="addOn.price"
                      type="number"
                      step="0.01"
                      min="0"
                      required
                      class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50"
                    />
                  </div>
                </div>
                <button
                  type="button"
                  @click="removeAddOn(index)"
                  class="text-red-400 hover:text-red-300 px-4"
                >
                  ✕
                </button>
              </div>
            </div>

            <!-- Display Order -->
            <div>
              <label class="block text-sm font-medium mb-2">Display Order</label>
              <input
                v-model.number="form.display_order"
                type="number"
                min="0"
                class="glass-input w-full px-4 py-3 rounded-lg text-white placeholder-white/50 focus:outline-none focus:ring-2 focus:ring-white/50"
              />
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-white/20">
              <Link
                :href="route('admin.food')"
                class="glass-button px-6 py-3 rounded-lg"
              >
                Cancel
              </Link>
              <button
                type="submit"
                :disabled="processing"
                class="glass-button bg-green-500/30 hover:bg-green-500/40 px-6 py-3 rounded-lg font-semibold disabled:opacity-50"
              >
                {{ processing ? 'Updating...' : 'Update Menu Item' }}
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
  food: Object,
  categories: Array,
  errors: Object,
})

const imagePreview = ref(null)

const availableTags = ['spicy', 'vegetarian', 'vegan', 'gluten-free', 'dairy-free', 'halal', 'kosher', 'keto', 'healthy']
const availableAllergens = ['milk', 'eggs', 'fish', 'shellfish', 'tree nuts', 'peanuts', 'wheat', 'soybeans']

const form = useForm({
  name: props.food.name || '',
  type: props.food.type || '',
  description: props.food.description || '',
  picture: null,
  price: props.food.price || 0,
  category_id: props.food.category_id || null,
  availability_times: props.food.availability_times || [],
  tags: props.food.tags || [],
  calories: props.food.calories || null,
  protein: props.food.protein || null,
  carbs: props.food.carbs || null,
  fat: props.food.fat || null,
  allergens: props.food.allergens || [],
  is_available: props.food.is_available ?? true,
  is_featured: props.food.is_featured ?? false,
  display_order: props.food.display_order || 0,
  preparation_time: props.food.preparation_time || null,
  variants: props.food.variants?.map(v => ({
    id: v.id,
    name: v.name,
    price_modifier: parseFloat(v.price_modifier),
    size: v.size || '',
    is_default: v.is_default || false,
  })) || [],
  add_ons: props.food.add_ons?.map(a => ({
    id: a.id,
    name: a.name,
    price: parseFloat(a.price),
  })) || [],
})

const handleImageChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    form.picture = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const addVariant = () => {
  form.variants.push({
    name: '',
    price_modifier: 0,
    size: '',
    is_default: false,
  })
}

const removeVariant = (index) => {
  form.variants.splice(index, 1)
}

const addAddOn = () => {
  form.add_ons.push({
    name: '',
    price: 0,
  })
}

const removeAddOn = (index) => {
  form.add_ons.splice(index, 1)
}

const submit = () => {
  form.post(route('admin.food.update', props.food.id), {
    forceFormData: true,
  })
}

const processing = form.processing
</script>
