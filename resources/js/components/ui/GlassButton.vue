<template>
  <button
    :type="type"
    :class="[
      'glass-button',
      'px-4 py-2 rounded-lg font-medium',
      'text-white',
      variant === 'primary' ? 'bg-red-500/30 hover:bg-red-500/40' : '',
      variant === 'secondary' ? 'bg-blue-500/30 hover:bg-blue-500/40' : '',
      variant === 'success' ? 'bg-green-500/30 hover:bg-green-500/40' : '',
      variant === 'danger' ? 'bg-red-600/30 hover:bg-red-600/40' : '',
      disabled ? 'opacity-50 cursor-not-allowed' : '',
      className
    ]"
    :disabled="disabled"
    @click="$emit('click', $event)"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
interface Props {
  type?: 'button' | 'submit' | 'reset'
  variant?: 'primary' | 'secondary' | 'success' | 'danger' | 'default'
  disabled?: boolean
  className?: string
}

withDefaults(defineProps<Props>(), {
  type: 'button',
  variant: 'default',
  disabled: false,
  className: ''
})

defineEmits<{
  click: [event: MouseEvent]
}>()
</script>

