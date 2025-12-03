<template>
  <Transition name="fade">
    <div
      v-if="message"
      :class="[
        'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-2xl max-w-md',
        type === 'success' ? 'bg-green-500/90 glass-card-strong' : 'bg-red-500/90 glass-card-strong',
        'text-white backdrop-blur-xl'
      ]"
    >
      <div class="flex items-center space-x-3">
        <div class="text-2xl">
          {{ type === 'success' ? '✅' : '❌' }}
        </div>
        <div class="flex-1">
          <p class="font-semibold">{{ message }}</p>
        </div>
        <button
          @click="close"
          class="text-white/80 hover:text-white text-xl"
        >
          ×
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const showMessage = ref(true)

const flashMessage = computed(() => {
  return page.props.flash?.success || page.props.flash?.error || null
})

const message = computed(() => {
  return showMessage.value ? flashMessage.value : null
})

const type = computed(() => {
  return page.props.flash?.success ? 'success' : 'error'
})

const close = () => {
  showMessage.value = false
}

// Watch for new flash messages
watch(flashMessage, (newVal) => {
  if (newVal) {
    showMessage.value = true
    // Auto-close after 5 seconds
    setTimeout(() => {
      showMessage.value = false
    }, 5000)
  }
}, { immediate: true })
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateX(100px);
}
</style>

