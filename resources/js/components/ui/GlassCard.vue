<template>
  <div
    :class="[
      'glass-card',
      variant === 'strong' ? 'glass-card-strong' : '',
      variant === 'dark' ? 'glass-dark' : '',
      'p-6',
      rounded ? 'rounded-2xl' : 'rounded-lg',
      className
    ]"
    :style="customStyle"
  >
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  variant?: 'default' | 'strong' | 'dark'
  rounded?: boolean
  className?: string
  customStyle?: object
}

withDefaults(defineProps<Props>(), {
  variant: 'default',
  rounded: true,
  className: '',
  customStyle: () => ({})
})
</script>

<style scoped>
.glass-card {
  transition: all 0.3s ease;
}

.glass-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.5);
}
</style>

