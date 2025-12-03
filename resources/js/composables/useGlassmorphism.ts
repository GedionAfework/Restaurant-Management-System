import { computed, ref } from 'vue'

export function useGlassmorphism(opacity = 0.1) {
  const baseStyle = computed(() => ({
    background: `rgba(255, 255, 255, ${opacity})`,
    backdropFilter: 'blur(10px)',
    WebkitBackdropFilter: 'blur(10px)',
    border: '1px solid rgba(255, 255, 255, 0.2)',
    boxShadow: '0 8px 32px 0 rgba(31, 38, 135, 0.37)'
  }))

  const darkStyle = computed(() => ({
    background: `rgba(0, 0, 0, ${opacity * 2})`,
    backdropFilter: 'blur(10px)',
    WebkitBackdropFilter: 'blur(10px)',
    border: '1px solid rgba(255, 255, 255, 0.1)',
    boxShadow: '0 8px 32px 0 rgba(0, 0, 0, 0.37)'
  }))

  return {
    baseStyle,
    darkStyle
  }
}

